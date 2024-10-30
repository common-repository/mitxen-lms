<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Settings;
use Mxlms\base\modules\Helper;

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-general-settings-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_general_settings">
    <input type="hidden" name="update_general_settings_nonce" value="<?php echo wp_create_nonce('update_general_settings_nonce'); ?>"> <!-- kind of csrf token-->

    <div class="mxlms-form-group">
        <label for="system_name"><?php esc_html_e('Business Name', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="system_name" class="mxlms-form-control" id="system_name" aria-describedby="system_name" value="<?php echo esc_attr(Helper::get_general_settings('system_name')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="system_email"><?php esc_html_e('Business Email', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="email" name="system_email" class="mxlms-form-control" id="system_email" aria-describedby="system_email" placeholder="mitxen@example.com" value="<?php echo esc_attr(Helper::get_general_settings('system_email')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="address"><?php esc_html_e('Address', BaseController::$text_domain); ?></label>
        <textarea name="address" id="adress" rows="5" class="mxlms-form-control"><?php echo esc_textarea(Helper::get_general_settings('address')); ?></textarea>
    </div>

    <div class="mxlms-form-group">
        <label for="phone"><?php esc_html_e('Phone Number', BaseController::$text_domain); ?></label>
        <input type="text" name="phone" class="mxlms-form-control" id="phone" aria-describedby="phone" placeholder="+990123123123" value="<?php echo esc_attr(Helper::get_general_settings('phone')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="purchase_code"><?php esc_html_e('Purchase code', BaseController::$text_domain); ?></label>
        <input type="text" name="purchase_code" class="mxlms-form-control" id="purchase_code" aria-describedby="purchase_code" placeholder="<?php esc_html_e('Your Purchase Code', BaseController::$text_domain); ?>" value="<?php echo esc_attr(Helper::get_general_settings('purchase_code')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="youtube_api_key"><?php esc_html_e('YouTube API Key', BaseController::$text_domain); ?></label>
        <input type="text" name="youtube_api_key" class="mxlms-form-control" id="youtube_api_key" aria-describedby="youtube_api_key" placeholder="<?php esc_html_e('Provide YouTube API Key', BaseController::$text_domain); ?>" value="<?php echo esc_attr(Helper::get_general_settings('youtube_api_key')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="vimeo_api_key"><?php esc_html_e('Vimeo API Key', BaseController::$text_domain); ?></label>
        <input type="text" name="vimeo_api_key" class="mxlms-form-control" id="vimeo_api_key" aria-describedby="vimeo_api_key" placeholder="<?php esc_html_e('Provide Vimeo API Key', BaseController::$text_domain); ?>" value="<?php echo esc_attr(Helper::get_general_settings('vimeo_api_key')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="logo_lg_upload mxlms-w-100">
            <?php esc_html_e("Upload Large Logo", BaseController::$text_domain) ?>
            <span class="mxlms-anim"><i class="las la-question-circle"></i>
                <span class="mxlms-popover">
                    <?php esc_html_e('The image size should be ', BaseController::$text_domain); ?>
                </span>
            </span>
        </label>
        <div class="mxlms-image-uploader" id="logo_lg">
            <img src="<?php echo esc_url(Helper::get_general_settings('logo_lg_path')); ?>" alt="" id="logo_lg_upload" height="150" width="150">
        </div>
        <input type="hidden" name="logo_lg_path" id="logo_lg_path" value="<?php echo esc_url(Helper::get_general_settings('logo_lg_path')); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="logo_sm_upload mxlms-w-100">
            <?php esc_html_e("Upload Small Logo", BaseController::$text_domain) ?>
            <span class="mxlms-anim"><i class="las la-question-circle"></i>
                <span class="mxlms-popover">
                    <?php esc_html_e('The image size should be ', BaseController::$text_domain); ?>
                </span>
            </span>
        </label>
        <div class="mxlms-image-uploader" id="logo_sm">
            <img src="<?php echo esc_url(Helper::get_general_settings('logo_sm_path')); ?>" alt="" id="logo_sm_upload" height="150" width="150">
        </div>
        <input type="hidden" name="logo_sm_path" id="logo_sm_path" value="<?php echo esc_url(Helper::get_general_settings('logo_sm_path')); ?>">
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

    jQuery('.update-general-settings-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    jQuery(document).ready(function() {

        jQuery("#meta_keywords").tagsinput();
        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-general-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        initNiceSelect();
    });

    function validate() {
        var system_name = jQuery('#system_name').val();
        var system_email = jQuery('#system_email').val();

        if (system_name === '' || system_email === '') {
            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-general-settings-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }

    jQuery('.mxlms-image-uploader').on('click', function(e) {

        var logoType = this.id;

        var mediaUploader;
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: "<?php esc_html_e('Choose File', BaseController::$text_domain) ?>",
            button: {
                text: "<?php esc_html_e('Choose File', BaseController::$text_domain) ?>"
            },
            multiple: false

        });
        mediaUploader.open();

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            jQuery('#' + logoType + '_path').val(attachment.url);
            jQuery('#' + logoType + '_upload').attr('src', attachment.url);
            jQuery('#' + logoType + '_upload').show();
            jQuery('.mxlms-image-uploader i').hide();
        });
    });
</script>