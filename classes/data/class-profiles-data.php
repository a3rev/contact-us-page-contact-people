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
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_cup_cp_profiles'") != $table_cup_cp_profiles) {
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
		$result = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id='$id' {$where}", $output_type);
		return $result;
	}

	public static function get_maximum_order($where='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "cup_cp_profiles";
		if (trim($where) != '')
			$where = " WHERE {$where} ";
		$maximum = $wpdb->get_var("SELECT MAX(c_order) FROM {$table_name} {$where}");

		return $maximum;
	}

	public static function get_count($where='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "cup_cp_profiles";
		if (trim($where) != '')
			$where = " WHERE {$where} ";
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
		$result = $wpdb->get_results("SELECT * FROM {$table_name} {$where} {$order} {$limit}", $output_type);
		return $result;
	}

	public static function insert_row($args) {
		global $wpdb;
		extract($args);
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		$c_title = strip_tags( addslashes( $c_title ) );
		$c_name = strip_tags( addslashes( $c_name ) );
		$c_identitier = strip_tags( addslashes( $c_identitier ) );
		$c_avatar = strip_tags( addslashes( $c_avatar ) );
		$c_email = strip_tags( addslashes( $c_email ) );
		$c_phone = strip_tags( addslashes( $c_phone ) );
		$c_fax = strip_tags( addslashes( $c_fax ) );
		$c_mobile = strip_tags( addslashes( $c_mobile ) );
		$c_website = strip_tags( addslashes( $c_website ) );
		$c_about = addslashes( $c_about );
		$show_on_main_page = $show_on_main_page;
		$enable_map_marker = $enable_map_marker;
		$c_address = strip_tags( addslashes( $c_address ) );
		$c_latitude = strip_tags( addslashes( $c_latitude ) );
		$c_longitude = strip_tags( addslashes( $c_longitude ) );
		
		$c_order = self::get_maximum_order();
		$c_order++;
		$query = $wpdb->query("INSERT INTO {$table_name}( c_title, c_name, c_identitier, c_avatar, c_attachment_id, c_email, c_phone, c_fax, c_mobile, c_website, c_about, show_on_main_page, enable_map_marker, c_address, c_latitude, c_longitude, c_shortcode, c_order ) VALUES('$c_title', '$c_name', '$c_identitier', '$c_avatar', '$c_attachment_id', '$c_email', '$c_phone', '$c_fax', '$c_mobile', '$c_website', '$c_about', '$show_on_main_page', '$enable_map_marker', '$c_address', '$c_latitude', '$c_longitude', '', '$c_order' )");
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
		$c_title = strip_tags( addslashes( $c_title ) );
		$c_name = strip_tags( addslashes( $c_name ) );
		$c_identitier = strip_tags( addslashes( $c_identitier ) );
		$c_avatar = strip_tags( addslashes( $c_avatar ) );
		$c_email = strip_tags( addslashes( $c_email ) );
		$c_phone = strip_tags( addslashes( $c_phone ) );
		$c_fax = strip_tags( addslashes( $c_fax ) );
		$c_mobile = strip_tags( addslashes( $c_mobile ) );
		$c_website = strip_tags( addslashes( $c_website ) );
		$c_about = addslashes( $c_about );
		$show_on_main_page = $show_on_main_page;
		$enable_map_marker = $enable_map_marker;
		$c_address = strip_tags( addslashes( $c_address ) );
		$c_latitude = strip_tags( addslashes( $c_latitude ) );
		$c_longitude = strip_tags( addslashes( $c_longitude ) );
		$c_shortcode = strip_tags( addslashes( $c_shortcode ) );
		$query = $wpdb->query("UPDATE {$table_name} SET c_title='$c_title', c_name='$c_name', c_identitier='$c_identitier', c_avatar='$c_avatar', c_attachment_id='$c_attachment_id', c_email='$c_email', c_phone='$c_phone', c_fax='$c_fax', c_mobile='$c_mobile', c_website='$c_website', c_about='$c_about', show_on_main_page='$show_on_main_page', enable_map_marker='$enable_map_marker', c_address='$c_address', c_latitude='$c_latitude', c_longitude='$c_longitude', c_shortcode='' WHERE id='$profile_id'");
		return $query;

	}

	public static function set_lat_lng( $profile_id, $c_latitude, $c_longitude ) {
		global $wpdb;

		$table_name = $wpdb->prefix. "cup_cp_profiles";

		$query = $wpdb->query("UPDATE {$table_name} SET c_latitude='$c_latitude', c_longitude='$c_longitude' WHERE id='$profile_id'");
		
		return $query;
	}
	
	public static function reset_shortcode_to_global() {
		global $wpdb;
		global $people_email_inquiry_global_settings;
		$table_name = $wpdb->prefix. "cup_cp_profiles";
		$query = $wpdb->query("UPDATE {$table_name} SET c_shortcode='".$people_email_inquiry_global_settings['contact_form_type_shortcode']."' ");
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
		$query = $wpdb->query("UPDATE {$table_name} SET c_order='$c_order' WHERE id='$profile_id'");
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
		$result = $wpdb->query("DELETE FROM {$table_name} WHERE id='{$profile_id}'");
		return $result;
	}
}
