<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;

$parent_categories = \Mxlms\base\modules\Category::get_parent_categories();
$sub_categories = \Mxlms\base\modules\Category::get_sub_categories();


// GET SELECTED CATEGORIES
$selected_categories_slugs = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL)) : array();
$selected_subcategories_slugs = filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL)) : array();
$selected_prices_slugs = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL)) : array();
?>

<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-courses-list mxlms-page-content mxlms-hidden">
        <?php include 'mxlms-page-navbar.php'; ?>
        <div class="mxlms-h5">
            <?php esc_html_e('All Courses', BaseController::$text_domain); ?>
        </div>
        <div class="mxlms-row">
            <div class="mxlms-wrap mxlms-col-xl-3 mxlms-col-lg-3 mxlms-col-md-12 mxlms-col-sm-12">
                <div class="" id="mxlms-accordion">
                    <span class="mxlms-page-sub-header mxlms-filter-title"><?php esc_html_e('Filter', BaseController::$text_domain); ?></span>
                    <ul class="mxlms-accordion mxlms-mb-30">
                        <li class="mxlms-filter-card">
                            <a class="mxlms-accordion-toggle" href=#>
                                <?php esc_html_e('Categories', BaseController::$text_domain); ?> <i class="las la-chevron-up mxlms-float-right"></i>
                            </a>
                            <p class="show">
                                <?php foreach ($parent_categories as $parent_category) : ?>
                                    <input type="checkbox" id="<?php echo esc_attr($parent_category->id); ?>" name="filter-cateogries" value="<?php echo esc_attr($parent_category->slug); ?>" class="mxlms-available-categories mxlms-filter-attribute" <?php if (in_array($parent_category->slug, $selected_categories_slugs)) echo "checked"; ?>>
                                    <label for="<?php echo esc_attr($parent_category->id); ?>"><?php echo esc_html($parent_category->title); ?></label>
                                <?php endforeach; ?>
                            </p>
                        </li>

                        <li class="mxlms-filter-card">
                            <a class="mxlms-accordion-toggle" href=#>
                                <?php esc_html_e('Sub Categories', BaseController::$text_domain); ?> <i class="las la-chevron-down mxlms-float-right"></i>
                            </a>
                            <p class="inner">
                                <?php foreach ($sub_categories as $sub_category) : ?>
                                    <input type="checkbox" id="<?php echo esc_attr($sub_category->id); ?>" name="filter-cateogries" value="<?php echo esc_attr($sub_category->slug); ?>" class="mxlms-available-subcategories mxlms-filter-attribute" <?php if (in_array($sub_category->slug, $selected_subcategories_slugs)) echo "checked"; ?>>
                                    <label for="<?php echo esc_attr($sub_category->id); ?>"><?php echo esc_html($sub_category->title); ?></label>
                                <?php endforeach; ?>
                            </p>
                        </li>

                        <li class="mxlms-filter-card">
                            <a class="mxlms-accordion-toggle" href=#>
                                <?php esc_html_e('Price', BaseController::$text_domain); ?> <i class="las la-chevron-down mxlms-float-right"></i>
                            </a>
                            <p class="inner">
                                <input type="checkbox" id="price-1" name="filter-cateogries" value="free" class="mxlms-available-prices mxlms-filter-attribute" <?php if (in_array("free", $selected_prices_slugs)) echo "checked"; ?>>
                                <label for="price-1"><?php esc_html_e('Free', BaseController::$text_domain); ?></label>
                                <input type="checkbox" id="price-2" name="filter-cateogries" value="paid" class="mxlms-available-prices mxlms-filter-attribute" <?php if (in_array("paid", $selected_prices_slugs)) echo "checked"; ?>>
                                <label for="price-2"><?php esc_html_e('Paid', BaseController::$text_domain); ?></label>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mxlms-wrap mxlms-col-xl-9 mxlms-col-lg-9 mxlms-col-md-12 mxlms-col-sm-12" id="mxlms-filtered-courses-area">
                <?php require "mxlms-filtered-courses.php"; ?>
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

            jQuery('.mxlms-filter-attribute').on("change", function() {
                filterCourses();
            });
        }, 500);
    }, false);

    function sort() {
        // MXLMS-DROPDOWN-FOR-FRONTEND
        var X = jQuery('.mxlms-course-sort-btn').attr("id");
        let action = jQuery('.mxlms-course-sort-btn').attr("action");
        if (action) {
            if (X == 1) {
                jQuery(".mxlms-submenu#action-" + action).hide();
                jQuery('.mxlms-course-sort-btn').attr("id", "0");
            } else {
                jQuery(".mxlms-submenu#action-" + action).show();
                jQuery('.mxlms-course-sort-btn').attr("id", "1");
            }
        }
    }

    function filterCourses() {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        let data = get_url();
        mxlmsMakeAjaxCall(ajaxurl, 'mxlms-filtered-courses', 'mxlms-filtered-courses-area', data['selectedCategories'], data['selectedSubCategories'], data['selectedPrices'], data['selectedSortBy'], data['searchString'], '<?php echo esc_js(esc_url(Helper::get_url())); ?>');
    }

    function get_url() {
        var urlPrefix = '<?php echo esc_js(esc_url(Helper::get_url('page-contains=courses'))); ?>'
        var urlSuffix = "";
        var selectedCategories = "all";
        var selectedSubCategories = "all";
        var selectedPrices = "all";
        var selectedSortBy = "none";
        var searchString = "none";

        // Get selected category
        jQuery('.mxlms-available-categories:checked').each(function() {
            if (selectedCategories === "all") {
                selectedCategories = jQuery(this).attr('value');
            } else {
                selectedCategories = selectedCategories + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected subcategory
        jQuery('.mxlms-available-subcategories:checked').each(function() {
            if (selectedSubCategories === "all") {
                selectedSubCategories = jQuery(this).attr('value');
            } else {
                selectedSubCategories = selectedSubCategories + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected price
        jQuery('.mxlms-available-prices:checked').each(function() {
            if (selectedPrices === "all") {
                selectedPrices = jQuery(this).attr('value');
            } else {
                selectedPrices = selectedPrices + "--" + jQuery(this).attr('value');
            }
        });

        // GET SEARCH STRING
        searchString = '<?php echo $search_string; ?>'

        // Get selected sort
        selectedSortBy = jQuery('#mxlms-sort-type').val();


        urlSuffix = "&category=" + selectedCategories + "&subcategory=" + selectedSubCategories + "&price=" + selectedPrices + "&search=" + searchString + "&sort-by=" + selectedSortBy + "&page_number=1";

        var url = urlPrefix + urlSuffix;
        window.history.pushState("string", '<?php esc_html_e("Course Filter", BaseController::$text_domain); ?>', url);

        let returningArray = [];
        returningArray['selectedCategories'] = selectedCategories;
        returningArray['selectedSubCategories'] = selectedSubCategories;
        returningArray['selectedPrices'] = selectedPrices;
        returningArray['selectedSortBy'] = selectedSortBy;
        returningArray['searchString'] = searchString;
        returningArray['url'] = url;
        return returningArray;
    }

    function getSortValue(sortBy) {
        jQuery('#mxlms-sort-type').val(sortBy);
        filterCourses();
    }
</script>