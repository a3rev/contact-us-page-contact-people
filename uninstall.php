<?php
/**
 * Contact People Uninstall
 *
 * Uninstalling deletes options, tables, and pages.
 *
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

global $wpdb;

// Delete Google Font
delete_option('contact_people_ultimate_google_api_key');
delete_option('contact_people_ultimate_google_api_key' . '_enable');
delete_transient('contact_people_ultimate_google_api_key' . '_status');
delete_option('contact_people_ultimate' . '_google_font_list');

if (get_option('a3_people_contact_lite_clean_on_deletion') == 1) {
    delete_option('contact_people_ultimate_toggle_box_open');
    delete_option('contact_people_ultimate' . '-custom-boxes');

    delete_metadata( 'user', 0, 'contact_people_ultimate' . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

    delete_option('people_contact_settings');
    delete_option('people_contact_global_settings');
    delete_option('people_contact_location_map_settings');
    delete_option('people_contact_contact_forms_settings');
    delete_option('people_contact_popup_style_settings');

    delete_option('people_contact_grid_view_style');
    delete_option('people_contact_grid_view_layout');
    delete_option('people_contact_grid_view_icon');
    delete_option('people_contact_widget_settings');
    delete_option('people_contact_widget_maps');
    delete_option('people_contact_widget_information');
    delete_option('people_contact_widget_email_contact_form');

    delete_option('people_email_inquiry_global_settings');
    delete_option('people_email_inquiry_contact_form_settings');
    delete_option('people_email_inquiry_popup_form_style');
    delete_option('people_email_inquiry_contact_success');
    delete_option('people_email_inquiry_3rd_contact_form_settings');
    delete_option('people_email_inquiry_fancybox_popup_settings');
    delete_option('people_email_inquiry_colorbox_popup_settings');

    delete_option('contact_arr');
    delete_option('profile_email_page_id');
    delete_option('a3rev_wp_people_contact_plugin');
    delete_option('a3rev_wp_people_contact_message');
    delete_option('contact_us_page_id');
    delete_option('a3rev_wp_people_contact_version');

    delete_option('a3_people_contact_lite_clean_on_deletion');

    global $wpdb;
    $wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cup_cp_profiles');

    $string_ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}icl_strings WHERE context='a3 Contact People' ");
    if (is_array($string_ids) && count($string_ids) > 0) {
        $str        = join(',', array_map('intval', $string_ids));
        $wpdb->query("
            DELETE s.*, t.* FROM {$wpdb->prefix}icl_strings s LEFT JOIN {$wpdb->prefix}icl_string_translations t ON s.id = t.string_id
            WHERE s.id IN ({$str})");
        $wpdb->query("DELETE FROM {$wpdb->prefix}icl_string_positions WHERE string_id IN ({$str})");
    }
}