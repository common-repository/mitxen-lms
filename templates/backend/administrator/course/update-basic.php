<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;


$categories    = \Mxlms\base\modules\Category::get_parent_categories();
$subcategories = \Mxlms\base\modules\Category::get_sub_categories_by_category_id($course_details->category_id);
$languages = Mxlms\base\modules\Language::get_all_languages();
?>

<div class="mxlms-row mxlms-mr-1">
    <div class="mxlms-col-xl-8">
        <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form course-basic-update-form' enctype='multipart/form-data' autocomplete="off">
            <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_course'; ?>">
            <input type="hidden" name="task" value="edit_course">
            <input type="hidden" name="edit_course_nonce" value="<?php echo wp_create_nonce('edit_course_nonce'); ?>"> <!-- kind of csrf token-->
            <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">
            <input type="hidden" name="section" value="basic">
            <div class="mxlms-form-group">
                <label for="title"><?php esc_html_e('Course Title', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
                <input type="text" name="title" class="mxlms-form-control" id="title" aria-describedby="title" placeholder="<?php esc_html_e('Course Title', BaseController::$text_domain); ?>" value="<?php echo esc_attr($course_details->title); ?>">
            </div>

            <div class="mxlms-form-group">
                <label for="category_id"><?php esc_html_e("Category", BaseController::$text_domain) ?> <span class="mxlms-text-danger"><span class="mxlms-text-danger">*</span></span></label>
                <select name="category_id" class="mxlms-form-control mxlms-wide" id="category_id" onchange="getSubCategories(this.value)">
                    <option value=''><?php esc_html_e("Select A Category", BaseController::$text_domain) ?></option>
                    <?php foreach ($categories as $key => $category) : ?>
                        <option value="<?php echo esc_attr($category->id); ?>" <?php if ($course_details->category_id == $category->id) echo "selected"; ?>><?php echo esc_html($category->title); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mxlms-form-group">
                <label for="sub_category_id"><?php esc_html_e("Sub Category", BaseController::$text_domain) ?> <span class="mxlms-text-danger"><span class="mxlms-text-danger">*</span></span></label>
                <select name="sub_category_id" class="mxlms-form-control mxlms-wide" id="sub_category_id">
                    <?php if (count($subcategories)) : ?>
                        <?php foreach ($subcategories as $key => $subcategory) : ?>
                            <option value="<?php echo esc_attr($subcategory->id) ?>" <?php if ($subcategory->id == $course_details->sub_category_id) echo "selected"; ?>><?php echo esc_html($subcategory->title); ?></option>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value=""><?php esc_html_e("No Sub Category Found", BaseController::$text_domain) ?></option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mxlms-form-group">
                <label for="short_description"><?php esc_html_e('Short Description', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
                <textarea name="short_description" class="mxlms-form-control" rows="1"><?php echo esc_html($course_details->short_description); ?></textarea>
            </div>

            <div class="mxlms-form-group">
                <label for="description"><?php esc_html_e('Description', BaseController::$text_domain); ?><span class="mxlms-text-danger">*</span></label>
                <textarea name="description" class="mxlms-form-control" rows="5"><?php echo esc_html($course_details->description); ?></textarea>
            </div>

            <div class="mxlms-form-group">
                <label for="level"><?php esc_html_e("Level", BaseController::$text_domain) ?></label>
                <select name="level" class="mxlms-form-control mxlms-wide" id="level">
                    <option value="beginner" <?php if ($course_details->level == "beginner") echo "selected"; ?>><?php esc_html_e("Beginner", BaseController::$text_domain) ?></option>
                    <option value="advanced" <?php if ($course_details->level == "advanced") echo "selected"; ?>><?php esc_html_e("Advacned", BaseController::$text_domain) ?></option>
                    <option value="intermediate" <?php if ($course_details->level == "intermediate") echo "selected"; ?>><?php esc_html_e("Intermediate", BaseController::$text_domain) ?></option>
                </select>
            </div>

            <div class="mxlms-form-group">
                <label for="language"><?php esc_html_e("Langauge Made In", BaseController::$text_domain) ?></label>
                <select name="language" class="mxlms-form-control mxlms-wide" id="language">
                    <?php foreach ($languages as $language) : ?>
                        <option value="<?php echo esc_attr($language->name); ?>" <?php if ($course_details->language == $language->name) echo "selected"; ?>><?php echo esc_html($language->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mxlms-form-group">
                <button type="submit" class="mxlms-btn mxlms-btn-success"><?php esc_html_e("Update Basic", BaseController::$text_domain) ?></button>
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
        jQuery('.course-basic-update-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options)
            return false;
        });

        // CALLING SOME FUNCTION WHICH NEEDS TO BE CALLED AT THE FIRST PLACE
        calculateDiscountPercentage('<?php echo esc_js($course_details->discounted_price); ?>');
        togglePriceFields();
    });

    function validate() {
        var title = jQuery('#title').val();
        var category_id = jQuery('#category_id').val();
        var sub_category_id = jQuery('#sub_category_id').val();
        var description = jQuery('#description').val();
        var short_description = jQuery('#short_description').val();


        if (title === '' || sub_category_id === '' || category_id === '' || description === '' || short_description === '') {
            mxlmsNotify("<?php esc_html_e('Required fields can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
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

    function togglePriceFields() {
        var elemName = "is_free_course";
        if (jQuery("input[name='" + elemName + "']:checked").val() === "0") {
            jQuery('.paid-course-stuffs').slideDown();
        } else
            jQuery('.paid-course-stuffs').slideUp();
    }

    function getSubCategories(categoryId) {
        mxlmsMakeAjaxCall(ajaxurl, 'subcategory/dropdown-list', 'sub_category_id', categoryId);
    }
</script>
