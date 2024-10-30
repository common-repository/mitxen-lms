<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form language-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_language'; ?>">
    <input type="hidden" name="task" value="add_language">
    <input type="hidden" name="add_language_nonce" value="<?php echo wp_create_nonce('add_language_nonce'); ?>"> <!-- kind of csrf token-->

    <div class="mxlms-form-group">
        <label for="name"><?php esc_html_e('Language Name', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="name" class="form-control" id="name" aria-describedby="name" placeholder="English">
    </div>

    <div class="mxlms-form-group">
        <label for="code"><?php esc_html_e('Language code', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="code" class="form-control" id="code" aria-describedby="code" placeholder="Example: en">
        <small class="mxlms-text-danger">N.B: <?php esc_html_e('It has to be 2 characters and unique', BaseController::$text_domain); ?></small>
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
        jQuery('.language-add-form').on('submit', function() {
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
