<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\User;
use Mxlms\base\modules\Messaging;

$last_conversation = Messaging::get_last_conversation_thread_code();
?>
<?php if (Helper::get_current_user_role() == "instructor" || Helper::get_current_user_role() == "student") : ?>
    <div class="mxlms-row mxlms-justify-content-center mxlms-page-navigationbar">
        <div class="mxlms-col-md">
            <a href="<?php echo esc_url(Helper::get_url("page-contains=my-courses")); ?>" class="mxlms-button mxlms-page-navigation-btn mxlms-block mxlms-round <?php if ($page_contains == "my-courses" || $page_contains == "lessons") echo 'active'; ?>">
                <i class="las la-user"></i>
                <?php esc_html_e('My Courses', BaseController::$text_domain); ?></a>
        </div>
        <div class="mxlms-col-md">
            <a href="<?php echo esc_url(Helper::get_url("page-contains=my-wishlist")); ?>" class="mxlms-button mxlms-page-navigation-btn mxlms-block mxlms-round <?php if ($page_contains == "my-wishlist") echo 'active'; ?>">
                <i class="las la-heart"></i>
                <?php esc_html_e('My Wishlist', BaseController::$text_domain); ?></a>
        </div>
        <div class="mxlms-col-md">
            <?php if ($last_conversation && count((array)$last_conversation)) : ?>
                <a href="<?php echo esc_url(Helper::get_url("page-contains=my-messages&inner-page-contains=message-read&thread=" . esc_attr($last_conversation->message_thread_code))); ?>" class="mxlms-button mxlms-page-navigation-btn mxlms-block mxlms-round <?php if ($page_contains == "my-messages") echo 'active'; ?>">
                    <i class="las la-sms"></i>
                    <?php esc_html_e('My Messages', BaseController::$text_domain); ?></a>
            <?php else : ?>
                <a href="<?php echo esc_url(Helper::get_url("page-contains=my-messages")); ?>" class="mxlms-button mxlms-page-navigation-btn mxlms-block mxlms-round <?php if ($page_contains == "my-messages") echo 'active'; ?>">
                    <i class="las la-sms"></i>
                    <?php esc_html_e('My Messages', BaseController::$text_domain); ?></a>
            <?php endif; ?>
        </div>
        <div class="mxlms-col-md">
            <a href="<?php echo esc_url(Helper::get_url("page-contains=purchase-history")); ?>" class="mxlms-button mxlms-page-navigation-btn mxlms-block mxlms-round <?php if ($page_contains == "purchase-history") echo 'active'; ?>">
                <i class="las la-shopping-bag"></i>
                <?php esc_html_e('Purchase History', BaseController::$text_domain); ?></a>
        </div>
    </div>
<?php endif; ?>

<script>
    "use strict";

    function toggleProfileMenu() {
        // MXLMS-DROPDOWN-FOR-FRONTEND
        var X = jQuery('.mxlms-logged-in-student-thumbnail').attr("id");
        let action = jQuery('.mxlms-logged-in-student-thumbnail').attr("action");
        if (action) {
            if (X == 1) {
                jQuery(".mxlms-submenu#action-" + action).hide();
                jQuery('.mxlms-logged-in-student-thumbnail').attr("id", "0");
            } else {
                jQuery(".mxlms-submenu#action-" + action).show();
                jQuery('.mxlms-logged-in-student-thumbnail').attr("id", "1");
            }
        }
    }
</script>