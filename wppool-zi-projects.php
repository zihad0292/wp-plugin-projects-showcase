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

 
define( 'WPPOOL_ZI_PROJECTS_VERSION', '1.0.0' ); 

define( 'WPPOOL_ZI_PROJECTS_TEXT_DOMAIN', 'wppool-zi-projects' );

define( 'WPPOOL_ZI_PROJECTS_PLUGIN', __FILE__ );

define( 'WPPOOL_ZI_PROJECTS_PLUGIN_BASENAME', plugin_basename( WPPOOL_ZI_PROJECTS_PLUGIN ) );

define( 'WPPOOL_ZI_PROJECTS_PLUGIN_NAME', trim( dirname( WPPOOL_ZI_PROJECTS_PLUGIN_BASENAME ), '/' ) );

define( 'WPPOOL_ZI_PROJECTS_PLUGIN_DIR', untrailingslashit( dirname( WPPOOL_ZI_PROJECTS_PLUGIN ) ) );
 
require_once WPPOOL_ZI_PROJECTS_PLUGIN_DIR . '/includes/functions.php';


/**
 * Registers post types
 */

function wppool_zi_projects_init() {
	
	wppool_zi_projects_register_post_types();
	
	do_action( 'wppool_zi_projects_init' );
}

add_action( 'init', 'wppool_zi_projects_init', 10, 0 );



