<?php

/**
 * @package mitxenLMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use WP_Filesystem_Direct;

defined('ABSPATH') or die('You can not access the file directly');

class Addon extends BaseController
{

    // CONSTRUCTOR
    function __construct()
    {
        $this->register();
    }
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_addon', array($this, 'post'));
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
            case 'add_addon':
                $this->add_addon();
                break;
            case 'edit_addon':
                $this->edit_addon();
                break;
            case 'delete':
                $this->delete_addon();
                break;
        }
    }

    function renaming_file($filename)
    {
        $info = pathinfo($filename);
        $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);

        return md5($name) . $ext;
    }

    /**
     * METHOD FOR INSTALLING AN ADDON.
     */
    private function add_addon()
    {
        if (self::verify_nonce('add_addon_nonce') == true) {

            // GET THE UPLOADED ADDON FILE PATH. IT RETURNS THE FULL PATH
            $addon_path  = get_attached_file(sanitize_text_field($_POST['addon_path']));
            // GET THE UPLOADED ADDON FILE NAME FROM THE FILE PATH
            $addon_file = wp_basename($addon_path);

            WP_Filesystem();

            global $wp_filesystem;

            $destination_path = Helper::get_plugin_path('uploads/addons');
            $unzipfile = unzip_file($addon_path, $destination_path);

            wp_delete_attachment($_POST['addon_path'], true);

            // GET THE UPLOADED ADDON FILE TYPE
            $filetype = wp_check_filetype($addon_file);
            $filetype = $filetype['ext'];

            // CHECK IF THE ADDON FILE TYPE IS ZIP
            if ($filetype == "zip") {
                if ($unzipfile) {
                    $unzipped_file_name = substr($addon_file, 0, -4);
                    if (is_dir(Helper::get_plugin_path("uploads/addons/$unzipped_file_name"))) {
                        if (file_exists(Helper::get_plugin_path("uploads/addons/$unzipped_file_name/config.json"))) {
                            $config_str = file_get_contents(Helper::get_plugin_path("uploads/addons/$unzipped_file_name/config.json"));
                            $config = json_decode($config_str, true);
                            // INSERT OR UPDATE AN ENTRY ON DATABASE
                            $data['name'] = sanitize_text_field($config['name']);
                            $unique_identifier = sanitize_text_field($config['unique_identifier']);
                            $data['unique_identifier'] = $unique_identifier;
                            $data['version'] = sanitize_text_field($config['version']);
                            $data['about'] = sanitize_text_field($config['about']);
                            $data['status'] = 1;

                            global $wpdb;
                            $table = self::$tables['addons'];
                            $previous_entries =  $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE `unique_identifier`= %s", $unique_identifier ) );

                            // CHECKING IF THE ADDON ALREADY EXISTS
                            if (count($previous_entries) > 0) {
                                $data['updated_at'] = strtotime(date('d-m-y'));
                                $wpdb->update($table, $data, array('unique_identifier' => $data['unique_identifier']));
                            } else {
                                $data['created_at'] = strtotime(date('d-m-y'));
                                $wpdb->insert(self::$tables['addons'], $data);
                            }


                            // CREATE OR REPLACE NEW FILES
                            if (!empty($config['files'])) {
                                foreach ($config['files'] as $file) {
                                    copy(Helper::get_plugin_path($file['root_directory']), Helper::get_plugin_path($file['update_directory']));
                                }
                            }

                            $wp_filesystem->rmdir(Helper::get_plugin_path("uploads/addons/$unzipped_file_name"), true);
                            echo json_encode(['status' => true, 'message' => esc_html__("Addon installed successfully", BaseController::$text_domain)]);
                        } else {
                            $wp_filesystem->rmdir(Helper::get_plugin_path("uploads/addons/$unzipped_file_name"), true);
                            echo json_encode(['status' => false, 'message' => esc_html__("Invalid addon file", BaseController::$text_domain)]);
                        }
                    } else {
                        $wp_filesystem->rmdir(Helper::get_plugin_path("uploads/addons/$unzipped_file_name"), true);
                        echo json_encode(['status' => false, 'message' => esc_html__("An error occurred during reading the zip", BaseController::$text_domain)]);
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => esc_html__("An error occurred while extracting the zip", BaseController::$text_domain)]);
                }
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid addon file", BaseController::$text_domain)]);
            }
        }
    }

    public static function edit_addon()
    {
    }

    public static function delete_addon()
    {
    }

    public static function get_all_addons()
    {
        global $wpdb;
        $table = self::$tables['addons'];
        $result = $wpdb->get_results( "SELECT * FROM $table ORDER BY `id` DESC" );
        return $result;
    }

    public static function paginate_addons($page_number, $limit)
    {
        global $wpdb;
        $table = self::$tables['addons'];
        $offset = ($page_number - 1)  * $limit;
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table ORDER BY `id` DESC LIMIT %d OFFSET %d", $limit, $offset ) );
        return $result;
    }
}