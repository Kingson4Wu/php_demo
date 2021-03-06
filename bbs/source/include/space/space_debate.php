<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_debate.php 10930 2010-05-18 07:34:59Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$minhot = $_G['setting']['feedhotmin']<1?3:$_G['setting']['feedhotmin'];
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$id = empty($_GET['id'])?0:intval($_GET['id']);

if(empty($_GET['view'])) $_GET['view'] = 'we';
$_GET['order'] = empty($_GET['order']) ? 'dateline' : $_GET['order'];
$perpage = 20;
$perpage = mob_perpage($perpage);
$start = ($page-1)*$perpage;
ckstart($start, $perpage);

$list = array();
$userlist = array();
$count = $pricount = 0;

$gets = array(
	'mod' => 'space',
	'uid' => $space['uid'],
	'do' => 'debate',
	'view' => $_GET['view'],
	'order' => $_GET['order'],
	'type' => $_GET['type'],
	'fuid' => $_GET['fuid'],
	'searchkey' => $_GET['searchkey']
);
$theurl = 'home.php?'.url_implode($gets);
$multi = '';

$wheresql = '1';
$apply_sql = '';

$f_index = '';
$ordersql = 't.dateline DESC';
$need_count = true;

if($_GET['view'] == 'all') {

	if($_GET['order'] == 'hot') {
		$wheresql = "t.replies>='$minhot'";
	}
	$orderactives = array($_GET['order'] => ' class="a"');

} elseif($_GET['view'] == 'me') {

	if($_GET['type'] == 'reply') {
		$wheresql = "p.authorid = '$space[uid]' AND p.first='0' AND p.tid = t.tid";
		$posttable = getposttable('p');
		$apply_sql = ', '.DB::table($posttable).' p ';
	} else {
		$wheresql = "t.authorid = '$space[uid]'";
	}
	$viewtype = in_array($_G['gp_type'], array('orig', 'reply')) ? $_G['gp_type'] : 'orig';
	$typeactives = array($viewtype => ' class="a"');

} else {

	space_merge($space, 'field_home');

	if($space['feedfriend']) {

		$fuid_actives = array();

		require_once libfile('function/friend');
		$fuid = intval($_GET['fuid']);
		if($fuid && friend_check($fuid, $space['uid'])) {
			$wheresql = "t.authorid='$fuid'";
			$fuid_actives = array($fuid=>' selected');
		} else {
			$wheresql = "t.authorid IN ($space[feedfriend])";
			$theurl = "home.php?mod=space&uid=$space[uid]&do=$do&view=we";
		}

		$query = DB::query("SELECT * FROM ".DB::table('home_friend')." WHERE uid='$space[uid]' ORDER BY num DESC LIMIT 0,100");
		while ($value = DB::fetch($query)) {
			$userlist[] = $value;
		}
	} else {
		$need_count = false;
	}
}

$actives = array($_GET['view'] =>' class="a"');

if($need_count) {

	$wheresql .= " AND t.special='5'";
	if($searchkey = stripsearchkey($_GET['searchkey'])) {
		$wheresql .= " AND t.subject LIKE '%$searchkey%'";
	}

	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('forum_thread')." t $apply_sql WHERE $wheresql"),0);
	if($count) {
		$field = $apply_sql ? ', p.message' : '';
		$query = DB::query("SELECT t.* $field FROM ".DB::table('forum_thread')." t $apply_sql
			WHERE $wheresql
			ORDER BY $ordersql LIMIT $start,$perpage");
	}
}

if($count) {
	$dids = $special = $multitable = $tids = array();
	require_once libfile('function/post');
	while($value = DB::fetch($query)) {
		$value['dateline'] = dgmdate($value['dateline']);
		if($_GET['view'] == 'me' && $_GET['type'] == 'reply' && $page == 1 && count($special) < 2) {
			$value['message'] = messagecutstr($value['message'], 200);
			$special[$value['tid']] = $value;
		} else {
			if($page == 1 && count($special) < 2) {
				$tids[$value['posttableid']][$value['tid']] = $value['tid'];
				$special[$value['tid']] = $value;
			} else {
				$list[$value['tid']] = $value;
			}
		}
		$dids[$value['tid']] = $value['tid'];
	}
	if($tids) {
		foreach($tids as $postid => $tid) {
			$posttable = getposttable('p');
			$query = DB::query("SELECT tid, message FROM ".DB::table($posttable)." WHERE tid IN(".dimplode($tid).")");
			while($value = DB::fetch($query)) {
				$special[$value['tid']]['message'] = messagecutstr($value['message'], 200);
			}
		}
	}
	if($dids) {
		$query = DB::query("SELECT * FROM ".DB::table('forum_debate')." WHERE tid IN(".dimplode($dids).")");
		while($value = DB::fetch($query)) {
			$value['negavotesheight'] = $value['affirmvotesheight'] = '8px';
			if($value['affirmvotes'] || $value['negavotes']) {
				$allvotes = $value['affirmvotes'] + $value['negavotes'];
				$value['negavotesheight'] = round($value['negavotes']/$allvotes * 100, 2).'%';
				$value['affirmvotesheight'] = round($value['affirmvotes']/$allvotes * 100, 2).'%';
			}
			if($list[$value['tid']]) {
				$list[$value['tid']] = array_merge($value, $list[$value['tid']]);
			} elseif($special[$value['tid']]) {
				$special[$value['tid']] = array_merge($value, $special[$value['tid']]);
			}
		}
	}

	$multi = multi($count, $perpage, $page, $theurl);
}

include_once template("diy:home/space_debate");

?>