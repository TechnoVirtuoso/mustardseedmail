<?php

// Remove blog posts from Menu since we are not doing a blog
// function remove_posts_menu()
// {
// 	remove_menu_page('edit.php');
// }
// add_action('admin_menu', 'remove_posts_menu');

// Adds SVG support to media
function add_to_upload_mimes($upload_mimes)
{
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	$upload_mimes['ico'] = 'image/x-icon';
	return $upload_mimes;

}
add_filter('upload_mimes', 'add_to_upload_mimes', 10, 1);

// Register custom taxonomies
function add_custom_taxonomies()
{

	/*register_taxonomy('type', ['recipe', 'product'], array(
													'hierarchical' => true,
													'labels' => array(
													'name' => _x( 'Types', 'taxonomy general name' ),
													'singular_name' => _x( 'Type', 'taxonomy singular name' ),
													'search_items' =>  __( 'Search Types' ),
													'all_items' => __( 'All Types' ),
													'parent_item' => __( 'Parent Type' ),
													'parent_item_colon' => __( 'Parent Type:' ),
													'edit_item' => __( 'Edit Type' ),
													'update_item' => __( 'Update Type' ),
													'add_new_item' => __( 'Add New Type' ),
													'new_item_name' => __( 'New Type Name' ),
													'menu_name' => __( 'Types' ),
													),
													'rewrite' => array(
													'slug' => 'types',
													'with_front' => false, 
													'hierarchical' => true
													),
												));*/
}

add_action('init', 'add_custom_taxonomies', 0);


// Register Post Types
function create_customContentTypes()
{

	// Create Global Block content type
	register_post_type(
		'globalblock',
		array(
			'labels' => array(
				'name' => __('Global Blocks'),
				'singular_name' => __('Global Block'),
				'all_items' => __('All Global Blocks'),
				'view_item' => __('View Global Block'),
				'add_new' => __('Add New Global Block'),
				'add_new_item' => __('Add Global Block'),
				'edit_item' => __('Edit Global Block'),
				'update_item' => __('Update Global Block'),
				'search_items' => __('Search Global Blocks')
			),
			'menu_icon' => 'dashicons-admin-site',
			'public' => true,
			'publicly_queryable' => false,
			'has_archive' => true,
			'rewrite' => array('slug' => 'globalblock'),
			'supports' => array('title'),
		)
	);


	add_theme_support('post-thumbnails');

}
add_action('init', 'create_customContentTypes');

if (function_exists('acf_add_options_page')) {

	// Adds Theme Options Page
	acf_add_options_page(
		array(
			'page_title' => 'Theme General Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug' => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect' => false,
			'position' => '42',
		)
	);

	// Add Navigation Page
	acf_add_options_page(
		array(
			'page_title' => 'Navigation',
			'menu_title' => 'Navigation',
			'menu_slug' => 'navigation',
			'capability' => 'edit_posts',
			'redirect' => false,
			'position' => '40',
			'icon_url' => 'dashicons-menu-alt',

		)
	);
}





function compareByName($a, $b)
{
	return strcmp($a->name, $b->name);
}

function get_terms_by_post_type($taxonomies, $post_types)
{

	global $wpdb;

	$query = $wpdb->prepare(
		"SELECT t.*, COUNT(*) from $wpdb->terms AS t
      INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
      INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
      INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
      WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
      GROUP BY t.term_id",
		join("', '", $post_types),
		join("', '", $taxonomies)
	);

	$results = $wpdb->get_results($query);

	usort($results, 'compareByName');

	return $results;

}

add_post_type_support('page', 'excerpt');



function add_superandsubscript($buttons)
{
	array_push($buttons, 'superscript');
	array_push($buttons, 'subscript');
	return $buttons;
}
add_filter('mce_buttons', 'add_superandsubscript');


// disable xmlrpc
function remove_xmlrpc_methods($methods)
{
	return array();
}
add_filter('xmlrpc_methods', 'remove_xmlrpc_methods');

function mytheme_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');


