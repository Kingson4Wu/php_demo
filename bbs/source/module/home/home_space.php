<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_space.php 10932 2010-05-18 07:39:13Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = empty($_GET['uid']) ? 0 : intval($_GET['uid']);

if($_GET['username']) {
	$member = DB::fetch_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[username]' LIMIT 1");
	$uid = $member['uid'];
} elseif ($_GET['domain']) {
	$member = DB::fetch_first("SELECT uid FROM ".DB::table('common_member_field_home')." WHERE domain='$_GET[domain]' LIMIT 1");
	$uid = $member['uid'];
}

$dos = array('index', 'doing', 'blog', 'album', 'friend', 'wall',
	'notice', 'share', 'home', 'pm', 'videophoto', 'top', 'favorite',
	'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'group', 'profile');

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';

if(empty($uid)) $uid = $_G['uid'];

if($uid) {
	$space = getspace($uid, 'uid');
	if(empty($space)) {
		showmessage('space_does_not_exist');
	}
}

if(empty($space)) {
	if(in_array($do, array('doing', 'blog', 'album', 'share', 'home', 'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'group'))) {
		$_GET['view'] = 'all';
		$space['uid'] = 0;
	} else {
		showmessage('login_before_enter_home', 'member.php?mod=logging&action=login');
	}
} else {

	if($space['status'] == -1) {
		showmessage('space_has_been_locked');
	}

	if(!ckprivacy($do, 'view')) {
		include template('home/space_privacy');
		exit();
	}

	if(!$space['self']) $_GET['view'] = 'me';

	get_my_userapp();

	get_my_app();
}

$diymode = 0;

$seccodecheck = $_G['setting']['seccodestatus'] & 4;
$secqaacheck = $_G['setting']['secqaa']['status'] & 2;

require_once libfile('space/'.$do, 'include');

?>