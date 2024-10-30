<?php
defined('ABSPATH') or die('You can not access the file directly');



$subcategories = \Mxlms\base\modules\Category::get_sub_categories_by_category_id(\Mxlms\base\AjaxPosts::$param1);
$selected_subcategroy_id = isset(\Mxlms\base\AjaxPosts::$param2) ? \Mxlms\base\AjaxPosts::$param2 : "";
?>

<select name="sub_category_id" class="mxlms-wide mxlms-w-100" id="sub_category_id">
  <?php if (count($subcategories)) : ?>
    <?php foreach ($subcategories as $key => $subcategory) : ?>
      <option value="<?php echo esc_attr($subcategory->id); ?>" <?php if ($selected_subcategroy_id == $subcategory->id) echo "selected"; ?>><?php echo esc_html($subcategory->title); ?></option>
    <?php endforeach; ?>
  <?php else : ?>
    <option value=""><?php \Mxlms\base\AjaxPosts::$param1 ? esc_html_e('No Sub Category Found', \Mxlms\base\BaseController::$text_domain) : esc_html_e('Select A Category First', \Mxlms\base\BaseController::$text_domain); ?></option>
  <?php endif; ?>
</select>

<script>
  "use strict";

  // Niceselect initializer
  jQuery(document).ready(function() {
    jQuery('select').niceSelect();
  });
</script>