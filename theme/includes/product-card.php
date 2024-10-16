<?php

function product_card($product_id, $button = "none", $quick = false)
{
	$product = wc_get_product($product_id);

	$product_type_class = $product->is_type('subscription') ? 'subscription-product' : 'simple-product';
	$product_price_html = $product->get_type() !== 'simple' ? '<div class="price">$' . $product->get_price() . '</div>' : '';

	?>
	<div class="global-product-card 
		<?php
			echo esc_attr($product_type_class);
			if ($quick) {
				echo " quickView quick-view-popup-btn";
			}
		?>" 
		data-id="<?php echo esc_attr($product_id); ?>">
		<div class="global-product-card-wrapper">
			<div class="image">
				<?php if (!$quick) { ?>
					<a href="<?php echo esc_url(get_permalink($product_id)); ?>">
					<?php } ?>
					<?php echo get_the_post_thumbnail($product_id); ?>
					<?php if (!$quick) { ?>
					</a>
				<?php } ?>
				<div class="quickView quick-view-popup-btn" data-id="<?php echo esc_attr($product_id); ?>">
					<span>Quick View</span>
				</div>
			</div>
			<div class="product-content">
				<?php if (!$quick) { ?>
					<a class="product-content-wrapper" href="<?php echo esc_url(get_permalink($product_id)); ?>">
					<?php } ?>
					<div class="global-product-card-info">
						<div class="global-product-card-title">
							<?php echo esc_html(get_the_title($product_id)); ?>
						</div>
						<?php echo $product_price_html; ?>
					</div>
					<?php if (!$quick) { ?>
					</a>
				<?php } ?>
				<?php
				switch ($button) {
					case 'view':
						if (!$quick) {
							?>
							<a class="button" href="<?php echo esc_url(get_permalink($product_id)); ?>">
								<span>Subscription Only</span>
							</a>
							<?php
						} else {
							?>
							<button class="button">
								<span><?php echo $quick ?></span>
							</button>
							<?php
						}
						break;
					case 'add':
						addToSubscription($product_id);
						break;
					default:
						break;
				}
				?>
			</div>
		</div>
	</div>
	<?php
}

