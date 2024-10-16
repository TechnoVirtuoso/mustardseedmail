<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
	return;
}

global $product;

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
		'woocommerce-product-gallery--columns-' . absint($columns),
		'images',
	)
);
$images = get_product_all_images_by_hamza($product);
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
	data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
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
</div>

<script>
	$(document).ready(() => {
		$('.product-main-image').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			infinate:true,
			asNavFor: '.product-all-images'
		});
		$('.product-all-images').slick({
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