<?php defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;
use Mxlms\base\modules\Category;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Enrolment;
use Mxlms\base\modules\Lesson;

use Mxlms\base\modules\Student;

$total_courses = Course::get_all_courses();
$total_lessons = Lesson::get_all_lessons();
$total_enrolment = Enrolment::get_all_enrolment();
$total_student = Student::get_student();
?>
<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Admin Dashboard', BaseController::$text_domain); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-lg-12">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-row">
                        <div class="mxlms-col-md-3 mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-cubes mxlms-dashboard-icon"></i> <span class="mxlms-dashboard-value"><?php echo esc_html(count((array) $total_courses)); ?></span>
                            </p>
                            <p class="mxlms-settings-type-title mxlms-dashboard-info">
                                <?php esc_html_e('Total Courses', BaseController::$text_domain); ?>
                            </p>
                        </div>
                        <div class="mxlms-col-md-3 mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-book-reader mxlms-dashboard-icon"></i> <span class="mxlms-dashboard-value"><?php echo esc_html(count((array) $total_lessons)); ?></span>
                            </p>
                            <p class="mxlms-settings-type-title mxlms-dashboard-info">
                                <?php esc_html_e('Total Lessons', BaseController::$text_domain); ?>
                            </p>
                        </div>
                        <div class="mxlms-col-md-3 mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-handshake mxlms-dashboard-icon"></i> <span class="mxlms-dashboard-value"><?php echo esc_html(count((array) $total_enrolment)); ?></span>
                            </p>
                            <p class="mxlms-settings-type-title mxlms-dashboard-info">
                                <?php esc_html_e('Total Enrollment', BaseController::$text_domain); ?>
                            </p>
                        </div>

                        <div class="mxlms-col-md-3">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-graduation-cap mxlms-dashboard-icon"></i> <span class="mxlms-dashboard-value"><?php echo esc_html(count((array) $total_student)); ?></span>
                            </p>
                            <p class="mxlms-settings-type-title mxlms-dashboard-info">
                                <?php esc_html_e('Total Student', BaseController::$text_domain); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-lg-12">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Course Selling Earnings : ', BaseController::$text_domain); ?><?php echo date("Y"); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div id="chartdiv"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'chart.php'; ?>