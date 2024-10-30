<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

?>

<div class="mxlms-row mxlms-mr-1">
  <div class="mxlms-col-xl-8">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-outcomes-update-form' enctype='multipart/form-data' autocomplete="off">
      <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
      <input type="hidden" name="task" value="edit_course">
      <input type="hidden" name="edit_course_nonce" value="<?php echo wp_create_nonce('edit_course_nonce'); ?>"> <!-- kind of csrf token-->
      <input type="hidden" name="section" value="outcomes">
      <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

      <div id="outcome_area">
        <?php if (!empty($course_details->outcomes) && count(json_decode($course_details->outcomes)) > 0) : ?>
          <?php foreach (json_decode($course_details->outcomes) as $key => $outcome) : ?>
            <?php if ($key == 0) : ?>
              <div class="mxlms-d-flex">
                <div class="mxlms-flex-grow-1 mxlms-pr-2">
                  <div class="mxlms-form-group mxlms-mr-30">
                    <input type="text" class="mxlms-form-control" name="outcomes[]" placeholder="<?php esc_html_e("Provide Outcomes", BaseController::$text_domain); ?>" value="<?php echo esc_attr($outcome); ?>">
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="mxlms-d-flex">
                <div class="mxlms-flex-grow-1 mxlms-pr-2">
                  <div class="mxlms-form-group">
                    <input type="text" class="mxlms-form-control" name="outcomes[]" placeholder="<?php esc_html_e("Provide Outcomes", BaseController::$text_domain); ?>" value="<?php echo esc_attr($outcome); ?>">
                  </div>
                </div>
                <div class="">
                  <button type="button" class="mxlms-btn mxlms-btn-danger mxlms-btn-sm mxlms-mt-0" name="button" onclick="removeOutcome(this)"> <i class="las la-times"></i> </button>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="mxlms-d-flex">
            <div class="mxlms-flex-grow-1 mxlms-pr-2">
              <div class="mxlms-form-group mxlms-mr-30">
                <input type="text" class="mxlms-form-control" name="outcomes[]" placeholder="<?php esc_html_e("Provide Outcomes", BaseController::$text_domain); ?>">
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div id="blank_outcome_field">
          <div class="mxlms-d-flex">
            <div class="mxlms-flex-grow-1 mxlms-pr-2">
              <div class="mxlms-form-group">
                <input type="text" class="mxlms-form-control" name="outcomes[]" placeholder="<?php esc_html_e("Provide Outcomes", BaseController::$text_domain); ?>">
              </div>
            </div>
            <div class="">
              <button type="button" class="mxlms-btn mxlms-btn-danger mxlms-btn-sm mxlms-mt-0" name="button" onclick="removeOutcome(this)"> <i class="las la-times"></i> </button>
            </div>
          </div>
        </div>
      </div>

      <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-sm mxlms-float-right" name="button" onclick="appendOutcome()"> <i class="las la-plus"></i> <?php esc_html_e("Add New outcome", BaseController::$text_domain); ?> </button>

      <div class="mxlms-form-group">
        <button type="submit" class="mxlms-btn mxlms-btn-success"><?php esc_html_e("Update Outcomes", BaseController::$text_domain); ?></button>
      </div>
    </form>
  </div>
</div>

<script>
  "use strict";

  var blank_outcome = jQuery('#blank_outcome_field').html();
  var blank_outcome = jQuery('#blank_outcome_field').html();

  jQuery(document).ready(function() {

    jQuery('#blank_outcome_field').hide();
    jQuery('#blank_outcome_field').hide();

    var options = {
      beforeSubmit: validate,
      success: showResponse,
      resetForm: false
    };
    jQuery('.course-outcomes-update-form').on('submit', function() {
      jQuery(this).ajaxSubmit(options)
      return false;
    });
  });

  function validate() {
    var description = jQuery('#description').val();
    var short_description = jQuery('#short_description').val();

    if (description === '' || short_description === '') {
      mxlmsNotify("<?php esc_html_e('Description, Short Description can not be empty', BaseController::$text_domain) ?>", 'warning');
      return false;
    }
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

  function appendOutcome() {
    jQuery('#outcomes_area').append(blank_outcome);
  }

  function removeOutcome(outcomeElem) {
    jQuery(outcomeElem).parent().parent().remove();
  }

  function appendOutcome() {
    jQuery('#outcome_area').append(blank_outcome);
  }

  function removeOutcome(outcomeElem) {
    jQuery(outcomeElem).parent().parent().remove();
  }
</script>
