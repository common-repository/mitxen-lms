<?php
defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;


$categories = \Mxlms\base\modules\Category::get_parent_categories();
$selected_category_id = isset(\Mxlms\base\AjaxPosts::$param1) ? \Mxlms\base\AjaxPosts::$param1 : "";
?>

<select name="category_id" class="mxlms-wide" id="category_id" onchange="getSubCategories(this.value)">
    <option value='' class="mxlms-disabled"> <?php esc_html_e("Select a category", BaseController::$text_domain) ?></option>
    <?php foreach ($categories as $key => $category) : ?>
        <option value="<?php echo esc_attr($category->id); ?>" <?php if ($selected_category_id == $category->id) echo "selected"; ?>><?php echo esc_html($category->title); ?></option>
    <?php endforeach; ?>
</select>

<script>
    "use strict";
    initNiceSelect();
</script>