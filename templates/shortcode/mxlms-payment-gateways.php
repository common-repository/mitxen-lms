<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\User;


$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_details = Course::get_course_details_by_id($course_id);
$slug = Helper::slugify($course_details->title);
$instructor_details = User::get_user_by_id($course_details->user_id);
$amount_to_check_out = Helper::get_course_price($course_details->id);
?>
<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-hidden">
        <div class="mxlms-row mxlms-justify-content-center mxlms-mb-5">
            <div class="mxlms-col-md-8">
                <span class="mxlms-back">
                    <a href="<?php echo esc_url(Helper::get_url_manually($permalink, "page-contains=course-details&course=$slug&id=$course_id")); ?>"><i class="las la-caret-left"></i> <?php esc_html_e("Back", BaseController::$text_domain); ?></a>
                </span>
                <div class="mxlms-row">
                    <div class="mxlms-col-md-12">
                        <span class="mxlms-h2"><?php esc_html_e('Make Payment', BaseController::$text_domain) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mxlms-row mxlms-justify-content-center">
            <div class="mxlms-col-md-8">
                <div class="mxlms-row">
                    <div class="mxlms-col-md-3">
                        <p class="mxlms-pb-2 mxlms-payment-header"><?php esc_html_e("Payment Gateways", BaseController::$text_domain); ?></p>

                        <div class="mxlms-row mxlms-payment-gateway mxlms-paypal" onclick="selectedPaymentGateway('paypal')">
                            <div class="mxlms-col-12">
                                <img class="mxlms-tick-icon mxlms-paypal-icon" src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/payment/tick.png')); ?>">
                                <img class="mxlms-payment-gateway-icon" src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/payment/paypal.png')); ?>">
                            </div>
                        </div>
                        <div class="mxlms-row mxlms-payment-gateway mxlms-stripe" onclick="selectedPaymentGateway('stripe')">
                            <div class="mxlms-col-12">
                                <img class="mxlms-tick-icon mxlms-stripe-icon" src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/payment/tick.png')); ?>">
                                <img class="mxlms-payment-gateway-icon" src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/payment/stripe.png')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mxlms-col-md-1"></div>

                    <div class="mxlms-col-md-8">
                        <div class="mxlms-w-100">
                            <p class="mxlms-pb-2 mxlms-payment-header"><?php esc_html_e("Order Summary", BaseController::$text_domain); ?></p>
                            <p class="mxlms-item mxlms-float-left">
                                <span class="mxlms-count-item">1</span>
                                <span class="mxlms-item-title"><?php echo esc_html($course_details->title); ?>
                                    <span class="mxlms-item-price">
                                        <?php echo Helper::currency($amount_to_check_out); ?>
                                    </span>
                                </span>
                                <span class="mxlms-by-owner">
                                    <?php esc_html_e('By', BaseController::$text_domain); ?> <?php echo esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name); ?>
                                </span>
                            </p>
                        </div>
                        <div class="mxlms-w-100 mxlms-mt-4 mxlms-indicated-price">
                            <div class="mxlms-float-right mxlms-total-price"><?php echo Helper::currency($amount_to_check_out); ?></div>
                            <div class="mxlms-float-right mxlms-total"><?php esc_html_e("Total", BaseController::$text_domain); ?></div>
                        </div>
                        <div class="mxlms-w-100 mxlms-float-left">
                            <div class="mxlms-paypal-form mxlms-form">
                                <?php include "payment-gateways/paypal/mxlms-paypal-payment-gateway-form.php"; ?>
                            </div>
                            <div class="mxlms-stripe-form mxlms-form">
                                <?php include "payment-gateways/stripe/mxlms-stripe-payment-gateway-form.php"; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    "use strict";

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            jQuery(".mxlms-preloader").hide();
            jQuery(".mxlms-page-content").removeClass('mxlms-hidden');
        }, 500);
    }, false);

    function selectedPaymentGateway(gateway) {
        jQuery(".mxlms-payment-gateway").css("border", "2px solid #D3DCDD");
        jQuery('.mxlms-tick-icon').hide();
        jQuery('.mxlms-form').hide();
        if (gateway == 'paypal') {
            jQuery(".mxlms-paypal").css("border", "2px solid #00D04F");
            jQuery('.mxlms-paypal-icon').show();
            jQuery('.mxlms-paypal-form').show();
        } else if (gateway == 'stripe') {
            jQuery(".mxlms-stripe").css("border", "2px solid #00D04F");
            jQuery('.mxlms-stripe-icon').show();
            jQuery('.mxlms-stripe-form').show();
        }
    }
</script>