<?php defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;

$inner_page_contains = (isset($_GET['inner-page-contains']) && sanitize_text_field($_GET['inner-page-contains'])) ? sanitize_text_field($_GET['inner-page-contains']) : "message-home";
?>
<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-hidden">
        <?php include 'mxlms-page-navbar.php'; ?>
        <div class="mxlms-h5">
            <?php esc_html_e('My Messages', BaseController::$text_domain); ?>
        </div>
        <div class="mxlms-row mxlms-mr-1">
            <div class="mxlms-col">
                <div class="mxlms-panel">
                    <div class="mxlms-panel-body mxlms-p-0">
                        <?php
                        switch ($inner_page_contains) {
                            case "message-home":
                                include 'mxlms-message-home.php';
                                break;
                            case "message-read":
                                include 'mxlms-message-read.php';
                                break;
                            case "message-new":
                                include 'mxlms-message-new.php';
                                break;
                            default:
                                include 'mxlms-message-home.php';
                                break;
                        }
                        ?>
                    </div>
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
        }, 500);
    }, false);
</script>
