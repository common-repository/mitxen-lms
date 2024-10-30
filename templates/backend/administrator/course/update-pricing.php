<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

?>

<div class="mxlms-row mxlms-mr-1">
    <div class="mxlms-col-xl-8">
        <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-pricing-update-form' enctype='multipart/form-data' autocomplete="off">
            <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
            <input type="hidden" name="task" value="edit_course">
            <input type="hidden" name="edit_course_nonce" value="<?php echo wp_create_nonce('edit_course_nonce'); ?>"> <!-- kind of csrf token-->
            <input type="hidden" name="section" value="pricing">
            <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">

            <div class="mxlms-form-group">
                <div class="offset-md-2 col-md-10">
                    <div class="custom-control custom-checkbox">
                        <input type="radio" class="custom-control-input mxlms-pricing-radio-paid" name="is_free_course" id="paid_course" value="0" onclick="togglePriceFields()" <?php if (!$course_details->is_free_course) echo "checked" ?>>
                        <label class="custom-control-label" for="paid_course"><?php esc_html_e("Paid Course", BaseController::$text_domain) ?></label>

                        <input type="radio" class="custom-control-input mxlms-pricing-radio-free" name="is_free_course" id="free_course" value="1" onclick="togglePriceFields()" <?php if ($course_details->is_free_course) echo "checked" ?>>
                        <label class="custom-control-label" for="free_course"><?php esc_html_e("Free Course", BaseController::$text_domain) ?></label>
                    </div>
                </div>
            </div>

            <div class="paid-course-stuffs">
                <div class="mxlms-form-group">
                    <label for="price"><?php esc_html_e("Course Price", BaseController::$text_domain) ?></label>
                    <input type="number" class="mxlms-form-control" id="price" name="price" placeholder="<?php esc_html_e("Enter Course Price", BaseController::$text_domain) ?>" min="0" value="<?php echo esc_attr($course_details->price); ?>" step="0.01">
                </div>

                <div class="mxlms-form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="discount_flag" id="discount_flag" value="1" <?php if ($course_details->discount_flag) echo "checked"; ?>>
                        <label for="discount_flag"><?php esc_html_e("Check if this course has a discount", BaseController::$text_domain) ?></label>
                    </div>
                </div>

                <div class="mxlms-form-group">
                    <label for="discounted_price"><?php esc_html_e("Discounted Price", BaseController::$text_domain) ?></label>
                    <input type="number" class="mxlms-form-control" name="discounted_price" id="discounted_price" onkeyup="calculateDiscountPercentage(this.value)" min="0" value="<?php echo esc_attr($course_details->discounted_price); ?>" step="0.01" placeholder="<?php esc_html_e('Enter Discounted Price', BaseController::$text_domain); ?>">
                    <small class="mxlms-text-muted"><?php esc_html_e("This course has", BaseController::$text_domain) ?> <span id="discounted_percentage" class="mxlms-text-danger">0%</span> <?php esc_html_e("discount", BaseController::$text_domain) ?></small>
                </div>
            </div>

            <div class="mxlms-form-group">
                <button type="submit" class="mxlms-btn mxlms-btn-success"><?php esc_html_e("Update pricing", BaseController::$text_domain) ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    "use strict";

    jQuery(document).ready(function() {

        initNiceSelect();
        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.course-pricing-update-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options)
            return false;
        });
    });

    function validate() {
        var price = jQuery('#price').val();
        var discounted_price = jQuery('#discounted_price').val();
        var is_free_course = jQuery("input[name='is_free_course']:checked").val();

        if (is_free_course === "1") {
            if (price === '') {
                mxlmsNotify("<?php esc_html_e('Price can not be empty', BaseController::$text_domain) ?>", 'warning');
                return false;
            }
            if (jQuery('#discount_flag').is(":checked")) {
                if (discounted_price === '') {
                    mxlmsNotify("<?php esc_html_e('Discounted price can not be empty', BaseController::$text_domain) ?>", 'warning');
                    return false;
                }
            }
        }
        return true;
    }

    function showResponse(response) {
        response = JSON.parse(response);
        if (response.status) {
            mxlmsNotify(response.message, 'success')
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }
</script>
