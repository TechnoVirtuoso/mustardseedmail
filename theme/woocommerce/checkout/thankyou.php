<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
// Retrieve subscription IDs associated with the order
$subscription_ids = wcs_get_subscriptions_for_order($order->get_id());
?>

<div class="woocommerce-order woocommerce-thankyou-order">

	<?php
	if ($order):

		do_action('woocommerce_before_thankyou', $order->get_id());
		?>

		<?php if ($order->has_status('failed')): ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
				<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
			</p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
					<?php esc_html_e('Pay', 'woocommerce'); ?>
				</a>
				<?php if (is_user_logged_in()): ?>
					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay">
						<?php esc_html_e('My account', 'woocommerce'); ?>
					</a>
				<?php endif; ?>
			</p>

		<?php else: ?>
			<div class="head">
				<div class="title">Thank You</div>
				<a href="/my-account">
					<p>Activate your subscription now</p>
					<span>(click here)</span>
				</a>
			</div>
			<?php
			// wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) );
			?>

		<?php endif; ?>
		<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
		<?php if ($subscription_ids): ?>
			<div class="sub">
				<table
					class="shop_table shop_table_responsive my_account_orders woocommerce-orders-table woocommerce-MyAccount-subscriptions woocommerce-orders-table--subscriptions">
					<thead>
						<tr>
							<th class="subscription-name ">
								<span class="nobr">Subscription</span>
							</th>
							<th class="subscription-order-no">
								<span class="nobr">Order No</span>
							</th>
							<th class="subscription-next-payment">
								<span class="nobr">Next payment</span>
							</th>
							<th class="subscription-total">
								<span class="nobr">Total</span>
							</th>
							<th class="subscription-actions">
								&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($subscription_ids as $subscription_id):
							$subscription = wcs_get_subscription($subscription_id); // Get subscription object
							$product_id = current($subscription->get_items())->get_product_id();
							$product = wc_get_product($product_id);
							$subscription_name = $product->get_name();
							$next_payment_date = $subscription->get_date('next_payment');
							$total_price = $subscription->get_total();
							?>
							<tr class="order woocommerce-orders-table__row woocommerce-orders-table__row--status-active">
								<td
									class="subscription-name order-name woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-name woocommerce-orders-table__cell-order-name"
									data-title="Subscription">
									<span>
										<?php echo esc_html($subscription_name); ?>
									</span>
								</td>
								<td class="subscription-order-no" data-title="order-no">
									<span>
										<?php echo $order->get_id(); ?>
									</span>
								</td>
								<td
									class="subscription-next-payment order-date woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-next-payment woocommerce-orders-table__cell-order-date"
									data-title="Next payment">
									<?php echo esc_html($next_payment_date); ?>
								</td>
								<td
									class="subscription-total order-total woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-total woocommerce-orders-table__cell-order-total"
									data-title="Total">
									<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>
										<?php echo esc_html($total_price); ?>
									</span>
								</td>
								<td
									class="subscription-actions order-actions woocommerce-orders-table__cell woocommerce-orders-table__cell-subscription-actions woocommerce-orders-table__cell-order-actions">
									<a href="/my-account"
										class="woocommerce-button button view">View</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<div class="ACTION" style="display:none;">
			<?php do_action('woocommerce_thankyou', $order->get_id()); ?>
		</div>

	<?php else: ?>

		<?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>

	<?php endif; ?>

</div>

<style>
	.woocommerce-customer-details {
		display: none;
	}
</style>