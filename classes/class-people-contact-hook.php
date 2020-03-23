<?php
/**
 * People Contact Hook Filter
 *
 * Table Of Contents
 *
 * register_admin_screen()
 * contact_manager_load_only_script()
 * add_new_load_only_script()
 * admin_header_script()
 * people_update_orders()
 * a3_wp_admin()
 * admin_sidebar_menu_css()
 * plugin_extra_links()
 *
 */

namespace A3Rev\ContactPeople;

class Hook_Filter
{
	public static function register_admin_screen () {
		global $query_string, $current_user;
		$current_user_id = $current_user->user_login;

		$contact_manager = add_menu_page( __('Contact Us', 'contact-us-page-contact-people' ), __('Contact Us', 'contact-us-page-contact-people' ), 'manage_options', 'people-contact-manager', array( __NAMESPACE__ . '\Admin\Profile_Manager', 'admin_screen' ), null, '27.222');

		$profile = add_submenu_page('people-contact-manager', __( 'Profiles', 'contact-us-page-contact-people' ), __( 'Profiles', 'contact-us-page-contact-people' ), 'manage_options', 'people-contact-manager', array( __NAMESPACE__ . '\Admin\Profile_Manager', 'admin_screen' ) );

		$add_new = add_submenu_page('people-contact-manager', __( 'Add New Profile', 'contact-us-page-contact-people' ), __( 'Add New Profile', 'contact-us-page-contact-people' ), 'manage_options', 'people-contact', array( __NAMESPACE__ . '\Admin\AddNew', 'admin_screen_add_edit' ) );

		$categories_page = add_submenu_page('people-contact-manager', __( 'Groups', 'contact-us-page-contact-people' ), __( 'Groups', 'contact-us-page-contact-people' ), 'manage_options', 'people-category-manager', array( __NAMESPACE__ . '\Admin\Category_Manager', 'admin_screen' ) );

		add_action( "admin_print_scripts-" . $contact_manager, array( __CLASS__, 'contact_manager_load_only_script') );
		add_action( "admin_print_scripts-" . $profile, array( __CLASS__, 'contact_manager_load_only_script') );
		add_action( "admin_print_scripts-" . $add_new, array( __CLASS__, 'add_new_load_only_script') );
		add_action( "admin_print_scripts-" . $categories_page, array( __CLASS__, 'category_manager_load_only_script') );

	} // End register_admin_screen()

	public static function add_google_fonts() {
		global $people_contact_loaded_google_fonts;

		global $people_contact_grid_view_layout;
		global $people_email_inquiry_global_settings;

		if ( $people_contact_loaded_google_fonts ) return ;
		if ( $people_email_inquiry_global_settings['contact_form_type_other'] == 1 ) return ;

		$people_contact_loaded_google_fonts = true;

		$google_fonts = array(
			$people_contact_grid_view_layout['card_title_font']['face'],
			$people_contact_grid_view_layout['card_profile_name_font']['face'],
			$people_contact_grid_view_layout['card_contact_icons_font']['face'],
			$people_contact_grid_view_layout['card_about_profile_font']['face'],

			$people_email_inquiry_global_settings['inquiry_contact_heading_font']['face'],
			$people_email_inquiry_global_settings['inquiry_form_site_name_font']['face'],
			$people_email_inquiry_global_settings['inquiry_form_profile_position_font']['face'],
			$people_email_inquiry_global_settings['inquiry_form_profile_name_font']['face'],
			$people_email_inquiry_global_settings['inquiry_contact_popup_text']['face'],
			$people_email_inquiry_global_settings['inquiry_contact_button_font']['face']
		);

		$GLOBALS[PEOPLE_CONTACT_PREFIX.'fonts_face']->generate_google_webfonts( $google_fonts );
	}

