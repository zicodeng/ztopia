<?php

// Plugin Name: Education Post Type
// Description: This plugin registers the "Education" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register the "Education" post type

*******************************************************/
function education_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		  => array( 'name' => 'Education' ),
		'supports'            => array( 'title', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'education' ),
        'public'      		  => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'hierarchical' 		  => false,
		'menu_position' 	  => 10,
	);

	register_post_type('education', $args);
}

add_action( 'init', 'education_post_type' );

/******************************************************

Register the "Education Information" meta box

******************************************************/
function education_information_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box( 'education-information-meta-box', 'Education Information', 'education_information_meta_box_callback', 'education' );
}

// Display meta box
function education_information_meta_box_callback( $post ) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field( plugin_basename(__FILE__), 'education_information_nonce' );

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	$institute_location = get_post_meta( $post->ID, 'institute_location', true );
	$graduation_date = get_post_meta( $post->ID, 'graduation_date', true );
	$degree_type = get_post_meta( $post->ID, 'degree_type', true );
	$education_description = get_post_meta( $post->ID, 'education_description', true );

	?>

	<div class="input-field">
		<label for="school-location">Institute Location</label>
		<input id="school-location" type="text" name="institute_location" value="<?php echo $institute_location; ?>">
	</div>

	<div class="input-field">
		<label for="graduation-date">Graduation Date</label>
		<input id="graduation-date" type="text" name="graduation_date" value="<?php echo $graduation_date; ?>">
	</div>

	<div class="input-field">
		<label for="degree-type">Degree Type</label>
		<input id="degree-type" type="text" name="degree_type" value="<?php echo $degree_type; ?>">
	</div>

	<div class="input-field">
		<label for="education-description">Description</label>
		<?php

		// Settings that we"ll pass to wp_editor
		$editor_settings = array(
			"quicktags"     => false,
			"media_buttons" => false,
			"editor_height" => 200,
		);

		// P1: content
		// P2: editor id, used to display data
		// P3: settings
		wp_editor( $education_description, "education_description", $editor_settings );

		?>
	</div>

	<?php
}

add_action( 'add_meta_boxes', 'education_information_meta_box' );

// Save user input to our database
function education_information_meta_box_save( $post_id ) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ( ( isset( $_POST['education_information_nonce'] ) ) &&
	( ! wp_verify_nonce( $_POST['education_information_nonce'], plugin_basename(__FILE__) ) ) )
		return;

	// Check permissions
	if ( ( isset( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] ) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// OK, we're authenticated: we need to find and save the data
	if ( isset( $_POST['institute_location'] ) ) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta( $post_id, 'institute_location', $_POST['institute_location'] );
	}

	if ( isset( $_POST['degree_type'] ) ) {
		update_post_meta( $post_id, 'degree_type', $_POST['degree_type'] );
	}

	if ( isset( $_POST['graduation_date'] ) ) {
		update_post_meta( $post_id, 'graduation_date', $_POST['graduation_date'] );
	}

	if ( isset( $_POST['education_description'] ) ) {
		update_post_meta( $post_id, 'education_description', $_POST['education_description'] );
	}
}

add_action( 'save_post', 'education_information_meta_box_save' );

/******************************************************

Load meta box style

******************************************************/
function education_dashboard_style(){
    global $typenow;
    if ( $typenow == 'education' ) {
        wp_enqueue_style( 'education_dashboard_style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'education_dashboard_style' );

?>
