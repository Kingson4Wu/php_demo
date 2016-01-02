<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: group_index.php 9843 2010-05-05 05:38:57Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$gid = intval(getgpc('gid'));
$sgid = intval(getgpc('sgid'));
$groupids = $groupnav = $typelist = '';
$selectorder = array('default' => '', 'thread' => '', 'membernum' => '', 'dateline' => '', 'activity' => '');
if(!empty($_G['gp_orderby'])) {
	$selectorder[$_G['gp_orderby']] = 'selected';
} else {
	$selectorder['default'] = 'selected';
}
$first = &$_G['cache']['grouptype']['first'];
$second = &$_G['cache']['grouptype']['second'];
require_once libfile('function/group');
require_once libfile('function/friend');
$url = $_G['basescript'].'.php';
$navtitle = '';
if($gid) {
	if(!empty($first[$gid])) {
		$curtype = $first[$gid];
		foreach($curtype['secondlist'] as $fid) {
			$typelist[$fid] = $second[$fid];
		}
		$groupids = $first[$gid]['secondlist'];
		$url .= '?gid='.$gid;
	} else {
		$gid = 0;
	}
} elseif($sgid) {
	if(!empty($second[$sgid])) {
		$curtype = $second[$sgid];
		$fup = $curtype['fup'];
		$groupids = array($sgid);
		$url .= '?sgid='.$sgid;
	} else {
		$sgid = 0;
	}
}

if(empty($curtype)) {
	$curtype = array();
	$navtitle = lang('group/template', 'group_index');
} else {
	$_G['grouptypeid'] = $curtype['fid'];
	$navtitle = lang('group/template', 'group').' - '.$curtype['name'];
	$groupnav = get_groupnav($curtype);
	$perpage = 10;
	if($curtype['forumcolumns'] > 1) {
		$curtype['forumcolwidth'] = floor(99 / $curtype['forumcolumns']).'%';
		$perpage = $curtype['forumcolumns'] * 10;
	}
}

$data = $randgrouplist = $randgroupdata = $grouptop = $newgrouplist = array();
$randgroupdata = $_G['cache']['groupindex']['randgroupdata'];
$topgrouplist = $_G['cache']['groupindex']['topgrouplist'];
$newgrouparray = $_G['cache']['groupindex']['newgrouparray'];
$joinnum = intval($_G['cache']['groupindex']['joinnum']);
$todayposts = intval($_G['cache']['groupindex']['todayposts']);
$groupnum = intval($_G['cache']['groupindex']['groupnum']);
$cachetimeupdate = TIMESTAMP - intval($_G['cache']['groupindex']['updateline']);

if(empty($_G['cache']['groupindex']) || $cachetimeupdate > 3600) {
	$data['randgroupdata'] = $randgroupdata = grouplist('lastupdate', array('ff.membernum', 'ff.icon'), 80);
	$data['newgrouparray'] = $newgrouparray = grouplist('dateline', array('ff.icon, ff.membernum'), 10);
	$data['topgrouplist'] = $topgrouplist = grouplist('activity', array('f.fid', 'f.name', 'ff.membernum', 'ff.icon'), 8);
	$data['joinnum'] = $joinnum = DB::result_first("SELECT COUNT(DISTINCT uid) FROM ".DB::table('forum_groupuser')."");
	$data['updateline'] = TIMESTAMP;
	$groupdata = DB::fetch_first("SELECT SUM(todayposts) AS todayposts, COUNT(fid) AS groupnum FROM ".DB::table('forum_forum')." WHERE status='3' AND type='sub'");
	$data['todayposts'] = $todayposts = $groupdata['todayposts'];
	$data['groupnum'] = $groupnum = $groupdata['groupnum'];
	save_syscache('groupindex', $data);
}

if($newgrouparray) {
	foreach($newgrouparray as $groupid => $group) {
		$newgrouplist[$groupid]['icon'] = $group['icon'];
		$newgrouplist[$groupid]['name'] = cutstr($group['name'], 16);
		$newgrouplist[$groupid]['membernum'] = $group['membernum'];
	}
}

$randgroup = $list = array();
if($groupids) {
	$orderby = in_array(getgpc('orderby'), array('membernum', 'dateline', 'thread', 'activity')) ? getgpc('orderby') : 'displayorder';
	$page = intval(getgpc('page')) ? intval($_G['gp_page']) : 1;
	$start = ($page - 1) * $perpage;
	$getcount = grouplist('', '', '', $groupids, 1, 1);
	if($getcount) {
		$list = grouplist($orderby, '', array($start, $perpage), $groupids, 1);
		$multipage = multi($getcount, $perpage, $page, $url."&orderby=$orderby");
	}

} else {
	if($randgroupdata) {
		foreach($randgroupdata as $groupid => $rgroup) {
			if($rgroup['iconstatus']) {
				$randgrouplist[$groupid] = $rgroup;
			}
		}
	}

	if(count($randgrouplist) > 8) {
		foreach(array_rand($randgrouplist, min(8, count($randgrouplist))) as $fid) {
			$randgroup[] = $randgrouplist[$fid];
		}
	} elseif (count($randgrouplist)) {
		$randgroup = $randgrouplist;
	}
}

$frienduid = friend_list($_G['uid'], 50);
$frienduidarray = $friendgrouplist = array();
if($frienduid && is_array($frienduid)) {
	foreach($frienduid as $friend) {
		$frienduidarray[] = $friend['fuid'];
	}

	$query = DB::query("SELECT f.fid, f.name, ff.icon
		FROM ".DB::table('forum_forum')." f
		LEFT JOIN ".DB::table("forum_forumfield")." ff ON ff.fid=f.fid
		LEFT JOIN ".DB::table('forum_groupuser')." g ON g.fid=f.fid
		WHERE g.uid IN (".dimplode($frienduidarray).") LIMIT 0, 6");
	while($group = DB::fetch($query)) {
		$group['icon'] = get_groupimg($group['icon'], 'icon');
		$friendgrouplist[$group['fid']] = $group;
	}
}

$groupviewed_list = get_viewedgroup();

include template('diy:group/index');

?>