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

wp_head();  

 
$custom_post_type = 'wppool_zi_projects';

// Define arguments for get_posts()
$args = array(
    'post_type'      => $custom_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => -1, // -1 means fetch all posts (no pagination)
);

// Get posts of the custom post type
$posts = get_posts( $args );

// Extract post IDs and store them in an array
$post_ids_array = array();
foreach ( $posts as $post ) {
    $post_ids_array[] = $post->ID;
}

// Get the list of categories for the custom post type
$all_categories = get_terms( array(
    'taxonomy' => 'category',
    'object_ids' => $post_ids_array,
) ); 

// Custom sorting function for terms
function sort_terms_ascending($a, $b) {
    return strcasecmp($a->name, $b->name);
}
 
?>

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
                    <div class="sidebar-row">
                        <h4>Categories:</h4>
                        <div id="filter-buttons">
                            <button class="button is-checked" data-filter="*">All</button>
                            <?php
                            // Check if categories are available and loop through them
                            if ( ! is_wp_error( $all_categories ) && ! empty( $all_categories ) ) {
                                foreach ( $all_categories as $category ) {
                                    // Output the category name and link
                                    echo '<button class="button" data-filter=".' . $category->slug . '">' . $category->name . '</button><br>';
                                }
                            } else {
                                echo 'No categories found!';
                            }  
                            ?>    
                        </div><!-- #filter-buttons -->
                    </div><!-- sidebar-row -->
                    <div class="sidebar-row">
                        <h4>Sort Projects:</h4>
                        <div id="sort-buttons">
                            <button class="button is-checked" data-sort-by="original-order">Original Order</button>
                            <button class="button" data-sort-by="category">Category</button>
                            <button class="button" data-sort-by="title">Title</button>   
                        </div><!-- #sort-buttons -->
                    </div><!-- sidebar-row -->
                </div>
                <div class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content">
                    <div id="wppool-grid" class="row projects-grid">
                    <?php if ( have_posts() ) : ?>
                        <?php
                        // Start the Loop.
                        while ( have_posts() ) :
                            the_post();
 
                            // Get the current post ID
                            $post_id = get_the_ID();

                            // Get the categories assigned to the post
                            $post_categories = get_the_terms( $post_id, 'category' );
                            
                            $category_classes = "";
                            $category_names = "";
                            if ( ! is_wp_error( $post_categories ) && ! empty( $post_categories ) ) {

                                // Sort the terms in ascending order
                                usort($post_categories, 'sort_terms_ascending');
                                
                                foreach ( $post_categories as $cat ) {
                                    $category_classes .= $cat->slug . ' ';
                                    $category_names .= $cat->name . ' ';
                                }
                                // Remove the trailing comma and space at the end
                                // $category_names = rtrim($category_names, ', ');
                            } 

                            // Get the URL of the featured image (thumbnail).
                            $thumbnail_url = get_the_post_thumbnail_url( $post_id, 'wppool-zi-projects-thumb-square' );
                            // get the external URL custom field value. 
                            $external_url = get_post_meta( $post_id, 'wppool_zi_projects_external_url', true );
                
                            ?>
                            <div class="card element-item <?php echo $category_classes;?>" data-category="<?php echo ! empty( $category_names ) ? $category_names : "0";?>" data-title="<?php the_title();?>" style="width: 18rem;" id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-postID="<?php the_ID();?>">
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
                                    <p>Category: <?php echo ! empty( $category_names ) ? $category_names : "No category assigned"; ?></p>  
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