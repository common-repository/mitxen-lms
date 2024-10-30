<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Category;
use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

$category_details = Category::get_category_details_by_id(\Mxlms\base\AjaxPosts::$param1);
$categories = \Mxlms\base\modules\Category::get_parent_categories();
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form category-edit-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_category'; ?>">
    <input type="hidden" name="task" value="edit_category">
    <input type="hidden" name="edit_category_nonce" value="<?php echo wp_create_nonce('edit_category_nonce'); ?>">
    <!-- kind of csrf token-->

    <?php foreach ($category_details as $category_detail) : ?>

        <input type="hidden" name="id" value="<?php echo esc_attr($category_detail->id); ?>">

        <div class="mxlms-form-group">
            <label for="title"><?php esc_html_e('Category Name', BaseController::$text_domain); ?> <span class='mxlms-text-danger'>*</span> </label>
            <input type="text" name="title" class="form-control" id="title" aria-describedby="title" placeholder="<?php esc_html_e('Category Title', BaseController::$text_domain); ?>" value="<?php echo esc_attr($category_detail->title); ?>">
        </div>

        <div class="mxlms-form-group">
            <label for="parent_category_id"><?php esc_html_e('Parent category', BaseController::$text_domain); ?></label>
            <select name="parent_category_id" class="mxlms-form-control mxlms-wide mxlms-w-100" id="parent_category_id">
                <option value='0'><?php esc_html_e("None", BaseController::$text_domain) ?></option>
                <?php foreach ($categories as $key => $category) : ?>
                    <option value="<?php echo esc_attr($category->id); ?>" <?php if ($category->id == $category_detail->parent_category_id) echo "selected"; ?>><?php echo esc_html($category->title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="mxlms-parent-category-stuff" class="<?php echo $category_detail->parent_category_id ? 'mxlms-hidden' : ''; ?>">
            <div class="mxlms-form-group">
                <label for="is_featured"><?php esc_html_e("Is Featured", BaseController::$text_domain) ?></label>
                <select name="is_featured" class="mxlms-wide mxlms-w-100" id="is_featured">
                    <option value=0 <?php if (!$category_detail->is_featured) echo 'selected'; ?>><?php esc_html_e("Not Featured", BaseController::$text_domain) ?></option>
                    <option value=1 <?php if ($category_detail->is_featured) echo 'selected'; ?>><?php esc_html_e("Featured", BaseController::$text_domain) ?></option>
                </select>
            </div>

            <div class="mxlms-form-group">
                <label for="category_image_upload mxlms-w-100">
                    <?php esc_html_e("Upload category Image", BaseController::$text_domain) ?>
                    <span class="mxlms-anim"><i class="las la-question-circle"></i>
                        <span class="mxlms-popover">
                            <?php esc_html_e('The image size should be', BaseController::$text_domain); ?> 800 X 533
                        </span>
                    </span>
                </label>
                <div class="mxlms-image-uploader">
                    <i class="las la-plus"></i>
                    <img src="<?php echo esc_url_raw($category_detail->thumbnail); ?>" alt="" id="category_image_upload" height="150" width="150">
                </div>
                <input type="hidden" name="category_image_path" id="category_image_path" value="<?php echo esc_url_raw($category_detail->thumbnail); ?>">
            </div>
        </div>

        <div class="mxlms-custom-modal-action-footer">
            <div class="mxlms-custom-modal-actions">
                <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
                <button type="submit" class="mxlms-btn mxlms-btn-primary mxlms-btn-md"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
            </div>
        </div>
    <?php endforeach; ?>
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
        jQuery('.category-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var category_title = jQuery('#title').val();

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
            jQuery('.category-edit-form').trigger('reset');
            closeModal();
            mxlmsNotify(response.message, 'success');
            mxlmsMakeAjaxCall(ajaxurl, 'category/list', 'category-list-area');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }


    jQuery('.mxlms-image-uploader').on('click', function(e) {

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
            jQuery('#category_image_path').val(attachment.url);
            jQuery('#category_image_upload').attr('src', attachment.url);
            jQuery('#category_image_upload').show();
            jQuery('.mxlms-image-uploader i').hide();
        });
    });


    function toggleParentCategoryStuff(parentId) {
        if (parentId > 0) {
            jQuery("#mxlms-parent-category-stuff").hide();
        } else {
            jQuery("#mxlms-parent-category-stuff").show();
        }
    }
</script>