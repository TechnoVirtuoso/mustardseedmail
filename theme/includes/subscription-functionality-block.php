<?php
function generate_subscription_meta($user_id, $subscription_id, $subscription_product_id)
{
    $meta_array = array();
    $products = get_field_product($subscription_product_id, "products");
    $isOneTime = get_field_product($subscription_product_id, "one_time");
    $current_timestamp = strtotime('+1 day');

    foreach ($products as $key => $product_id) {
        $product = wc_get_product($product_id);

        array_push(
            $meta_array,
            array(
                'id' => $product_id,
                'name' => $product->get_name(),
                'send_time' => $current_timestamp,
                'processing' => 0,
                'is_delivered' => 0,
            )
        );
        // Increment timestamp by 1 day for the next iteration
        $current_timestamp = strtotime('+10 day', $current_timestamp);
    }
    return array(
        'products' => $meta_array,
        'isOneTime' => $isOneTime
    );
}
function process_all_users_subscriptions()
{
    $customer_query = new WP_User_Query(
        array(
            'fields' => 'ID',
        )
    );
    $customers_ = $customer_query->get_results();
    foreach ($customers_ as $customer_id) {
        $customer = new WC_Customer($customer_id);
        ProcessSubscriptions($customer_id, $customer);
    }
}
function check_if_time_to_create_order($subscription_meta)
{
    $current_timestamp = time();
    foreach ($subscription_meta["products"] as $key => $product_meta) {
        // Check if the product is already processed
        if ($product_meta['processing'] == 0) {
            // Check if the product send time has been reached
            if ($product_meta['send_time'] <= $current_timestamp) {
                $subscription_meta["products"][$key]['processing'] = 1;
                // Update the is_delivered field for all previous products
                for ($i = 0; $i < $key; $i++) {
                    $subscription_meta["products"][$i]['is_delivered'] = 1;
                }
                return array($subscription_meta["products"][$key], $subscription_meta);
            }
        }
    }
    // If no products have reached their send time, return null
    return array(null, $subscription_meta);
}
function create_order($product_id, $user_id, $subscription_id, $coupon_code)
{
    // Get the subscription object
    $subscription = wcs_get_subscription($subscription_id);

    // Create a new order
    $order = wc_create_order();

    // Add the product to the order
    $quantity = 1;
    $order->add_product(wc_get_product($product_id), $quantity);

    // Set billing information from the subscription
    $billing_address = array(
        'first_name' => $subscription->billing_first_name,
        'last_name' => $subscription->billing_last_name,
        'address_1' => $subscription->billing_address_1,
        'address_2' => $subscription->billing_address_2,
        'city' => $subscription->billing_city,
        'state' => $subscription->billing_state,
        'postcode' => $subscription->billing_postcode,
        'country' => $subscription->billing_country,
        'email' => $subscription->billing_email,
        'phone' => $subscription->billing_phone,
    );

    $order->set_address($billing_address, 'billing');

    // Set shipping information from the subscription
    $shipping_address = array(
        'first_name' => $subscription->shipping_first_name,
        'last_name' => $subscription->shipping_last_name,
        'address_1' => $subscription->shipping_address_1,
        'address_2' => $subscription->shipping_address_2,
        'city' => $subscription->shipping_city,
        'state' => $subscription->shipping_state,
        'postcode' => $subscription->shipping_postcode,
        'country' => $subscription->shipping_country,
    );

    $order->set_address($shipping_address, 'shipping');
    // Apply the coupon to the order
    $order->apply_coupon($coupon_code);
    // Set the order status to "processing"
    $order->set_status('processing');
    // Calculate totals
    $order->calculate_totals();
    // Save the order
    $order_id = $order->save();
    // Output the order ID
    return $order_id;
}

function ProcessSubscriptions($user_id, $customer)
{

    $subscriptions = wcs_get_subscriptions(
        array(
            'customer_id' => $user_id,
        )
    );

    if ($subscriptions) {

        foreach ($subscriptions as $subscription) {
            // Extract information from the subscription
            $subscription_id = $subscription->get_id();
            $product_id = current($subscription->get_items())->get_product_id();

            // Modify user meta according to the subscription ID
            // For example, update_user_meta($user_id, 'subscription_id', $subscription_id);

            // Output information (you might want to replace this with your actual processing logic)

            $subscription_meta_array = get_user_meta($user_id, "subscription_" . $subscription_id)[0];
            if (array_key_exists('products', $subscription_meta_array)) {
                $data = check_if_time_to_create_order($subscription_meta_array);
                

                $product_to_order = $data[0];
                if ($product_to_order) {
                    create_order($product_to_order["id"], $user_id, $subscription_id, "zdhfag2a");
                }

                if ($data[1]) {
                    update_user_meta(
                        $user_id,
                        'subscription_' . $subscription_id,
                        $data[1] // new user meta
                    );
                }
            } 
        }
    } else {
        // Handle case when no active or on-hold subscriptions are found
    }
}


