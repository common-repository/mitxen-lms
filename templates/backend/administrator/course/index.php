<?php
defined('ABSPATH') or die('You can not access the file directly');

$page_contains = (isset($_GET['page-contains']) && sanitize_text_field($_GET['page-contains'])) ? sanitize_text_field($_GET['page-contains']) : "course-list";
switch ($page_contains) {
    case "course-create":
        include 'course-create.php';
        break;
    case "course-edit":
        include 'course-edit.php';
        break;
    default:
        include 'course-list.php';
        break;
}
