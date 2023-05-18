<?php
/**
 * People Contact Functions
 *
 * Table Of Contents
 *
 * plugins_loaded()
 * create_page()
 * people_contact_register_sidebar()
 */

namespace A3Rev\ContactPeople;

class Contact_Functions
{	
	
	/** 
	 * Set global variable when plugin loaded
	 */
	public static function plugins_loaded() {
		global $contact_people_page_id;

		$contact_people_page_id = self::get_page_id_from_shortcode( 'people_contacts', 'contact_us_page_id');
	}
	
	public static function contact_to_people( $profile_data = array(), $send_copy_yourself = 1 ) {
		$contact_success = get_option( 'people_email_inquiry_contact_success', '' );
		$contact_success = ( function_exists('icl_t') ? icl_t( 'a3 Contact People', 'Email Inquiry - Contact Success Message', stripslashes( $contact_success ) ) : stripslashes( $contact_success ) );
		global $people_email_inquiry_global_settings;

		$include_from_page = false;
		
		if ( trim( $contact_success ) != '') $contact_success = wpautop( wptexturize( $contact_success ) );
		else $contact_success = __("Thanks for your contact - we'll be in touch with you as soon as possible!", 'contact-us-page-contact-people' );
		
		$to_email = esc_attr( stripslashes( $profile_data['to_email'] ) );
			
		if ( $people_email_inquiry_global_settings['email_from_name'] == '' )
			$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		else
			$from_name = $people_email_inquiry_global_settings['email_from_name'];
			
		if ( $people_email_inquiry_global_settings['email_from_address'] == '' )
			$from_email = get_option('admin_email');
		else
			$from_email = $people_email_inquiry_global_settings['email_from_address'];
			
		$headers = array();
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset='. get_option('blog_charset');
		$headers[] = 'From: '.$from_name.' <'.$from_email.'>';
		$headers_yourself = $headers;
		
		$subject_yourself = people_ict_t__( 'Email Inquiry - Copy', __('[Copy]:', 'contact-us-page-contact-people' ) ).' '. stripslashes( $profile_data['subject'] );

		$from_page_html = '';
		if ( $include_from_page ) {
			$from_page_html = '
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - From Page Title', __('From Page Title', 'contact-us-page-contact-people' ) ) .'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">' . esc_html( $from_page_title ) . '</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - From Page URL', __('From Page URL', 'contact-us-page-contact-people' ) ) .'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a href="' . $from_page_url . '">' . $from_page_url . '</a></font> </td></tr>
			';
		}
			
			$content = '
	<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#eaeaea"><tbody>
	  <tr>
		<td>
		  <table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#ffffff"><tbody>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - Profile Name', __('Profile Name', 'contact-us-page-contact-people' ) ) .'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[profile_name]</font> </td></tr>
			'.$from_page_html.'
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - Contact Name', __('Contact Name', 'contact-us-page-contact-people' ) ) .'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[contact_name]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - Contact Email', __('Contact Email Address', 'contact-us-page-contact-people' ) ) .'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="mailto:[contact_email]">[contact_email]</a></font> 
			  </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - Contact Phone', __('Contact Phone', 'contact-us-page-contact-people' ) ) .'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[contact_phone]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'. people_ict_t__( 'Email Inquiry - Contact Message', __('Message', 'contact-us-page-contact-people' ) ) .'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[message]</font> 
		  </td></tr></tbody></table></td></tr></tbody></table>';
		  
			$content = str_replace('[profile_name]', esc_attr( stripslashes( $profile_data['profile_name']) ), $content);
			$content = str_replace('[contact_name]', esc_attr( stripslashes( $profile_data['contact_name']) ), $content);
			$content = str_replace('[contact_email]', esc_attr( stripslashes( $profile_data['contact_email']) ), $content);
			$content = str_replace('[contact_phone]', esc_attr( stripslashes( $profile_data['contact_phone']) ), $content);
			
			$your_message 	= esc_attr( stripslashes( strip_tags( $profile_data['message'] ) ) );
			$your_message 	= str_replace( '://', ':&#173;­//', $your_message );
			$your_message 	= str_replace( '.com', '&#173;.com', $your_message );
			$your_message 	= str_replace( '.net', '&#173;.net', $your_message );
			$your_message 	= str_replace( '.info', '&#173;.info', $your_message );
			$your_message 	= str_replace( '.org', '&#173;.org', $your_message );
			$your_message 	= str_replace( '.au', '&#173;.au', $your_message );
			$content 		= str_replace('[message]', wpautop( $your_message ), $content);
			
			$content = apply_filters('people_contact_contact_profile_content', $content, $profile_data );
			
			// Filters for the email
			add_filter( 'wp_mail_from', array( __CLASS__, 'profile_get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( __CLASS__, 'profile_get_from_name' ) );
			add_filter( 'wp_mail_content_type', array( __CLASS__, 'get_content_type' ) );
			
			wp_mail( $to_email, stripslashes( $profile_data['subject'] ), $content, $headers, '' );
			
			if ($send_copy_yourself == 1) {
				wp_mail( esc_attr( stripslashes( $profile_data['contact_email']) ) , $subject_yourself, $content, $headers_yourself, '' );
			}
			
			// Unhook filters
			remove_filter( 'wp_mail_from', array( __CLASS__, 'profile_get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( __CLASS__, 'profile_get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( __CLASS__, 'get_content_type' ) );
			return $contact_success;
	}
	
	public static function contact_to_site( $contact_data = array(), $send_copy_yourself = 1 ) {
		global $people_contact_widget_email_contact_form;
					
		$contact_success = people_ict_t__( 'Contact Widget - Success Message', __("Thanks for your contact - we'll be in touch with you as soon as possible!", 'contact-us-page-contact-people' ) );
		
		if ( $people_contact_widget_email_contact_form['widget_email_to'] == '' )
			$to_email = get_option('admin_email');
		else
			$to_email = $people_contact_widget_email_contact_form['widget_email_to'];
			
		$cc_emails = '';
		if ( trim( $people_contact_widget_email_contact_form['widget_email_cc']) != '' ) 
			$cc_emails = $people_contact_widget_email_contact_form['widget_email_cc'];
		
		if ( $people_contact_widget_email_contact_form['widget_email_from_name'] == '' )
			$from_name = get_option('blogname');
		else
			$from_name = $people_contact_widget_email_contact_form['widget_email_from_name'];
			
		if ( $people_contact_widget_email_contact_form['widget_email_from_address'] == '' )
			$from_email = get_option('admin_email');
		else
			$from_email = $people_contact_widget_email_contact_form['widget_email_from_address'];
				
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset='. get_option('blog_charset');
			$headers[] = 'From: '.$from_name.' <'.$from_email.'>';
			$headers_yourself = $headers;
			
			if (trim($cc_emails) != '') {
				$cc_emails_a = explode("," , $cc_emails);
				if (is_array($cc_emails_a) && count($cc_emails_a) > 0) {
					foreach ($cc_emails_a as $cc_email) {
						$headers[] = 'Cc: '.$cc_email;
					}
				} else {
					$headers[] = 'Cc: '.$cc_emails;
				}
			}
			
			$subject_yourself = people_ict_t__( 'Contact Widget - Copy', __('[Copy]:', 'contact-us-page-contact-people' ) ).' '. stripslashes( $contact_data['subject']) ;
			
			$content = '
	<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#eaeaea"><tbody>
	  <tr>
		<td>
		  <table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#ffffff"><tbody>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.people_ict_t__( 'Contact Widget - Name', __('Name', 'contact-us-page-contact-people' ) ).'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[contact_name]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.people_ict_t__( 'Contact Widget - Email Address', __('Email Address', 'contact-us-page-contact-people' ) ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="mailto:[contact_email]">[contact_email]</a></font> 
			  </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.people_ict_t__( 'Contact Widget - Message', __('Message', 'contact-us-page-contact-people' ) ).'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[message]</font> 
		  </td></tr></tbody></table></td></tr></tbody></table>';
		  
			$content = str_replace('[contact_name]', esc_attr( stripslashes( $contact_data['contact_name']) ), $content);
			$content = str_replace('[contact_email]', esc_attr( stripslashes( $contact_data['contact_email']) ), $content);
			
			$your_message 	= esc_attr( stripslashes( strip_tags( $contact_data['message'] ) ) );
			$your_message 	= str_replace( '://', ':&#173;­//', $your_message );
			$your_message 	= str_replace( '.com', '&#173;.com', $your_message );
			$your_message 	= str_replace( '.net', '&#173;.net', $your_message );
			$your_message 	= str_replace( '.info', '&#173;.info', $your_message );
			$your_message 	= str_replace( '.org', '&#173;.org', $your_message );
			$your_message 	= str_replace( '.au', '&#173;.au', $your_message );
			$content 		= str_replace('[message]', wpautop( $your_message ), $content);
			
			$content = apply_filters('people_contact_contact_site_content', $content, $contact_data );
			
			// Filters for the email
			add_filter( 'wp_mail_from', array( __CLASS__, 'get_from_address' ) );
			add_filter( 'wp_mail_from_name', array( __CLASS__, 'get_from_name' ) );
			add_filter( 'wp_mail_content_type', array( __CLASS__, 'get_content_type' ) );
			
			wp_mail( $to_email, stripslashes( $contact_data['subject'] ), $content, $headers, '' );
			
			if ($send_copy_yourself == 1) {
				wp_mail( esc_attr( stripslashes( $contact_data['contact_email']) ) , $subject_yourself, $content, $headers_yourself, '' );
			}
			
			// Unhook filters
			remove_filter( 'wp_mail_from', array( __CLASS__, 'get_from_address' ) );
			remove_filter( 'wp_mail_from_name', array( __CLASS__, 'get_from_name' ) );
			remove_filter( 'wp_mail_content_type', array( __CLASS__, 'get_content_type' ) );
			
			return $contact_success;
	}
	
	public static function profile_get_from_address() {
		global $people_email_inquiry_global_settings;
		if ( $people_email_inquiry_global_settings['email_from_address'] == '' )
			$from_email = get_option('admin_email');
		else
			$from_email = $people_email_inquiry_global_settings['email_from_address'];
			
		return $from_email;
	}
	
	public static function profile_get_from_name() {
		global $people_email_inquiry_global_settings;
		if ( $people_email_inquiry_global_settings['email_from_name'] == '' )
			$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		else
			$from_name = $people_email_inquiry_global_settings['email_from_name'];
			
		return $from_name;
	}
	
	public static function get_from_address() {
		global $people_contact_widget_email_contact_form;
		if ( $people_contact_widget_email_contact_form['widget_email_from_address'] == '' )
			$from_email = get_option('admin_email');
		else
			$from_email = $people_contact_widget_email_contact_form['widget_email_from_address'];
			
		return $from_email;
	}
	
	public static function get_from_name() {
		global $people_contact_widget_email_contact_form;
		if ( $people_contact_widget_email_contact_form['widget_email_from_name'] == '' )
			$from_name = ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		else
			$from_name = $people_contact_widget_email_contact_form['widget_email_from_name'];
			
		return $from_name;
	}
	
	public static function get_content_type() {
		return 'text/html';
	}

	public static function enqueue_modal_scripts() {

		if ( ! wp_script_is( 'bootstrap-modal', 'registered' ) 
			&& ! wp_script_is( 'bootstrap-modal', 'enqueued' ) ) {
			$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->register_modal_scripts();
		}

		wp_enqueue_style( 'bootstrap-modal' );

		// Don't include modal script if bootstrap is loaded by theme or plugins
		if ( wp_script_is( 'bootstrap', 'registered' ) 
			|| wp_script_is( 'bootstrap', 'enqueued' ) ) {
			
			wp_enqueue_script( 'bootstrap' );
			
			return;
		}

		if ( wp_script_is( 'wpsm_tabs_r_bootstrap-js-front', 'enqueued' ) || wp_script_is( 'wpsm_ac_bootstrap-js-front', 'enqueued' ) ) {
			return;
		}

		wp_enqueue_script( 'bootstrap-modal' );
	}

	public static function check_use_modal_popup() {
		return true;

		global $people_email_inquiry_global_settings;
		
		if ( $people_email_inquiry_global_settings['contact_form_type_other'] != '1' ) return true;
		if ( $people_email_inquiry_global_settings['contact_form_3rd_open_type'] == 'popup' ) return true;
		
		return false;
	}

	public static function modal_popup_container( $profile_email_form = '' ) {
		global $people_email_inquiry_global_settings;

		$inquiry_contact_heading = $people_email_inquiry_global_settings['inquiry_contact_heading'];
		if ( empty( $inquiry_contact_heading ) ) {
			$inquiry_contact_heading = __( 'Email Inquiry', 'contact-us-page-contact-people' );
		}

		ob_start();
	?>
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title people_email_inquiry_contact_heading"><?php echo $inquiry_contact_heading; ?></div>
				<span class="close" data-dismiss="modal" aria-label="<?php echo __( 'Close', 'contact-us-page-contact-people' ); ?>">
					<span aria-hidden="true">&times;</span>
				</span>
			</div>
			<div class="modal-body">
				<?php echo $profile_email_form; ?>
			</div>
		</div>
	</div>
	<?php
		$output = ob_get_clean();
		
		return $output;
	}
		
	/**
	 * Create Page
	 */
	public static function create_page( $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
		global $wpdb;

		$option_value = get_option($option);

		if ( $option_value > 0 && get_post( $option_value ) )
			return $option_value;

		$page_id = $wpdb->get_var( "SELECT ID FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%$page_content%'  AND `post_type` = 'page' AND post_status = 'publish' ORDER BY ID ASC LIMIT 1" );

		if ( $page_id != NULL ) :
			if ( ! $option_value )
				update_option( $option, $page_id );
			return $page_id;
		endif;

		$page_data = array(
			'post_status' 		=> 'publish',
			'post_type' 		=> 'page',
			'post_author' 		=> 1,
			'post_name' 		=> $slug,
			'post_title' 		=> $page_title,
			'post_content' 		=> $page_content,
			'post_parent' 		=> $post_parent,
			'comment_status' 	=> 'closed'
		);
		$page_id = wp_insert_post( $page_data );

		if ( class_exists('SitePress') ) {
			global $sitepress;
			$source_lang_code = $sitepress->get_default_language();
			$trid = $sitepress->get_element_trid( $page_id, 'post_page' );
			if ( ! $trid ) {
				$wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix . "icl_translations SET trid=%d WHERE element_id=%d AND language_code=%s AND element_type='post_page' " ), $page_id, $page_id, $source_lang_code );
			}
		}

		update_option( $option, $page_id );

		return $page_id;
	}

	public static function create_page_wpml( $trid, $lang_code, $source_lang_code, $slug, $page_title = '', $page_content = '' ) {
		global $wpdb;

		$element_id = $wpdb->get_var( "SELECT ID FROM " . $wpdb->posts . " AS p INNER JOIN " . $wpdb->prefix . "icl_translations AS ic ON p.ID = ic.element_id WHERE p.post_content LIKE '%$page_content%' AND p.post_type = 'page' AND p.post_status = 'publish' AND ic.trid=".$trid." AND ic.language_code = '".$lang_code."' AND ic.element_type = 'post_page' ORDER BY p.ID ASC LIMIT 1" );

		if ( $element_id != NULL ) :
			return $element_id;
		endif;

		$page_data = array(
			'post_date'			=> gmdate( 'Y-m-d H:i:s' ),
			'post_modified'		=> gmdate( 'Y-m-d H:i:s' ),
			'post_status' 		=> 'publish',
			'post_type' 		=> 'page',
			'post_author' 		=> 1,
			'post_name' 		=> $slug,
			'post_title' 		=> $page_title,
			'post_content' 		=> $page_content,
			'comment_status' 	=> 'closed'
		);
		$wpdb->insert( $wpdb->posts , $page_data);
		$element_id = $wpdb->insert_id;

		//$element_id = wp_insert_post( $page_data );

		$wpdb->insert( $wpdb->prefix . "icl_translations", array(
				'element_type'			=> 'post_page',
				'element_id'			=> $element_id,
				'trid'					=> $trid,
				'language_code'			=> $lang_code,
				'source_language_code'	=> $source_lang_code,
			) );

		return $element_id;
	}

	public static function auto_create_page_for_wpml(  $original_id, $slug, $page_title = '', $page_content = '' ) {
		if ( class_exists('SitePress') ) {
			global $sitepress;
			$active_languages = $sitepress->get_active_languages();
			if ( is_array($active_languages)  && count($active_languages) > 0 ) {
				$source_lang_code = $sitepress->get_default_language();
				$trid = $sitepress->get_element_trid( $original_id, 'post_page' );
				foreach ( $active_languages as $language ) {
					if ( $language['code'] == $source_lang_code ) continue;
					self::create_page_wpml( $trid, $language['code'], $source_lang_code, $slug.'-'.$language['code'], $page_title.' '.$language['display_name'], $page_content );
				}
			}
		}
	}

	public static function get_page_id_from_shortcode( $shortcode, $option ) {
		global $wpdb;
		global $wp_version;
		$page_id = get_option($option);
		if ( version_compare( $wp_version, '4.0', '<' ) ) {
			$shortcode = esc_sql( like_escape( $shortcode ) );
		} else {
			$shortcode = esc_sql( $wpdb->esc_like( $shortcode ) );
		}
		$page_data = null;
		if ($page_id)
			$page_data = $wpdb->get_row( "SELECT ID FROM " . $wpdb->posts . " WHERE post_content LIKE '%[{$shortcode}]%' AND ID = '".$page_id."' AND post_type = 'page' LIMIT 1" );
		if ( $page_data == null )
			$page_data = $wpdb->get_row( "SELECT ID FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%[{$shortcode}]%' AND `post_type` = 'page' ORDER BY post_date DESC LIMIT 1" );

		if ( ! empty( $page_data ) ) {
			$page_id = $page_data->ID;
		}

		// For WPML
		if ( class_exists('SitePress') && ! empty( $page_id ) ) {
			global $sitepress;
			$translation_page_data = null;
			$trid = $sitepress->get_element_trid( $page_id, 'post_page' );
			if ( $trid ) {
				$translation_page_data = $wpdb->get_row( $wpdb->prepare( "SELECT element_id FROM " . $wpdb->prefix . "icl_translations WHERE trid = %d AND element_type='post_page' AND language_code = %s LIMIT 1", $trid , $sitepress->get_current_language() ) );
				if ( $translation_page_data != null )
					$page_id = $translation_page_data->element_id;

			}
		}

		return $page_id;
	}
	
	public static function people_contact_register_sidebar() {
		global $wpdb;
		register_sidebar(array(
		  'name' => __( 'Contact Page Sidebar', 'contact-us-page-contact-people' ),
		  'id' => 'contact-us-sidebar',
		  'description' => __( 'Contact Page Widgets area.', 'contact-us-page-contact-people' ),
		  'before_widget' => '<div id="%1$s" class="widget %2$s">',
		  'after_widget' => '</div>',
		  'before_title' => '<h3>',
		  'after_title' => '</h3><div style="clear:both;"></div>'
		));
	}
	
	public static function extension_shortcode() {
		$html = '';
		$html .= '<div id="a3_plugin_shortcode_extensions">'. sprintf( __( '<a href="%s" target="_blank">Ultimate Version</a> only', 'contact-us-page-contact-people' ), PEOPLE_CONTACT_ULTIMATE_URI ) .'</div>';
		return $html;	
	}
}
