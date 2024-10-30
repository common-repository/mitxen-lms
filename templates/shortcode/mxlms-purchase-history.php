<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Course;

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\User;

$page_number = (isset($_GET['page_number']) && !empty($_GET['page_number'])) ?  sanitize_text_field($_GET['page_number']) : 1; ?>

<div class="mxlms-wrapper">
    <div class="mxlms-preloader"></div>
    <div class="mxlms-container-fluid mxlms-page-content mxlms-hidden">

        <?php include 'mxlms-page-navbar.php'; ?>
        <div class="mxlms-h5">
            <?php esc_html_e('Purchase History', BaseController::$text_domain); ?>
        </div>
        <div class="mxlms-row">
            <div class="mxlms-col-lg-12">
                <table class="mxlms-responsive-table mxlms-mt-0">
                    <thead>
                        <tr>
                            <th scope="col" class="mxlms-th"><?php esc_html_e('Purchase Date', BaseController::$text_domain); ?></th>
                            <th scope="col" class="mxlms-th"><?php esc_html_e('Course Details', BaseController::$text_domain); ?></th>
                            <th scope="col" class="mxlms-th"><?php esc_html_e('Amount Paid', BaseController::$text_domain); ?></th>
                            <th scope="col" class="mxlms-th"><?php esc_html_e('Payment Type', BaseController::$text_domain); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_number_of_rows = count(Mxlms\base\modules\Payment::purchase_history(Helper::get_current_user_id()));
                        $page_number = isset(Mxlms\base\AjaxPosts::$param1) ? Mxlms\base\AjaxPosts::$param1 : 1;
                        $page_number = $page_number ? $page_number : 1;
                        $page_size     = 10;
                        $first_page = 1;
                        $last_page = ceil($total_number_of_rows / $page_size);
                        $payment_histories = Mxlms\base\modules\Payment::purchase_history(Helper::get_current_user_id(), $page_number, $page_size); ?>
                        <?php if (count($payment_histories)) : ?>
                            <?php foreach ($payment_histories as $key => $payment_history) :
                                $course_details = Course::get_course_details_by_id($payment_history->course_id); ?>
                                <tr>
                                    <td class="mxlms-td"><?php echo date('D, d-M-Y', esc_html($payment_history->date_added)); ?></td>
                                    <td class="mxlms-td"><?php echo esc_html($course_details->title); ?></td>
                                    <td class="mxlms-td"><?php echo Helper::currency(esc_html($payment_history->amount)); ?></td>
                                    <td class="mxlms-td"><?php echo esc_html($payment_history->payment_type); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr class="mxlms-empty-table">
                                <td colspan="4"><?php esc_html_e("No data found", BaseController::$text_domain) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="mxlms-pagination-container">
                    <!-- PAGINATION STARTS -->
                    <?php
                    if ($total_number_of_rows > $page_size) : ?>
                        <div class="mxlms-float-right">
                            <div class="mxlms-pagination mxlms-pagination--right">
                                <?php for ($i = 1; $i <= $last_page; $i++) : ?>
                                    <a <?php if ($page_number == $i) : ?>aria-current="page" <?php else : ?> href="javascript:void(0)" onclick="paginate('<?php echo esc_js($i); ?>', '<?php echo esc_js($page_size); ?>')" <?php endif; ?> class="mxlms-page-numbers <?php if ($page_number == $i) : ?> mxlms-current <?php endif; ?>">
                                        <?php echo esc_html($i); ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- PAGINATION ENDS -->
                </div>
            </div>
        </div>
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