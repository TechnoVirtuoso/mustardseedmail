<?php

function add_product_to_subscription()
{
  // Get the product ID from the AJAX request
  $product_id = $_POST['product_id'];
  $subscription_id = $_POST['subscription_id'];

  $user_id = get_current_user_id();

  // Update the subscription meta with the product ID
  $subscription_meta_array = get_user_meta($user_id, "subscription_" . $subscription_id, true);

  $last_subscription = end($subscription_meta_array["products"]);

  // Increment the send_time for the new subscription
  $new_send_time = $last_subscription['send_time'] + (86400 * 10); // Add 10 DAYS

  // get product
  $product = wc_get_product($product_id);

  // Create the new subscription
  $new_subscription = array(
    'id' => $product_id,
    'name' => $product->get_name(),
    'send_time' => $new_send_time,
    'processing' => 0,
    'is_delivered' => 0,
  );

  array_push($subscription_meta_array["products"], $new_subscription);

  // update meta
  update_user_meta($user_id, "subscription_" . $subscription_id, $subscription_meta_array);

  // Don't forget to exit, as this is an AJAX request
  exit();
}
add_action('wp_ajax_add_product_to_subscription', 'add_product_to_subscription');
// add_action('wp_ajax_nopriv_add_product_to_subscription', 'add_product_to_subscription'); // For non-logged in users