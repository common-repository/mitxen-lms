<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\User;

use Mxlms\base\modules\Course;
use Mxlms\base\modules\Helper;

$private_page_link = Helper::get_page_settings('private_page', true);
$course_id = sanitize_text_field($_POST['course_id']);
$course_details = Course::get_course_details_by_id($course_id);
$logged_in_user_details = User::get_logged_in_user_details();
$paypal_payment_info = json_decode(Helper::get_payment_settings('paypal'));

if ($paypal_payment_info->mode == "sandbox") {
    $paypalClientID = $paypal_payment_info->sandbox_client_id;
    $paypalSecret = $paypal_payment_info->sandbox_secret_key;
} else {
    $paypalClientID = $paypal_payment_info->production_client_id;
    $paypalSecret = $paypal_payment_info->production_secret_key;
}
?>
<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-hidden">
        <div class="mxlms-row mxlms-mb-3">
            <div class="mxlms-col-lg-12">
                <span class="mxlms-back">
                    <a href="<?php echo esc_url_raw(\Mxlms\base\modules\Helper::get_url('page-contains=payment-gateways&course-id=' . esc_html($course_id))); ?>"><i class="las la-caret-left"></i> <?php esc_html_e('Back', BaseController::$text_domain); ?></a>
                </span>

                <div class="mxlms-package-details">
                    <strong><?php esc_html_e('Student Name', BaseController::$text_domain); ?> | <?php echo esc_html($logged_in_user_details->first_name . ' ' . $logged_in_user_details->last_name); ?></strong> <br>
                    <strong><?php esc_html_e('Amount to pay', BaseController::$text_domain); ?> | <?php echo Helper::currency(Helper::get_course_price(esc_html($course_details->id))); ?></strong> <br>
                    <div id="paypal-button" class="mxlms-mt-4"></div><br>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            jQuery(".mxlms-preloader").hide();
            jQuery(".mxlms-page-content").removeClass('mxlms-hidden');

            paypal.Button.render({
                env: '<?php echo esc_js($paypal_payment_info->mode); ?>', // 'sandbox' or 'production'
                style: {
                    label: 'paypal',
                    size: 'medium', // small | medium | large | responsive
                    shape: 'rect', // pill | rect
                    color: 'blue', // gold | blue | silver | black
                    tagline: false
                },
                client: {
                    sandbox: '<?php echo esc_js($paypalClientID); ?>',
                    production: '<?php echo esc_js($paypalSecret); ?>'
                },

                commit: true, // Show a 'Pay Now' button

                payment: function(data, actions) {
                    return actions.payment.create({
                        payment: {
                            transactions: [{
                                amount: {
                                    total: '<?php echo Helper::get_course_price(esc_js($course_details->id)); ?>',
                                    currency: '<?php echo esc_js($paypal_payment_info->currency); ?>'
                                }
                            }]
                        }
                    });
                },

                onAuthorize: function(data, actions) {
                    // executes the payment
                    return actions.payment.execute().then(function() {
                        // PASSING TO CONTROLLER FOR CHECKING
                        // OLDER CODE
                        // var redirectUrl = '<?php echo esc_js(esc_url_raw(\Mxlms\base\modules\Helper::get_url("page-contains=payment-success&payment-type=paypal&course-id=$course_id&payment_id="))); ?>' + data.paymentID + '&payment_token=' + data.paymentToken + '&payer_id=' + data.payerID;
                        // window.location = redirectUrl;

                        var redirectUrl = '<?php echo esc_js(add_query_arg(array('page-contains' => 'payment-success', 'payment-type' => 'paypal', 'course-id' => $course_id, 'payment_id' => ''), esc_url($private_page_link))); ?>' + data.paymentID + '&payment_token=' + data.paymentToken + '&payer_id=' + data.payerID;
                        window.location = redirectUrl;
                    });
                }

            }, '#paypal-button');
        }, 500);
    }, false);
</script>