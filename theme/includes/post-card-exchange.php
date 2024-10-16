<?php

function post_card_exchange($extra_class = "")
{
	// Get all product categories
	$product_categories = get_terms(
		array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		)
	);

	$cat = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : false;

	// Assuming you have a custom taxonomy named 'product_category' for categorizing products
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
	);

	if ($cat !== false) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $cat,
			),
		);
	}

	$products = new WP_Query($args);

	?>
	<div id="post_card_exchange_account" class="post-card-exchange <?php echo $extra_class ?>">
		<div class="title">PostCard Library</div>
		<div class="post-card-exchange-wrapper">
			<div class="filters">
				<div class="title">Categories</div>
				<?php foreach ($product_categories as $cat): ?>
					<?php if($cat->slug == "uncategorized") continue; ?>
					<?php $active  = (isset($_GET['category']) && $cat->term_id == $_GET['category'] ? true : false) ?>
					<a class="filter" href="<?php echo esc_url(add_query_arg('category', $cat->term_id, get_permalink())); ?>">
						<input type="checkbox" <?php echo ($active ? "checked" : "")?>>
						<div
							class="name <?php echo ($active ? "active" : "") ?>">
							<?php echo $cat->name ?>
						</div>
					</a>
				<?php endforeach ?>
			</div>
			<div class="cards">
				<?php
				if ($products->have_posts()) {
					while ($products->have_posts()) {
						$products->the_post();
						$product = wc_get_product(get_the_ID());
						if ($product->get_type() == "subscription")
							continue;
						product_card(get_the_ID(), "add");
					}
					wp_reset_postdata(); // Reset the post data to the main query
				} else {
					echo 'No products found';
				}
				?>
			</div>
		</div>
	</div>
	 <script>
        jQuery(document).ready(function($) {
            // Add click event handler to checkboxes
            $('.filter input[type="checkbox"]').on('change', function() {
                // Trigger click event on corresponding anchor tag
                $(this).siblings('.name').click();
            });
        });
    </script>
	<?php
}