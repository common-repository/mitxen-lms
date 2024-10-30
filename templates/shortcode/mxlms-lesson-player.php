<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Lesson;
use Mxlms\base\modules\Section;
use Mxlms\base\modules\Video;

?>
<div class="col-lg-9  order-md-1 course_col" id="video_player_area">
    <div class="mxlms-text-center">
        <?php
        if ($lesson_details->lesson_type == 'video') :
            $video_url = $lesson_details->video_url;
            $provider = $lesson_details->video_provider;
        ?>

            <!-- If the video is youtube video -->
            <?php if (strtolower($provider) == 'youtube') : ?>
                <!------------- PLYR.IO ------------>

                <div class="plyr__video-embed" id="player">
                    <iframe height="500" src="<?php echo esc_url($video_url); ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                <!------------- PLYR.IO ------------>

                <!-- If the video is vimeo video -->
            <?php elseif (strtolower($provider) == 'vimeo') :
                $video_details = Video::get_video_details($video_url);
                $video_id = $video_details['video_id']; ?>
                <!------------- PLYR.IO ------------>
                <div class="plyr__video-embed" id="player">
                    <iframe src="https://player.vimeo.com/video/<?php echo esc_attr($video_id);?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
                <!------------- PLYR.IO ------------>

                <!-- If the video is self uploaded video -->
            <?php elseif (strtolower($provider) == 'system') : ?>
                <!------------- PLYR.IO ------------>
                <video poster="" id="player" playsinline controls>
                    <?php if (Helper::get_extension($video_url) == 'mp4') : ?>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                    <?php elseif (Helper::get_extension($video_url) == 'webm') : ?>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/webm">
                    <?php else : ?>
                        <h4><?php esc_html_e('Unsupported File', BaseController::$text_domain); ?></h4>
                    <?php endif; ?>
                </video>
                <!------------- PLYR.IO ------------>
                <!-- If the video is self uploaded video -->
            <?php else : ?>
                <!------------- PLYR.IO ------------>
                <video poster="" id="player" playsinline controls>
                    <?php if (Helper::get_extension($video_url) == 'mp4') : ?>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                    <?php elseif (Helper::get_extension($video_url) == 'webm') : ?>
                        <source src="<?php echo esc_url($video_url); ?>" type="video/webm">
                    <?php else : ?>
                        <h4><?php esc_html_e('Unsupported File', BaseController::$text_domain); ?></h4>
                    <?php endif; ?>
                </video>
                <!------------- PLYR.IO ------------>
            <?php endif; ?>
        <?php elseif ($lesson_details->lesson_type == "iframe") : ?>
            <div class="mxlms-iframe-container">
                <iframe src="<?php echo esc_url($lesson_details->attachment); ?>" class="mxlms-iframe" width="448" height="252" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </div>
        <?php elseif ($lesson_details->lesson_type == "document" || $lesson_details->lesson_type == "image") : ?>
            <div class="mxlms-iframe-container">
                <?php if ($lesson_details->attachment_type == "doc") : ?>
                    <div class="mxlms-no-preview-available">
                        <i class="las la-eye-slash"></i>
                        <br>
                        <?php esc_html_e('Preview is not available at this moment.', BaseController::$text_domain); ?>
                        <br>
                        <?php esc_html_e('Please download the file.', BaseController::$text_domain); ?>
                    </div>
                <?php elseif ($lesson_details->attachment_type == "img") : ?>
                    <div class="mxlms-image-lesson-preview" style="background-image: url(<?php echo esc_url($lesson_details->attachment); ?>);"></div>
                <?php else : ?>
                    <iframe src="<?php echo esc_url($lesson_details->attachment); ?>" class="mxlms-iframe" width="448" height="252" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                <?php endif; ?>

                <div class="mxlms-floating-container">
                    <a target="javascript:void(0)" class="mxlms-floating mxlms-download-btn" id="download-attachment-btn"><i class="las la-cloud-download-alt"></i> <?php esc_html_e('Download file', BaseController::$text_domain); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    "use script";

    document.addEventListener('DOMContentLoaded', function() {
        jQuery("#download-attachment-btn").on('click', downloadAttachment);
    }, false);


    function downloadAttachment() {
        let downloadLink = '<?php echo esc_url($lesson_details->attachment); ?>';
        let filename = downloadLink.split('/').pop();
        fetch(downloadLink)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = filename;
                document.body.appendChild(a);
                a.on('click', );
                window.URL.revokeObjectURL(url);

                setTimeout(() => {
                    alert('<?php esc_html_e("Your file has downloaded", BaseController::$text_domain); ?>!'); // or you know, something with better UX...
                }, 500);
            })
            .catch(() => alert('<?php esc_html_e("Downloading failed", BaseController::$text_domain); ?>'));
    }
</script>