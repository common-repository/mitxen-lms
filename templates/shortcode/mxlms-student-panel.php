<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper;
?>

<div class="mxlms-wrapper">
    <div class="mxlms-container-fluid">

        <div class="mxlms-tabset">
            <!-- Tab 0 -->
            <input type="radio" name="tabset" id="my-courses" aria-controls="my-courses" <?php if ($active_tab == "my-courses") echo "checked"; ?>>
            <label for="my-courses">
                <i class="las la-book"></i>
                <span class="mxlms-tab-title"><a href="<?php echo esc_url(Helper::get_url("page-contains=my-courses")); ?>" class="mxlms-text-decoration-none mxlms-link-unset"><?php esc_html_e('My Courses', BaseController::$text_domain); ?></a></span>
            </label>
            <!-- Tab 1 -->
            <input type="radio" name="tabset" id="my-wishlist" aria-controls="my-wishlist" <?php if ($active_tab == "my-wishlist") echo "checked"; ?>>
            <label for="my-wishlist">
                <i class="las la-exclamation-circle"></i>
                <span class="mxlms-tab-title"><a href="<?php echo esc_url(Helper::get_url("page-contains=my-wishlist")); ?>" class="mxlms-text-decoration-none mxlms-link-unset"><?php esc_html_e('My Wishlist', BaseController::$text_domain); ?></a></span>
            </label>
            <!-- Tab 2 -->
            <input type="radio" name="tabset" id="my-messages" aria-controls="my-messages" <?php if ($active_tab == "my-messages") echo "checked"; ?>>
            <label for="my-messages">
                <i class="las la-expand-arrows-alt"></i>
                <span class="mxlms-tab-title"><a href="<?php echo esc_url(Helper::get_url("page-contains=my-messages")); ?>" class="mxlms-text-decoration-none mxlms-link-unset"><?php esc_html_e('My Messages', BaseController::$text_domain); ?></a></span>
            </label>
            <!-- Tab 3 -->
            <input type="radio" name="tabset" id="purchase-history" aria-controls="purchase-history" <?php if ($active_tab == "purchase-history") echo "checked"; ?>>
            <label for="purchase-history">
                <i class="las la-exclamation-triangle"></i>
                <span class="mxlms-tab-title"><?php esc_html_e('Purchase History', BaseController::$text_domain); ?></span>
            </label>

            <div class="mxlms-tab-panels">
                <section id="my-courses" class="mxlms-tab-panel">
                    <?php include 'mxlms-my-courses.php'; ?>
                </section>
                <section id="my-wishlist" class="mxlms-tab-panel">
                    <?php include 'mxlms-my-wishlist.php'; ?>
                </section>
                <section id="my-messages" class="mxlms-tab-panel">
                    <?php include 'mxlms-my-messages.php'; ?>
                </section>
                <section id="purchase-history" class="mxlms-tab-panel">
                    <?php include 'mxlms-purchase-history.php'; ?>
                </section>
            </div>
        </div>
    </div>
</div>