<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;

defined('ABSPATH') or die('You can not access the file directly');

class Review extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_review', array($this, 'post'));
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
            case 'edit_review':
                $this->edit_review();
                break;
        }
    }

    public static function edit_review()
    {
        $table = self::$tables['rating'];
        if (self::verify_nonce('edit_review_nonce') == true) {
            $user_id = Helper::get_current_user_id();
            $course_id     = sanitize_text_field($_POST['course_id']);
            $data['review'] = sanitize_text_field($_POST['review']);
            $data['rating'] = sanitize_text_field($_POST['rating']);
            $data['course_id'] = $course_id;
            $data['user_id'] = $user_id;
            $data['date_added'] = strtotime(date('D, d-M-Y'));

            if (Helper::is_purchased($course_id)) {
                global $wpdb;
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d AND `user_id` = %d", $course_id, $user_id));
                if ($result && count($result) > 0) {
                    $result = $result[0];
                    $wpdb->update($table, $data, array('id' => $result->id));
                } else {
                    $wpdb->insert($table, $data);
                }
                // INSERT AVG RATING INTO COURSE TABLE ALSO
                $course_table_data['avg_rating'] = Review::get_course_rating($course_id);
                $wpdb->update(self::$tables['courses'], $course_table_data, array('id' => $course_id));
                echo json_encode(['status' => true, 'message' => esc_html__("Review Updated Successfully", BaseController::$text_domain), 'course_id' => $course_id, 'rating' => $data['rating']]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function get_course_rating($course_id)
    {
        $table = self::$tables['rating'];
        global $wpdb;
        $total_rating = 0;
        $ratings = $wpdb->get_results($wpdb->prepare("SELECT `rating` FROM $table WHERE `course_id` = %d", $course_id));
        $number_of_rows = count($ratings);
        foreach ($ratings as $key => $rating) {
            $total_rating = $total_rating + $rating->rating;
        }

        if ($total_rating && $number_of_rows) {
            $avg_rating = $total_rating / $number_of_rows;
        } else {
            $avg_rating = 0;
        }
        return $avg_rating;
    }

    public static function get_user_wise_course_review($course_id, $user_id)
    {
        $table = self::$tables['rating'];
        global $wpdb;
        $total_rating = 0;
        $ratings = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d AND `user_id` = %d", $course_id, $user_id));
        if (count($ratings)) {
            $rating = $ratings[0];
        } else {
            $rating = 0;
        }

        return $rating;
    }

    public static function get_course_review_and_ratings($course_id)
    {
        $table = self::$tables['rating'];
        global $wpdb;
        $ratings = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d", $course_id));
        return $ratings;
    }

    /**
     * this function will return rating percentage like, 5 star 90% 4 star 2% etc
     *
     * @return array
     */
    public static function get_rating_percentage($course_id)
    {
        $rating_percentages = array();
        $table = self::$tables['rating'];
        global $wpdb;
        $ratings = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d", $course_id));
        $total_number_of_rating = count($ratings);
        for ($i = 1; $i < 6; $i++) {
            $number_of_each_rating = $wpdb->get_var($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d AND `rating` = %d", $course_id, $i));
            $rating_percentages[$i - 1]['rating'] = $i;
            $rating_percentages[$i - 1]['percentage'] = ($number_of_each_rating * 100) / $total_number_of_rating;
        }
        return $rating_percentages;
    }
}
