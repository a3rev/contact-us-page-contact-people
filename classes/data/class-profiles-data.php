<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * People Contact Profile Data
 *
 * Table Of Contents
 *
 * install_database()
 * get_row()
 * get_maximum_order()
 * get_count()
 * get_results()
 * insert_row()
 * update_row()
 * update_items_order()
 * update_order()
 * delete_rows()
 * delete_row()
 */

namespace A3Rev\ContactPeople\Data;

class Profile
{

	public static function install_database() {
		global $wpdb;
		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if( ! empty($wpdb->charset ) ) $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			if( ! empty($wpdb->collate ) ) $collate .= " COLLATE $wpdb->collate";
		}
	
		$table_cup_cp_profiles = $wpdb->prefix. "cup_cp_profiles";
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- table name is internal, not user input
		if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_cup_cp_profiles ) ) != $table_cup_cp_profiles ) {
			$sql = "CREATE TABLE IF NOT EXISTS `{$table_cup_cp_profiles}` (
				  `id` int(11) NOT NULL auto_increment,
				  `c_title` blob NOT NULL,
				  `c_name` blob NOT NULL,
				  `c_identitier` varchar(250) NOT NULL,
				  `c_avatar` text NOT NULL,
				  `c_attachment_id` bigint(20) NOT NULL DEFAULT '0',
				  `c_email` varchar(250) NOT NULL,
				  `c_phone` varchar(250) NOT NULL,
				  `c_fax` varchar(250) NOT NULL,
				  `c_mobile` varchar(250) NOT NULL,
				  `c_website` text NOT NULL,
				  `c_about` blob NOT NULL,
				  `show_on_main_page` tinyint(1) NOT NULL DEFAULT '1',
				  `enable_map_marker` tinyint(1) NOT NULL DEFAULT '1',
				  `c_address` blob NOT NULL,
				  `c_latitude` varchar(250) NOT NULL,
				  `c_longitude` varchar(250) NOT NULL,
				  `c_shortcode` text NOT NULL,
				  `c_order` int(11) NOT NULL,
				  PRIMARY KEY  (`id`)
				) $collate; ";
			$wpdb->query($sql);
		}
	}	

	public static function get_row($id, $where='', $output_type='OBJECT') {
		global $wpdb;
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		if (trim($where) != '')
			$where = ' AND '.$where;
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $where is an internal SQL fragment built by plugin code, not user input
		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id=%d {$where}", absint( $id ) ), $output_type );
		return $result;
	}

	public static function get_maximum_order($where='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "cup_cp_profiles";
		if (trim($where) != '')
			$where = " WHERE {$where} ";
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $where is an internal SQL fragment built by plugin code, not user input
		$maximum = $wpdb->get_var("SELECT MAX(c_order) FROM {$table_name} {$where}");

		return $maximum;
	}

	public static function get_count($where='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "cup_cp_profiles";
		if (trim($where) != '')
			$where = " WHERE {$where} ";
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $where is an internal SQL fragment built by plugin code, not user input
		$count = $wpdb->get_var("SELECT COUNT(id) FROM {$table_name} {$where}");

		return $count;
	}

	public static function get_results($where='', $order='', $limit ='', $output_type='OBJECT') {
		global $wpdb;
		$table_name = $wpdb->prefix . "cup_cp_profiles";
		if (trim($where) != '')
			$where = " WHERE {$where} ";
		if (trim($order) != '')
			$order = " ORDER BY {$order} ";
		if (trim($limit) != '')
			$limit = " LIMIT {$limit} ";
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $where/$order/$limit are internal SQL fragments built by plugin code, not user input
		$result = $wpdb->get_results("SELECT * FROM {$table_name} {$where} {$order} {$limit}", $output_type);
		return $result;
	}

	public static function insert_row($args) {
		global $wpdb;
		extract($args);
		$table_name = $wpdb->prefix. "cup_cp_profiles";

		$c_order = self::get_maximum_order();
		$c_order++;

		$query = $wpdb->insert(
			$table_name,
			array(
				'c_title'           => strip_tags( $c_title ),
				'c_name'            => strip_tags( $c_name ),
				'c_identitier'      => strip_tags( $c_identitier ),
				'c_avatar'          => strip_tags( $c_avatar ),
				'c_attachment_id'   => absint( $c_attachment_id ),
				'c_email'           => strip_tags( $c_email ),
				'c_phone'           => strip_tags( $c_phone ),
				'c_fax'             => strip_tags( $c_fax ),
				'c_mobile'          => strip_tags( $c_mobile ),
				'c_website'         => strip_tags( $c_website ),
				'c_about'           => $c_about,
				'show_on_main_page' => absint( $show_on_main_page ),
				'enable_map_marker' => absint( $enable_map_marker ),
				'c_address'         => strip_tags( $c_address ),
				'c_latitude'        => strip_tags( $c_latitude ),
				'c_longitude'       => strip_tags( $c_longitude ),
				'c_shortcode'       => '',
				'c_order'           => absint( $c_order ),
			),
			array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d' )
		);

		if ($query) {
			$profile_id = $wpdb->insert_id;
			return $profile_id;
		} else {
			return false;
		}
	}

	public static function update_row($args) {
		global $wpdb;
		extract($args);
		$table_name = $wpdb->prefix. "cup_cp_profiles";

		$query = $wpdb->update(
			$table_name,
			array(
				'c_title'           => strip_tags( $c_title ),
				'c_name'            => strip_tags( $c_name ),
				'c_identitier'      => strip_tags( $c_identitier ),
				'c_avatar'          => strip_tags( $c_avatar ),
				'c_attachment_id'   => absint( $c_attachment_id ),
				'c_email'           => strip_tags( $c_email ),
				'c_phone'           => strip_tags( $c_phone ),
				'c_fax'             => strip_tags( $c_fax ),
				'c_mobile'          => strip_tags( $c_mobile ),
				'c_website'         => strip_tags( $c_website ),
				'c_about'           => $c_about,
				'show_on_main_page' => absint( $show_on_main_page ),
				'enable_map_marker' => absint( $enable_map_marker ),
				'c_address'         => strip_tags( $c_address ),
				'c_latitude'        => strip_tags( $c_latitude ),
				'c_longitude'       => strip_tags( $c_longitude ),
				'c_shortcode'       => '',
			),
			array( 'id' => absint( $profile_id ) ),
			array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' ),
			array( '%d' )
		);
		return $query;
	}

	public static function set_lat_lng( $profile_id, $c_latitude, $c_longitude ) {
		global $wpdb;

		$table_name = $wpdb->prefix. "cup_cp_profiles";

		$query = $wpdb->update(
			$table_name,
			array(
				'c_latitude'  => strip_tags( $c_latitude ),
				'c_longitude' => strip_tags( $c_longitude ),
			),
			array( 'id' => absint( $profile_id ) ),
			array( '%s', '%s' ),
			array( '%d' )
		);

		return $query;
	}
	
	public static function reset_shortcode_to_global() {
		global $wpdb;
		global $people_email_inquiry_global_settings;
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		$shortcode = strip_tags( $people_email_inquiry_global_settings['contact_form_type_shortcode'] );
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table_name is derived from $wpdb->prefix, not user input
		$wpdb->query( $wpdb->prepare( "UPDATE {$table_name} SET c_shortcode=%s", $shortcode ) );
	}

	public static function update_items_order( $item_orders=array() ) {
		if (is_array($item_orders) && count($item_orders) > 0) {
			foreach ($item_orders as $profile_id => $c_order) {
				self::update_order($profile_id, $c_order);
			}
		}
	}

	public static function update_order($profile_id, $c_order=0) {
		global $wpdb;
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		$query = $wpdb->update(
			$table_name,
			array( 'c_order' => absint( $c_order ) ),
			array( 'id'      => absint( $profile_id ) ),
			array( '%d' ),
			array( '%d' )
		);
		return $query;
	}

	public static function delete_rows($items=array()) {
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $profile_id) {
				self::delete_row($profile_id);
			}
		}
	}

	public static function delete_row($profile_id) {
		global $wpdb;
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		$result = $wpdb->delete( $table_name, array( 'id' => absint( $profile_id ) ), array( '%d' ) );
		return $result;
	}
}
