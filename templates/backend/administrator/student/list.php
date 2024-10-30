<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;

use Mxlms\base\modules\Helper; ?>
<table id="myTable" class="mxlms-responsive-table">
    <thead>
        <tr>
            <th scope="col"><?php esc_html_e('Image', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Name', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Email', BaseController::$text_domain); ?></th>
            <th scope="col" class="mxlms-text-center"><?php esc_html_e('Option', BaseController::$text_domain); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $number_of_rows = count(Mxlms\base\modules\Student::get_student());
        $page_number   = (isset(Mxlms\base\AjaxPosts::$param1) && !empty(Mxlms\base\AjaxPosts::$param1)) ? Mxlms\base\AjaxPosts::$param1 : 1;
        $page_size     = 10;
        $first_page = 1;
        $last_page = ceil($number_of_rows / $page_size);
        $students = Mxlms\base\modules\Student::paginate_student($page_number, $page_size); ?>
        <?php if (count($students)) : ?>
            <?php foreach ($students as $key => $student) : ?>
                <tr class="mxlms-clickable-row" callback="redirectTo('admin.php?page=mxlms-students&page-contains=student-edit&student-id=<?php echo esc_attr($student->id); ?>');">
                    <td>
                        <img src="<?php echo esc_url(Helper::get_image($student->profile_image_path)); ?>" alt="" class="mxlms-round-img-thumbnail">
                    </td>
                    <td><?php echo esc_html($student->first_name . ' ' . $student->last_name); ?></td>

                    <td><?php echo esc_html($student->email); ?></td>
                    <td class="mxlms-text-center">
                        <?php if (\Mxlms\base\modules\Helper::get_current_user_role() == 'administrator') : ?>
                            <label class="mxlms-dropdown mxlms-stop-prop">
                                <div class="mxlms-dd-button mxlms-btn-secondary">
                                    <i class="las la-ellipsis-v"></i>
                                </div>
                                <input type="checkbox" class="mxlms-dd-input" id="mxlms-dd-checkbox-<?php echo esc_attr($student->id); ?>" onchange="mxlmsHandleDropDown('<?php echo esc_js($student->id); ?>')">
                                <ul class="mxlms-dd-menu" id="mxlms-dd-menu-<?php echo esc_attr($student->id); ?>">
                                    <li> <a href="admin.php?page=mxlms-students&page-contains=student-edit&student-id=<?php echo esc_js($student->id); ?>"><i class="las la-pen"></i> <?php esc_html_e('Edit', BaseController::$text_domain); ?></a></li>
                                    <li onclick="confirmation_for_deletion('<?php esc_html_e('Delete student', \Mxlms\base\BaseController::$text_domain); ?>', '<?php echo esc_js($student->id); ?>', 'student', 'student/list', 'student-list-area')"><i class="las la-trash"></i> <?php esc_html_e('Delete', BaseController::$text_domain); ?></li>
                                </ul>
                            </label>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="mxlms-empty-table">
                <td colspan="4"><?php esc_html_e("No data found", BaseController::$text_domain) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- PAGINATION STARTS -->
<div class="mxlms-pagination-container">
    <?php
    if ($number_of_rows > $page_size) : ?>
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
</div>
<!-- PAGINATION ENDS -->