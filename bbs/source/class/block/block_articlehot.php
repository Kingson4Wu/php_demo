<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_articlehot.php 7212 2010-03-30 13:05:47Z xupeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('block/article', 'class');
class block_articlehot extends block_article {
	function block_articlehot() {
		$this->setting = array(
			'catid' => array(
				'title' => 'articlelist_catid',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'picrequired' => array(
				'title' => 'articlelist_picrequired',
				'type' => 'radio',
				'default' => '0'
			),
			'orderby' => array(
				'title' => 'articlelist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('viewnum', 'articlelist_orderby_viewnum'),
					array('replynum', 'articlelist_orderby_commentnum'),
				),
				'default' => 'viewnum'
			),
			'titlelength' => array(
				'title' => 'articlelist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength'	=> array(
				'title' => 'articlelist_summarylength',
				'type' => 'text',
				'default' => 80
			)
		);
	}

}

?>