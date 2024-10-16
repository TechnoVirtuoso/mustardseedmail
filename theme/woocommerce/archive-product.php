<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');


?>

<div class="shop-page">
	<div class="subscriptions">
		<div class="title">Subscriptions</div>
		<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
		);

		$products = get_posts($args); ?>
		<div class="subscriptions-wrapper">
			<?php foreach ($products as $prod) {
				$product = wc_get_product($prod->ID);
				if (!$product->is_type('subscription'))
					continue; ?>
				<div class="subscription">
					<div class="image">
						<span class="tag">subscription</span>
						<a href="<?php echo $product->get_permalink() ?>">
							<img src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>" alt="">
						</a>
						<div class="quickView quick-view-popup-btn" data-id="<?php echo $prod->ID ?>">
							<span>Quick View</span>
						</div>
					</div>
					<div class="sub-content">
						<a class="name" href="<?php echo $product->get_permalink() ?>">
							<?php echo $product->get_name(); ?>
						</a>
						<a class="price" href="<?php echo $product->get_permalink() ?>">
							$<?php echo $product->get_price(); ?>
						</a>
						<button class="custom-add-to-cart" data-product-id="<?php echo $prod->ID; ?>">
							<span class="simple">Add to Cart</span>
							<span class="load">Loading</span>
						</button>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
	if (woocommerce_product_loop()) {
		woocommerce_product_loop_start();
		if (wc_get_loop_prop('total')) {
			while (have_posts()) {
				the_post();
				do_action('woocommerce_shop_loop');
				if (wc_get_product()->is_type('subscription')) {
					continue;
				}
				?>
				<div
					class="product <?php echo (wc_get_product()->is_type('subscription')) ? 'subscription-product' : 'simple-product'; ?>">
					<div class="image">
						<a href="<?php echo get_permalink(); ?>">
							<?php echo get_the_post_thumbnail() ?>
						</a>
						<div class="quickView quick-view-popup-btn" data-id="<?php echo get_the_ID() ?>">
							<span>Quick View</span>
						</div>
					</div>
					<div class="product-content">
						<a class="product-content-wrapper" href="<?php echo get_permalink(); ?>">
							<div class="info">
								<div class="title">
									<?php echo get_the_title() ?>
								</div>
								<?php if (wc_get_product()->get_type() != "simple") { ?>
									<div class="price">
										$
										<?php echo wc_get_product()->get_price() ?>
									</div>
								<?php } ?>
							</div>
						</a>
						<button>
							<span>Subscription only</span>
						</button>
					</div>
				</div>
				<?php
			}
		}

		woocommerce_product_loop_end();
	} else {
		do_action('woocommerce_no_products_found');
	}
	?>

	</div>

	<script>
		$(document).ready(() => {
			$('.shop-page .subscriptions .subscriptions-wrapper').slick({
				infinite: true,
				slidesToShow: 2,
				slidesToScroll: 1,
				prevArrow: `
			<button class="leftButton">
				<svg width="12px" height="24px" viewBox="0 0 12 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
					style="pointer-events: none;">
					<title>09 Icons / Arrows / Navigation / RegularArrow / Medium / Left / CenterAlign</title>
					<g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
						<g id="slider-gallery---arrows" transform="translate(-57.000000, -247.000000)" class="tI157n">
							<g id="🎨-Color" transform="translate(51.000000, 247.000000)">
								<polygon
									transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(90.000000) translate(-12.000000, -12.000000) "
									points="11.9989984 6 0 16.4818792 1.31408063 18 11.9989984 8.66778523 22.6859194 18 24 16.4818792">
								</polygon>
							</g>
						</g>
					</g>
				</svg>
			</button>
			`,
				nextArrow: `
			<button class="rightButton" style="rotate: 180deg">
				<svg width="12px" height="24px" viewBox="0 0 12 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
					style="pointer-events: none;">
					<title>09 Icons / Arrows / Navigation / RegularArrow / Medium / Left / CenterAlign</title>
					<g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
						<g id="slider-gallery---arrows" transform="translate(-57.000000, -247.000000)" class="tI157n">
							<g id="🎨-Color" transform="translate(51.000000, 247.000000)">
								<polygon
									transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(90.000000) translate(-12.000000, -12.000000) "
									points="11.9989984 6 0 16.4818792 1.31408063 18 11.9989984 8.66778523 22.6859194 18 24 16.4818792">
								</polygon>
							</g>
						</g>
					</g>
				</svg>
			</button>
			`,
				responsive: [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
						}
					},
					{
						breakpoint: 991,
						settings: {
							slidesToShow: 2,
						}
					},
					{
						breakpoint: 786,
						settings: {
							slidesToShow: 1,
						}
					}

				]

			});
		})
	</script>

</div>

<?php
get_footer('shop');
