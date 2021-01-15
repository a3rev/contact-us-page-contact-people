<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

$sql = "ALTER TABLE " . $wpdb->prefix . "cup_cp_profiles ADD `c_identitier` varchar(250) NOT NULL DEFAULT '' AFTER `c_name` ;";
$wpdb->query( $sql );