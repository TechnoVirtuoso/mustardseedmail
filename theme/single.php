<?php get_header(); ?>

<?php
    // Get the current post ID
    $post_id = get_the_ID(); 
    // Get the author ID for the post
    $author_id = get_post_field('post_author', $post_id);
    // Get author's display name
    $author_name = get_the_author_meta('display_name', $author_id);
    // Get author's email
    $author_email = get_the_author_meta('user_email', $author_id);
    // Get author's avatar
    $author_avatar = get_avatar($author_email, 96); // You can adjust the size as needed
    // Get post reading time
    $reading_time = get_post_reading_time($post_id);
    // Get the post's published date and time
    $posted_at = get_the_time('F j, Y \a\t g:i a', $post_id);
    // Get the featured image URL
    $featured_image_url = get_the_post_thumbnail_url($post_id, 'large');
    // Description
    $description = get_field('description', $post->ID);
    // Get the post content
    $post_content = get_the_content();
    // Get the categories for the post
    $categories = get_the_category($post_id);
    // Get the number of comments for the post
    $comments_count = get_comments_number($post_id);
?>

<div class="single-post">
    <!-- <div class="profile-head">
        <div class="user">
            <div class="image">
                <?php echo $author_avatar ?>
            </div>
            <div class="name">.
                <?php echo $author_name ?>    
            </div>
            <div class="date">.
                <?php echo $posted_at ?>
            </div>
            <div class="read-time">.
                <?php echo $reading_time ?> min read
            </div>
        </div>
        <div class="actions"></div>
    </div> -->
    <div class="title"><?php echo $post->post_title ?></div>
    <div class="banner">
        <div class="image">
            <?php 
                if ($featured_image_url) {
                    echo '<img src="' . esc_url($featured_image_url) . '" alt="Featured Image">';
                } 
            ?>
        </div>
        <div class="description">
            <?php echo $description ?>
        </div>
    </div>
    <div class="post-content">
        <?php echo $post_content ?>
    </div>


    <!-- <div class="categories">
        <?php 
            // Check if categories exist for the post
            if ($categories) {
                echo '<ul>';
                foreach ($categories as $category) {
                    echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No categories found.</p>';
            }
        ?>
    </div>
    <hr class="up">
    <div class="links">
        <div class="facebook">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M13.1 6H15V3h-1.9A4.1 4.1 0 0 0 9 7.1V9H7v3h2v10h3V12h2l.6-3H12V6.6a.6.6 0 0 1 .6-.6h.5Z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="linkedin">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12.5 8.8v1.7a3.7 3.7 0 0 1 3.3-1.7c3.5 0 4.2 2.2 4.2 5v5.7h-3.2v-5c0-1.3-.2-2.8-2.1-2.8-1.9 0-2.2 1.3-2.2 2.6v5.2H9.3V8.8h3.2ZM7.2 6.1a1.6 1.6 0 0 1-2 1.6 1.6 1.6 0 0 1-1-2.2A1.6 1.6 0 0 1 6.6 5c.3.3.5.7.5 1.1Z" clip-rule="evenodd"/>
                <path d="M7.2 8.8H4v10.7h3.2V8.8Z"/>
            </svg>  
        </div>
        <div class="twitter">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M22 5.9c-.7.3-1.5.5-2.4.6a4 4 0 0 0 1.8-2.2c-.8.5-1.6.8-2.6 1a4.1 4.1 0 0 0-6.7 1.2 4 4 0 0 0-.2 2.5 11.7 11.7 0 0 1-8.5-4.3 4 4 0 0 0 1.3 5.4c-.7 0-1.3-.2-1.9-.5a4 4 0 0 0 3.3 4 4.2 4.2 0 0 1-1.9.1 4.1 4.1 0 0 0 3.9 2.8c-1.8 1.3-4 2-6.1 1.7a11.7 11.7 0 0 0 10.7 1A11.5 11.5 0 0 0 20 8.5V8a10 10 0 0 0 2-2.1Z" clip-rule="evenodd"/>
            </svg>           
        </div>
        <div class="copy-url">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.2 9.8a3.4 3.4 0 0 0-4.8 0L5 13.2A3.4 3.4 0 0 0 9.8 18l.3-.3m-.3-4.5a3.4 3.4 0 0 0 4.8 0L18 9.8A3.4 3.4 0 0 0 13.2 5l-1 1"/>
            </svg>
        </div>
    </div>
    <hr>
    <div class="actions">
        <div class="views"></div>
        <div class="comments"><?php echo $comments_count ?> Comments</div>
    </div> -->

 
</div>

<?php get_blocks() ?>

<?php get_footer();?>