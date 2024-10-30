<?php
defined('ABSPATH') or die('You can not access the file directly');

use \Mxlms\base\BaseController;


$video_url = isset($lesson_details->video_url) ? $lesson_details->video_url : "";
$duration  = isset($lesson_details->duration) ? $lesson_details->duration : "";
?>
<input type="hidden" name="lesson_type" value="video">
<input type="hidden" name="video_provider" value="youtube">

<div class="mxlms-form-group">
  <label for="video_url"><?php esc_html_e('YouTube Video URL', BaseController::$text_domain); ?></label>
  <input type="text" name="video_url" class="form-control" id="video_url" aria-describedby="video_url" placeholder="<?php esc_html_e('Provide YouTube video URL', BaseController::$text_domain); ?>" value="<?php echo esc_url($video_url); ?>">
</div>

<div class="mxlms-text-danger mxlms-mb-1 mxlms-analyzing-video-url mxlms-hidden" id="video-url-validity-message">
  <i class="las la-spinner"></i> <?php esc_html_e('Analyzing Your Video URL', BaseController::$text_domain); ?>
</div>

<div class="mxlms-text-danger mxlms-mb-1 mxlms-invalid-video-url mxlms-hidden" id="invalid-video-url-message">
  <i class="las la-ban"></i> <?php esc_html_e('Invalid Video URL', BaseController::$text_domain); ?>
</div>
<br>

<div class="mxlms-form-group">
  <label for="duration"><?php esc_html_e('YouTube Video Duration', BaseController::$text_domain); ?></label>
  <input type="text" name="duration" class="form-control" id="duration" aria-describedby="duration" placeholder="00:00:00" value="<?php echo esc_attr($duration); ?>">
</div>