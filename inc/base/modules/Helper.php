<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');
class Helper extends BaseController
{

    public static function get_plugin_path($trail = "")
    {
        $current_directory = plugin_dir_path(__FILE__);
        $exploded_current_directory = explode("/inc/base/modules", $current_directory);
        $plugin_dir = $exploded_current_directory[0];

        if (!empty($trail)) {
            $plugin_dir = $plugin_dir . '/' . $trail;
        }
        return esc_url($plugin_dir);
    }

    public static function get_plugin_url($trail = "")
    {
        $current_url = plugin_dir_url(__FILE__);
        $exploded_current_url = explode("/inc/base/modules", $current_url);
        $plugin_url = $exploded_current_url[0];
        if (!empty($trail)) {
            $plugin_url = $plugin_url . '/' . $trail;
        }
        return esc_url($plugin_url);
    }

    public static function get_general_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['general_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        // CHECK IF THE LOGO FIELD IS EMPTY, THEN RETURN DEFAULT LOGO
        if ($attribute == "logo_lg_path" && $result[0]->value == "") {
            return esc_url(Helper::get_plugin_url('assets/common/img/mitxen-logo-lg.png'));
        } elseif ($attribute == "logo_sm_path" && $result[0]->value == "") {
            return esc_url(Helper::get_plugin_url('assets/common/img/mitxen-logo-sm.png'));
        }
        return $result[0]->value;
    }

    public static function get_smtp_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['smtp_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        return $result[0]->value;
    }

    public static function get_instructor_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['instructor_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        return $result[0]->value;
    }

