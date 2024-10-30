<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;
?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Add New Course', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-courses&page-contains=course-list" class="mxlms-btn mxlms-btn-primary mxlms-title-btn">
                            <i class="las la-long-arrow-alt-left"></i> <?php esc_html_e("Back To Courses", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-xl-6 mxlms-col-md-8">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Course Create Form', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-add-form' enctype='multipart/form-data' autocomplete="off">
                        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
                        <input type="hidden" name="task" value="add_course">
                        <input type="hidden" name="add_course_nonce" value="<?php echo wp_create_nonce('add_course_nonce'); ?>"> <!-- kind of csrf token-->
                        <div class="mxlms-form-group">
                            <label for="title"><?php esc_html_e('Course Title', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
                            <input type="text" name="title" class="mxlms-form-control" id="title" aria-describedby="title" placeholder="<?php esc_html_e('Course Title', BaseController::$text_domain); ?>">
                        </div>


                        <div class="mxlms-form-group">
                            <label for="category_id"><?php esc_html_e("Category", BaseController::$text_domain) ?> <span class="mxlms-text-danger"><span class="mxlms-text-danger">*</span></span></label>
                            <div class="mxlms-d-flex">
                                <div class="mxlms-flex-grow-1 mxlms-pr-2">
                                    <div class="mxlms-form-group" id="category-area">
                                        <select name="category_id" class="mxlms-wide" id="category_id">
                                            <option value=''><?php esc_html_e("Select A Category", BaseController::$text_domain) ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="">
                                    <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-sm mxlms-add-option-btn" name="button" onclick="present_right_modal( 'course/category-create', '<?php esc_html_e('Add New Category', BaseController::$text_domain); ?>' )" mxlms-tooltip="<?php esc_html_e("Add new category", BaseController::$text_domain); ?>"> <i class="las la-plus-circle"></i> </button>
                                </div>
                            </div>
                        </div>

                        <div class="mxlms-form-group">
                            <label for="sub_category_id"><?php esc_html_e("Sub Category", BaseController::$text_domain) ?> <span class="mxlms-text-danger"><span class="mxlms-text-danger">*</span></span></label>
                            <div class="mxlms-d-flex">
                                <div class="mxlms-flex-grow-1 mxlms-pr-2">
                                    <div class="mxlms-form-group" id="sub-category-area">
                                        <select name="sub_category_id" class="mxlms-wide" id="sub_category_id">
                                            <option value=''><?php esc_html_e("Select A Category First", BaseController::$text_domain) ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="">
                                    <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-sm mxlms-add-option-btn" name="button" onclick="present_right_modal_for_subcategory_add()" mxlms-tooltip="<?php esc_html_e("Add new Subcategory", BaseController::$text_domain); ?>"> <i class="las la-plus-circle"></i> </button>
                                </div>
                            </div>
                        </div>


                        <div class="mxlms-form-group">

                        </div>


                        <div class="mxlms-form-group">
                            <label for="pricing" class="mxlms-mr-2"><?php esc_html_e("Pricing", BaseController::$text_domain) ?> <span class="mxlms-text-danger"><span class="mxlms-text-danger">*</span></span> : </label>
                            <input type="radio" class="mxlms-pricing-radio-paid" name="is_free_course" id="paid_course" value="0" onclick="togglePriceFields(this.name)" checked>
                            <label class="" for="paid_course"><?php esc_html_e("Paid Course", BaseController::$text_domain) ?></label>

                            <input type="radio" class="mxlms-pricing-radio-free" name="is_free_course" id="free_course" value="1" onclick="togglePriceFields(this.name)">
                            <label class="" for="free_course"><?php esc_html_e("Free Course", BaseController::$text_domain) ?></label>
                        </div>

                        <div class="paid-course-stuffs">
                            <div class="mxlms-form-group">
                                <label for="price"><?php esc_html_e("Course Price", BaseController::$text_domain) ?></label>
                                <input type="number" class="mxlms-form-control" id="price" name="price" placeholder="<?php esc_html_e("Enter Course Price", BaseController::$text_domain) ?>" min="0" step="0.01">
                            </div>

                            <div class="mxlms-form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="discount_flag" id="discount_flag" value="1">
                                    <label for="discount_flag"><?php esc_html_e("Check if this course has a discount", BaseController::$text_domain) ?></label>
                                </div>
                            </div>

                            <div class="mxlms-form-group">
                                <label for="discounted_price"><?php esc_html_e("Discounted Price", BaseController::$text_domain) ?></label>
                                <input type="number" class="mxlms-form-control" name="discounted_price" id="discounted_price" onkeyup="calculateDiscountPercentage(this.value)" min="0" step="0.01" placeholder="<?php esc_html_e('Enter Discounted Price', BaseController::$text_domain); ?>">
                                <small class="mxlms-text-muted"><?php esc_html_e("This course has", BaseController::$text_domain) ?> <span id="discounted_percentage" class="mxlms-text-danger">0%</span> <?php esc_html_e("discount", BaseController::$text_domain) ?></small>
                            </div>
                        </div>

                        <div class="mxlms-form-group">
                            <button type="submit" class="mxlms-btn mxlms-btn-md mxlms-btn-success"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>
<script>
    "use strict";
    jQuery(document).ready(function() {
        initNiceSelect();
        getCategories();
    });


    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: true
        };
        jQuery('.course-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options)
            return false;
        });
    });

    function validate() {
        var title = jQuery('#title').val();
        var category_id = jQuery('#category_id').val();
        var sub_category_id = jQuery('#sub_category_id').val();
        var price = jQuery('#price').val();
        var discounted_price = jQuery('#discounted_price').val();
        var is_free_course = jQuery("input[name='is_free_course']:checked").val();

        if (title === '' || sub_category_id === '' || category_id === '') {
            mxlmsNotify("<?php esc_html_e('Title, Category or Sub Category can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        } else {
            if (is_free_course != "1") {
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
        }
        return true;
    }

    function showResponse(response) {
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.course-add-form').trigger('reset');
            mxlmsNotify(response.message, 'success');
            setTimeout(() => {
                window.location.href = "admin.php?page=mxlms-courses&page-contains=course-edit&course_id=" + response.id;
            }, 500);
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }

    function priceChecked(elem) {
        if (jQuery('#discountCheckbox').is(':checked')) {

            jQuery('#discountCheckbox').prop("checked", false);
        } else {

            jQuery('#discountCheckbox').prop("checked", true);
        }
    }


    function isFreeCourseChecked(elem) {

        if (jQuery('#' + elem.id).is(':checked')) {
            jQuery('#price').prop('required', false);
        } else {
            jQuery('#price').prop('required', true);
        }
    }

    function calculateDiscountPercentage(discounted_price) {
        if (discounted_price > 0) {
            var actualPrice = jQuery('#price').val();
            if (actualPrice > 0) {
                var reducedPrice = actualPrice - discounted_price;
                var discountedPercentage = (reducedPrice / actualPrice) * 100;
                if (discountedPercentage > 0) {
                    jQuery('#discounted_percentage').text(discountedPercentage.toFixed(2) + '%');

                } else {
                    jQuery('#discounted_percentage').text('<?php echo '0%'; ?>');
                }
            }
        }
    }

    function togglePriceFields(elem) {
        if (jQuery("input[name='" + elem + "']:checked").val() === "0") {
            jQuery('.paid-course-stuffs').slideDown();
        } else
            jQuery('.paid-course-stuffs').slideUp();
    }

    function getSubCategories(categoryId, subCategoryId) {
        mxlmsMakeAjaxCall(ajaxurl, 'subcategory/dropdown-list', 'sub-category-area', categoryId, subCategoryId);
    }

    function getCategories(categoryId) {
        mxlmsMakeAjaxCall(ajaxurl, 'category/dropdown-list', 'category-area', categoryId);
    }

    function present_right_modal_for_subcategory_add() {
        present_right_modal('course/subcategory-create', '<?php esc_html_e('Add New Category', BaseController::$text_domain); ?>', jQuery('#category_id').val());
    }
</script>