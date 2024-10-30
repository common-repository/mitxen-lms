<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\api\callbacks;

use \Mxlms\base\BaseController;
use \Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class AdminCallbacks extends BaseController
{
	protected $current_user_role;

	// DECLARING THIS CONSTRUCTOR FOR INITIALIZING THE CURRENT USER ROLE
	function __construct()
	{
		parent::__construct();
		$this->current_user_role = Helper::get_current_user_role();
	}

	// Method called when user clicks on dashboard menu of the plugin
	public function dashboard()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/dashboard/index.php" );
	}

	// Method called when user clicks on categories menu of the plugin
	public function categories()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/category/index.php" );
	}

	// Method called when user clicks on courses menu of the plugin
	public function courses()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/course/index.php" );
	}

	// Method called when user clicks on instructor menu of the plugin
	public function instructors()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/instructor/index.php" );
	}

	// Method called when user clicks on students menu of the plugin
	public function students()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/student/index.php" );
	}

	// Method called when user clicks on enrolments menu of the plugin
	public function enrolments()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/enrolment/index.php" );
	}

	// Method called when user clicks on reports menu of the plugin
	public function reports()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/report/index.php" );
	}

	// Method called when user clicks on messages menu of the plugin
	public function messages()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/message/index.php" );
	}

	// Method called when user clicks on Addon manager menu of the plugin
	public function addon()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/addon/index.php" );
	}

	// Method called when user clicks on settings menu of the plugin
	public function settings()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/settings/index.php" );
	}

	// Method called when user clicks on settings menu of the plugin
	public function enrolled()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/settings/index.php" );
	}

	// Method called when user clicks on settings menu of the plugin
	public function payout()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/payout/index.php" );
	}

	// Method called when student clicks on become an instructor menu of the plugin
	public function become_an_instructor()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/application/index.php" );
	}

	// Method called when user clicks on manage profile link
	public function manage_profile()
	{
		return require_once( "$this->plugin_path/templates/backend/$this->current_user_role/profile/index.php" );
	}
}