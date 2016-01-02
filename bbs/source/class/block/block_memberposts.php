<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_memberposts.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('block/member', 'class');
class block_memberposts extends block_member {
	function block_memberposts() {
		$this->setting = array(
			'orderby' => array(
				'title' => 'memberlist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('posts', 'memberlist_orderby_posts'),
					array('digestposts', 'memberlist_orderby_digestposts'),
					array('hourposts', 'memberlist_orderby_hourposts'),
					array('todayposts', 'memberlist_orderby_todayposts'),
					array('weekposts', 'memberlist_orderby_weekposts'),
					array('monthposts', 'memberlist_orderby_monthposts')
				),
				'default' => 'posts'
			),
			'startrow' => array(
				'title' => 'memberlist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}
}

?>