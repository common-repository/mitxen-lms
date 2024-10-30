<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;
use \Mxlms\base\modules\Course;
use Mxlms\base\modules\Enrolment;
use Mxlms\base\modules\Helper;
use \Mxlms\base\modules\Section;
use \Mxlms\base\modules\Lesson;
use \Mxlms\base\modules\User;

use Mxlms\base\modules\Review;

$course_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_URL);
$course_details = Course::get_course_details_by_id($course_id);
$instructor_details = User::get_user_by_id($course_details->user_id);
$sections = Section::get_sections($course_id);

$outcomes = ($course_details->outcomes && count(json_decode($course_details->outcomes))) ? json_decode($course_details->outcomes) : [];
$requirements = ($course_details->requirements && count(json_decode($course_details->requirements))) ? json_decode($course_details->requirements) : [];
$avg_rating = Review::get_course_rating($course_id);
$all_ratings_and_reviews = Review::get_course_review_and_ratings($course_id);
$all_ratings_and_reviews = $all_ratings_and_reviews ? $all_ratings_and_reviews : array();
?>

<div class="mxlms-wrapper">
  <div class="mxlms-preloader"></div>

  <div class="mxlms-container-fluid mxlms-course-details mxlms-page-content mxlms-hidden">
    <?php include 'mxlms-page-navbar.php'; ?>
    <div class="mxlms-row mxlms-mb-3">
      <div class="mxlms-col-lg-9">
        <span class="mxlms-back">
          <a href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=courses') ); ?>"><i class="las la-caret-left"></i>
            <?php esc_html_e('Back', BaseController::$text_domain); ?>
          </a>
        </span>
        <div class="mxlms-h2"><?php echo esc_html($course_details->title); ?></div>
        <div class="mxlms-row">
          <div class="mxlms-col mxlms-p-relative">
            <div class="mxlms-instructor-intro mxlms-d-inline-block">
              <img src="<?php echo esc_url(Helper::get_image($instructor_details->profile_image_path)); ?>" alt="" height="20px">
              <div class="mxlms-instructor-name"><?php echo esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name); ?></div>
            </div>
            <div class="mxlms-course-star-rating mxlms-d-inline-block">
              <?php for ($i = 1; $i < 6; $i++) : ?>
                <?php if ($i <= $avg_rating) : ?>
                  <i class="las la-star mxlms-rated"></i>
                <?php else : ?>
                  <i class="las la-star mxlms-unrated"></i>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
            <div class="mxlms-course-rating mxlms-d-inline-block">
              <?php echo sprintf("%.1f", $avg_rating); ?>
            </div>
            <div class="mxlms-course-number-of-ratings mxlms-d-inline-block">
              (<?php echo count($all_ratings_and_reviews); ?> <?php esc_html_e('Ratings', BaseController::$text_domain); ?>)
            </div>
          </div>
        </div>
      </div>
      <div class="mxlms-col-lg-3" id="mxlms-wishlist-ribbon-area">
        <?php include "mxlms-wishlist-ribbon.php"; ?>
      </div>
    </div>
    <div class="mxlms-row">
      <div class="mxlms-col-lg-9">
        <div class="mxlms-card-no-shadow">
          <div class="mxlms-course-banner">
            <img src="<?php echo Helper::get_image(esc_url($course_details->banner), "course_banner"); ?>" alt="">
          </div>
        </div>
        <div class="mxlms-mt-5">
          <span class="mxlms-section-header"><?php esc_html_e("Course Description", BaseController::$text_domain); ?></span>
          <div class="mxlms-course-details-body-text mxlms-text-justify mxlms-py-3">
            <?php echo esc_html($course_details->description); ?>
          </div>
        </div>
        <div class="mxlms-mt-5">
          <span class="mxlms-section-header"><?php esc_html_e("What will I learn?", BaseController::$text_domain); ?></span>
          <div class="mxlms-row">
            <?php foreach ($outcomes as $key => $outcome) : ?>
              <div class="mxlms-col-md-6 mxlms-course-outcomes"> <i class="las la-gift"></i> <?php echo esc_html($outcome); ?></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="mxlms-mt-5">
          <span class="mxlms-section-header"><?php esc_html_e("What are the requirements?", BaseController::$text_domain); ?></span>
          <div class="mxlms-row">
            <?php foreach ($requirements as $key => $requirement) : ?>
              <div class="mxlms-col-md-6 mxlms-course-requirements"> <i class="las la-pen-nib"></i> <?php echo esc_html($requirement); ?></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="mxlms-mt-5 mxlms-course-lessons" id="mxlms-accordion">
          <span class="mxlms-section-header"><?php esc_html_e("Curriculum for this course", BaseController::$text_domain); ?></span>
          <ul class="mxlms-accordion">
            <?php foreach ($sections as $key => $section) :
              $lessons = Lesson::get_lessons_by_section_id($section->id); ?>
              <li class="mxlms-filter-card">
                <a class="mxlms-accordion-toggle" href=#>
                  <?php esc_html_e('Section', BaseController::$text_domain); ?> : <?php echo esc_html($section->title); ?> <i class="las la-chevron-<?php if ($key == 0) echo 'up';
                                                                                                                                                      else echo 'down'; ?> mxlms-float-right"></i>
                </a>
                <p class="inner <?php if ($key == 0) echo 'show'; ?>">
                  <?php
                  foreach ($lessons as $key => $lesson) : ?>
                    <span class="mxlms-lesson-title">
                      <?php if ($lesson->lesson_type == "video") : ?>
                        <i class="lar la-play-circle"></i> <?php echo esc_html($lesson->title); ?>
                      <?php elseif ($lesson->lesson_type == "iframe") : ?>
                        <i class="las la-code"></i> <?php echo esc_html($lesson->title); ?>
                      <?php elseif ($lesson->lesson_type == "document") : ?>
                        <i class="las fa-paperclip"></i> <?php echo esc_html($lesson->title); ?>
                      <?php elseif ($lesson->lesson_type == "image") : ?>
                        <i class="lar fa-image"></i> <?php echo esc_html($lesson->title); ?>
                      <?php endif; ?>

                      <span class="mxlms-lesson-duration">
                        <?php echo esc_html($lesson->duration); ?>
                      </span>
                    </span>
                  <?php endforeach; ?>
                </p>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="mxlms-col-lg-3">
        <div class="mxlms-card mxlms-course-summary">
          <div class="mxlms-card-header">
            <p>
              <?php esc_html_e('Course Summary', BaseController::$text_domain); ?>
            </p>
          </div>
          <div class="mxlms-card-body">
            <div class="mxlms-course-specifications">
              <img src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/calendar.png')); ?>" alt="">
              <p>
                <?php echo date('d F Y', $course_details->date_added); ?>
              </p>
            </div>
            <div class="mxlms-course-specifications">
              <img src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/globe.png')); ?>" alt="">
              <p>
                <?php echo ucfirst(esc_html($course_details->language)); ?>
              </p>
            </div>
            <div class="mxlms-course-specifications">
              <img src="<?php echo esc_url(Helper::get_plugin_url('assets/frontend/img/graduation-cap.png')); ?>" alt="">
              <p>
                <?php echo count(Enrolment::course_wise_enrolment($course_details->id)); ?> <?php esc_html_e('Students enrolled', BaseController::$text_domain); ?>
              </p>
            </div>

            <div class="mxlms-course-price">
              <?php if ($course_details->is_free_course) : ?>
                <?php esc_html_e('Free', BaseController::$text_domain); ?>
              <?php else : ?>
                <?php echo Helper::currency(Helper::get_course_price($course_details->id)); ?>
              <?php endif; ?>
            </div>

            <div class="mxlms-course-specifications-buttons">
              <?php if (!empty(esc_url($course_details->preview_video_url))) : ?>
                <button class="mxlms-btn mxlms-btn-primary mxlms-lighten-primary mxlms-btn-block mxlms-mb-3 mxlms-open-course-preview-modal mxlms-course-summary-button">
                  <i class="lar la-eye"></i> <?php esc_html_e('Preview Course', BaseController::$text_domain); ?>
                </button>
              <?php endif; ?>
              <?php if (Helper::is_purchased($course_id)) : ?>
                <a href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=my-courses') ); ?>" class="mxlms-btn mxlms-btn-primary mxlms-lighten-primary-active mxlms-btn-block mxlms-course-summary-button">
                  <i class="las la-check-double"></i> <?php esc_html_e('ALREADY PURCHASED ', BaseController::$text_domain); ?></a>
              <?php else : ?>
                <?php if ($course_details->is_free_course) : ?>
                  <a href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=free-enrolment&course-id=' . $course_id) ); ?>" class="mxlms-btn mxlms-btn-primary mxlms-lighten-primary-active mxlms-btn-block mxlms-course-summary-button">
                    <i class="las la-graduation-cap"></i> <?php esc_html_e('GET ENROLLED', BaseController::$text_domain); ?></a>
                <?php else : ?>
                  <a href="<?php echo esc_url( \Mxlms\base\modules\Helper::get_url('page-contains=payment-gateways&course-id=' . $course_id) ); ?>" class="mxlms-btn mxlms-btn-primary mxlms-lighten-primary-active mxlms-btn-block mxlms-course-summary-button">
                    <i class="las la-shopping-cart"></i> <?php esc_html_e('BUY', BaseController::$text_domain); ?></a>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php require "mxlms-course-preview.php"; ?>
    <?php include "$this->plugin_path/templates/shortcode/modal/index.php"; ?>
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