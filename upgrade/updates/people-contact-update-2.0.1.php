<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$people_contact_grid_view_layout = get_option('people_contact_grid_view_layout', array() );

$people_contact_grid_view_style = get_option('people_contact_grid_view_style', array() );

$people_contact_grid_view_layout = array_merge( $people_contact_grid_view_style, $people_contact_grid_view_layout );

$people_contact_grid_view_image_style = get_option('people_contact_grid_view_image_style', array() );

$people_contact_grid_view_layout = array_merge( $people_contact_grid_view_image_style, $people_contact_grid_view_layout );

update_option('people_contact_grid_view_layout', $people_contact_grid_view_layout);



$people_email_inquiry_global_settings = get_option('people_email_inquiry_global_settings', array() );

$people_email_inquiry_contact_form_settings = get_option('people_email_inquiry_contact_form_settings', array() );

$people_email_inquiry_global_settings = array_merge( $people_email_inquiry_contact_form_settings, $people_email_inquiry_global_settings );

$people_email_inquiry_popup_form_style = get_option('people_email_inquiry_popup_form_style', array() );

$people_email_inquiry_global_settings = array_merge( $people_email_inquiry_popup_form_style, $people_email_inquiry_global_settings );

$people_email_inquiry_3rd_contact_form_settings = get_option('people_email_inquiry_3rd_contact_form_settings', array() );

$people_email_inquiry_global_settings = array_merge( $people_email_inquiry_3rd_contact_form_settings, $people_email_inquiry_global_settings );

$people_email_inquiry_fancybox_popup_settings = get_option('people_email_inquiry_fancybox_popup_settings', array() );

$people_email_inquiry_global_settings = array_merge( $people_email_inquiry_fancybox_popup_settings, $people_email_inquiry_global_settings );

$people_email_inquiry_colorbox_popup_settings = get_option('people_email_inquiry_colorbox_popup_settings', array() );

$people_email_inquiry_global_settings = array_merge( $people_email_inquiry_colorbox_popup_settings, $people_email_inquiry_global_settings );

update_option('people_email_inquiry_global_settings', $people_email_inquiry_global_settings);

$people_contact_widget_maps = get_option('people_contact_widget_maps', array() );

update_option('widget_hide_maps_frontend', $people_contact_widget_maps['widget_hide_maps_frontend']);

global $wpdb;

$sql = "ALTER TABLE " . $wpdb->prefix . "cup_cp_profiles ADD `enable_map_marker` TINYINT(1) NOT NULL DEFAULT '1' AFTER `c_about` ;";
$wpdb->query( $sql );

$sql = "ALTER TABLE " . $wpdb->prefix . "cup_cp_profiles ADD `show_on_main_page` TINYINT(1) NOT NULL DEFAULT '1' AFTER `c_about` ;";
$wpdb->query( $sql );


