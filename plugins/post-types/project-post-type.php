<?php

// Plugin Name: Project Post Type
// Description: This plugin registers the "Project" post type.
// Author: Zico Deng
// Version: 1.0
// Author URI: www.zicodeng.me
// License:

/******************************************************

Register the "Project" post type

*******************************************************/
function project_post_type() {
	// 'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes')
	// 'taxonomies' => array( 'post_tag', 'category' )
	$args = array(
		'labels'      		    => array( 'name' => 'Projects' ),
		'supports'              => array( 'title', 'thumbnail' ),
		'rewrite'               => array( 'slug' => 'project' ),
        'public'      		    => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
		'publicly_queryable'    => true, // True allows single-project.php to be viewed publicly
		'hierarchical' 			=> false,
		'menu_position' 	    => 10,
		'show_in_rest' 		    => true,
		'rest_base' 		    => 'project-api',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);

	register_post_type('project', $args);
}

add_action( 'init', 'project_post_type' );

/******************************************************

Add filter by "Project Category" in admin

*******************************************************/
function get_project_categories() {
	$project_categories = array(
		'Web'  	  => 'web',
		'Android' => 'android',
		'Design'  => 'design',
	);

	return $project_categories;
}

function add_project_category_admin_filter() {
	global $typenow;

    if ( $typenow == 'project' ) {
		$project_categories = get_project_categories();
		?>
		<select class="" name="project_category_admin_filter">
			<option value="">All Projects</option>
			<?php
			$selected_project_category_value = isset( $_GET['project_category_admin_filter'] ) ? $_GET['project_category_admin_filter'] : '';
			foreach ($project_categories as $project_category_label => $project_category_value) {
	            printf
	                (
	                    '<option value="%s"%s>%s</option>',
	                    $project_category_value,
	                    $project_category_value == $selected_project_category_value ? ' selected="selected"' : '',
	                    $project_category_label
	                );
	            }
			?>
		</select>
	<?php
	}
}

add_action( 'restrict_manage_posts', 'add_project_category_admin_filter' );

function project_category_admin_filter_query( $query ) {
	global $pagenow, $typenow;

	// Only apply to "Resource" post type
	if ( $typenow == 'project' &&
	is_admin() &&
	$pagenow == 'edit.php' &&
	isset( $_GET['project_category_admin_filter'] ) &&
 	$_GET['project_category_admin_filter'] != '' ) {
		// WHERE clause
		$query->query_vars['meta_key'] = 'project_category';
		$query->query_vars['meta_value'] = $_GET['project_category_admin_filter'];
	}
}

add_filter( 'parse_query', 'project_category_admin_filter_query' );

/******************************************************

Register the "Project Information" meta box

******************************************************/
function project_information_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box( 'project-information-meta-box', 'Project Information', 'project_information_meta_box_callback', 'project' );
}

// Display meta box
function project_information_meta_box_callback( $post ) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field( plugin_basename(__FILE__), 'project_information_nonce' );

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	$selected_project_category_value = get_post_meta( $post->ID, 'project_category', true );
	$project_time_period = get_post_meta( $post->ID, 'project_time_period', true );
	$core_technologies = get_post_meta( $post->ID, 'core_technologies', true );
	$project_description = get_post_meta( $post->ID, 'project_description', true );
	$project_link = get_post_meta( $post->ID, 'project_link', true );
	?>

	<div class="input-field">
		<label for="project-category">Category</label>
		<select class="" name="project_category">
			<?php
			$project_categories = get_project_categories();
			foreach ($project_categories as $project_category_label => $project_category_value) {
	            printf
	                (
	                    '<option value="%s"%s>%s</option>',
	                    $project_category_value,
	                    $project_category_value == $selected_project_category_value ? ' selected="selected"' : '',
	                    $project_category_label
	                );
	            }
			?>
		</select>
	</div>

	<div class="input-field">
		<label for="project-time-period">Time Period</label>
		<input id="project-time-period" type="text" name="project_time_period" value="<?php echo $project_time_period; ?>">
	</div>

	<div class="input-field">
		<label for="core-technologies">Core Technologies</label>
		<input id="core-technologies" type="text" name="core_technologies" value="<?php echo $core_technologies; ?>">
	</div>

	<div class="input-field">
		<label for="project-description">Description</label>
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
		wp_editor( $project_description, 'project_description', $editor_settings );

		?>
	</div>

	<div class="input-field">
		<label for="project-link">Link</label>
		<input id="project-link" type="text" name="project_link" value="<?php echo $project_link; ?>">
	</div>

	<?php
}

