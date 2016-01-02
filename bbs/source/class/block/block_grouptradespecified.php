<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_grouptradespecified.php 7212 2010-03-30 13:05:47Z xupeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('block/grouptrade', 'class');
class block_grouptradespecified extends block_grouptrade {
	function block_grouptradespecified() {
		$this->setting = array(
			'tids' => array(
				'title' => 'grouptrade_tids',
				'type' => 'text'
			),
			'uids' => array(
				'title' => 'grouptrade_uids',
				'type' => 'text'
			),
			'keyword' => array(
				'title' => 'grouptrade_keyword',
				'type' => 'text'
			),
			'fids'	=> array(
				'title' => 'grouptrade_fids',
				'type' => 'text'
			),
			'gtids' => array(
				'title' => 'grouptrade_gtids',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'titlelength' => array(
				'title' => 'grouptrade_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'grouptrade_summarylength',
				'type' => 'text',
				'default' => 80
			),
		);
	}
}

?>