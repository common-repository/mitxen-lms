<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use \Mxlms\base\modules\Category;
use \Mxlms\base\modules\Course;
use Mxlms\base\modules\Helper;


$subcategories = Category::get_sub_categories();
$course_id  = sanitize_text_field($_GET['course_id']);
$active_tab = (isset($_GET['tab']) && sanitize_text_field($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'curriculum';
$course_details = Course::get_authenticated_course_details_by_id($course_id);
?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Edit Course', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-courses&page-contains=course-list" class="mxlms-btn mxlms-btn-primary mxlms-title-btn">
                            <i class="las la-long-arrow-alt-left"></i> <?php esc_html_e("Back To Courses", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-panel">
        <div class="mxlms-panel-title">
            <?php esc_html_e('Course Edit Form For', BaseController::$text_domain); ?> : <?php echo esc_html($course_details->title); ?>
        </div>
        <div class="mxlms-panel-body">
            <div class="mxlms-tabset">
                <!-- Tab 0 -->
                <input type="radio" name="tabset" id="curriculum" aria-controls="curriculum" <?php if ($active_tab == "curriculum") echo "checked"; ?>>
                <label for="curriculum">
                    <i class="las la-book"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Curriculum', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 7 -->
                <input type="radio" name="tabset" id="live-class" aria-controls="live-class" <?php if ($active_tab == "live-class") echo "checked"; ?>>
                <label for="live-class">
                    <i class="las la-video"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Live Class', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 1 -->
                <input type="radio" name="tabset" id="basic" aria-controls="basic" <?php if ($active_tab == "basic") echo "checked"; ?>>
                <label for="basic">
                    <i class="las la-exclamation-circle"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Basic', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 2 -->
                <input type="radio" name="tabset" id="pricing" aria-controls="pricing" <?php if ($active_tab == "pricing") echo "checked"; ?>>
                <label for="pricing">
                    <i class="las la-pound-sign"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Pricing', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 3 -->
                <input type="radio" name="tabset" id="requirements" aria-controls="requirements" <?php if ($active_tab == "requirements") echo "checked"; ?>>
                <label for="requirements">
                    <i class="las la-pen-nib"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Requirments', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 4 -->
                <input type="radio" name="tabset" id="outcomes" aria-controls="outcomes" <?php if ($active_tab == "outcomes") echo "checked"; ?>>
                <label for="outcomes">
                    <i class="las la-gift"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Outcomes', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 5 -->
                <input type="radio" name="tabset" id="media" aria-controls="media" <?php if ($active_tab == "media") echo "checked"; ?>>
                <label for="media">
                    <i class="las la-photo-video"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Media', BaseController::$text_domain); ?></span>
                </label>
                <!-- Tab 6 -->
                <input type="radio" name="tabset" id="tags" aria-controls="tags" <?php if ($active_tab == "tags") echo "checked"; ?>>
                <label for="tags">
                    <i class="las la-tag"></i>
                    <span class="mxlms-tab-title"><?php esc_html_e('Tags', BaseController::$text_domain); ?></span>
                </label>

                <div class="mxlms-tab-panels">
                    <section id="curriculum" class="mxlms-tab-panel">
                        <div id="curriculum-area">
                            <?php include 'update-curriculum.php'; ?>
                        </div>
                    </section>
                    <section id="live-class" class="mxlms-tab-panel">
                        <div id="live-class-area">
                            <?php include Helper::get_plugin_path('templates/backend/' . Helper::get_current_user_role() . '/liveclass/live-classes.php'); ?>
                        </div>
                    </section>
                    <section id="basic" class="mxlms-tab-panel">
                        <?php include 'update-basic.php'; ?>
                    </section>
                    <section id="pricing" class="mxlms-tab-panel">
                        <?php include 'update-pricing.php'; ?>
                    </section>
                    <section id="requirements" class="mxlms-tab-panel">
                        <?php include 'update-requirements.php'; ?>
                    </section>
                    <section id="outcomes" class="mxlms-tab-panel">
                        <?php include 'update-outcomes.php'; ?>
                    </section>
                    <section id="media" class="mxlms-tab-panel">
                        <?php include 'update-media.php'; ?>
                    </section>
                    <section id="tags" class="mxlms-tab-panel">
                        <?php include 'update-tags.php'; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>