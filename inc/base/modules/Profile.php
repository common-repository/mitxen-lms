<?php

/**
 * @package mitxenLMS
 */

namespace Mxlms\base\modules;


use Mxlms\base\modules\Settings;
use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Profile extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_profile', array($this, 'post'));
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
            case 'update_profile':
                $this->update_profile();
                break;
            case 'update_password':
                $this->update_password();
                break;
        }
    }

    public static function update_profile()
    {
        if (self::verify_nonce('update_profile_nonce') == true) {

            $email = sanitize_email($_POST['email']);
            $user_id = sanitize_text_field($_POST['id']);
            $user_details = User::get_user_by_id($user_id);
            $wp_userdata = get_userdata($user_details->wp_user_id);
            // CHECK IF THE ID GOT MATCHED WITH THE LOGGED IN USER

            if ($user_id == Helper::get_current_user_id()) {
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
                $data['profile_image_path']  = sanitize_text_field($_POST['user_image_path']);

                // SOCIAL INFORMATION
                $social_link['facebook'] = sanitize_text_field($_POST['facebook']);
                $social_link['twitter'] = sanitize_text_field($_POST['twitter']);
                $social_link['linkedin'] = sanitize_text_field($_POST['linkedin']);
                $data['social_links'] = json_encode($social_link);


                wp_update_user(array('ID' => $wp_userdata->ID, 'user_email' => esc_attr($email)));
                global $wpdb;
                $wpdb->update(self::$tables['users'], $data, array('id' => $user_id));
                echo json_encode(['status' => true, 'message' => esc_html__("Profile Updated Successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }

    public static function update_password()
    {
        if (self::verify_nonce('update_password_nonce') == true) {

            $user_id = sanitize_text_field($_POST['id']);
            $user_details = User::get_user_by_id($user_id);
            $wp_userdata = get_userdata($user_details->wp_user_id);
            // CHECK IF THE ID GOT MATCHED WITH THE LOGGED IN USER
            if ($user_id == Helper::get_current_user_id()) {

                // it has not been sanitized because password can be anything.
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];

                // THIS IS THE EXISTING HASHED PASSWORD
                $old_hashed_password = $wp_userdata->user_pass;
                if (wp_check_password($current_password, $old_hashed_password, $wp_userdata->ID)) {
                    if ($new_password == $confirm_password) {
                        wp_set_password($confirm_password, $user_details->wp_user_id);
                        echo json_encode(['status' => true, 'message' => esc_html__("Profile Updated Successfully. Please login again", BaseController::$text_domain)]);
                    } else {
                        echo json_encode(['status' => false, 'message' => esc_html__("Password Mismatched", BaseController::$text_domain)]);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => esc_html__("Password Mismatched Here", BaseController::$text_domain)]);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
            }
        }
    }
}