<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\base;

defined('ABSPATH') or die('You can not access the file directly');

class Enqueue extends BaseController
{
	// Method for registering admin script enqueue hook to this plugin
	public function register()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	// Method to enqueue css and js specifically for this plugin so that they does not conflict with other plugins
	function enqueue($hook)
	{
		if (
			'toplevel_page_' . $this->slugs['main'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['categories'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['courses'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['enrolments'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['messages'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['reports'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['students'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['instructors'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['addon'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['settings'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['payout'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['become-an-instructor'] == $hook ||
			'mitxen-lms_page_' . $this->slugs['manage-profile'] == $hook
		) {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}
	}
	// Method for enqueueing stylesheets
	private function enqueue_styles()
	{
		wp_enqueue_style('bootstrap', $this->plugin_url . 'assets/common/css/mxlms.bootstrap.css');
		wp_enqueue_style('line-awesome', $this->plugin_url . 'assets/common/line-awesome/css/line-awesome.min.css');
		wp_enqueue_style('toastr', $this->plugin_url . 'assets/common/plugins/toastr/toastr.css');
		wp_enqueue_style('toastr-custom', $this->plugin_url . 'assets/common/plugins/toastr/mxlms-custom-toastr.css');
		wp_enqueue_style('mxlms-tabs-backend', $this->plugin_url . 'assets/backend/css/tab.css');
		wp_enqueue_style('tag-input', $this->plugin_url . 'assets/backend/plugins/bootstrap-tag-input/bootstrap-tagsinput.css');
		wp_enqueue_style('mxlms-colors-styles', $this->plugin_url . 'assets/backend/css/colors.css');
		wp_enqueue_style('mxlms-button-backend', $this->plugin_url . 'assets/backend/css/mxlms-button.css');
		wp_enqueue_style('sortable', $this->plugin_url . 'assets/backend/plugins/sortable/sortable.css');
		wp_enqueue_style('mxlms-custom-modal-backend', $this->plugin_url . 'assets/backend/modal/css/mxlms-custom-modal.css');
		wp_enqueue_style('mxlms-custom-right-modal', $this->plugin_url . 'assets/backend/modal/css/mxlms-right-modal.css');
		wp_enqueue_style('nice-select', $this->plugin_url . 'assets/common/plugins/nice-select/css/nice-select.css');
		wp_enqueue_style('mxlms-nice-select-custom', $this->plugin_url . 'assets/backend/plugins/mxlms-nice-select/css/custom.css');
		wp_enqueue_style('mxlms-more-option-custom', $this->plugin_url . 'assets/backend/plugins/mxlms-more-dropdown/mxlms-more-dropdown-style.css');
		wp_enqueue_style('daterangepicker', $this->plugin_url . 'assets/backend/plugins/daterangepicker/daterangepicker.css');
		wp_enqueue_style('mxlms-tooltip-backend', $this->plugin_url . 'assets/backend/css/mxlms-tooltip.css');
		wp_enqueue_style('popover', $this->plugin_url . 'assets/backend/css/mxlms-popover-style.css');
		wp_enqueue_style('responsive-table', $this->plugin_url . 'assets/backend/plugins/mxlms-table/mxlms-table.css');
		wp_enqueue_style('mxlms-messaging-backend', $this->plugin_url . 'assets/backend/plugins/mxlms-messaging/mxlms-messaging.css');
		wp_enqueue_style('mxlms-timepicker-backend', $this->plugin_url . 'assets/backend/css/mdtimepicker.min.css');
		wp_enqueue_style('mxlms-datepicker-backend', $this->plugin_url . 'assets/backend/css/datepicker.min.css');
		wp_enqueue_style('mxlms-custom-style', $this->plugin_url . 'assets/backend/css/custom.css');
		wp_enqueue_style('mxlms-media-queries-backend', $this->plugin_url . 'assets/backend/css/mxlms-media-queries.css');
	}

	// Method for enqueueing javascript
	private function enqueue_scripts()
	{
		wp_enqueue_script('popper', $this->plugin_url . 'assets/backend/js/popper.min.js', array('jquery'));
		wp_enqueue_script('moment-script', $this->plugin_url . 'assets/backend/js/moment.min.js', array('jquery'));
		wp_enqueue_script('jquery-form', $this->plugin_url . 'assets/common/js/jquery.form.js', array('jquery'));
		wp_enqueue_script('toastr', $this->plugin_url . 'assets/common/plugins/toastr/toastr.js', array('jquery'));
		wp_enqueue_script('printThis', $this->plugin_url . 'assets/backend/js/printThis.js', array('jquery'));
		wp_enqueue_script('blockui', $this->plugin_url . 'assets/common/js/blockui.js', array('jquery'));
		wp_enqueue_script('tag-input', $this->plugin_url . 'assets/backend/plugins/bootstrap-tag-input/bootstrap-tagsinput.js', array('jquery'));
		wp_enqueue_script('mxlms-custom-modal', $this->plugin_url . 'assets/common/modal/js/mxlms-custom-modal.js', array('jquery'));
		wp_enqueue_script('nice-select', $this->plugin_url . 'assets/common/plugins/nice-select/js/jquery.mxlms-nice-select.js', array('jquery'));
		wp_enqueue_script('daterangepicker', $this->plugin_url . 'assets/backend/plugins/daterangepicker/daterangepicker.js', array('jquery'));
		wp_enqueue_script('responsive-table', $this->plugin_url . 'assets/backend/plugins/mxlms-table/mxlms-table.js', array('jquery'));
		wp_enqueue_script('mxlms-timepicker-script', $this->plugin_url . 'assets/backend/js/mdtimepicker.min.js', array('jquery'));
		wp_enqueue_script('mxlms-datepicker-script', $this->plugin_url . 'assets/backend/js/datepicker.min.js', array('jquery'));
		// AMCHART SCRIPTS
		wp_enqueue_script('am-chart-core', $this->plugin_url . 'assets/backend/plugins/amchart/core.js', array('jquery'));
		wp_enqueue_script('am-chart-charts', $this->plugin_url . 'assets/backend/plugins/amchart/charts.js', array('jquery'));
		wp_enqueue_script('am-chart-animated', $this->plugin_url . 'assets/backend/plugins/amchart/animated.js', array('jquery'));

		// PAYPAL CHECKOUT
		wp_enqueue_script('paypal-checkout', $this->plugin_url . 'assets/payment-gateways/paypal/js/paypal-checkout.js', array('jquery'));

		wp_enqueue_script('mxlms-initializers', $this->plugin_url . 'assets/common/js/init.js', array('jquery'));
		wp_enqueue_script('mxlms-custom-script-backend', $this->plugin_url . 'assets/backend/js/custom.js', array('jquery'));
		wp_enqueue_media();
	}
}
