<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;


$course_id = \Mxlms\base\AjaxPosts::$param1; ?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form section-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_section'; ?>">
    <input type="hidden" name="task" value="add_section">
    <input type="hidden" name="add_section_nonce" value="<?php echo wp_create_nonce('add_section_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

    <div class="mxlms-form-group">
        <label for="section-title"><?php esc_html_e('Section title', BaseController::$text_domain); ?></label>
        <input type="text" name="title" class="form-control" id="section-title" aria-describedby="title" placeholder="<?php esc_html_e('Section Title', BaseController::$text_domain); ?>">
    </div>
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

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.section-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var section_title = jQuery('#section-title').val();

        if (section_title == '') {

            mxlmsNotify("<?php esc_html_e('Section title can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.section-add-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
            mxlmsMakeAjaxCall(ajaxurl, 'course/update-curriculum', 'curriculum-area', '<?php echo esc_js($course_id); ?>');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>