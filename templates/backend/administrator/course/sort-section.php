<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\modules\Section;
use \Mxlms\base\BaseController;

$course_id   = \Mxlms\base\AjaxPosts::$param1;
$sections = Section::get_authenticated_sections($course_id);

?>

<ul id="mxlms-sortable">
  <?php foreach ($sections as $key => $section) : ?>
    <li class="mxlms-box mxlms-draggable-item" id="<?php echo esc_attr($section->id); ?>">
      <div class="mxlms-panel mxlms-sortable-panel-style">
        <div class="mxlms-panel-title">
          <i class="las la-bars"></i> <?php echo esc_html($section->title); ?>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
</ul>

<div class="mxlms-row mxlms-justify-content-center mxlms-mb-2">
  <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form section-sort-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo BaseController::$plugin_id . '_section'; ?>">
    <input type="hidden" name="task" value="sort_section">
    <input type="hidden" name="sort_section_nonce" value="<?php echo wp_create_nonce('sort_section_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="section_serial" id="section-serial" value=''>

    <div class="mxlms-custom-modal-action-footer">
      <div class="mxlms-custom-modal-actions">
        <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
        <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-md" id="update-sort-btn"><?php esc_html_e("Update Sort", BaseController::$text_domain); ?></button>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  'use strict';

  jQuery(document).ready(function() {

    var options = {
      beforeSubmit: validate,
      success: showResponse,
      resetForm: false
    };
    jQuery('.section-sort-form').on('submit', function() {
      jQuery(this).ajaxSubmit(options);
      return false;
    });

    jQuery("#update-sort-btn").on('click', updateSort);
  });

  function validate() {
    var section_serial = jQuery('#section-serial').val();

    if (section_serial === '') {

      mxlmsNotify("<?php esc_html_e('Update section failed', BaseController::$text_domain) ?>", 'warning');
      return false;
    }
    return true;
  }

  function showResponse(response) {
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    response = JSON.parse(response);
    if (response.status) {
      jQuery('.section-sort-form').trigger('reset');
      closeModal();
      mxlmsNotify(response.message, 'success');
      mxlmsMakeAjaxCall(ajaxurl, 'course/update-curriculum', 'curriculum-area', '<?php echo esc_js($course_id); ?>');
    } else {
      mxlmsNotify(response.message, 'warning');
    }
  }

  jQuery(function() {
    jQuery("#mxlms-sortable").sortable();
    jQuery("#mxlms-sortable").disableSelection();
  });


  function updateSort() {
    var containerArray = ['mxlms-sortable'];
    var itemArray = [];
    var itemJSON;
    for (var i = 0; i < containerArray.length; i++) {
      jQuery('#' + containerArray[i]).each(function() {
        jQuery(this).find('.mxlms-draggable-item').each(function() {
          itemArray.push(this.id);
        });
      });
    }
    itemJSON = JSON.stringify(itemArray);
    jQuery("#section-serial").val(itemArray);

    // SUBMITTING THE FORM
    jQuery('.section-sort-form').submit();
  }
</script>