add_action( 'add_meta_boxes', 'project_information_meta_box' );

// Save user input to our database
function project_information_meta_box_save( $post_id ) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ( ( isset( $_POST['project_information_nonce'] ) ) &&
	( ! wp_verify_nonce( $_POST['project_information_nonce'], plugin_basename(__FILE__) ) ) )
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
	if ( isset( $_POST['project_category'] ) ) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta( $post_id, 'project_category', $_POST['project_category'] );
	}

	if ( isset( $_POST['project_location'] ) ) {
		update_post_meta( $post_id, 'project_location', $_POST['project_location'] );
	}

	if ( isset( $_POST['project_time_period'] ) ) {
		update_post_meta( $post_id, 'project_time_period', $_POST['project_time_period'] );
	}

	if ( isset( $_POST['core_technologies'] ) ) {
		update_post_meta( $post_id, 'core_technologies', $_POST['core_technologies'] );
	}

	if ( isset( $_POST['project_description'] ) ) {
		update_post_meta( $post_id, 'project_description', $_POST['project_description'] );
	}

	if ( isset( $_POST['project_link'] ) ) {
		update_post_meta( $post_id, 'project_link', $_POST['project_link'] );
	}
}

add_action( 'save_post', 'project_information_meta_box_save' );

/******************************************************

Register the "Project Detail" meta box

******************************************************/
function project_detail_meta_box() {
	// P1: CSS id
	// P2: mata box title
	// P3: callback function
	// P4: post type this meta box is associated with
	// P5: priority level (optional)
	// P6: callback function arguments (optional)
    add_meta_box( 'project-detail-meta-box', 'Project Detail', 'project_detail_meta_box_callback', 'project' );
}

