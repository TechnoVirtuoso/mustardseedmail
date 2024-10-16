<?php
function addToSubscription($product_id)
{
	?>
	<div class="add-to-subscription-button">
		<button class="default">Add to subscription</button>
		<div class="possible-buttons">
			<?php getCurrentUserSubscriptions("active", function ($sub, $prod) use ($product_id) {
				$shipping_address = $sub->get_address('shipping');
				?>
				<button class="subscription" sub-id="<?php echo $sub->get_id() ?>" prod-id="<?php echo $product_id ?>">
					<?php
					echo $shipping_address["first_name"] . " " . $shipping_address["last_name"]
						?>
				</button>
				<?php
			}) ?>
		</div>
	</div>
	<?php
}
