<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\AjaxPosts;
use Mxlms\base\modules\Course;

use Mxlms\base\modules\Helper;
use Mxlms\base\modules\Report;
use Mxlms\base\BaseController;

$start_date = isset(AjaxPosts::$param1) ? AjaxPosts::$param1 : date("F d, Y");
$end_date = isset(AjaxPosts::$param2) ? AjaxPosts::$param2 : date("F t, Y"); ?>
<table id="myTable" class="mxlms-responsive-table">
    <thead>
        <tr>
            <th scope="col"><?php esc_html_e('Enrolled Course', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Total Amount', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Admin Revenue', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Enrolment Date', BaseController::$text_domain); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $filtered_result = Report::get_admin_revenue_reports($start_date, $end_date);
        $total_number_of_rows = count($filtered_result);
        $page_number = isset(Mxlms\base\AjaxPosts::$param3) ? Mxlms\base\AjaxPosts::$param3 : 1;
        $page_number = $page_number ? $page_number : 1;
        $page_size     = 10;
        $first_page = 1;
        $last_page = ceil($total_number_of_rows / $page_size);
        $reports = Report::get_admin_revenue_reports($start_date, $end_date, $page_number, $page_size); ?>
        <?php if (count($reports)) : ?>
            <?php foreach ($reports as $key => $report) :
                $course_details = Course::get_authenticated_course_details_by_id($report->course_id); ?>
                <tr>
                    <td><?php echo esc_html($course_details->title); ?></td>
                    <td><?php echo Helper::currency(esc_html($report->amount)); ?></td>
                    <td><?php echo Helper::currency(esc_html($report->admin_revenue)); ?></td>
                    <td><?php echo date('D, d-M-Y', esc_html($report->date_added)); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="mxlms-summary-row">
                <?php
                $total_amount = 0;
                $total_admin_revenue = 0;
                foreach ($filtered_result as $row) {
                    $total_amount = $total_amount + $row->amount;
                    $total_admin_revenue = $total_admin_revenue + $row->admin_revenue;
                } ?>
                <td><?php echo esc_html($total_number_of_rows); ?> <?php esc_html_e('Result found', BaseController::$text_domain); ?></td>
                <td><?php esc_html_e('Total Amount', BaseController::$text_domain); ?> : <?php echo Helper::currency($total_amount); ?></td>
                <td><?php esc_html_e('Total Admin Revenue', BaseController::$text_domain); ?> : <?php echo Helper::currency($total_admin_revenue); ?></td>
                <td></td>
            </tr>
        <?php else : ?>
            <tr class="mxlms-empty-table">
                <td colspan="4"><?php esc_html_e("No data found", BaseController::$text_domain) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mxlms-pagination-container">
    <!-- PAGINATION STARTS -->
    <?php if ($total_number_of_rows > $page_size) : ?>
        <div class="mxlms-float-right">
            <div class="mxlms-pagination mxlms-pagination--right">
                <?php for ($i = 1; $i <= $last_page; $i++) : ?>
                    <a <?php if ($page_number == $i) : ?>aria-current="page" <?php else : ?> href="javascript:void(0)" onclick="paginate('<?php echo esc_js($start_date); ?>','<?php echo esc_js($end_date); ?>', '<?php echo esc_js($i); ?>', '<?php echo esc_js($page_size); ?>', true)" <?php endif; ?> class="mxlms-page-numbers <?php if ($page_number == $i) : ?> mxlms-current <?php endif; ?>">
                        <?php echo esc_html($i); ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    <?php endif; ?>
    <!-- PAGINATION ENDS -->
</div>