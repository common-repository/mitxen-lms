<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Payment extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_payment', array($this, 'post'));
    }


    // GET PURCHASE HISTORY USER WISE
    public static function purchase_history($user_id = "", $page_number = "", $limit = "")
    {
        $user_id = !empty($user_id) ? $user_id : Helper::get_current_user_id();
        $table = self::$tables['payment'];
        global $wpdb;

        if (!empty($page_number) && !empty($limit)) {
            $offset = (int)($page_number - 1)  * $limit;
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $user_id, $limit, $offset ) );
        } else {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d", $user_id ) );
        }

        return $result;
    }

    // GET PURCHASE HISTORY BY ID
    public static function purchase_history_by_id($id)
    {
        $table = self::$tables['payment'];
        global $wpdb;

        $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE `id` = %d", $id ) );

        return $result;
    }
}