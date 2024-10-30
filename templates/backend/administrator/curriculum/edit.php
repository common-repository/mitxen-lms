<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Lesson;
use Mxlms\base\modules\Section;
use Mxlms\base\BaseController;


$course_id   = \Mxlms\base\AjaxPosts::$param1;
$lesson_id = \Mxlms\base\AjaxPosts::$param2;
$sections = Section::get_authenticated_sections($course_id);

$lesson_details = Lesson::get_authenticated_lesson_by_id($lesson_id);
if ($lesson_details->lesson_type == "video") {
  if (strtolower($lesson_details->video_provider) == "system") {
    $lesson_type = "video";
  } else {
    $lesson_type = strtolower($lesson_details->video_provider);
  }
} else {
  $lesson_type = $lesson_details->lesson_type;
}
?>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form lesson-edit-form' enctype='multipart/form-data' autocomplete="off">
  <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_lesson'; ?>">
  <input type="hidden" name="task" value="edit_lesson">
  <input type="hidden" name="edit_lesson_nonce" value="<?php echo wp_create_nonce('edit_lesson_nonce'); ?>"> <!-- kind of csrf token-->
  <input type="hidden" name="id" value="<?php echo esc_attr($lesson_id); ?>">
  <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

  <div class="mxlms-form-group">
    <label for="lesson-title"><?php esc_html_e('Lesson Title', BaseController::$text_domain); ?></label>
    <input type="text" name="title" class="mxlms-form-control" id="lesson-title" aria-describedby="title" placeholder="<?php esc_html_e('Lesson Title', BaseController::$text_domain); ?>" value="<?php echo esc_attr($lesson_details->title); ?>">
  </div>

  <div class="mxlms-form-group">
    <label for="lesson-section_id"><?php esc_html_e("Section Id", BaseController::$text_domain) ?></label>
    <select name="section_id" class="mxlms-form-control mxlms-w-100" id="lesson-section_id">
      <?php foreach ($sections as $key => $section) : ?>
        <option value="<?php echo esc_attr($section->id); ?>" <?php if ($section->id == $lesson_details->section_id) echo "selected"; ?>><?php echo esc_html($section->title); ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php include $lesson_type . '.php'; ?>

  <?php if ($lesson_type != "quiz") : ?>
    <div class="mxlms-form-group">
      <label for="summary"><?php esc_html_e('Lesson Summary', BaseController::$text_domain); ?></label>
      <textarea name="summary" rows="3" class="mxlms-form-control"><?php echo esc_textarea($lesson_details->summary); ?></textarea>
    </div>
  <?php endif; ?>

  <div class="mxlms-custom-modal-action-footer">
    <div class="mxlms-custom-modal-actions">
      <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
      <button type="submit" class="mxlms-btn mxlms-btn-primary mxlms-btn-md"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
    </div>
  </div>
</form>

<script>
  "use strict";

  jQuery(document).ready(function() {

    initNiceSelect();
    var options = {
      beforeSubmit: validate,
      success: showResponse,
      resetForm: false
    };
    jQuery('.lesson-edit-form').on('submit', function() {

      // SHOW THE PLACEHOLDER
      jQuery(".mxlms-custom-modal-body").hide();
      jQuery("#mxlms-right-modal .mxlms-custom-modal-content").addClass(
        "mxlms-custom-modal-body-placeholder"
      );

      jQuery(this).ajaxSubmit(options);
      return false;
    });
  });

  function validate() {
    var lesson_title = jQuery('#lesson-title').val();
    var lesson_section_id = jQuery('#lesson-section_id').val();

    if (lesson_title === '' || lesson_section_id === '') {

      mxlmsNotify("<?php esc_html_e('Title, Section can not be empty', BaseController::$text_domain) ?>", 'warning');
      return false;
    }
    return true;
  }

  function showResponse(response) {
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    response = JSON.parse(response);
    if (response.status) {
      jQuery('.lesson-edit-form').trigger('reset');
      closeModal();
      mxlmsNotify(response.message, 'success');
      mxlmsMakeAjaxCall(ajaxurl, 'course/update-curriculum', 'curriculum-area', '<?php echo esc_js($course_id); ?>');
    } else {
      mxlmsNotify(response.message, 'warning');
    }

    // HIDE THE PLACEHOLDER
    jQuery("#mxlms-right-modal .mxlms-custom-modal-content").removeClass(
      "mxlms-custom-modal-body-placeholder"
    );
    jQuery(".mxlms-custom-modal-body").show();
  }

  function backToLessonType() {
    present_modal_page('curriculum/types', '<?php esc_html_e('Types Of Lessons', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', '<?php echo esc_js($lesson_type); ?>');
  }
</script>