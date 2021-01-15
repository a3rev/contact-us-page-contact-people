<?php
/**
 * Call this function when plugin is deactivated
 */
function people_contact_install(){
	update_option('a3rev_wp_people_contact_lite_version', PEOPLE_CONTACT_VERSION );
	update_option('a3rev_wp_people_contact_ultimate_version', '3.0.4');

	$contact_us_page_id = \A3Rev\ContactPeople\Contact_Functions::create_page( esc_sql( 'contact-us-page' ), 'contact_us_page_id', __('Contact Us Page', 'contact-us-page-contact-people' ), '[people_contacts]' );
	\A3Rev\ContactPeople\Contact_Functions::auto_create_page_for_wpml( $contact_us_page_id, _x('contact-us-page', 'page_slug', 'contact-us-page-contact-people' ), __('Contact Us Page', 'contact-us-page-contact-people' ), '[people_contacts]' );
	\A3Rev\ContactPeople\Data\Profile::install_database();

	delete_metadata( 'user', 0, $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->plugin_name . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

	update_option('a3rev_wp_people_contact_just_installed', true);
}

function register_people_contact_widget(){register_widget( '\A3Rev\ContactPeople\Widget' );}
add_action('widgets_init', 'register_people_contact_widget');

/**
 * Load languages file
 */
function wp_people_contact_init() {
	if ( get_option('a3rev_wp_people_contact_just_installed') ) {
		delete_option('a3rev_wp_people_contact_just_installed');

		// Set Settings Default from Admin Init
		$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->set_default_settings();

		// Build sass
		$GLOBALS[PEOPLE_CONTACT_PREFIX.'less']->plugin_build_sass();
	}

	wp_people_contact_plugin_textdomain();

	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'people-contact-settings', 'people-contact' ) ) ) {
		add_action( 'admin_notices', array( '\A3Rev\ContactPeople\Hook_Filter', 'map_notice' ), 11 );
	}
}

// Add language
add_action('init', 'wp_people_contact_init');

//Resgister Sidebar
add_action('init', array('\A3Rev\ContactPeople\Contact_Functions', 'people_contact_register_sidebar'),99);

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( '\A3Rev\ContactPeople\Hook_Filter', 'a3_wp_admin' ) );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( '\A3Rev\ContactPeople\Hook_Filter', 'admin_sidebar_menu_css' ) );

// Load global settings when Plugin loaded
add_action( 'plugins_loaded', array( '\A3Rev\ContactPeople\Contact_Functions', 'plugins_loaded' ), 8 );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('\A3Rev\ContactPeople\Hook_Filter', 'plugin_extra_links'), 10, 2 );

	$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->init();

	// Add upgrade notice to Dashboard pages
	add_filter( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->plugin_name . '_plugin_extension_boxes', array( '\A3Rev\ContactPeople\Hook_Filter', 'plugin_extension_box' ) );

	// Add extra link on left of Deactivate link on Plugin manager page
	add_action('plugin_action_links_'.PEOPLE_CONTACT_NAME, array('\A3Rev\ContactPeople\Hook_Filter', 'settings_plugin_links') );

	add_action('init', array('\A3Rev\ContactPeople\Admin\AddNew', 'profile_form_action') );

	add_action( 'admin_menu', array( '\A3Rev\ContactPeople\Hook_Filter', 'register_admin_screen' ), 9 );

	add_action( 'wp_enqueue_scripts', array( '\A3Rev\ContactPeople\Hook_Filter', 'frontend_scripts_register' ) );

	add_filter( 'body_class', array( '\A3Rev\ContactPeople\Hook_Filter', 'browser_body_class'), 10, 2 );

	//Ajax Sort Contact
	add_action('wp_ajax_people_update_orders', array( '\A3Rev\ContactPeople\Hook_Filter', 'people_update_orders') );
	add_action('wp_ajax_nopriv_people_update_orders', array( '\A3Rev\ContactPeople\Hook_Filter', 'people_update_orders') );

	// Include script admin plugin
	if ( in_array( basename ($_SERVER['PHP_SELF']), array('admin.php', 'edit.php') ) && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array('people-contact-manager', 'people-contact', 'people-contact-settings', 'people-category-manager' ) ) ) {
		add_action('admin_head', array('\A3Rev\ContactPeople\Hook_Filter', 'admin_header_script'));
	}

	// Check upgrade functions
add_action('plugins_loaded', 'a3_people_contact_lite_upgrade_plugin');
function a3_people_contact_lite_upgrade_plugin () {

	// Upgrade to 1.0.3
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '1.0.3') === -1){
		\A3Rev\ContactPeople\Data\Profile::install_database();
		update_option('a3rev_wp_people_contact_lite_version', '1.0.3');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.0.3.php' );
	}

	// Upgrade to 1.1.1
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '1.1.1') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '1.1.1');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.1.1.php' );
	}

	// Upgrade to 1.1.4 for Lite version
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '1.1.4') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '1.1.4');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-1.1.4.php' );
	}

	// Upgrade to 1.2.0
	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '1.2.0') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '1.2.0');

		// Build sass
		$GLOBALS[PEOPLE_CONTACT_PREFIX.'less']->plugin_build_sass();
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

		$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->set_default_settings();

		// Build sass
		$GLOBALS[PEOPLE_CONTACT_PREFIX.'less']->plugin_build_sass();
	}

	if(version_compare(get_option('a3rev_wp_people_contact_lite_version'), '3.2.3') === -1){
		update_option('a3rev_wp_people_contact_lite_version', '3.2.3');

		$people_contact_global_settings = get_option( 'people_contact_global_settings' );
		$google_map_api_key = $people_contact_global_settings['google_map_api_key'];

		update_option( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->google_map_api_key_option . '_enable', 1 );
		update_option( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->google_map_api_key_option, $google_map_api_key );
	}

	if( version_compare(get_option('a3rev_wp_people_contact_lite_version'), '3.5.0') === -1 ){
		update_option('a3rev_wp_people_contact_lite_version', '3.5.0');

		include( PEOPLE_CONTACT_DIR. '/upgrade/updates/people-contact-update-3.5.0.php' );
	}

	update_option('a3rev_wp_people_contact_lite_version', PEOPLE_CONTACT_VERSION );
	update_option('a3rev_wp_people_contact_ultimate_version', '3.5.0');

}

function people_ict_t_e( $name, $string ) {
	global $people_contact_wpml;
	$string = ( function_exists('icl_t') ? icl_t( $people_contact_wpml->plugin_wpml_name, $name, $string ) : $string );
	
	echo $string;
}

function people_ict_t__( $name, $string ) {
	global $people_contact_wpml;
	$string = ( function_exists('icl_t') ? icl_t( $people_contact_wpml->plugin_wpml_name, $name, $string ) : $string );
	
	return $string;
}
