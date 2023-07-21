<div class="single-post pb-5">
    <div class="single-header jumbotron jumbotron-fluid text-light pt-0 pb-2"> 
        <div class="container">
            <div class="featured-image">
                <!--Post Thumbnail  -->
                <img src='<?php echo esc_url($thumbnail_url); ?>' alt="">
            </div>
            <!-- Post Title -->
            <h1 class="display-6 fw-semibold text-dark mb-3 mt-4"><?php echo esc_html($post_title); ?></h1> 
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
                <h4 class="fs-4 fw-semibold text-dark mb-3">Description:</h4> 
                <?php echo wp_kses_post(wpautop($post_content)); ?>
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
