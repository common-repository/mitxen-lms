<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\modules\Helper;
use Mxlms\base\BaseController;
?>

<div class="mxlms-wrapper">
    <div class="mxlms-row mxlms-mr-1">
        <div class="mxlms-col">
            <div class="mxlms-panel">
                <div class="mxlms-panel-body">
                    <div class="mxlms-page-title-area">
                        <?php include Helper::get_plugin_path('templates/backend/common/header-image.php'); ?>
                        <span class="mxlms-page-title">
                            <?php esc_html_e('Category Manager', BaseController::$text_domain); ?>
                        </span>
                        <a href="#" class="mxlms-btn mxlms-btn-primary mxlms-title-btn" onclick="present_right_modal( 'category/create', '<?php esc_html_e('Add New Category', BaseController::$text_domain); ?>' )">
                            <i class="las la-plus"></i>
                            <?php esc_html_e("Add New Category", BaseController::$text_domain) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mxlms-row mxlms-justify-content-center mxlms-mr-1">
        <div class="mxlms-col-lg-12">
            <div id="category-list-area"></div>
        </div>
    </div>

    <div class="mxlms-floating-container">
        <a target="javascript:void(0)" class="mxlms-floating" onclick="present_right_modal('category/create', '<?php esc_html_e('Add New Category', BaseController::$text_domain); ?>')"><i class="las la-plus"></i></a>
    </div>

    <?php include "$this->plugin_path/templates/backend/modal/index.php"; ?>
</div>

<script>
    "use strict";

    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    jQuery(document).ready(function($) {
        showCategoryList();
    });

    function showCategoryList() {
        mxlmsMakeAjaxCall(ajaxurl, 'category/list', 'category-list-area');
    }

    function paginate(pageNumber, pageSize) {
        mxlmsMakeAjaxCall(ajaxurl, 'category/list', 'category-list-area', pageNumber, pageSize);
    }
</script>