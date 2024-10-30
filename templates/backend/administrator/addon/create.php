<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form addon-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_addon'; ?>">
    <input type="hidden" name="task" value="add_addon">
    <input type="hidden" name="add_addon_nonce" value="<?php echo esc_attr( wp_create_nonce('add_addon_nonce') ); ?>"> <!-- kind of csrf token-->
    <div class="mxlms-form-group mxlms-text-center">
        <img src="<?php echo esc_url(Helper::get_plugin_url('assets/backend/img/file-upload.png')); ?>" alt="" id="mxlms-addon-upload" class="mxlms-addon-upload" height="150" width="150">
        <div class="mxlms-h3" id="mxlms-info-msg">
            <label for="mxlms-addon-upload" class="mxlms-addon-upload"><?php esc_html_e("Choose an addon zip from below", BaseController::$text_domain) ?></label>
        </div>
        <div class="mxlms-h3 mxlms-hidden mxlms-text-secondary" id="mxlms-success-msg">
            <label for="mxlms-addon-upload mxlms-hidden">
                <i class="lar la-file-archive"></i>
                <span id="addon_path_to_show"></span>
            </label>
        </div>
        <input type="hidden" name="addon_path" id="addon_path">
    </div>
    <div class="mxlms-custom-modal-action-footer">
        <div class="mxlms-custom-modal-actions">
            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
            <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-md mxlms-addon-upload" id="mxlms-choose-addon-btn"><?php esc_html_e("Choose Addon Zip", BaseController::$text_domain) ?></button>
            <button type="submit" class="mxlms-btn mxlms-btn-success mxlms-btn-md mxlms-hidden" id="mxlms-install-addon-btn"><?php esc_html_e("Install Addon", BaseController::$text_domain) ?></button>
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

        jQuery('.addon-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var addon_path = jQuery('#addon_path').val();

        if (addon_path === '') {

            mxlmsNotify("<?php esc_html_e('Upload an addon first', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.addon-add-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
            mxlmsMakeAjaxCall(ajaxurl, 'addon/list', 'addon-list-area');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }

    jQuery('.mxlms-addon-upload').on('click', function(e) {

        var mediaUploader;
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: "<?php esc_html_e('Choose Addon Zip File', BaseController::$text_domain) ?>",
            button: {
                text: "<?php esc_html_e('Upload Addon Zip', BaseController::$text_domain) ?>"
            },
            multiple: false

        });
        mediaUploader.open();

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            if (attachment.id) {
                jQuery('#addon_path').val(attachment.id);
                jQuery('#addon_path_to_show').text(getFilename(attachment.url));
                jQuery('#mxlms-addon-upload').attr('src', '<?php echo Helper::get_plugin_url('assets/backend/img/file-upload-success.png'); ?>');

                jQuery('#mxlms-info-msg').hide();
                jQuery('#mxlms-success-msg').show();
                jQuery('#addon_path_to_show').show();

                jQuery('#mxlms-install-addon-btn').show();
                jQuery('#mxlms-choose-addon-btn').hide();
            } else {
                jQuery('#addon_path').val('');
                jQuery('#mxlms-addon-upload').attr('src', '<?php echo Helper::get_plugin_url('assets/backend/img/file-upload.png'); ?>');

                jQuery('#mxlms-info-msg').show();
                jQuery('#mxlms-success-msg').hide();
                jQuery('#addon_path_to_show').hide();

                jQuery('#mxlms-install-addon-btn').hide();
                jQuery('#mxlms-choose-addon-btn').show();
            }
        });
    });
</script>