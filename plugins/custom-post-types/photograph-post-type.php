<?php

// Plugin Name: Photograph Post Type
// Description: This plugin registers the "Photograph" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register the "Photograph" post type

*******************************************************/
function photograph_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		  => array( 'name' => 'Photo Gallery' ),
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'photograph' ),
        'public'      		  => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'hierarchical' 		  => false,
		'menu_position' 	  => 15,
		'show_in_rest' 		    => true,
		'rest_base' 		    => 'photograph-api',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);

	register_post_type( 'photograph', $args );
}

add_action( 'init', 'photograph_post_type' );

/******************************************************

REST API

******************************************************/
// Register featured image associated with this post
function register_photograph_featured_image_url() {
	register_rest_field(
		'photograph',
	    'photograph_featured_image_url', //Name of the new field to be added
	    array(
	        'get_callback'    => 'get_photograph_featured_image_url',
	        'update_callback' => null,
	        'schema'          => null,
	    	)
	    );
}

function get_photograph_featured_image_url( $object, $field_name, $request ) {
	// Define image size (thumbnail, medium, large, full)
    $size = 'full';
    $featured_image_array = wp_get_attachment_image_src( $object['featured_media'], $size, true );
    return $featured_image_array[0];
}

add_action( 'rest_api_init', 'register_photograph_featured_image_url' );

/******************************************************

Load meta box style

******************************************************/
function photograph_dashboard_style(){
    global $typenow;
    if ( $typenow == 'photograph' ) {
        wp_enqueue_style( 'photograph_dashboard_style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'photograph_dashboard_style' );

?>
