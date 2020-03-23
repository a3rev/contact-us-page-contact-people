<?php
/**
 * People Contact WPML Functions
 *
 * Table Of Contents
 *
 * plugins_loaded()
 * wpml_register_string()
 */

namespace A3Rev\ContactPeople;

class WPML_Functions
{	
	public $plugin_wpml_name = 'a3 Contact People';
	
	public function __construct() {
		
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		
		$this->wpml_ict_t();
		
	}
	
	/** 
	 * Register WPML String when plugin loaded
	 */
	public function plugins_loaded() {
		$this->wpml_register_dynamic_string();
		$this->wpml_register_static_string();
	}
	
	/** 
	 * Get WPML String when plugin loaded
	 */
	public function wpml_ict_t() {
		
		$plugin_name = PEOPLE_CONTACT_KEY;
		
		add_filter( $plugin_name . '_' . 'people_email_inquiry_global_settings' . '_get_settings', array( $this, 'ict_t_email_inquiry_global_settings' ) );
		add_filter( $plugin_name . '_' . 'people_contact_global_settings' . '_get_settings', array( $this, 'ict_t_contact_page_settings' ) );
		add_filter( $plugin_name . '_' . 'people_contact_widget_information' . '_get_settings', array( $this, 'ict_t_contact_widget_settings' ) );
		add_filter( $plugin_name . '_' . 'people_contact_grid_view_layout' . '_get_settings', array( $this, 'ict_t_profile_cards_settings' ) );
	}
	
	// Registry Dynamic String for WPML
	public function wpml_register_dynamic_string() {
		$people_email_inquiry_global_settings = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_email_inquiry_global_settings', array() ) );
		$people_email_inquiry_contact_success = stripslashes( get_option( 'people_email_inquiry_contact_success', '' ) );

		if ( function_exists('icl_register_string') ) {
			
			// Default Form
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - From Name', $people_email_inquiry_global_settings['email_from_name'] );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Header Title', $people_email_inquiry_global_settings['inquiry_contact_heading'] );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Site Name', $people_email_inquiry_global_settings['inquiry_form_site_name'] );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Send Button Title', $people_email_inquiry_global_settings['inquiry_contact_text_button'] );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact Success Message', $people_email_inquiry_contact_success );
			