// Display meta box
function project_detail_meta_box_callback( $post ) {
	// Use nonce for verification
	// P1: action name, should give the context to what is taking place
	// P2: nonce name
	wp_nonce_field( plugin_basename(__FILE__), 'project_detail_nonce' );

	// P1: get post meta-box with post ID (has to be uppercase)
	// P2: get the specific value in the meta box using its key
	// P3: true = return a single value, false = return an array
	// Slide one
	$project_slide_one_title = get_post_meta( $post->ID, 'project_slide_one_title', true );
	$project_slide_one_description = get_post_meta( $post->ID, 'project_slide_one_description', true );
	$project_slide_one_image_one = get_post_meta( $post->ID, 'project_slide_one_image_one', true );
	$project_slide_one_image_two = get_post_meta( $post->ID, 'project_slide_one_image_two', true );
	$project_slide_one_image_three = get_post_meta( $post->ID, 'project_slide_one_image_three', true );
	$project_slide_one_image_four = get_post_meta( $post->ID, 'project_slide_one_image_four', true );

	// Slide two
	$project_slide_two_title = get_post_meta( $post->ID, 'project_slide_two_title', true );
	$project_slide_two_description = get_post_meta( $post->ID, 'project_slide_two_description', true );
	$project_slide_two_image_one = get_post_meta( $post->ID, 'project_slide_two_image_one', true );
	$project_slide_two_image_two = get_post_meta( $post->ID, 'project_slide_two_image_two', true );
	$project_slide_two_image_three = get_post_meta( $post->ID, 'project_slide_two_image_three', true );
	$project_slide_two_image_four = get_post_meta( $post->ID, 'project_slide_two_image_four', true );

	// Slide three
	$project_slide_three_title = get_post_meta( $post->ID, 'project_slide_three_title', true );
	$project_slide_three_description = get_post_meta( $post->ID, 'project_slide_three_description', true );
	$project_slide_three_image_one = get_post_meta( $post->ID, 'project_slide_three_image_one', true );
	$project_slide_three_image_two = get_post_meta( $post->ID, 'project_slide_three_image_two', true );
	$project_slide_three_image_three = get_post_meta( $post->ID, 'project_slide_three_image_three', true );
	$project_slide_three_image_four = get_post_meta( $post->ID, 'project_slide_three_image_four', true );

	// Slide four
	$project_slide_four_title = get_post_meta( $post->ID, 'project_slide_four_title', true );
	$project_slide_four_description = get_post_meta( $post->ID, 'project_slide_four_description', true );
	$project_slide_four_image_one = get_post_meta( $post->ID, 'project_slide_four_image_one', true );
	$project_slide_four_image_two = get_post_meta( $post->ID, 'project_slide_four_image_two', true );
	$project_slide_four_image_three = get_post_meta( $post->ID, 'project_slide_four_image_three', true );
	$project_slide_four_image_four = get_post_meta( $post->ID, 'project_slide_four_image_four', true );
	?>

	<!-- Slide one -->
	<div class="slide-info-container">
		<h2>Slide One <span class="toggle-indicator"></span></h2>
		<div class="input-container">
			<div class="input-field">
				<label for="project-slide-one-title">Title</label>
				<input id="project-slide-one-title" type="text" name="project_slide_one_title" value="<?php echo $project_slide_one_title; ?>">
			</div>

			<div class="input-field">
				<label for="project-slide-one-description">Description</label>
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
				wp_editor( $project_slide_one_description, 'project_slide_one_description', $editor_settings );

				?>
			</div>

			<div class="input-field image">
				<label for="project-slide-one-image-one">Image One</label>
				<div id="meta-box-image-one-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_one_image_one ) ) {
					echo $project_slide_one_image_one;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-one" type="text" name="project_slide_one_image_one" value="<?php echo $project_slide_one_image_one; ?>">
				<button type="button" class="meta-box-image-edit-btn one">Edit</button>
				<button type="button" class="meta-box-image-remove-btn one">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-one-image-two">Image Two</label>
				<div id="meta-box-image-two-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_one_image_two ) ) {
					echo $project_slide_one_image_two;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-two" type="text" name="project_slide_one_image_two" value="<?php echo $project_slide_one_image_two; ?>">
				<button type="button" class="meta-box-image-edit-btn two">Edit</button>
				<button type="button" class="meta-box-image-remove-btn two">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-one-image-three">Image Three</label>
				<div id="meta-box-image-three-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_one_image_three ) ) {
					echo $project_slide_one_image_three;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-three" type="text" name="project_slide_one_image_three" value="<?php echo $project_slide_one_image_three; ?>">
				<button type="button" class="meta-box-image-edit-btn three">Edit</button>
				<button type="button" class="meta-box-image-remove-btn three">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-one-image-four">Image Four</label>
				<div id="meta-box-image-four-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_one_image_four ) ) {
					echo $project_slide_one_image_four;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-four" type="text" name="project_slide_one_image_four" value="<?php echo $project_slide_one_image_four; ?>">
				<button type="button" class="meta-box-image-edit-btn four">Edit</button>
				<button type="button" class="meta-box-image-remove-btn four">Remove</button>
			</div>
		</div>
	</div>

	<!-- Slide two -->
	<div class="slide-info-container">
		<h2>Slide Two <span class="toggle-indicator"></span></h2>
		<div class="input-container">
			<div class="input-field">
				<label for="project-slide-two-title">Title</label>
				<input id="project-slide-two-title" type="text" name="project_slide_two_title" value="<?php echo $project_slide_two_title; ?>">
			</div>

			<div class="input-field">
				<label for="project-slide-two-description">Description</label>
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
				wp_editor( $project_slide_two_description, 'project_slide_two_description', $editor_settings );

				?>
			</div>

			<div class="input-field image">
				<label for="project-slide-two-image-one">Image One</label>
				<div id="meta-box-image-five-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_two_image_one ) ) {
					echo $project_slide_two_image_one;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-five" type="text" name="project_slide_two_image_one" value="<?php echo $project_slide_two_image_one; ?>">
				<button type="button" class="meta-box-image-edit-btn five">Edit</button>
				<button type="button" class="meta-box-image-remove-btn five">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-two-image-two">Image Two</label>
				<div id="meta-box-image-six-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_two_image_two ) ) {
					echo $project_slide_two_image_two;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-six" type="text" name="project_slide_two_image_two" value="<?php echo $project_slide_two_image_two; ?>">
				<button type="button" class="meta-box-image-edit-btn six">Edit</button>
				<button type="button" class="meta-box-image-remove-btn six">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-two-image-three">Image Three</label>
				<div id="meta-box-image-seven-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_two_image_three ) ) {
					echo $project_slide_two_image_three;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-seven" type="text" name="project_slide_two_image_three" value="<?php echo $project_slide_two_image_three; ?>">
				<button type="button" class="meta-box-image-edit-btn seven">Edit</button>
				<button type="button" class="meta-box-image-remove-btn seven">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-two-image-four">Image Four</label>
				<div id="meta-box-image-eight-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_two_image_four ) ) {
					echo $project_slide_two_image_four;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-eight" type="text" name="project_slide_two_image_four" value="<?php echo $project_slide_two_image_four; ?>">
				<button type="button" class="meta-box-image-edit-btn eight">Edit</button>
				<button type="button" class="meta-box-image-remove-btn eight">Remove</button>
			</div>
		</div>
	</div>

	<!-- Slide three -->
	<div class="slide-info-container">
		<h2>Slide Three <span class="toggle-indicator"></span></h2>
		<div class="input-container">
			<div class="input-field">
				<label for="project-slide-three-title">Title</label>
				<input id="project-slide-three-title" type="text" name="project_slide_three_title" value="<?php echo $project_slide_three_title; ?>">
			</div>

			<div class="input-field">
				<label for="project-slide-three-description">Description</label>
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
				wp_editor( $project_slide_three_description, 'project_slide_three_description', $editor_settings );

				?>
			</div>

			<div class="input-field image">
				<label for="project-slide-three-image-one">Image One</label>
				<div id="meta-box-image-nine-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_three_image_one ) ) {
					echo $project_slide_three_image_one;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-nine" type="text" name="project_slide_three_image_one" value="<?php echo $project_slide_three_image_one; ?>">
				<button type="button" class="meta-box-image-edit-btn nine">Edit</button>
				<button type="button" class="meta-box-image-remove-btn nine">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-three-image-two">Image Two</label>
				<div id="meta-box-image-ten-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_three_image_two ) ) {
					echo $project_slide_three_image_two;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-ten" type="text" name="project_slide_three_image_two" value="<?php echo $project_slide_three_image_two; ?>">
				<button type="button" class="meta-box-image-edit-btn ten">Edit</button>
				<button type="button" class="meta-box-image-remove-btn ten">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-three-image-three">Image Three</label>
				<div id="meta-box-image-eleven-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_three_image_three ) ) {
					echo $project_slide_three_image_three;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-eleven" type="text" name="project_slide_three_image_three" value="<?php echo $project_slide_three_image_three; ?>">
				<button type="button" class="meta-box-image-edit-btn eleven">Edit</button>
				<button type="button" class="meta-box-image-remove-btn eleven">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-three-image-four">Image Four</label>
				<div id="meta-box-image-twelve-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_three_image_four ) ) {
					echo $project_slide_three_image_four;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-twelve" type="text" name="project_slide_three_image_four" value="<?php echo $project_slide_three_image_four; ?>">
				<button type="button" class="meta-box-image-edit-btn twelve">Edit</button>
				<button type="button" class="meta-box-image-remove-btn twelve">Remove</button>
			</div>
		</div>
	</div>

	<!-- Slide four -->
	<div class="slide-info-container">
		<h2>Slide Four <span class="toggle-indicator"></span></h2>
		<div class="input-container">
			<div class="input-field">
				<label for="project-slide-four-title">Title</label>
				<input id="project-slide-four-title" type="text" name="project_slide_four_title" value="<?php echo $project_slide_four_title; ?>">
			</div>

			<div class="input-field">
				<label for="project-slide-four-description">Description</label>
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
				wp_editor( $project_slide_four_description, 'project_slide_four_description', $editor_settings );

				?>
			</div>

			<div class="input-field image">
				<label for="project-slide-four-image-one">Image One</label>
				<div id="meta-box-image-thirteen-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_four_image_one ) ) {
					echo $project_slide_four_image_one;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-thirteen" type="text" name="project_slide_four_image_one" value="<?php echo $project_slide_four_image_one; ?>">
				<button type="button" class="meta-box-image-edit-btn thirteen">Edit</button>
				<button type="button" class="meta-box-image-remove-btn thirteen">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-four-image-two">Image Two</label>
				<div id="meta-box-image-fourteen-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_four_image_two ) ) {
					echo $project_slide_four_image_two;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-fourteen" type="text" name="project_slide_four_image_two" value="<?php echo $project_slide_four_image_two; ?>">
				<button type="button" class="meta-box-image-edit-btn fourteen">Edit</button>
				<button type="button" class="meta-box-image-remove-btn fourteen">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-four-image-four">Image Three</label>
				<div id="meta-box-image-fifteen-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_four_image_three ) ) {
					echo $project_slide_four_image_three;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-fifteen" type="text" name="project_slide_four_image_three" value="<?php echo $project_slide_four_image_three; ?>">
				<button type="button" class="meta-box-image-edit-btn fifteen">Edit</button>
				<button type="button" class="meta-box-image-remove-btn fifteen">Remove</button>
			</div>

			<div class="input-field image">
				<label for="project-slide-four-image-four">Image Four</label>
				<div id="meta-box-image-sixteen-preview" class="image-preview"
				style='background-image: url("<?php
				if ( ! empty( $project_slide_four_image_four ) ) {
					echo $project_slide_four_image_four;
				} else {
					echo get_template_directory_uri() . '/assets/images/default-image.jpg';
				}
				?>")'></div>
				<input id="meta-box-image-sixteen" type="text" name="project_slide_four_image_four" value="<?php echo $project_slide_four_image_four; ?>">
				<button type="button" class="meta-box-image-edit-btn sixteen">Edit</button>
				<button type="button" class="meta-box-image-remove-btn sixteen">Remove</button>
			</div>
		</div>
	</div>

	<?php
}