// Add this code to your theme's functions.php file or a custom plugin
function on_subscription_purchase($order_id)
{
    // Check if the order has subscription products
    if (wcs_order_contains_subscription($order_id)) {
        // Perform your custom actions here
        // For example, you can retrieve subscription information and update user meta
        $subscriptions = wcs_get_subscriptions_for_order($order_id);

        foreach ($subscriptions as $subscription) {
            $user_id = $subscription->get_user_id();
            $subscription_id = $subscription->get_id();
            send_custom_email($subscription_id, "Activate Subscription", 'get_activate_email_html');
            $product_id = current($subscription->get_items())->get_product_id();

            $subscription_meta = generate_subscription_meta($user_id, $subscription_id, $product_id);

            // Set Subscription Status to pending
            $subscription->update_status('on-hold');

            update_user_meta(
                $user_id,
                'subscription_' . $subscription_id,
                $subscription_meta
            );
        }
    }
}
add_action('woocommerce_thankyou', 'on_subscription_purchase');

function cron_job_scheduled_for_subscription()
{

    if (!wp_next_scheduled('subscription_scheduling_hook')) {
        // Schedule the event to run after 24 hours
        wp_schedule_event(time(), 'daily', 'subscription_scheduling_hook');
    }
}
add_action('wp', 'cron_job_scheduled_for_subscription');

add_action('subscription_scheduling_hook', 'process_all_users_subscriptions');




// Action for updating user subscription meta from front end using ajax
function update_subscription_meta()
{
    // Get the user ID and subscription meta data from the AJAX request
    $user_id = get_current_user_id();

    $json = mb_convert_encoding($_POST['data'], "UTF-8");
    $subscription = json_decode(stripslashes($json), true);

    if ($subscription === null && json_last_error() !== JSON_ERROR_NONE) {
        // JSON decoding failed
        wp_send_json_error('Error decoding subscription meta JSON: ' . json_last_error());
        return;
    }

    // Update the subscription meta for the user
    update_user_meta($user_id, $subscription["key"], $subscription["meta"]);

    // Return a response (optional)
    wp_send_json_success('Subscription meta updated successfully!');
}

add_action('wp_ajax_update_subscription_meta', 'update_subscription_meta');
add_action('wp_ajax_nopriv_update_subscription_meta', 'update_subscription_meta');


// Update subscription address AJAX action
add_action('wp_ajax_update_subscription_address', 'update_subscription_address_callback');
add_action('wp_ajax_nopriv_update_subscription_address', 'update_subscription_address_callback');

function update_subscription_address_callback()
{

    // Check if the request is coming from a valid AJAX call
    // check_ajax_referer('update_subscription_address_nonce', 'security');

    // Get subscription ID and updated data from the AJAX request
    $subscription_id = isset($_POST['subscriptionId']) ? sanitize_text_field($_POST['subscriptionId']) : '';
    $updated_data = isset($_POST['updatedData']) ? json_decode(stripslashes($_POST['updatedData']), true) : array();

    // Perform the necessary processing and update logic here
    // Check if subscription ID and data are valid
    if (!$subscription_id || empty($updated_data)) {
        wp_send_json_error(array('message' => 'Invalid data.'));
    }

    // Perform the necessary processing and update logic here
    $order = wc_get_order($subscription_id);

    // Check if the order exists
    if ($order) {
        // Update shipping address
        $order->set_shipping_first_name(sanitize_text_field($updated_data['first_name']));
        $order->set_shipping_last_name(sanitize_text_field($updated_data['last_name']));
        $order->set_shipping_address_1(sanitize_text_field($updated_data['address_1']));
        $order->set_shipping_address_2(sanitize_text_field($updated_data['address_2']));
        $order->set_shipping_city(sanitize_text_field($updated_data['city']));
        $order->set_shipping_state(sanitize_text_field($updated_data['state']));
        $order->set_shipping_postcode(sanitize_text_field($updated_data['postcode']));
        $order->set_shipping_country(sanitize_text_field($updated_data['country']));

        // Save the changes
        $order->save();

        // Return a success response
        wp_send_json_success(array('message' => 'Address updated successfully!'));
    } else {
        // Return an error response if the order is not found
        wp_send_json_error(array('message' => 'Order not found.'));
    }
}


// Update subscription status AJAX action
add_action('wp_ajax_update_subscription_status', 'update_subscription_status');
add_action('wp_ajax_nopriv_update_subscription_status', 'update_subscription_status');
function update_subscription_status() {
    // Check if the request is coming from a valid AJAX call

    // Get subscription ID and updated data from the AJAX request
    $subscription_id = isset($_POST['subscriptionId']) ? sanitize_text_field($_POST['subscriptionId']) : '';

    // Perform the necessary processing and update logic here
    // Check if subscription ID and data are valid
    if (!$subscription_id) {
        wp_send_json_error(array('message' => 'Invalid data.'));
    }

    $subscription = wcs_get_subscription($subscription_id);

    if($subscription->get_status() !== 'active') {
        $subscription->update_status('active');
    }

    wp_send_json_success(array('message' => 'Status updated successfully!'));
}
