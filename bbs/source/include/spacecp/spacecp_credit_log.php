<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_credit_log.php 6890 2010-03-26 09:23:38Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$perpage = 20;
$start = ($page-1)*$perpage;

$gets = array(
	'mod' => 'spacecp',
	'op' => $_G['gp_op'],
	'ac' => 'credit',
	'suboperation' => $_G['gp_suboperation']
);
$theurl = 'home.php?'.url_implode($gets);
$multi = '';

if($_G['gp_suboperation'] == 'paymentlog') {

	$page = max(1, intval($_G['gp_page']));
	$start_limit = ($page - 1) * $_G['tpp'];

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='BTC'"), 0);
	$loglist = array();
	if($count) {
		$query = DB::query("SELECT l.*, f.fid, f.name, t.tid, t.subject, t.author, t.authorid, t.dateline AS tdateline
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=l.relatedid
			LEFT JOIN ".DB::table('forum_forum')." f ON f.fid=t.fid
			WHERE l.uid='$_G[uid]' AND l.operation='BTC' ORDER BY l.dateline DESC
			LIMIT $start_limit, $_G[tpp]");

		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['tdateline'] = dgmdate($log['tdateline'], 'u');
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation'] == 'incomelog') {

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='STC'"), 0);
	$loglist = array();
	if($count) {
		$query = DB::query("SELECT cl.*, m.username, t.tid, t.subject, t.dateline AS tdateline
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=l.relatedid
			LEFT JOIN ".DB::table('common_member')." m ON m.uid=l.uid
			LEFT JOIN ".DB::table('common_credit_log')." cl ON t.tid=l.relatedid
			WHERE t.authorid='$_G[uid]' AND cl.operation='STC' AND l.operation='BTC' ORDER BY l.dateline DESC
			LIMIT $start, $perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['tdateline'] = dgmdate($log['tdateline'], 'u');
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation'] == 'attachpaymentlog') {

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='BAC'"), 0);
	$loglist = array();
	if($count) {
		$query = DB::query("SELECT l.*, a.filename, a.pid, a.dateline as adateline, t.subject, t.tid, t.author, t.authorid
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_attachment')." a ON a.aid=l.relatedid
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=a.tid
			WHERE l.uid='$_G[uid]' AND l.operation='BAC' ORDER BY l.dateline DESC
			LIMIT $start, $perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['adateline'] = dgmdate($log['adateline'], 'u');
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation']== 'attachincomelog') {

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='SAC'"), 0);
	if($count) {
		$loglist = array();
		$query = DB::query("SELECT cl.*, a.filename, a.pid, a.dateline as adateline, t.subject, t.tid, m.username
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_attachment')." a ON a.aid=l.relatedid
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=a.tid
			LEFT JOIN ".DB::table('common_member')." m ON m.uid=l.uid
			LEFT JOIN ".DB::table('common_credit_log'). " cl ON a.aid=cl.relatedid
			WHERE t.authorid='$_G[uid]' AND l.operation='BAC' AND cl.operation='SAC' ORDER BY l.dateline DESC
			LIMIT $start,$perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['adateline'] = dgmdate($log['adateline'], 'u');
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation'] == 'rewardpaylog') {

	$loglist = array();
	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='RTC'"), 0);
	if($count) {
		$query = DB::query("SELECT l.*, m.uid, m.username, f.fid, f.name, t.tid, t.subject, t.price
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=l.relatedid
			LEFT JOIN ".DB::table('common_credit_log')." cl ON l.relatedid=cl.relatedid
			LEFT JOIN ".DB::table('forum_forum')." f ON f.fid=t.fid
			LEFT JOIN ".DB::table('common_member')." m ON m.uid=cl.uid
			WHERE l.uid='$_G[uid]' AND l.operation='RTC' AND cl.operation='RAC' ORDER BY l.dateline DESC
			LIMIT $start,$perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['price'] = abs($log['price']);
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation'] == 'rewardincomelog') {

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation='RAC'"), 0);
	if($count) {
		$loglist = array();
		$query = DB::query("SELECT l.*, m.uid, m.username, f.fid, f.name, t.tid, t.subject, t.price
			FROM ".DB::table('common_credit_log')." l
			LEFT JOIN ".DB::table('forum_thread')." t ON t.tid=l.relatedid
			LEFT JOIN ".DB::table('common_credit_log')." cl ON l.relatedid=cl.relatedid
			LEFT JOIN ".DB::table('forum_forum')." f ON f.fid=t.fid
			LEFT JOIN ".DB::table('common_member')." m ON m.uid=cl.uid
			WHERE l.uid='$_G[uid]' AND l.operation='RAC' AND cl.operation='RTC' ORDER BY l.dateline DESC
			LIMIT $start,$perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$log['price'] = abs($log['price']);
			$loglist[] = $log;
		}
	}

} elseif($_G['gp_suboperation'] == 'creditrulelog') {

	$count = DB::result(DB::query("SELECT count(*) FROM ".DB::table('common_credit_rule_log')." WHERE uid='$_G[uid]'"), 0);
	if($count) {
		$query = DB::query("SELECT r.rulename, l.* FROM ".DB::table('common_credit_rule_log')." l LEFT JOIN ".DB::table('common_credit_rule')." r ON r.rid=l.rid WHERE l.uid='$_G[uid]' ORDER BY dateline DESC LIMIT $start,$perpage");
		while($value = DB::fetch($query)) {
			$list[] = $value;
		}
	}
} else {

	loadcache('usergroups');
	$suboperation = 'creditslog';
	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_log')." WHERE uid='$_G[uid]' AND operation IN('TFR', 'RCV', 'CEC', 'ECU', 'AFD', 'UGP', 'TRC')"), 0);
	if($count) {
		$loglist = array();
		$query = DB::query("SELECT l.*, m.uid AS mid, m.username FROM ".DB::table('common_credit_log')." l
							LEFT JOIN ".DB::table('common_member')." m ON m.uid=l.relatedid
							WHERE l.uid='$_G[uid]' AND l.operation IN('TFR', 'RCV', 'CEC', 'ECU', 'AFD', 'UGP', 'TRC')
							ORDER BY dateline DESC LIMIT $start,$perpage");
		while($log = DB::fetch($query)) {
			$log['dateline'] = dgmdate($log['dateline'], 'u');
			$loglist[] = $log;
		}
	}
}

if($count) {
	$multi = multi($count, $perpage, $page, $theurl);
}
include template('home/spacecp_credit_log');
?>