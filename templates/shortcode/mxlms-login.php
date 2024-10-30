<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;

if (is_user_logged_in()) {
    wp_redirect(esc_url(Helper::get_url("page-contains=courses")));
    exit;
}
?>
<div class="mxlms-container-fluid">
    <?php include 'mxlms-page-navbar.php'; ?>
    <div class="mxlms-row mxlms-justify-content-center">
        <!-- FORM ELEMENT -->
        <div class="mxlms-col-md-6 mxlms-account-info">
            <i class="las la-sign-in-alt"></i>
            <div class="mxlms-title-login">
                <?php esc_html_e('Login to', BaseController::$text_domain); ?> <?php echo Helper::get_general_settings('system_name'); ?>
            </div>
        </div>
        <div class="mxlms-col-md-6">
            <div class="mxlms-card mxlms-account">
                <div class="mxlms-card-header mxlms-pt-3">
                    <div class="mxlms-card-title">
                        <?php esc_html_e('Login', BaseController::$text_domain); ?>
                    </div>
                </div>
                <div class="mxlms-card-body">
                    <form class="mxlms-form mxlms-form-layout" name="loginform" id="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post" autocomplete="off">
                        <input type="hidden" value="<?php echo esc_attr(esc_url(Helper::get_url("page-contains=my-courses"))); ?>" name="redirect_to">
                        <input type="hidden" value="1" name="testcookie">
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="user_login"><?php esc_html_e('Email', BaseController::$text_domain); ?></label>
                            <input class="mxlms-input" type="text" id="user_login" name="log" autocomplete="off" />
                        </div>
                        <div class="mxlms-field">
                            <label class="mxlms-text-label  mxlms-fieldlabel-layout" for="user_pass"><?php esc_html_e('Password', BaseController::$text_domain); ?></label>
                            <input class="mxlms-input" type="password" id="user_pass" name="pwd" />
                        </div>
                        <div class="mxlms-field">
                            <button class="mxlms-button mxlms-btn-secondary mxlms-block mxlms-round" id="wp-submit" type="submit" name="wp-submit"><?php esc_html_e("Login", BaseController::$text_domain); ?></button>
                        </div>
                    </form>
                </div>
                <div class="mxlms-card-footer mxlms-text-center">
                    <span class="mxlms-d-block mxlms-text-primary"><a href="<?php echo wp_lostpassword_url(wp_login_url()); ?>" class="mxlms-text-decoration-none mxlms-link-unset"><?php esc_html_e('Forgot Password?', BaseController::$text_domain); ?></a></span>
                    <span class="mxlms-d-block mxlms-text-primary"><a href="<?php echo esc_url(Helper::get_url("page-contains=signup")); ?>" class="mxlms-text-decoration-none mxlms-link-unset"><?php esc_html_e('Create a new account', BaseController::$text_domain); ?><i class="las la-arrow-right"></i></a></span>
                </div>
            </div>
        </div>
    </div>
</div>