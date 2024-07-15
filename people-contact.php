<?php
/*
Plugin Name: Contact Us page - Contact people LITE
Description: Instantly and easily create a simply stunning Contact Us page on almost any theme. Google location map, People Contact Profiles and a fully featured Contact Us widget. Fully responsive and easy to customize. Ultimate Version upgrade for even more features.
Version: 3.7.4
Author: a3rev Software
Author URI: https://a3rev.com/
Requires at least: 6.0
Tested up to: 6.6
Text Domain: contact-us-page-contact-people
Domain Path: /languages
License: GPLv2 or later
*/

/*
	Contact People Ultimate. Plugin for wordpress.
	Copyright © 2011 A3 Revolution Software Development team

	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/

// File Security Check
if (!defined('ABSPATH')) exit;

define('PEOPLE_CONTACT_PATH', dirname(__FILE__));
define('PEOPLE_CONTACT_TEMPLATE_PATH', PEOPLE_CONTACT_PATH . '/templates');
define('PEOPLE_CONTACT_FOLDER', dirname(plugin_basename(__FILE__)));
define('PEOPLE_CONTACT_URL', str_replace(array('http:','https:'), '', untrailingslashit(plugins_url('/', __FILE__))) );
define('PEOPLE_CONTACT_DIR', WP_PLUGIN_DIR . '/' . PEOPLE_CONTACT_FOLDER);
define('PEOPLE_CONTACT_NAME', plugin_basename(__FILE__));
define('PEOPLE_CONTACT_TEMPLATE_URL', PEOPLE_CONTACT_URL . '/templates');
define('PEOPLE_CONTACT_CSS_URL', PEOPLE_CONTACT_URL . '/assets/css');
define('PEOPLE_CONTACT_JS_URL', PEOPLE_CONTACT_URL . '/assets/js');
define('PEOPLE_CONTACT_IMAGE_URL', PEOPLE_CONTACT_URL . '/assets/images');

if (!defined("PEOPLE_CONTACT_ULTIMATE_URI")) define("PEOPLE_CONTACT_ULTIMATE_URI", "https://a3rev.com/shop/contact-people-ultimate/");

define( 'PEOPLE_CONTACT_KEY', 'contact_us_page_contact_people' );
define( 'PEOPLE_CONTACT_PREFIX', 'people_contact_' );
define( 'PEOPLE_CONTACT_VERSION', '3.7.4' );
define( 'PEOPLE_CONTACT_G_FONTS', true );

use \A3Rev\ContactPeople\FrameWork;

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';

	new \A3Rev\ContactPeople\Ajax();

	global $people_contact_wpml;
	$people_contact_wpml = new \A3Rev\ContactPeople\WPML_Functions();

	/**
	 * Plugin Framework init
	 */
	$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface'] = new FrameWork\Admin_Interface();

	global $people_contact_settings_page;
	$people_contact_settings_page = new FrameWork\Pages\People_Contact();

	$GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init'] = new FrameWork\Admin_Init();

	$GLOBALS[PEOPLE_CONTACT_PREFIX.'less'] = new FrameWork\Less_Sass();

	// End - Plugin Framework init

	global $people_contact;
	$people_contact = new \A3Rev\ContactPeople\Main();

	new \A3Rev\ContactPeople\Shortcode();

	// Gutenberg blocks init
	new \A3Rev\ContactPeople\Blocks();
	new \A3Rev\ContactPeople\Blocks\Profile();
	
} else {
	return;
}

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/contact-us-page-contact-people/contact-us-page-contact-people-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/contact-us-page-contact-people-LOCALE.mo
 * 	 	- /wp-content/plugins/contact-us-page-contact-people/languages/contact-us-page-contact-people-LOCALE.mo (which if not found falls back to)
 */
function wp_people_contact_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'contact-us-page-contact-people' );

	load_textdomain( 'contact-us-page-contact-people', WP_LANG_DIR . '/contact-us-page-contact-people/contact-us-page-contact-people-' . $locale . '.mo' );
	load_plugin_textdomain( 'contact-us-page-contact-people', false, PEOPLE_CONTACT_FOLDER.'/languages' );
}

// Editor
include 'tinymce3/tinymce.php';

include ('admin/people-contact-init.php');

/**
 * Call when the plugin is activated
 */
register_activation_hook(__FILE__, 'people_contact_install');
