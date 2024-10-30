<?php defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Admin Report', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-reports&page-contains=report-menu" class="mxlms-btn mxlms-btn-primary mxlms-title-btn">
                            <i class="las la-long-arrow-alt-left"></i> <?php esc_html_e("Back to reports", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('List of Admin Revenue', BaseController::$text_domain); ?>
                    <!--Add the class mxlms-form report-export-form for ajax for submission-->
                    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='' enctype='multipart/form-data' autocomplete="off">
                        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_report'; ?>">
                        <input type="hidden" name="task" value="export_admin_report">
                        <input type="hidden" name="export_admin_report_nonce" value="<?php echo wp_create_nonce('export_admin_report_nonce'); ?>"> <!-- kind of csrf token-->
                        <input type="hidden" name="report_export_start_date" id="report_export_start_date">
                        <input type="hidden" name="report_export_end_date" id="report_export_end_date">
                        <button type="submit" class="mxlms-btn mxlms-btn-sm mxlms-btn-primary mxlms-export-button mxlms-text-decoration-none" name="button">
                            <i class="las la-arrow-circle-down"></i>
                            <?php esc_html_e('Export CSV', BaseController::$text_domain); ?>
                        </button>
                    </form>
                </div>
                <div class="mxlms-panel-body">
                    <div class="mxlms-mb-4 mxlms-form-group">
                        <div class="mxlms-row mxlms-justify-content-center">
                            <div class="mxlms-col-lg-6">
                                <div class="mxlms-form-group mxlms-row">
                                    <label for="reportrange" class="mxlms-col-sm-4 mxlms-col-form-label mxlms-text-right mxlms-date-range-label"><?php esc_html_e('Select Date Range', BaseController::$text_domain); ?></label>
                                    <div class="mxlms-col-sm-8">
                                        <input type="text" name="title" class="mxlms-form-control" id="reportrange" aria-describedby="title" placeholder="<?php esc_html_e('Select Date Range', BaseController::$text_domain); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="mxlms-col-md-1">
                                <button class="mxlms-btn mxlms-btn-md mxlms-btn-primary" onclick="filter()"><?php esc_html_e('Filter', BaseController::$text_domain); ?></button>
                            </div>
                        </div>
                    </div>

                    <div id="admin-report-list-area"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    jQuery(document).ready(function($) {
        initDateRangePicker(["#reportrange"]);
        filter();
    });

    function showAdminReportList(startDate, endDate) {
        mxlmsMakeAjaxCall(ajaxurl, 'report/admin-report-list', 'admin-report-list-area', startDate, endDate);
    }

    function paginate(startDate, endDate, pageNumber, pageSize) {
        mxlmsMakeAjaxCall(ajaxurl, 'report/admin-report-list', 'admin-report-list-area', startDate, endDate, pageNumber, pageSize);
    }

    function filter() {
        let dateRange = jQuery("#reportrange").val();
        let splittedRange = dateRange.split('-');
        let startDate = splittedRange[0].trim();
        let endDate = splittedRange[1].trim();

        assingingDatesForExporting();
        showAdminReportList(startDate, endDate);
    }

    function assingingDatesForExporting() {
        let dateRange = jQuery("#reportrange").val();
        if (dateRange) {
            let splittedRange = dateRange.split('-');
            let startDate = splittedRange[0].trim();
            let endDate = splittedRange[1].trim();

            jQuery('#report_export_start_date').val(startDate);
            jQuery('#report_export_end_date').val(endDate);
        }

    }
</script>