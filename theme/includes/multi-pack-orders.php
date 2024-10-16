<?php

function hs_multi_pack_orders_content()
{
    global $product;
    $subs = array();
    // Get the current user ID
    $current_user_id = get_current_user_id();

    // Check if the user is logged in
    if ($current_user_id) {
        // Get user subscriptions
        $subscriptions = wcs_get_subscriptions(
            array(
                'customer_id' => $current_user_id,
            )
        );

        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                // Extract information from the subscription
                $subscription_id = $subscription->get_id();
                $product_id = current($subscription->get_items())->get_product_id();
                $product = wc_get_product($product_id);

                $subscription_meta_array = get_user_meta($current_user_id, "subscription_" . $subscription_id);

                // Get the order associated with the subscription
                $order = wc_get_order($subscription->get_parent_id());

                // Get the shipping information from the subscription
                $shipping_address = $subscription->get_address('shipping');


                if ($subscription_meta_array) {
                    $subs[] = array(
                        'subscription_id' => $subscription_id,
                        'subscription_meta' => $subscription_meta_array[0],
                        'subscription_name' => $product->get_name(),
                        'status' => $subscription->get_status(),
                        'start_date' => $subscription->get_date('start'),
                        'end_date' => $subscription->get_date('end'),
                        'order_id' => $order->get_id(),
                        'shipping' => $shipping_address
                    );
                }
            }
        } else {
            // Handle case when no active or on-hold subscriptions are found
        }
    }
    ?>
    <div class="subscriptions multi-pack-orders">
        <?php foreach ($subs as $index => $view_sub): ?>
            <?php if (!$view_sub['subscription_meta']["isOneTime"])
                continue; ?>
            <div class="subscription" data-parent-id="<?php echo $view_sub['subscription_id'] ?>"
                subscription_meta='<?php echo json_encode($view_sub['subscription_meta']) ?>'
                subscription-addr='<?php echo json_encode($view_sub['shipping']) ?>'>
                <div class="subscription-header">
                    <a class="title" href="/my-account/view-subscription/<?php echo $view_sub['subscription_id'] ?>/">
                        <?php echo $view_sub["subscription_name"] ?>
                    </a>
                </div>
                <div class="subscription-address">
                    <div class="name">
                        <p>
                            <?php echo $view_sub['shipping']["first_name"] . " " . $view_sub['shipping']["last_name"] ?>
                        </p>
                    </div>
                    <div class="address-1">
                        <p>
                            <?php echo $view_sub['shipping']['address_1'] ?>
                        </p>
                    </div>
                    <div class="address-2">
                        <p>
                            <?php echo $view_sub['shipping']['address_2'] ?>
                        </p>
                    </div>
                    <div class="city">
                        <p>
                            <?php echo $view_sub['shipping']['city'] ?>
                        </p>
                    </div>
                    <div class="state">
                        <p>
                            <?php echo $view_sub['shipping']['state'] ?>
                        </p>
                    </div>
                    <div class="post-code">
                        <p>
                            <?php echo $view_sub['shipping']['postcode'] ?>
                        </p>
                    </div>
                    <div class="country">
                        <p>
                            <?php echo $view_sub['shipping']['country'] ?>
                        </p>
                    </div>
                </div>
                <div class="all-products">
                    <?php $count = 0; ?>
                    <?php foreach ($view_sub['subscription_meta']['products'] as $index => $_product): ?>
                        <?php $product = wc_get_product($_product["id"]); ?>
                        <div class="product <?php echo $_product['is_delivered'] ? "delivered" : "" ?> " <?php if ($count >= 3)
                                    echo 'style="display: none;"'; ?>>
                            <div class="image">
                                <?php
                                $image_url = wp_get_attachment_url($product->get_image_id());
                                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
                                ?>
                                <?php if ($_product['is_delivered']) {
                                    echo "<img class='icon' src='/wp-content/uploads/2023/12/Greencheck.png.png' alt=''>";
                                }
                                ?>
                                <div class="quickView quick-view-popup-btn"
                                    data-id="<?php echo $product_id = $product->get_id(); ?>">
                                    <span>Quick View</span>
                                </div>
                            </div>
                            <div class="info">
                                <div class="left">
                                    <div class="shipping">
                                        <?php
                                        if ($_product['is_delivered']) {
                                            echo "Shipped";
                                        } else if ($_product['processing']) {
                                            echo "Processing";
                                        } else {
                                            echo "Shipping";
                                        }
                                        ?>
                                    </div>
                                    <?php if (!$_product['processing']) { ?>
                                        <div class="time-left">
                                            <?php
                                            $time_left = calculate_time_left($_product['send_time']);
                                            echo $time_left;
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php $count++; ?>
                    <?php endforeach; ?>
                    <div class="add-item" <?php if ($count >= 3)
                        echo 'style="display: none;"'; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 128 128">
                            <path
                                d="M 64 6.0507812 C 49.15 6.0507812 34.3 11.7 23 23 C 0.4 45.6 0.4 82.4 23 105 C 34.3 116.3 49.2 122 64 122 C 78.8 122 93.7 116.3 105 105 C 127.6 82.4 127.6 45.6 105 23 C 93.7 11.7 78.85 6.0507812 64 6.0507812 z M 64 12 C 77.3 12 90.600781 17.099219 100.80078 27.199219 C 121.00078 47.499219 121.00078 80.500781 100.80078 100.80078 C 80.500781 121.10078 47.500781 121.10078 27.300781 100.80078 C 7.0007813 80.500781 6.9992188 47.499219 27.199219 27.199219 C 37.399219 17.099219 50.7 12 64 12 z M 64 42 C 62.3 42 61 43.3 61 45 L 61 61 L 45 61 C 43.3 61 42 62.3 42 64 C 42 65.7 43.3 67 45 67 L 61 67 L 61 83 C 61 84.7 62.3 86 64 86 C 65.7 86 67 84.7 67 83 L 67 67 L 83 67 C 84.7 67 86 65.7 86 64 C 86 62.3 84.7 61 83 61 L 67 61 L 67 45 C 67 43.3 65.7 42 64 42 z">
                            </path>
                        </svg>
                    </div>


                </div>
                <?php if ($count > 2): ?>
                    <div class="see-more-product" onclick="showMoreProducts(this)">
                        <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 15.5L0.0407079 0.5L19.9593 0.500002L10 15.5Z" fill="#DB2222" />
                        </svg>
                        See More
                    </div>
                    <div class="see-less-product" onclick="showLessProducts(this)" style="display: none;">
                        <svg width="21" height="15" viewBox="0 0 21 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5 0L20.4593 15H0.540708L10.5 0Z" fill="#DB2222" />
                        </svg>
                        See Less
                    </div>
                <?php endif; ?>

                <!-- <div id="seeMoreBtn">See More</div> -->
            </div>
        <?php endforeach; ?>


        <div class="modify-subscription-modal" onclick="closeModal()">
            <div class="modal-content" onclick="event.stopPropagation();">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'product_type' => 'simple',
                );

                $products = new WP_Query($args);

                if ($products->have_posts()) {
                    while ($products->have_posts()) {
                        $products->the_post();
                        global $product;
                        if ($product->get_type() == "simple") {
                            $product_id = $product->get_id();
                            $product_name = get_the_title();
                            $product_image = get_the_post_thumbnail_url($product_id, 'full');
                            ?>
                            <div class="product" product-id="<?php echo $product_id ?>">
                                <div class="image">
                                    <img src="<?php echo $product_image ?>" alt="" />
                                </div>
                                <div class="info">
                                    <button onclick="onModalProductAdd(
                                            '<?php echo $product_id ?>',
                                            '<?php echo $product_name ?>'
                                        )">Select</button>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo 'No simple products found.';
                }

                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
    </div>
    <?php
}
?>