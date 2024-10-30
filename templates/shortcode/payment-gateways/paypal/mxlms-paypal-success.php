<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\payment\Paypal;


$paymentID = filter_input(INPUT_GET, 'payment_id', FILTER_SANITIZE_URL);
$paymentToken = filter_input(INPUT_GET, 'payment_token', FILTER_SANITIZE_URL);
$payerID = filter_input(INPUT_GET, 'payer_id', FILTER_SANITIZE_URL);

$status = Paypal::paypal_payment($paymentID);
?>
<?php if ($status) : ?>
    <?php if (Paypal::record_payment_data($course_id, $paymentID)) : ?>
        <div class="mxlms-container-fluid">
            <div class="mxlms-row mxlms-justify-content-center">
                <div class="mxlms-col-lg-6">
                    <div class="mxlms-card mxlms-text-center">
                        <div class="mxlms-card-header">
                            <span class="mxlms-h2"><?php esc_html_e("Congratulations!!!", BaseController::$text_domain); ?></span>
                            <p>
                                <?php esc_html_e("You have been enrolled to ", BaseController::$text_domain); ?> <?php echo esc_html($course_details->title); ?>
                            </p>
                        </div>
                        <div class="mxlms-card-body">
                            <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=my-courses') ); ?>"><?php esc_html_e("Check your courses"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="mxlms-container-fluid">
            <div class="mxlms-row mxlms-justify-content-center">
                <div class="mxlms-col-lg-6">
                    <div class="mxlms-card mxlms-text-center">
                        <div class="mxlms-card-header">
                            <span class="mxlms-h2"><?php esc_html_e("Oopps", BaseController::$text_domain); ?></span>
                            <p>
                                <?php esc_html_e("An error occurred", BaseController::$text_domain); ?>
                            </p>
                        </div>
                        <div class="mxlms-card-body">
                            <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=courses') ); ?>"><?php esc_html_e("Get Back To Previous Page"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else : ?>
    <div class="mxlms-container-fluid">
        <div class="mxlms-row mxlms-justify-content-center">
            <div class="mxlms-col-lg-6">
                <div class="mxlms-card mxlms-text-center">
                    <div class="mxlms-card-header">
                        <span class="mxlms-h2"><?php esc_html_e("Oopps", BaseController::$text_domain); ?></span>
                        <p>
                            <?php esc_html_e("An error occurred", BaseController::$text_domain); ?>
                        </p>
                    </div>
                    <div class="mxlms-card-body">
                        <a class="mxlms-btn mxlms-btn-danger mxlms-text-decoration-none" href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=courses') ); ?>"><?php esc_html_e("Get Back To Previous Page"); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>