<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\User;

defined('ABSPATH') or die('You can not access the file directly');

class Enrolment extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_enrolment', array($this, 'post'));
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
        switch ($task) {
            case 'create_enrolment':
                $this->create_enrolment();
                break;
            case 'delete':
                $this->delete_enrolment();
                break;
        }
    }

    public static function get_enrolment_history($start_date, $end_date)
    {
        $start_date = (int)strtotime($start_date);
        $end_date   = (int)strtotime($end_date);

        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC", $start_date, $end_date ) );
        return $result;
    }


    // FOR DASHBOARD DATA
    public static function get_all_enrolment()
    {
        global $wpdb;
        $table = self::$tables['enrolment'];
        if (Helper::get_current_user_role() == "administrator") {
            $result = $wpdb->get_results( "SELECT * FROM $table" );
        } else {
            $instructor_courses = Course::get_instructor_courses_by_instructor_id(Helper::get_current_user_id());
            $instructor_course_ids = array();

            foreach ($instructor_courses as $instructor_course) {
                if (!in_array($instructor_course->id, $instructor_course_ids)) {
                    array_push($instructor_course_ids, esc_sql($instructor_course->id));
                }
            }

            if (count($instructor_course_ids)) {
                $instructor_course_ids_impoded = implode(", ", esc_sql($instructor_course_ids));
            } else {
                $instructor_course_ids_impoded = null;
            }

            if ($instructor_course_ids_impoded) {
                global $wpdb;
                $table = self::$tables['payment'];
                $result = $wpdb->get_results( "SELECT* FROM $table WHERE `course_id` IN ($instructor_course_ids_impoded)" );
            }else{
                $result = array();
            }
        }

        return $result;
    }



    private function create_enrolment()
    {
        if (self::verify_nonce('create_enrolment_nonce') == true) {
            $table = self::$tables['enrolment'];
            $course_id  = sanitize_text_field($_POST['course_id']);
            $user_id = sanitize_text_field($_POST['user_id']);
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            global $wpdb;

            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d AND `course_id` = %d", $user_id, $course_id ) );
            if (count($result) > 0) {
                echo json_encode(['status' => false, 'message' => esc_html__("Enrolment Already Exists", BaseController::$text_domain)]);
            } else {
                $data['user_id'] = $user_id;
                $data['course_id'] = $course_id;
                $wpdb->insert($table, $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Enrolment Added Successfully", BaseController::$text_domain)]);
            }
        }
    }

    public static function enrol_after_payment($user_id, $course_id)
    {
        if (!empty($user_id) && !empty($course_id)) {
            $table = self::$tables['enrolment'];
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d AND `course_id` = %d", $user_id, $course_id ) );
            if (count($result) > 0) {
                return false;
            } else {
                $data['user_id'] = $user_id;
                $data['course_id'] = $course_id;
                $wpdb->insert($table, $data);
                return true;
            }
        }
    }

    public static function enrol_to_free_course($course_id)
    {
        if (!empty($course_id)) {
            $course_details = Course::get_course_details_by_id($course_id);
            if ($course_details->status == "active" && $course_details->is_free_course == 1) {
                $user_id = Helper::get_current_user_id();
                $table = self::$tables['enrolment'];
                $data['date_added'] = strtotime(date('D, d-M-Y'));
                global $wpdb;
                $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d AND `course_id` = %d", $user_id, $course_id ) );
                if (count($result) > 0) {
                    return false;
                } else {
                    $data['user_id'] = $user_id;
                    $data['course_id'] = $course_id;
                    $wpdb->insert($table, $data);
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function delete_enrolment()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['enrolment'];
            $id = sanitize_text_field($_POST['id']);
            $wpdb->delete($table, ['id' => $id]);
            echo json_encode(['status' => true, 'message' => esc_html__("Enrolment Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    public static function course_wise_enrolment($course_id)
    {
        global $wpdb;
        $table = self::$tables['enrolment'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` = %d", $course_id ) );
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    public static function paginate($start_date, $end_date, $page_number, $limit, $isAdminReport)
    {
        $start_date = (int)strtotime($start_date);
        $end_date   = (int)strtotime($end_date);

        global $wpdb;
        $table = self::$tables['enrolment'];
        $offset = (int)($page_number - 1)  * $limit;

        if ($isAdminReport) {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `date_added` >= %d AND `date_added` <= %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $start_date, $end_date, $limit, $offset ) );
        } else {
            $admin_details = User::get_admin_details();
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` != %d AND `date_added` >= %d AND `date_added`<= %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $admin_details->id, $start_date, $end_date, $limit, $offset ) );
        }
        return $result;
    }


    // GET LOGGED IN STUDENT COURSES
    public static function get_my_courses($page_number = "", $limit = "")
    {
        $user_id = Helper::get_current_user_id();
        global $wpdb;
        $table = self::$tables['enrolment'];
        if (!empty($page_number) && !empty($limit)) {
            $offset = (int)($page_number - 1)  * $limit;
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d ORDER BY $table.`date_added` ASC LIMIT %d OFFSET %d", $user_id, $limit, $offset ) );
        } else {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `user_id` = %d ORDER BY $table.`date_added` ASC", $user_id ) );
        }
        return $result;
    }
}
