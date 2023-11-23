<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\ContactPeople\FrameWork\Settings {

use A3Rev\ContactPeople\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Contact Widget Global Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class Contact_Widget extends FrameWork\Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'contact-widget';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'people_contact_widget_information';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'people_contact_widget_information';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Contact Widget Settings successfully saved.', 'contact-us-page-contact-people' ),
				'error_message'		=> __( 'Error: Contact Widget Settings can not save.', 'contact-us-page-contact-people' ),
				'reset_message'		=> __( 'Contact Widget Settings successfully reseted.', 'contact-us-page-contact-people' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {		
		$GLOBALS[$this->plugin_prefix.'admin_interface']->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {		
		$GLOBALS[$this->plugin_prefix.'admin_interface']->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'global-settings',
			'label'				=> __( 'Settings', 'contact-us-page-contact-people' ),
			'callback_function'	=> 'people_contact_contact_widget_global_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {		
		$output = '';
		$output .= $GLOBALS[$this->plugin_prefix.'admin_interface']->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {

		$privacy_policy_url = '#';

  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
            	'name' 		=> __( 'Help Notes', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_help_notes_box',
                'is_box'	=> true,
           	),
			array(
            	'name' 		=> __( 'Contact Us Widget', 'contact-us-page-contact-people' ),
				'desc'		=> sprintf( __( 'This plugin includes a <a href="%s" target="_blank">Contact Us Widget</a>. Use it to add Business / Organization Details, information and a general Contact Us form to the Contact Us Page sidebar. All of the Widget settings are here on the Contact Widget tab. Use the plugins built in Contact Form or add a custom form by shortcode from a Contact Form plugin of your choice.', 'contact-us-page-contact-people' ), admin_url('widgets.php') ),
                'type' 		=> 'heading',
           	),
			array(
            	'name' 		=> __( 'Contact Page Sidebar', 'contact-us-page-contact-people' ),
				'desc'		=> sprintf( __( 'We recommend that you create a new sidebar and assign it to the Contact Us Page. If you do you can then add the Contact Widget to that sidebar and it will only show on the Contact Us page. We recommend you install this plugin <a href="%s" target="_blank">WooSidebars</a> and use it to create the custom sidebar.', 'contact-us-page-contact-people' ), 'http://wordpress.org/plugins/woosidebars/'),
                'type' 		=> 'heading',
           	),

           	array(
            	'name' 		=> __( 'Widget Contact Details', 'contact-us-page-contact-people' ),
				'desc'		=> __( "Add contact details to show in the widget, empty fields don't show on front end.", 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_details_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Address', 'contact-us-page-contact-people' ),
				'id' 		=> 'widget_info_address',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Phone', 'contact-us-page-contact-people' ),
				'id' 		=> 'widget_info_phone',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Fax', 'contact-us-page-contact-people' ),
				'id' 		=> 'widget_info_fax',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Mobile', 'contact-us-page-contact-people' ),
				'id' 		=> 'widget_info_mobile',
				'type' 		=> 'text',
				'default'	=> ''
			),
			array(  
				'name' 		=> __( 'Visible Email address', 'contact-us-page-contact-people' ),
				'desc'		=> __( 'NOT Recommended! Open email addresses on a site allow spammers to scrape them. It is very bad form to have open email addresses anywhere on a site.', 'contact-us-page-contact-people' ),
				'id' 		=> 'widget_info_email',
				'type' 		=> 'text',
				'default'	=> ''
			),
			
			array(
            	'name' 		=> __( 'Widget Custom Content', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_content_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Content before Map', 'contact-us-page-contact-people' ),
				'desc'		=> __( "Content will show above map on widget. Leave empty and nothing shows on the frontend.", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_content_before_maps',
				'type' 		=> 'wp_editor',
				'default'	=> '',
				'textarea_rows'	=> 15,
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Content after Map', 'contact-us-page-contact-people' ),
				'desc'		=> __( "Content will show below map on widget. Leave empty and nothing shows on the frontend.", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_content_after_maps',
				'type' 		=> 'wp_editor',
				'default'	=> '',
				'textarea_rows'	=> 15,
				'separate_option'	=> true,
			),


			array(
            	'name' 		=> __( 'Widget Email Contact Form', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'email_contact_form_box',
                'is_box'	=> true,
           	),
           	array(  
				'name' 		=> __( 'Default Contact Form', 'contact-us-page-contact-people' ),
				'class'		=> 'widget_show_contact_form',
				'id' 		=> 'people_contact_widget_email_contact_form[widget_show_contact_form]',
				'type' 		=> 'onoff_radio',
				'default' 	=> 0,
				'onoff_options' => array(
					array(
						'val' 				=> 1,
						'text' 				=> '',
						'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ) ,
						'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ) ,
					),
					
				),
				'separate_option'	=> true,
			),
           	array(  
				'name' 		=> __( 'Create Form By Shortcode', 'contact-us-page-contact-people' ),
				'desc'		=> __( 'Create the widget email contact form by entering a shortcode from any contact form plugin.', 'contact-us-page-contact-people' ),
				'class'		=> 'widget_show_contact_form',
				'id' 		=> 'people_contact_widget_email_contact_form[widget_show_contact_form]',
				'type' 		=> 'onoff_radio',
				'default' 	=> 0,
				'onoff_options' => array(
					array(
						'val' 				=> 0,
						'text' 				=> '',
						'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ) ,
						'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ) ,
					),
					
				),
				'separate_option'	=> true,
			),
			array(
            	'name' 		=> __( 'Form Shortcode', 'contact-us-page-contact-people' ),
				'desc'		=> __( 'Create the widget contact us form by entering a shortcode from any contact form plugin.', 'contact-us-page-contact-people' ),
				'class'		=> 'widget_show_contact_form_another_plugin',
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_3rd_form_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Form Shortcode', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_input_shortcode]',
				'type' 		=> 'text',
				'default'	=> '',
				'separate_option'	=> true,
			),
			
			array(
            	'name' 		=> __( 'Form Settings', 'contact-us-page-contact-people' ),
            	'class'		=> 'widget_show_contact_form_default',
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_default_form_box',
                'is_box'	=> true,

           	),
			array(
            	'name' 		=> __( "Email 'From' Settings", 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( '"From" Name', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Leave empty and your site title will be used", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_email_from_name]',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('blogname'),
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( '"From" Email Address', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Leave empty and your WordPress admin email address will be used", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_email_from_address]',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('admin_email'),
				'separate_option'	=> true,
			),
			array(
            	'name' 		=> __( "Sender 'Request A Copy'", 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Send Copy to Sender', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Gives users a checkbox option to send a copy of the Inquiry email to themselves", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_send_copy]',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ),
				'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,
			),
			
			array(
            	'name' 		=> __( 'Email Delivery', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Inquiry Email goes to', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Leave empty and your WordPress admin email address will be used", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_email_to]',
				'type' 		=> 'text',
				'default'	=> get_bloginfo('admin_email'),
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'CC', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Leave empty and no email is sent", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[widget_email_cc]',
				'type' 		=> 'text',
				'default'	=> '',
				'separate_option'	=> true,
			),

			array(
            	'name' 		=> __( 'GDPR Compliance', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'class'		=> 'widget_show_contact_form_default',
                'id'		=> 'contact_widget_gdpr_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Show acceptance checkbox', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Gives users a checkbox to agree terms and conditions, this field is required if you turn it ON.", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_email_contact_form[acceptance]',
				'class'		=> 'acceptance',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value' 	=> 'no',
				'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ),
				'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,
			),

			array(
				'class'		=> 'show_acceptance_yes',
				'type' 		=> 'heading',
			),
			array(  
				'name' 		=> __( 'Information Text', 'contact-us-page-contact-people' ),
				'desc' 		=> __( 'Enter informational text and a link to your privacy policy (Optional). Leave blank and nothing will show.', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_information_text',
				'type' 		=> 'wp_editor',
				'textarea_rows'		=> 15,
				'default'	=> sprintf( __( 'The information you enter here will be sent directly to the recipient. It is not stored on this sites database. Read more in our <a href="%s" target="_blank">Privacy Policy</a>', 'contact-us-page-contact-people' ), $privacy_policy_url ),
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Acceptance Text', 'contact-us-page-contact-people' ),
				'desc' 		=> __( 'Text will show on the right of the acceptance checkbox. (Required)', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_condition_text',
				'type' 		=> 'wp_editor',
				'textarea_rows'		=> 10,
				'default'	=> __( 'I have read and agree to the website terms and conditions', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,
			),


			array(
            	'name' 		=> __( 'Widget Google Map', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'contact_widget_map_settings_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Show Map', 'contact-us-page-contact-people' ),
				'class'		=> 'widget_hide_maps_frontend',
				'id' 		=> 'widget_hide_maps_frontend',
				'type' 		=> 'onoff_checkbox',
				'default' 	=> 0,
				'checked_value'		=> 0,
				'unchecked_value' 	=> 1,
				'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ),
				'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,

			),
			array(
				'class'		=> 'widget_maps_container',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Marker Location', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_maps[widget_location]',
				'desc'		=> '</span><div style="font-size: 13px; margin-top: 10px;">'.__( '* Tip - drag and drop the map marker to the required location', 'contact-us-page-contact-people' ).'</div><div id="map_canvas"></div><span>',
				'class'		=> 'widget_location',
				'type' 		=> 'text',
				'default'	=> 'Australia',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Zoom Level', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_maps[widget_zoom_level]',
				'class'		=> 'widget_zoom_level',
				'type' 		=> 'slider',
				'min'		=> 1,
				'max'		=> 19,
				'default'	=> 16,
				'increment'	=> 1,
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Map Type', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_maps[widget_map_type]',
				'class'		=> 'widget_map_type chzn-select',
				'type' 		=> 'select',
				'default'	=> 'ROADMAP',
				'options'		=> array( 
					'ROADMAP' 	=> 'ROADMAP', 
					'SATELLITE' => 'SATELLITE', 
					'HYBRID' 	=> 'HYBRID',
					'TERRAIN'	=> 'TERRAIN',
				),
				'css' 		=> 'width:120px;',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Map Height', 'contact-us-page-contact-people' ),
				'desc'		=> 'px',
				'id' 		=> 'people_contact_widget_maps[widget_map_height]',
				'class'		=> 'widget_map_height',
				'type' 		=> 'text',
				'default'	=> 150,
				'css' 		=> 'width:60px;',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Map Callout Text', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Text or HTML that will be output when you click on the map marker for your location.", 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_widget_maps[widget_maps_callout_text]',
				'class'		=> 'widget_maps_callout_text',
				'type' 		=> 'textarea',
				'css'		=> 'height:80px;',
				'separate_option'	=> true,
			),
        ));
	}

	public function include_google_map() {
		$widget_hide_maps_frontend = get_option( 'widget_hide_maps_frontend' );
		if ( 1 == $widget_hide_maps_frontend ) return;

		$google_map_api_key = '';
		if ( $GLOBALS[$this->plugin_prefix.'admin_init']->is_valid_google_map_api_key() ) {
			$google_map_api_key = get_option( $GLOBALS[$this->plugin_prefix.'admin_init']->google_map_api_key_option, '' );
		}

		if ( ! empty( $google_map_api_key ) ) {
			$google_map_api_key = '&key=' . $google_map_api_key;
		} else {
			return;
		}

		wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
		wp_enqueue_script('maps-googleapis','https://maps.googleapis.com/maps/api/js?v=3.exp' . $google_map_api_key );

		global $people_contact_widget_maps;
		$googleapis_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($people_contact_widget_maps['widget_location']).'&sensor=false' . $google_map_api_key;
		$response = wp_remote_get( $googleapis_url, array( 'timeout' => 120 ) );
		$geodata = wp_remote_retrieve_body( $response );
		$geodata = json_decode($geodata);
		$center_lat = $geodata->results[0]->geometry->location->lat;
		$center_lng = $geodata->results[0]->geometry->location->lng;
		$latlng_center = $latlng = $center_lat.','.$center_lng;
	?>
<style>
.a3rev_panel_container #map_canvas {
	width: 300px;
	height: <?php echo $people_contact_widget_maps['widget_map_height']; ?>px;
	margin-top: 5px;
}
</style>
<script type="text/javascript">
var geocoder;
var map;
var marker;

function initialize(){
//MAP
  var latlng_center = new google.maps.LatLng(<?php echo $latlng_center; ?>);
  var options = {
	zoom: <?php echo $people_contact_widget_maps['widget_zoom_level']; ?>,
	center: latlng_center,
	mapTypeId: google.maps.MapTypeId.<?php echo $people_contact_widget_maps['widget_map_type']; ?>
  };

  map = new google.maps.Map(document.getElementById("map_canvas"), options);

  //GEOCODER
  geocoder = new google.maps.Geocoder();

  marker = new google.maps.Marker({
	map: map,
	draggable: true,
	position: latlng_center
  });

}

(function($) {
jQuery(document).ready(function ($) {

	initialize();

	function a3_people_reload_map() {
		var zoom_level = parseInt( $('.a3rev-ui-slide-result-container input').val() );
		var map_type = $('.widget_map_type').val();
		var map_height = $('.widget_map_height').val();

		$('.a3rev_panel_container #map_canvas').css('height', map_height);
		map.setZoom(zoom_level);

		if ( 'SATELLITE' == map_type ) {
			map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
		} else if( 'HYBRID' == map_type ) {
			map.setMapTypeId(google.maps.MapTypeId.HYBRID);
		} else if ( 'TERRAIN' == map_type ) {
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
		} else {
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
		}

		google.maps.event.trigger(map, "resize"); //this fix the problem with not completely map
	}

	$('#contact_widget_map_settings_box .a3rev_panel_box_handle .a3-plugin-ui-panel-box').on('click', function(){
		a3_people_reload_map();
		$(this).addClass('google_map_canvas_resized');
	});

	$('.a3rev-ui-slide-container #people_contact_widget_information_people_contact_widget_maps_div').on('slidechange', function(){
		a3_people_reload_map();
	});

	$('.widget_map_type').on('change', function(){
		a3_people_reload_map();
	});

	$('.widget_map_height').on('change', function(){
		a3_people_reload_map();
	});

	$(function() {
		$(".widget_location").autocomplete({
		  //This bit uses the geocoder to fetch address values
		  source: function(request, response) {
			geocoder.geocode( {'address': request.term }, function(results, status) {
			  response($.map(results, function(item) {
				return {
				  label:  item.formatted_address,
				  value: item.formatted_address,
				  latitude: item.geometry.location.lat(),
				  longitude: item.geometry.location.lng()
				}
			  }));
			})
		  },
		  //This bit is executed upon selection of an address
		  select: function(event, ui) {
			var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
			marker.setPosition(location);
			map.setCenter(location);
		  }
		});
  	});

	//Add listener to marker for reverse geocoding
	google.maps.event.addListener(marker, 'drag', function() {
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
			  	$('.widget_location').val(results[0].formatted_address);
			}
		  }
		});
	});

	google.maps.event.addListener(marker, 'dragend', function() {
		map.setCenter(marker.getPosition());
	});

	var infowindow = new google.maps.InfoWindow({
		content: "loading..."
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent($('.widget_maps_callout_text').val());
		infowindow.open(marker.get('map'), marker);
	});

	google.maps.event.addListener(map, 'mouseout', function() {
		infowindow.close();
	});

	google.maps.event.addListener(map, 'resize', function() {
		setTimeout( function() {
			map.setCenter(marker.getPosition());
		}, 600 );
	});

});
})(jQuery);
</script>
    <?php
	}

	public function include_script() {
		$this->include_google_map();
?>
<script type="text/javascript">
(function($) {
jQuery(document).ready(function ($) {
	if ( $("input.acceptance:checked").val() != 'yes') {
		$(".show_acceptance_yes").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	}

	if ( $("input.widget_show_contact_form:checked").val() == 1) {
		$(".widget_show_contact_form_another_plugin").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	} else {
		$(".widget_show_contact_form_default").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	}
	$(document).on( "a3rev-ui-onoff_radio-switch", '.widget_show_contact_form', function( event, value, status ) {
		$(".widget_show_contact_form_another_plugin").attr('style','display:none;');
		$(".widget_show_contact_form_default").attr('style','display:none;');
		if ( value == 1 && status == 'true' ) {
			$(".widget_show_contact_form_default").slideDown();
			$(".widget_show_contact_form_another_plugin").slideUp();
		} else if ( status == 'true' ) {
			$(".widget_show_contact_form_default").slideUp();
			$(".widget_show_contact_form_another_plugin").slideDown();
		}
	});

	if ( $("input.widget_hide_maps_frontend:checked").val() != 0) {
		$(".widget_maps_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	}
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.widget_hide_maps_frontend', function( event, value, status ) {
		$(".widget_maps_container").attr('style','display:none;');
		if ( status == 'true' ) {
			$(".widget_maps_container").slideDown();
		} else {
			$(".widget_maps_container").slideUp();
		}
	});

	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.acceptance', function( event, value, status ) {
		$(".show_acceptance_yes").attr('style','display:none;');
		if ( value == 'yes' && status == 'true' ) {
			$(".show_acceptance_yes").slideDown();
		}
	});

});
})(jQuery);
</script>
<?php
	}
}

}

// global code
namespace {

/** 
 * people_contact_contact_widget_global_settings_form()
 * Define the callback function to show subtab content
 */
function people_contact_contact_widget_global_settings_form() {
	global $people_contact_contact_widget_global_settings;
	$people_contact_contact_widget_global_settings->settings_form();
}

}
