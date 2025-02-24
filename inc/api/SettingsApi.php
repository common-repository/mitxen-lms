<?php

/**
 * @package mitxen LMS
 */

namespace Mxlms\api;

defined('ABSPATH') or die('You can not access the file directly');

class SettingsApi
{
	public $admin_pages = array();
	public $admin_sub_page = array();

	// Method for registering admin menu hook to the plugin
	public function register()
	{
		if (!empty($this->admin_pages)) {
			add_action('admin_menu', array($this, 'add_admin_menu'));
		}
	}

	// Method for adding pages to the plugin
	public function add_pages(array $pages)
	{
		$this->admin_pages = $pages;
		return $this;
	}

	// Method for adding the first sub menu to the plugin
	public function with_sub_page($title = null)
	{
		if (empty($this->admin_pages)) {
			return $this;
		}
		$admin_page = $this->admin_pages[0];
		$sub_page = array(
			array(
				'parent_slug' => $admin_page['menu_slug'],
				'page_title' => $admin_page['page_title'],
				'menu_title' => ($title) ? $title : $admin_page['menu_title'],
				'capability' => $admin_page['capability'],
				'menu_slug' => $admin_page['menu_slug'],
				'callback' => $admin_page['callback']
			)
		);
		$this->admin_sub_page = $sub_page;
		return $this;
	}

	// Method for adding other sub menus to the plugin
	public function add_sub_pages(array $pages)
	{
		$this->admin_sub_page = array_merge($this->admin_sub_page, $pages);
		return $this;
	}

	// Method for adding menu items to the plugin after all necessary configurations
	public function add_admin_menu()
	{
		foreach ($this->admin_pages as $page) {
			add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
		}
		foreach ($this->admin_sub_page as $page) {
			add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
		}
	}
}
