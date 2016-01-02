<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_share.php 7910 2010-04-15 01:55:08Z liguode $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_GET['op'] == 'delete') {

	$favid = intval($_GET['favid']);
	$thevalue = DB::fetch_first('SELECT * FROM '.DB::table('home_favorite')." WHERE favid='$favid'");
	if(empty($thevalue) || $thevalue['uid'] != $_G['uid']) {
		showmessage('favorite_does_not_exist');
	}

	if(submitcheck('deletesubmit')) {
		DB::query('DELETE FROM '.DB::table('home_favorite')." WHERE favid='$favid'");
		showmessage('do_success', 'home.php?mod=space&do=favorite&view=me&type='.$_GET['type'].'&quickforward=1', array('favid' => $favid), array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1));
	}

} else {

	ckrealname('favorite');

	ckvideophoto('favorite');

	cknewuser();

	$type = empty($_GET['type']) ? '' : $_GET['type'];
	$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
	$spaceuid = empty($_GET['spaceuid']) ? 0 : intval($_GET['spaceuid']);
	$idtype = $title = $icon = '';
	switch($type) {
		case 'thread':
			$idtype = 'tid';
			$title = DB::result_first('SELECT subject FROM '.DB::table('forum_thread')." WHERE tid='$id'");
			$icon = '<img src="static/image/feed/thread.gif" alt="thread" class="vm" /> ';
			break;
		case 'forum':
			$idtype = 'fid';
			$title = DB::result_first('SELECT `name` FROM '.DB::table('forum_forum')." WHERE fid='$id' AND status !='3'");
			$icon = '<img src="static/image/feed/discuz.gif" alt="forum" class="vm" /> ';
			break;
		case 'blog';
			$idtype = 'blogid';
			$title = DB::result_first('SELECT subject FROM '.DB::table('home_blog')." WHERE blogid='$id' AND uid='$spaceuid'");
			$icon = '<img src="static/image/feed/blog.gif" alt="blog" class="vm" /> ';
			break;
		case 'group';
			$idtype = 'gid';
			$title = DB::result_first('SELECT `name` FROM '.DB::table('forum_forum')." WHERE fid='$id' AND status ='3'");
			$icon = '<img src="static/image/feed/group.gif" alt="group" class="vm" /> ';
			break;
		case 'album';
			$idtype = 'albumid';
			$title = DB::result_first('SELECT albumname FROM '.DB::table('home_album')." WHERE albumid='$id' AND uid='$spaceuid'");
			$icon = '<img src="static/image/feed/album.gif" alt="album" class="vm" /> ';
			break;
		case 'space';
			$idtype = 'uid';
			$title = DB::result_first('SELECT username FROM '.DB::table('common_member')." WHERE uid='$id'");
			$icon = '<img src="static/image/feed/profile.gif" alt="space" class="vm" /> ';
			break;
	}
	if(empty($idtype) || empty($title)) {
		showmessage('favorite_cannot_favorite');
	}

	$thevalue = DB::fetch_first('SELECT * FROM '.DB::table('home_favorite')." WHERE uid='$_G[uid]' AND idtype='$idtype' AND id='$id'");
	$description = !empty($thevalue) ? $thevalue['description'] : '';
	$description_show = nl2br($description);

	if(submitcheck('favoritesubmit')) {
		$arr = array(
			'uid' => intval($_G['uid']),
			'idtype' => $idtype,
			'id' => $id,
			'spaceuid' => $spaceuid,
			'title' => getstr($title, 255, 0, 1),
			'description' => getstr($_POST['description'], '', 1, 1, 1),
			'dateline' => TIMESTAMP
		);
		if($thevalue) {
			DB::update('home_favorite', $arr, array('favid'=>intval($thevalue['favid'])));
		} else {
			DB::insert('home_favorite', $arr);
		}
		showmessage('favorite_do_success', dreferer(), array(), array('showdialog'=>true, 'closetime'=>1));
	}
}

include template('home/spacecp_favorite');


?>