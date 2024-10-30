<?php

defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
?>
<table id="myTable" class="mxlms-responsive-table">
    <thead>
        <tr>
            <th scope="col"><?php esc_html_e('Name', \Mxlms\base\BaseController::$text_domain); ?></th>
            <th scope="col"><?php esc_html_e('Code', \Mxlms\base\BaseController::$text_domain); ?></th>
            <th scope="col" class="mxlms-text-center"><?php esc_html_e('Actions', \Mxlms\base\BaseController::$text_domain); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $languages = Mxlms\base\modules\Language::get_all_languages(); ?>
        <?php if (count($languages)) : ?>
            <?php foreach ($languages as $key => $language) : ?>
                <tr>
                    <td><?php echo esc_html($language->name); ?></td>

                    <td><?php echo esc_html($language->code); ?></td>
                    <?php if (\Mxlms\base\modules\Helper::get_current_user_role() == 'administrator') : ?>
                        <td class="mxlms-text-center">
                            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-sm" onclick="present_right_modal( 'language/edit', '<?php esc_html_e('Update Language', \Mxlms\base\BaseController::$text_domain); ?>', '<?php echo esc_js($language->id); ?>' )">
                                <i class="la la-edit"></i>
                            </button>
                            <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-sm" onclick="confirmation_for_deletion('<?php esc_html_e('Delete Language', \Mxlms\base\BaseController::$text_domain); ?>', '<?php echo esc_js($language->id); ?>', 'language')">
                                <i class="la la-trash"></i>
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="mxlms-empty-table">
                <td colspan="4"><?php esc_html_e("No data found", \Mxlms\base\BaseController::$text_domain) ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mxlms-custom-modal-action-footer">
    <div class="mxlms-custom-modal-actions">
        <button type="button" class="mxlms-btn mxlms-btn-secondary mxlms-btn-sm" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
        <button type="button" class="mxlms-btn mxlms-btn-primary mxlms-btn-sm" onclick="present_right_modal( 'language/create', '<?php esc_html_e('Create New Language', BaseController::$text_domain); ?>');"> <?php esc_html_e("Add Language", BaseController::$text_domain); ?> </button>
    </div>
</div>