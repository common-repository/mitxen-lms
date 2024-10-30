<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Section;
use Mxlms\base\modules\Lesson;


$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_details = Course::get_course_details_by_id($course_id);
$slug = Helper::slugify($course_details->title);
$sections = Section::get_sections($course_id);
$lesson_id = filter_input(INPUT_GET, 'lesson-id', FILTER_SANITIZE_URL);
if ($lesson_id) {
    $active_lesson_id = $lesson_id;
    $lesson_details = Lesson::get_lesson_by_id($active_lesson_id);
    $active_section_id = $lesson_details->section_id;
} else {
    $active_section_id = $sections[0]->id;
    $active_lessons    = Lesson::get_lessons_by_section_id($active_section_id);
    $active_lesson_id  = $active_lessons[0]->id;
    $lesson_details = Lesson::get_lesson_by_id($active_lesson_id);
}
?>




<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-opacity-0">
        <?php include 'mxlms-page-navbar.php'; ?>
        <?php if ($course_details->id == $lesson_details->course_id && Helper::is_purchased($course_details->id)) : ?>
            <div class="mxlms-row mxlms-lesson-player-holder">
                <div class="mxlms-col-lg-12 mxlms-pl-0 mxlms-pr-0 mxlms-course-player-header">
                    <?php echo esc_html($course_details->title); ?>
                    <a href="<?php echo esc_url(Helper::get_url("page-contains=course-details&course=$slug&id=$course_id")); ?>" class="mxlms-lesson-player-option-button">
                        <span class="mxlms-back" mxlms-tooltip="<?php esc_html_e('Course Info', BaseController::$text_domain); ?>">
                            <i class="las la-info-circle"></i>
                        </span>
                    </a>
                    <a href="<?php echo esc_url(Helper::get_url('page-contains=my-courses')); ?>" class="mxlms-lesson-player-option-button">
                        <span class="mxlms-back" mxlms-tooltip="<?php esc_html_e('Back To My Courses', BaseController::$text_domain); ?>">
                            <i class="las la-arrow-circle-left"></i>
                        </span>
                    </a>
                </div>
                <div class="mxlms-col-lg-8 mxlms-pl-0 mxlms-pr-0" id="mxlms-lesson-player">
                    <?php include "mxlms-lesson-player.php"; ?>
                </div>
                <div class="mxlms-col-lg-4 mxlms-lesson-list-for-player" id="mxlms-lesson-list-area">
                    <?php include "mxlms-lesson-list.php"; ?>
                </div>
            </div>

            <!-- IF SUMMARY EXISTS, SUMMARY OF EVERY LESSON WILL GO HERE OTHERWISE HIDE THE SECTION BELOW -->
            <div class="mxlms-row">
                <div class="mxlms-col-12 mxlms-lesson-summary">
                    <div class="mxlms-alert mxlms-alert-warning" role="mxlms-alert">
                        <span class="mxlms-lesson-summary-title"><?php esc_html_e('Summary', BaseController::$text_domain); ?> : </span><?php echo esc_textarea($lesson_details->summary); ?>
                    </div>
                </div>
            </div>

            <!-- TAB FOR ADDONS -->
            <div class="mxlms-row mxlms-mt-3">
                <div class="mxlms-col-12" id="mxlms-lesson-addon-tabview">
                    <?php include "mxlms-lesson-tabview.php"; ?>
                </div>
            </div>

        <?php else : ?>
            <div class="mxlms-row">
                <div class="mxlms-col-lg-12">
                    <?php include "mxlms-404.php"; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<script>
    "use strict";

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            jQuery(".mxlms-preloader").hide();
            jQuery(".mxlms-page-content").removeClass('mxlms-opacity-0');
        }, 500);
    }, false);
</script>
