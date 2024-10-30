<?php

defined('ABSPATH') or die('You can not access the file directly');

use Mxlms\base\BaseController;
?>
<form action="<?php echo esc_url(\Mxlms\base\modules\Helper::get_url("page-contains=paypal-checkout")); ?>" method="post" class="paypal-form form">
    <br>
    <input type="hidden" name="course_id" value="<?php echo esc_attr($course_id); ?>">
    <button type="submit" class="mxlms-payment-button mxlms-float-right"><?php esc_html_e('Pay By Paypal', BaseController::$text_domain); ?></button>
</form>