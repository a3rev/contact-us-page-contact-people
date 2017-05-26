<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$contacts = get_option('contact_arr');
if ( is_array($contacts) && count($contacts) > 0 ) {
	$i = 0;
	foreach ( $contacts as $key => $value ) {
		$i++;
		$new_value = array();
		foreach ( $value as $key => $field ) {
			$new_value[$key] = esc_attr( stripslashes( $field ) );
		}
		$new_value['c_order'] = $i;
		People_Contact_Profile_Data::insert_row( $new_value );
	}
}