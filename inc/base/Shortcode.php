<?php

/**
* @package LMS
*/

namespace Mxlms\base;

defined('ABSPATH') or die('You can not access the file directly');

class Shortcode extends BaseController
{
	public function register()
	{
		add_shortcode('mx_course', array($this, 'render_lms_course_view'));
		add_shortcode('mx_account', array($this, 'render_lms_account_view'));
	}

	public function render_lms_course_view()
	{
		ob_start();

		$this->enqueue_styles();
		$this->enqueue_scripts();
		require_once("$this->plugin_path/templates/shortcode/mxlms-course-shortcode.php");

		return ob_get_clean();
	}

	public function render_lms_account_view()
	{
		ob_start();

		$this->enqueue_styles();
		$this->enqueue_scripts();

		require_once("$this->plugin_path/templates/shortcode/mxlms-account-shortcode.php");

		return ob_get_clean();
	}

	public function enqueue_styles()
	{
		wp_enqueue_style('bootstrap', $this->plugin_url . 'assets/common/css/mxlms.bootstrap.css');
		wp_enqueue_style('element', $this->plugin_url . 'assets/common/css/mxlms.element.css');
		wp_enqueue_style('line-awesome', $this->plugin_url . 'assets/common/line-awesome/css/line-awesome.min.css');
		wp_enqueue_style('mxlms-button-frontend', $this->plugin_url . 'assets/frontend/css/mxlms-button.css');
		wp_enqueue_style('payment-style', $this->plugin_url . 'assets/frontend/css/payment.css');
		wp_enqueue_style('mxlms-tooltip-frontend', $this->plugin_url . 'assets/frontend/css/mxlms-tooltip.css');
		wp_enqueue_style('mxlms-tabs-frontend', $this->plugin_url . 'assets/frontend/css/mxlms-tabs.css');
		wp_enqueue_style('nice-select', $this->plugin_url . 'assets/common/plugins/nice-select/css/nice-select.css');
		wp_enqueue_style('mxlms-nice-select-custom', $this->plugin_url . 'assets/frontend/plugins/mxlms-nice-select/css/custom.css');
		wp_enqueue_style('mxlms-messaging-frontend', $this->plugin_url . 'assets/frontend/plugins/mxlms-messaging/mxlms-messaging.css');
		wp_enqueue_style('toastr', $this->plugin_url . 'assets/common/plugins/toastr/toastr.css');
		wp_enqueue_style('toastr-custom', $this->plugin_url . 'assets/common/plugins/toastr/mxlms-custom-toastr.css');
		wp_enqueue_style('mxlms-media-queries-frontend', $this->plugin_url . 'assets/frontend/css/mxlms-media-queries.css');

		// PLYR CSS
		wp_enqueue_style('plyr-style', $this->plugin_url . 'assets/frontend/plugins/plyr/plyr.css');

		wp_enqueue_style('mxlms-custom-modal-frontend', $this->plugin_url . 'assets/frontend/modal/css/mxlms-custom-modal.css');
		wp_enqueue_style('mxlms-custom-css', $this->plugin_url . 'assets/frontend/css/custom.css');
	}
	public function enqueue_scripts()
	{
		// Javascript files enqued
		wp_enqueue_script('blockui', $this->plugin_url . 'assets/common/js/blockui.js', array('jquery'));
		wp_enqueue_script('jquery-form', $this->plugin_url . 'assets/common/js/jquery.form.js', array('jquery'));
		wp_enqueue_script('nice-select', $this->plugin_url . 'assets/common/plugins/nice-select/js/jquery.mxlms-nice-select.js', array('jquery'));
		wp_enqueue_script('initializers', $this->plugin_url . 'assets/common/js/init.js', array('jquery'));
		wp_enqueue_script('toastr', $this->plugin_url . 'assets/common/plugins/toastr/toastr.js', array('jquery'));
		wp_enqueue_script('mxlms-custom-modal', $this->plugin_url . 'assets/common/modal/js/mxlms-custom-modal.js', array('jquery'));

		// PAYPAL CHECKOUT
		wp_enqueue_script('paypal-checkout', $this->plugin_url . 'assets/payment-gateways/paypal/js/paypal-checkout.js', array('jquery'));
		// PLYR SCRIPT
		wp_enqueue_script('plyr-script', $this->plugin_url . 'assets/frontend/plugins/plyr/plyr.js', array('jquery'));
		wp_enqueue_script('plyr-custom-script', $this->plugin_url . 'assets/frontend/plugins/plyr/plyr-custom.js', array('jquery'));

		wp_enqueue_script('mxlms-custom-script-frontend', $this->plugin_url . 'assets/frontend/js/custom.js', array('jquery'));
		wp_enqueue_media();
	}
}
