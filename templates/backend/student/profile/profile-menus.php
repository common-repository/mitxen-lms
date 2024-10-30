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
                            <?php esc_html_e('Manage Profile', BaseController::$text_domain); ?>
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
                    <?php esc_html_e('Manage Profile Options', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div class="mxlms-row">
                        <div class="mxlms-col-md-6 mxlms-settings-container mxlms-border-right-for-settings-types">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-chalkboard-teacher"></i> <?php esc_html_e('Update Profile', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use this option for updating your profile.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'profile/update-profile', '<?php esc_html_e('Update Profile', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('Update Profile', BaseController::$text_domain); ?>
                            </a>
                        </div>
                        <div class="mxlms-col-md-6 mxlms-settings-container">
                            <p class="mxlms-settings-type-title">
                                <i class="las la-file-invoice-dollar"></i> <?php esc_html_e('Update Password', BaseController::$text_domain); ?>
                            </p>
                            <p class="mxlms-settings-type-description">
                                <?php esc_html_e('Use this option for updating your password.', BaseController::$text_domain); ?>
                            </p>
                            <a href="javascript:void(0)" onclick="present_right_modal( 'profile/update-password', '<?php esc_html_e('Update Password', BaseController::$text_domain); ?>' )" class="mxlms-text-decoration-none">
                                <?php esc_html_e('Update Password', BaseController::$text_domain); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>