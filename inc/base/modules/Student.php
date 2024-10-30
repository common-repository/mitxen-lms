<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Student extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_student', array($this, 'post'));
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
            case 'add_student':
                $this->add_student();
                break;
            case 'edit_student':
                $this->edit_student();
                break;
            case 'delete':
                $this->delete_student();
                break;
        }
    }

    // Method for adding a new student (adds an user in wp-user table and keeps one within plugin's ins table)
    private function add_student()
    {
        if (self::verify_nonce('add_student_nonce') == true) {

            // Create a wp user of role 'student'
            $username   = sanitize_text_field($_POST['username']);
            $email      = sanitize_email($_POST['email']);
            $password   = sanitize_text_field($_POST['password']);

            // Return if username or email already exists otherwise add user
            if (username_exists($username) || email_exists($email)) {
                echo json_encode(['status' => false, 'message' => esc_html__("Username or Email duplication", BaseController::$text_domain)]);
            } else {
                $user_id = wp_create_user($username, $password, $email);
                $user = get_user_by('id', $user_id);
                $user->remove_role('subscriber');
                $user->add_role(self::$custom_roles['student']['role']);
                // Create an entry within plugin's user table
                $data['wp_user_id']     =   $user_id;
                $data['first_name']     =   sanitize_text_field($_POST['firstname']);
                $data['last_name']      =   sanitize_text_field($_POST['lastname']);
                $data['email']          =   sanitize_text_field($_POST['email']);
                $data['social_links']   =   "social_information";
                $data['biography']      =   sanitize_text_field($_POST['biography']);
                $data['role']           =   'student';
                $data['profile_image_path']  = sanitize_text_field($_POST['student_image_path']);

                // SOCIAL INFORMATION
                $social_link['facebook'] = sanitize_text_field($_POST['facebook_link']);
                $social_link['twitter'] = sanitize_text_field($_POST['twitter_link']);
                $social_link['linkedin'] = sanitize_text_field($_POST['linkedin_link']);
                $data['social_links'] = json_encode($social_link);

                // Add paypal keys
                $paypal_info = array(
                    'production_client_id' => 'paypal-client-id',
                    'production_secret_key' => 'paypal-secret-key'
                );
                $data['paypal_keys'] = json_encode($paypal_info);

                // Add Stripe keys
                $stripe_info = array(
                    'public_live_key' => 'stripe-live-key',
                    'secret_live_key' => 'stripe-secret-key'
                );
                $data['stripe_keys'] = json_encode($stripe_info);

                $data['status'] = 1;
                global $wpdb;
                $wpdb->insert(self::$tables['users'], $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Student Added Successfully", BaseController::$text_domain)]);
            }
        }
    }

    private function edit_student()
    {
        if (self::verify_nonce('edit_student_nonce') == true) {

            // Create a wp user of role 'student'
            $email = sanitize_email($_POST['email']);
            $user_id = sanitize_text_field($_POST['id']);
            $student_details = self::get_student_by_id($user_id);
            $wp_userdata = get_userdata($student_details->wp_user_id);


            // Return if username or email already exists otherwise add user
            if ($wp_userdata->user_email != $email) {
                if (email_exists($email)) {
                    echo json_encode(['status' => false, 'message' => esc_html__("Username or Email duplication", BaseController::$text_domain)]);
                    return;
                }
            }

            // Create an entry within plugin's user table
            $data['first_name']     =   sanitize_text_field($_POST['firstname']);
            $data['last_name']      =   sanitize_text_field($_POST['lastname']);
            $data['email']          =   sanitize_text_field($_POST['email']);
            $data['social_links']   =   "social_information";
            $data['biography']      =   sanitize_text_field($_POST['biography']);
            $data['profile_image_path']  = sanitize_text_field($_POST['student_image_path']);

            // SOCIAL INFORMATION
            $social_link['facebook'] = sanitize_text_field($_POST['facebook_link']);
            $social_link['twitter'] = sanitize_text_field($_POST['twitter_link']);
            $social_link['linkedin'] = sanitize_text_field($_POST['linkedin_link']);
            $data['social_links'] = json_encode($social_link);

            // Add paypal keys
            $paypal_info = array(
                'production_client_id' => 'paypal-client-id',
                'production_secret_key' => 'paypal-secret-key'
            );
            $data['paypal_keys'] = json_encode($paypal_info);

            // Add Stripe keys
            $stripe_info = array(
                'public_live_key' => 'stripe-live-key',
                'secret_live_key' => 'stripe-live-key'
            );
            $data['stripe_keys'] = json_encode($stripe_info);

            wp_update_user(array('ID' => $wp_userdata->ID, 'user_email' => esc_attr($email)));
            global $wpdb;
            $wpdb->update(self::$tables['users'], $data, array('id' => $user_id));
            echo json_encode(['status' => true, 'message' => esc_html__("Student Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function delete_student()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['users'];
            $id = sanitize_text_field($_POST['id']);
            $student_details = self::get_student_by_id($id);
            $wp_userdata = get_userdata($student_details->wp_user_id);

            wp_delete_user($wp_userdata->ID);

            $wpdb->delete($table, ['id' => $id]);

            echo json_encode(['status' => true, 'message' => esc_html__("Student Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    public static function get_student_by_id($student_id = "")
    {
        global $wpdb;
        $table = self::$tables['users'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `id` = %d", $student_id ) );
        return $result[0];
    }

    public static function get_student()
    {
        global $wpdb;
        $table = self::$tables['users'];
        $result = $wpdb->get_results( "SELECT * FROM $table where `role` = 'student' ORDER BY `id` DESC" );
        return $result;
    }

    public static function paginate_student($page_number, $limit)
    {
        global $wpdb;
        $table = self::$tables['users'];
        $offset = ($page_number - 1)  * $limit;
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table where `role` = 'student' ORDER BY `id` DESC LIMIT %d OFFSET %d", $limit, $offset ) );
        return $result;
    }
}
