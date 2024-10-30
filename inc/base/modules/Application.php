<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Application extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_application', array($this, 'post'));
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
            case 'add_application':
                $this->add_application();
                break;
            case 'edit_application':
                $this->edit_application();
                break;
            case 'delete':
                $this->delete_application();
                break;
            case 'approve':
                $this->approve_application();
                break;
        }
    }

    public static function add_application()
    {
        $table = self::$tables['applications'];
        global $wpdb;
        if (self::verify_nonce('add_application_nonce') == true) {
            $current_user_role = Helper::get_current_user_role();
            if ($current_user_role == "student") {
                $current_user_id = Helper::get_current_user_id();
                $data['user_id'] = sanitize_text_field($current_user_id);
                $data['address'] = sanitize_text_field($_POST['address']);
                $data['phone'] = sanitize_text_field($_POST['phone']);
                $data['message'] = sanitize_text_field($_POST['message']);
                $data['document'] = sanitize_text_field($_POST['document_path']);
                $wpdb->insert($table, $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Your Application Has Been Submitted Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not eligible to do this", BaseController::$text_domain)]);
            }
        }
    }

    public static function edit_application()
    {
        if (self::verify_nonce('edit_application_nonce') == true) {
        }
    }

    public static function delete_application()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['applications'];
            $id = sanitize_text_field($_POST['id']);
            $wpdb->delete($table, ['id' => $id]);
            echo json_encode(['status' => true, 'message' => esc_html__("Application Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    public static function approve_application()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            // UPDATE APPLICATION STATUS
            global $wpdb;
            $table = self::$tables['applications'];
            $id = sanitize_text_field($_POST['id']);
            $data['status'] = 1;
            $wpdb->update($table, $data, array('id' => $id));

            // UPDATE WP USER ROLE
            $application_details = Application::get_application_by_id($id);
            $user_details = User::get_user_by_id($application_details->user_id);

            $user = get_user_by('id', $user_details->wp_user_id);
            $user->remove_role(self::$custom_roles['student']['role']);
            $user->add_role(self::$custom_roles['instructor']['role']);

            // UPDATE USER TABLE ROLE
            $userdata['role'] = 'instructor';
            $wpdb->update(self::$tables['users'], $userdata, array('id' => $application_details->user_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Application Status Updated Successfully", BaseController::$text_domain)]);
        }
    }

    public static function get_applications()
    {
        global $wpdb;
        $table = self::$tables['applications'];
        $result = $wpdb->get_results( "SELECT * FROM $table" );
        return $result;
    }
    public static function get_application_by_id($application_id)
    {
        global $wpdb;
        $table = self::$tables['applications'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `id` = %d", $application_id ) );
        return $result[0];
    }
    public static function get_application_by_user_id($user_id)
    {
        global $wpdb;
        $table = self::$tables['applications'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `user_id` = %d", $user_id ) );
        return $result;
    }

    public static function paginate($page_number, $limit)
    {
        global $wpdb;
        $table = self::$tables['applications'];
        $offset = ($page_number - 1)  * $limit;
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `status` = 0 ORDER BY `id` DESC LIMIT %d OFFSET %d", $limit, $offset ) );
        return $result;
    }
}