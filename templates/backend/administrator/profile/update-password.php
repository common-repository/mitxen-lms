<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

use Mxlms\base\modules\User;

$profile_data = User::get_logged_in_user_details();
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-password-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_profile'; ?>">
    <input type="hidden" name="task" value="update_password">
    <input type="hidden" name="update_password_nonce" value="<?php echo wp_create_nonce('update_password_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="id" value="<?php echo esc_attr($profile_data->id); ?>">

    <div class="mxlms-form-group">
        <label for="current_password"><?php esc_html_e("Current Password", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
        <input type="password" class="mxlms-form-control" id="current_password" name="current_password" aria-describedby="current_password" placeholder="<?php esc_html_e("Enter Current Password", BaseController::$text_domain) ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="new_password"><?php esc_html_e("New Password", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
        <input type="password" class="mxlms-form-control" id="new_password" name="new_password" aria-describedby="new_password" placeholder="<?php esc_html_e("Enter New Password", BaseController::$text_domain) ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="confirm_password"><?php esc_html_e("Confirm Password", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
        <input type="password" class="mxlms-form-control" id="confirm_password" name="confirm_password" aria-describedby="confirm_password" placeholder="<?php esc_html_e("Confirm Password", BaseController::$text_domain) ?>">
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

    jQuery(document).ready(function($) {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: true
        };
        jQuery('.update-password-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var current_password = jQuery('#current_password').val();
        var new_password = jQuery('#new_password').val();
        var confirm_password = jQuery('#confirm_password').val();
        if (current_password === '' || new_password === '' || confirm_password === '') {
            mxlmsNotify("<?php esc_html_e('Required field can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        if (new_password !== confirm_password) {
            mxlmsNotify("<?php esc_html_e('Password Mismatched', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-profile-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>