add_action( 'add_meta_boxes', 'project_detail_meta_box' );

// Save user input to our database
function project_detail_meta_box_save( $post_id ) {

	// Verify if this is an auto save routine.
	// If it is our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return;

	// Verify this comes from the our screen and with proper authorization
	// Because save_post can be triggered at other times
	if ( ( isset( $_POST['project_detail_nonce'] ) ) &&
	( ! wp_verify_nonce( $_POST['project_detail_nonce'], plugin_basename(__FILE__) ) ) )
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

	// Slide one
	// OK, we're authenticated: we need to find and save the data
	if ( isset( $_POST['project_slide_one_title'] ) ) {
		// P1: post id
		// P2: key
		// P3: value
		update_post_meta( $post_id, 'project_slide_one_title', $_POST['project_slide_one_title'] );
	}

	if ( isset( $_POST['project_slide_one_description'] ) ) {
		update_post_meta( $post_id, 'project_slide_one_description', $_POST['project_slide_one_description'] );
	}

	if ( isset( $_POST['project_slide_one_image_one'] ) ) {
		update_post_meta( $post_id, 'project_slide_one_image_one', $_POST['project_slide_one_image_one'] );
	}

	if ( isset( $_POST['project_slide_one_image_two'] ) ) {
		update_post_meta( $post_id, 'project_slide_one_image_two', $_POST['project_slide_one_image_two'] );
	}

	if ( isset( $_POST['project_slide_one_image_three'] ) ) {
		update_post_meta( $post_id, 'project_slide_one_image_three', $_POST['project_slide_one_image_three'] );
	}

	if ( isset( $_POST['project_slide_one_image_four'] ) ) {
		update_post_meta( $post_id, 'project_slide_one_image_four', $_POST['project_slide_one_image_four'] );
	}

	// Slide two
	if ( isset( $_POST['project_slide_two_title'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_title', $_POST['project_slide_two_title'] );
	}

	if ( isset( $_POST['project_slide_two_description'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_description', $_POST['project_slide_two_description'] );
	}

	if ( isset( $_POST['project_slide_two_image_one'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_image_one', $_POST['project_slide_two_image_one'] );
	}

	if ( isset( $_POST['project_slide_two_image_two'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_image_two', $_POST['project_slide_two_image_two'] );
	}

	if ( isset( $_POST['project_slide_two_image_three'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_image_three', $_POST['project_slide_two_image_three'] );
	}

	if ( isset( $_POST['project_slide_two_image_four'] ) ) {
		update_post_meta( $post_id, 'project_slide_two_image_four', $_POST['project_slide_two_image_four'] );
	}

	// Slide three
	if ( isset( $_POST['project_slide_three_title'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_title', $_POST['project_slide_three_title'] );
	}

	if ( isset( $_POST['project_slide_three_description'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_description', $_POST['project_slide_three_description'] );
	}

	if ( isset( $_POST['project_slide_three_image_one'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_image_one', $_POST['project_slide_three_image_one'] );
	}

	if ( isset( $_POST['project_slide_three_image_two'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_image_two', $_POST['project_slide_three_image_two'] );
	}

	if ( isset( $_POST['project_slide_three_image_three'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_image_three', $_POST['project_slide_three_image_three'] );
	}

	if ( isset( $_POST['project_slide_three_image_four'] ) ) {
		update_post_meta( $post_id, 'project_slide_three_image_four', $_POST['project_slide_three_image_four'] );
	}

	// Slide four
	if ( isset( $_POST['project_slide_four_title'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_title', $_POST['project_slide_four_title'] );
	}

	if ( isset( $_POST['project_slide_four_description'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_description', $_POST['project_slide_four_description'] );
	}

	if ( isset( $_POST['project_slide_four_image_one'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_image_one', $_POST['project_slide_four_image_one'] );
	}

	if ( isset( $_POST['project_slide_four_image_two'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_image_two', $_POST['project_slide_four_image_two'] );
	}

	if ( isset( $_POST['project_slide_four_image_three'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_image_three', $_POST['project_slide_four_image_three'] );
	}

	if ( isset( $_POST['project_slide_four_image_four'] ) ) {
		update_post_meta( $post_id, 'project_slide_four_image_four', $_POST['project_slide_four_image_four'] );
	}
}

add_action( 'save_post', 'project_detail_meta_box_save' );

/******************************************************

REST API

******************************************************/

// Register the "Project Category" field
function register_project_category() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		'project',
		'project_category',
		array(
			'get_callback'    => 'get_project_category',
			'update_callback' => null,
			'schema' 	      => null,
		)
	);
}

function get_project_category( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], 'project_category', true );
}

add_action('rest_api_init', 'register_project_category');

// Register the "Project Time Period" field
function register_project_time_period() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		'project',
		'project_time_period',
		array(
			'get_callback'    => 'get_project_time_period',
			'update_callback' => null,
			'schema' 	      => null,
		)
	);
}

function get_project_time_period( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], 'project_time_period', true );
}

add_action('rest_api_init', 'register_project_time_period');

// Register the "Core Technologies" field
function register_project_core_technologies() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		'project',
		'project_core_technologies',
		array(
			'get_callback'    => 'get_project_core_technologies',
			'update_callback' => null,
			'schema' 	      => null,
		)
	);
}

function get_project_core_technologies( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], 'core_technologies', true );
}

add_action('rest_api_init', 'register_project_core_technologies');

// Register the "Project Description" field
function register_project_description() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		'project',
		'project_description',
		array(
			'get_callback'    => 'get_project_description',
			'update_callback' => null,
			'schema' 	      => null,
		)
	);
}

function get_project_description( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], 'project_description', true );
}

add_action('rest_api_init', 'register_project_description');

// Register the "Project Link" field
function register_project_link() {
	// P1: post type this field is associated with
	// P2: key (field name)
	// P3: callback to retrieve value
	register_rest_field(
		'project',
		'project_link',
		array(
			'get_callback'    => 'get_project_link',
			'update_callback' => null,
			'schema' 	      => null,
		)
	);
}

function get_project_link( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], 'project_link', true );
}

add_action('rest_api_init', 'register_project_link');

// Register featured image associated with this post
function register_project_featured_image_url() {
	register_rest_field(
		'project',
	    'project_featured_image_url', //Name of the new field to be added
	    array(
	        'get_callback'    => 'get_project_featured_image_url',
	        'update_callback' => null,
	        'schema'          => null,
	    	)
	    );
}

function get_project_featured_image_url( $object, $field_name, $request ) {
	// Define image size (thumbnail, medium, large, full)
    $size = 'full';
    $featured_image_array = wp_get_attachment_image_src( $object['featured_media'], $size, true );
    return $featured_image_array[0];
}

add_action( 'rest_api_init', 'register_project_featured_image_url' );

/******************************************************

Load meta box style

******************************************************/
function project_dashboard_style(){
    global $typenow;
    if ( $typenow == 'project' ) {
        wp_enqueue_style( 'project-dashboard-style', plugin_dir_url(__FILE__) . '/dashboard.css' );
    }
}

add_action( 'admin_print_styles', 'project_dashboard_style' );

/******************************************************

Load meta box image management JavaScript

******************************************************/
function project_meta_box_image_script() {
    global $typenow;
    if( $typenow == 'project' ) {
        wp_enqueue_media();

        // Registers and enqueues the required javascript.
        wp_enqueue_script( 'project-meta-box-image-script', plugin_dir_url(__FILE__) . '/meta-box-images.js' );
    }
}

add_action( 'admin_enqueue_scripts', 'project_meta_box_image_script' );

?>
