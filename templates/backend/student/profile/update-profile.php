<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;

use Mxlms\base\modules\User;

$profile_data = User::get_logged_in_user_details();
$social_links = json_decode($profile_data->social_links, true);
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='mxlms-form update-profile-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_profile'; ?>">
    <input type="hidden" name="task" value="update_profile">
    <input type="hidden" name="update_profile_nonce" value="<?php echo wp_create_nonce('update_profile_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="id" value="<?php echo esc_attr($profile_data->id); ?>">

    <div class="mxlms-form-group">
        <label for="firstname"><?php esc_html_e("First name", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
        <input type="text" class="mxlms-form-control" id="firstname" name="firstname" aria-describedby="firstname" placeholder="<?php esc_html_e("Enter Firstname", BaseController::$text_domain) ?>" value="<?php echo esc_attr($profile_data->first_name); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="lastname"><?php esc_html_e("Last name", BaseController::$text_domain) ?></label> <span class="mxlms-text-danger">*</span>
        <input type="text" class="mxlms-form-control" id="lastname" name="lastname" aria-describedby="lastname" placeholder="<?php esc_html_e("Enter Lastname", BaseController::$text_domain) ?>" value="<?php echo esc_attr($profile_data->last_name); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="email"><?php esc_html_e("Email", BaseController::$text_domain) ?></label><span class="mxlms-text-danger">*</span>
        <input type="email" class="mxlms-form-control" id="email" name="email" aria-describedby="email" placeholder="<?php esc_html_e("Enter Email", BaseController::$text_domain) ?>" value="<?php echo esc_attr($profile_data->email); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="facebook"><?php esc_html_e("Facebook Link", BaseController::$text_domain) ?></label>
        <input type="text" class="mxlms-form-control" id="facebook" name="facebook" aria-describedby="facebook" placeholder="<?php esc_html_e("Facebook Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['facebook']); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="twitter"><?php esc_html_e("Twitter Link", BaseController::$text_domain) ?></label>
        <input type="text" class="mxlms-form-control" id="twitter" name="twitter" aria-describedby="twitter" placeholder="<?php esc_html_e("Twitter Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['twitter']); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="linkedin"><?php esc_html_e("Linkedin Link", BaseController::$text_domain) ?></label>
        <input type="text" class="mxlms-form-control" id="linkedin" name="linkedin" aria-describedby="linkedin" placeholder="<?php esc_html_e("Linkedin Link", BaseController::$text_domain) ?>" value="<?php echo esc_url($social_links['linkedin']); ?>">
    </div>
    <div class="mxlms-form-group">
        <label for="biography"><?php esc_html_e("Biography", BaseController::$text_domain) ?>
            <span class="mxlms-anim"><i class="las la-question-circle"></i>
                <span class="mxlms-popover">
                    <?php esc_html_e('Write down a short biography', BaseController::$text_domain); ?>
                </span>
            </span>
        </label>
        <textarea class="mxlms-form-control" id="biography" name="biography" aria-describedby="biography" placeholder="<?php esc_html_e("Enter Biography", BaseController::$text_domain) ?>"><?php echo esc_textarea($profile_data->biography); ?></textarea>
    </div>
    <div class="mxlms-form-group">
        <label for="user_image_upload mxlms-w-100">
            <?php esc_html_e("Upload Image", BaseController::$text_domain) ?>
            <span class="mxlms-anim"><i class="las la-question-circle"></i>
                <span class="mxlms-popover">
                    <?php esc_html_e('The image size should be', BaseController::$text_domain); ?> 500 X 500
                </span>
            </span>
        </label>
        <div class="mxlms-image-uploader">
            <i class="las la-pencil-alt"></i>
            <img src="<?php echo esc_url(Helper::get_image($profile_data->profile_image_path)); ?>" alt="" id="user_image_upload" height="150" width="150">
        </div>
        <input type="hidden" name="user_image_path" id="user_image_path" value="<?php echo esc_url(Helper::get_image($profile_data->profile_image_path)); ?>">
    </div>

    <div class="mxlms-custom-modal-action-footer">
        <div class="mxlms-custom-modal-actions">
            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
            <button type="submit" class="mxlms-btn mxlms-btn-primary mxlms-btn-md"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
        </div>
    </div>
</form>

<script>
    "use strict";

    jQuery(document).ready(function($) {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: true
        };
        jQuery('.update-profile-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var firstname = jQuery('#firstname').val();
        var lastname = jQuery('#lastname').val();
        var email = jQuery('#email').val();
        if (firstname === '' || lastname === '' || email === '') {
            mxlmsNotify("<?php esc_html_e('Required field can not be empty', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.update-profile-form').trigger('reset');
            closeModal();
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
            jQuery('#user_image_path').val(attachment.url);
            jQuery('#user_image_upload').attr('src', attachment.url);
            jQuery('#user_image_upload').show();
            jQuery('.mxlms-image-uploader i').hide();
        });
    });
</script>