<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;

?>
<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<h2>
		Order Summary
	</h2>

	<div cellspacing="0" class="shop_table shop_table_responsive">

		<div class="info-item cart-subtotal">
			<div class="title">
				<?php esc_html_e('Subtotal', 'woocommerce'); ?>
			</div>
			<div class="value">
				<?php wc_cart_totals_subtotal_html(); ?>
			</div>

		</div>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
			<div class="info-item cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<div class="title">
					<?php wc_cart_totals_coupon_label($coupon); ?>
				</div>
				<div class="value">
					<?php wc_cart_totals_coupon_html($coupon); ?>
				</div>

			</div>
		<?php endforeach; ?>

		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>
			<div class="info-item shipping">>
				<div class="title">
					<?php esc_html_e('Shipping', 'woocommerce'); ?>
				</div>
				<div class="value">
					<?php wc_cart_totals_shipping_html(); ?>
				</div>

			</div>
		<?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')): ?>
			<div class="info-item shipping">>
				<div class="title">
					<?php esc_html_e('Shipping', 'woocommerce'); ?>
				</div>
				<div class="value">
					<?php woocommerce_shipping_calculator(); ?>
				</div>

			</div>
		<?php endif; ?>

		<?php foreach (WC()->cart->get_fees() as $fee): ?>
			<div class="info-item fee">item">
				<div class="title">
					<?php echo esc_html($fee->name); ?>
				</div>
				<div class="value">
					<?php wc_cart_totals_fee_html($fee); ?>
				</div>

			</div>
		<?php endforeach; ?>

		<?php
		if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text = '';

			if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
				/* translators: %s location. */
				$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}

			if ('itemized' === get_option('woocommerce_tax_total_display')) {
				foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="info-item tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<div class="title">
							<?php echo esc_html($tax->label) . $estimated_text; ?>
						</div>
						<div class="value">
							<?php echo wp_kses_post($tax->formatted_amount); ?>
						</div>

					</div>
					<?php
				}
			} else {
				?>
				<div class="info-item tax-total">
					<div class="title">
						<?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?>
					</div>
					<div class="value">
						<?php wc_cart_totals_taxes_total_html(); ?>
					</div>

				</div>
				<?php
			}
		}
		?>

		<div class="info-item order-total">
			<div class="title">
				<?php esc_html_e('Total', 'woocommerce'); ?>
			</div>
			<div class="value">
				<?php wc_cart_totals_order_total_html(); ?>
			</div>

		</div>

	</div>

	<div class="wc-proceed-to-checkout">
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>