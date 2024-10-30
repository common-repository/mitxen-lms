<?php
/**
 * @package mitxen LMS
 */

namespace Mxlms\pages;

use \Mxlms\base\BaseController;
use \Mxlms\api\SettingsApi;
use \Mxlms\api\callbacks\AdminCallbacks;
use Mxlms\base\modules\Helper;

require_once(ABSPATH . '/wp-includes/pluggable.php');

defined('ABSPATH') or die('You can not access the file directly');

class Admin extends BaseController
{
	public $settings;
	public $callbacks;
	public $pages = array();
	public $sub_pages = array();
	public $excluded_subpages_id_for_student = array();
	public $excluded_subpages_id_for_instructor = array();
	public $excluded_subpages_id_for_administrator = array();

	public function __construct()
	{
		parent::__construct();
		if (Helper::get_instructor_settings('allow_public_instructor')) {
			$this->excluded_subpages_id_for_student = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
		} else {
			$this->excluded_subpages_id_for_student = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		}

		$this->excluded_subpages_id_for_instructor	= [0, 2, 3, 4, 7, 8, 10];
		$this->excluded_subpages_id_for_administrator	= [7, 9, 10];
	}

	// Method that sets the main page of the plugin
	public function set_pages()
	{
		$logged_in_user_role = Helper::get_current_user_role();
		if ($logged_in_user_role == "administrator" || $logged_in_user_role == "student" || $logged_in_user_role == "instructor") {
			$this->pages = array(
				array(
					'page_title' => esc_html__('Dashboard', self::$text_domain),
					'menu_title' => 'Mitxen-LMS',
					'capability' => 'read',
					'menu_slug' => $this->slugs['main'],
					'callback' => array($this->callbacks, 'dashboard'),
					'icon_url' => 'dashicons-welcome-learn-more',
					'position' => 1
				)
			);
		} else {
			$this->pages = array();
		}
	}

	// Method that sets information of all the sub menus present in the plugin

	public function set_sub_pages()
	{
		$this->sub_pages = array(
			array( // 0
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Categories', self::$text_domain),
				'menu_title' => esc_html__('Categories', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['categories'],
				'callback' => array($this->callbacks, 'categories')
			),
			array( // 1
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Courses', self::$text_domain),
				'menu_title' => esc_html__('Courses', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['courses'],
				'callback' => array($this->callbacks, 'courses')
			),
			array( // 2
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Students', self::$text_domain),
				'menu_title' => esc_html__('Students', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['students'],
				'callback' => array($this->callbacks, 'students')
			),
			array( // 3
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Instructors', self::$text_domain),
				'menu_title' => esc_html__('Instructors', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['instructors'],
				'callback' => array($this->callbacks, 'instructors')
			),
			array( // 4
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Enrolments', self::$text_domain),
				'menu_title' => esc_html__('Enrolments', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['enrolments'],
				'callback' => array($this->callbacks, 'enrolments')
			),
			array( // 5
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Reports', self::$text_domain),
				'menu_title' => esc_html__('Reports', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['reports'],
				'callback' => array($this->callbacks, 'reports')
			),
			array( // 6
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Internal Messaging', self::$text_domain),
				'menu_title' => esc_html__('Messaging', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['messages'],
				'callback' => array($this->callbacks, 'messages')
			),
			array( // 7
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Addon Manager', self::$text_domain),
				'menu_title' => esc_html__('Addon Manager', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['addon'],
				'callback' => array($this->callbacks, 'addon')
			),
			array( // 8
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Settings', self::$text_domain),
				'menu_title' => esc_html__('Settings', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['settings'],
				'callback' => array($this->callbacks, 'settings')
			),
			array( // 9
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Instructor Payout', self::$text_domain),
				'menu_title' => esc_html__('Instructor Payout', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['payout'],
				'callback' => array($this->callbacks, 'payout')
			),
			array( // 10
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Become an Instructor', self::$text_domain),
				'menu_title' => esc_html__('Become an Instructor', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['become-an-instructor'],
				'callback' => array($this->callbacks, 'become_an_instructor')
			),
			array( // 11
				'parent_slug' => $this->slugs['main'],
				'page_title' => esc_html__('Manage Profile', self::$text_domain),
				'menu_title' => esc_html__('Profile', self::$text_domain),
				'capability' => 'read',
				'menu_slug' => $this->slugs['manage-profile'],
				'callback' => array($this->callbacks, 'manage_profile')
			),
		);
		$logged_in_user_role = Helper::get_current_user_role();

		if ($logged_in_user_role == 'student') {
			for ($i = 0; $i < sizeof($this->excluded_subpages_id_for_student); $i++) {
				unset($this->sub_pages[$this->excluded_subpages_id_for_student[$i]]);
			}
		} elseif ($logged_in_user_role == 'instructor') {
			for ($i = 0; $i < sizeof($this->excluded_subpages_id_for_instructor); $i++) {
				unset($this->sub_pages[$this->excluded_subpages_id_for_instructor[$i]]);
			}
		} elseif ($logged_in_user_role == 'administrator') {
			for ($i = 0; $i < sizeof($this->excluded_subpages_id_for_administrator); $i++) {
				unset($this->sub_pages[$this->excluded_subpages_id_for_administrator[$i]]);
			}
		} else {
			$this->sub_pages = array();
		}
	}

	// Method for adding the pages into this plugin
	public function register()
	{
		$this->settings = new SettingsApi();
		$this->callbacks = new AdminCallbacks();
		$this->set_pages();
		$this->set_sub_pages();
		$this->settings->add_pages($this->pages)->with_sub_page(esc_html__('Dashboard', self::$text_domain))->add_sub_pages($this->sub_pages)->register();
	}
}