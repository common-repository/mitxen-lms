<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;

defined('ABSPATH') or die('You can not access the file directly');

class Section extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_section', array($this, 'post'));
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
            case 'add_section':
                $this->add_section();
                break;
            case 'edit_section':
                $this->edit_section();
                break;
            case 'delete':
                $this->delete_section();
                break;
            case 'sort_section':
                $this->sort_section();
                break;
        }
    }

    public static function add_section()
    {
        if (self::verify_nonce('add_section_nonce') == true) {
            $data['title']  = sanitize_text_field($_POST['title']);
            $data['course_id']  = sanitize_text_field($_POST['course_id']);

            if (Course::course_authentication($data['course_id'])) {
                global $wpdb;
                $wpdb->insert(self::$tables['sections'], $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Section Added Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function edit_section()
    {
        if (self::verify_nonce('edit_section_nonce') == true) {
            $section_id    = sanitize_text_field($_POST['id']);
            $data['title'] = sanitize_text_field($_POST['title']);
            if (self::section_authentication($section_id)) {
                global $wpdb;
                $wpdb->update(self::$tables['sections'], $data, array('id' => $section_id));
                echo json_encode(['status' => true, 'message' => esc_html__("Section Updated Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function delete_section()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['sections'];
            $section_id = sanitize_text_field($_POST['id']);

            if (self::section_authentication($section_id)) {
                $wpdb->delete($table, ['id' => $section_id]);
                echo json_encode(['status' => true, 'message' => esc_html__("Section Deleted Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function get_authenticated_sections($course_id)
    {
        global $wpdb;
        $result = array();
        $table = self::$tables['sections'];

        if (Course::course_authentication($course_id)) {
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` = %d ORDER BY $table.`order` ASC", $course_id ) );
        }

        return $result;
    }

    public static function get_sections($course_id)
    {
        global $wpdb;
        $result = array();
        $table = self::$tables['sections'];

        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `course_id` = %d ORDER BY $table.`order` ASC", $course_id ) );

        return $result;
    }

    public static function get_authenticated_section_by_id($section_id)
    {
        if (self::section_authentication($section_id)) {
            global $wpdb;
            $table = self::$tables['sections'];
            $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `id` = %d", $section_id ) );
            return $result;
        }
        return array();
    }

    public static function sort_section()
    {
        if (self::verify_nonce('sort_section_nonce') == true) {

            $table = self::$tables['sections'];
            $sections = explode(',', $_POST['section_serial']);
            for ($i = 0; $i < count($sections); $i++) {
                $updater = array(
                    'order' => $i + 1
                );
                global $wpdb;
                $wpdb->update(self::$tables['sections'], $updater, array('id' => sanitize_text_field($sections[$i])));
            }

            echo json_encode(['status' => true, 'message' => esc_html__("Section Sorted Successfully", BaseController::$text_domain)]);
        }
    }

    // CHECKS IF THE SECTION DOES BELONG TO CURRENT USER
    public static function section_authentication($section_id)
    {
        global $wpdb;
        $table = self::$tables['sections'];
        $section_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `id` = %d", $section_id ) );
        if (sizeof($section_details)) {
            $section_details = $section_details[0];
            $course_id = $section_details->course_id;

            $course_table = self::$tables['courses'];
            $course_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $course_table WHERE `id` = %d", $course_id ) );

            if (sizeof($course_details)) {
                $course_details = $course_details[0];
                if (Helper::get_current_user_role() == "administrator") {
                    return true;
                } elseif (Helper::get_current_user_role() == "instructor" && $course_details->user_id == Helper::get_current_user_id()) {
                    return true;
                }
            } else {
                return false;
            }
        }

        return false;
    }
}