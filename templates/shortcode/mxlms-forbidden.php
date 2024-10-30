<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
?>
<div class="mxlms-container-fluid">
    <div class="mxlms-row mxlms-justify-content-center">
        <div class="mxlms-col-lg-6">
            <div class="mxlms-card mxlms-text-center">
                <div class="mxlms-card-header">
                    <span class="mxlms-h2"><?php esc_html_e("403 Forbidden", BaseController::$text_domain); ?></span>
                    <p>
                        <?php esc_html_e("Student is not logged in", BaseController::$text_domain); ?>
                    </p>
                </div>
                <div class="mxlms-card-body">
                    <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="wp-login.php"><?php esc_html_e("Login"); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>