<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\base;

defined('ABSPATH') or die('You can not access the file directly');

class Activate extends BaseController
{
	// Main method that activates the plugin
	public static function activate()
	{
		self::add_custom_roles();
		self::setup_tables();
		flush_rewrite_rules();

		self::push_default_data();
		// flush_rewrite_rules();
	}

	// Method for adding custom roles
	private static function add_custom_roles()
	{
		foreach (self::$custom_roles as $custom_role) {
			remove_role($custom_role['role']);
			add_role($custom_role['role'], $custom_role['display_name'], array('read' => true, 'upload_files' => true));
		}
	}

	// Method for setting up tables required for the plugin into wp database
	private static function setup_tables()
	{
		self::setup_addon_table();
		self::setup_applications_table();
		self::setup_category_table();
		self::setup_courses_table();
		self::setup_currencies_table();
		self::setup_enrolment_table();
		self::setup_general_settings_table();
		self::setup_instructor_settings_table();
		self::setup_languages_table();
		self::setup_lessons_table();
		self::setup_message_table();
		self::setup_message_thread_table();
		self::setup_payment_table();
		self::setup_payment_settings_table();
		self::setup_payout_table();
		self::setup_rating_table();
		self::setup_sections_table();
		self::setup_users_table();
		self::setup_wishlist_table();
		self::setup_page_settings_table();

		// version : 1.2 changes
		self::setup_certificate_settings_table();
		self::setup_certificates_table();
		self::setup_aws_settings_table();
		self::setup_live_class_settings_table();
		self::setup_live_classes_table();

		// version 1.3 changes
		self::setup_questions_table();
		self::setup_quiz_results_table();
	}

