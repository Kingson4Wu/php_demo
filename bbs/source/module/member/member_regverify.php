<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_regverify.php 9411 2010-04-28 09:20:15Z zhaoxiongfei $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

if($_G['setting']['regverify'] == 2 && $_G['groupid'] == 8 && submitcheck('verifysubmit')) {

	$query = DB::query("SELECT uid FROM ".DB::table('common_member_validate')." WHERE uid='$_G[uid]' AND status='1'");
	if(DB::num_rows($query)) {
		DB::query("UPDATE ".DB::table('common_member_validate')." SET submittimes=submittimes+1, submitdate='$_G[timestamp]', status='0', message='".dhtmlspecialchars($regmessagenew)."'
			WHERE uid='$_G[uid]'");
		showmessage('submit_verify_succeed', 'home.php?mod=space&do=home');
	} else {
		showmessage('undefined_action', NULL);
	}

}

?>