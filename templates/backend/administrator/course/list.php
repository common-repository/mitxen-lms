<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\User;
 ?>
<table id="myTable" class="mxlms-responsive-table">
    <thead>
        <tr>
            <th scope="col"><?php esc_html_e('Name', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Instructor', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Category', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Status', BaseController::$text_domain); ?></th>
            <th scope="col" class="mxlms-text-center"><?php esc_html_e('Option', BaseController::$text_domain); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_number_of_courses = count(Mxlms\base\modules\Course::get_all_courses());
        $page_number = isset(Mxlms\base\AjaxPosts::$param1) ? Mxlms\base\AjaxPosts::$param1 : 1;
        $page_number = $page_number ? $page_number : 1;
        $page_size     = 10;
        $first_page = 1;
        $last_page = ceil($total_number_of_courses / $page_size);
        $courses = Mxlms\base\modules\Course::get_all_courses($page_number, $page_size); ?>
        <?php if (count($courses)) : ?>
            <?php foreach ($courses as $key => $course) :
                $category = Mxlms\base\modules\Category::get_category_details_by_id($course->category_id);
                $sub_category = Mxlms\base\modules\Category::get_category_details_by_id($course->sub_category_id);
                $instructor_details = User::get_user_by_id($course->user_id);
            ?>
                <tr class="mxlms-clickable-row" callback="redirectTo('admin.php?page=mxlms-courses&page-contains=course-edit&course_id=<?php echo esc_js($course->id); ?>');">
                    <td>
                        <?php echo esc_html($course->title); ?>
                    </td>
                    <td>
                        <?php echo esc_html($instructor_details->first_name . ' ' . $instructor_details->last_name); ?>
                    </td>
                    <td>
                        <small><strong><?php esc_html_e('Category', BaseController::$text_domain); ?></strong> : <?php echo esc_html($category[0]->title); ?></small><br>
                        <small><strong><?php esc_html_e('SubCategory', BaseController::$text_domain); ?></strong> : <?php echo esc_html($sub_category[0]->title); ?></small>
                    </td>
                    <td>
                        <?php if ($course->status == "active") : ?>
                            <span class="mxlms-badge mxlms-badge-success"><?php esc_html_e('Active', BaseController::$text_domain); ?></span>
                        <?php else : ?>
                            <span class="mxlms-badge mxlms-badge-warning"><?php esc_html_e('Pending', BaseController::$text_domain); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="mxlms-text-center">
                        <label class="mxlms-dropdown mxlms-stop-prop">
                            <div class="mxlms-dd-button mxlms-btn-secondary">
                                <i class="las la-ellipsis-v"></i>
                            </div>
                            <input type="checkbox" class="mxlms-dd-input" id="mxlms-dd-checkbox-<?php echo esc_attr($course->id); ?>" onchange="mxlmsHandleDropDown('<?php echo esc_js($course->id); ?>')">
                            <ul class="mxlms-dd-menu" id="mxlms-dd-menu-<?php echo esc_attr($course->id); ?>">
                                <?php if ($course->status == "pending") : ?>
                                    <li onclick="confirmation_for_updating('<?php esc_html_e('Approve This Course', \Mxlms\base\BaseController::$text_domain); ?>', 'approve', '<?php echo esc_js($course->id); ?>', 'course', 'course/list', 'course-list-area')"><i class="las la-power-off"></i> <?php esc_html_e('Mark as Approve', BaseController::$text_domain); ?></li>
                                <?php elseif ($course->status == "active") : ?>
                                    <li onclick="confirmation_for_updating('<?php esc_html_e('Pending This Course', \Mxlms\base\BaseController::$text_domain); ?>', 'pending', '<?php echo esc_js($course->id); ?>', 'course', 'course/list', 'course-list-area')"><i class="las la-power-off"></i> <?php esc_html_e('Mark as Pending', BaseController::$text_domain); ?></li>
                                <?php endif; ?>
                                <li><a href="admin.php?page=mxlms-courses&page-contains=course-edit&course_id=<?php echo esc_attr($course->id); ?>"><i class="las la-pen"></i> <?php esc_html_e('Edit', BaseController::$text_domain); ?></a></li>
                                <li onclick="confirmation_for_deletion('<?php esc_html_e('Delete Course', BaseController::$text_domain); ?>', '<?php echo esc_js($course->id); ?>', 'course', 'course/list', 'course-list-area')"><i class="las la-trash"></i> <?php esc_html_e('Delete', BaseController::$text_domain); ?></li>
                            </ul>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="mxlms-empty-table">
                <td colspan="5"><?php esc_html_e("No data found", BaseController::$text_domain) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- PAGINATION STARTS -->
<div class="mxlms-pagination-container">
    <?php
    if ($total_number_of_courses > $page_size) : ?>
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