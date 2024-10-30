<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Category extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_category', array($this, 'post'));
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
            case 'add_category':
                $this->add_category();
                break;
            case 'edit_category':
                $this->edit_category();
                break;
            case 'delete':
                $this->delete_category();
                break;
        }
    }

    private function add_category()
    {
        if (self::verify_nonce('add_category_nonce') == true) {
            $data['title']              = sanitize_text_field($_POST['title']);
            $data['parent_category_id'] = (isset($_POST['parent_category_id']) && !empty($_POST['parent_category_id'])) ? sanitize_text_field($_POST['parent_category_id']) : 0;
            $slug                       = Helper::slugify(sanitize_text_field($_POST['title']));

            if ($data['parent_category_id'] > 0) {
                $data['thumbnail']   = "";
                $data['is_featured']           = "";
            } else {
                $data['thumbnail']   = sanitize_text_field($_POST['category_image_path']);
                $data['is_featured']           = sanitize_text_field($_POST['is_featured']);
            }

            $table                      = self::$tables['categories'];
            global $wpdb;
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `slug` = %s", $slug));
            if ($result && count($result)) {
                echo json_encode(['status' => false, 'message' => esc_html__("Category With The Same Name Already Added", BaseController::$text_domain)]);
            } else {
                $data['slug'] = $slug;
                $wpdb->insert($table, $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Category Added Successfully", BaseController::$text_domain), 'id' => $wpdb->insert_id]);
            }
        }
    }

    private function edit_category()
    {
        if (self::verify_nonce('edit_category_nonce') == true) {
            $category_id                = sanitize_text_field($_POST['id']);
            $data['title']              = sanitize_text_field($_POST['title']);
            $slug                       = Helper::slugify(sanitize_text_field($_POST['title']));
            $data['parent_category_id'] = sanitize_text_field($_POST['parent_category_id']);

            if ($data['parent_category_id'] > 0) {
                $data['thumbnail']   = "";
                $data['is_featured']           = "";
            } else {
                $data['thumbnail']   = sanitize_text_field($_POST['category_image_path']);
                $data['is_featured']           = sanitize_text_field($_POST['is_featured']);
            }

            $table = self::$tables['categories'];
            global $wpdb;
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `slug` = %s AND `id` != %d", $slug, $category_id));

            if ($result && count($result)) {
                echo json_encode(['status' => false, 'message' => esc_html__("Category With The Same Name Already Added", BaseController::$text_domain)]);
            } else {
                $data['slug'] = $slug;
                $wpdb->update(self::$tables['categories'], $data, array('id' => $category_id));
                echo json_encode(['status' => true, 'message' => esc_html__("Category Updated Successfully", BaseController::$text_domain)]);
            }
        }
    }

    private function delete_category()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table  = self::$tables['categories'];
            $id     = sanitize_text_field($_POST['id']);
            $wpdb->delete($table, ['id' => $id]);
            echo json_encode(['status' => true, 'message' => esc_html__("Category Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    public static function get_all_categories()
    {
        global $wpdb;
        $table  = self::$tables['categories'];
        $result = $wpdb->get_results("SELECT * FROM $table");
        return $result;
    }

    public static function paginate_categories($page_number, $limit)
    {
        global $wpdb;
        $table  = self::$tables['categories'];
        $offset = ($page_number - 1)  * $limit;
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table LIMIT %d OFFSET %d", $limit, $offset));
        return $result;
    }

    public static function get_category_details_by_id($category_id)
    {
        global $wpdb;
        $table = self::$tables['categories'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $category_id));
        return $result;
    }

    public static function get_sub_categories()
    {
        global $wpdb;
        $table = self::$tables['categories'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `parent_category_id` != 0");
        return $result;
    }

    public static function get_sub_categories_by_category_id($category_id)
    {
        global $wpdb;
        $table = self::$tables['categories'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `parent_category_id` = %d", $category_id));
        return $result;
    }

    public static function get_parent_categories()
    {
        global $wpdb;
        $table = self::$tables['categories'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `parent_category_id` = 0");
        return $result;
    }

    /**
     * GET FEATURED CATEGORIES
     *
     * @return array
     */
    public static function get_featured_categories()
    {
        global $wpdb;
        $table = self::$tables['categories'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `is_featured` = 1");
        return $result;
    }
}
