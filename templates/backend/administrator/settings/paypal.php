<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Settings;


$currencies = Settings::get_all_currencies();
$paypal_payment_info = json_decode(Helper::get_payment_settings('paypal'));

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-payment-settings-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_payment_settings">
    <input type="hidden" name="update_payment_settings_nonce" value="<?php echo wp_create_nonce('update_payment_settings_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="type" value="paypal">

    <div class="mxlms-form-group">
        <label for="active"><?php esc_html_e("Paypal Active", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="active" class="mxlms-form-control mxlms-wise" id="active">
            <option value="1" <?php if ($paypal_payment_info->active == "1") echo "selected"; ?>><?php esc_html_e('Yes', BaseController::$text_domain); ?></option>
            <option value="0" <?php if ($paypal_payment_info->active == "0") echo "selected"; ?>><?php esc_html_e('No', BaseController::$text_domain); ?></option>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="mode"><?php esc_html_e("Paypal Mode", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="mode" class="mxlms-form-control mxlms-wise" id="mode">
            <option value="sandbox" <?php if ($paypal_payment_info->mode == "sandbox") echo "selected"; ?>><?php esc_html_e('Sandbox', BaseController::$text_domain); ?></option>
            <option value="production" <?php if ($paypal_payment_info->mode == "production") echo "selected"; ?>><?php esc_html_e('Production', BaseController::$text_domain); ?></option>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="currency"><?php esc_html_e("Paypal Currency", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="currency" class="mxlms-form-control mxlms-wise" id="currency">
            <?php foreach ($currencies as $key => $currency) : ?>
                <option value="<?php echo esc_attr($currency->code); ?>" <?php if ($paypal_payment_info->currency == $currency->code) echo "selected"; ?>><?php echo esc_html($currency->code); ?> - <?php echo esc_html($currency->symbol); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="sandbox_client_id"><?php esc_html_e('Sandbox Client Id', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="sandbox_client_id" class="mxlms-form-control" id="sandbox_client_id" aria-describedby="sandbox_client_id" value="<?php echo esc_attr($paypal_payment_info->sandbox_client_id); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="sandbox_secret_key"><?php esc_html_e('Sandbox Secret Key', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="sandbox_secret_key" class="mxlms-form-control" id="sandbox_secret_key" aria-describedby="sandbox_secret_key" value="<?php echo esc_attr($paypal_payment_info->sandbox_secret_key); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="production_client_id"><?php esc_html_e('Production Client Id', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="production_client_id" class="mxlms-form-control" id="production_client_id" aria-describedby="production_client_id" value="<?php echo esc_attr($paypal_payment_info->production_client_id); ?>">
    </div>

    <div class="mxlms-form-group">
        <label for="production_secret_key"><?php esc_html_e('Production Secret Key', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="production_secret_key" class="mxlms-form-control" id="production_secret_key" aria-describedby="production_secret_key"  value="<?php echo esc_attr($paypal_payment_info->production_secret_key); ?>">
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