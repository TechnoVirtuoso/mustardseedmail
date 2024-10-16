<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div class="single-product-page">
	<div id="product" <?php wc_product_class('', $product); ?>>
		<?php
		$accordion = get_field_product($product->get_id(), "accordion");
		?>

		<div class="summary entry-summary">
			<div class="info">
				<h1 class="product_title ">
					<?php echo $product->get_name() ?>
				</h1>
				<?php
				// do_action('woocommerce_single_product_summary'); 
				?>
			</div>
			<?php if ($product->get_type() != "simple") {
				?>
				<div class="price">
					$<?php echo $product->get_price() ?>
				</div>
				<?php
			} ?>
			<div class="action">
				<?php if ($product->get_type() == "simple") { ?>
					<button>
						Subscription Only
					</button>
				<?php } else { ?>
					<button class="custom-add-to-cart" data-product-id="<?php echo $product->get_id(); ?>">
						<span class="simple">Add to Cart</span>
					</button>
				<?php } ?>
			</div>
			<div class="description">
				<?php echo $product->description ?>
			</div>
			<?php if($accordion) { ?>
				<div class="legal">
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
			<?php } ?>
		</div>

		<?php
		// do_action('woocommerce_before_single_product_summary');
		$images = get_product_all_images_by_hamza($product);
		?>
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
					<img class="image-open" src="<?php echo $image ?>" alt="">
				</div>
			<?php endforeach; ?>
		</div>
		</div>
		

		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		// do_action('woocommerce_after_single_product_summary');
		?>
	</div>
</div>

<div class="single-product-blocks">
	<?php 
		$cards = get_field('cards', $product->get_id()); 
		if($cards) {
			?>
				<section class="cards">
					<div class="cards-wrapper section-wrapper">
						<?php foreach ($cards as $key => $card) { ?>
							<div class="card">
								<div class="image">
									<img src="<?php echo $card["image"]["url"] ?>" alt="">
								</div>
								<div class="title"><?php echo $key + 1?>.
									<?php echo $card["title"] ?>
								</div>
								<div class="description">
									<?php echo $card["description"] ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</section>
			<?php
		}
	?>
</div>

<?php
// do_action('woocommerce_after_single_product'); 
?>

<script>
	$(document).ready(() => {
		$('.product-main-image').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			infinite: true,
			asNavFor: '.product-all-images'
		});
		$('.product-all-images').slick({
			slidesToShow: 8,
			slidesToScroll: 1,
			asNavFor: '.product-main-image',
			dots: false,
			infinite: true,
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
	jQuery(document).ready(function () {
		// Close all descriptions initially
		jQuery(".legal .accordion .item .description").hide();

		// Open the first item by default
		// jQuery(".accordion .item:first-child .description").slideDown(function () {
		//     jQuery(this).parent().addClass("active");
		// });

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