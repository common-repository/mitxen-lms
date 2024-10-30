<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms;

use Mxlms\base\modules\Helper;

defined('ABSPATH') or die('You can not access the file directly');

final class Init
{
	public static function get_services()
	{
		return array(
			api\Api::class,
			pages\Admin::class,
			base\Enqueue::class,
			base\AjaxPosts::class,
			base\modules\Category::class,
			base\modules\Course::class,
			base\modules\Student::class,
			base\modules\Section::class,
			base\modules\Lesson::class,
			base\modules\Video::class,
			base\modules\Question::class,
			base\modules\User::class,
			base\modules\Instructor::class,
			base\modules\Settings::class,
			base\modules\Language::class,
			base\modules\Addon::class,
			base\modules\Payout::class,
			base\modules\Application::class,
			base\modules\Report::class,
			base\modules\Enrolment::class,
			base\modules\Messaging::class,
			base\modules\Wishlist::class,
			base\modules\Review::class,
			base\modules\Payment::class,
			base\modules\Profile::class,
			base\modules\Certificate::class,
			base\modules\Aws::class,
			base\modules\Updater::class,
			base\modules\LiveClass::class,
			base\modules\Quiz::class,
			base\Shortcode::class,
			base\widgets\Topcourse::class,
			base\widgets\FeaturedCategory::class,
			base\widgets\SocialMedia::class,
			base\widgets\SearchWidget::class,
		);
	}

	public static function register_services()
	{
		foreach (self::get_services() as $class) {
			$service = self::instantiate($class);
			if (method_exists($service, 'register')) {
				$service->register();
			}
		}
	}

	private static function instantiate($class)
	{
		$service = new $class();
		return $service;
	}
}
