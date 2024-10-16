<?php
$header = get_field("header", "option");
?>

<script>
    // Define custom events
    const openCartEvent = new Event('openCart');
    const closeCartEvent = new Event('closeCart');

    // Event listener for opening the cart
    document.addEventListener('openCart', function () {
        // Add overflow-y: hidden to the html element
        document.documentElement.style.overflowY = 'hidden';

        $(".cart-sidebar-page").css("display", "grid");
        $(".cart-sidebar-page").animate({
            opacity: 1
        }, 300);
        $('.cart-sidebar').animate({
            right: 0
        }, 300);
    });

    // Event listener for closing the cart
    document.addEventListener('closeCart', function () {
        $('.cart-sidebar').animate({
            right: -350
        }, 300);
        $(".cart-sidebar-page").animate({
            opacity: 0
        }, 300, () => {
            $(".cart-sidebar-page").css("display", "none")
        });

        // Remove overflow-y: hidden from the html element
        document.documentElement.style.overflowY = '';
    });

    // Trigger the events from any file
    function triggerOpenCartEvent() {
        document.dispatchEvent(openCartEvent);
    }

    function triggerCloseCartEvent() {
        document.dispatchEvent(closeCartEvent);
    }
</script>

<header class="site-header">
    <div class="top-bar">
        Share bite sized truth to challenge echo chambers.
    </div>
    <div class="logo-section section-wrapper">
        <div class="logo">
            <a href="/">
                <img src="<?php echo $header["logo"] ?>" alt="">
            </a>
        </div>
        <div class="actions">
            <div class="desktop">
                <a href="/my-account" class="login">
                    <?php
                    if (is_user_logged_in()) {
                        echo "Account";
                    } else
                        echo "Login";
                    ?>
                </a>
                <div class="cart" onclick="triggerOpenCartEvent()">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100%"
                        viewBox="0 0 329.7 134.5" data-hook="svg-icon-6">
                        <path
                            d="M74.1 134.4c-8.7 0-16.2-7.4-16.2-16.2S65.3 102 74.1 102s16.2 7.4 16.2 16.2-7.4 16.2-16.2 16.2zm0-21.7c-3.1 0-5.6 2.5-5.6 5.6s2.5 5.6 5.6 5.6 5.6-2.5 5.6-5.6-2.5-5.6-5.6-5.6zM120.5 134.5c-8.7 0-16.2-7.4-16.2-16.2s7.4-16.2 16.2-16.2 16.2 7.4 16.2 16.2-7.4 16.2-16.2 16.2zm0-21.7c-3.1 0-5.6 2.5-5.6 5.6s2.5 5.6 5.6 5.6c3.1 0 5.6-2.5 5.6-5.6s-2.5-5.6-5.6-5.6z">
                        </path>
                        <path
                            d="M141.2 92.1L53.5 92.1 23 10.6 0 10.6 0 0 30.4 0 61.2 81.6 133.5 81.6 152.4 30.4 38.5 30.4 34.8 19.9 167.9 19.9z">
                        </path>
                    </svg>
                    <span class="cart-count">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="nav">
        <a href="/#subscription-section">Buy Subscription</a>
        <span>|</span>
        <a href="/my-account" class="login">
            <?php
            if (is_user_logged_in()) {
                echo "Account";
            } else
                echo "Login";
            ?>
        </a>
    </div>
</header>

<div class="cart-sidebar-page">
    <div class="empty-space close-cart" onclick="triggerCloseCartEvent()"></div>
    <div class="cart-sidebar">
        <div class="cart-head">
            <button class="close-cart icon" onclick="triggerCloseCartEvent()">
                > </button>
            <span>Cart</span>
        </div>
        <form class="cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
            <?php do_action('woocommerce_before_cart_table'); ?>
            <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
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

                                    <div class="product-field product-quantity"
                                        data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
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
            </div>
            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>
        <div class="cart-foot">
            <div class="info">
                <div class="total">Total</div>
                <div class="value"><?php echo WC()->cart->get_total() ?></div>
            </div>
            <a href="/cart">View Cart</a>
        </div>
    </div>
</div>