<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Settings;


$currencies = Settings::get_all_currencies();
$system_payment_info = json_decode(Helper::get_payment_settings('system'));

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-payment-settings-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_payment_settings">
    <input type="hidden" name="update_payment_settings_nonce" value="<?php echo wp_create_nonce('update_payment_settings_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="type" value="system">

    <div class="mxlms-form-group">
        <label for="system_currency"><?php esc_html_e("System Currency", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="system_currency" class="mxlms-form-control mxlms-wise" id="system_currency">
            <?php foreach ($currencies as $key => $currency) : ?>
                <option value="<?php echo esc_attr($currency->code); ?>" <?php if ($system_payment_info->system_currency == $currency->code) echo "selected"; ?>><?php echo esc_html($currency->code); ?> - <?php echo esc_html($currency->symbol); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="currency_position"><?php esc_html_e("Currency Position", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="currency_position" class="mxlms-form-control mxlms-wise" id="currency_position">
            <option value="left" <?php if ($system_payment_info->currency_position == 'left') echo 'selected'; ?>><?php esc_html_e('Left', BaseController::$text_domain); ?></option>
            <option value="right" <?php if ($system_payment_info->currency_position == 'right') echo 'selected'; ?>><?php esc_html_e('Right', BaseController::$text_domain); ?></option>
            <option value="left-space" <?php if ($system_payment_info->currency_position == 'left-space') echo 'selected'; ?>><?php esc_html_e('Left Space', BaseController::$text_domain); ?></option>
            <option value="right-space" <?php if ($system_payment_info->currency_position == 'right-space') echo 'selected'; ?>><?php esc_html_e('Right Space', BaseController::$text_domain); ?></option>
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
        jQuery('.update-payment-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var system_currency = jQuery('#system_currency').val();
        var currency_position = jQuery('#currency_position').val();

        if (system_currency === '' || currency_position === '') {
            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-payment-settings-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>