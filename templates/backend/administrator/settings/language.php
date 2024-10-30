<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Language;


$languages = Language::get_all_languages();
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-language-settings-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_language_settings">
    <input type="hidden" name="update_language_settings_nonce" value="<?php echo wp_create_nonce('update_language_settings_nonce'); ?>"> <!-- kind of csrf token-->

    <div class="mxlms-form-group">
        <label for="system_language"><?php esc_html_e("System Language", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="system_language" class="mxlms-form-control mxlms-wider" id="system_language">
            <?php foreach ($languages as $key => $language) : ?>
                <option value="<?php echo esc_attr($language->code); ?>" <?php if (Helper::get_general_settings('system_language') == $language->code) echo "selected"; ?>><?php echo esc_html($language->name); ?></option>
            <?php endforeach; ?>
        </select>
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

    initNiceSelect();
    jQuery(document).ready(function() {

        jQuery("#meta_keywords").tagsinput();
        var instructor_revenue_percentage = jQuery('#instructor_revenue_percentage').val();
        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-language-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var system_language = jQuery('#system_language').val();

        if (system_language === '') {
            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-language-settings-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>