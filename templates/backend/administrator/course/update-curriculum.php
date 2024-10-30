<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use \Mxlms\base\modules\Section;
use \Mxlms\base\modules\Lesson;


$course_id = $course_id ? $course_id : \Mxlms\base\AjaxPosts::$param1;
$sections = Section::get_authenticated_sections($course_id);

?>
<div class="mxlms-row mxlms-justify-content-center">
    <div class="mxlms-col-xl-10">
        <div class="mxlms-course-update-actions mxlms-text-center">
            <button type="button" class="mxlms-btn mxlms-btn-outline-primary mxlms-mr-2 mxlms-btn-md" name="button" onclick="present_right_modal( 'section/create', '<?php esc_html_e('Add New Section', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )">
                <i class="las la-plus"></i>
                <?php esc_html_e('Add New Section', BaseController::$text_domain); ?></button>
            <button type="button" class="mxlms-btn mxlms-btn-outline-primary  mxlms-mr-2 mxlms-btn-md" name="button" onclick="present_right_modal( 'curriculum/types', '<?php esc_html_e('Types Of Lessons', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )">
                <i class="las la-plus"></i>
                <?php esc_html_e('Add New Lesson', BaseController::$text_domain); ?></button>
            <button type="button" class="mxlms-btn mxlms-btn-outline-primary  mxlms-mr-2 mxlms-btn-md" name="button" onclick="present_right_modal( 'course/sort-section', '<?php esc_html_e('Sort Sections', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )"><i class="las la-sort"></i> <?php esc_html_e('Sort Sections', BaseController::$text_domain); ?></button>
            <button type="button" class="mxlms-btn mxlms-btn-outline-primary  mxlms-mr-2 mxlms-btn-md" name="button" onclick="present_right_modal('curriculum/create', '<?php esc_html_e('Add New Quiz', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', 'quiz');"><i class="las la-puzzle-piece"></i> <?php esc_html_e('Add New Quiz', BaseController::$text_domain); ?></button>
        </div>
        <?php foreach ($sections as $key => $section) : ?>
            <div class="mxlms-panel mxlms-bg-secondary on-hover-action" id="section-<?php echo esc_attr($section->id); ?>">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Section:', BaseController::$text_domain); ?> <?php echo esc_html($section->title); ?>
                    <span class="mxlms-float-right mxlms-hidden" id="widgets-of-section-<?php echo esc_attr($section->id); ?>">
                        <button type="button" class="mxlms-btn mxlms-btn-outline-secondary mxlms-btn-round mxlms-btn-sm mxlms-mr-1" name="button" onclick="present_right_modal( 'course/sort-lesson', '<?php esc_html_e('Sort Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', '<?php echo esc_js($section->id); ?>' )">
                            <i class="las la-sort"></i>
                            <?php esc_html_e('Sort Lessons', BaseController::$text_domain); ?>
                        </button>
                        <button type="button" class="mxlms-btn mxlms-btn-outline-secondary mxlms-btn-round mxlms-btn-sm mxlms-mr-1" name="button" onclick="present_right_modal( 'section/edit', '<?php esc_html_e('Edit Section', BaseController::$text_domain); ?>', '<?php echo esc_js($section->id); ?>' )">
                            <i class="las la-pen"></i>
                            <?php esc_html_e('Edit Section', BaseController::$text_domain); ?>
                        </button>
                        <button type="button" class="mxlms-btn mxlms-btn-outline-secondary mxlms-btn-round mxlms-btn-sm mxlms-mr-1" name="button" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Section', BaseController::$text_domain); ?>', '<?php echo esc_js($section->id); ?>', 'section', 'course/update-curriculum', 'curriculum-area', '<?php echo esc_js($course_id); ?>')">
                            <i class="las la-trash"></i>
                            <?php esc_html_e('Delete Section', BaseController::$text_domain); ?>
                        </button>
                    </span>
                </div>
                <div class="mxlms-panel-body">
                    <?php $lessons = Lesson::get_authenticated_lessons_by_section_id($section->id); ?>
                    <?php foreach ($lessons as $key => $lesson) : ?>
                        <div class="mxlms-panel on-hover-action" id="lesson-<?php echo esc_attr($lesson->id); ?>">
                            <div class="mxlms-panel-title">
                                <?php if ($lesson->lesson_type == "quiz") : ?>
                                    <i class="las la-puzzle-piece"></i> <?php esc_html_e('Quiz :', BaseController::$text_domain); ?>
                                <?php else : ?>
                                    <i class="lar la-play-circle"></i> <?php esc_html_e('Lesson :', BaseController::$text_domain); ?>
                                <?php endif; ?>

                                <?php echo esc_html($lesson->title); ?>

                                <span class="mxlms-float-right mxlms-hidden" id="widgets-of-lesson-<?php echo esc_attr($lesson->id); ?>">
                                    <?php if ($lesson->lesson_type == "quiz") : ?>
                                        <a href="javascript:void(0)" class="mxlms-text-decoration-none mxlms-text-secondary" onclick="present_right_modal( 'question/list', '<?php esc_html_e('Quiz Questions', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', '<?php echo esc_js($lesson->id); ?>');"> <i class="lar la-question-circle"></i> </a>
                                    <?php endif; ?>
                                    <a href="javascript:void(0)" class="mxlms-text-decoration-none mxlms-text-secondary" onclick="present_right_modal( 'curriculum/edit', '<?php esc_html_e('Edit Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', '<?php echo esc_js($lesson->id); ?>');"> <i class="las la-pencil-alt"></i> </a>
                                    <a href="javascript:void(0)" class="mxlms-text-decoration-none mxlms-text-secondary" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($lesson->id); ?>', 'lesson', 'course/update-curriculum', 'curriculum-area', '<?php echo esc_js($course_id); ?>')"> <i class="las la-trash"></i> </a>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    "use strict";

    jQuery('.on-hover-action').on("mouseenter", function() {
        var id = this.id;
        jQuery('#widgets-of-' + id).show();
    });
    jQuery('.on-hover-action').on("mouseleave", function() {
        var id = this.id;
        jQuery('#widgets-of-' + id).hide();
    });
</script>