	public static function frontend_scripts_register() {
		global $post;
		global $contact_people_page_id;

		if ( is_admin() ) return;
		global $is_IE;
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		$_upload_dir = wp_upload_dir();

		wp_register_style( 'people_contact_style', PEOPLE_CONTACT_CSS_URL.'/style'.$suffix.'.css', array(), PEOPLE_CONTACT_VERSION );

		if ( file_exists( $_upload_dir['basedir'] . '/sass/wp_contact_people'.$suffix.'.css' ) ) {
			wp_register_style( 'wp_contact_people', str_replace(array('http:','https:'), '', $_upload_dir['baseurl'] ) . '/sass/wp_contact_people'.$suffix.'.css', array( 'people_contact_style' ), $GLOBALS[PEOPLE_CONTACT_PREFIX.'less']->get_css_file_version() );
		}
		if ( $is_IE ) {
			wp_register_script( 'respondjs', PEOPLE_CONTACT_JS_URL . '/respond-ie.js', array( 'jquery' ) );
		}

		if ( $post
			&& ( $contact_people_page_id == $post->ID
				|| has_shortcode( $post->post_content, 'people_contacts' )
				|| has_shortcode( $post->post_content, 'people_contact' ) ) ) {

			self::add_google_fonts();

			if ( wp_style_is( 'wp_contact_people', 'registered' ) ) {
				wp_enqueue_style( 'wp_contact_people' );
			} else {
				wp_enqueue_style( 'people_contact_style' );
				include( PEOPLE_CONTACT_DIR . '/templates/customized_style.php' );
			}

			wp_enqueue_script( 'respondjs' );

			add_action( 'wp_head', array( __CLASS__, 'fix_window_console_ie' ) );
			add_action( 'wp_head', array( __CLASS__, 'footer_default_form_scripts' ) );

		}
	}

	public static function frontend_scripts_enqueue() {
		if ( is_admin() ) return;

		self::add_google_fonts();
		if ( wp_style_is( 'wp_contact_people', 'registered' ) ) {
			wp_enqueue_style( 'wp_contact_people' );
		} else {
			wp_enqueue_style( 'people_contact_style' );
			include( PEOPLE_CONTACT_DIR . '/templates/customized_style.php' );
		}

		wp_enqueue_script( 'respondjs' );

		self::fix_window_console_ie();
		self::footer_default_form_scripts();
	}

	public static function fix_window_console_ie() {
		global $people_contact_loaded_fix_console_ie;

		if ( $people_contact_loaded_fix_console_ie ) return ;

		$people_contact_loaded_fix_console_ie = true;
	?>
    <script type="text/javascript">
		if(!(window.console && console.log)) {
			console = {
				log: function(){},
				debug: function(){},
				info: function(){},
				warn: function(){},
				error: function(){}
			};
		}
	</script>

    <?php
	}

	public static function footer_default_form_scripts() {

		global $people_email_inquiry_global_settings;

		if ( 1 == $people_email_inquiry_global_settings['contact_form_type_other'] ) {
			return ;
		}

		global $people_default_form_scripts;

		if ( $people_default_form_scripts ) {
			return ;
		}

		$people_default_form_scripts = true;

		if ( wp_script_is( 'people-ei-default-form', 'enqueued' ) ) {
			return;
		}

		wp_enqueue_script( 'people-ei-default-form', PEOPLE_CONTACT_JS_URL . '/default-form.js', array( 'jquery' ), PEOPLE_CONTACT_VERSION, true );

		wp_localize_script( 'people-ei-default-form',
			'people_ei_default_vars',
			apply_filters( 'people_ei_default_vars', array(
				'ajax_url'          => admin_url( 'admin-ajax.php', 'relative' ),
				'email_valid_error' => people_ict_t__( 'Default Form - Contact Email Error', __( 'Please enter valid Email address', 'contact-us-page-contact-people' ) ),
				'required_error'    => people_ict_t__( 'Default Form - Required Error', __( 'is required', 'contact-us-page-contact-people' ) ),
				'agree_terms_error' => people_ict_t__( 'Default Form - Agree Terms Error', __( 'You need to agree to the website terms and conditions if want to submit this inquiry', 'contact-us-page-contact-people' ) ),
				'security_nonce'    => wp_create_nonce( 'people-ei-default-form' )
			) )
		);

	}

	public static function browser_body_class( $classes, $class = '' ) {
		if ( !is_array($classes) ) $classes = array();

		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		if($is_lynx) $classes[] = 'lynx';
		elseif($is_gecko) $classes[] = 'gecko';
		elseif($is_opera) $classes[] = 'opera';
		elseif($is_NS4) $classes[] = 'ns4';
		elseif($is_safari) $classes[] = 'safari';
		elseif($is_chrome) $classes[] = 'chrome';
		elseif($is_IE) {
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$browser = substr( "$browser", 25, 8);
			if ($browser == "MSIE 7.0"  ) {
				$classes[] = 'ie7';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 6.0" ) {
				$classes[] = 'ie6';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 8.0" ) {
				$classes[] = 'ie8';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 9.0" ) {
				$classes[] = 'ie9';
				$classes[] = 'ie';
			} else {
				$classes[] = 'ie';
			}
		} else { $classes[] = 'unknown'; }

		if( $is_iphone ) $classes[] = 'iphone';

		return $classes;

	}