	private static function setup_addon_table()
	{
		$table = self::$tables['addons'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`unique_identifier` varchar(255) NOT NULL,
			`version` varchar(255) DEFAULT NULL,
			`status` int(11) NOT NULL,
			`created_at` int(11) DEFAULT NULL,
			`updated_at` int(11) DEFAULT NULL,
			`about` longtext,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}
	private static function setup_applications_table()
	{
		$table = self::$tables['applications'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) DEFAULT NULL,
			`address` longtext,
			`phone` varchar(255) DEFAULT NULL,
			`message` longtext,
			`document` varchar(255) DEFAULT NULL,
			`status` int(11) DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}
	private static function setup_category_table()
	{
		$table = self::$tables['categories'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`parent_category_id` int(11) DEFAULT '0',
			`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`is_featured` int(11) DEFAULT '0',
			`thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_courses_table()
	{
		$table = self::$tables['courses'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`user_id` int(11) DEFAULT NULL,
			`short_description` longtext COLLATE utf8_unicode_ci,
			`description` longtext COLLATE utf8_unicode_ci,
			`requirements` longtext COLLATE utf8_unicode_ci,
			`outcomes` longtext COLLATE utf8_unicode_ci,
			`category_id` int(11) DEFAULT NULL,
			`sub_category_id` int(11) DEFAULT NULL,
			`language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`section` longtext COLLATE utf8_unicode_ci,
			`is_free_course` int(11) DEFAULT NULL,
			`price` double DEFAULT NULL,
			`discount_flag` int(11) DEFAULT '0',
			`discounted_price` int(11) DEFAULT NULL,
			`level` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
			`thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`preview_video_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`preview_video_provider` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`meta_keywords` longtext COLLATE utf8_unicode_ci,
			`meta_description` longtext COLLATE utf8_unicode_ci,
			`banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`avg_rating` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
			`slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPRESSED;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_currencies_table()
	{
		$table = self::$tables['currencies'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) DEFAULT NULL,
			`code` varchar(255) DEFAULT NULL,
			`symbol` varchar(255) DEFAULT NULL,
			`paypal_supported` int(11) DEFAULT NULL,
			`stripe_supported` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_enrolment_table()
	{
		$table = self::$tables['enrolment'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_general_settings_table()
	{
		$table = self::$tables['general_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_page_settings_table()
	{
		$table = self::$tables['page_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_instructor_settings_table()
	{
		$table = self::$tables['instructor_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_languages_table()
	{
		$table = self::$tables['languages'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_lessons_table()
	{
		$table = self::$tables['lessons'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`duration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`section_id` int(11) DEFAULT NULL,
			`video_provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`video_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`lesson_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`attachment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`attachment_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`summary` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`order` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_message_table()
	{
		$table = self::$tables['message'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`message_id` int(11) NOT NULL AUTO_INCREMENT,
			`message_thread_code` longtext,
			`message` longtext,
			`sender` longtext,
			`timestamp` longtext,
			`read_status` int(11) DEFAULT NULL,
			PRIMARY KEY (`message_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_message_thread_table()
	{
		$table = self::$tables['message_thread'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`message_thread_id` int(11) NOT NULL AUTO_INCREMENT,
			`message_thread_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
			`receiver` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
			`last_message_timestamp` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`message_thread_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_payment_table()
	{
		$table = self::$tables['payment'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) DEFAULT NULL,
			`payment_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`amount` double DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`admin_revenue` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`instructor_revenue` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`paypal_pay_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`session_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_payment_settings_table()
	{
		$table = self::$tables['payment_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_payout_table()
	{
		$table = self::$tables['payout'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) DEFAULT NULL,
			`payment_type` varchar(255) DEFAULT NULL,
			`amount` double DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`status` int(11) DEFAULT '0',
			`paypal_pay_id` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_rating_table()
	{
		$table = self::$tables['rating'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`rating` double DEFAULT NULL,
			`user_id` int(11) DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`review` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_sections_table()
	{
		$table = self::$tables['sections'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`order` int(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_users_table()
	{
		$table = self::$tables['users'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`social_links` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`biography` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`role` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			`last_modified` int(11) DEFAULT NULL,
			`watch_history` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`title` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`paypal_keys` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`stripe_keys` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`verification_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`status` int(11) DEFAULT NULL,
			`wp_user_id` int(11) DEFAULT NULL,
			`profile_image_path` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_wishlist_table()
	{
		$table = self::$tables['wishlist'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`course_id` int(11) DEFAULT NULL,
			`date_added` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}


	private static function setup_certificate_settings_table()
	{
		$table = self::$tables['certificate_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_aws_settings_table()
	{
		$table = self::$tables['aws_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_live_class_settings_table()
	{
		$table = self::$tables['live_class_settings'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}


	private static function setup_certificates_table()
	{
		$table = self::$tables['certificates'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` int(11) DEFAULT NULL,
			`course_id` int(11) DEFAULT NULL,
			`shareable_url` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_live_classes_table()
	{
		$table = self::$tables['live_classes'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`course_id` int(11) DEFAULT NULL,
			`date` int(11) DEFAULT NULL,
			`time` int(11) DEFAULT NULL,
			`zoom_meeting_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`zoom_meeting_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`jitsi_room` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`note` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_questions_table()
	{
		$table = self::$tables['questions'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`quiz_id` int(11) DEFAULT NULL,
			`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
			`number_of_options` int(11) DEFAULT NULL,
			`options` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`correct_answers` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`order` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	private static function setup_quiz_results_table()
	{
		$table = self::$tables['quiz_results'];
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`student_id` int(11) DEFAULT NULL,
			`quiz_id` int(11) DEFAULT NULL,
			`submitted_answers` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
			`date` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

		global $wpdb;
		$wpdb->query($sql);
	}

	// INSERT DEFAULT DATA TO TABLES
	private static function push_default_data()
	{
		self::push_to_currencies_table();
		self::push_to_general_settings_table();
		self::push_to_instructor_settings_table();
		self::push_to_languages_table();
		self::push_to_payment_settings_table();
		self::push_to_users_table();
		self::push_to_page_settings_table();

		// VERSION : 1.2
		self::push_to_certificate_settings_table();
		self::push_to_aws_settings_table();
		self::push_to_live_class_settings_table();
	}

	private static function push_to_currencies_table()
	{
		$table = self::$tables['currencies'];
		global $wpdb;
		$data['name'] = "Dollars";
		$data['code'] = "USD";
		$data['symbol'] = "$";
		$data['paypal_supported'] = "1";
		$data['stripe_supported'] = "1";

		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `name` = %s AND `code` = %s", $data['name'], $data['code']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}

	private static function push_to_general_settings_table()
	{

		$table = self::$tables['general_settings'];
		global $wpdb;

		$keys = array('system_name', 'system_email', 'address', 'phone', 'purchase_code', 'youtube_api_key', 'vimeo_api_key', 'version', 'student_email_verification', 'system_language', 'logo_lg_path', 'logo_sm_path');
		foreach ($keys as $key) {
			$data['key'] = $key;
			if ($key == "version") {
				$data['value'] = "1.3";
			} elseif ($key == "system_language") {
				$data['value'] = "en";
			} else {
				$data['value'] = "";
			}

			$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
			if (!$availability || count($availability) == 0) {
				$wpdb->insert($table, $data);
			}
		}
	}

	private static function push_to_instructor_settings_table()
	{
		$table = self::$tables['instructor_settings'];

		global $wpdb;

		$data['key'] = "allow_public_instructor";
		$data['value'] = "1";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}


		$data['key'] = "instructor_application_note";
		$data['value'] = "Fill all the required fields with valid data. Also make sure to upload any necessary document that can help to define your skills. Best of luck.";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "instructor_revenue_percentage";
		$data['value'] = "89";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "admin_revenue_percentage";
		$data['value'] = "11";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}

	private static function push_to_languages_table()
	{
		$table = self::$tables['languages'];
		global $wpdb;

		$data['name'] = "English";
		$data['code'] = "en";

		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `name` = %s AND `code` = %s", $data['name'], $data['code']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}

	private static function push_to_payment_settings_table()
	{
		$table = self::$tables['payment_settings'];

		global $wpdb;

		$data['key'] = "system";
		$data['value'] = "{\"system_currency\":\"USD\",\"currency_position\":\"left\"}";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "paypal";
		$data['value'] = "{\"active\":\"1\",\"mode\":\"sandbox\",\"currency\":\"USD\",\"sandbox_client_id\":\"sandbox-client-id\",\"sandbox_secret_key\":\"sandbox-secret-key\",\"production_client_id\":\"client-id-prod\",\"production_secret_key\":\"secret-key-prod\"}";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "stripe";
		$data['value'] = '{"active":"1","testmode":"on","currency":"USD","public_key":"pk_test_xxxxxxxxxxxxxxxxxxxxxxxx","secret_key":"sk_test_xxxxxxxxxxxxxxxxxxxxxxxx","public_live_key":"pk_live_xxxxxxxxxxxxxxxxxxxxxxxx","secret_live_key":"sk_live_xxxxxxxxxxxxxxxxxxxxxxxx"}';
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}

	private static function push_to_users_table()
	{
		$wp_object = wp_get_current_user();
		$wp_user_id = $wp_object->ID;

		// CHECK IF THE ADMIN ALREADY EXISTS
		global $wpdb;
		$table = self::$tables['users'];
		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `wp_user_id`= %d", $wp_user_id));
		if (count($result) == 0) {
			$data['wp_user_id']     =   $wp_user_id;
			$data['first_name']     =   $wp_object->user_login;
			$data['last_name']      =   "";
			$data['email']          =   $wp_object->user_email;
			$data['social_links']   =   "social_information";
			$data['biography']      =   "";
			$data['role']           =   "admin";
			$data['profile_image_path']  = "";

			// SOCIAL INFORMATION
			$social_link['facebook'] = "";
			$social_link['twitter'] = "";
			$social_link['linkedin'] = "";
			$data['social_links'] = json_encode($social_link);

			// Add paypal keys
			$paypal_info = array(
				'production_client_id' => "prod-client-id",
				'production_secret_key' => "prod-secret-key"
			);
			$data['paypal_keys'] = json_encode($paypal_info);

			// Add Stripe keys
			$stripe_info = array(
				'public_live_key' => "public_live_key",
				'secret_live_key' => "secret_live_key"
			);
			$data['stripe_keys'] = json_encode($stripe_info);

			$data['status'] = 1;
			$wpdb->insert(self::$tables['users'], $data);
		}
	}

	private static function push_to_page_settings_table()
	{
		$table = self::$tables['page_settings'];
		global $wpdb;

		// PUBLIC PAGE
		$data['key'] = "public_page";
		$data['value'] = "";

		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		// PRIVATE PAGE
		$data['key'] = "private_page";
		$data['value'] = "";

		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		// LIVE CLAS PAGE
		$data['key'] = "live_class_page";
		$data['value'] = "";

		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}



	// PUSH TO CERTIFICATE SETTINGS TABLE
	private static function push_to_certificate_settings_table()
	{
		$table = self::$tables['certificate_settings'];

		global $wpdb;

		$data['key'] = "general_font_size";
		$data['value'] = "26";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "instructor_font_size";
		$data['value'] = "17";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "date_font_size";
		$data['value'] = "17";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "certificate_text";
		$data['value'] = "This is to certify that Mr. / Ms. {student} successfully completed the course with on certificate for {course}.";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "template";
		$data['value'] = "template.jpg";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}


	private static function push_to_aws_settings_table()
	{
		$table = self::$tables['aws_settings'];

		global $wpdb;

		$data['key'] = "amazon_s3_access_key";
		$data['value'] = "amazon_s3_access_key";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "amazon_s3_secret_key";
		$data['value'] = "amazon_s3_secret_key";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "amazon_s3_bucket_name";
		$data['value'] = "amazon_s3_bucket_name";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "amazon_s3_region_code";
		$data['value'] = "amazon_s3_region_code";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}

	private static function push_to_live_class_settings_table()
	{
		$table = self::$tables['live_class_settings'];

		global $wpdb;

		$data['key'] = "zoom_api_key";
		$data['value'] = "zoom_api_key";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}

		$data['key'] = "zoom_secret_key";
		$data['value'] = "zoom_secret_key";
		$availability = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $data['key']));
		if (!$availability || count($availability) == 0) {
			$wpdb->insert($table, $data);
		}
	}
}
