<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_share.php 6752 2010-03-25 08:47:54Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$space = getspace($_G['uid']);

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$id = empty($_GET['id'])?0:intval($_GET['id']);

$perpage = 20;

$start = ($page-1)*$perpage;
ckstart($start, $perpage);

$idtypes = array('thread'=>'tid', 'forum'=>'fid', 'blog'=>'blogid', 'group'=>'gid', 'album'=>'albumid', 'space'=>'uid');
$_GET['type'] = isset($idtypes[$_GET['type']]) ? $_GET['type'] : 'all';
$actives = array($_GET['type'] => ' class="a"');

$gets = array(
	'mod' => 'space',
	'uid' => $space['uid'],
	'do' => 'favorite',
	'view' => 'me',
	'type' => $_GET['type'],
	'from' => $_GET['from']
);
$theurl = 'home.php?'.url_implode($gets);

$idtype = isset($idtypes[$_GET['type']]) ? $idtypes[$_GET['type']] : '';
$wheresql = $idtype ? "uid='$_G[uid]' AND idtype='$idtype'" : "uid='$_G[uid]'";

$list = array();
$favid = empty($_GET['favid'])?0:intval($_GET['favid']);
$favsql = $sid?"favid='$favid' AND":'';

$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('home_favorite')." WHERE $favsql $wheresql"),0);
if($count) {
	$query = DB::query("SELECT * FROM ".DB::table('home_favorite')."
		WHERE $favsql $wheresql
		ORDER BY dateline DESC
		LIMIT $start,$perpage");
	$icons = array(
		'tid'=>'<img src="static/image/feed/thread.gif" alt="thread" class="t" /> ',
		'fid'=>'<img src="static/image/feed/discuz.gif" alt="forum" class="t" /> ',
		'blogid'=>'<img src="static/image/feed/blog.gif" alt="blog" class="t" /> ',
		'gid'=>'<img src="static/image/feed/group.gif" alt="group" class="t" /> ',
		'uid'=>'<img src="static/image/feed/profile.gif" alt="space" class="t" /> ',
		'albumid'=>'<img src="static/image/feed/album.gif" alt="album" class="t" /> ',
	);
	while ($value = DB::fetch($query)) {
		$value['icon'] = isset($icons[$value['idtype']]) ? $icons[$value['idtype']] : '';
		$value['url'] = makeurl($value['id'], $value['idtype'], $value['spaceuid']);
		$value['description'] = !empty($value['description']) ? nl2br($value['description']) : '';
		$list[$value['favid']] = $value;
	}
}

$multi = multi($count, $perpage, $page, $theurl);

dsetcookie('home_diymode', $diymode);

include_once template("diy:home/space_favorite");

function makeurl($id, $idtype, $spaceuid=0) {
	$url = '';
	switch($idtype) {
		case 'tid':
			$url = 'forum.php?mod=viewthread&tid='.$id;
			break;
		case 'fid':
			$url = 'forum.php?mod=forumdisplay&fid='.$id;
			break;
		case 'blogid':
			$url = 'home.php?mod=space&uid='.$spaceuid.'&do=blog&id='.$id;
			break;
		case 'gid':
			$url = 'forum.php?mod=group&fid='.$id;
			break;
		case 'uid':
			$url = 'home.php?mod=space&uid='.$id;
			break;
		case 'albumid':
			$url = 'home.php?mod=space&uid='.$spaceuid.'&do=album&id='.$id;
			break;
	}
	return $url;
}

?>