<?php
/**
 * Custom Post Type Archive Template
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

// project fallback thubmnail and header image.
$plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) ); 
 
?>

<!-- Modal -->
<div class="modal fade" id="single-post-modal" tabindex="-1" aria-labelledby="singlePostModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-6" id="singlePostModal">Modal title</h2>
        <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
      </div>
      <div class="modal-body">
        <div class="loader-wrapper">
            <div class="spinner-grow text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="content-wrapper container"></div>
      </div>
      <div class="modal-footer">
        <span href="#" class="btn btn-danger" data-bs-dismiss="modal">Close</span> 
      </div>
    </div>
  </div>
</div>

<main class="wppool-archive-wrapper">
    <div class="archive-header jumbotron jumbotron-fluid bg-secondary text-light">
        <div class="overlay"></div>
        <div class="container">
            <h1 class="display-4 fw-semibold">Portfolio projects</h1>
            <p class="lead mt-2">A well-crafted portfolio is a window into one's professional journey, showcasing a diverse array of projects that highlight skills, expertise, and creativity. Each project encapsulates a unique story of dedication and problem-solving, reflecting the passion and commitment of the individual.</p>
        </div>
    </div>
    <section class="content py-5">
        <div class="container">
            <div class="row flex-xl-nowrap">
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="sidebar-row mb-3">
                        <h4 class="fs-6 mb-3">Categories:</h4>
                        <div id="filter-buttons">
                            <?php
                            // check if there are any projects available
                            if ( have_posts() ){
                                echo '<a href="#" class="btn btn-secondary is-checked" data-filter="*">All</a>';
                                // Check if categories are available and loop through them
                                if ( ! is_wp_error( $all_categories ) && ! empty( $all_categories ) ) {
                                    foreach ( $all_categories as $category ) {
                                        // Output the category name and link
                                        echo '<a href="#" class="btn btn-secondary" data-filter=".' . $category->slug . '">' . $category->name . '</a>';
                                    }
                                } else {
                                    echo '<span class="fs-6 ps-3">No categories found!</span>';
                                }  
                            }else{
                                echo '<span class="fs-6 ps-3">No options available!</span>';
                            }
                            ?>    
                        </div><!-- #filter-buttons -->
                    </div><!-- sidebar-row -->
                    <div class="sidebar-row mb-5">
                        <h4 class="fs-6 mb-3">Sort By:</h4>
                        <?php
                        // check if there are any projects available
                        if ( have_posts() ){ ?>
                        <div id="sort-buttons">
                            <a href="#" class="btn btn-secondary is-checked" data-sort-by="original-order">Default</a><a href="#" class="btn btn-secondary" data-sort-by="category">Category</a><a href="#" class="btn btn-secondary" data-sort-by="title">Title</a>   
                        </div><!-- #sort-buttons -->
                        <?php
                        }else{
                            echo '<span class="fs-6 ps-2">No options available!</span>';
                        }
                        ?> 
                    </div><!-- sidebar-row -->
                </div>
                <div class="col-sm-12 col-md-9 col-lg-10">
                    <div class="wppool-grid-container">
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
                                if ( !$thumbnail_url ) {
                                    $thumbnail_url = $plugin_dir_url . 'assets/images/fallback-image.jpg';
                                }
                    
                                ?>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3 mb-md-4 wppool-loop-project element-item <?php echo $category_classes;?>" data-title="<?php the_title();?>" data-category="<?php echo ! empty( $category_names ) ? $category_names : "0";?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <div class="card ajax-load-post" data-post-id="<?php the_ID();?>" data-title="<?php the_title();?>" data-bs-toggle="modal" data-bs-target="#single-post-modal">
                                    <?php echo '<img class="card-img-top" src="' . esc_url( $thumbnail_url ) . '" alt="' . get_the_title() . '">'; ?> 
                                        <div class="card-body">
                                            <h5 class="card-title fs-5 fw-normal text-center"><?php the_title(); ?></h5>  
                                            <p class="fs-6 fw-medium text-center wppool-loop-project-category">
                                            <?php 
                                            // Check if categories are available and loop through them
                                            if ( ! is_wp_error( $post_categories ) && ! empty( $post_categories ) ) {
                                                foreach ( $post_categories as $category ) {
                                                    // Output the category name
                                                    echo '<span class="badge text-bg-secondary mx-1 p-2">' . $category->name . '</span>';
                                                }
                                            }   
                                            ?>  
                                            </p>  
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            ?> 
                        </div><!-- row projects-grid -->                   
                        <?php else: ?>
                            <p class="text-center fs-5">No projects found!</p>
                        <?php endif; ?>
                    </div><!-- wppool-grid-container -->                   
                </div><!-- col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content -->
            </div>
        </div>
    </section><!-- post-type-archive -->
    <div class="single-post-container"></div>
</main><!-- #primary -->

<?php
wp_footer();