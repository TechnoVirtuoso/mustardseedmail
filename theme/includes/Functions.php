<?php
function get_prev_next_products($current_product_id)
{
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'orderby' => 'ID',
		'order' => 'ASC'
	);

	$products = get_posts($args);
	$current_product_index = -1;

	foreach ($products as $index => $product) {
		if ($product->ID == $current_product_id) {
			$current_product_index = $index;
			break;
		}
	}

	$prev_product = null;
	$next_product = null;

	if ($current_product_index > 0) {
		$prev_product = $products[$current_product_index - 1];
	}

	if ($current_product_index < count($products) - 1) {
		$next_product = $products[$current_product_index + 1];
	}

	return array(
		'prev' => $prev_product,
		'next' => $next_product
	);
}
function get_post_reading_time($post_id) {
    // Get post content
    $post_content = get_post_field('post_content', $post_id);

    // Count words in post content
    $word_count = str_word_count(strip_tags($post_content));

    // Average reading speed (words per minute)
    $words_per_minute = 200; // Adjust as needed

    // Calculate reading time (in minutes)
    $reading_time = ceil($word_count / $words_per_minute);

    // Return reading time
    return $reading_time;
}


function get_prev_next_subscription_product_permalink($current_product_id)
{
    $subscription_products = get_subscription_products();

    // Retrieve product IDs using a loop
    $product_ids = array();
    foreach ($subscription_products as $product) {
        $product_ids[] = $product->get_id();
    }

    $current_key = array_search($current_product_id, $product_ids);

    $prev_product_id = null;
    $next_product_id = null;

    if ($current_key !== false) {
        if ($current_key > 0) {
            $prev_product_id = $subscription_products[$current_key - 1]->get_id(); // Access ID by index
        }

        if ($current_key < count($subscription_products) - 1) {
            $next_product_id = $subscription_products[$current_key + 1]->get_id(); // Access ID by index
        }
    }

    $prev_permalink = $prev_product_id ? get_permalink($prev_product_id) : null;
    $next_permalink = $next_product_id ? get_permalink($next_product_id) : null;

    return array(
        'prev' => $prev_permalink,
        'next' => $next_permalink
    );
}

function get_subscription_products()
{
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_subscription_product', // Custom meta key for identifying subscription products
                'value'   => 'yes', // Value for subscription products
                'compare' => '='
            )
        ),
        'orderby'        => 'ID', // Sort by product ID
        'order'          => 'ASC' // Order in ascending order
    );

    $query = new WC_Product_Query($args);
    $subscription_products = $query->get_products();

    return array_values(array_filter(
			array_map(function($prod) {
				if($prod->get_type() === 'subscription') return $prod;
			}, $subscription_products)
		));
}

function get_product_all_images_by_hamza($prod)
{
	$main_image_id = $prod->get_image_id();
	$gallery_image_ids = $prod->get_gallery_image_ids();

	$images = array();

	// Add main image to the array
	$main_image_url = wp_get_attachment_url($main_image_id);
	if ($main_image_url) {
		$images[] = $main_image_url;
	}

	// Add gallery images to the array
	foreach ($gallery_image_ids as $gallery_image_id) {
		$gallery_image_url = wp_get_attachment_url($gallery_image_id);
		if ($gallery_image_url) {
			$images[] = $gallery_image_url;
		}
	}
	return $images;
}
function get_field_product($product_id, $field_name)
{
	return get_field($field_name, $product_id);
}
function hprint($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
function getCurrentUserSubscriptions($status, $callBack)
{
	$current_user = wp_get_current_user();
	$subscriptions = wcs_get_subscriptions(
		array(
			'customer_id' => $current_user->ID,
			'status' => 'active' // You can change the status based on your requirements
		)
	);

	foreach ($subscriptions as $subscription) {
		// Access subscription data here
		$subscription_status = $subscription->get_status();
		$product_id = current($subscription->get_items())->get_product_id();
		$product = wc_get_product($product_id);
		if ($subscription_status == $status) {
			$callBack($subscription, $product);
		}
	}
}