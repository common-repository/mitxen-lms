<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;


$course_id = \Mxlms\base\AjaxPosts::$param1;
$lesson_type = \Mxlms\base\AjaxPosts::$param2 ? \Mxlms\base\AjaxPosts::$param2 : 'youtube';
?>

<div class="mxlms-lesson-types">
    <div class="mxlms-form-group">
        <label for="title" class="mxlms-lesson-type-header"><?php esc_html_e('Lesson Types', BaseController::$text_domain); ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="youtube-vide" value="youtube" <?php if ($lesson_type == "youtube") : ?>checked<?php endif; ?>>
        <label for="youtube-vide"><?php esc_html_e("YouTube Video", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="vimeo-video" value="vimeo" <?php if ($lesson_type == "vimeo") : ?>checked<?php endif; ?>>
        <label for="vimeo-video"><?php esc_html_e("Vimeo Video", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="video-file" value="video" <?php if ($lesson_type == "video") : ?>checked<?php endif; ?>>
        <label for="video-file"><?php esc_html_e("Video File", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="s3" value="s3" <?php if ($lesson_type == "s3") : ?>checked<?php endif; ?>>
        <label for="s3"><?php esc_html_e("Amazon S3", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="video-url" value="html5" <?php if ($lesson_type == "html5") : ?>checked<?php endif; ?>>
        <label for="video-url"><?php esc_html_e("Video URL [.mp4]", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="document" value="document" <?php if ($lesson_type == "document") : ?>checked<?php endif; ?>>
        <label for="document"><?php esc_html_e("Document", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="image" value="image" <?php if ($lesson_type == "image") : ?>checked<?php endif; ?>>
        <label for="image"><?php esc_html_e("Image", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-form-group">
        <input type="radio" name="lesson_type" id="iframe" value="iframe" <?php if ($lesson_type == "iframe") : ?>checked<?php endif; ?>>
        <label for="iframe"><?php esc_html_e("Iframe Embed", BaseController::$text_domain) ?></label>
    </div>

    <div class="mxlms-custom-modal-action-footer">
        <div class="mxlms-custom-modal-actions">
            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
            <button type="submit" class="mxlms-btn mxlms-btn-primary mxlms-btn-md" onclick="showLessonAddingPage()"><?php esc_html_e("Next", BaseController::$text_domain) ?></button>
        </div>
    </div>
</div>

<script>
    "use strict";

    function showLessonAddingPage() {
        let lessonType = jQuery('input[name="lesson_type"]:checked').val();
        present_right_modal('curriculum/create', '<?php esc_html_e('Add New Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', lessonType);
    }
</script>