<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Language;


$language_details = Language::get_language_by_id(\Mxlms\base\AjaxPosts::$param1);
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form language-edit-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_language'; ?>">
    <input type="hidden" name="task" value="edit_language">
    <input type="hidden" name="edit_language_nonce" value="<?php echo wp_create_nonce('edit_language_nonce'); ?>"> <!-- kind of csrf token-->

    <?php foreach ($language_details as $language_detail) : ?>
        <input type="hidden" name="id" value="<?php echo esc_attr($language_detail->id) ?>">
        <div class="mxlms-form-group">
            <label for="name"><?php esc_html_e('Language Name', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
            <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="English" value="<?php echo esc_attr($language_detail->name); ?>">
        </div>

        <div class="mxlms-form-group">
            <label for="code"><?php esc_html_e('Language code', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
            <input type="text" name="code" class="form-control" id="code" aria-describedby="code" placeholder="Example: en" value="<?php echo esc_attr($language_detail->code); ?>">
            <small class="mxlms-text-danger">N.B: <?php esc_html_e('It has to be 2 characters and unique', BaseController::$text_domain); ?></small>
        </div>
    <?php endforeach; ?>

    <div class="mxlms-custom-modal-action-footer">
        <div class="mxlms-custom-modal-actions">
            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" onclick="present_right_modal( 'language/list', '<?php esc_html_e('Available Language', BaseController::$text_domain); ?>');"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
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
        jQuery('.language-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var name = jQuery('#name').val();
        var code = jQuery('#code').val();

        if (name === '' || code === '') {

            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.language-add-form').trigger('reset');
            mxlmsNotify(response.message, 'success');
            present_right_modal('language/list', '<?php esc_html_e('Available Languages', BaseController::$text_domain); ?>');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>
