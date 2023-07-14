<?php

    /**
	 * Registers the post type for WPPool Projects.
	 */
	function wppool_zi_projects_register_post_types() {
		$labels = array(
            'name'               => 'WPPool Projects',
            'singular_name'      => 'WPPool Project',
            'menu_name'          => 'WPPool Projects', 
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New WPPool Project',
            'new_item'           => 'New WPPool Project',
            'edit_item'          => 'Edit WPPool Project',
            'view_item'          => 'View WPPool Project',
            'all_items'          => 'All WPPool Projects',
            'search_items'       => 'Search WPPool Projects',
            'parent_item_colon'  => 'Parent WPPool Projects:',
            'not_found'          => 'No WPPool Projects found.',
            'not_found_in_trash' => 'No WPPool Projects found in Trash.'
        );
    
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'wppool-zi-projects' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-buddicons-topics',
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        );
        
        $post_type = 'wppool_zi_projects';

        if ( ! post_type_exists( $post_type ) ) { 
            register_post_type( $post_type, $args );
        }
     
	}

    /**
	 * function for unregistering the WPPool Projects post type.
	 */
	function wppool_zi_projects_unregister_post_types() {
        unregister_post_type('wppool_zi_projects');
    }
