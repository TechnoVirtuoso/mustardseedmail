<div class="all_blogs">
    <?php $args = array(
        'post_type' => 'post',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,
        'taxonomy' => 'category',
    );

    $the_query  = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        $the_query->the_post();
        foreach ( $the_query->posts as $post ) {
            $post_slug = $post->post_name;
            $date = date("d M, Y",strtotime($post->post_date));
            ?>
            <div class="blog">
                <div class="wrap-featured-image"><?php echo get_the_post_thumbnail( $post->ID ); ?></div>

                <div class="content_wrap">
                    <div class="date"><?php echo $date ?></div>
                    <a class="post_title" href="<?php echo get_site_url() . "/posts//" . $post->post_name ?>"> <?php echo $post->post_title ?> </a>
                    
                </div>



            </div>
            <?php 
        }
        wp_reset_postdata(); // Reset the post data to the main query
    } else {
        echo 'No posts found';
    }
    ?>
</div>