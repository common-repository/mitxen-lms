<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Student;


$active_tab = (isset($_GET['tab']) && sanitize_text_field($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'basic';
$student_id = sanitize_text_field($_GET['student-id']) ? sanitize_text_field($_GET['student-id']) : 0;
?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Update Student', BaseController::$text_domain); ?>
                        </span>
                        <a href="admin.php?page=mxlms-students&page-contains=student-list" class="mxlms-btn mxlms-btn-primary mxlms-title-btn">
                            <i class="las la-long-arrow-alt-left"></i> <?php esc_html_e("Back To Student List", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-lg-6">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('Student Update Form', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <?php if ($student_id > 0) : ?>
                        <?php

                        $student_details = Student::get_student_by_id($student_id);
                        $wp_userdata  = get_userdata($student_details->wp_user_id);
                        $paypal_keys  = json_decode($student_details->paypal_keys, true);
                        $stripe_keys  = json_decode($student_details->stripe_keys, true);
                        $social_links = json_decode($student_details->social_links, true);
                        ?>
                        <div class="mxlms-tabset">
                            <!-- Tab 1 -->
                            <input type="radio" name="tabset" id="basic" aria-controls="basic" <?php if ($active_tab == "basic") echo "checked"; ?>>
                            <label for="basic">
                                <i class="las la-info-circle"></i>
                                <span class="mxlms-tab-title"><?php esc_html_e('Basic info', BaseController::$text_domain); ?></span>
                            </label>
                            <!-- Tab 2 -->
                            <input type="radio" name="tabset" id="login-info" aria-controls="login-info" <?php if ($active_tab == "login-info") echo "checked"; ?>>
                            <label for="login-info">
                                <i class="las la-user-lock"></i>
                                <span class="mxlms-tab-title"><?php esc_html_e('Login info', BaseController::$text_domain); ?></span>
                            </label>
                            <!-- Tab 3 -->
                            <input type="radio" name="tabset" id="social-info" aria-controls="social-info" <?php if ($active_tab == "social-info") echo "checked"; ?>>
                            <label for="social-info">
                                <i class="lab la-facebook"></i>
                                <span class="mxlms-tab-title"><?php esc_html_e('Social info', BaseController::$text_domain); ?></span>
                            </label>
                            <!-- Tab 5 -->
                            <input type="radio" name="tabset" id="finish" aria-controls="finish" <?php if ($active_tab == "finish") echo "checked"; ?>>
                            <label for="finish">
                                <i class="las la-thumbs-up"></i>
                                <span class="mxlms-tab-title"><?php esc_html_e('Finish', BaseController::$text_domain); ?></span>
                            </label>
                            <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form student-edit-form' enctype='multipart/form-data' autocomplete="off">
                                <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_student'; ?>">
                                <input type="hidden" name="task" value="edit_student">
                                <input type="hidden" name="edit_student_nonce" value="<?php echo wp_create_nonce('edit_student_nonce'); ?>"> <!-- kind of csrf token-->
                                <input type="hidden" name="id" value="<?php echo esc_attr($student_id); ?>">
                                <div class="mxlms-tab-panels">
                                    <section id="basic" class="mxlms-tab-panel">
                                        <div class="mxlms-form-group">
                                            <label for="firstname"><?php esc_html_e("First name", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
                                            <input type="text" class="mxlms-form-control" id="firstname" name="firstname" aria-describedby="firstname" placeholder="<?php esc_html_e("Enter Firstname", BaseController::$text_domain) ?>" value="<?php echo esc_attr($student_details->first_name); ?>">
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="lastname"><?php esc_html_e("Last name", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
                                            <input type="text" class="mxlms-form-control" id="lastname" name="lastname" aria-describedby="lastname" placeholder="<?php esc_html_e("Enter Lastname", BaseController::$text_domain) ?>" value="<?php echo esc_attr($student_details->last_name); ?>">
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="username"><?php esc_html_e("Username", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
                                            <input type="text" class="mxlms-form-control" id="username" placeholder="<?php esc_html_e("Enter Unique Username", BaseController::$text_domain) ?>" value="<?php echo esc_attr($wp_userdata->user_nicename); ?>" disabled>
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="biography"><?php esc_html_e("Biography", BaseController::$text_domain) ?>
                                                <span class="mxlms-anim"><i class="las la-question-circle"></i>
                                                    <span class="mxlms-popover">
                                                        <?php esc_html_e('Write down a short biography', BaseController::$text_domain); ?>
                                                    </span>
                                                </span>
                                            </label>
                                            <textarea class="mxlms-form-control" id="biography" name="biography" aria-describedby="biography" placeholder="<?php esc_html_e("Enter Biography", BaseController::$text_domain) ?>"><?php echo esc_textarea($student_details->biography); ?></textarea>
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="student_image_upload mxlms-w-100">
                                                <?php esc_html_e("Upload Student Image", BaseController::$text_domain) ?>
                                                <span class="mxlms-anim"><i class="las la-question-circle"></i>
                                                    <span class="mxlms-popover">
                                                        <?php esc_html_e('The image size should be', BaseController::$text_domain); ?> 500 X 500
                                                    </span>
                                                </span>
                                            </label>
                                            <div class="mxlms-image-uploader">
                                                <i class="las la-pencil-alt"></i>
                                                <img src="<?php echo esc_url(Helper::get_image($student_details->profile_image_path)); ?>" alt="" id="student_image_upload" height="150" width="150">
                                            </div>
                                            <input type="hidden" name="student_image_path" id="student_image_path" value="<?php echo esc_url(Helper::get_image($student_details->profile_image_path)); ?>">
                                        </div>

                                    </section>
                                    <section id="login-info" class="mxlms-tab-panel">
                                        <div class="mxlms-form-group">
                                            <label for="email"><?php esc_html_e("Email", BaseController::$text_domain) ?></label><span class="mxlms-text-danger">*</span>
                                            <input type="email" class="mxlms-form-control" id="email" name="email" aria-describedby="email" placeholder="<?php esc_html_e("Enter Email", BaseController::$text_domain) ?>" value="<?php echo esc_attr($student_details->email); ?>">
                                        </div>
                                    </section>
                                    <section id="social-info" class="mxlms-tab-panel">
                                        <div class="mxlms-form-group">
                                            <label for="facebook_link"><?php esc_html_e("Facebook Link", BaseController::$text_domain) ?></label>
                                            <input type="text" class="mxlms-form-control" id="facebook_link" name="facebook_link" aria-describedby="facebook_link" placeholder="<?php esc_html_e("Facebook Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['facebook']); ?>">
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="twitter_link"><?php esc_html_e("Twitter Link", BaseController::$text_domain) ?></label>
                                            <input type="text" class="mxlms-form-control" id="twitter_link" name="twitter_link" aria-describedby="twitter_link" placeholder="<?php esc_html_e("Twitter Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['twitter']); ?>">
                                        </div>
                                        <div class="mxlms-form-group">
                                            <label for="linkedin_link"><?php esc_html_e("Linkedin Link", BaseController::$text_domain) ?></label>
                                            <input type="text" class="mxlms-form-control" id="linkedin_link" name="linkedin_link" aria-describedby="linkedin_link" placeholder="<?php esc_html_e("Linkedin Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['linkedin']); ?>">
                                        </div>
                                    </section>
                                    <section id="finish" class="mxlms-tab-panel mxlms-text-center">
                                        <i class="lar la-check-circle font-40 mxlms-text-success"></i>
                                        <div class="mxlms-h3">
                                            <?php esc_html_e("You are almost there", BaseController::$text_domain) ?>, <?php esc_html_e("Just one click away", BaseController::$text_domain) ?>...
                                            <div class="mxlms-form-group mxlms-mt-4">
                                                <button type="submit" class="mxlms-btn mxlms-btn-success mxlms-btn-md"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </form>
                        </div>
                    <?php else : ?>
                        <div class="mxlms-alert mxlms-alert-warning"><?php esc_html_e('Invalid student id', BaseController::$text_domain); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    jQuery(document).ready(function($) {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.student-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var firstname = jQuery('#firstname').val();
        var lastname = jQuery('#lastname').val();
        var email = jQuery('#email').val();
        var password = jQuery('#password').val();

        if (username === '' || firstname === '' || lastname === '' || email === '' || password === '') {
            mxlmsNotify("<?php esc_html_e('Required field can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.student-add-form').trigger('reset');
            mxlmsNotify(response.message, 'success');
        } else {
            mxlmsNotify(response.message, 'warning');
        }
    }

    jQuery('.mxlms-image-uploader').on('click', function(e) {

        var mediaUploader;
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: "<?php esc_html_e('Choose File', BaseController::$text_domain) ?>",
            button: {
                text: "<?php esc_html_e('Choose File', BaseController::$text_domain) ?>"
            },
            multiple: false

        });
        mediaUploader.open();

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            jQuery('#student_image_path').val(attachment.url);
            jQuery('#student_image_upload').attr('src', attachment.url);
            jQuery('#student_image_upload').show();
            jQuery('.mxlms-image-uploader i').hide();
        });
    });
</script>
