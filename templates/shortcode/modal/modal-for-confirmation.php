<?php
defined('ABSPATH') or die('You can not access the file directly');



$id                     = Mxlms\base\AjaxPosts::$param1;
$submission_hook        = Mxlms\base\AjaxPosts::$param2;
$section_to_be_displayed = Mxlms\base\AjaxPosts::$param3;
$section_container      = Mxlms\base\AjaxPosts::$param4;
$parameter_to_return_1  = Mxlms\base\AjaxPosts::$param5;
$parameter_to_return_2  = Mxlms\base\AjaxPosts::$param6;
$parameter_to_return_3  = Mxlms\base\AjaxPosts::$param7;
?>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form confirmation-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(\Mxlms\base\BaseController::$plugin_id . '_' . $submission_hook); ?>">
    <input type="hidden" name="task" value="delete">
    <input type="hidden" name="confirmation_form_nonce" value="<?php echo wp_create_nonce('confirmation_form_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">

    <label for="warning" class="mxlms-confirmation-modal-label"><?php esc_html_e('Are you sure, You want to do this?', \Mxlms\base\BaseController::$text_domain) ?></label>
    <div class="mxlms-row mxlms-justify-content-center mxlms-m-3">
        <div class="mxlms-col-lg-4 mxlms-col-6">
            <button type="submit" class="mxlms-btn mxlms-btn-danger mxlms-btn-block" onclick="closeModal(jQuery(this).closest('.mxlms-modal').attr('id'))"><?php esc_html_e("Yes", \Mxlms\base\BaseController::$text_domain); ?></button>
        </div>

        <div class="mxlms-col-lg-4 mxlms-col-6">
            <a href="#" class="mxlms-btn mxlms-btn-secondary mxlms-btn-block" onclick="closeModal(jQuery(this).closest('.mxlms-modal').attr('id'))"><?php esc_html_e("Cancel", \Mxlms\base\BaseController::$text_domain); ?></a>
        </div>
    </div>
</form>

<script>
    "use strict";

    jQuery(document).ready(function($) {
        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: true
        };
        jQuery('.confirmation-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {

    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            closeModal();
            mxlmsNotify(response.message, 'success');
            mxlmsMakeAjaxCall(ajaxurl, '<?php echo esc_js($section_to_be_displayed); ?>', '<?php echo esc_js($section_container); ?>', '<?php echo esc_js($parameter_to_return_1); ?>', '<?php echo esc_js($parameter_to_return_2); ?>', '<?php echo esc_js($parameter_to_return_3); ?>');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>