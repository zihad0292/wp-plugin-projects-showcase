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
        // title
        $post_title = $post->post_title;
        // categories
        $post_categories = get_the_category();
        // description
        $post_content = $post->post_content;
        // Get the featured image URL
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');

        // project fallback thubmnail and header image.
        $plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) ); 
        $fallback_image_path = $plugin_dir_url . 'assets/images/archive-header.jpg';

        if(!$thumbnail_url){
            $thumbnail_url = $fallback_image_path;
        }

        // Custom Fields
        $external_url = get_post_meta($post_id, 'wppool_zi_projects_external_url', true);
        $preview_images = get_post_meta($post_id, 'wppool_zi_projects_images_url', true);
        $preview_images_full_size = get_post_meta($post_id, 'wppool_zi_projects_images_url_full_size', true);

		// convert the data to array for use in template
        $preview_images = explode(';', $preview_images);
        $preview_images_full_size = explode(';', $preview_images_full_size);

        // Load template part "content"
		$template_path = 'template-parts/content.php';
        include $template_path; 

    endwhile;
else :
    echo 'No posts found.';
endif;
wp_footer();
?>


