<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Course;
use Mxlms\base\AjaxPosts;


$course_id = $course_id ? $course_id : AjaxPosts::$param1;
$course_details = Course::get_course_details_by_id($course_id);

$slug = Helper::slugify($course_details->title);
$permalink = esc_url( Helper::get_url() );

?>

<div class="mxlms-addon-tab-view-placeholder mxlms-hidden"></div>
<div class="mxlms-tabset mxlms-addon-tab-view">
    <!-- Tab 0 -->
    <input type="radio" name="tabset" id="about" aria-controls="about" checked>
    <label for="about">
        <i class="las la-book"></i>
        <span class="mxlms-tab-title"><?php esc_html_e('About', BaseController::$text_domain); ?></span>
    </label>

    <!-- Tab 1 -->
    <input type="radio" name="tabset" id="certificate" aria-controls="certificate">
    <label for="certificate">
        <i class="las la-certificate"></i>
        <span class="mxlms-tab-title"><?php esc_html_e('Certificate', BaseController::$text_domain); ?></span>
    </label>

    <!-- Tab 2-->
    <input type="radio" name="tabset" id="live-class" aria-controls="live-class">
    <label for="live-class">
        <i class="las la-camera"></i>
        <span class="mxlms-tab-title"><?php esc_html_e('Live Class', BaseController::$text_domain); ?></span>
    </label>

    <div class="mxlms-tab-panels">
        <!-- LESSON SUMMARY TAB CONTENT -->
        <section id="about" class="mxlms-tab-panel">
            <?php echo esc_textarea($course_details->short_description); ?>
        </section>
        <!-- CERTIFICATE TAB CONTENT -->
        <section id="certificate" class="mxlms-tab-panel">
            <?php include 'mxlms-certificate-tabview.php'; ?>
        </section>
        <!-- CERTIFICATE TAB CONTENT -->
        <section id="live-class" class="mxlms-tab-panel">
            <?php include 'mxlms-live-class-tabview.php'; ?>
        </section>
    </div>
</div>
