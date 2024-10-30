<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
?>
<div class="mxlms-container-fluid">
    <div class="mxlms-row mxlms-justify-content-center">
        <div class="mxlms-col-lg-6">
            <div class="mxlms-card mxlms-text-center">
                <div class="mxlms-card-header">
                    <span class="mxlms-h2"><?php esc_html_e("Error 404", BaseController::$text_domain); ?></span>
                    <p>
                        <?php esc_html_e("Page Not Found", BaseController::$text_domain); ?>
                    </p>
                </div>
                <div class="mxlms-card-body">
                    <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url( Helper::get_url("page-contains=courses") ); ?>">
                        <?php esc_html_e("Go Back", BaseController::$text_domain); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>