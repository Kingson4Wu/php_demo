<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_emailverify.php 9485 2010-04-29 02:34:05Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

$member = DB::fetch_first("SELECT mf.authstr FROM ".DB::table('common_member')." m, ".DB::table('common_member_field_forum')." mf
	WHERE m.uid='$_G[uid]' AND mf.uid=m.uid AND m.groupid='8'");

if(!$member) {
	showmessage('undefined_action', NULL);
}

if($_G['setting']['regverify'] == 2) {
	showmessage('register_verify_invalid');
}

list($dateline, $type, $idstring) = explode("\t", $member['authstr']);
if($type == 2 && TIMESTAMP - $dateline < 86400) {
	showmessage('email_verify_invalid');
}

$idstring = $type == 2 && $idstring ? $idstring : random(6);
DB::query("UPDATE ".DB::table('common_member_field_forum')." SET authstr='$_G[timestamp]\t2\t$idstring' WHERE uid='$_G[uid]'");

$email_verify_message = lang('email', 'email_verify_message', array(
	'username' => $_G['member']['username'],
	'bbname' => $_G['setting']['bbname'],
	'uid' => $_G['uid'],
	'siteurl' => $_G['siteurl'],
	'idstring' => $idstring,
));
include_once libfile('function/mail');
sendmail("{$_G[member][username]} <$_G[gp_email]>", lang('email', 'email_verify_subject'), $email_verify_message);
showmessage('email_verify_succeed');

?>