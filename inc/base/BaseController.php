<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\base;

use Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class BaseController
{
	public $plugin_path;
	public $plugin_url;
	public static $plugin_url_from_theme;
	public $plugin;
	public static $text_domain = 'mxlms';
	public $slugs;
	public static $tables;
	public static $custom_roles;
	public static $plugin_id = 'mxlms';

	// Defines the public variables initiated in this class
	public function __construct()
	{
		$this->plugin_path      = plugin_dir_path(dirname(dirname(__FILE__)));
		$this->plugin_url       = plugin_dir_url(dirname(dirname(__FILE__)));
		self::$plugin_url_from_theme = plugin_dir_url(dirname(dirname(__FILE__)));
		$this->plugin           = plugin_basename(dirname(dirname(dirname(__FILE__)))) . '/mxlms.php';
		$this->slugs            = $this->define_slugs();
		self::$tables           = $this->define_tables();
		self::$custom_roles     = $this->define_custom_roles();
	}

	// Define the menu and submenu slugs
	public function define_slugs()
	{
		$slugs_array = array(
			'main'        => self::$plugin_id . '-' . 'dashboard',
			'categories'  => self::$plugin_id . '-' . 'categories',
			'courses'     => self::$plugin_id . '-' . 'courses',
			'sections'    => self::$plugin_id . '-' . 'sections',
			'students'    => self::$plugin_id . '-' . 'students',
			'instructors' => self::$plugin_id . '-' . 'instructors',
			'lessons' 	  => self::$plugin_id . '-' . 'lessons',
			'reports'     => self::$plugin_id . '-' . 'reports',
			'enrolments'  => self::$plugin_id . '-' . 'enrolments',
			'messages'    => self::$plugin_id . '-' . 'messages',
			'addon'   	  => self::$plugin_id . '-' . 'addon',
			'settings'	  => self::$plugin_id . '-' . 'settings',
			'payout'	  => self::$plugin_id . '-' . 'payout',
			'become-an-instructor'	  => self::$plugin_id . '-' . 'become-an-instructor',
			'manage-profile'	  => self::$plugin_id . '-' . 'manage-profile'
		);
		return $slugs_array;
	}

	// Define table names created by the plugin
	public function define_tables()
	{
		global $wpdb;
		$tables_array = array(
			'addons' => $wpdb->prefix . self::$plugin_id . '_' . 'addons',
			'currencies' => $wpdb->prefix . self::$plugin_id . '_' . 'currencies',
			'categories' => $wpdb->prefix . self::$plugin_id . '_' . 'categories',
			'courses' => $wpdb->prefix . self::$plugin_id . '_' . 'courses',
			'enrolment' => $wpdb->prefix . self::$plugin_id . '_' . 'enrolment',
			'users' => $wpdb->prefix . self::$plugin_id . '_' . 'users',
			'sections' => $wpdb->prefix . self::$plugin_id . '_' . 'sections',
			'lessons' => $wpdb->prefix . self::$plugin_id . '_' . 'lessons',
			'questions' => $wpdb->prefix . self::$plugin_id . '_' . 'questions',
			'general_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'general_settings',
			'smtp_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'smtp_settings',
			'instructor_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'instructor_settings',
			'payment_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'payment_settings',
			'languages' => $wpdb->prefix . self::$plugin_id . '_' . 'languages',
			'payout' => $wpdb->prefix . self::$plugin_id . '_' . 'payout',
			'applications' => $wpdb->prefix . self::$plugin_id . '_' . 'applications',
			'payment' => $wpdb->prefix . self::$plugin_id . '_' . 'payment',
			'message' => $wpdb->prefix . self::$plugin_id . '_' . 'message',
			'message_thread' => $wpdb->prefix . self::$plugin_id . '_' . 'message_thread',
			'wishlist' => $wpdb->prefix . self::$plugin_id . '_' . 'wishlist',
			'rating' => $wpdb->prefix . self::$plugin_id . '_' . 'rating',
			'page_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'page_settings',
			'certificates' => $wpdb->prefix . self::$plugin_id . '_' . 'certificates',
			'certificate_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'certificate_settings',
			'aws_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'aws_settings',
			'live_classes' => $wpdb->prefix . self::$plugin_id . '_' . 'live_classes',
			'live_class_settings' => $wpdb->prefix . self::$plugin_id . '_' . 'live_class_settings',
			'questions' => $wpdb->prefix . self::$plugin_id . '_' . 'questions',
			'quiz_results' => $wpdb->prefix . self::$plugin_id . '_' . 'quiz_results',
		);
		return $tables_array;
	}

	// Define custom roles created by the plugin
	public function define_custom_roles()
	{
		$roles_array = array(
			'student' => array(
				'role' => self::$plugin_id . '-' . 'student',
				'display_name' => 'Mitxen Student'
			),
			'instructor' => array(
				'role' => self::$plugin_id . '-' . 'instructor',
				'display_name' => 'Mitxen Instructor'
			)
		);
		return $roles_array;
	}

	// Convenient method for sanitizing an array and return a sanitized array
	public function sanitize_array($array)
	{
		$sanitized_array = array();
		$i = 0;
		foreach ($array as $value) {
			$sanitized_array[$i] = (isset($value)) ? sanitize_text_field($value) : '';
			$i++;
		}
		return $sanitized_array;
	}

	// Convenient method for verifying wp nonce (provided that nonce field name and action is same)
	public static function verify_nonce($nonce_name)
	{
		if ($_POST[$nonce_name]) {
			if (wp_verify_nonce($_POST[$nonce_name], $nonce_name)) {
				return true;
			}
			return false;
		}
		return false;
	}
}
