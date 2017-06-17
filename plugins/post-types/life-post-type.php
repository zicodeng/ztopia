<?php

// Plugin Name: Life Post Type
// Description: This plugin registers the "Life" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register "Life" post type

*******************************************************/
function life_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		    => array( 'name' => 'Life' ),
		'supports'              => array( 'title', 'thumbnail' ),
		'rewrite'               => array( 'slug' => 'life' ),
        'public'      		    => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'hierarchical' 			=> false,
		'menu_position' 	    => 15,
		'show_in_rest' 		    => true,
		'rest_base' 		    => 'life-api',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);

	register_post_type("life", $args);
}

add_action("init", "life_post_type");

/******************************************************

Register "Life Map" meta box

*******************************************************/
function life_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box("life-meta-box", "Life Information", "life_meta_box_callback", "life");
}

// Display meta box
function life_meta_box_callback($post) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field(plugin_basename(__FILE__), "life_nonce");

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	$life_time_period = get_post_meta($post->ID, "life_time_period", true);
	$life_location = get_post_meta($post->ID, "life_location", true);
	$life_description = get_post_meta($post->ID, "life_description", true);
	?>

	<div class="input-field">
		<label for="life-time-period">Time Period</label>
		<input id="life-time-period" type="text" name="life_time_period" value="<?php echo $life_time_period ?>">
	</div>

	<div class="input-field">
		<label for="life-location">Location</label>
		<input id="life-location" type="text" name="life_location" value="<?php echo $life_location ?>">
	</div>

	<div class="input-field">
		<label for="life-description">Description</label>
		<?php

		// Settings that we"ll pass to wp_editor
		$editor_settings = array(
			"quicktags"     => false,
			"media_buttons" => false,
			"editor_height" => 200
		);

		// P1: content
		// P2: editor id, used to display data
		// P3: settings
		wp_editor( $life_description, "life_description", $editor_settings );

		?>
	</div>

	<?php
}

add_action("add_meta_boxes", "life_meta_box");

// Save user input to our database
function life_meta_box_save($post_id) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don"t want to do anything
	if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ((isset($_POST["life_nonce"])) && (!wp_verify_nonce($_POST["life_nonce"], plugin_basename(__FILE__))))
		return;

	// Check permissions
	if ((isset($_POST["post_type"])) && ("page" == $_POST["post_type"])) {
		if (!current_user_can("edit_page", $post_id)) {
			return;
		}
	} else {
		if(!current_user_can("edit_post", $post_id)) {
			return;
		}
	}

	// OK, we"re authenticated: we need to find and save the data
	if (isset($_POST["life_time_period"])) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta($post_id, "life_time_period", $_POST["life_time_period"] );
	}

	if (isset($_POST["life_location"])) {
		update_post_meta($post_id, "life_location", $_POST["life_location"] );
	}

	if (isset($_POST["life_description"])) {
		update_post_meta($post_id, "life_description", $_POST["life_description"] );
	}
}

add_action("save_post", "life_meta_box_save");

/******************************************************

REST API

*******************************************************/

// Register life time period field
function register_life_time_period() {
	// P1: post type this field is associated with
	// P2: key (entry point)
	// P3: callback to retrieve value
	register_rest_field(
		"life",
		"life_time_period",
		array(
			"get_callback"    => "get_life_time_period",
			"update_callback" => null,
			"schema" 	      => null
		)
	);
}

function get_life_time_period($object, $field_name, $request) {
	return get_post_meta($object["id"], "life_time_period", true);
}

add_action("rest_api_init", "register_life_time_period");

// Register life location field
function register_life_location() {
	// P1: post type this field is associated with
	// P2: key (entry point)
	// P3: callback to retrieve value
	register_rest_field(
		"life",
		"life_location",
		array(
			"get_callback"    => "get_life_location",
			"update_callback" => null,
			"schema" 	      => null
		)
	);
}

function get_life_location($object, $field_name, $request) {
	return get_post_meta($object["id"], "life_location", true);
}

add_action("rest_api_init", "register_life_location");

// Register life description field
function register_life_description() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		"life",
		"life_description",
		array(
			"get_callback"    => "get_life_description",
			"update_callback" => null,
			"schema" 	      => null,
		)
	);
}

function get_life_description($object, $field_name, $request) {
	return get_post_meta($object["id"], "life_description", true);
}

add_action("rest_api_init", "register_life_description");

// Register featured image associated with this post
function register_life_featured_image_url() {
	register_rest_field(
		'life',
	    'life_featured_image_url', //Name of the new field to be added
	    array(
	        'get_callback'    => 'get_life_featured_image_url',
	        'update_callback' => null,
	        'schema'          => null,
	    	)
	    );
}

function get_life_featured_image_url( $object, $field_name, $request ) {
	// Define image size (thumbnail, medium, large, full)
    $size = 'full';
    $featured_image_array = wp_get_attachment_image_src( $object['featured_media'], $size, true );
    return $featured_image_array[0];
}

add_action( 'rest_api_init', 'register_life_featured_image_url' );

/******************************************************

Load meta box style

******************************************************/
function life_dashboard_style(){
    global $typenow;
    if ( $typenow == 'life' ) {
        wp_enqueue_style( 'life_dashboard_style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'life_dashboard_style' );

?>
