<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_image.php 7390 2010-04-07 05:53:38Z monkey $
 */

if(!defined('IN_DISCUZ') || empty($_G['gp_aid']) || empty($_G['gp_size']) || empty($_G['gp_key'])) {
	header('location: '.$_G['siteurl'].'static/image/common/none.gif');
	exit;
}


$nocache = !empty($_G['gp_nocache']) ? 1 : 0;
$aid = intval($_G['gp_aid']);
$type = !empty($_G['gp_type']) ? $_G['gp_type'] : 'fixwr';
list($w, $h) = explode('x', $_G['gp_size']);
$w = intval($w);
$h = intval($h);
$thumbfile = 'image/'.$aid.'_'.$w.'_'.$h.'.jpg';
if(!$nocache) {
	if(file_exists(DISCUZ_ROOT.'./data/attachment/'.$thumbfile)) {
		header('location: '.$_G['siteurl'].'data/attachment/'.$thumbfile);
		exit;
	}
}

define('NOROBOT', TRUE);

list($daid, $dw, $dh) = explode("\t", authcode($_G['gp_key'], 'DECODE', $_G['config']['security']['authkey']));

if($daid != $aid || $dw != $w || $dh != $h) {
	dheader('location: '.$_G['siteurl'].'static/image/common/none.gif');
}

if($attach = DB::fetch(DB::query("SELECT remote, attachment FROM ".DB::table('forum_attachment')." WHERE aid='$aid' AND isimage IN ('1', '-1')"))) {
	dheader('Expires: '.gmdate('D, d M Y H:i:s', TIMESTAMP + 3600).' GMT');
	if($attach['remote']) {
		$filename = $_G['setting']['ftp']['attachurl'].'/forum/'.$attach['attachment'];
	} else {
		$filename = $_G['setting']['attachdir'].'/forum/'.$attach['attachment'];
	}
	require_once libfile('class/image');
	$img = new image;
	if($img->Thumb($filename, $thumbfile, $w, $h, $type)) {
		if($nocache) {
			@readfile(DISCUZ_ROOT.'./data/attachment/'.$thumbfile);
			@unlink(DISCUZ_ROOT.'./data/attachment/'.$thumbfile);
		} else {
			dheader('location: '.$_G['siteurl'].'data/attachment/'.$thumbfile);
		}
	} else {
		@readfile($filename);
	}
}

?>