    public static function get_payment_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['payment_settings'];
        $json_result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `key` = %s", $attribute));
        return $json_result[0]->value;
    }

    public static function get_page_settings($attribute, $return_url = false)
    {
        global $wpdb;
        $table = self::$tables['page_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        $page_id = $result[0]->value;
        if ($return_url) {
            $page_url = rtrim(get_page_link($page_id));
            if ($page_url && !empty($page_url)) {
                return $page_url;
            } else {
                if ($attribute == "private_page") {
                    return wp_login_url();
                } else {
                    return site_url();
                }
            }
        }
        return $page_id;
    }

    public static function get_certificate_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['certificate_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        return $result[0]->value;
    }

    public static function get_aws_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['aws_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        return $result[0]->value;
    }

    public static function get_live_class_settings($attribute)
    {
        global $wpdb;
        $table = self::$tables['live_class_settings'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT `value` FROM $table WHERE `key` = %s", $attribute));
        return $result[0]->value;
    }
    
    public static function get_user_info($attribute, $user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'users';
        $result = $wpdb->get_results($wpdb->prepare("SELECT $attribute FROM $table WHERE  `ID` = %d", $user_id));
        foreach ($result as $row) {
            return $row->$attribute;
        }
    }

    public static function get_info_from_random_table($table, $id, $column)
    {
        global $wpdb;
        $table = self::$tables[$table];
        $result = $wpdb->get_results($wpdb->prepare("SELECT $column FROM $table WHERE  `id` = %d", $id));
        foreach ($result as $row) {
            return $row->$column;
        }
    }

    public static function get_current_user_role()
    {
        $wp_object = wp_get_current_user();
        $user_role_array_with_plugin_id = $wp_object->roles;

        if (count($user_role_array_with_plugin_id)) {
            if ($user_role_array_with_plugin_id[0] == 'administrator') {
                return $user_role_array_with_plugin_id[0];
            } else {

                $user_role_array = explode('-', $user_role_array_with_plugin_id[0]);
                return $user_role_array[1];
            }
        }
    }

    public static function get_current_wp_user_id()
    {
        $wp_object = wp_get_current_user();

        return $wp_object->data->ID;
    }

    public static function get_current_user_id()
    {
        $wp_object = wp_get_current_user();

        return isset($wp_object->data->ID) ? self::get_user_id($wp_object->data->ID) : false;
    }

    public static function get_user_id($wp_user_id)
    {
        global $wpdb;
        $table   = self::$tables['users'];
        $user_id = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM $table where `wp_user_id` = %d", $wp_user_id));
        return $user_id;
    }

    public static function get_url($urlTail = null)
    {
        $permalink = get_permalink();

        if (empty($urlTail)) {
            return $permalink;
        } else {
            $final_url = strpos($permalink, '?') ? $permalink . "&" . $urlTail : $permalink . "?" . $urlTail;
            if (filter_var($final_url, FILTER_VALIDATE_URL) === FALSE) {
                return $final_url;
            } else {
                return $final_url;
            }
        }
    }

    // CHECK PERMALINK MANUALLY
    public static function get_url_manually($permalink, $urlTail = null)
    {
        if (empty($urlTail)) {
            return $permalink;
        } else {
            $final_url = strpos($permalink, '?') ? $permalink . "&" . $urlTail : $permalink . "?" . $urlTail;
            if (filter_var($final_url, FILTER_VALIDATE_URL) === FALSE) {
                return $final_url;
            } else {
                return $final_url;
            }
        }
    }

    public static function ellipsis($string, $length = 30)
    {
        $short_string = strlen($string) > $length ? substr($string, 0, $length) . "..." : $string;
        return $short_string;
    }

    public static function slugify($string)
    {
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string);
        $string = trim($string, '-');
        $string = strtolower($string);
        if (empty($string))
            return 'n-a';
        return $string;
    }

    public static function is_student_logged_in()
    {
        if (is_user_logged_in()) {
            return (self::get_current_user_role() == "administrator") ? false : true;
        } else {
            return false;
        }
    }


    public static function url_exists($url)
    {
        $headers = get_headers($url);
        return stripos($headers[0], "200 OK") ? true : false;
    }

    // CHECK IF THE IMAGE EXISTIS
    public static function get_image($url = "", $image_type = "")
    {
        if (isset($url) && !empty($url)) {
            $url_details = self::url_exists($url);
            if ($url_details) {
                return $url;
            }
        }

        if ($image_type == "user") {
            return self::get_plugin_url('uploads/placeholders/user.png');
        } elseif ($image_type == "course_banner") {
            return self::get_plugin_url('uploads/placeholders/course-banner.png');
        } elseif ($image_type == "course_thumbnail") {
            return self::get_plugin_url('uploads/placeholders/course-thumbnail.png');
        }
        return self::get_plugin_url('uploads/placeholders/placeholder.jpg');
    }

    // ADDON HELPER FUNCTIONS
    // CHECK IF AN ADDON EXISTS OR NOR
    public static function does_addon_exist($unique_identifier)
    {
        global $wpdb;
        $table = self::$tables['addons'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `unique_identifier` = %s AND `status` = '1'", $unique_identifier));
        return count($result) ? true : false;
    }


    // GET CURRENCY CODE
    public static function currency($price, $attr = "symbol")
    {
        $system_payment_info  = json_decode(self::get_payment_settings('system'));
        $system_currency_code = $system_payment_info->system_currency;
        $system_currency_position = $system_payment_info->currency_position;
        global $wpdb;
        $table = self::$tables['currencies'];
        $currency_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `code` = %s", $system_currency_code));

        $currency_data = $currency_data[0];

        if ($system_currency_position == 'right') {
            return $price . $currency_data->symbol;
        } elseif ($system_currency_position == 'right-space') {
            return $price . ' ' . $currency_data->symbol;
        } elseif ($system_currency_position == 'left') {
            return $currency_data->symbol . $price;
        } elseif ($system_currency_position == 'left-space') {
            return $currency_data->symbol . ' ' . $price;
        }
    }

    // GET COURSE PRICE
    public static function get_course_price($course_id)
    {
        $course_details = Course::get_course_details_by_id($course_id);
        if ($course_details->discount_flag == 1) {
            return $course_details->discounted_price;
        } else {
            return $course_details->price;
        }
    }

    // CHECK IF THE COURSE IS PURCHASED BY THE USER
    public static function is_purchased($course_id)
    {
        $user_id = Helper::get_current_user_id();
        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `user_id` = %d AND `course_id` = %d", $user_id, $course_id));
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // RETURN THE FILE EXTENSION
    public static function get_extension($file)
    {
        $filetype = wp_check_filetype($file);
        $filetype = $filetype['ext'];
        return $filetype;
    }

    // RETURN THE HUMAN READABLE
    public static function readable_time_for_humans($duration)
    {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour   = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour . ' ' . 'hr' . ' ' . $minute . ' ' . 'min';
            } elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute + 1) . ' ' . 'min';
                } else {
                    $duration = $minute . ' ' . 'min';
                }
            } elseif ($second > 0) {
                $duration = $second . ' ' . 'sec';
            } else {
                $duration = '00:00';
            }
        } else {
            $duration = '00:00';
        }
        return $duration;
    }


    // LESSON PROGRESS
    public static function lesson_progress($lesson_id = "", $user_id = "")
    {
        if ($user_id == "") {
            $user_id = self::get_current_user_id();
        }
        $user_details = User::get_user_by_id($user_id);

        $watch_history_array = !empty($user_details->watch_history) ? json_decode($user_details->watch_history, true) : array();
        for ($i = 0; $i < count($watch_history_array); $i++) {
            $watch_history_for_each_lesson = $watch_history_array[$i];
            if ($watch_history_for_each_lesson['lesson_id'] == $lesson_id) {
                return $watch_history_for_each_lesson['progress'];
            }
        }
        return 0;
    }

    // COURSE PROGRESS
    public static function course_progress($course_id = "", $user_id = "")
    {
        if ($user_id == "") {
            $user_id = self::get_current_user_id();
        }
        $user_details = User::get_user_by_id($user_id);

        // this array will contain all the completed lessons from different different courses by a user
        $completed_lessons_ids = array();

        // this variable will contain number of completed lessons for a certain course. Like for this one the course_id
        $lesson_completed = 0;

        // User's watch history
        $watch_history_array = !empty($user_details->watch_history) ? json_decode($user_details->watch_history, true) : array();
        // desired course's lessons
        $lessons_for_that_course = Lesson::get_lesson_by_course_id($course_id);
        // total number of lessons for that course
        $total_number_of_lessons = count($lessons_for_that_course) ? count($lessons_for_that_course) : 0;
        // arranging completed lesson ids
        for ($i = 0; $i < count($watch_history_array); $i++) {
            $watch_history_for_each_lesson = $watch_history_array[$i];
            if ($watch_history_for_each_lesson['progress'] == 1) {
                array_push($completed_lessons_ids, $watch_history_for_each_lesson['lesson_id']);
            }
        }

        foreach ($lessons_for_that_course as $row) {
            if (in_array($row->id, $completed_lessons_ids)) {
                $lesson_completed++;
            }
        }

        if ($lesson_completed > 0 && $total_number_of_lessons > 0) {
            // calculate the percantage of progress
            $course_progress = ($lesson_completed / $total_number_of_lessons) * 100;
            $course_progress = round($course_progress);
            return $course_progress;
        } else {
            return 0;
        }
    }

    // GENERATE RANDOM NUMBER
    public static function random($length_of_string)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
}
