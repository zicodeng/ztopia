<?php

// Plugin Name: About Post Type
// Description: This plugin registers the "About" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register the "About" post type

*******************************************************/
function about_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		  => array( 'name' => 'About' ),
		'supports'            => array( 'title', 'thumbnail' ),
		'rewrite'             => array( 'slug' => 'about' ),
        'public'      		  => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'hierarchical' 		  => false,
		'menu_position' 	  => 10,
	);

	register_post_type( 'about', $args );
}

add_action( 'init', 'about_post_type' );

/******************************************************

Register the "About Information" meta box

******************************************************/
function about_information_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box( 'about-information-meta-box', 'About Information', 'about_information_meta_box_callback', 'about' );
}

// Display meta box
function about_information_meta_box_callback( $post ) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field( plugin_basename(__FILE__), 'about_information_nonce' );

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	$about_subtitle = get_post_meta( $post->ID, 'about_subtitle', true );
	$about_description = get_post_meta( $post->ID, 'about_description', true );

	?>

	<div class="input-field">
		<label for="about-subtitle">Subtitle</label>
		<input id="about-subtitle" type="text" name="about_subtitle" value="<?php echo $about_subtitle; ?>">
	</div>

	<div class="input-field">
		<label for="about-description">Description</label>
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
		wp_editor( $about_description, "about_description", $editor_settings );

		?>
	</div>

	<?php
}

add_action( 'add_meta_boxes', 'about_information_meta_box' );

// Save user input to our database
function about_information_meta_box_save( $post_id ) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ( ( isset( $_POST['about_information_nonce'] ) ) &&
	( ! wp_verify_nonce( $_POST['about_information_nonce'], plugin_basename(__FILE__) ) ) )
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
	if ( isset( $_POST['about_subtitle'] ) ) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta( $post_id, 'about_subtitle', $_POST['about_subtitle'] );
	}

	if ( isset( $_POST['about_description'] ) ) {
		update_post_meta( $post_id, 'about_description', $_POST['about_description'] );
	}
}

add_action( 'save_post', 'about_information_meta_box_save' );

/******************************************************

Load meta box style

******************************************************/
function about_dashboard_style(){
    global $typenow;
    if ( $typenow == 'about' ) {
        wp_enqueue_style( 'about_dashboard_style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'about_dashboard_style' );

?>
