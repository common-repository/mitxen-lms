<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base;

defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\modules\Helper;
use \Mxlms\base\modules\Video;
use Mxlms\base\modules\Course;


class AjaxPosts extends BaseController
{
	protected $page_to_load;
	protected $current_user_role;

	// Arbitrary parameters that might be sent during any ajax call
	public static $param1;
	public static $param2;
	public static $param3;
	public static $param4;
	public static $param5;
	public static $param6;
	public static $param7;
	public static $param8;
	public static $param9;
	public static $url;

	// DECLARING THIS CONSTRUCTOR FOR INITIALIZING THE CURRENT USER ROLE
	function __construct()
	{
		parent::__construct();
		$this->current_user_role = Helper::get_current_user_role();
	}

	// Method for registering ajax submit hook to the plugin
	public function register()
	{
		add_action('wp_ajax_' . self::$plugin_id, array($this, 'post'));

		// USE THIS HOOK FOR LOGGED OUT USERS AJAX CALL
		add_action('wp_ajax_nopriv_' . self::$plugin_id, array($this, 'post'));
	}

	// Method for sanitizing all the received parameters and assign it to the public variables declared in this class
	public function post()
	{
		$task = sanitize_text_field($_POST['task']);

		if (isset($_POST['page']))
			$this->page_to_load = sanitize_text_field($_POST['page']);

		if (isset($_POST['param1']))
			self::$param1 = sanitize_text_field($_POST['param1']);

		if (isset($_POST['param2']))
			self::$param2 = sanitize_text_field($_POST['param2']);

		if (isset($_POST['param3']))
			self::$param3 = sanitize_text_field($_POST['param3']);

		if (isset($_POST['param4']))
			self::$param4 = sanitize_text_field($_POST['param4']);

		if (isset($_POST['param5']))
			self::$param5 = sanitize_text_field($_POST['param5']);

		if (isset($_POST['param6']))
			self::$param6 = sanitize_text_field($_POST['param6']);

		if (isset($_POST['param7']))
			self::$param7 = sanitize_text_field($_POST['param7']);

		if (isset($_POST['param8']))
			self::$param8 = sanitize_text_field($_POST['param8']);

		if (isset($_POST['param9']))
			self::$param9 = sanitize_text_field($_POST['param9']);

		if (isset($_POST['url']))
			self::$url = sanitize_text_field($_POST['url']);

		$this->handle_ajax_posts($task);
	}

	// Method for determining the ajax request type and send feedback accordingly
	private function handle_ajax_posts($task)
	{
		if ($task == 'load_modal_page')
			$this->load_modal_page();

		if ($task == 'load_response')
			$this->load_response();

		if ($task == 'load_frontend_response')
			$this->load_frontend_response();

		if ($task == 'load_confirm_modal_page')
			$this->load_confirm_modal_page();

		if ($task == 'load_confirm_modal_page_for_updating')
			$this->load_confirm_modal_page_for_updating();

		if ($task == 'load_theme_confirm_modal_for_updating')
			$this->load_theme_confirm_modal_for_updating();

		if ($task == 'video_url_validity')
			$this->video_url_validity();

		if ($task == 'save_course_progress')
			$this->save_course_progress();
	}

	// Method for presenting modal with contents sent from ajax post request
	private function load_modal_page()
	{
		require($this->plugin_path . "/templates/backend/$this->current_user_role/$this->page_to_load.php");
		die();
	}

	// Method for presenting confirm modal with contents sent from ajax post request
	private function load_confirm_modal_page()
	{
		require($this->plugin_path . "/templates/backend/modal/modal-for-confirmation.php");
		die();
	}

	// Method for presenting confirm modal with contents sent from ajax post request
	private function load_confirm_modal_page_for_updating()
	{
		require($this->plugin_path . "/templates/backend/modal/modal-for-confirmation-for-updating.php");
		die();
	}

	// Method for loading any response to a page after ajax post request
	private function load_response()
	{
		require($this->plugin_path . "/templates/backend/$this->current_user_role/$this->page_to_load.php");
		die();
	}

	// Method for loading any frontend response to a page after ajax post request
	private function load_frontend_response()
	{
		$shortcoded_file_path = $this->plugin_path . "/templates/shortcode/$this->page_to_load.php";
		$theme_file_path = get_theme_file_path("/template-parts/$this->page_to_load.php");
		if (file_exists($theme_file_path)) {
			require($theme_file_path);
		} else {
			require($shortcoded_file_path);
		}
		die();
	}

	// Method for loading confirmation modal for updating for theme
	private function load_theme_confirm_modal_for_updating()
	{
		$shortcoded_file_path = $this->plugin_path . "/templates/modal/modal-for-confirmation-for-updating.php";
		$theme_file_path = get_theme_file_path("/template-parts/modal/modal-for-confirmation-for-updating.php");

		if (file_exists($theme_file_path)) {
			require($theme_file_path);
		} else {
			require($shortcoded_file_path);
		}

		die();
	}

	// Method for checking whether the video url is valid and if it is then return the video duration
	private function video_url_validity()
	{
		$video_details = Video::get_video_details(self::$url);
		$response = json_encode(["status" => $video_details['status'], "duration" => $video_details['duration']]);
		echo esc_html($response);
		die();
	}

	// Method for saving course progress
	private function save_course_progress()
	{
		$response = Course::save_course_progress(self::$param1, self::$param2);
		echo $response;
		die();
	}
}
