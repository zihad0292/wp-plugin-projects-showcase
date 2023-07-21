<?php

    // title
    $post_title = $post->post_title;
    // categories
    $post_categories = get_the_terms( $post_id, 'category' );
    // description
    $post_content = $post->post_content;

    // Get the featured image URL
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');

    // project fallback thubmnail and header image.
    $plugin_dir_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) ); 
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

?>

<div class="single-post pb-5">
    <div class="single-header jumbotron jumbotron-fluid text-light pt-0 pb-2"> 
        <div class="container">
            <div class="featured-image">
                <!--Post Thumbnail  -->
                <img class="d-block w-100" src='<?php echo esc_url($thumbnail_url); ?>' alt="">
            </div>
            <!-- Post Title -->
            <h1 class="display-6 fw-semibold text-dark mb-3 mt-4 pb-0"><?php echo esc_html($post_title); ?></h1> 
            <!-- Post Category -->
            <?php if (!empty($post_categories)) : ?>
                <div class="post-category">
                    <span class="text-dark">Category:</span> 
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
                <span class="alert-link">External URL:</span> <a href="<?php echo esc_url( sanitize_text_field( $external_url ) ); ?>" target="_blank"><?php echo esc_url( sanitize_text_field( $external_url )); ?></a>
            </div> 
        <?php endif; ?>
        <!-- Post Description -->
        <div class="highlight p-5 text-bg-light border border-secondary-subtle rounded">
            <div class="post-description">
                <h4 class="fs-4 fw-semibold text-dark mt-0 mb-3 pb-0">Description:</h4> 
                <?php echo wp_kses_post(wpautop($post_content)); ?>
            </div>
            <hr class="my-5 w-25 d-block m-auto">
            <?php if (!empty($preview_images)) : ?>
                <div class="preview-images">
                    <h4 class="fs-4 fw-semibold text-dark text-center mt-0 mb-2 pb-0">Preview Images:</h4>
                    <p class="text-center text-dark">Click on any image to open in lightbox</p>
                    <div class="row mt-5">
                        <?php
                        foreach ( $preview_images as $index => $url ) { 
                            echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-3 mb-md-4"><div class="image-wrapper"><a data-lightbox="image" href="' . esc_url( $preview_images_full_size[$index] ) . '"><img class="card-img-top d-block w-100" src="' . esc_url( $url ) . '" alt=""></a></div></div>';
                        }
                        ?>
                    </div> 
                </div>
            <?php endif; ?>
        </div>
    </div> 
</div>
