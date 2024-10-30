<?php
defined('ABSPATH') or die('You can not access the file directly');
$page_contains = (isset($_GET['page-contains']) && sanitize_text_field($_GET['page-contains'])) ? sanitize_text_field($_GET['page-contains']) : "report-menu";

switch ($page_contains) {
    case "admin-report":
        include 'admin-report.php';
        break;
    case "instructor-report":
        include 'instructor-report.php';
        break;
    case "report-receipt":
        include 'report-receipt.php';
        break;
    case "export-receipt":
        include 'export-receipt.php';
        break;
    case "receipt-content-pdf":
        include 'receipt-content-pdf.php';
        break;
    case "email-receipt":
        include 'email-receipt.php';
        break;
    default:
        include 'report-menu.php';
        break;
}
