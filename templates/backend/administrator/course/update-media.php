<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;
?>

<div class="mxlms-row mxlms-mr-1">
    <div class="mxlms-col-xl-8">
        <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-media-update-form' enctype='multipart/form-data' autocomplete="off">
            <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
            <input type="hidden" name="task" value="edit_course">
            <input type="hidden" name="edit_course_nonce" value="<?php echo wp_create_nonce('edit_course_nonce'); ?>"> <!-- kind of csrf token-->
            <input type="hidden" name="section" value="media">
            <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

            <div class="mxlms-form-group">
                <label for="preview_video_provider"><?php esc_html_e("Course Preview Video Provider", BaseController::$text_domain) ?></label>
                <select name="preview_video_provider" class="form-control mxlms-wide" id="preview_video_provider">
                    <option value="youtube" <?php if ($course_details->preview_video_provider == "youtube") echo "selected"; ?>><?php esc_html_e("YouTube", BaseController::$text_domain) ?></option>
                    <option value="vimeo" <?php if ($course_details->preview_video_provider == "vimeo") echo "selected"; ?>><?php esc_html_e("Vimeo", BaseController::$text_domain) ?></option>
                    <option value="html5" <?php if ($course_details->preview_video_provider == "html5") echo "selected"; ?>><?php esc_html_e("HTML5", BaseController::$text_domain) ?></option>
                </select>
            </div>

            <div class="mxlms-form-group">
                <label for="preview_video_url"><?php esc_html_e('Course Preview Video URL', BaseController::$text_domain); ?></label>
                <input type="text" class="mxlms-form-control" name="preview_video_url" value="<?php echo esc_url($course_details->preview_video_url); ?>">
            </div>

            <div class="mxlms-form-group">
                <label for="course_thumbnail_upload">
                    <?php esc_html_e("Upload Course Thumbnail", BaseController::$text_domain) ?>
                    <span class="mxlms-anim"><i class="las la-question-circle"></i>
                        <span class="mxlms-popover">
                            <?php esc_html_e('Course thumbnail should be 540 X 300', BaseController::$text_domain); ?>
                        </span>
                    </span>
                </label>
                <div class="mxlms-image-uploader" id="course_thumbnail">
                    <i class="las la-plus mxlms-hidden"></i>
                    <img src="<?php echo Helper::get_image(esc_url($course_details->thumbnail)); ?>" alt="" id="course_thumbnail_upload" height="150" width="150">
                </div>
                <input type="hidden" name="course_thumbnail_path" id="course_thumbnail_path">
            </div>

            <div class="mxlms-form-group">
                <label for="course_banner_upload">
                    <?php esc_html_e("Upload Course Banner", BaseController::$text_domain) ?>
                    <span class="mxlms-anim"><i class="las la-question-circle"></i>
                        <span class="mxlms-popover">
                            <?php esc_html_e('Course banner should be 1000 X 300', BaseController::$text_domain); ?>
                        </span>
                    </span>
                </label>
                <div class="mxlms-image-uploader" id="course_banner">
                    <i class="las la-plus mxlms-hidden"></i>
                    <img src="<?php echo Helper::get_image(esc_url($course_details->banner)); ?>" alt="" id="course_banner_upload" height="150" width="150">
                </div>
                <input type="hidden" name="course_banner_path" id="course_banner_path">
            </div>
            <div class="mxlms-form-group mxlms-mt-4">
                <button type="submit" class="mxlms-btn mxlms-btn-success"><?php esc_html_e("Update Media", BaseController::$text_domain) ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    "use strict";

    jQuery(document).ready(function() {
        initNiceSelect();

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.course-media-update-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options)
            return false;
        });
    });

    function validate() {
        var description = jQuery('#description').val();
        var short_description = jQuery('#short_description').val();

        if (description === '' || short_description === '') {
            mxlmsNotify("<?php esc_html_e('Description, Short Description can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        response = JSON.parse(response);
        if (response.status) {
            mxlmsNotify(response.message, 'success')
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }

    jQuery('.mxlms-image-uploader').on('click', function(e) {

        let imageUploaderId = this.id;

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
            jQuery('#' + imageUploaderId + '_path').val(attachment.url);
            jQuery('#' + imageUploaderId + '_upload').attr('src', attachment.url);
            jQuery('#' + imageUploaderId + '_upload').show();
            jQuery('.mxlms-image-uploader#' + imageUploaderId + ' i').hide();
        });
    });
</script>
