<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Settings extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_settings', array($this, 'post'));
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
            case 'update_general_settings':
                $this->update_general_settings();
                break;
            case 'update_instructor_settings':
                $this->update_instructor_settings();
                break;
            case 'update_payment_settings':
                $this->update_payment_settings();
                break;
            case 'update_language_settings':
                $this->update_language_settings();
                break;
            case 'update_page_settings':
                $this->update_page_settings();
                break;
        }
    }

    private function update_general_settings()
    {
        if (self::verify_nonce('update_general_settings_nonce') == true) {
            $data['system_name']              = sanitize_text_field($_POST['system_name']);
            $data['system_email']              = sanitize_text_field($_POST['system_email']);
            $data['address']              = sanitize_text_field($_POST['address']);
            $data['phone']              = sanitize_text_field($_POST['phone']);
            $data['purchase_code']              = sanitize_text_field($_POST['purchase_code']);
            $data['youtube_api_key']              = sanitize_text_field($_POST['youtube_api_key']);
            $data['vimeo_api_key']              = sanitize_text_field($_POST['vimeo_api_key']);
            $data['logo_lg_path']  = sanitize_text_field($_POST['logo_lg_path']);
            $data['logo_sm_path']  = sanitize_text_field($_POST['logo_sm_path']);
            global $wpdb;
            foreach ($data as $key => $value) {
                $updater['value'] = $value;
                $wpdb->update(self::$tables['general_settings'], $updater, array('key' => $key));
            }
            echo json_encode(['status' => true, 'message' => esc_html__("General Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_instructor_settings()
    {
        if (self::verify_nonce('update_instructor_settings_nonce') == true) {
            $data['allow_public_instructor']       = sanitize_text_field($_POST['allow_public_instructor']);
            $data['instructor_application_note']   = sanitize_text_field($_POST['instructor_application_note']);
            $data['instructor_revenue_percentage'] = sanitize_text_field($_POST['instructor_revenue_percentage']);
            if ($data['instructor_revenue_percentage'] < 0 || $data['instructor_revenue_percentage'] > 100) {
                echo json_encode(['status' => true, 'message' => esc_html__("Revenue percentage has to be a valid number", BaseController::$text_domain)]);
            } else {
                $data['admin_revenue_percentage'] = 100 - sanitize_text_field($_POST['instructor_revenue_percentage']);

                global $wpdb;
                foreach ($data as $key => $value) {
                    $updater['value'] = $value;
                    $wpdb->update(self::$tables['instructor_settings'], $updater, array('key' => $key));
                }
                echo json_encode(['status' => true, 'message' => esc_html__("Instructor Settings Updated Successfully", BaseController::$text_domain)]);
            }
        }
    }

    private function update_payment_settings()
    {
        if (self::verify_nonce('update_payment_settings_nonce') == true) {
            if (sanitize_text_field($_POST['type']) == "system") {
                $system_payment_data['system_currency']       = sanitize_text_field($_POST['system_currency']);
                $system_payment_data['currency_position']   = sanitize_text_field($_POST['currency_position']);
                $data['system'] = json_encode($system_payment_data);
                global $wpdb;
                foreach ($data as $key => $value) {
                    $updater['value'] = $value;
                    $wpdb->update(self::$tables['payment_settings'], $updater, array('key' => $key));
                }
                echo json_encode(['status' => true, 'message' => esc_html__("Payment Settings Updated Successfully", BaseController::$text_domain)]);
                return;
            } elseif (sanitize_text_field($_POST['type']) == "paypal") {
                $system_payment_data['active']       = sanitize_text_field($_POST['active']);
                $system_payment_data['mode']   = sanitize_text_field($_POST['mode']);
                $system_payment_data['currency']   = sanitize_text_field($_POST['currency']);
                $system_payment_data['sandbox_client_id']   = sanitize_text_field($_POST['sandbox_client_id']);
                $system_payment_data['sandbox_secret_key']   = sanitize_text_field($_POST['sandbox_secret_key']);
                $system_payment_data['production_client_id']   = sanitize_text_field($_POST['production_client_id']);
                $system_payment_data['production_secret_key']   = sanitize_text_field($_POST['production_secret_key']);
                $data['paypal'] = json_encode($system_payment_data);
                global $wpdb;
                foreach ($data as $key => $value) {
                    $updater['value'] = $value;
                    $wpdb->update(self::$tables['payment_settings'], $updater, array('key' => $key));
                }
                echo json_encode(['status' => true, 'message' => esc_html__("Paypal Settings Updated Successfully", BaseController::$text_domain)]);
                return;
            } elseif (sanitize_text_field($_POST['type']) == "stripe") {
                $stripe_payment_data['active']       = sanitize_text_field($_POST['stripe_active']);
                $stripe_payment_data['testmode']   = sanitize_text_field($_POST['test_mode']);
                $stripe_payment_data['currency']   = sanitize_text_field($_POST['stripe_currency']);
                $stripe_payment_data['public_key']   = sanitize_text_field($_POST['test_public_key']);
                $stripe_payment_data['secret_key']   = sanitize_text_field($_POST['test_secret_key']);
                $stripe_payment_data['public_live_key']   = sanitize_text_field($_POST['live_public_key']);
                $stripe_payment_data['secret_live_key']   = sanitize_text_field($_POST['live_secret_key']);
                $data['stripe'] = json_encode($stripe_payment_data);
                global $wpdb;
                foreach ($data as $key => $value) {
                    $updater['value'] = $value;
                    $wpdb->update(self::$tables['payment_settings'], $updater, array('key' => $key));
                }
                echo json_encode(['status' => true, 'message' => esc_html__("Stripe Settings Updated Successfully", BaseController::$text_domain)]);
                return;
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid Payment Settings", BaseController::$text_domain)]);
                return;
            }
        }
    }


    private function update_language_settings()
    {
        if (self::verify_nonce('update_language_settings_nonce') == true) {
            global $wpdb;
            $updater['value'] = sanitize_text_field($_POST['system_language']);
            $wpdb->update(self::$tables['general_settings'], $updater, array('key' => 'system_language'));
            echo json_encode(['status' => true, 'message' => esc_html__("Language Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_page_settings()
    {
        if (self::verify_nonce('update_page_settings_nonce') == true) {
            global $wpdb;
            $updater['value'] = sanitize_text_field($_POST['public_page']);
            $wpdb->update(self::$tables['page_settings'], $updater, array('key' => 'public_page'));

            $updater['value'] = sanitize_text_field($_POST['private_page']);
            $wpdb->update(self::$tables['page_settings'], $updater, array('key' => 'private_page'));

            $updater['value'] = sanitize_text_field($_POST['live_class_page']);
            $wpdb->update(self::$tables['page_settings'], $updater, array('key' => 'live_class_page'));
            echo json_encode(['status' => true, 'message' => esc_html__("Page Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    public static function get_all_currencies()
    {
        global $wpdb;
        $table = self::$tables['currencies'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table"));
        return $result;
    }
}
