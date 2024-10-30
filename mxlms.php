<?php

/**
 * @package mitxenLMS
 */
/**
 * Plugin Name: Mitxen Learning Management System
 * Plugin URI: https://codecanyon.net/user/creativeitem
 * Description: Mitxen LMS is the ultimate online learning platform. Here students and teachers are combined for sharing knowledge through a structured course-based system. Teachers or instructors can create an unlimited number of courses, upload videos and documents on their expertise, and students can enrol in these courses and make themselves skilled anytime and from anywhere.
 * Version: 1.3.0
 * Author: Creativeitem
 * Author URI: https://codecanyon.net/user/creativeitem
 * License: GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort!!!
defined('ABSPATH') or die('You can not access the file directly');

// Require once the composer autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Method runs during plugin activation
function mxlms_activate()
{
	Mxlms\base\Activate::activate();
}
register_activation_hook(__FILE__, 'mxlms_activate');

// Method runs during plugin deactivation
function mxlms_deactivate()
{
	Mxlms\base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'mxlms_deactivate');

// Initialize all the core classes of the plugin with all the necessary hooks that are needed to be registered
if (class_exists('Mxlms\\Init')) {
	Mxlms\Init::register_services();
}
