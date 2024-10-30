<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Section;

defined('ABSPATH') or die('You can not access the file directly');
class Lesson extends BaseController
{
  // Method for registering form submission hook to this plugin
  public function register()
  {
    add_action('admin_post_' . self::$plugin_id . '_lesson', array($this, 'post'));
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
      case 'add_lesson':
        $this->add_lesson();
        break;
      case 'edit_lesson':
        $this->edit_lesson();
        break;
      case 'delete':
        $this->delete_lesson();
        break;
      case 'sort_lesson':
        $this->sort_lesson();
        break;
    }
  }

  public static function add_lesson()
  {
    if (self::verify_nonce('add_lesson_nonce') == true) {
      $data['title'] = sanitize_text_field($_POST['title']);
      $data['course_id'] = sanitize_text_field($_POST['course_id']);
      $data['section_id'] = sanitize_text_field($_POST['section_id']);
      $data['summary'] = sanitize_text_field($_POST['summary']);
      $data['lesson_type'] = sanitize_text_field($_POST['lesson_type']);

      if ($data['lesson_type'] == "video") {
        $data['attachment_type'] = $data['video_provider'] == "system" ? "file" : "url";
        $data['duration'] = sanitize_text_field($_POST['duration']);
        $data['video_provider'] = sanitize_text_field($_POST['video_provider']);
        $data['video_url'] = sanitize_text_field($_POST['video_url']);
      } elseif ($data['lesson_type'] == "iframe") {
        $data['attachment_type'] = "iframe";
        $data['attachment'] = sanitize_text_field($_POST['iframe_source']);
      } elseif ($data['lesson_type'] == 'image') {
        $data['attachment_type'] = "img";
        $data['attachment'] = sanitize_text_field($_POST['image_file_path']);
      } elseif ($data['lesson_type'] == 'document') {
        $data['attachment_type'] = sanitize_text_field($_POST['document_type']);;
        $data['attachment'] = sanitize_text_field($_POST['document_path']);
      }

      // CHECK IF THE VIDEO PROVIDER IS AMAZON S3
      if ($data['lesson_type'] == "video" && $data['video_provider'] == "s3") {
        $uploaded_file_name = basename($data['video_url']);
        $upload_details = wp_upload_dir();
        $uploaded_file_path = $upload_details['path'] . '/' . $uploaded_file_name;

        $s3config = array(
          'region'  => Helper::get_aws_settings('amazon_s3_region_code'),
          'version' => 'latest',
          'credentials' => [
            'key'    => Helper::get_aws_settings('amazon_s3_access_key'), //Put key here
            'secret' => Helper::get_aws_settings('amazon_s3_secret_key') // Put Secret here
          ]
        );

        $s3 = new \Aws\S3\S3Client($s3config);
        $key = str_replace(".", "-" . rand(1, 9999) . ".", $uploaded_file_name);

        $result = $s3->putObject([
          'Bucket' => Helper::get_aws_settings('amazon_s3_bucket_name'),
          'Key'    => $key,
          'SourceFile' => $uploaded_file_path,
          'ACL'   => 'public-read'
        ]);

        $data['video_url'] = $result['ObjectURL'];

        // REMOVING THE FILE FROM UPLOADS FOLDER
        wp_delete_file($uploaded_file_path);
      }

      $data['date_added'] = strtotime(date('D, d M Y'));

      if (Course::course_authentication($data['course_id'])) {
        global $wpdb;
        $wpdb->insert(self::$tables['lessons'], $data);
        echo json_encode(['status' => true, 'message' => esc_html__("Lesson Added Successfully", BaseController::$text_domain)]);
      } else {
        echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
      }
    }
  }

  public static function edit_lesson()
  {
    if (self::verify_nonce('edit_lesson_nonce') == true) {
      $lesson_id = sanitize_text_field($_POST['id']);

      $data['title'] = sanitize_text_field($_POST['title']);
      $data['course_id'] = sanitize_text_field($_POST['course_id']);
      $data['section_id'] = sanitize_text_field($_POST['section_id']);
      $data['summary'] = sanitize_text_field($_POST['summary']);
      $data['lesson_type'] = sanitize_text_field($_POST['lesson_type']);

      if ($data['lesson_type'] == "video") {
        $data['attachment_type'] = $data['video_provider'] == "system" ? "file" : "url";
        $data['duration'] = sanitize_text_field($_POST['duration']);
        $data['video_provider'] = sanitize_text_field($_POST['video_provider']);
        $data['video_url'] = sanitize_text_field($_POST['video_url']);
      } elseif ($data['lesson_type'] == "iframe") {
        $data['attachment_type'] = "iframe";
        $data['attachment'] = sanitize_text_field($_POST['iframe_source']);
      } elseif ($data['lesson_type'] == 'image') {
        $data['attachment_type'] = "img";
        $data['attachment'] = sanitize_text_field($_POST['image_file_path']);
      } elseif ($data['lesson_type'] == 'document') {
        $data['attachment_type'] = sanitize_text_field($_POST['document_type']);;
        $data['attachment'] = sanitize_text_field($_POST['document_path']);
      }


      // CHECK IF THE VIDEO PROVIDER IS AMAZON S3
      if ($data['lesson_type'] == "video" && $data['video_provider'] == "s3") {
        $uploaded_file_name = basename($data['video_url']);
        $upload_details = wp_upload_dir();
        $uploaded_file_path = $upload_details['path'] . '/' . $uploaded_file_name;

        $s3config = array(
          'region'  => Helper::get_aws_settings('amazon_s3_region_code'),
          'version' => 'latest',
          'credentials' => [
            'key'    => Helper::get_aws_settings('amazon_s3_access_key'), //Put key here
            'secret' => Helper::get_aws_settings('amazon_s3_secret_key') // Put Secret here
          ]
        );

        $s3 = new \Aws\S3\S3Client($s3config);
        $key = str_replace(".", "-" . rand(1, 9999) . ".", $uploaded_file_name);

        $result = $s3->putObject([
          'Bucket' => Helper::get_aws_settings('amazon_s3_bucket_name'),
          'Key'    => $key,
          'SourceFile' => $uploaded_file_path,
          'ACL'   => 'public-read'
        ]);

        $data['video_url'] = $result['ObjectURL'];

        // REMOVING THE FILE FROM UPLOADS FOLDER
        wp_delete_file($uploaded_file_path);
      }

      $data['last_modified'] = strtotime(date('D, d M Y'));

      if (Course::course_authentication($data['course_id'])) {
        global $wpdb;
        $wpdb->update(self::$tables['lessons'], $data, array('id' => $lesson_id));

        echo json_encode(['status' => true, 'message' => esc_html__("Lesson Updated Successfully", BaseController::$text_domain)]);
      } else {
        echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
      }
    }
  }

  public static function delete_lesson()
  {
    if (self::verify_nonce('confirmation_form_nonce') == true) {
      global $wpdb;
      $table = self::$tables['lessons'];
      $id = sanitize_text_field($_POST['id']);
      if (self::lesson_authentication($id)) {
        $wpdb->delete($table, ['id' => $id]);
        echo json_encode(['status' => true, 'message' => esc_html__("Lesson Deleted Successfully", BaseController::$text_domain)]);
      } else {
        echo json_encode(['status' => false, 'message' => esc_html__("You are not authorized", BaseController::$text_domain)]);
      }
    }
  }

  public static function get_authenticated_lessons_by_section_id($section_id)
  {
    $result = array();
    if (Section::section_authentication($section_id)) {
      global $wpdb;
      $table = self::$tables['lessons'];
      $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `section_id` = %d ORDER BY $table.`order` ASC", $section_id));
    }
    return $result;
  }

  public static function get_lessons_by_section_id($section_id)
  {
    $result = array();
    global $wpdb;
    $table = self::$tables['lessons'];
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `section_id` = %d ORDER BY $table.`order` ASC", $section_id));
    return $result;
  }

  public static function get_authenticated_lesson_by_id($lesson_id)
  {
    if (self::lesson_authentication($lesson_id)) {
      global $wpdb;
      $table = self::$tables['lessons'];
      $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $lesson_id));
      return $result[0];
    } else {
      return array();
    }
  }
  public static function get_lesson_by_id($lesson_id)
  {
    global $wpdb;
    $table = self::$tables['lessons'];
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $lesson_id));
    return $result[0];
  }

  public static function sort_lesson()
  {
    if (self::verify_nonce('sort_lesson_nonce') == true) {

      $table = self::$tables['lessons'];
      $lessons = explode(',', $_POST['lesson_serial']);
      for ($i = 0; $i < count($lessons); $i++) {
        $updater = array(
          'order' => $i + 1
        );
        global $wpdb;
        $wpdb->update(self::$tables['lessons'], $updater, array('id' => $lessons[$i]));
      }

      echo json_encode(['status' => true, 'message' => esc_html__("Lesson Sorted Successfully", BaseController::$text_domain)]);
    }
  }

  // GET LESSON BY COURSE ID
  public static function get_lesson_by_course_id($course_id)
  {
    global $wpdb;
    $table = self::$tables['lessons'];
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `course_id` = %d", $course_id));
    return $result;
  }


  // GET LESSON BY COURSE ID
  public static function get_all_lessons()
  {
    $result = array();
    global $wpdb;
    $table = self::$tables['lessons'];
    if (Helper::get_current_user_role() == "administrator") {
      $result = $wpdb->get_results("SELECT * FROM $table");
    } elseif (Helper::get_current_user_role() == "instructor") {
      $instructor_courses = Course::get_instructor_courses_by_instructor_id(Helper::get_current_user_id());
      $instructor_course_ids = array();
      foreach ($instructor_courses as $instructor_course) {
        if (!in_array($instructor_course->id, $instructor_course_ids)) {
          array_push($instructor_course_ids, esc_sql($instructor_course->id));
        }
      }

      if (count($instructor_course_ids)) {
        $instructor_course_ids = implode(", ", esc_sql($instructor_course_ids));
        global $wpdb;
        $table = self::$tables['lessons'];
        $result = $wpdb->get_results("SELECT * FROM $table WHERE `course_id` IN ($instructor_course_ids)");
      }
    }

    return $result;
  }

  // CHECKS IF THE LESSON DOES BELONG TO CURRENT USER
  public static function lesson_authentication($lesson_id)
  {
    global $wpdb;
    $table = self::$tables['lessons'];
    $lesson_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $lesson_id));
    if (sizeof($lesson_details)) {
      $lesson_details = $lesson_details[0];
      $course_id = $lesson_details->course_id;

      $course_table = self::$tables['courses'];
      $course_details = $wpdb->get_results($wpdb->prepare("SELECT * FROM $course_table WHERE `id` = %d", $course_id));

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
