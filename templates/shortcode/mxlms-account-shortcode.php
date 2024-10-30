<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\payment\Stripe;

// CHECK IS ADMIN LOGGED IN RIGHT OF THE BAT
if (Helper::get_current_user_role() == "administrator") {
    wp_redirect(admin_url());
}

$page_contains = filter_input(INPUT_GET, 'page-contains', FILTER_SANITIZE_URL);

if (!empty($page_contains)) {
    $page_contains = $page_contains;
} else {
    $page_contains = Helper::is_student_logged_in() ? "my-courses" : "login";
}

switch ($page_contains) {
    case "login":
        require 'mxlms-login.php';
        break;
    case "signup":
        require 'mxlms-signup.php';
        break;
    case "my-courses":
        require Helper::is_student_logged_in() ? 'mxlms-my-courses.php' : 'mxlms-forbidden.php';
        break;
    case "my-wishlist":
        require Helper::is_student_logged_in() ? 'mxlms-my-wishlist.php' : 'mxlms-forbidden.php';
        break;
    case "my-messages":
        require Helper::is_student_logged_in() ? 'mxlms-my-messages.php' : 'mxlms-forbidden.php';
        break;
    case "purchase-history":
        require Helper::is_student_logged_in() ? 'mxlms-purchase-history.php' : 'mxlms-forbidden.php';
        break;
    case "download-invoice":
        require Helper::is_student_logged_in() ? 'mxlms-download-invoice.php' : 'mxlms-forbidden.php';
        break;
    case "lessons":
        require Helper::is_student_logged_in() ? 'mxlms-lessons.php' : 'mxlms-forbidden.php';
        break;
    case "live-class":
        require Helper::is_student_logged_in() ? 'mxlms-live-class.php' : 'mxlms-forbidden.php';
        break;
    case "payment-success":
        require Helper::is_student_logged_in() ? 'mxlms-payment-success.php' : 'mxlms-forbidden.php';
        break;
    default:
        require 'mxlms-404.php';
}
