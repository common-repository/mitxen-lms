<?php

/**
 * @package mitxen-LMS
 */

namespace Mxlms\base\modules;

use Mxlms\base\BaseController;

defined('ABSPATH') or die('You can not access the file directly');

class Video extends BaseController
{
  // parse video id from youtube embed url
  public static function get_youtube_video_id($embed_url = '')
  {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $embed_url, $match);
    $video_id = $match[1];
    return $video_id;
  }

  public static function get_video_details($url)
  {
    
    $regs = array();
    $video_details = array();

    $id = '';

    if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
        $id = $regs[3];
    }

    $video_details['video_id'] = $id;
    return $video_details;
    
  }
}