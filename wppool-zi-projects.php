<?php
/**
 * Plugin Name: WPPool Projects By Zihad
 * Plugin URI: https://zihadsweb.com
 * Description: This plugin extends the functionality of WordPress by adding a custom post types called 'Projects'. It provides a projects archive page and individual project pages displayed using a modal window. Additionally, it incorporates sorting and filtering capabilities for projects on the archive page.
 * Version: 1.0.0
 * Author: Zihad Ul Islam
 * Author URI: https://zihadsweb.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wppool-zi-projects
 * Domain Path: /languages
 */
  
 require_once('includes/functions.php');

 class WppoolZiProjects {
	public function __construct() {  
		add_action( 'init', array( $this, 'wppool_zi_projects_init' ) );
		add_action( 'init', array( $this, 'wppool_zi_projects_register_image_size' ) ); 
		add_action( 'admin_menu', array( $this, 'wppool_zi_projects_add_metabox' ) );
		add_action( 'save_post', array( $this, 'wppool_zi_projects_save_metabox' ) );
		add_action( 'save_post', array( $this, 'wppool_zi_projects_save_preview_images' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wppool_zi_projects_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wppool_zi_projects_frontend_assets' ) );

		// Load templates
		add_filter('template_include', array( $this, 'wppool_zi_projects_archive_template' ));
		add_filter('template_include', array( $this, 'wppool_zi_projects_single_template' ));
 
		 // AJAX handler for loading single post content
		 add_action('wp_ajax_wppool_zi_projects_ajax_load_single_post_data', array($this, 'wppool_zi_projects_ajax_load_single_post_data'));
		 add_action('wp_ajax_nopriv_wppool_zi_projects_ajax_load_single_post_data', array($this, 'wppool_zi_projects_ajax_load_single_post_data'));
	
	}

	// Enqueue css and js files for admin
	function wppool_zi_projects_admin_assets() {
		// admin css
		wp_enqueue_style( 'wppool_zi_projects-admin-style', plugin_dir_url( __FILE__ ) . "assets/css/style.css", null, time() );
		// jquery ui css
		wp_enqueue_style( 'jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time() );
		// Custom js file for admin
		wp_enqueue_script( 'wppool_zi_projects-admin-js', plugin_dir_url( __FILE__ ) . "assets/js/admin.js", array(
			'jquery',
		), time(), true ); 
	}

	// Enqueue css and js files for frontend
	function wppool_zi_projects_frontend_assets() { 
		// frontend css
		wp_enqueue_style( 'wppool_zi_projects-frontend-style', plugin_dir_url( __FILE__ ) . "assets/css/frontend.css", null, time() );
		// bootstrap css
		wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', null, time() );
		// bootstrap js
		wp_enqueue_script( 'wppool_zi_projects-bootstrap-js', "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js", array(
			'jquery',
		), time(), true );
		// istope js for sorting and filtering
		wp_enqueue_script( 'wppool_zi_projects-isotope-js', "https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js", array(
			'jquery',
		), time(), true ); 
		// Custom js file for frontend
		wp_enqueue_script( 'wppool_zi_projects-frontend-js', plugin_dir_url( __FILE__ ) . "assets/js/frontend.js", array(
			'jquery',
		), time(), true ); 
		
        // Localize the script with custom data 
        $data = array( 
            'ajax_url' => admin_url('admin-ajax.php'),
			'security' => wp_create_nonce('wppool_zi_projects_load_single_post_by_ajax'),
        );

        wp_localize_script('wppool_zi_projects-frontend-js', 'ajax_data', $data);
		 
	}


	// Add security check for database operation
	private function is_secured( $nonce_field, $action, $post_id ) {
		$nonce = isset( $_POST[ $nonce_field ] ) ? $_POST[ $nonce_field ] : '';

		if ( $nonce == '' ) {
			return false;
		}
		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( wp_is_post_autosave( $post_id ) ) {
			return false;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return false;
		}

		return true;

	}

 
	// Register custom post type
	function wppool_zi_projects_init() {
		
		wppool_zi_projects_register_post_types();
		
		do_action( 'wppool_zi_projects_init' );
	}

	function wppool_zi_projects_register_image_size() { 
		add_image_size( 'wppool-zi-projects-thumb-square', 300, 300, true );
	}

	// Save metabox function
	function wppool_zi_projects_save_metabox( $post_id ) {

		if ( ! $this->is_secured( 'wppool_zi_projects_external_url_field', 'wppool_zi_projects_external_url', $post_id ) ) {
			return $post_id;
		}

		$external_url    = isset( $_POST['wppool_zi_projects_external_url'] ) ? $_POST['wppool_zi_projects_external_url'] : ''; 

		if ( $external_url == '' ) {
			return $post_id;
		}

		$external_url = sanitize_text_field( $external_url ); 

		update_post_meta( $post_id, 'wppool_zi_projects_external_url', $external_url ); 
	}

	// Save preivew images
	function wppool_zi_projects_save_preview_images($post_id){
		if ( ! $this->is_secured( 'wppool_zi_projects_preview_images_nonce', 'wppool_zi_projects_preview_images', $post_id ) ) {
			return $post_id;
		}

		$image_ids    = isset( $_POST['wppool_zi_projects_images_id'] ) ? $_POST['wppool_zi_projects_images_id'] : '';
		$image_urls    = isset( $_POST['wppool_zi_projects_images_url'] ) ? $_POST['wppool_zi_projects_images_url'] : '';
		$full_size_image_urls    = isset( $_POST['wppool_zi_projects_images_url_full_size'] ) ? $_POST['wppool_zi_projects_images_url_full_size'] : '';

		update_post_meta($post_id,'wppool_zi_projects_images_id', $image_ids);
		update_post_meta($post_id,'wppool_zi_projects_images_url', $image_urls);
		update_post_meta($post_id,'wppool_zi_projects_images_url_full_size', $full_size_image_urls);

	}
	
	// Wrapper function for add_meta_box WP functions
	function wppool_zi_projects_add_metabox() {
		add_meta_box(
			'wppool_zi_projects_external_url',
			__( 'External URL', 'wppool-zi-projects' ),
			array( $this, 'wppool_zi_projects_display_metabox' ),
			'wppool_zi_projects',
		); 

		add_meta_box(
			'wppool_zi_projects_preview_images',
			__( 'Preview Images', 'wppool-zi-projects' ),
			array( $this, 'wppool_zi_projects_preview_images' ),
			'wppool_zi_projects',
		);

	}

	// Display upload image button for Preview Images
	function wppool_zi_projects_preview_images($post) {
		$image_ids = esc_attr(get_post_meta($post->ID,'wppool_zi_projects_images_id',true));
		$image_urls = esc_attr(get_post_meta($post->ID,'wppool_zi_projects_images_url',true));
		$full_size_image_urls = esc_attr(get_post_meta($post->ID,'wppool_zi_projects_images_url_full_size',true));

		wp_nonce_field( 'wppool_zi_projects_preview_images', 'wppool_zi_projects_preview_images_nonce' );

		$label = __('Preview Images','wppool-zi-projects');
		$button_label = __('Upload Images','wppool-zi-projects');
		$metabox_html = <<<EOD
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label>{$label}</label>
		</div>
		<div class="input_c">
			<button class="button" id="upload_images">{$button_label}</button>
			<input type="hidden" name="wppool_zi_projects_images_id" id="wppool_zi_projects_images_id" value="{$image_ids}"/>
			<input type="hidden" name="wppool_zi_projects_images_url" id="wppool_zi_projects_images_url" value="{$image_urls}"/>
			<input type="hidden" name="wppool_zi_projects_images_url_full_size" id="wppool_zi_projects_images_url_full_size" value="{$full_size_image_urls}"/>
			<div id="images-container"></div>
		</div>
		<div class="float_c"></div>
	</div>
	
</div>
EOD;

		echo $metabox_html;

	}

	// Display textfield metabox for External URL
	function wppool_zi_projects_display_metabox( $post ) {
		$external_url    = get_post_meta( $post->ID, 'wppool_zi_projects_external_url', true );

		$label1 = __( 'External URL', 'wppool-zi-projects' );

		wp_nonce_field( 'wppool_zi_projects_external_url', 'wppool_zi_projects_external_url_field' );


		$metabox_html = <<<EOD
<p>
<label for="wppool_zi_projects_external_url">{$label1}: </label>
<input type="text" name="wppool_zi_projects_external_url" id="wppool_zi_projects_external_url" value="{$external_url}"/>
</p>
EOD;

		echo $metabox_html;
	}

	// archive template
	function wppool_zi_projects_archive_template($template) {
		if (is_post_type_archive('wppool_zi_projects')) {
			$child_template = locate_template('templates/archive-wppool_zi_projects.php');
	
			if ($child_template) {
				return $child_template;
			}
	
			// Fallback to the plugin's template
			return plugin_dir_path(__FILE__) . 'templates/archive-wppool_zi_projects.php';
		}
	
		return $template;
	}

	// single template
	function wppool_zi_projects_single_template($template) {
		if (is_singular('wppool_zi_projects')) {
			$child_template = locate_template('templates/single-wppool_zi_projects.php');
	
			if ($child_template) {
				return $child_template;
			}
	
			// Fallback to the plugin's template
			return plugin_dir_path(__FILE__) . 'templates/single-wppool_zi_projects.php';
		}
	
		return $template;
	}
	

    public function wppool_zi_projects_ajax_load_single_post_data() {
		// check nonce
		check_ajax_referer( 'wppool_zi_projects_load_single_post_by_ajax', 'security' );  

		if (isset($_POST['post_id'])) {
            // Get the post ID from the AJAX request
            $post_id = intval($_POST['post_id']);

            // Check if the post ID is valid
            if ($post_id) {
                // Get the post object using the post ID
                $post = get_post($post_id);

                // Check if the post exists
                if ($post) {
					// Prepare the post data to send as a response
					$response = array(
						'post_title' => $post->post_title,
						'post_content' => $post->post_content,  
					);

					// Get the featured image URL
					$featured_image_url = get_the_post_thumbnail_url($post_id, 'full'); 
		
					// Add the featured image URL to the response
					$response['featured_image'] = $featured_image_url;

					// Get custom fields data
					$external_url = get_post_meta($post_id, 'wppool_zi_projects_external_url', true); 
					$preview_images = esc_attr(get_post_meta($post_id,'wppool_zi_projects_images_url_full_size',true)); 
		
					// Add custom fields data to the response
					$response['external_url'] = $external_url;
					$response['preview_images'] = $preview_images;
		
					// Send the post data as a JSON response
					wp_send_json_success($response);
                } else {
                    echo 'Invalid post ID.';
                }
            } else {
                echo 'Invalid post ID.';
            }
        } else {
            echo 'Post ID not provided.';
        }
        wp_die();
    }

}

new WppoolZiProjects();

