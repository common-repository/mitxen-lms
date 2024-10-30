<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Course;
use Mxlms\base\modules\Helper;

$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$payment_type = filter_input(INPUT_GET, 'payment-type', FILTER_SANITIZE_URL);

$course_details = Course::get_course_details_by_id($course_id);
if ($payment_type == "paypal") {
    include Helper::get_plugin_path("templates/shortcode/payment-gateways/paypal/mxlms-paypal-success.php");
} elseif ($payment_type == "stripe") {
    include Helper::get_plugin_path("templates/shortcode/payment-gateways/stripe/mxlms-stripe-success.php");
} elseif ($payment_type == "free") {
    include "mxlms-enrolment-success.php";
}
