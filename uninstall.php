<?php

/**
 * THIS FILE WILL BE TRIGGERED ON UNINSTALLING THE PLUGIN
 * @package mitxen LMS
 */
// If this file is called directly, abort!!!
defined('ABSPATH') or die('You can not access the file directly');

if (!defined('WP_UNINSTALL_PLUGIN')) {
   die;
}

//DO THE UNINSTALL STUFFS HERE LIKE CLEARING THE DATABASE OR SOMETHING ELSE

echo "Uninstalling Mixtem LMS";
