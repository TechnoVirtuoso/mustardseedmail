<div class="collection-carousel section-wrapper">
    <div class="wrapper">
        <div class="main-title">
            <?php echo $block["title"] ?>
        </div>
        <div class="carousel">
            <?php foreach($block['collections'] as $product_id) {
                $product = wc_get_product($product_id);
                // Check if $product is a valid product object
                if($product && is_a($product, 'WC_Product_Simple')) {
                    product_card(
                        $product_id,
                        "view",
                        is_user_logged_in() ? "View" : false,
                    );
                }
            } ?>
        </div>
        <?php if(isset($block["button"]["title"])) { ?>
            <div class="button">
                <a href="<?php echo $block["button"]["url"] ?>">
                    <?php echo $block["button"]["title"] ?>
                </a>
            </div>
        <?php } ?>
    </div>
</div>