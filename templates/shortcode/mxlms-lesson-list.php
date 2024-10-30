<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;

use Mxlms\base\modules\Lesson;
?>
<div id="mxlms-lesson-accordion">
    <span class="mxlms-page-sub-header mxlms-filter-title"><?php esc_html_e('Lesson List', BaseController::$text_domain); ?></span>
    <ul class="mxlms-accordion">
        <?php foreach ($sections as $key => $section) :
            $lessons = Lesson::get_lessons_by_section_id($section->id); ?>
            <li class="mxlms-filter-card mxlms-section-list" id="section-<?php echo esc_attr($section->id); ?>">
                <a class="mxlms-accordion-toggle" href=#>
                    <?php esc_html_e('Section', BaseController::$text_domain); ?> : <?php echo esc_html($section->title); ?> <i class="las la-chevron-<?php if ($section->id == $active_section_id) echo 'up';
                                                                                                                                                        else echo 'down'; ?> mxlms-float-right"></i>
                </a>
                <p class="inner <?php if ($section->id == $active_section_id) echo 'show'; ?>">
                    <?php
                    foreach ($lessons as $key => $lesson) : ?>
                        <span class="mxlms-lesson-title mxlms-mt-4">
                            <input type="checkbox" id="<?php echo esc_attr($lesson->id); ?>" onchange="markThisLessonAsCompleted(this.id, '<?php echo esc_js($course_details->id); ?>');" value=1 <?php if (Helper::lesson_progress($lesson->id)) : ?> checked <?php endif; ?>>
                            <label for="<?php echo esc_attr($lesson->id); ?>" class="mxlms-display-inline"></label>
                            <a href="<?php echo esc_url( Helper::get_url('page-contains=lessons&course=' . Helper::slugify(esc_attr($course_details->title)) . '&course-id=' . esc_attr($course_details->id)) . '&lesson-id=' . esc_attr($lesson->id) ); ?>" class="mxlms-text-decoration-none mxlms-link-unset">
                                <span class="<?php if ($lesson->id == $active_lesson_id) echo 'mxlms-text-danger'; ?>"><?php echo esc_html($lesson->title); ?></span>
                            </a>
                            <span class="mxlms-lesson-duration-for-lesson-player mxlms-mt-1">
                                <?php if ($lesson->lesson_type == "video") : ?>
                                    <i class="las la-play-circle"></i> <?php echo Helper::readable_time_for_humans($lesson->duration); ?>
                                <?php elseif ($lesson->lesson_type == "iframe") : ?>
                                    <i class="las la-code"></i> <?php esc_html_e('iframe Embed', BaseController::$text_domain); ?>
                                <?php elseif ($lesson->lesson_type == "document") : ?>
                                    <i class="las la-paperclip"></i> <?php esc_html_e('attachment', BaseController::$text_domain); ?>
                                <?php elseif ($lesson->lesson_type == "image") : ?>
                                    <i class="lar la-image"></i> <?php esc_html_e('image', BaseController::$text_domain); ?>
                                <?php endif; ?>
                            </span>
                        </span>
                    <?php endforeach; ?>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    "use script";
    document.addEventListener('DOMContentLoaded', function() {
        let sectionId = '<?php echo 'section-' . esc_js($active_section_id); ?>';
        scrollToSection(sectionId);
    }, false);


    function scrollToSection(sectionId) {
        var contactTopPosition = jQuery("#" + sectionId).position().top;
        jQuery("#mxlms-lesson-list-area").scrollTop(contactTopPosition);
    }

    function markThisLessonAsCompleted(lessonId, courseId) {
        var progress;
        if (jQuery('input#' + lessonId).is(':checked')) {
            progress = 1;
        } else {
            progress = 0;
        }

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        mxlmsSaveCourseProgress(ajaxurl, lessonId, courseId, progress);
    }
</script>
