<?php
defined('ABSPATH') or die('You can not access the file directly');

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
                            <?php esc_html_e('Settings', BaseController::$text_domain); ?>
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
                    <?php esc_html_e('Select a setting type', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div class="mxlms-row">
                        <!-- GENERAL SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-tools"></i> <?php esc_html_e('General Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to define plugin general settings and default settings for your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/general', '<?php esc_html_e('Plugin Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Plugin Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- GENERAL SETTINGS ENDS -->

                        <!-- INSTRUCTOR SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-user-cog"></i> <?php esc_html_e('Instructor Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to define public instructor and instructor revenue percentage for your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/instructor', '<?php esc_html_e('Instructor Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Instructor Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- INSTRUCTOR SETTINGS ENDS -->

                        <!-- PAYMENT SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-hand-holding-usd"></i> <?php esc_html_e('Payment Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to define default currency, currency positioning of your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/payment', '<?php esc_html_e('Payment Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Payment Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a><br>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/paypal', '<?php esc_html_e('Paypal Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Paypal Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a><br>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/stripe', '<?php esc_html_e('Stripe Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Stripe Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- PAYMENT SETTINGS ENDS -->

                        <!-- LANGUAGE SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-language"></i> <?php esc_html_e('Language Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to define default language for your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'language/create', '<?php esc_html_e('Create New Language', BaseController::$text_domain); ?>');" class="mxlms-text-decoration-none">
                                <?php esc_html_e('Add New Language', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a><br>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/language', '<?php esc_html_e('Language Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Language Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a><br>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'language/list', '<?php esc_html_e('Available Languages', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('Manage Available Languages', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- LANGUAGE SETTINGS ENDS -->

                        <!-- PAGE SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-sliders-h"></i> <?php esc_html_e('Page Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to define default language for your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/page', '<?php esc_html_e('Page Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Page Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- PAGE SETTINGS ENDS -->

                        <!-- CERTIFICATE SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-certificate"></i> <?php esc_html_e('Certificate Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to setup certificate template and text.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/certificate', '<?php esc_html_e('Certificate Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Certificate Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- CERTIFICATE SETTINGS ENDS -->

                        <!-- AWS SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="lab la-bitbucket"></i> <?php esc_html_e('Amazon S3 Bucket Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to setup your Amazon S3 bucket.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/s3', '<?php esc_html_e('S3 Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View S3 Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- AWS SETTINGS ENDS -->

                        <!-- ZOOM LIVE CLASS SETTINGS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-video"></i> <?php esc_html_e('Live Class Settings', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these settings to setup Zoom live class.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/live-class', '<?php esc_html_e('Live Class Settings', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Live Class Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- ZOOM LIVE CLASS SETTINGS ENDS -->

                        <!-- UPDATING MITXEN LMS STARTS -->
                        <div class="mxlms-col-md-4 mxlms-settings-container">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-binoculars"></i> <?php esc_html_e('Update Product', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use these option for updating your LMS.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'settings/update', '<?php esc_html_e('Update Product', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('View Plugin Update Settings', BaseController::$text_domain); ?>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                        <!-- UPDATING MITXEN LMS ENDS-->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>