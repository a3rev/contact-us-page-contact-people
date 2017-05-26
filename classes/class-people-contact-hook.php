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
class People_Contact_Hook_Filter
{
	public static function register_admin_screen () {
		global $query_string, $current_user;
		$current_user_id = $current_user->user_login;

		$contact_manager = add_menu_page( __('Contact Us', 'cup_cp'), __('Contact Us', 'cup_cp'), 'manage_options', 'people-contact-manager', array( 'People_Contact_Manager_Panel', 'admin_screen' ), null, '27.222');

		$profile = add_submenu_page('people-contact-manager', __( 'Profiles', 'cup_cp' ), __( 'Profiles', 'cup_cp' ), 'manage_options', 'people-contact-manager', array( 'People_Contact_Manager_Panel', 'admin_screen' ) );

		$add_new = add_submenu_page('people-contact-manager', __( 'Add New Profile', 'cup_cp' ), __( 'Add New Profile', 'cup_cp' ), 'manage_options', 'people-contact', array( 'People_Contact_AddNew', 'admin_screen_add_edit' ) );

		$categories_page = add_submenu_page('people-contact-manager', __( 'Groups', 'cup_cp' ), __( 'Groups', 'cup_cp' ), 'manage_options', 'people-category-manager', array( 'People_Category_Manager_Panel', 'admin_screen' ) );

		add_action( "admin_print_scripts-" . $contact_manager, array( 'People_Contact_Hook_Filter', 'contact_manager_load_only_script') );
		add_action( "admin_print_scripts-" . $profile, array( 'People_Contact_Hook_Filter', 'contact_manager_load_only_script') );
		add_action( "admin_print_scripts-" . $add_new, array( 'People_Contact_Hook_Filter', 'add_new_load_only_script') );
		add_action( "admin_print_scripts-" . $categories_page, array( 'People_Contact_Hook_Filter', 'category_manager_load_only_script') );

	} // End register_admin_screen()

	public static function add_google_fonts() {
		global $people_contact_loaded_google_fonts;

		global $people_contact_grid_view_layout;
		global $people_email_inquiry_global_settings;

		if ( $people_contact_loaded_google_fonts ) return ;
		if ( $people_email_inquiry_global_settings['contact_form_type_other'] == 1 ) return ;

		$people_contact_loaded_google_fonts = true;

		global $people_contact_fonts_face;

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

		$people_contact_fonts_face->generate_google_webfonts( $google_fonts );
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
			global $a3_people_contact_less;
			wp_register_style( 'wp_contact_people', str_replace(array('http:','https:'), '', $_upload_dir['baseurl'] ) . '/sass/wp_contact_people'.$suffix.'.css', array( 'people_contact_style' ), $a3_people_contact_less->get_css_file_version() );
		}
		if ( $is_IE ) {
			wp_register_script( 'respondjs', PEOPLE_CONTACT_JS_URL . '/respond-ie.js', array( 'jquery' ) );
		}

		if ( $post
			&& ( $contact_people_page_id == $post->ID
				|| has_shortcode( $post->post_content, 'people_contacts' ) ) ) {

			self::add_google_fonts();

			if ( wp_style_is( 'wp_contact_people', 'registered' ) ) {
				wp_enqueue_style( 'wp_contact_people' );
			} else {
				wp_enqueue_style( 'people_contact_style' );
				include( PEOPLE_CONTACT_DIR . '/templates/customized_style.php' );
			}

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'respondjs' );

