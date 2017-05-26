<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$people_contact_grid_view_layout = get_option( 'people_contact_grid_view_layout', array() );
$people_contact_global_settings = array(
	'grid_view_team_title' 	=> $people_contact_grid_view_layout['grid_view_team_title'],
	'grid_view_col'			=> $people_contact_grid_view_layout['grid_view_col'],
);
update_option( 'people_contact_global_settings', $people_contact_global_settings );

$people_contact_location_map_settings = get_option( 'people_contact_location_map_settings', array() );
$people_contact_location_map_settings['map_width_responsive'] = $people_contact_location_map_settings['map_width'];
$people_contact_location_map_settings['map_width_fixed'] = $people_contact_location_map_settings['map_width'];
update_option( 'people_contact_location_map_settings', $people_contact_location_map_settings );