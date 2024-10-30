<?php
defined('ABSPATH') or die('You can not access the file directly');


use Mxlms\base\modules\Helper;
?>
<img src="<?php echo esc_url(Helper::get_general_settings('logo_lg_path')); ?>" class="mxlms-title-logo-lg" />
<img src="<?php echo esc_url(Helper::get_general_settings('logo_sm_path')); ?>" class="mxlms-title-logo-sm" />