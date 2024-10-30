<?php
defined('ABSPATH') or die('You can not access the file directly');


use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Video;
use Mxlms\base\BaseController; ?>
<!-- Modal -->
<div class="mxlms-course-preview-modal" aria-hidden="true">
    <div class="mxlms-course-preview-modal-dialog">
        <div class="mxlms-course-preview-modal-header">
            <h2><?php echo esc_html($course_details->title); ?></h2>
            <a href="#" class="btn-close mxlms-close-course-preview-modal" aria-hidden="true">&times;</a>
        </div>
        <div class="mxlms-course-preview-modal-body" id="yt-player">
            <!-- If the video is youtube video -->
            <?php if (strtolower(esc_html($course_details->preview_video_provider)) == 'youtube') : ?>
                <!------------- PLYR.IO ------------>

                <div class="plyr__video-embed" id="player">
                    <iframe height="500" src="<?php echo esc_url($course_details->preview_video_url); ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                <!------------- PLYR.IO ------------>

                <!-- If the video is vimeo video -->
            <?php elseif (strtolower(esc_html($course_details->preview_video_provider)) == 'vimeo') :
                $video_details = Video::get_video_details($course_details->preview_video_url);
                $video_id = $video_details['video_id']; ?>
                <!------------- PLYR.IO ------------>
                <div class="plyr__video-embed" id="player">
                    <iframe height="500" src="https://player.vimeo.com/video/<?php echo esc_html($video_id); ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                <!------------- PLYR.IO ------------>
            <?php else : ?>
                <!------------- PLYR.IO ------------>
                <video poster="" id="player" playsinline controls>
                    <?php if (Helper::get_extension($course_details->preview_video_url) == 'mp4') : ?>
                        <source src="<?php echo esc_url($course_details->preview_video_url); ?>" type="video/mp4">
                    <?php elseif (Helper::get_extension($course_details->preview_video_url) == 'webm') : ?>
                        <source src="<?php echo esc_url($course_details->preview_video_url); ?>" type="video/webm">
                    <?php else : ?>
                        <h4><?php esc_html_e('Unsupported File', BaseController::$text_domain); ?></h4>
                    <?php endif; ?>
                </video>
                <!------------- PLYR.IO ------------>
            <?php endif; ?>
        </div>
    </div>
</div>