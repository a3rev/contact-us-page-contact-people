<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

$sql = "ALTER TABLE " . $wpdb->prefix . "cup_cp_profiles ADD `c_attachment_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `c_avatar` ;";
$wpdb->query( $sql );