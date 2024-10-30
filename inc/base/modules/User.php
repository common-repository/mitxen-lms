<?php

/**
 * @package mitxenLMS
 */

namespace Mxlms\base\modules;


use Mxlms\base\modules\Settings;
use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class User extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_user', array($this, 'post'));
    }

    // Main method for handling all the post data submitted during a form submission
    public function post()
    {
        $task = sanitize_text_field($_POST['task']);
        $this->handle_posts($task);
    }

    // Method for handling form submission according to task of the form
    public function handle_posts($task)
    {
      // DO STUFF
    }

    public static function get_user_by_id($user_id = "")
    {
        global $wpdb;
        $table = self::$tables['users'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `id` = %d ", $user_id ) );
        return $result[0];
    }

    public static function get_approved_users($user_id = "")
    {
        global $wpdb;
        $table = self::$tables['users'];
        if (isset($user_id) && !empty($user_id)) {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `id` = %d AND `status` = 1", $user_id ) );
            return $result[0];
        } else {
            $result = $wpdb->get_results( "SELECT * FROM $table WHERE `role` != 'admin' AND `status` = 1" );
            return $result;
        }
    }

    public static function get_admin_details()
    {
        global $wpdb;
        $table = self::$tables['users'];
        $result = $wpdb->get_results( "SELECT * FROM $table where `role` = 'admin' " );
        return $result[0];
    }

    public static function get_logged_in_user_details()
    {
        $logged_in_user_id = Helper::get_current_user_id();
        global $wpdb;
        $table = self::$tables['users'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `id` = %d", $logged_in_user_id ) );
        return $result[0];
    }
}