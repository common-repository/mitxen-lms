<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;
?>
<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Course Manager', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-courses&page-contains=course-create" class="mxlms-btn mxlms-btn-primary mxlms-title-btn">
                            <i class="la la-plus"></i> &nbsp; <?php esc_html_e("Add New Course", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-lg-12">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Available course list', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div id="course-list-area"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-floating-container">
        <a href="admin.php?page=mxlms-courses&page-contains=course-create" class="mxlms-floating"><i class="las la-plus"></i></a>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>

<script>
    'use strict';
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    jQuery(document).ready(function($) {
        showCourseList();
    });

    function showCourseList() {
        mxlmsMakeAjaxCall(ajaxurl, 'course/list', 'course-list-area');
    }

    function paginate(pageNumber, pageSize) {
        mxlmsMakeAjaxCall(ajaxurl, 'course/list', 'course-list-area', pageNumber, pageSize);
    }
</script>