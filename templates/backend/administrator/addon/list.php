<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;
use Mxlms\base\modules\Addon;

use Mxlms\base\AjaxPosts; ?>
<table id="myTable" class="mxlms-responsive-table">
    <thead>
        <tr>
            <th scope="col" width="10%">#</th>
            <th scope="col"><?php esc_html_e('Name', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Unique Identifier', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Version', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Status', BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Date Added', BaseController::$text_domain); ?></th>
            <th scope="col" class="mxlms-text-center"><?php esc_html_e('Option', BaseController::$text_domain); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_number_of_addons = count(Addon::get_all_addons());
        $page_number = isset(AjaxPosts::$param1) ? AjaxPosts::$param1 : 1;
        $page_number = $page_number ? $page_number : 1;
        $page_size     = 10;
        $first_page = 1;
        $last_page = ceil($total_number_of_addons / $page_size);
        $addons = Addon::paginate_addons($page_number, $page_size); ?>
        <?php if (count($addons)) : ?>
            <?php foreach ($addons as $key => $addon) : ?>
                <tr>
                    <td><?php echo esc_html($key); ?></td>
                    <td><?php echo esc_html($addon->name); ?></td>
                    <td><?php echo esc_html($addon->unique_identifier); ?></td>
                    <td><?php echo esc_html($addon->version); ?></td>
                    <td>
                        <?php if ($addon->status) : ?>
                            <span class="mxlms-alert mxlms-alert-success"><?php esc_html_e('Active', BaseController::$text_domain); ?></span>
                        <?php else : ?>
                            <span class="mxlms-alert mxlms-alert-warning"><?php esc_html_e('Disabled', BaseController::$text_domain); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date("D, d-M-Y", $addon->created_at); ?></td>
                    <?php if (Helper::get_current_user_role() == 'administrator') : ?>
                        <td class="mxlms-text-center">
                            <button type="button" class="mxlms-btn mxlms-btn-danger mxlms-btn-sm" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Addon', BaseController::$text_domain); ?>', '<?php echo esc_js($addon->id); ?>', 'addon', 'addon/list', 'addon-list-area')">
                                <i class="las la-trash"></i>
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="mxlms-empty-table">
                <td colspan="7"><?php esc_html_e("No data found", BaseController::$text_domain) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mxlms-pagination-container">
    <!-- PAGINATION STARTS -->
    <?php
    if ($total_number_of_addons > $page_size) : ?>
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