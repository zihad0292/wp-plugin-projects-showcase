<?php
/**
 * Custom Post Type Archive Template
 *
 * Template for displaying the archive of your custom post type.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Your_Theme
 */

wp_head(); ?>

<main id="primary" class="site-main">
    <div class="jumbotron jumbotron-fluid bg-secondary py-5 text-light">
        <div class="container">
            <h1 class="display-4">Portfolio projects</h1>
            <p class="lead">A well-crafted portfolio is a window into one's professional journey, showcasing a diverse array of projects that highlight skills, expertise, and creativity. Each project encapsulates a unique story of dedication and problem-solving, reflecting the passion and commitment of the individual.</p>
        </div>
    </div>
    <section class="content">
        <div class="container">
            <div class="row flex-xl-nowrap">
                <div class="col-12 col-md-3 col-xl-2 bd-sidebar"> 
                </div>
                <div class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content">
                    <div class="row projects-grid">
                    <?php if ( have_posts() ) : ?>
                        <?php
                        // Start the Loop.
                        while ( have_posts() ) :
                            the_post();

                            // Get the URL of the featured image (thumbnail).
                            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'wppool-zi-projects-thumb-square' );
                
                            ?>
                            <div class="card" style="width: 18rem;" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <?php
                                // Display the featured image (thumbnail) if it exists.
                                if ( $thumbnail_url ) {
                                    echo '<img class="card-img-top" src="' . esc_url( $thumbnail_url ) . '" alt="' . get_the_title() . '">';
                                } else {
                                    // Display the fallback image from the plugin directory.
                                    $plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) ); 
                                    $image_path = $plugin_dir_url . 'assets/images/fallback-image.jpg';  
                                    echo '<img style="display: block; width: 100%;" src="' . esc_url($image_path) . '" alt="Fallback Image">'; 
                                }
                                ?> 
                                <div class="card-body">
                                    <h5 class="card-title"><?php the_title(); ?></h5> 
                                    <?php
                                    // Display the external URL custom field. 
                                    $external_url = get_post_meta( get_the_ID(), 'wppool_zi_projects_external_url', true );
                                    if ( ! empty( $external_url ) ) {
                                        echo '<a class="btn btn-primary" href="' . esc_url( $external_url ) . '">Details</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        ?> 
                    </div><!-- row projects-grid -->                   
                    <?php endif; ?>
                </div><!-- col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content -->
            </div>
        </div>
    </section><!-- post-type-archive -->
</main><!-- #primary -->

<?php
wp_footer();