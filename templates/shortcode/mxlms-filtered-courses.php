<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Course;

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\User;
use Mxlms\base\BaseController;
use Mxlms\base\AjaxPosts;

// GET SELECTED CATEGORIES
if (AjaxPosts::$param1) {
    $selected_categories = AjaxPosts::$param1;
} else {
    if (filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL)) {
        $selected_categories = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL);
    } else {
        $selected_categories = "all";
    }
}

// GET SELECTED SUBCATEGORIES
if (AjaxPosts::$param2) {
    $selected_subcategories = AjaxPosts::$param2;
} else {
    if (filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL)) {
        $selected_subcategories = filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL);
    } else {
        $selected_subcategories = "all";
    }
}

// GET SELECTED PRICES
if (AjaxPosts::$param3) {
    $selected_prices = AjaxPosts::$param3;
} else {
    if (filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL)) {
        $selected_prices = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL);
    } else {
        $selected_prices = "all";
    }
}


// GET SELECTED PRICES
if (AjaxPosts::$param4) {
    $selected_sort_by = AjaxPosts::$param4;
} else {
    if (filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL)) {
        $selected_sort_by = filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL);
    } else {
        $selected_sort_by = "none";
    }
}


// GET SEARCH STRING
if (AjaxPosts::$param5) {
    $search_string = AjaxPosts::$param5;
} else {
    if (filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL)) {
        $search_string = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL);
    } else {
        $search_string = "none";
    }
}

// GET PERMALINK FOR PAGINATION
if (AjaxPosts::$param6) {
    $permalink = AjaxPosts::$param6;
} else {
    $permalink = esc_url(Helper::get_url());
}

$page_number = (isset($_GET['page_number']) && !empty($_GET['page_number'])) ?  sanitize_text_field($_GET['page_number']) : 1;

$total_number_of_courses = count(Course::filter($selected_categories, $selected_subcategories, $selected_prices, $selected_sort_by, $search_string)) ? count(Course::filter($selected_categories, $selected_subcategories, $selected_prices, $selected_sort_by, $search_string)) : 0;
$page_size = 8;
$first_page = 1;
$last_page = ceil($total_number_of_courses / $page_size);
$courses = Course::filter($selected_categories, $selected_subcategories, $selected_prices, $selected_sort_by, $search_string, $page_number, $page_size);

?>
<div class="mxlms-page-sub-header-container mxlms-mb-3">
    <span class="mxlms-page-sub-header"><?php esc_html_e('Total', BaseController::$text_domain); ?> <?php echo esc_html($total_number_of_courses); ?> <?php esc_html_e('Courses Found', BaseController::$text_domain); ?></span>
    <span class="mxlms-page-sub-header mxlms-float-right">
        <button class="mxlms-btn mxlms-btn-primary mxlms-lighten-primary course-layout-changer-btn mxlms-account mxlms-course-sort-btn" action="toggle-sorting-menu" onclick="sort()"> <?php esc_html_e('Sort By', BaseController::$text_domain); ?> <i class="las la-caret-down"></i></button>
        <div class="mxlms-dropdown" id="mxlms-sorting-menu-dropdown">
            <div class="mxlms-submenu mxlms-sorting-submenu mxlms-top-20" id="action-toggle-sorting-menu">
                <ul class="mxlms-root">
                    <li>
                        <?php if ($selected_sort_by == "latest") : ?><i class="las la-check mxlms-text-success mxlms-selected-sorting"></i><?php endif; ?>
                        <a href="javascript:void(0)" onclick="getSortValue('latest')">
                            <?php esc_html_e('Latest Course', BaseController::$text_domain); ?>
                        </a>
                    </li>
                    <li>
                        <?php if ($selected_sort_by == "rating") : ?><i class="las la-check mxlms-text-success mxlms-selected-sorting"></i><?php endif; ?>
                        <a href="javascript:void(0)" onclick="getSortValue('rating')">
                            <?php esc_html_e('Popular Course', BaseController::$text_domain); ?>
                        </a>
                    </li>
                    <li>
                        <?php $price_sorting_value = ($selected_sort_by == "price-low") ? "price-high" : "price-low"; ?>
                        <?php if ($selected_sort_by == "price-low" || $selected_sort_by == "price-high") : ?><i class="las la-check mxlms-text-success mxlms-selected-sorting"></i><?php endif; ?>
                        <a href="javascript:void(0)" onclick="getSortValue('<?php echo esc_js($price_sorting_value); ?>')">
                            <?php esc_html_e('Course Price', BaseController::$text_domain); ?>
                            <?php if ($selected_sort_by == "price-low") : ?>
                                <i class="las la-arrow-up"></i>
                            <?php else : ?>
                                <i class="las la-arrow-down"></i>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <input type="hidden" id="mxlms-sort-type" value="none">
    </span>
