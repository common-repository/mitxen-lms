<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Enrolment;

?>
<?php if (Helper::get_current_user_role() == "student" || Helper::get_current_user_role() == "instructor") :
    $course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
    $course_details = Course::get_course_details_by_id($course_id);
    $status = Enrolment::enrol_to_free_course($course_id); ?>
    <?php if ($status) : ?>
        <div class="mxlms-container-fluid">
            <div class="mxlms-row mxlms-justify-content-center">
                <div class="mxlms-col-lg-6">
                    <div class="mxlms-card mxlms-text-center">
                        <div class="mxlms-card-header">
                            <span class="mxlms-h2"><?php esc_html_e("Congratulations!!!", BaseController::$text_domain); ?></span>
                            <p>
                                <?php esc_html_e("You have been enrolled to ", BaseController::$text_domain); ?> <?php echo esc_html($course_details->title); ?>
                            </p>
                        </div>
                        <div class="mxlms-card-body">
                            <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url_raw(\Mxlms\base\modules\Helper::get_url('page-contains=my-courses')); ?>"><?php esc_html_e("Check your courses"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="mxlms-container-fluid">
            <div class="mxlms-row mxlms-justify-content-center">
                <div class="mxlms-col-lg-6">
                    <div class="mxlms-card mxlms-text-center">
                        <div class="mxlms-card-header">
                            <span class="mxlms-h2"><?php esc_html_e("Oopps", BaseController::$text_domain); ?></span>
                            <p>
                                <?php esc_html_e("An error occurred", BaseController::$text_domain); ?>
                            </p>
                        </div>
                        <div class="mxlms-card-body">
                            <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url_raw(\Mxlms\base\modules\Helper::get_url('page-contains=courses')); ?>"><?php esc_html_e("Get Back To Previous Page"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else : ?>
    <?php include "mxlms-forbidden.php"; ?>
<?php endif; ?>