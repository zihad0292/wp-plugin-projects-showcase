<?php
/**
 * Custom Post Single Template  
 */

wp_head();  

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $post_id = get_the_ID();
        $post = get_post($post_id);

        // Load template part "content"
        include 'template-parts/content.php'; 

    endwhile;
else :
    echo 'No posts found.';
endif;
wp_footer();
?>


