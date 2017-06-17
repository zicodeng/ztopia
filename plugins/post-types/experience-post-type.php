<?php

// Plugin Name: Experience Post Type
// Description: This plugin registers the "Experience" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register the "Experience" post type

*******************************************************/
function experience_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		  => array( 'name' => 'Experience' ),
		'supports'            => array( 'title', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'experience' ),
        'public'      		  => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'hierarchical' 		  => false,
		'menu_position' 	  => 10,
	);

	register_post_type('experience', $args);
}

add_action( 'init', 'experience_post_type' );

/******************************************************

Register the "Experience Information" meta box

******************************************************/
function experience_information_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box( 'experience-information-meta-box', 'Experience Information', 'experience_information_meta_box_callback', 'experience' );
}

// Display meta box
function experience_information_meta_box_callback( $post ) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field( plugin_basename(__FILE__), 'experience_information_nonce' );

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	$experience_location = get_post_meta( $post->ID, 'experience_location', true );
	$experience_time_period = get_post_meta( $post->ID, 'experience_time_period', true );
	$job_title = get_post_meta( $post->ID, 'job_title', true );
	$experience_description = get_post_meta( $post->ID, 'experience_description', true );

	?>

	<div class="input-field">
		<label for="experience-location">Location</label>
		<input id="experience-location" type="text" name="experience_location" value="<?php echo $experience_location; ?>">
	</div>

	<div class="input-field">
		<label for="experience-time-period">Time Period</label>
		<input id="experience-time-period" type="text" name="experience_time_period" value="<?php echo $experience_time_period; ?>">
	</div>

	<div class="input-field">
		<label for="job-title">Job Title</label>
		<input id="job-title" type="text" name="job_title" value="<?php echo $job_title; ?>">
	</div>

	<div class="input-field">
		<label for="experience-description">Description</label>
		<?php

		// Settings that we"ll pass to wp_editor
		$editor_settings = array(
			'quicktags'     => false,
			'media_buttons' => false,
			'editor_height' => 200,
		);

		// P1: content
		// P2: editor id, used to display data
		// P3: settings
		wp_editor( $experience_description, 'experience_description', $editor_settings );

		?>
	</div>

	<?php
}

add_action( 'add_meta_boxes', 'experience_information_meta_box' );

// Save user input to our database
function experience_information_meta_box_save( $post_id ) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ( ( isset( $_POST['experience_information_nonce'] ) ) &&
	( ! wp_verify_nonce( $_POST['experience_information_nonce'], plugin_basename(__FILE__) ) ) )
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
	if ( isset( $_POST['experience_location'] ) ) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta( $post_id, 'experience_location', $_POST['experience_location'] );
	}

	if ( isset( $_POST['experience_time_period'] ) ) {
		update_post_meta( $post_id, 'experience_time_period', $_POST['experience_time_period'] );
	}

	if ( isset( $_POST['job_title'] ) ) {
		update_post_meta( $post_id, 'job_title', $_POST['job_title'] );
	}

	if ( isset( $_POST['experience_description'] ) ) {
		update_post_meta( $post_id, 'experience_description', $_POST['experience_description'] );
	}
}

add_action( 'save_post', 'experience_information_meta_box_save' );

/******************************************************

Load meta box style

******************************************************/
function experience_dashboard_style(){
    global $typenow;
    if ( $typenow == 'experience' ) {
        wp_enqueue_style( 'experience_dashboard_style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'experience_dashboard_style' );

?>
