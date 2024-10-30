<?php defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use Mxlms\base\modules\Helper; ?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Course Sales Report', BaseController::$text_domain); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Select a report type', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div class="mxlms-row">
                        <div class="mxlms-col-md-6 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-chart-bar"></i> <?php esc_html_e('Admin Earnings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use this option for getting report related to admin earnings. You can also filter reports date wise.', BaseController::$text_domain); ?>
                            </p>
                            <a href="admin.php?page=mxlms-reports&page-contains=admin-report" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Admin Report', BaseController::$text_domain); ?>
                                    <i class="las la-arrow-right"></i>
                                </a>
                        </div>
                        <div class="mxlms-col-md-6 mxlms-settings-container">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-chart-pie"></i> <?php esc_html_e('Instructor Earnings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use this option for getting report related to instructor earnings. You can also filter reports date wise.', BaseController::$text_domain); ?>
                            </p>
                            <a href="admin.php?page=mxlms-reports&page-contains=instructor-report" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Instructor Report', BaseController::$text_domain); ?>
                                    <i class="las la-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>