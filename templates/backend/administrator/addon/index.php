<?php defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
use Mxlms\base\modules\Helper; ?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Addon Manager', BaseController::$text_domain); ?>
                        </span>
                        <a href="javascript:void(0)" class="mxlms-btn mxlms-btn-primary mxlms-title-btn" onclick="present_right_modal( 'addon/create', '<?php esc_html_e('Add New Addon', BaseController::$text_domain); ?>' )">
                            <i class="las la-plus"></i> &nbsp; <?php esc_html_e("Add New Addon", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-title">
                    <?php esc_html_e('List Of Addons', BaseController::$text_domain); ?>
                </div>
                <div class="mxlms-panel-body">
                    <div id="addon-list-area"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-floating-container">
        <a target="javascript:void(0)" class="mxlms-floating" onclick="present_right_modal('addon/create', '<?php esc_html_e('Add New Addon', BaseController::$text_domain); ?>')"><i class="las la-plus"></i></a>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>

<script>
    "use strict";
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    jQuery(document).ready(function() {
        showaddonList();
    });

    function showaddonList() {
        mxlmsMakeAjaxCall(ajaxurl, 'addon/list', 'addon-list-area');
    }

    function paginate(pageNumber, pageSize) {
        mxlmsMakeAjaxCall(ajaxurl, 'addon/list', 'addon-list-area', pageNumber, pageSize);
    }
</script>