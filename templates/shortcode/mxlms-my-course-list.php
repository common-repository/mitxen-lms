<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\AjaxPosts;
use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;
use Mxlms\base\modules\Enrolment;

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Review;
use Mxlms\base\modules\User;

$total_number_of_courses = count(Enrolment::get_my_courses());
$page_size = 8;
$first_page = 1;
$last_page = ceil($total_number_of_courses / $page_size);
$page_number = (isset($_GET['page_number']) && !empty($_GET['page_number'])) ?  sanitize_text_field($_GET['page_number']) : 1;
$my_courses = Enrolment::get_my_courses($page_number, $page_size);

// GET PERMALINK FOR PAGINATION
if (AjaxPosts::$param1) {
    $permalink = AjaxPosts::$param1;
} else {
    $permalink = esc_url( Helper::get_url() );
}
?>

<div class="mxlms-page-sub-header-container">
    <span class="mxlms-page-sub-header"><?php esc_html_e('Total', BaseController::$text_domain); ?> <?php echo esc_html($total_number_of_courses); ?> <?php esc_html_e('Courses Found', BaseController::$text_domain); ?></span>
</div>
<div class="mxlms-row">
    <?php foreach ($my_courses as $key => $my_course) :
        $course = Course::get_course_details_by_id($my_course->course_id);
        $slug = Helper::slugify($course->title);
        $instructor_details = User::get_user_by_id($course->user_id);
        $avg_rating = Review::get_course_rating($my_course->course_id);
        $my_rating_and_review = Review::get_user_wise_course_review($my_course->course_id, Helper::get_current_user_id());
        $course_progress = Helper::course_progress($course->id);
    ?>
        <div class="mxlms-wrap mxlms-col-xl-3 mxlms-col-lg-4 mxlms-col-md-6 mxlms-col-sm-12 mxlms-mb-4">
            <div class="mxlms-course-grid-view-card">
                <div class="mxlms-course-thumbnail-on-grid-view" style="background-image: url('<?php echo esc_url(Helper::get_image($course->thumbnail)); ?>');" height="300" width="540"></div>
                <div class="mxlms-course-body-on-grid-view">
                    <div class="mxlms-course-title-on-grid-view">
                        <a href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=lessons&course=$slug&course-id=$course->id") ); ?>"><?php echo esc_html(Helper::ellipsis($course->title, 40)); ?></a>
                    </div>
                    <div class="mxlms-instructor-intro-on-grid-view">
                        <div class="mxlms-row">
                            <div class="mxlms-col-md-9">
                                <img src="<?php echo Helper::get_image(esc_url($instructor_details->profile_image_path)); ?>" alt="">
                                <span class="mxlms-instructor-name-on-grid-view"><?php echo esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name); ?></span>
                            </div>
                            <div class="mxlms-col-md-3 mxlms-text-right">
                                <a href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=course-details&course=$slug&id=$course->id") ); ?>" class="mxlms-link-unset">
                                    <span mxlms-tooltip="<?php esc_html_e('Course Details', BaseController::$text_domain); ?>" class="mxlms-font-20">
                                        <i class="las la-info-circle mxlms-text-secondary"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PROGRESS BAR STARTS -->
                <span mxlms-tooltip="<?php echo esc_html($course_progress); ?>% <?php esc_html_e('Completed', BaseController::$text_domain); ?>" class="mxlms-font-20">
                    <div class="mxlms-progress">
                        <div class="mxlms-bar" style="width:<?php echo esc_html($course_progress); ?>%">
                            <p class="mxlms-percent"></p>
                        </div>
                    </div>
                </span>
                <!-- PROGRESS BAR ENDS -->
                <div class="mxlms-course-rating-price-for-grid-view">
                    <span class="mxlms-float-left mxlms-course-star-rating" id="mxlms-rating-area-<?php echo esc_attr($course->id); ?>">
                        <?php for ($i = 1; $i < 6; $i++) : ?>
                            <?php if ( isset($my_rating_and_review->rating) && $i <= $my_rating_and_review->rating) : ?>
                                <i class="las la-star mxlms-rated"></i>
                            <?php else : ?>
                                <i class="las la-star mxlms-unrated"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </span>
                    <span class="mxlms-course-price">
                        <a href="javascript:void(0)" class="mxlms-text-secondary mxlms-text-decoration-none" id="mxlms-edit-review-btn-<?php echo esc_attr($course->id); ?>" onclick="showReviewArea('<?php echo esc_js($course->id); ?>')">
                            <span mxlms-tooltip="<?php esc_html_e('Update Rating and Review', BaseController::$text_domain); ?>" class="mxlms-font-20 mxlms-p-0">
                                <i class="las la-pencil-alt mxlms-edit-rating-icon"></i>
                            </span>
                        </a>
                        <a href="javascript:void(0)" id="mxlms-cancel-review-btn-<?php echo esc_attr($course->id); ?>" onclick="hideReviewArea('<?php echo esc_js($course->id); ?>')" class="mxlms-hidden mxlms-text-secondary">
                            <span mxlms-tooltip="<?php esc_html_e('Cancel', BaseController::$text_domain); ?>" class="mxlms-font-20 mxlms-p-0">
                                <i class="las la-times mxlms-edit-rating-icon"></i>
                            </span>
                        </a>
                    </span>
                    <span class="mxlms-float-right mxlms-course-price">
                        <a href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=lessons&course=$slug&course-id=$course->id") ); ?>">
                            <span class="mxlms-badge mxlms-badge-primary">
                                <?php if ($course_progress && $course_progress > 0) : ?>
                                    <i class="las la-play"></i> <?php esc_html_e('Continue', BaseController::$text_domain); ?>
                                <?php else : ?>
                                    <i class="las la-play"></i> <?php esc_html_e('Start', BaseController::$text_domain); ?>
                                <?php endif; ?>
                            </span>
                        </a>
                    </span>

                    <div class="mxlms-course-rating-edit-area mxlms-hidden" id="mxlms-course-rating-edit-area-<?php echo esc_attr($course->id); ?>">
                        <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form rating-update-form' enctype='multipart/form-data' autocomplete="off">
                            <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_review'; ?>">
                            <input type="hidden" name="task" value="edit_review">
                            <input type="hidden" name="edit_review_nonce" value="<?php echo wp_create_nonce('edit_review_nonce'); ?>"> <!-- kind of csrf token-->
                            <input type="hidden" name="course_id" value="<?php echo esc_attr($course->id); ?>">
                            <div class="mxlms-form-group">
                                <textarea name="review" class="mxlms-form-control" rows="2" placeholder="<?php esc_html_e('Provide a good review', BaseController::$text_domain); ?>"><?php echo isset($my_rating_and_review->review) ? esc_html($my_rating_and_review->review) : ""; ?></textarea>
                            </div>

                            <div class="mxlms-form-group">
                                <select name="rating" class="mxlms-wide" id="my-course-rating">
                                    <option value=1 <?php if (isset($my_rating_and_review->rating) && $my_rating_and_review->rating == 1) echo "selected"; ?>>1</option>
                                    <option value=2 <?php if (isset($my_rating_and_review->rating) && $my_rating_and_review->rating == 2) echo "selected"; ?>>2</option>
                                    <option value=3 <?php if (isset($my_rating_and_review->rating) && $my_rating_and_review->rating == 3) echo "selected"; ?>>3</option>
                                    <option value=4 <?php if (isset($my_rating_and_review->rating) && $my_rating_and_review->rating == 4) echo "selected"; ?>>4</option>
                                    <option value=5 <?php if (isset($my_rating_and_review->rating) && $my_rating_and_review->rating == 5) echo "selected"; ?>>5</option>
                                </select>
                            </div>

                            <button type="submit" class="mxlms-button mxlms-blue mxlms-block mxlms-round mxlms-text-decoration-none"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- PAGINATION STARTS -->
<?php
if ($total_number_of_courses > count($my_courses)) : ?>
    <div class="mxlms-float-right">
        <div class="mxlms-pagination mxlms-pagination--right">
            <?php if ($page_number != $first_page) :
                $previous_page_number = $page_number - 1; ?>
                <a class="mxlms-page-numbers" href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=my-courses&page_number=$previous_page_number") ); ?>">
                    <i class="las la-angle-left"></i>
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $last_page; $i++) : ?>
                <a <?php if ($page_number == $i) : ?>aria-current="page" <?php else : ?> href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=my-courses&tab=&page_number=$i") ); ?>" <?php endif; ?> class="mxlms-page-numbers <?php if ($page_number == $i) : ?> mxlms-current <?php endif; ?>">
                    <?php echo esc_html($i); ?>
                </a>
            <?php endfor; ?>

            <?php if ($page_number != $last_page) :
                $next_page_number = $page_number + 1; ?>
                <a class="mxlms-page-numbers" href="<?php echo esc_url( Helper::get_url_manually($permalink, "page-contains=my-courses&page_number=$next_page_number") ); ?>">
                    <i class="las la-angle-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<!-- PAGINATION ENDS -->