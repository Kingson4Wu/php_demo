<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_reward.php 10930 2010-05-18 07:34:59Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$minhot = $_G['setting']['feedhotmin']<1?3:$_G['setting']['feedhotmin'];
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$id = empty($_GET['id'])?0:intval($_GET['id']);
$_G['gp_flag'] = empty($_G['gp_flag']) ? 0 : intval($_G['gp_flag']);

if(empty($_GET['view'])) $_GET['view'] = 'we';

$perpage = 20;
$perpage = mob_perpage($perpage);
$start = ($page-1)*$perpage;
ckstart($start, $perpage);

$list = array();
$userlist = array();
$hiddennum = $count = $pricount = 0;

$gets = array(
	'mod' => 'space',
	'uid' => $space['uid'],
	'do' => 'reward',
	'view' => $_GET['view'],
	'order' => $_GET['order'],
	'flag' => $_GET['flag'],
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
require_once libfile('function/misc');
if($_GET['view'] == 'all') {
	$ordertype = in_array($_G['gp_order'], array('new', 'hot')) ? $_G['gp_order'] : 'new';
	if($_GET['order'] == 'hot') {
		$wheresql .= " AND t.replies>='$minhot'";
	}
	$orderactives = array($ordertype => ' class="a"');
} elseif($_GET['view'] == 'me') {
	$wheresql = "t.authorid = '$space[uid]'";
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

	$wheresql .= " AND t.special='3'";

	if($searchkey = stripsearchkey($_GET['searchkey'])) {
		$wheresql .= " AND t.subject LIKE '%$searchkey%'";
	}

	if($_G['gp_flag'] < 0) {
		$wheresql .= " AND t.price < '0'";
	} elseif($_G['gp_flag'] > 0) {
		$wheresql .= " AND t.price > '0'";
	}
	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('forum_thread')." t $apply_sql WHERE $wheresql"),0);
	if($count) {
		$query = DB::query("SELECT t.* FROM ".DB::table('forum_thread')." t $apply_sql
			WHERE $wheresql
			ORDER BY $ordersql LIMIT $start,$perpage");
	}
}

if($count) {
	while($value = DB::fetch($query)) {
		if(empty($value['author']) && $value['authorid'] != $_G['uid']) {
			$hiddennum++;
			continue;
		}
		$list[] = procthread($value);
	}
	$multi = multi($count, $perpage, $page, $theurl);
}
$creditid = 0;
if($_G['setting']['creditstransextra'][2]) {
	$creditid = intval($_G['setting']['creditstransextra'][2]);
} elseif ($_G['setting']['creditstrans']) {
	$creditid = intval($_G['setting']['creditstrans']);
}

include_once template("diy:home/space_reward");

?>