	public static function contact_manager_load_only_script(){
		wp_enqueue_script('jquery-ui-sortable');
	}

	public static function category_manager_load_only_script() {
		wp_enqueue_script('jquery-ui-sortable');
	}

	public static function add_new_load_only_script(){

		$google_map_api_key = '';
		if ( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->is_valid_google_map_api_key() ) {
			$google_map_api_key = get_option( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->google_map_api_key_option, '' );
		}

		if ( ! empty( $google_map_api_key ) ) {
			$google_map_api_key = '&key=' . $google_map_api_key;
		}

		wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
		wp_enqueue_script('maps-googleapis','https://maps.googleapis.com/maps/api/js?v=3.exp' . $google_map_api_key );
	}

	public static function admin_header_script() {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'people_contact_manager', PEOPLE_CONTACT_CSS_URL.'/admin.css' );
		wp_enqueue_style( 'people_contact_manager' );
	}

	public static function people_update_orders() {
		check_ajax_referer( 'people_update_orders', 'security' );
		$updateRecordsArray  = $_REQUEST['recordsArray'];
		$i = 0;
		foreach ($updateRecordsArray as $recordIDValue) {
			$i++;
			Data\Profile::update_order( absint( $recordIDValue ), $i);
		}
		die();
	}

	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', PEOPLE_CONTACT_CSS_URL . '/a3_wp_admin.css' );
	}

	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-people-contact-admin-sidebar-menu-style', PEOPLE_CONTACT_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public static function plugin_extension_box( $boxes = array() ) {
		
		$support_box = '<a href="'.$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->support_url.'" target="_blank" alt="'.__('Go to Support Forum', 'contact-us-page-contact-people' ).'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_wordpress_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WordPress Plugins', 'contact-us-page-contact-people' ).'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/free-wordpress-plugins.png" /></a>';
		$boxes[] = array(
			'content' => $free_wordpress_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

        $rating_box = '<div style="margin-bottom: 5px; font-size: 12px;"><strong>' . __('Is this plugin is just what you needed? If so', 'contact-us-page-contact-people' ) . '</strong></div>';
        $rating_box .= '<a href="https://wordpress.org/support/view/plugin-reviews/contact-us-page-contact-people?filter=5#postform" target="_blank" alt="'.__('Submit Review for Plugin on WordPress', 'contact-us-page-contact-people' ).'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/a-5-star-rating-would-be-appreciated.png" /></a>';
        $boxes[] = array(
            'content' => $rating_box,
            'css' => 'border: none; padding: 0; background: none;'
        );

        $follow_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via', 'contact-us-page-contact-people' ) . '</div>';
		$follow_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'contact-us-page-contact-people' ).'" style="margin-right: 5px;"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/follow-facebook.png" /></a> ';
		$follow_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'contact-us-page-contact-people' ).'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/follow-twitter.png" /></a>';
		$boxes[] = array(
			'content' => $follow_box,
			'css' => 'border-color: #3a5795;'
		);

		return $boxes;
	}

	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != PEOPLE_CONTACT_NAME) {
			return $links;
		}

		$links[] = '<a href="'.$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->support_url.'" target="_blank">'.__('Support', 'contact-us-page-contact-people' ).'</a>';
		return $links;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="admin.php?page=people-contact-settings">' . __( 'Settings', 'contact-us-page-contact-people' ) . '</a>' ), $actions );

		return $actions;
	}

	public static function map_notice() {
		global $widget_hide_maps_frontend;
		global $people_contact_location_map_settings;

		if (  ( 1 == $widget_hide_maps_frontend && 1 == $people_contact_location_map_settings['hide_maps_frontend'] ) || $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->is_valid_google_map_api_key() ) return;
	?>
		<div class="error below-h2" style="display:block !important; margin-left:2px;">
			<p><?php echo sprintf( __( 'Warning: No Google Maps API key was found - Maps may not show without a key. Go to the <a href="%s">Google Maps API option box</a>, enter your key and Save Changes or switch Google Maps OFF <a href="%s">here</a> and <a href="%s">here</a>.' , 'contact-us-page-contact-people' ), admin_url( 'admin.php?page=people-contact-settings&box_open=google_map_api_key_settings_box' ), admin_url( 'admin.php?page=people-contact-settings&box_open=google_map_settings_box' ), admin_url( 'admin.php?page=people-contact-settings&tab=contact-widget&box_open=contact_widget_map_settings_box' ) ); ?></p>
		</div>
	<?php
	}
}