			// Contact Page Global Settings
			$people_contact_global_settings = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_contact_global_settings', array() ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Page - Profile Cards Title', $people_contact_global_settings['grid_view_team_title'] );

			// Contact Widget Global Settings
			$people_contact_widget_information = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_contact_widget_information', array() ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Us Widget - Address', $people_contact_widget_information['widget_info_address'] );

			$people_contact_widget_content_before_maps = stripslashes( get_option( 'people_contact_widget_content_before_maps', '' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Us Widget - Content Before Map', $people_contact_widget_content_before_maps );

			$people_contact_widget_content_after_maps = stripslashes( get_option( 'people_contact_widget_content_after_maps', '' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Us Widget - Content After Map', $people_contact_widget_content_after_maps );

			$people_contact_widget_email_contact_form = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_contact_widget_email_contact_form', array() ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Us Widget - From Name', $people_contact_widget_email_contact_form['widget_email_from_name'] );

			$people_contact_widget_maps = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_contact_widget_maps', array() ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Us Widget - Map Callout Text', $people_contact_widget_maps['widget_maps_callout_text'] );

			// Profile Cards Settings
			$people_contact_grid_view_icon = array_map( array( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'], 'admin_stripslashes' ), get_option( 'people_contact_grid_view_icon', array() ) );
			icl_register_string($this->plugin_wpml_name, 'Profile Cards - Email Link Text', $people_contact_grid_view_icon['grid_view_email_text'] );
			icl_register_string($this->plugin_wpml_name, 'Profile Cards - Website Link Text', $people_contact_grid_view_icon['grid_view_website_text'] );
		}
	}
	
	// Registry Static String for WPML
	public function wpml_register_static_string() {
		if ( function_exists('icl_register_string') ) {
			
			// Default Form
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Name', __( 'Name', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Email', __( 'Email', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Phone', __( 'Phone', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Subject', __( 'Subject', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Message', __( 'Message', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Send Copy', __( 'Send a copy of this email to myself.', 'contact-us-page-contact-people' ) );

			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Email Error', __( 'Please enter valid Email addres',  'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Required Error', __( 'is required',  'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Agree Terms Error', __( 'You need to agree to the website terms and conditions if want to submit this inquiry', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Default Form - Contact Not Allow', __( "Sorry, you can't contact at this time.", 'contact-us-page-contact-people' ) );
			
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - from', __( 'from', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact from', __( 'Contact from', 'contact-us-page-contact-people' ) );
			
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Profile Name', __( 'Profile Name', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - From Page Title', __( 'From Page Title', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - From Page URL', __( 'From Page URL', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact Name', __( 'Contact Name', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact Email', __( 'Contact Email Address', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact Phone', __( 'Contact Phone', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Contact Message', __( 'Message', 'contact-us-page-contact-people' ) );
			
			icl_register_string($this->plugin_wpml_name, 'Email Inquiry - Copy', __( '[Copy]:', 'contact-us-page-contact-people' ) );
			
			icl_register_string($this->plugin_wpml_name, 'Contact Widget - Success Message', __( "Thanks for your contact - we'll be in touch with you as soon as possible!", 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Widget - Copy', __( '[Copy]:', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Widget - Name', __( 'Name', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Widget - Email Address', __( 'Email Address', 'contact-us-page-contact-people' ) );
			icl_register_string($this->plugin_wpml_name, 'Contact Widget - Message', __( 'Message', 'contact-us-page-contact-people' ) );
						
		}
	}
	
	// Default Form Settings
	public function ict_t_email_inquiry_global_settings( $current_settings = array() ) {
		if ( is_array( $current_settings ) && isset( $current_settings['email_from_name'] ) ) 
			$current_settings['email_from_name'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Email Inquiry - From Name', $current_settings['email_from_name'] ) : $current_settings['email_from_name'] );
		if ( is_array( $current_settings ) && isset( $current_settings['inquiry_contact_heading'] ) ) 
			$current_settings['inquiry_contact_heading'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Email Inquiry - Header Title', $current_settings['inquiry_contact_heading'] ) : $current_settings['inquiry_contact_heading'] );
			
		if ( is_array( $current_settings ) && isset( $current_settings['inquiry_form_site_name'] ) ) 
			$current_settings['inquiry_form_site_name'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Email Inquiry - Site Name', $current_settings['inquiry_form_site_name'] ) : $current_settings['inquiry_form_site_name'] );
		
		if ( is_array( $current_settings ) && isset( $current_settings['inquiry_contact_text_button'] ) ) 
			$current_settings['inquiry_contact_text_button'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Email Inquiry - Send Button Title', $current_settings['inquiry_contact_text_button'] ) : $current_settings['inquiry_contact_text_button'] );
		
		return $current_settings;
	}

	// Contact Page Global Settings
	public function ict_t_contact_page_settings( $current_settings = array() ) {
		if ( is_array( $current_settings ) && isset( $current_settings['grid_view_team_title'] ) ) 
			$current_settings['grid_view_team_title'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Page - Profile Cards Title', $current_settings['grid_view_team_title'] ) : $current_settings['grid_view_team_title'] );
		
		return $current_settings;
	}

	// Contact Widget Global Settings
	public function ict_t_contact_widget_settings( $current_settings = array() ) {
		if ( is_array( $current_settings ) && isset( $current_settings['widget_info_address'] ) ) 
			$current_settings['widget_info_address'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Us Widget - Address', $current_settings['widget_info_address'] ) : $current_settings['widget_info_address'] );
		if ( is_array( $current_settings ) && isset( $current_settings['widget_email_from_name'] ) ) 
			$current_settings['widget_email_from_name'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Us Widget - From Name', $current_settings['widget_email_from_name'] ) : $current_settings['widget_email_from_name'] );
		if ( is_array( $current_settings ) && isset( $current_settings['widget_maps_callout_text'] ) ) 
			$current_settings['widget_maps_callout_text'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Us Widget - Map Callout Text', $current_settings['widget_maps_callout_text'] ) : $current_settings['widget_maps_callout_text'] );
		if ( is_array( $current_settings ) && isset( $current_settings['people_contact_widget_content_before_maps'] ) ) 
			$current_settings['people_contact_widget_content_before_maps'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Us Widget - Content Before Map', $current_settings['people_contact_widget_content_before_maps'] ) : $current_settings['people_contact_widget_content_before_maps'] );
		if ( is_array( $current_settings ) && isset( $current_settings['people_contact_widget_content_after_maps'] ) ) 
			$current_settings['people_contact_widget_content_after_maps'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Contact Us Widget - Content After Map', $current_settings['people_contact_widget_content_after_maps'] ) : $current_settings['people_contact_widget_content_after_maps'] );
		
		return $current_settings;
	}

	// Profile Cards Settings
	public function ict_t_profile_cards_settings( $current_settings = array() ) {
		if ( is_array( $current_settings ) && isset( $current_settings['grid_view_email_text'] ) ) 
			$current_settings['grid_view_email_text'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Profile Cards - Email Link Text', $current_settings['grid_view_email_text'] ) : $current_settings['grid_view_email_text'] );
		if ( is_array( $current_settings ) && isset( $current_settings['grid_view_website_text'] ) ) 
			$current_settings['grid_view_website_text'] = ( function_exists('icl_t') ? icl_t( $this->plugin_wpml_name, 'Profile Cards - Website Link Text', $current_settings['grid_view_website_text'] ) : $current_settings['grid_view_website_text'] );

		return $current_settings;
	}
	
}