function add_to_cart_ajax()
{
	// Get the product ID from the AJAX request
	$product_id = $_POST['product_id'];

	// Add the product to the cart
	WC()->cart->add_to_cart($product_id);

	// Get updated cart count
	$cart_count = WC()->cart->get_cart_contents_count();

	// Get updated cart HTML for cart items
	ob_start();
	?>
	<div class="shop_table-wrapper">
		<?php do_action('woocommerce_before_cart_contents'); ?>

		<?php
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			/**
			 * Filter the product name.
			 *
			 * @since 2.1.0
			 * @param string $product_name Name of the product in the cart.
			 * @param array $cart_item The product in the cart.
			 * @param string $cart_item_key Key for the product in the cart.
			 */
			$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>
				<div
					class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

					<div class="product-field product-thumbnail">
						<?php
						$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

						if (!$product_permalink) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
						}
						?>
					</div>

					<div class="product-field product-info">


						<div class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
							<?php
							if (!$product_permalink) {
								echo wp_kses_post($product_name . '&nbsp;');
							} else {
								/**
								 * This filter is documented above.
								 *
								 * @since 2.1.0
								 */
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
							}

							do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

							// Meta data.
							echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
				
							// Backorder notification.
							if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
							}
							?>
						</div>

						<div class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
							<?php
							echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
							?>
						</div>

						<div class="product-field product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
							<?php
							if ($_product->is_sold_individually()) {
								$min_quantity = 1;
								$max_quantity = 1;
							} else {
								$min_quantity = 0;
								$max_quantity = $_product->get_max_purchase_quantity();
							}

							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name' => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value' => $max_quantity,
									'min_value' => $min_quantity,
									'product_name' => $product_name,
								),
								$_product,
								false
							);

							echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
							?>
						</div>
					</div>

					<div class="product-field product-remove">
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url(wc_get_cart_remove_url($cart_item_key)),
								/* translators: %s is the product name */
								esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							),
							$cart_item_key
						);
						?>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php
	$cart_items_html = ob_get_clean();

	// Return updated cart count and cart items HTML
	wp_send_json_success(
		array(
			'cart_count' => $cart_count,
			'cart_items_html' => $cart_items_html,
			'total' => WC()->cart->get_total()
		)
	);

	// Don't forget to exit, as this is an AJAX request
	exit();
}
add_action('wp_ajax_add_to_cart', 'add_to_cart_ajax');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart_ajax'); // For non-logged in users




add_action('wp_ajax_quick-view-popup', 'quick_view_popup_callback');
add_action('wp_ajax_nopriv_quick-view-popup', 'quick_view_popup_callback'); // For non-logged in users


