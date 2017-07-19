<?php

// Include CSS and JavaScript resources for the site
function ztopia_resources() {
	// Scripts
	// P1: handle used to refer to the script
	// P2: file path
	// P3: an array of dependencies
	// P4: version number
	// P5: Where do you want to load the script? true = load this script in footer
	// Only load these files in front-page.php
	if ( is_front_page() ) {
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC_FtTqKXIsU2cykmp5aoVogtQpz_plq1Q' );
		wp_enqueue_script( 'app', get_template_directory_uri() . '/assets/dist/app.js', array(), 1.0, true );
		wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/src/main.js', array('jquery'), 1.0, true );
	}

	// Only load these files in single-project.php
	if ( is_singular('project') ) {

		// Fullpage plugin script
		wp_enqueue_script( 'full-page-scrolling-script', get_template_directory_uri() . '/assets/src/jquery.fullpage.extensions.min.js', array('jquery'), 1.0, true );

		// Fullpage plugin style
		wp_enqueue_style( 'full-page-scrolling-style', get_template_directory_uri() . '/assets/src/jquery.fullpage.css', array(), '1', 'all' );
	}

	// Only load these files in page-photo-gallery.php
	if ( is_page('photo-gallery') ) {
		// Isotope
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/src/isotope.pkgd.min.js', array(), 1.0, true );

		// jsPDF
		wp_enqueue_script( 'jsPDF', get_template_directory_uri() . '/assets/src/jspdf.min.js', array(), 1.0, true );

		// Page-specific JavaScript
		wp_enqueue_script( 'gallery', get_template_directory_uri() . '/assets/src/photo-gallery.js', array('jquery'), 1.0, true );
	}

	// Styles
	// P1: handle
	// P2: src
	// P3: dependencies
	// P4: version
	// P5: media
	// Font Awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/bower_components/components-font-awesome/css/font-awesome.min.css', array(), '4.0.3', 'all' );

	// Bootstrap
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bower_components/bootstrap/dist/css/bootstrap.min.css', array(), '4.0.0-alpha.6', 'all' );

	// Load customized theme style last
	wp_enqueue_style( 'ztopia-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'ztopia_resources' );

// Theme setup
function ztopia_setup() {
    // Register navigation menus
    register_nav_menus( array(
        'sidebar-nav' => __( 'Sidebar Navigation' ),
    ) );
}

add_action( 'after_setup_theme', 'ztopia_setup' );

// Add thumbnail
add_theme_support( 'post-thumbnails' );

// Customize dashboard menu
function remove_menus(){

	// remove_menu_page( 'index.php' );                  //Dashboard
	// remove_menu_page( 'jetpack' );                    //Jetpack*
	remove_menu_page( 'edit.php' );                   //Posts
	// remove_menu_page('upload.php');                 //Media
	// remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
	// remove_menu_page( 'themes.php' );                 //Appearance
	// remove_menu_page( 'plugins.php' );                //Plugins
	// remove_menu_page( 'users.php' );                  //Users
	remove_menu_page( 'tools.php' );                  //Tools
	// remove_menu_page( 'options-general.php' );        //Settings
}

add_action( 'admin_menu', 'remove_menus' );

// Customize excerpt word count length
function custom_excerpt_length() {
    return 25;
}

add_filter( 'excerpt_length', 'custom_excerpt_length' );

// Custom login style
function custom_login_style() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/login/custom-login-style.css" />';
}

add_action('login_head', 'custom_login_style');

// Add GitHub field on the user editing screens
// P1: $user: WP_User user object
function add_usermeta_form_field_github( $user ) {
    ?>
	<h3>Social Media</h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="title">GitHub</label>
            </th>
            <td>
                <input type="text"
                       class="regular-text ltr"
                       id="github"
                       name="github"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'github', true ) ); ?>"
                       required>
                <p class="description">Link to your personal GitHub</p>
            </td>
        </tr>
    </table>
    <?php
}

// add the field to user's own profile editing screen
add_action( 'edit_user_profile', 'add_usermeta_form_field_github' );
// add the field to user profile editing screen
add_action( 'show_user_profile', 'add_usermeta_form_field_github' );

// The save action.
// P1: $user_id int the ID of the current user.
function update_usermeta_form_field_github( $user_id ) {
    // Check that the current user have the capability to edit the $user_id
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Create/update user meta for the $user_id
    return update_user_meta(
        $user_id,
        'github',
        $_POST['github']
    );
}

// Add the save action to user's own profile editing screen update
add_action( 'personal_options_update', 'update_usermeta_form_field_github' );
// Add the save action to user profile editing screen update
add_action( 'edit_user_profile_update', 'update_usermeta_form_field_github' );

// Add LinkedIn field on the user editing screens
// P1: $user: WP_User user object
function add_usermeta_form_field_linkedin( $user ) {
    ?>
    <table class="form-table">
        <tr>
            <th>
                <label for="title">LinkedIn</label>
            </th>
            <td>
                <input type="text"
                       class="regular-text ltr"
                       id="linkedin"
                       name="linkedin"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'linkedin', true ) ); ?>"
                       required>
                <p class="description">Link to your personal LinkedIn</p>
            </td>
        </tr>
    </table>
    <?php
}

// add the field to user's own profile editing screen
add_action( 'edit_user_profile', 'add_usermeta_form_field_linkedin' );
// add the field to user profile editing screen
add_action( 'show_user_profile', 'add_usermeta_form_field_linkedin' );

// The save action.
// P1: $user_id int the ID of the current user.
function update_usermeta_form_field_linkedin( $user_id ) {
    // Check that the current user have the capability to edit the $user_id
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Create/update user meta for the $user_id
    return update_user_meta(
        $user_id,
        'linkedin',
        $_POST['linkedin']
    );
}

// Add the save action to user's own profile editing screen update
add_action( 'personal_options_update', 'update_usermeta_form_field_linkedin' );
// Add the save action to user profile editing screen update
add_action( 'edit_user_profile_update', 'update_usermeta_form_field_linkedin' );
?>
