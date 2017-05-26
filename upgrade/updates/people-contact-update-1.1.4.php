<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$people_contact_widget_information = get_option( 'people_contact_widget_information', array() );

if ( is_array( $people_contact_widget_information ) ) {
	if ( isset( $people_contact_widget_information['widget_content_before_maps'] ) ) 
		update_option( 'people_contact_widget_content_before_maps', $people_contact_widget_information['widget_content_before_maps'] )	;
	if ( isset( $people_contact_widget_information['widget_content_after_maps'] ) ) 
		update_option( 'people_contact_widget_content_after_maps', $people_contact_widget_information['widget_content_after_maps'] )	;
}