function quick_view_popup_callback()
{

	$product_id = $_REQUEST['id'];



	$product = wc_get_product($product_id);
	$images = get_product_all_images_by_hamza($product);
	ob_start();

	?>

	<div class="quick-view-popup">
		<div class="popup-wrapper">
			<div class="head">
				<div class="links">
					<?php $links = get_prev_next_products($product_id) ?>

					<?php if (isset($links["prev"])): ?>
						<div class="quickView quick-view-popup-btn" data-id="<?php echo esc_attr($links["prev"]->ID); ?>">
							<span>
								< prev</span>
						</div>
					<?php endif; ?>

					<?php if (isset($links["prev"]) && isset($links["next"])): ?>
						<span>|</span>
					<?php endif; ?>

					<?php if (isset($links["next"])): ?>
						<div class="quickView quick-view-popup-btn" data-id="<?php echo esc_attr($links["next"]->ID); ?>">
							<span>next ></span>
						</div>
					<?php endif; ?>
				</div>
				<button type="button" data-hook="close-quick-view" aria-label="Close" class="close-quick-view">
					<svg viewBox="0 0 32 32" fill="currentColor" width="20" height="32">
						<g fill="none" fill-rule="evenodd">
							<circle cx="16" cy="16" r="16"></circle>
							<path
								d="M18.7692308,4 L20,5.23076923 L13.23,12 L20,18.7692308 L18.7692308,20 L12,13.23 L5.23076923,20 L4,18.7692308 L10.769,12 L4,5.23076923 L5.23076923,4 L12,10.769 L18.7692308,4 Z"
								transform="translate(4 4)" fill="currentColor"></path>
						</g>
					</svg>
				</button>
			</div>
			<div class="product-popup-content">
				<div class="image-carousel">
					<div class="product-main-image">
						<?php foreach ($images as $image): ?>
							<div class="image">
								<img class="image-open" src="<?php echo $image ?>" alt="">
							</div>
						<?php endforeach; ?>
					</div>
					<div class="product-all-images">
						<?php foreach ($images as $image): ?>
							<div class="image">
								<img class="zoom-image" src="<?php echo $image ?>" alt="">
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="product-content">

					<div class="info">
						<div class="title">
							<?php echo $product->get_name() ?>
						</div>
					</div>

					<div class="action">
						<?php if ($product->get_type() == "simple") {
							if(is_user_logged_in()) {
								addToSubscription($product->get_id());
							} else {
								?>
									<button class="custom-add-to-cart">
										<span class="simple">Only Subscription</span>
									</button>
								<?php
							}
						} else { ?>

							<button class="custom-add-to-cart" data-product-id="<?php echo $product_id; ?>">
								<span class="simple">Add to Cart</span>
							</button>

						<?php } ?>
					</div>

					<button class="toggleDescription">View More Details</button>
					<div class="description">
						<?php echo $product->get_description() ?>
					</div>

					<div class="legal">
						<?php
						$accordion = get_field_product($product_id, "accordion");
						?>
						<div class="accordion">
							<?php foreach ($accordion as $item): ?>
								<div class="item">
									<div class="title">
										<span>
											<?php echo $item["title"] ?>
										</span>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
											<path class="arrowDown"
												d="M8.14644661,10.1464466 C8.34170876,9.95118446 8.65829124,9.95118446 8.85355339,10.1464466 L12.4989857,13.7981758 L16.1502401,10.1464466 C16.3455022,9.95118446 16.6620847,9.95118446 16.8573469,10.1464466 C17.052609,10.3417088 17.052609,10.6582912 16.8573469,10.8535534 L12.4989857,15.2123894 L8.14644661,10.8535534 C7.95118446,10.6582912 7.95118446,10.3417088 8.14644661,10.1464466 Z">
											</path>
										</svg>
									</div>
									<div class="description">
										<?php echo $item["description"] ?>
									</div>
								</div>
								<hr>
							<?php endforeach; ?>
						</div>
					</div>

				</div>
			</div>
			<script>
				// Quick View Product info Accordion Script
				jQuery(document).ready(function () {
					// Close all descriptions initially
					jQuery(".legal .accordion .item .description").hide();

					// Open the first item by default
					jQuery(".accordion .item:first-child .description").slideDown(function () {
						jQuery(this).parent().addClass("active");
					});

					// Click event handler
					jQuery(".legal .accordion .item").click(function () {
						const clickedDescription = jQuery(this).children(".description");

						if (clickedDescription.is(":visible")) {
							clickedDescription.slideUp(function () {
								jQuery(this).parent().removeClass("active");
							});
						} else {
							jQuery(".accordion .item .description").slideUp(function () {
								jQuery(this).parent().removeClass("active");
							});
							clickedDescription.slideDown(function () {
								jQuery(this).parent().addClass("active");
							});
						}
					});
				});
			</script>
			<script>
				$(document).ready(() => {
					$('.quick-view-popup .product-main-image').slick({
						slidesToShow: 1,
						slidesToScroll: 1,
						arrows: false,
						fade: true,
						asNavFor: '.product-all-images'
					});
					$('.quick-view-popup .product-all-images').slick({
						slidesToShow: 8,
						slidesToScroll: 1,
						asNavFor: '.product-main-image',

						dots: false,
						centerMode: false,
						focusOnSelect: true,
						responsive: [
							{
								breakpoint: 768, // Mobile breakpoint
								settings: {
									slidesToShow: 4 // Show 4 items on tablet
								}
							},
							{
								breakpoint: 480, // Tablet breakpoint
								settings: {
									slidesToShow: 3 // Show 3 items on mobile
								}
							}
						]
					});
				})
			</script>
			<script>
				jQuery(document).ready(function ($) {
					$(".quick-view-popup .toggleDescription").click(function () {
						var productDetails = $(this).closest(".quick-view-popup").find(".description");
						productDetails.toggle();
					});
				});
			</script>
		</div>
	</div>

	<?php

	$html = ob_get_clean();

	$output = array(
		"fragment" => $html,
	);

	wp_send_json_success($output, 200);


	exit();
}

// add_action("template_redirect", "redirection_by_hamza");
// function redirection_by_hamza()
// {
// 	if (is_home() || is_front_page()) {
// 		if (is_user_logged_in()) {
// 			wp_redirect("/my-account/");
// 			exit;
// 		}
// 	}
// }




?>