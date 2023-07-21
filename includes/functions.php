<?php

    /**
	 * Registers the post type for WPPool Projects.
	 */
	function wppool_zi_projects_register_post_types() {
		$labels = array(
            'name'               => 'Projects',
            'singular_name'      => 'Project',
            'menu_name'          => 'WPPool ZI Projects', 
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Project',
            'new_item'           => 'New Project',
            'edit_item'          => 'Edit Project',
            'view_item'          => 'View Project',
            'all_items'          => 'All Projects',
            'search_items'       => 'Search Projects',
            'parent_item_colon'  => 'Parent Projects:',
            'not_found'          => 'No Projects found.',
            'not_found_in_trash' => 'No Projects found in Trash.'
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
            'taxonomies'          => array( 'category' ),
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-buddicons-topics',
            'supports'            => array( 'title', 'editor', 'thumbnail'),
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
