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
                            <?php esc_html_e('Student Manager', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-students&page-contains=student-create" class="mxlms-btn mxlms-btn-primary mxlms-title-btn" onclick="present_modal_page( 'modal-student-add', '<?php esc_html_e('Add New Student', \Mxlms\base\BaseController::$text_domain); ?>' )">
                            <i class="las la-plus"></i> &nbsp; <?php esc_html_e("Add New Student", \Mxlms\base\BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-xl-8">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Student List', \Mxlms\base\BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div id="student-list-area"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>

<script>
    "use strict";

    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    jQuery(document).ready(function($) {
        showStudentList();
    });

    function showStudentList() {
        mxlmsMakeAjaxCall(ajaxurl, 'student/list', 'student-list-area');
    }


    function paginate(pageNumber, pageSize) {
        mxlmsMakeAjaxCall(ajaxurl, 'student/list', 'student-list-area', pageNumber, pageSize);
    }
</script>