</div>
<div class="mxlms-row">
    <?php foreach ($courses as $key => $course) :
        $slug = Helper::slugify($course->title);
        $instructor_details = User::get_user_by_id($course->user_id);
    ?>
        <div class="mxlms-wrap mxlms-col-xl-3 mxlms-col-lg-4 mxlms-col-md-6 mxlms-col-sm-12 mxlms-mb-4">
            <div class="mxlms-course-grid-view-card">
                <div class="mxlms-course-thumbnail-on-grid-view" style="background-image: url('<?php echo esc_url(Helper::get_image($course->thumbnail, 'course_thumbnail')); ?>');" height="300" width="540"></div>
                <div class="mxlms-course-body-on-grid-view">
                    <div class="mxlms-course-title-on-grid-view">
                        <a href="<?php echo esc_url(Helper::get_url_manually($permalink, "page-contains=course-details&course=$slug&id=$course->id")); ?>"><?php echo esc_html(Helper::ellipsis($course->title, 40)); ?></a>
                    </div>
                    <div class="mxlms-instructor-intro-on-grid-view">
                        <img src="<?php echo esc_url(Helper::get_image(esc_url($instructor_details->profile_image_path))); ?>" alt="" height="20px">
                        <span class="mxlms-instructor-name-on-grid-view"><?php echo esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name); ?></span>
                    </div>
                </div>
                <div class="mxlms-course-rating-price-for-grid-view">
                    <span class="mxlms-float-left mxlms-course-star-rating">
                        <?php for ($i = 1; $i < 6; $i++) : ?>
                            <?php if ($i <= $course->avg_rating) : ?>
                                <i class="las la-star mxlms-rated"></i>
                            <?php else : ?>
                                <i class="las la-star mxlms-unrated"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </span>
                    <span class="mxlms-float-right mxlms-course-price">
                        <span class="mxlms-badge mxlms-badge-success mxlms-lighten-success">
                            <?php if ($course->is_free_course) : ?>
                                <?php esc_html_e('Free', BaseController::$text_domain); ?>
                            <?php else : ?>
                                <?php echo Helper::currency(Helper::get_course_price($course->id)); ?>
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- PAGINATION STARTS -->
<?php
if ($total_number_of_courses > count($courses)) : ?>
    <div class="mxlms-float-right">
        <div class="mxlms-pagination mxlms-pagination--right">
            <?php if ($page_number != $first_page) :
                $previous_page_number = $page_number - 1; ?>
                <a class="mxlms-page-numbers" href="<?php echo esc_url(Helper::get_url_manually($permalink, "page-contains=courses&category=$selected_categories&subcategory=$selected_subcategories&price=$selected_prices&sort-by=$selected_sort_by&page_number=$previous_page_number")); ?>">
                    <i class="las la-angle-left"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $last_page; $i++) : ?>
                <a <?php if ($page_number == $i) : ?>aria-current="page" <?php else : ?> href="<?php echo esc_url(Helper::get_url_manually($permalink, "page-contains=courses&category=$selected_categories&subcategory=$selected_subcategories&price=$selected_prices&sort-by=$selected_sort_by&page_number=$i")); ?>" <?php endif; ?> class="mxlms-page-numbers <?php if ($page_number == $i) : ?> mxlms-current <?php endif; ?>">
                    <?php echo esc_html($i); ?>
                </a>
            <?php endfor; ?>

            <?php if ($page_number != $last_page) :
                $next_page_number = $page_number + 1; ?>
                <a class="mxlms-page-numbers" href="<?php echo esc_url(Helper::get_url_manually($permalink, "page-contains=courses&category=$selected_categories&subcategory=$selected_subcategories&price=$selected_prices&sort-by=$selected_sort_by&page_number=$next_page_number")); ?>">
                    <i class="las la-angle-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<!-- PAGINATION ENDS -->