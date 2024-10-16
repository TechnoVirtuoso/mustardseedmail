<?php

function calculate_time_left($send_time)
{
    // Get the current timestamp
    $current_time = time();

    // Calculate the time difference in seconds
    $time_left = $send_time - $current_time;

    // Convert seconds to days, hours, minutes, and seconds
    $days_left = floor($time_left / (60 * 60 * 24));
    $hours_left = floor(($time_left % (60 * 60 * 24)) / (60 * 60));
    $minutes_left = floor(($time_left % (60 * 60)) / 60);
    $seconds_left = $time_left % 60;

    if ($days_left > 0) {
        return $days_left . " days left";
    } elseif ($hours_left > 0) {
        return $hours_left . " hours left";
    } elseif ($minutes_left > 0) {
        return $minutes_left . " minutes left";
    } else {
        return $seconds_left . " seconds left";
    }
}

function hs_manage_subscription_content()
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
                // Modify user meta according to the subscription ID
                // For example, update_user_meta($user_id, 'subscription_id', $subscription_id);

                // Get the order associated with the subscription
                $order = wc_get_order($subscription->get_parent_id());

                // Get the shipping information from the subscription
                $shipping_address = $subscription->get_address('shipping');

                $subscription_meta_array = get_user_meta($current_user_id, "subscription_" . $subscription_id);

                if ($subscription_meta_array) {
                    $subs[] = array(
                        'subscription_id' => $subscription_id,
                        'subscription_meta' => $subscription_meta_array[0],
                        'subscription_name' => $product->get_name(),
                        'status' => $subscription->get_status(),
                        'start_date' => $subscription->get_date('start'),
                        'end_date' => $subscription->get_date('end'),
                        'order_id' => $order->get_id(),
                        'shipping' => $shipping_address,
                    );
                }
            }
        } else {
            // Handle case when no active or on-hold subscriptions are found
        }
    }
    ?>
    <div class="subscriptions">
        <?php foreach ($subs as $index => $view_sub): ?>
            <?php if ($view_sub['subscription_meta']["isOneTime"])
                continue; ?>
            <div class="subscription" data-parent-id="<?php echo $view_sub['subscription_id'] ?>"
                subscription_meta='<?php echo json_encode($view_sub['subscription_meta']) ?>'
                subscription-addr='<?php echo json_encode($view_sub['shipping']) ?>'>
                <?php
                if ($view_sub['status'] === 'on-hold') {
                    ?>
                    <div class="activate-subscription">
                        <div class="title">Activate Your Subscription</div>
                        <div class="name"><?php echo $view_sub['subscription_name'] ?></div>
                        <button onclick="openAddressForm(
                                    '<?php echo $view_sub['subscription_id'] ?>',
                                    false
                                )">Activate</button>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="subscription-header">
                        <a class="title" href="">
                            Sending To:
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
                                <?php echo $view_sub['shipping']['city'] ?>, <?php echo $view_sub['shipping']['state'] ?>
                            </p>
                        </div>
                        <div class="post-code">
                            <p>
                                <?php echo $view_sub['shipping']['postcode'] ?>
                            </p>
                        </div>
                        <button onclick="openAddressForm(
                            <?php echo $view_sub['subscription_id'] ?>
                        )">Edit</button>
                        <a class="ms" href="/my-account/view-subscription/<?php echo $view_sub['subscription_id'] ?>/">Manage Subcsription</a>
                    </div>
                    <div class="all-products">
                        <?php $count = 0; ?>
                        <?php foreach ($view_sub['subscription_meta']['products'] as $index => $_product): ?>
                            <?php $product = wc_get_product($_product["id"]); ?>
                            <div class="product <?php echo $_product['is_delivered'] ? "delivered" : "" ?> " <?php if ($count >= 6)
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
                                    <div class="actions">
                                        <?php if (!$_product['processing']): ?>
                                            <button class="replace" onclick="openModal(
                                            <?php echo $view_sub['subscription_id'] ?>, 
                                            <?php echo $index ?>,
                                            'replace',
                                        )">Replace</button>
                                            <button class="remove" onclick="openModal(
                                            <?php echo $view_sub['subscription_id'] ?>, 
                                            <?php echo $index ?>,
                                            'remove',
                                        )">Remove</button>
                                        <?php else: ?>
                                            <button>Processing</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $count++; ?>
                        <?php endforeach; ?>
                        <div class="add-item" <?php if ($count >= 3)
                            echo 'style="display: none;"'; ?>
                            onclick="scrollToSection('post_card_exchange_account')">

                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 128 128">
                                <path
                                    d="M 64 6.0507812 C 49.15 6.0507812 34.3 11.7 23 23 C 0.4 45.6 0.4 82.4 23 105 C 34.3 116.3 49.2 122 64 122 C 78.8 122 93.7 116.3 105 105 C 127.6 82.4 127.6 45.6 105 23 C 93.7 11.7 78.85 6.0507812 64 6.0507812 z M 64 12 C 77.3 12 90.600781 17.099219 100.80078 27.199219 C 121.00078 47.499219 121.00078 80.500781 100.80078 100.80078 C 80.500781 121.10078 47.500781 121.10078 27.300781 100.80078 C 7.0007813 80.500781 6.9992188 47.499219 27.199219 27.199219 C 37.399219 17.099219 50.7 12 64 12 z M 64 42 C 62.3 42 61 43.3 61 45 L 61 61 L 45 61 C 43.3 61 42 62.3 42 64 C 42 65.7 43.3 67 45 67 L 61 67 L 61 83 C 61 84.7 62.3 86 64 86 C 65.7 86 67 84.7 67 83 L 67 67 L 83 67 C 84.7 67 86 65.7 86 64 C 86 62.3 84.7 61 83 61 L 67 61 L 67 45 C 67 43.3 65.7 42 64 42 z">
                                </path>
                            </svg>
                        </div>
                        <script>
                            function scrollToSection(sectionId) {
                                var section = document.getElementById(sectionId);
                                if (section) {
                                    var offset = 200; // Adjust the offset as needed
                                    var topPos = section.offsetTop - offset;
                                    window.scrollTo({ top: topPos, behavior: 'smooth' });
                                }
                            }
                        </script>

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
                    <?php endif;
                } ?>


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
                                        )">
                                        select
                                    </button>
                                    <a href="<?php echo $product->get_permalink(); ?>">view in store</a>
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
    <div id="addressFormModal" class="address-form-modal">
        <div class="modal-content">
            <div class="head">
                <h2>Recipient's Shipping Address</h2>
                <span class="close" onclick="closeAddressFormModal()">&times;</span>
            </div>
            <form id="addressForm" onsubmit="updateAddress(event)">
                <!-- Add input fields for the address -->
                <div class="field">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="field">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="field">
                    <label for="address_1">Address 1:</label>
                    <input type="text" id="address_1" name="address_1" required>
                </div>

                <div class="field">
                    <label for="address_2">Address 2:</label>
                    <input type="text" id="address_2" name="address_2">
                </div>

                <div class="field">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="field">
                    <label for="state">State:</label>
                    <input type="text" id="state" name="state">
                </div>

                <div class="field">
                    <label for="postcode">Zip Code:</label>
                    <input type="text" id="postcode" name="postcode" required>
                </div>

                <!-- Add more input fields for other address details -->

                <button class="address-form-button" type="submit">Update Address</button>
            </form>
        </div>
    </div>
    <div class="add-subscription">
        <a href="/shop">Buy Another Subscription</a>
    </div>
    </div>

    <script>
        const getCopy = (data) => JSON.parse(JSON.stringify(data));
        const getMeta = (subscriptionId) => {
            // Get the button element
            var sub = document.querySelector('.subscription[data-parent-id="' + subscriptionId + '"]');
            // Get the subscription meta attribute value from the button
            var subscriptionMeta = sub.getAttribute('subscription_meta');
            // Parse the subscription meta value
            var parsedSubscriptionMeta = JSON.parse(subscriptionMeta);
            return parsedSubscriptionMeta
        }
        function openModal(subscriptionId, productIndex, condition) {
            // Get the subscription ID and product index
            console.log("Subscription ID: " + subscriptionId);
            console.log("Product Index: " + productIndex);

            let meta = getMeta(subscriptionId)

            console.log(subscriptionId, productIndex)
            if (condition == 'replace') {
                openModalToReplace(subscriptionId, productIndex);
            } else if (condition == 'remove') {
                processRemove(subscriptionId, productIndex, meta);
            }
        }
        const getNewDate = (time, addDays) => {
            let newDate = new Date((time * 1000) + (addDays * 24 * 60 * 60 * 1000));
            let newTime = newDate.getTime();
            newTime = Math.floor(newTime / 1000)
            return newTime
        }
        const processRemove = (subscriptionId, productIndex, meta) => {
            let prod_to_remove = meta.products[productIndex]
            let send_time = prod_to_remove.send_time
            meta.products = meta?.products.map((prod, i) => {
                if (i === productIndex) {
                    return null
                } else if (i >= productIndex) {
                    prod.send_time = send_time
                    send_time = getNewDate(send_time, 10)
                    return prod
                } else {
                    return prod
                }
            }).filter(x => x)
            try {
                updateMeta(subscriptionId, meta)
            } catch (error) {
                console.log(error)
            }
        }
        const openModalToAdd = (subscriptionId) => {
            $('.modify-subscription-modal').css('opacity', '0').fadeTo(400, 1, function () {
                $(this).css('display', 'grid');
            })
                .attr('data-subscription-id', subscriptionId)
                .attr('data-product-index', "")
                .attr('data-status', "add");
        }
        const openModalToReplace = (subscriptionId, productIndex) => {
            $('.modify-subscription-modal').css('opacity', '0').fadeTo(400, 1, function () {
                $(this).css('display', 'grid');
            })
                .attr('data-subscription-id', subscriptionId)
                .attr('data-product-index', productIndex)
                .attr('data-status', "replace");
        }
        const closeModal = () => {
            $('.modify-subscription-modal').fadeOut()
                .removeAttr('data-subscription-id')
                .removeAttr('data-product-index')
                .removeAttr('data-status');
        }

        const onModalProductAdd = (product_id, product_name) => {
            const status = $('.modify-subscription-modal').attr('data-status');
            console.log(product_id, product_name)
            if (status == "replace") {
                processReplace(product_id, product_name)
            } else {
                processAdd(product_id, product_name)
            }
        }
        const processAdd = (product_id, product_name) => {
            const subscriptionId = $('.modify-subscription-modal').attr('data-subscription-id');
            const meta = getMeta(Number(subscriptionId))

            meta?.products?.push({
                id: product_id,
                name: product_name,
                send_time: getNewDate(meta?.products[meta?.products?.length - 1].send_time, 10),
                processing: 0,
                is_delivered: 0
            })
            updateMeta(subscriptionId, meta)
        }
        const processReplace = (product_id, product_name) => {
            const productIndex = $('.modify-subscription-modal').attr('data-product-index');
            const subscriptionId = $('.modify-subscription-modal').attr('data-subscription-id');
            let meta = getMeta(subscriptionId)
            meta.products = meta?.products?.map((p, i) => {
                if (i == productIndex) {
                    return ({
                        id: product_id,
                        name: product_name,
                        send_time: p.send_time,
                        processing: 0,
                        is_delivered: 0
                    })
                } return p
            })
            updateMeta(subscriptionId, meta)
        }


        const updateMeta = (sub_id, meta) => {
            return new Promise((res, rej) => {
                const data = {
                    key: `subscription_${sub_id}`,
                    meta: meta
                }
                // Send the AJAX request
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX endpoint URL
                    type: 'POST',
                    data: {
                        action: 'update_subscription_meta', // Custom AJAX action name
                        data: JSON.stringify(data)
                    },
                    success: function (response) {
                        // Handle the server response
                        console.log(response.data);
                        location.reload();
                        res(true)
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors
                        console.error('Error updating subscription meta:', error);
                        rej(false)
                    }
                });
            })
        }
    </script>
    <script>
        const getAddress = (subscriptionId) => {
            // Get the button element
            var sub = document.querySelector('.subscription[data-parent-id="' + subscriptionId + '"]');
            // Get the subscription Addr attribute value from the button
            var subscriptionAddr = sub.getAttribute('subscription-addr');
            // Parse the subscription Addr value
            var parsedSubscriptionAddr = JSON.parse(subscriptionAddr);
            return parsedSubscriptionAddr
        }
        function openAddressForm(subscriptionId, init = true) {
            // Display the address form modal
            const shippingData = getAddress(subscriptionId)

            if (init) {
                // Loop through each property in the shipping data and update the corresponding form field
                for (const field in shippingData) {
                    if (shippingData.hasOwnProperty(field)) {
                        // Construct the ID of the form field based on the property name
                        const fieldId = `#${field}`;

                        // Update the form field with the corresponding value from shippingData
                        $(fieldId).val(shippingData[field]);
                    }
                }
                $("#addressFormModal .address-form-button").text("Update Address")
            } else {
                for (const field in shippingData) {
                    if (shippingData.hasOwnProperty(field)) {
                        // Construct the ID of the form field based on the property name
                        const fieldId = `#${field}`;

                        // Update the form field with the corresponding value from shippingData
                        $(fieldId).val('');
                    }
                }
                $("#addressFormModal .address-form-button").text("Activate Subscription")
            }

            $('#addressFormModal')
                .css('display', 'grid')
                .attr('data-subscription-id', subscriptionId) // Attach subscription ID as an attribute
            
                document.documentElement.style.overflowY = 'hidden';
        }

        function closeAddressFormModal() {
            // Close the address form modal and remove the subscription ID attribute
            $('#addressFormModal')
                .css('display', 'none')
                .removeAttr('data-subscription-id')
                .removeAttr('data-subscription-addr');
            document.documentElement.style.overflowY = '';
        }
        function updateAddress(event, updateStatus) {
            // Prevent the form from submitting
            event.preventDefault();

            // Get the subscription ID from the form modal
            const subscriptionId = $('#addressFormModal').data('subscription-id');


            const updatedData = {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                address_1: $('#address_1').val(),
                address_2: $('#address_2').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                postcode: $('#postcode').val(),
                country: $('#country').val(),
                // Add other fields as needed
            };

            // Perform the AJAX request to update the address
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX endpoint URL
                type: 'POST',
                data: {
                    action: 'update_subscription_address',
                    subscriptionId: subscriptionId,
                    updatedData: JSON.stringify(updatedData),
                },
                success: function (response) {
                    // Handle the server response
                    console.log(response);
                    activateSubscription(subscriptionId)
                    // Optionally, close the form modal or perform additional actions
                    closeAddressFormModal();
                },
                error: function (xhr, status, error) {
                    // Handle any errors
                    console.error('Error updating address:', error);
                }
            });
        }

        function activateSubscription(subscriptionId) {
            // Perform the AJAX request to update the address
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX endpoint URL
                type: 'POST',
                data: {
                    action: 'update_subscription_status',
                    subscriptionId: subscriptionId,
                    status: "active"
                },
                success: function (response) {
                    // Handle the server response
                    console.log(response);
                    location.reload();
                },
                error: function (xhr, status, error) {
                    // Handle any errors
                    console.error('Error updating address:', error);
                }
            });
        }
    </script>
    <?php
}
?>