<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Lesson;

defined('ABSPATH') or die('You can not access the file directly');

class Question extends BaseController
{
  // Method for registering form submission hook to this plugin
  public function register()
  {
    add_action('admin_post_' . self::$plugin_id . '_question', array($this, 'post'));
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
      case 'add_question':
        $this->add_question();
        break;
      case 'edit_question':
        $this->edit_question();
        break;
      case 'delete':
        $this->delete_question();
        break;
      case 'sort_question':
        $this->sort_question();
        break;
    }
  }

  private function add_question()
  {
    if (self::verify_nonce('add_question_nonce') == true) {
      $response = false;

      $quiz_id  = sanitize_text_field($_POST['quiz_id']);
      $question_type = sanitize_text_field($_POST['question_type']);

      if ($question_type == 'mcq') {
        $response = $this->add_multiple_choice_question($quiz_id);
      }

      if ($response) {
        echo json_encode(['status' => true, 'message' => esc_html__("Quiz question has been added successfully", BaseController::$text_domain)]);
      } else {
        echo json_encode(['status' => false, 'message' => esc_html__("An error occurred while adding quiz question", BaseController::$text_domain), 'id' => $quiz_id]);
      }
    }
  }

  private function edit_question()
  {
    if (self::verify_nonce('edit_question_nonce') == true) {
      $response = false;

      $quiz_id  = sanitize_text_field($_POST['quiz_id']);
      $question_type = sanitize_text_field($_POST['question_type']);
      $question_id = sanitize_text_field($_POST['id']);

      if ($question_type == 'mcq') {
        $response = $this->add_multiple_choice_question($quiz_id, $question_id);
      }

      if ($response) {
        echo json_encode(['status' => true, 'message' => esc_html__("Quiz question has been updated successfully", BaseController::$text_domain)]);
      } else {
        echo json_encode(['status' => false, 'message' => esc_html__("An error occurred while adding quiz question", BaseController::$text_domain), 'id' => $quiz_id]);
      }
    }
  }

  private function add_multiple_choice_question($quiz_id, $question_id = false)
  {
    if (sizeof($_POST['options']) != $_POST['number_of_options']) {
      return false;
    }
    foreach ($_POST['options'] as $option) {
      if ($option == "") {
        return false;
      }
    }
    if (sizeof($_POST['correct_answers']) == 0) {
      $correct_answers = [""];
    } else {
      $correct_answers = $_POST['correct_answers'];
    }
    $data['quiz_id']            = $quiz_id;
    $data['title']              = sanitize_text_field($_POST['title']);
    $data['number_of_options']  = sanitize_text_field($_POST['number_of_options']);
    $data['type']               = 'multiple_choice';
    $data['options']            = json_encode($_POST['options']);
    $data['correct_answers']    = json_encode($correct_answers);

    global $wpdb;
    $table = self::$tables['questions'];
    if ($question_id) {
      $wpdb->update($table, $data, array('id' => $question_id));
    } else {
      $wpdb->insert($table, $data);
    }
    return true;
  }

  public static function delete_question()
  {
    if (self::verify_nonce('confirmation_form_nonce') == true) {
      global $wpdb;
      $table = self::$tables['questions'];
      $id = sanitize_text_field($_POST['id']);
      $wpdb->delete($table, ['id' => $id]);

      echo json_encode(['status' => true, 'message' => esc_html__("Question Deleted Successfully", BaseController::$text_domain)]);
    }
  }

  public static function get_questions_by_quiz_id($quiz_id)
  {

    global $wpdb;
    $table = self::$tables['questions'];
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `quiz_id` = %d ORDER BY $table.`order` ASC", $quiz_id));
    return $result;
  }

  public static function get_question_by_id($question_id)
  {
    global $wpdb;
    $table = self::$tables['questions'];
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `id` = %d", $question_id));
    return $result[0];
  }

  public static function sort_question()
  {
    if (self::verify_nonce('sort_question_nonce') == true) {

      $table = self::$tables['questions'];
      $questions = explode(',', $_POST['question_serial']);
      for ($i = 0; $i < count($questions); $i++) {
        $updater = array(
          'order' => $i + 1
        );
        global $wpdb;
        $wpdb->update(self::$tables['questions'], $updater, array('id' => $questions[$i]));
      }

      echo json_encode(['status' => true, 'message' => esc_html__("Question Sorted Successfully", BaseController::$text_domain)]);
    }
  }
}
