<?php
function get_blocks()
{
	global $post;

	$fields = get_fields($post->ID);
	loop_blocks($fields);
}

function loop_blocks($blocks)
{
	if (isset($blocks['blocks'])) {
		if ($blocks['blocks']) {
			foreach ($blocks['blocks'] as $key => $block) {
				switch ($block['acf_fc_layout']) {
					case 'global_block':
						if ($block['global_block']) {
							$blocks = get_fields($block['global_block'][0]);
							loop_blocks($blocks);
						}
						break;
					case 'full_width_text':
						include 'blocks/full_width_text.php';
						break;
					case 'content_with_image':
						include 'blocks/content_with_image.php';
						break;
					case 'cards':
						include 'blocks/cards.php';
						break;
					case 'simple_button':
						include 'blocks/simple_button.php';
						break;
					case 'content':
						include 'blocks/content.php';
						break;
					case 'collection_carousel':
						include 'blocks/collection_carousel.php';
						break;
					case 'quotes':
						include 'blocks/quotes.php';
						break;
					case 'accordion_with_card':
						include 'blocks/accordion_with_card.php';
						break;
					case 'simple_content':
						include 'blocks/simple_content.php';
						break;
					case 'subscription_carousel':
						include 'blocks/subscription_carousel.php';
						break;
					case 'all_blogs':
						include 'blocks/all_blogs.php';
						break;
				}
			}
		}
	}
}

?>