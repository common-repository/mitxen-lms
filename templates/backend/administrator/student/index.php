<?php
defined('ABSPATH') or die('You can not access the file directly');
$page_contains = (isset($_GET['page-contains']) && sanitize_text_field($_GET['page-contains'])) ? sanitize_text_field($_GET['page-contains']) : "student-list";
switch ($page_contains) {
    case "student-create":
        include 'student-create.php';
        break;
    case "student-edit":
        include 'student-edit.php';
        break;
    default:
        include 'student-list.php';
        break;
}
