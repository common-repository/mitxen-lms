<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form category-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_category'; ?>">
    <input type="hidden" name="task" value="add_category">
    <input type="hidden" name="add_category_nonce" value="<?php echo wp_create_nonce('add_category_nonce'); ?>"> <!-- kind of csrf token-->

    <div class="mxlms-form-group">
        <label for="title"><?php esc_html_e('Category Name', BaseController::$text_domain); ?><span class='mxlms-text-danger'>*</span></label>
        <input type="text" name="title" class="form-control" id="category-title" aria-describedby="category-title" placeholder="<?php esc_html_e('Category Title', BaseController::$text_domain); ?>">
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
    // Nice select initializer
    initNiceSelect();

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.category-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var category_title = jQuery('#category-title').val();

        if (category_title === '') {

            mxlmsNotify("<?php esc_html_e('You must enter name', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.category-add-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
            getCategories(response.id);
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>