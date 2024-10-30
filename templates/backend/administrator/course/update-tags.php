<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

?>

<div class="mxlms-row mxlms-mr-1">
  <div class="mxlms-col-xl-8">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-tag-update-form' enctype='multipart/form-data' autocomplete="off">
      <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
      <input type="hidden" name="task" value="edit_course">
      <input type="hidden" name="edit_course_nonce" value="<?php echo wp_create_nonce('edit_course_nonce'); ?>"> <!-- kind of csrf token-->
      <input type="hidden" name="section" value="tag">
      <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

      <div class="mxlms-mb-3">
        <label for="meta_keywords"><?php esc_html_e('Meta Keyword', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
        <input type="text" class="mxlms-form-control bootstrap-tag-input" id="meta_keywords" name="meta_keywords" data-role="tagsinput" value="<?php echo esc_attr($course_details->meta_keywords); ?>" />
        <small><?php esc_html_e('Write a keyword and press enter', BaseController::$text_domain); ?></small>
      </div>
      <div class="mxlms-form-group">
        <label for="meta_description"><?php esc_html_e('Meta Description', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
        <textarea name="meta_description" class="mxlms-form-control" rows="5"><?php echo esc_textarea($course_details->meta_description); ?></textarea>
      </div>

      <div class="mxlms-form-group">
        <button type="submit" class="mxlms-btn mxlms-btn-success"><?php esc_html_e("Update Tags", BaseController::$text_domain) ?></button>
      </div>
    </form>
  </div>
</div>

<script>
  "use strict";

  initNiceSelect();
  jQuery('.course-tag-update-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  jQuery(document).ready(function() {

    var options = {
      beforeSubmit: validate,
      success: showResponse,
      resetForm: false
    };
    jQuery('.course-tag-update-form').on('submit', function() {
      jQuery(this).ajaxSubmit(options)
      return false;
    });
  });

  function validate() {
    
    return true;
  }

  function showResponse(response) {
    response = JSON.parse(response);
    if (response.status) {
      mxlmsNotify(response.message, 'success')
    } else {
      mxlmsNotify(response.message, 'warning');
    }
  }
</script>
