<?php
/**
 * Call this function when plugin is deactivated
 */
function people_contact_install(){
	update_option('a3rev_wp_people_contact_lite_version', '3.1.1');
	update_option('a3rev_wp_people_contact_ultimate_version', '3.0.3');

	$contact_us_page_id = People_Contact_Functions::create_page( esc_sql( 'contact-us-page' ), 'contact_us_page_id', __('Contact Us Page', 'contact-us-page-contact-people' ), '[people_contacts]' );
	People_Contact_Functions::auto_create_page_for_wpml( $contact_us_page_id, _x('contact-us-page', 'page_slug', 'contact-us-page-contact-people' ), __('Contact Us Page', 'contact-us-page-contact-people' ), '[people_contacts]' );
	People_Contact_Profile_Data::install_database();

	// Set Settings Default from Admin Init
	global $people_contact_admin_init;
	$people_contact_admin_init->set_default_settings();

	delete_metadata( 'user', 0, $people_contact_admin_init->plugin_name . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

	// Build sass
	global $a3_people_contact_less;
	$a3_people_contact_less->plugin_build_sass();

	update_option('a3rev_wp_people_contact_just_installed', true);
}


update_option('a3rev_wp_people_contact_plugin', 'contact_us_page_contact_people');

/**
 * Load languages file
 */
function wp_people_contact_init() {
	if ( get_option('a3rev_wp_people_contact_just_installed') ) {
		delete_option('a3rev_wp_people_contact_just_installed');
		wp_redirect( admin_url( 'admin.php?page=people-contact-manager', 'relative' ) );
		exit;
	}

	wp_people_contact_plugin_textdomain();
}

// Add language
add_action('init', 'wp_people_contact_init');

//Resgister Sidebar
add_action('init', array('People_Contact_Functions', 'people_contact_register_sidebar'),99);

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'People_Contact_Hook_Filter', 'a3_wp_admin' ) );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( 'People_Contact_Hook_Filter', 'admin_sidebar_menu_css' ) );

// Load global settings when Plugin loaded
add_action( 'plugins_loaded', array( 'People_Contact_Functions', 'plugins_loaded' ), 8 );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('People_Contact_Hook_Filter', 'plugin_extra_links'), 10, 2 );

	global $people_contact_admin_init;
	$people_contact_admin_init->init();

	// Add upgrade notice to Dashboard pages
	add_filter( $people_contact_admin_init->plugin_name . '_plugin_extension_boxes', array( 'People_Contact_Hook_Filter', 'plugin_extension_box' ) );

	// Add extra link on left of Deactivate link on Plugin manager page
	add_action('plugin_action_links_'.PEOPLE_CONTACT_NAME, array('People_Contact_Hook_Filter', 'settings_plugin_links') );

	add_action('init', array('People_Contact_AddNew', 'profile_form_action') );

	add_action( 'admin_menu', array( 'People_Contact_Hook_Filter', 'register_admin_screen' ), 9 );

	add_action( 'wp_enqueue_scripts', array( 'People_Contact_Hook_Filter', 'frontend_scripts_register' ) );

	add_filter( 'body_class', array( 'People_Contact_Hook_Filter', 'browser_body_class'), 10, 2 );

	//Ajax Sort Contact
	add_action('wp_ajax_people_update_orders', array( 'People_Contact_Hook_Filter', 'people_update_orders') );
	add_action('wp_ajax_nopriv_people_update_orders', array( 'People_Contact_Hook_Filter', 'people_update_orders') );

	$GLOBALS['people_contact'] = new People_Contact();

	$GLOBALS['people_contact_shortcode'] = new People_Contact_Shortcode();

	// Include script admin plugin
	if ( in_array( basename ($_SERVER['PHP_SELF']), array('admin.php', 'edit.php') ) && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array('people-contact-manager', 'people-contact', 'people-contact-settings', 'people-category-manager' ) ) ) {
		add_action('admin_head', array('People_Contact_Hook_Filter', 'admin_header_script'));
	}

	// Check upgrade functions
add_action('plugins_loaded', 'a3_people_contact_lite_upgrade_plugin');
function a3_people_contact_lite_upgrade_plugin () {

	global $people_contact_admin_init;
	global $a3_people_contact_less;

	// Upgrade to 1.0.3
	if(version_compare(get_option('a3rev_wp_people_contact_version'), '1.0.3') === -1){
		People_Contact_Profile_Data::install_database();
		update_option('a3rev_wp_people_contact_version', '1.0.3');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.0.3.php' );
	}

	// Upgrade to 1.1.1
	if(version_compare(get_option('a3rev_wp_people_contact_version'), '1.1.1') === -1){
		update_option('a3rev_wp_people_contact_version', '1.1.1');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.1.1.php' );
	}

	// Upgrade to 1.1.4 for Lite version
	if(version_compare(get_option('a3rev_wp_people_contact_version'), '1.1.4') === -1){
		update_option('a3rev_wp_people_contact_version', '1.1.4');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.1.4.php' );
	}

	// Upgrade to 1.2.0
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '1.2.0') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '1.2.0');

		// Build sass
		$a3_people_contact_less->plugin_build_sass();
	}

	if( version_compare(get_option('a3rev_wp_people_contact_lite_version'), '2.0.1') === -1 ){
		update_option('a3rev_wp_people_contact_lite_version', '2.0.1');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-2.0.1.php' );
	}

	if( version_compare(get_option('a3rev_wp_people_contact_lite_version'), '2.1.0') === -1 ){
		update_option('a3rev_wp_people_contact_lite_version', '2.1.0');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-2.1.0.php' );
	}

	// Upgrade to 2.2.0
	if ( version_compare( get_option('a3rev_wp_people_contact_lite_version'), '2.2.0') === -1 ) {
		update_option('a3rev_wp_people_contact_lite_version', '2.2.0');
		update_option('wp_contact_people_style_version', time() );
	}

	// Upgrade to 3.0.0
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '3.0.0') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '3.0.0');

		$people_contact_admin_init->set_default_settings();

		// Build sass
		$a3_people_contact_less->plugin_build_sass();
	}

	update_option('a3rev_wp_people_contact_lite_version', '3.1.1');
	update_option('a3rev_wp_people_contact_ultimate_version', '3.0.3');

}
?>