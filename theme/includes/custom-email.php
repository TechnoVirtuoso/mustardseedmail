<?php

function extractShippingEmail($meta_data) {
    $shipping_email = '';
    // Loop through the meta data
    foreach ($meta_data as $meta) {
        // Check if the meta key is 'shipping_email'
        if ($meta->key === 'shipping_email') {
            $shipping_email = $meta->value;
            break; // Exit the loop if the shipping_email is found
        }
    }
    // Output the shipping_email
    return $shipping_email;
}

function get_activate_email_html($subscription, $heading = false, $mailer)
{
    $subscription_id = $subscription->get_id();
    $name = $subscription->get_billing_first_name() . ' ' . $subscription->get_billing_last_name();
    $amount = $subscription->get_total();
    $shipping_address = $subscription->get_shipping_address_1() . ', ' . $subscription->get_shipping_city() . ', ' . $subscription->get_shipping_state() . ', ' . $subscription->get_shipping_postcode();
    $shipping_email = extractShippingEmail($subscription->get_meta_data());

    ob_start();
    ?>
    <div class="email-content">
        <h2>Order Details</h2>
        <p>Subscription ID: <?php echo $subscription_id; ?></p>
        <p>Name: <?php echo $name; ?></p>
        <p>Amount: <?php echo $amount; ?></p>
        <p>Shipping Address: <?php echo $shipping_address; ?></p>
        <p>Shipping Email: <?php echo $shipping_email; ?></p>
        <p>Is this your correct shipping info?</p>
        <a href="/my-account">Activate Subscription</a>
    </div>
    <?php
    $email_content = ob_get_clean();

    return apply_filters('woocommerce_mail_content', $email_content, $subscription, $heading, $mailer);
}

function get_not_activated_email_html($subscription, $heading = false, $mailer) {
    $subscription_id = $subscription->get_id();
    $name = $subscription->get_billing_first_name() . ' ' . $subscription->get_billing_last_name();
    $amount = $subscription->get_total();
    $shipping_address = $subscription->get_shipping_address_1() . ', ' . $subscription->get_shipping_city() . ', ' . $subscription->get_shipping_state() . ', ' . $subscription->get_shipping_postcode();
    $shipping_email = extractShippingEmail($subscription->get_meta_data());

    ob_start();
    ?>
    <!-- Subscription On-Hold Notification -->
    <div class="email-content">
        <div class="title">Subscription On Hold - Action Required</div>
        <p>Dear <?php echo $name; ?>,</p>
        <p>Your subscription with ID <?php echo $subscription_id; ?> is currently on hold and requires your attention.</p>
        <p>The total amount due is <?php echo $amount; ?>. Please activate your subscription to resume receiving your products.</p>
        <p>Shipping Address: <?php echo $shipping_address; ?></p>
        <p>Shipping Email: <?php echo $shipping_email; ?></p>
        <p>To activate your subscription, please visit your account page and follow the instructions there.</p>
        <p>If you have any questions or concerns, feel free to contact us.</p>
        <p>Thank you,</p>
    </div>
    <?php
    $email_content = ob_get_clean();

    return apply_filters('woocommerce_mail_content', $email_content, $subscription, $heading, $mailer);
}


function send_custom_email($subscription_id, $subject, $get_content)
{
    $subscription = wcs_get_subscription($subscription_id); // Get the subscription object based on the ID
    $mailer = WC()->mailer();
    $headers = "Content-Type: text/html\r\n";
    $subject = __($subject, 'theme_name');
    $content = $get_content($subscription, $subject, $mailer); // Pass the subscription object
    $recipient = extractShippingEmail($subscription->get_meta_data());  // Use the shipping email address
    $mailer->send($recipient, $subject, $content, $headers);
}
