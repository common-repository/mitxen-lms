<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
?>


<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-hidden">

        <?php include 'mxlms-page-navbar.php'; ?>
        <div class="mxlms-h5">
            <?php esc_html_e('My Courses', BaseController::$text_domain); ?>
        </div>

        <div id="mxlms-my-course-list-area">
            <?php include "mxlms-my-course-list.php"; ?>
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

            initNiceSelect();

            var options = {
                beforeSubmit: validate,
                success: showResponse,
                resetForm: false
            };
            jQuery('.rating-update-form').on('submit', function() {
                jQuery(this).ajaxSubmit(options);
                return false;
            });

        }, 500);
    }, false);


    function showReviewArea(id) {
        jQuery("#mxlms-course-rating-edit-area-" + id).show();
        jQuery("#mxlms-edit-review-btn-" + id).hide();
        jQuery("#mxlms-cancel-review-btn-" + id).show();
    }

    function hideReviewArea(id) {
        jQuery("#mxlms-course-rating-edit-area-" + id).hide();
        jQuery("#mxlms-edit-review-btn-" + id).show();
        jQuery("#mxlms-cancel-review-btn-" + id).hide();
    }

    function validate() {
        var review = jQuery('#review').val();

        if (review === '') {

            mxlmsNotify("<?php esc_html_e('You must enter something', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            mxlmsNotify(response.message, 'success');
            setTimeout(() => {
                hideReviewArea(response.course_id);
                var starRatings = "";
                for (let index = 1; index < 6; index++) {
                    if (index <= response.rating) {
                        starRatings = starRatings + "<i class='las la-star mxlms-rated'></i> ";
                    } else {
                        starRatings = starRatings + "<i class='las la-star mxlms-unrated'></i> ";
                    }
                }
                jQuery("#mxlms-rating-area-" + response.course_id).html(starRatings);
            }, 500);

        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>