			add_action( 'wp_head', array( 'People_Contact_Hook_Filter', 'fix_window_console_ie' ) );
			add_action( 'wp_head', array( 'People_Contact_Hook_Filter', 'frontend_footer_scripts' ) );

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

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'respondjs' );

		self::fix_window_console_ie();
		self::frontend_footer_scripts();
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

	public static function frontend_footer_scripts() {
		global $people_contact_loaded_footer_scripts;

		if ( $people_contact_loaded_footer_scripts ) return ;

		$people_contact_loaded_footer_scripts = true;

		global $people_email_inquiry_global_settings;

		if ( $people_email_inquiry_global_settings['contact_form_type_other'] == 1 ) return ;
	?>
    	<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(document).on("click", ".people_email_inquiry_form_button", function(){
					var contact_id = $(this).attr("contact_id");
					var from_page_id = $(this).attr("from-page-id");
					var people_email_inquiry_error = "";
					var people_email_inquiry_have_error = false;

					var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					var profile_email = $("#profile_email_" + contact_id).val();
					var profile_name = $("#profile_name_" + contact_id).val();
					var c_name = $("#c_name_" + contact_id).val();
					var c_subject = $("#c_subject_" + contact_id).val();
					var c_email = $("#c_email_" + contact_id).val();
					var c_phone = $("#c_phone_" + contact_id).val();
					var c_message = $("#c_message_" + contact_id).val();
					var send_copy = 0;
					if ( $("#send_copy_" + contact_id).is(':checked') )
						send_copy = 1;

					if (c_name.replace(/^\s+|\s+$/g, '') == "") {
						people_email_inquiry_error += "<?php people_ict_t_e( 'Default Form - Contact Name Error', __('Please enter your Name', 'cup_cp') ); ?>\n";
						people_email_inquiry_have_error = true;
					}
					if (c_email == "" || !filter.test(c_email)) {
						people_email_inquiry_error += "<?php people_ict_t_e( 'Default Form - Contact Email Error', __('Please enter valid Email address', 'cup_cp') ); ?>\n";
						people_email_inquiry_have_error = true;
					}
					if (c_phone.replace(/^\s+|\s+$/g, '') == "") {
						people_email_inquiry_error += "<?php people_ict_t_e( 'Default Form - Contact Phone Error', __('Please enter your Phone', 'cup_cp') ); ?>\n";
						people_email_inquiry_have_error = true;
					}
					if (c_message.replace(/^\s+|\s+$/g, '') == "") {
						people_email_inquiry_error += "<?php people_ict_t_e( 'Default Form - Contact Message Error', __('Please enter your Message', 'cup_cp') ); ?>\n";
						people_email_inquiry_have_error = true;
					}

					if (people_email_inquiry_have_error) {
						alert(people_email_inquiry_error);
						return false;
					}

					$(this).attr("disabled", "disabled");

					var wait = $('.ajax-wait');
					wait.css('display','block');

					var data = {
						action: 		"send_a_contact",
						contact_id: 	contact_id,
						from_page_id: 	from_page_id,
						profile_email:	profile_email,
						profile_name:	profile_name,
						c_name: 		c_name,
						c_email: 		c_email,
						c_phone: 		c_phone,
						c_subject:		c_subject,
						c_message: 		c_message,
						send_copy:		send_copy,
						security: 		"<?php echo wp_create_nonce("send-a-contact");?>"
					};

					$.post( '<?php echo admin_url('admin-ajax.php', 'relative');?>', data, function(response) {
						$('#people_email_inquiry_content_' + contact_id ).html(response);
						wait.css('display','none');
					});
					return false;
				});
			});
		</script>
    <?php
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
		global $people_contact_global_settings;

		$google_map_api_key = $people_contact_global_settings['google_map_api_key'];

		wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
		wp_enqueue_script('maps-googleapis','https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $google_map_api_key );
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
			People_Contact_Profile_Data::update_order($recordIDValue, $i);
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
		$support_box = '<a href="https://wordpress.org/support/plugin/contact-us-page-contact-people" target="_blank" alt="'.__('Go to Support Forum', 'cup_cp').'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_wordpress_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WordPress Plugins', 'cup_cp').'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/free-wordpress-plugins.png" /></a>';
		$boxes[] = array(
			'content' => $free_wordpress_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

        $rating_box = '<div style="margin-bottom: 5px; font-size: 12px;"><strong>' . __('Is this plugin is just what you needed? If so', 'cup_cp') . '</strong></div>';
        $rating_box .= '<a href="https://wordpress.org/support/view/plugin-reviews/contact-us-page-contact-people?filter=5#postform" target="_blank" alt="'.__('Submit Review for Plugin on WordPress', 'cup_cp').'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/a-5-star-rating-would-be-appreciated.png" /></a>';
        $boxes[] = array(
            'content' => $rating_box,
            'css' => 'border: none; padding: 0; background: none;'
        );

        $follow_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via','cup_cp') . '</div>';
		$follow_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'cup_cp').'" style="margin-right: 5px;"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/follow-facebook.png" /></a> ';
		$follow_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'cup_cp').'"><img src="'.PEOPLE_CONTACT_IMAGE_URL.'/follow-twitter.png" /></a>';
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
		$links[] = '<a href="http://docs.a3rev.com/user-guides/plugins-extensions/woocommerce/contact-us-page-contact-people/" target="_blank">'.__('Documentation', 'cup_cp').'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/contact-us-page-contact-people/" target="_blank">'.__('Support', 'cup_cp').'</a>';
		return $links;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="admin.php?page=people-contact-settings">' . __( 'Settings', 'cup_cp' ) . '</a>' ), $actions );

		return $actions;
	}
}
?>
