<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Category;
use Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Course extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_course', array($this, 'post'));
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
            case 'add_course':
                $this->add_course();
                break;
            case 'edit_course':
                $this->edit_course();
                break;
            case 'approve':
                $this->mark_as_approve_course();
                break;
            case 'pending':
                $this->mark_as_pending_course();
                break;
            case 'delete':
                $this->delete_course();
                break;
        }
    }

    private function add_course()
    {
        if (self::verify_nonce('add_course_nonce') == true) {
            $data['title']           = sanitize_text_field($_POST['title']);
            $data['slug']           = Helper::slugify(sanitize_text_field($_POST['title']));
            $data['sub_category_id'] = sanitize_text_field($_POST['sub_category_id']);

            $category_details = Category::get_category_details_by_id($data['sub_category_id']);
            $data['category_id']      = $category_details[0]->parent_category_id;
            $data['is_free_course']   = (isset($_POST['is_free_course']) && !empty($_POST['is_free_course'])) ? sanitize_text_field($_POST['is_free_course']) : 0;
            $data['price']            = is_numeric(sanitize_text_field($_POST['price'])) ? sanitize_text_field($_POST['price']) : 0;
            $data['discount_flag']    = (isset($_POST['discount_flag']) && !empty($_POST['discount_flag'])) ? 1 : 0;
            $data['discounted_price'] = is_numeric(sanitize_text_field($_POST['discounted_price'])) ? sanitize_text_field($_POST['discounted_price']) : 0;
            $data['user_id']          = Helper::get_current_user_id();
            $data['date_added']       = strtotime(date('D, d-M-Y'));

            if (Helper::get_current_user_role() == "administrator") {
                $data['status'] = "active";
            } else {
                $data['status'] = "pending";
            }
            global $wpdb;
            $wpdb->insert(self::$tables['courses'], $data);
            $lastid = $wpdb->insert_id;

            echo json_encode(['status' => true, 'message' => esc_html__("Course Added Successfully", BaseController::$text_domain), 'id' => $lastid]);
        }
    }

    private function edit_course()
    {
        if (self::verify_nonce('edit_course_nonce') == true) {
            $dynamic_function_name = 'update_' . sanitize_text_field($_POST['section']);
            $this->$dynamic_function_name();
        }
    }

    private function update_basic()
    {
        $course_id                  = sanitize_text_field($_POST['course_id']);
        $data['title']              = sanitize_text_field($_POST['title']);
        $data['slug']               = Helper::slugify(sanitize_text_field($_POST['title']));
        $data['sub_category_id']    = sanitize_text_field($_POST['sub_category_id']);
        $category_details           = Category::get_category_details_by_id($data['sub_category_id']);
        $data['category_id']        = $category_details[0]->parent_category_id;
        $data['short_description']  = sanitize_text_field($_POST['short_description']);
        $data['description']        = htmlspecialchars($_POST['description']);
        $data['level']              = sanitize_text_field($_POST['level']);
        $data['language']           = sanitize_text_field($_POST['language']);



        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }

    private function update_pricing()
    {
        $course_id                = sanitize_text_field($_POST['course_id']);
        $data['is_free_course']   = (isset($_POST['is_free_course']) && !empty($_POST['is_free_course'])) ? sanitize_text_field($_POST['is_free_course']) : 0;
        $data['discount_flag']    = (isset($_POST['discount_flag']) && !empty($_POST['discount_flag'])) ? 1 : 0;
        $data['discounted_price'] = is_numeric(sanitize_text_field($_POST['discounted_price'])) ? sanitize_text_field($_POST['discounted_price']) : 0;
        $data['last_modified']    = strtotime(date('D, d-M-Y'));

        if ($data['is_free_course']) {
            $data['price'] = 0;
        } else {
            $data['price'] = is_numeric(sanitize_text_field($_POST['price'])) ? sanitize_text_field($_POST['price']) : 0;
        }

        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }

    private function update_requirements()
    {
        $course_id              = sanitize_text_field($_POST['course_id']);
        $requirements           = $this->trim_and_return_json($_POST['requirements']);
        $data['requirements']   = $requirements;
        $data['last_modified']  = strtotime(date('D, d-M-Y'));
        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }

    private function update_outcomes()
    {
        $course_id             = sanitize_text_field($_POST['course_id']);
        $outcomes              = $this->trim_and_return_json($_POST['outcomes']);
        $data['outcomes']      = $outcomes;
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }

    private function update_media()
    {
        $course_id = sanitize_text_field($_POST['course_id']);
        if (!empty($_POST['course_thumbnail_path'])) {
            $data['thumbnail']  = sanitize_text_field($_POST['course_thumbnail_path']);
        }
        if (!empty($_POST['course_banner_path'])) {
            $data['banner']  = sanitize_text_field($_POST['course_banner_path']);
        }
        $data['preview_video_provider'] = sanitize_text_field($_POST['preview_video_provider']);;
        $data['preview_video_url'] = sanitize_text_field($_POST['preview_video_url']);;
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }

    private function update_tag()
    {
        $course_id = sanitize_text_field($_POST['course_id']);
        $data['meta_keywords'] = sanitize_text_field($_POST['meta_keywords']);;
        $data['meta_description'] = sanitize_text_field($_POST['meta_description']);;
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        if (Helper::get_current_user_role() == "administrator") {
            $data['status'] = "active";
        } else {
            $data['status'] = "pending";
        }

        if (self::course_authentication($course_id)) {
            global $wpdb;
            $wpdb->update(self::$tables['courses'], $data, array('id' => $course_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Course Updated Successfully", BaseController::$text_domain)]);
        } else {
            echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
        }
    }
    private function delete_course()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['courses'];
            $id = sanitize_text_field($_POST['id']);

            if (self::course_authentication($id)) {
                $wpdb->delete($table, ['id' => $id]);
                echo json_encode(['status' => true, 'message' => esc_html__("Course Deleted Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    private function mark_as_approve_course()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['courses'];
            $id = sanitize_text_field($_POST['id']);

            if (self::course_authentication($id)) {
                $data['status'] = "active";
                $wpdb->update($table, $data, array('id' => $id));
                echo json_encode(['status' => true, 'message' => esc_html__("Course Has Been Approved Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }
    private function mark_as_pending_course()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['courses'];
            $id = sanitize_text_field($_POST['id']);

            if (self::course_authentication($id)) {
                $data['status'] = "pending";
                $wpdb->update($table, $data, array('id' => $id));
                echo json_encode(['status' => true, 'message' => esc_html__("Updated to pending status successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function get_all_courses($page = "", $limit = "")
    {
        global $wpdb;
        $table = self::$tables['courses'];

        if (!empty($page) && !empty($limit)) {
            $offset = ($page - 1)  * $limit;
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table LIMIT %d OFFSET %d", $limit, $offset));
        } else {

            $result = $wpdb->get_results("SELECT * FROM $table");
        }
        return $result;
    }

    public static function get_all_instructor_courses()
    {
        $admin_details = User::get_admin_details();
        global $wpdb;
        $table = self::$tables['courses'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `user_id` != %d", $admin_details->id));
        return $result;
    }

    public static function get_instructor_courses_by_instructor_id($instructor_id = "", $page = "", $limit = "")
    {
        if (Helper::get_current_user_role() == "instructor") {
            $instructor_id = (isset($instructor_id) && !empty($instructor_id)) ? $instructor_id : Helper::get_current_user_id();
        }

        if (!empty($instructor_id)) {
            global $wpdb;
            $table = self::$tables['courses'];
            if (!empty($page) && !empty($limit)) {
                $offset = ($page - 1)  * $limit;
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `user_id` = %d LIMIT %d OFFSET %d", $instructor_id, $limit, $offset));
            } else {
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `user_id` = %d", $instructor_id));
            }
        } else {
            $result = array();
        }

        return $result;
    }



    public static function get_active_courses()
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'active'");
        return $result;
    }

    public static function active_course_paginate($page = "", $limit = "")
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $offset = ($page - 1)  * $limit;
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `status` = 'active' LIMIT %d OFFSET %d", $limit, $offset));
        return $result;
    }

    public static function get_authenticated_course_details_by_id($course_id)
    {
        global $wpdb;
        $table = self::$tables['courses'];
        if (self::course_authentication($course_id)) {
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $course_id));
            $result = $result[0];
        } else {
            $result = array();
        }
        return $result;
    }

    function trim_and_return_json($untrimmed_array)
    {
        $trimmed_array = array();
        if (sizeof($untrimmed_array) > 0) {
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    array_push($trimmed_array, sanitize_text_field($row));
                }
            }
        }
        return json_encode($trimmed_array);
    }


    // CHECKS IF THE COURSE DOES BELONG TO CURRENT USER
    public static function course_authentication($course_id)
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $course_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $course_id));
        if (sizeof($course_details)) {
            $course_details = $course_details[0];
            if (Helper::get_current_user_role() == "administrator") {
                return true;
            } elseif (Helper::get_current_user_role() == "instructor" && $course_details->user_id == Helper::get_current_user_id()) {
                return true;
            }
        }

        return false;
    }


    // GET COURSE LIST FOR FRONTEND VIEWS
    public static function get_course_details_by_id($course_id)
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d AND `status` = 'active'", $course_id));
        $result = $result[0];
        return $result;
    }

    public static function get_course_details_by_slug($course_slug)
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `slug` = %s AND `status` = 'active'", $course_slug));
        $result = $result[0];
        return $result;
    }


    // FILTER COURSES
    public static function filter($selected_categories, $selected_subcategories, $selected_prices, $sort_by, $search_string, $page = "", $limit = "")
    {
        global $wpdb;
        $course_table = self::$tables['courses'];
        $category_table = self::$tables['categories'];

        // FIRST ESCAPE THE SQL THEN IMPLODE IT AS CSV
        $selected_categories = explode("--", $selected_categories);
        $selected_categories = esc_sql($selected_categories);
        $selected_categories = "'" . implode("','", $selected_categories) . "'";

        // FIRST ESCAPE THE SQL THEN IMPLODE IT AS CSV
        $selected_subcategories = explode("--", $selected_subcategories);
        $selected_subcategories = esc_sql($selected_subcategories);
        $selected_subcategories = "'" . implode("','", $selected_subcategories) . "'";

        // FIRST ESCAPE THE SQL THEN IMPLODE IT AS CSV
        $selected_prices = explode("--", $selected_prices);
        $selected_prices = esc_sql($selected_prices);

        // ESCAPED VALUE
        $selected_category_ids = $wpdb->get_results("SELECT `id` FROM $category_table WHERE `slug` IN ( $selected_categories )");
        $selected_category_ids_only = array();
        foreach ($selected_category_ids as $selected_category_id) {
            if (!in_array($selected_category_id->id, $selected_category_ids_only)) {
                array_push($selected_category_ids_only, esc_sql($selected_category_id->id));
            }
        }

        $selected_category_ids_only = implode(", ", $selected_category_ids_only);
        $category_query_part = !empty($selected_category_ids_only) ? "AND `category_id` IN ($selected_category_ids_only)" : "";



        $selected_subcategory_ids = $wpdb->get_results("SELECT `id` FROM $category_table WHERE `slug` IN ($selected_subcategories)");
        $selected_subcategory_ids_only = array();
        foreach ($selected_subcategory_ids as $selected_subcategory_id) {
            if (!in_array($selected_subcategory_id->id, $selected_subcategory_ids_only)) {
                array_push($selected_subcategory_ids_only, esc_sql($selected_subcategory_id->id));
            }
        }
        $selected_subcategory_ids_only = implode(", ", $selected_subcategory_ids_only);
        $subcategory_query_part = !empty($selected_subcategory_ids_only) ? "AND `sub_category_id` IN ($selected_subcategory_ids_only)" : "";

        if (count($selected_prices) == 1 && $selected_prices[0] != "all") {
            $price_query_part = $selected_prices[0] == "free" ? "AND `is_free_course` = 1" : "AND `is_free_course` = 0";
        } else {
            $price_query_part = "";
        }

        // SEARCH STRING QUERY PART
        if ($search_string != "none") {
            $search_string_query_part = "AND `title` LIKE '%$search_string%'";
        } else {
            $search_string_query_part = "";
        }

        // SORT QUERY PART
        if ($sort_by != "none") {
            if ($sort_by == "latest") {
                $sort_query = "ORDER BY $course_table.`date_added` DESC";
            } elseif ($sort_by == "price-low") {
                $sort_query = "ORDER BY $course_table.`price` ASC";
            } elseif ($sort_by == "price-high") {
                $sort_query = "ORDER BY $course_table.`price` DESC";
            } elseif ($sort_by == "rating") {
                $sort_query = "ORDER BY $course_table.`avg_rating` DESC";
            }
        } else {
            $sort_query = "";
        }


        if (!empty($page) && !empty($limit)) {
            $offset = ($page - 1)  * $limit;
            $filtered_courses = $wpdb->get_results("SELECT * FROM $course_table WHERE `status` = 'active' $category_query_part $subcategory_query_part $price_query_part $search_string_query_part $sort_query LIMIT $limit OFFSET $offset");
        } else {
            $filtered_courses = $wpdb->get_results("SELECT * FROM $course_table WHERE `status` = 'active' $category_query_part $subcategory_query_part $price_query_part $search_string_query_part $sort_query");
        }

        return $filtered_courses;
    }

    public static function save_course_progress($lesson_id, $progress)
    {
        $logged_in_user_details = User::get_logged_in_user_details();
        $watch_history = $logged_in_user_details->watch_history;
        $watch_history_array = array();
        if ($watch_history == '') {
            array_push($watch_history_array, array('lesson_id' => $lesson_id, 'progress' => $progress));
        } else {
            $founder = false;
            $watch_history_array = json_decode($watch_history, true);
            for ($i = 0; $i < count($watch_history_array); $i++) {
                $watch_history_for_each_lesson = $watch_history_array[$i];
                if ($watch_history_for_each_lesson['lesson_id'] == $lesson_id) {
                    $watch_history_for_each_lesson['progress'] = $progress;
                    $watch_history_array[$i]['progress'] = $progress;
                    $founder = true;
                }
            }
            if (!$founder) {
                array_push($watch_history_array, array('lesson_id' => $lesson_id, 'progress' => $progress));
            }
        }
        $data['watch_history'] = json_encode($watch_history_array);
        global $wpdb;
        $wpdb->update(self::$tables['users'], $data, array('id' => $logged_in_user_details->id));
        return json_encode(['status' => true, 'lesson_id' => $lesson_id, 'Progress' => $progress]);
    }

    /**
     * get_course_length
     *
     * @param int $course_id
     * @return string
     */
    public static function get_course_length($course_id)
    {
        $lessons = Lesson::get_lesson_by_course_id($course_id);
        $total_duration = 0;
        foreach ($lessons as $lesson) {
            if ($lesson->lesson_type == "video") {
                $time_array = explode(':', $lesson->duration);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }

        $hours = floor($total_duration / 3600);
        $minutes = floor(($total_duration % 3600) / 60);
        $seconds = $total_duration % 60;
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds) . ' ' . esc_html__('hours');
    }

    /**
     * GET CATEGORY WISE COURSES
     */
    public static function get_category_wise_course($category_id = "", $parent_category_id = "")
    {
        global $wpdb;
        $table = self::$tables['courses'];

        if ($parent_category_id) {
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `sub_category_id` = %d AND `status` = 'active'", $category_id));
        } else {
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `category_id` = %d AND `status` = 'active'", $category_id));
        }

        return $result;
    }

    /**
     * GET TOP RATED COURSES
     */
    public static function get_top_rated_courses()
    {
        global $wpdb;
        $table = self::$tables['courses'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'active' ORDER BY $table.`avg_rating` DESC");
        return $result;
    }
}
