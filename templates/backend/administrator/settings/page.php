<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-page-settings-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_page_settings">
    <input type="hidden" name="update_page_settings_nonce" value="<?php echo wp_create_nonce('update_page_settings_nonce'); ?>"> <!-- kind of csrf token-->

    <div class="mxlms-form-group">
        <label for="public-page"><?php esc_html_e("Select Public Page", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="public_page" class="mxlms-form-control mxlms-wide" id="public-page">
            <option value=""><?php echo esc_html_e('Choose a page'); ?></option>
            <?php
            $pages = get_pages();
            foreach ($pages as $page) : ?>
                <option value="<?php echo esc_attr($page->ID); ?>" <?php if ($page->ID == Helper::get_page_settings('public_page')) echo "selected"; ?>>
                    <?php echo $page->post_title; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="private-page"><?php esc_html_e("Select Private Page", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="private_page" class="mxlms-form-control mxlms-wide" id="private-page">
            <option value=""><?php echo esc_html_e('Choose a page'); ?></option>
            <?php
            $pages = get_pages();
            foreach ($pages as $page) : ?>
                <option value="<?php echo esc_attr($page->ID); ?>" <?php if ($page->ID == Helper::get_page_settings('private_page')) echo "selected"; ?>>
                    <?php echo $page->post_title; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mxlms-form-group">
        <label for="live-class-page"><?php esc_html_e("Select Live Class Page", BaseController::$text_domain) ?> <span class='mxlms-text-danger'>*</span></label>
        <select name="live_class_page" class="mxlms-form-control mxlms-wide" id="live-class-page">
            <option value=""><?php echo esc_html_e('Choose a page'); ?></option>
            <?php
            $pages = get_pages();
            foreach ($pages as $page) : ?>
                <option value="<?php echo esc_attr($page->ID); ?>" <?php if ($page->ID == Helper::get_page_settings('live_class_page')) echo "selected"; ?>>
                    <?php echo $page->post_title; ?>
                </option>
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

    jQuery(document).ready(function() {

        initNiceSelect();

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-page-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var publicPage = jQuery('#public-page').val();
        var privatePage = jQuery('#private-page').val();

        if (publicPage === '' || privatePage === '') {
            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-page-settings-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>
