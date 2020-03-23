<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\ContactPeople\FrameWork\Settings {

use A3Rev\ContactPeople\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Grid View Layout Settings

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

class Grid_View_Layout extends FrameWork\Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'profile-cards';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'people_contact_grid_view_layout';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'people_contact_grid_view_layout';
	
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
				'success_message'	=> __( 'Profile Cards successfully saved.', 'contact-us-page-contact-people' ),
				'error_message'		=> __( 'Error: Profile Cards can not save.', 'contact-us-page-contact-people' ),
				'reset_message'		=> __( 'Profile Cards successfully reseted.', 'contact-us-page-contact-people' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		add_action( $this->plugin_name . '-' . trim( $this->form_key ) . '_settings_init', array( $this, 'validate_radio_values' ) );
		
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
	/* validate_radio_values()
	/* Validate Value of onoff radio type */
	/*-----------------------------------------------------------------------------------*/
	public function validate_radio_values() {
		if ( isset( $_POST['bt_save_settings'] ) && ! isset( $_POST[$this->option_name]['thumb_image_position'] ) ) {
			$settings_array = get_option( $this->option_name, array() );
			$settings_array['thumb_image_position'] = 'left';
			update_option( $this->option_name, $settings_array );
		}
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
			'name'				=> 'profile-card-type',
			'label'				=> __( 'Profile Card Type', 'contact-us-page-contact-people' ),
			'callback_function'	=> 'people_contact_grid_view_layout_settings_form',
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
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
			
			array(
            	'name' 		=> __( 'Profile Card Layout', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'profile_card_type_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Profile Image Position', 'contact-us-page-contact-people' ),
				'id' 		=> 'thumb_image_position',
				'type' 		=> 'onoff_radio',
				'class'		=> 'thumb_image_position',
				'default' 	=> 'left',
				'onoff_options' => array(
					array(
						'val' 				=> 'left',
						'text' 				=> __( 'Card Type 1. Image Left - Content Right', 'contact-us-page-contact-people' ) . ' (' . __( 'Default', 'contact-us-page-contact-people' ) . ')',
						'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ) ,
						'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ) ,
					),
					array(
						'val' 				=> 'right',
						'text' 				=> __( 'Card Type 2. Content Left - Image Right', 'contact-us-page-contact-people' ),
						'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ) ,
						'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ) ,
					),
					array(
						'val' 				=> 'top',
						'text' 				=> __( 'Card Type 3. Image Top - Content Bottom', 'contact-us-page-contact-people' ),
						'checked_label'		=> __( 'ON', 'contact-us-page-contact-people' ) ,
						'unchecked_label' 	=> __( 'OFF', 'contact-us-page-contact-people' ) ,
					),
				),			
			),
			
			array(
            	'class'		=> 'thumb_image_position_side',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Profile Image Width', 'contact-us-page-contact-people' ),
				'desc'		=> '%. ' . __( 'Set as a percentage of total Profile Card width.', 'contact-us-page-contact-people' ),
				'id' 		=> 'thumb_image_wide',
				'type' 		=> 'slider',
				'default'	=> 25,
				'min'		=> 25,
				'max'		=> 50,
				'increment'	=> 1,
			),
			array(
            	'class'		=> 'thumb_image_position_top',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Image Height', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Dynamic image and hence profile card height will vary if uploaded profile images are different dimensions.", 'contact-us-page-contact-people' ),
				'id' 		=> 'fix_thumb_image_height',
				'class'		=> 'fix_thumb_image_height',
				'type' 		=> 'switcher_checkbox',
				'default'	=> '1',
				'checked_value'		=> 1,
				'unchecked_value' 	=> 0,
				'checked_label'		=> __( 'FIXED', 'contact-us-page-contact-people' ),
				'unchecked_label' 	=> __( 'DYNAMIC', 'contact-us-page-contact-people' ),
			),

			array(
            	'class'		=> 'fix_thumb_image_height_container',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Image Fixed Height', 'contact-us-page-contact-people' ),
				'desc'		=> 'px. ' . __( 'Max height of image. Example set 200px and will fix image container at 200px with image aligned to the top. Default', 'contact-us-page-contact-people' ) . ' [default_value]px',
				'id' 		=> 'thumb_image_height',
				'type' 		=> 'text',
				'default'	=> 150,
				'css'		=> 'width:40px;',
			),

			array(
            	'class'		=> 'thumb_image_position_top',
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Title Position', 'contact-us-page-contact-people' ),
				'id' 		=> 'item_title_position',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'above',
				'checked_value' 	=> 'above',
				'unchecked_value' 	=> 'below',
				'checked_label'		=> __( 'Above the image', 'contact-us-page-contact-people' ),
				'unchecked_label' 	=> __( 'Below the image', 'contact-us-page-contact-people' ),
			),
			

			array(
            	'name' 		=> __( 'Profile Card Style', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'profile_card_style_box',
                'is_box'	=> true,
           	),
			array(
            	'name' 		=> __( 'Create a Custom Profile Card Design', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Profile Card background Colour', 'contact-us-page-contact-people' ),
				'id' 		=> 'grid_view_item_background',
				'type' 		=> 'color',
				'default'	=> '#FFFFFF'
			),
			array(  
				'name' 		=> __( 'Profile Card background Hover Colour', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Just apply for Card set to show Location on Map.", 'contact-us-page-contact-people' ),
				'id' 		=> 'grid_view_item_background_hover',
				'type' 		=> 'color',
				'default'	=> '#e6f3f4'
			),
			array(  
				'name' 		=> __( 'Profile Card border', 'contact-us-page-contact-people' ),
				'id' 		=> 'grid_view_item_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#DBDBDB', 'corner' => 'square' , 'rounded_value' => 0 )
			),
			
			array(  
				'name' 		=> __( 'Card Border Shadow', 'contact-us-page-contact-people' ),
				'id' 		=> 'grid_view_item_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 1, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			array(  
				'name' => __( 'Border Padding', 'contact-us-page-contact-people' ),
				'id' 		=> 'grid_view_item_padding',
				'desc'		=> __( 'Adds padding (space) between the card borders and the card content', 'contact-us-page-contact-people' ),
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array( 
											'id' 		=> 'grid_view_item_padding_top',
	 										'name' 		=> __( 'Top', 'contact-us-page-contact-people' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
	 
	 								array(  'id' 		=> 'grid_view_item_padding_bottom',
	 										'name' 		=> __( 'Bottom', 'contact-us-page-contact-people' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
											
									array( 
											'id' 		=> 'grid_view_item_padding_left',
	 										'name' 		=> __( 'Left', 'contact-us-page-contact-people' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
											
									array( 
											'id' 		=> 'grid_view_item_padding_right',
	 										'name' 		=> __( 'Right', 'contact-us-page-contact-people' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
	 							)
			),

			array(
            	'name' 		=> __( 'Profile Card Fonts', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'profile_card_fonts_box',
                'is_box'	=> true,
           	),
           	array(  
				'name' 		=> __( 'Title / Position', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_title_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '18px', 'face' => 'Arial, sans-serif', 'style' => 'bold', 'color' => '#000000' )
			),
			array(  
				'name' 		=> __( 'Profile Name', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_profile_name_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '16px', 'face' => 'Arial, sans-serif', 'style' => 'bold', 'color' => '#000000' )
			),
			array(  
				'name' 		=> __( 'Contact Icon Text', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_contact_icons_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '13px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' )
			),
			array(  
				'name' 		=> __( 'Link Colour', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_link_color',
				'type' 		=> 'color',
				'default'	=> '#1A8FB2'
			),
			array(  
				'name' 		=> __( 'Link Hover', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_link_hover_color',
				'type' 		=> 'color',
				'default'	=> '#23527c'
			),
			array(  
				'name' 		=> __( 'About Profile', 'contact-us-page-contact-people' ),
				'id' 		=> 'card_about_profile_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '13px', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' )
			),

			array(
            	'name' 		=> __( 'Profile Card Image Style', 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'profile_image_style_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Image Style', 'contact-us-page-contact-people' ),
				'id' 		=> 'item_image_border_type',
				'type' 		=> 'select',
				'default'	=> 'rounder',
				'options'		=> array( 
					'rounder' 	=> __( 'Rounded Border', 'contact-us-page-contact-people' ), 
					'square' 	=> __( 'Square Border', 'contact-us-page-contact-people' ), 
					'no' 		=> __( 'Flat Image', 'contact-us-page-contact-people' ), 
				),
				'css' 		=> 'width:160px;',
			),
			array(  
				'name' 		=> __( 'Image Background Colour', 'contact-us-page-contact-people' ),
				'desc' 		=> __( "Shows in Image Padding area or if image is transparent. Default", 'contact-us-page-contact-people' ) . ' [default_value]',
				'id' 		=> 'item_image_background',
				'type' 		=> 'color',
				'default'	=> '#FFFFFF'
			),
			array(  
				'name' 		=> __( 'Image Border', 'contact-us-page-contact-people' ),
				'id' 		=> 'item_image_border',
				'type' 		=> 'border_styles',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#DBDBDB' )
			),
			
			array(  
				'name' 		=> __( 'Image Shadow', 'contact-us-page-contact-people' ),
				'id' 		=> 'item_image_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 1, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			array(  
				'name' 		=> __( 'Image Padding', 'contact-us-page-contact-people' ),
				'desc' 		=> 'px. ' . __( "Padding (space) between the image border and the image.", 'contact-us-page-contact-people' ),
				'id' 		=> 'item_image_padding',
				'type' 		=> 'text',
				'default'	=> 2,
				'css' 		=> 'width:40px;',
			),


			array(
            	'name' 		=> __( 'Profile Card No Image', 'contact-us-page-contact-people' ),
				'desc'		=> __( "Upload custom 'No Image' image, .jpg or.png format.", 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'default_profile_image_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Default Profile Image', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[default_profile_image]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/no-avatar.png',
				'separate_option'	=> true,
			),
			array(
            	'name' 		=> __( 'Profile Card Contact Icons', 'contact-us-page-contact-people' ),
				'desc'		=> __( "Delete default icons. Upload custom icons, transparent .png format, 16px by 16px recommended size.", 'contact-us-page-contact-people' ),
                'type' 		=> 'heading',
                'id'		=> 'profile_contact_icons_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Phone icon', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[grid_view_icon_phone]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/p_icon_phone.png',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Fax icon', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[grid_view_icon_fax]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/p_icon_fax.png',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Mobile icon', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[grid_view_icon_mobile]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/p_icon_mobile.png',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Email icon', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[grid_view_icon_email]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/p_icon_email.png',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Email Link Text', 'contact-us-page-contact-people' ),
				'desc'		=> __( 'Set hyperlink text that shows to the right of the Email icon. Default', 'contact-us-page-contact-people' ) . " '[default_value]'",
				'id' 		=> 'people_contact_grid_view_icon[grid_view_email_text]',
				'type' 		=> 'text',
				'default'	=> __( 'Click Here', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Website icon', 'contact-us-page-contact-people' ),
				'id' 		=> 'people_contact_grid_view_icon[grid_view_icon_website]',
				'type' 		=> 'upload',
				'default'	=> PEOPLE_CONTACT_IMAGE_URL.'/p_icon_website.png',
				'separate_option'	=> true,
			),
			array(  
				'name' 		=> __( 'Website Link Text', 'contact-us-page-contact-people' ),
				'desc'		=> __( 'Set hyperlink text that shows to the right of the Website icon. Default', 'contact-us-page-contact-people' ) . " '[default_value]'",
				'id' 		=> 'people_contact_grid_view_icon[grid_view_website_text]',
				'type' 		=> 'text',
				'default'	=> __( 'Visit Website', 'contact-us-page-contact-people' ),
				'separate_option'	=> true,
			),
        ));
	}

	public function include_script() {
	?>
<script>
(function($) {
$(document).ready(function() {
	if ( $("input.fix_thumb_image_height:checked").val() != '1') {
		$(".fix_thumb_image_height_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	}
	if ( $("input.thumb_image_position:checked").val() == 'top') {
		$(".thumb_image_position_side").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	} else {
		$(".thumb_image_position_top").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
		$(".fix_thumb_image_height_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
	}
	$(document).on( "a3rev-ui-onoff_radio-switch", '.thumb_image_position', function( event, value, status ) {
		$(".thumb_image_position_side").attr('style','display:none;');
		$(".thumb_image_position_top").attr('style','display:none;');
		$(".fix_thumb_image_height_container").attr('style','display:none;');
		if ( value == 'top' && status == 'true' ) {
			$(".thumb_image_position_top").slideDown();
			$(".thumb_image_position_side").slideUp();
			if ( $("input.fix_thumb_image_height:checked").val() == '1') {
				$(".fix_thumb_image_height_container").slideDown();
			}
		} else if ( status == 'true' ) {
			$(".thumb_image_position_top").slideUp();
			$(".thumb_image_position_side").slideDown();
			$(".fix_thumb_image_height_container").slideUp();
		}
	});
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.fix_thumb_image_height', function( event, value, status ) {
		$(".fix_thumb_image_height_container").attr('style','display:none;');
		if ( status == 'true' ) {
			$(".fix_thumb_image_height_container").slideDown();
		} else {
			$(".fix_thumb_image_height_container").slideUp();
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
 * people_contact_grid_view_layout_settings_form()
 * Define the callback function to show subtab content
 */
function people_contact_grid_view_layout_settings_form() {
	global $people_contact_grid_view_layout_settings;
	$people_contact_grid_view_layout_settings->settings_form();
}

}
