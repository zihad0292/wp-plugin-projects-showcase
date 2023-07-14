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
		add_action( 'admin_menu', array( $this, 'wppool_zi_projects_add_metabox' ) );
		add_action( 'save_post', array( $this, 'wppool_zi_projects_save_metabox' ) );
		add_action( 'save_post', array( $this, 'wppool_zi_projects_save_image' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wppool_zi_projects_admin_assets' ) );
	}

	function wppool_zi_projects_admin_assets() {
		wp_enqueue_style( 'wppool_zi_projects-admin-style', plugin_dir_url( __FILE__ ) . "assets/admin/css/style.css", null, time() );
		wp_enqueue_style( 'jquery-ui-css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', null, time() );
		wp_enqueue_script( 'wppool_zi_projects-admin-js', plugin_dir_url( __FILE__ ) . "assets/admin/js/main.js", array(
			'jquery',
		), time(), true );
	}


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

 
	function wppool_zi_projects_init() {
		
		wppool_zi_projects_register_post_types();
		
		do_action( 'wppool_zi_projects_init' );
	}

	function wppool_zi_projects_save_image($post_id){ 

		$image_id    = isset( $_POST['wppool_zi_projects_image_id'] ) ? $_POST['wppool_zi_projects_image_id'] : '';
		$image_url    = isset( $_POST['wppool_zi_projects_image_url'] ) ? $_POST['wppool_zi_projects_image_url'] : '';

		update_post_meta($post_id,'wppool_zi_projects_image_id',$image_id);
		update_post_meta($post_id,'wppool_zi_projects_image_url',$image_url);

	}

	function wppool_zi_projects_save_metabox( $post_id ) {

	}
	
	function wppool_zi_projects_add_metabox() {

		add_meta_box(
			'wppool_zi_projects_image_info',
			__( 'Image Info', 'our-metabox' ),
			array( $this, 'wppool_zi_projects_image_info' ),
			'wppool_zi_projects',
		);

	}

	function wppool_zi_projects_image_info($post) {
		$image_id = esc_attr(get_post_meta($post->ID,'wppool_zi_projects_image_id',true));
		$image_url = esc_attr(get_post_meta($post->ID,'wppool_zi_projects_image_url',true));
		wp_nonce_field( 'wppool_zi_projects_image', 'wppool_zi_projects_image_nonce' );

		$metabox_html = <<<EOD
<div class="fields">
	<div class="field_c">
		<div class="label_c">
			<label>Image</label>
		</div>
		<div class="input_c">
			<button class="button" id="upload_image">Upload Image</button>
			<input type="hidden" name="wppool_zi_projects_image_id" id="wppool_zi_projects_image_id" value="{$image_id}"/>
			<input type="hidden" name="wppool_zi_projects_image_url" id="wppool_zi_projects_image_url" value="{$image_url}"/>
			<div style="width:100%;height:auto;" id="image-container"></div>
		</div>
		<div class="float_c"></div>
	</div>
	
</div>
EOD;

		echo $metabox_html;

	}
}

new WppoolZiProjects();

