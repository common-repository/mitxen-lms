<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Language extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_language', array($this, 'post'));
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
            case 'add_language':
                $this->add_language();
                break;
            case 'edit_language':
                $this->edit_language();
                break;
            case 'delete':
                $this->delete_language();
                break;
        }
    }

    private function add_language()
    {
        if (self::verify_nonce('add_language_nonce') == true) {
            $data['name']              = sanitize_text_field($_POST['name']);
            $data['code']              = trim(sanitize_text_field($_POST['code']));
            if (!empty($data['name'])) {
                if (strlen($data['code']) == 2) {
                    global $wpdb;
                    $wpdb->insert(self::$tables['languages'], $data);
                    echo json_encode(['status' => true, 'message' => esc_html__("Language Added Successfully", BaseController::$text_domain)]);
                } else {
                    echo json_encode(['status' => false, 'message' => esc_html__("Language code has to be two characters", BaseController::$text_domain)]);
                }
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("Language name can not be empty", BaseController::$text_domain)]);
            }
        }
    }

    private function edit_language()
    {
        if (self::verify_nonce('edit_language_nonce') == true) {
            $language_id  = sanitize_text_field($_POST['id']);
            $data['name'] = sanitize_text_field($_POST['name']);
            $data['code'] = trim(sanitize_text_field($_POST['code']));

            if (!empty($data['name'])) {
                if (strlen($data['code']) == 2) {
                    global $wpdb;
                    $wpdb->update(self::$tables['languages'], $data, array('id' => $language_id));
                    echo json_encode(['status' => true, 'message' => esc_html__("Language Updated Successfully", BaseController::$text_domain)]);
                } else {
                    echo json_encode(['status' => false, 'message' => esc_html__("Language code has to be two characters", BaseController::$text_domain)]);
                }
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("Language name can not be empty", BaseController::$text_domain)]);
            }
        }
    }

    private function delete_language()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['languages'];
            $id = sanitize_text_field($_POST['id']);
            $wpdb->delete($table, ['id' => $id]);
            echo json_encode(['status' => true, 'message' => esc_html__("Language Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    public static function get_all_languages()
    {
        global $wpdb;
        $table = self::$tables['languages'];
        $result = $wpdb->get_results( "SELECT * FROM $table" );
        return $result;
    }

    public static function get_language_by_id($id)
    {
        global $wpdb;
        $table = self::$tables['languages'];
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `id`= %d", $id ) );
        return $result;
    }
}