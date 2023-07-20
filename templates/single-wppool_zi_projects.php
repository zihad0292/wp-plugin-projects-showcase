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
        $post_categories = get_the_category();
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

        $preview_images = explode(';', $preview_images);
        $preview_images_full_size = explode(';', $preview_images_full_size);
?>

    <div class="single-post pb-5">
        <div class="single-header jumbotron jumbotron-fluid text-light pt-0 pb-2"> 
            <div class="container">
                 <!--Post Thumbnail  -->
                 <img src='<?php echo esc_url($thumbnail_url); ?>' alt="">
                <!-- Post Title -->
                <h1 class="display-6 fw-semibold text-dark mb-3 mt-4"><?php echo esc_html($post->post_title); ?></h1> 
                <!-- Post Category -->
                <?php if (!empty($post_categories)) : ?>
                    <div class="post-category">
                        <?php
                        foreach ( $post_categories as $category ) {
                            // Output the category name
                            echo '<span class="badge text-bg-primary mx-1 p-2 fw-normal">' . $category->name . '</span>';
                        }
                        ?>
                    </div>
                <?php endif; ?> 
            </div>
        </div>

        <div class="container mt-3">
            <!-- External URL --> 
            <?php if (!empty($external_url)) : ?>
                <div class="alert alert-success fw-normal fs-6 mb-5" role="alert">
                    <span class="alert-link">External URL:</span> <a href="<?php echo esc_url( $external_url ); ?>" target="_blank"><?php echo esc_html($external_url); ?></a>
                </div> 
            <?php endif; ?>
            <!-- Post Description -->
            <div class="highlight p-5 text-bg-light border border-secondary-subtle rounded">
                <div class="post-description">
                    <h4 class="fs-4 fw-semibold text-dark mb-3">Description:</h4> 
                    <?php echo wp_kses_post(wpautop($post->post_content)); ?>
                </div>
                <hr class="my-5 w-25">
                <?php if (!empty($preview_images)) : ?>
                    <div class="preview-images">
                        <h4 class="fs-4 fw-semibold text-dark text-center">Preview Images:</h4>
                        <p class="text-center">Click on any image to open in lightbox</p>
                        <div class="row mt-5">
                            <?php
                            foreach ( $preview_images as $index => $url ) { 
                                echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-3 mb-md-4"><div class="image-wrapper"><a data-lightbox="image" href="' . esc_url( $preview_images_full_size[$index] ) . '"><img class="card-img-top" src="' . esc_url( $url ) . '" alt=""></a></div></div>';
                            }
                            ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div> 
    </div>

<?php
    endwhile;
else :
    echo 'No posts found.';
endif;
wp_footer();