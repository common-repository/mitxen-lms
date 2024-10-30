<?php defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;

$page_contains = (isset($_GET['page-contains']) && sanitize_text_field($_GET['page-contains'])) ? sanitize_text_field($_GET['page-contains']) : "profile-menus";
switch ($page_contains) {
    case "update-profile":
        include 'update-profile.php';
        break;
    case "update-password":
        include 'update-password.php';
        break;
    default:
        include 'profile-menus.php';
        break;
}
include "$this->plugin_path/templates/backend/modal/index.php";
