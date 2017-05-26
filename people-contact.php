<?php
/*
Plugin Name: Contact Us page - Contact people LITE
Description: Instantly and easily create a simply stunning Contact Us page on almost any theme. Google location map, People Contact Profiles and a fully featured Contact Us widget. Fully responsive and easy to customize. Ultimate Version upgrade for even more features.
Version: 3.0.1
Author: a3rev Software
Author URI: https://a3rev.com/
Requires at least: 4.1
Tested up to: 4.7.5
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
if (!defined("PEOPLE_CONTACT_ULTIMATE_URI")) define("PEOPLE_CONTACT_ULTIMATE_URI", "http://a3rev.com/shop/contact-people-ultimate/");
if (!defined("PEOPLE_CONTACT_DOCS_URI")) define("PEOPLE_CONTACT_DOCS_URI", "http://docs.a3rev.com/user-guides/plugins-extensions/wordpress/contact-us-page-contact-people/");

define('PEOPLE_CONTACT_VERSION', '3.0.1');

include ('admin/admin-ui.php');
include ('admin/admin-interface.php');

include ('classes/class-wpml-functions.php');

include ('admin/admin-pages/admin-settings-page.php');

include ('admin/admin-init.php');
include ('admin/less/sass.php');

include ('classes/data/class-profiles-data.php');

include ('classes/class-people-contact-functions.php');
include ('classes/class-people-contact-hook.php');
include ('classes/class-people-contact.php');

include ('admin/classes/class-people-contact-addnew.php');
include ('admin/classes/class-people-contact-manager-panel.php');
include ('admin/classes/class-people-category-manager-panel.php');

include ('shortcodes/class-people-contact-shortcodes.php');
include ('widgets/class-people-contact-widgets.php');

// Editor
include 'tinymce3/tinymce.php';

include ('admin/people-contact-init.php');

/**
 * Call when the plugin is activated
 */
register_activation_hook(__FILE__, 'people_contact_install');
?>