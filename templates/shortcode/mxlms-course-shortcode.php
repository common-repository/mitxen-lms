<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\modules\Helper;

$page_contains = filter_input(INPUT_GET, 'page-contains', FILTER_SANITIZE_URL);
$page_contains = !empty($page_contains) ? $page_contains : "courses";

switch ($page_contains) {
    case "courses":
        require 'mxlms-courses.php';
        break;
    case "course-details":
        require 'mxlms-course-details.php';
        break;
    case "payment-gateways":
        require 'mxlms-payment-gateways.php';
        break;
    case "paypal-checkout":
        require Helper::is_student_logged_in() ? 'payment-gateways/paypal/mxlms-paypal-checkout.php' : 'mxlms-forbidden.php';
        break;
    case "free-enrolment":
        require Helper::is_student_logged_in() ? 'mxlms-free-enrolment.php' : 'mxlms-forbidden.php';
        break;
    case "404":
        require 'mxlms-forbidden.php';
        break;
    default:
        require 'mxlms-courses.php';
}
