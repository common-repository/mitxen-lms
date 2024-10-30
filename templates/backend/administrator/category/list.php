<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
 ?>

<div class="mxlms-row mxlms-mr-1">
    <?php
    $categories = Mxlms\base\modules\Category::get_parent_categories(); ?>
    <?php if (count($categories)) : ?>
        <?php foreach ($categories as $key => $category) : ?>
            <div class="mxlms-col-lg-4 on-hover-action" id="<?php echo esc_attr($category->id); ?>">
                <div class="mxlms-panel">
                    <div class="mxlms-panel-title">
                        <?php echo esc_html($category->title); ?>
                    </div>
                    <div class="mxlms-panel-body mxlms-pt-1 mxlms-pb-0">
                        <?php
                        $subcategories = Mxlms\base\modules\Category::get_sub_categories_by_category_id($category->id); ?>
                        <ul class="mxlms-categories-list">
                            <?php foreach ($subcategories as $key => $subcategory) : ?>
                                <li class="on-hover-action" id="<?php echo esc_attr($subcategory->id); ?>">
                                    <i class="las la-dot-circle"></i> <?php echo esc_html($subcategory->title); ?>
                                    <span class="mxlms-float-right">
                                        <a href="javascript:void(0)" id="category-edit-btn-<?php echo esc_attr($subcategory->id); ?>" class="mxlms-hidden" onclick="present_right_modal( 'category/edit', '<?php esc_html_e('Update Category', BaseController::$text_domain); ?>', '<?php echo esc_js($subcategory->id); ?>' )"><i class="las la-pencil-alt"></i></a>
                                        <a href="javascript:void(0)" id="category-delete-btn-<?php echo esc_attr($subcategory->id); ?>" class="mxlms-hidden" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Category', BaseController::$text_domain); ?>', '<?php echo esc_js($subcategory->id); ?>', 'category', 'category/list', 'category-list-area')"><i class="las la-trash-alt"></i></a>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="mxlms-panel-footer mxlms-p-2 mxlms-min-h-48">
                        <button class="mxlms-btn mxlms-btn-sm mxlms-btn-secondary mxlms-hidden" id="category-edit-btn-<?php echo esc_attr($category->id); ?>" onclick="present_right_modal( 'category/edit', '<?php esc_html_e('Update Category', BaseController::$text_domain); ?>', '<?php echo esc_js($category->id); ?>' )"> <i class="las la-cog"></i> <?php esc_html_e('Edit', BaseController::$text_domain); ?></button>

                        <button class="mxlms-btn mxlms-btn-sm mxlms-btn-danger mxlms-float-right mxlms-hidden" id="category-delete-btn-<?php echo esc_attr($category->id); ?>" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Category', BaseController::$text_domain); ?>', '<?php echo esc_js($category->id); ?>', 'category', 'category/list', 'category-list-area')"> <i class="las la-trash-alt"></i> <?php esc_html_e('Delete', BaseController::$text_domain); ?></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    "use strict";
    jQuery('.on-hover-action').on( "mouseenter" , function() {
        var id = this.id;
        jQuery('#category-delete-btn-' + id).show();
        jQuery('#category-edit-btn-' + id).show();
    });
    jQuery('.on-hover-action').on( "mouseleave" , function() {
        var id = this.id;
        jQuery('#category-delete-btn-' + id).hide();
        jQuery('#category-edit-btn-' + id).hide();
    });
</script>