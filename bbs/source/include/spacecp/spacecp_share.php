<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_share.php 10965 2010-05-18 11:27:56Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sid = intval($_GET['sid']);

if($_GET['op'] == 'delete') {
	if(submitcheck('deletesubmit')) {
		require_once libfile('function/delete');
		deleteshares(array($sid));
		showmessage('do_success', $_GET['type']=='view'?'home.php?mod=space&quickforward=1&do=share':dreferer(), array('sid' => $sid), array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1));
	}
} elseif($_GET['op'] == 'edithot') {
	if(!checkperm('manageshare')) {
		showmessage('no_privilege');
	}

	if($sid) {
		$query = DB::query("SELECT * FROM ".DB::table('home_share')." WHERE sid='$sid'");
		if(!$share = DB::fetch($query)) {
			showmessage('no_privilege');
		}
	}

	if(submitcheck('hotsubmit')) {
		$_POST['hot'] = intval($_POST['hot']);
		DB::update('home_share', array('hot'=>$_POST['hot']), array('sid'=>$sid));
		DB::update('home_feed', array('hot'=>$_POST['hot']), array('id'=>$sid, 'idtype'=>'sid'));

		showmessage('do_success', dreferer());
	}

} else {


	if(!checkperm('allowshare')) {
		showmessage('no_privilege');
	}
	ckrealname('share');

	ckvideophoto('share');

	cknewuser();

	$type = empty($_GET['type'])?'':$_GET['type'];
	$id = empty($_GET['id'])?0:intval($_GET['id']);
	$note_uid = 0;
	$note_message = '';
	$note_values = array();

	$hotarr = array();

	$arr = array();
	$feed_hash_data = '';

	switch ($type) {
		case 'space':

			$feed_hash_data = "uid{$id}";

			if($id == $space['uid']) {
				showmessage('share_space_not_self');
			}

			$tospace = getspace($id);
			if(empty($tospace)) {
				showmessage('space_does_not_exist');
			}
			if(isblacklist($tospace['uid'])) {
				showmessage('is_blacklist');
			}

			$arr['title_template'] = lang('spacecp', 'share_space');
			$arr['body_template'] = '<b>{username}</b><br>{reside}<br>{spacenote}';
			$arr['body_data'] = array(
			'username' => "<a href=\"home.php?mod=space&uid=$id\">".$tospace['username']."</a>",
			'reside' => $tospace['resideprovince'].$tospace['residecity'],
			'spacenote' => $tospace['spacenote']
			);

			loaducenter();
			$isavatar = uc_check_avatar($id);
			$arr['image'] = $isavatar?avatar($id, 'middle', true):UC_API.'/images/noavatar_middle.gif';
			$arr['image_link'] = "home.php?mod=space&uid=$id";

			$note_uid = $id;
			$note_message = 'share_space';

			break;
		case 'blog':

			$feed_hash_data = "blogid{$id}";

			$query = DB::query("SELECT b.*,bf.message,bf.hotuser FROM ".DB::table('home_blog')." b
				LEFT JOIN ".DB::table('home_blogfield')." bf ON bf.blogid=b.blogid
				WHERE b.blogid='$id'");
			if(!$blog = DB::fetch($query)) {
				showmessage('blog_does_not_exist');
			}
			if($blog['uid'] == $space['uid']) {
				showmessage('share_not_self');
			}
			if($blog['friend']) {
				showmessage('logs_can_not_share');
			}
			if(isblacklist($blog['uid'])) {
				showmessage('is_blacklist');
			}

			$arr['title_template'] = lang('spacecp', 'share_blog');
			$arr['body_template'] = '<b>{subject}</b><br>{username}<br>{message}';
			$arr['body_data'] = array(
			'subject' => "<a href=\"home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]\">$blog[subject]</a>",
			'username' => "<a href=\"home.php?mod=space&uid=$blog[uid]\">".$blog['username']."</a>",
			'message' => getstr($blog['message'], 150, 0, 1, 0, 0, -1)
			);
			if($blog['pic']) {
				$arr['image'] = pic_cover_get($blog['pic'], $blog['picflag']);
				$arr['image_link'] = "home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]";
			}
			$note_uid = $blog['uid'];
			$note_message = 'share_blog';
			$note_values = array('url'=>"home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]", 'subject'=>$blog['subject']);

			$hotarr = array('blogid', $blog['blogid'], $blog['hotuser']);

			break;
		case 'album':

			$feed_hash_data = "albumid{$id}";

			$query = DB::query("SELECT * FROM ".DB::table('home_album')." WHERE albumid='$id'");
			if(!$album = DB::fetch($query)) {
				showmessage('album_does_not_exist');
			}
			if($album['uid'] == $space['uid']) {
				showmessage('share_not_self');
			}
			if($album['friend']) {
				showmessage('album_can_not_share');
			}
			if(isblacklist($album['uid'])) {
				showmessage('is_blacklist');
			}

			$arr['title_template'] =  lang('spacecp', 'share_album');
			$arr['body_template'] = '<b>{albumname}</b><br>{username}';
			$arr['body_data'] = array(
				'albumname' => "<a href=\"home.php?mod=space&uid=$album[uid]&do=album&id=$album[albumid]\">$album[albumname]</a>",
				'username' => "<a href=\"home.php?mod=space&uid=$album[uid]\">".$album['username']."</a>"
			);
			$arr['image'] = pic_cover_get($album['pic'], $album['picflag']);
			$arr['image_link'] = "home.php?mod=space&uid=$album[uid]&do=album&id=$album[albumid]";
			$note_uid = $album['uid'];
			$note_message = 'share_album';
			$note_values = array('url'=>"home.php?mod=space&uid=$album[uid]&do=album&id=$album[albumid]", 'albumname'=>$album['albumname']);

			break;
		case 'pic':

			$feed_hash_data = "picid{$id}";

			$query = DB::query("SELECT album.username, album.albumid, album.albumname, album.friend, pf.*, pic.*
				FROM ".DB::table('home_pic')." pic
				LEFT JOIN ".DB::table('home_picfield')." pf ON pf.picid=pic.picid
				LEFT JOIN ".DB::table('home_album')." album ON album.albumid=pic.albumid
				WHERE pic.picid='$id'");
			if(!$pic = DB::fetch($query)) {
				showmessage('image_does_not_exist');
			}
			if($pic['uid'] == $space['uid']) {
				showmessage('share_not_self');
			}
			if($pic['friend']) {
				showmessage('image_can_not_share');
			}
			if(isblacklist($pic['uid'])) {
				showmessage('is_blacklist');
			}
			if(empty($pic['albumid'])) $pic['albumid'] = 0;
			if(empty($pic['albumname'])) $pic['albumname'] = lang('spacecp', 'default_albumname');


			$arr['title_template'] = lang('spacecp', 'share_image');
			$arr['body_template'] = lang('spacecp', 'album').': <b>{albumname}</b><br>{username}<br>{title}';
			$arr['body_data'] = array(
			'albumname' => "<a href=\"home.php?mod=space&uid=$pic[uid]&do=album&id=$pic[albumid]\">$pic[albumname]</a>",
			'username' => "<a href=\"home.php?mod=space&uid=$pic[uid]\">".$pic['username']."</a>",
			'title' => getstr($pic['title'], 100, 0, 1, 0, 0, -1)
			);
			$arr['image'] = pic_get($pic['filepath'], 'album', $pic['thumb'], $pic['remote']);
			$arr['image_link'] = "home.php?mod=space&uid=$pic[uid]&do=album&picid=$pic[picid]";
			$note_uid = $pic['uid'];
			$note_message = 'share_pic';
			$note_values = array('url'=>"home.php?mod=space&uid=$pic[uid]&do=album&picid=$pic[picid]", 'albumname'=>$pic['albumname']);

			$hotarr = array('picid', $pic['picid'], $pic['hotuser']);

			break;

		case 'thread':

			$feed_hash_data = "tid{$id}";

			$actives = array('share' => ' class="active"');

			$thread = DB::fetch(DB::query("SELECT * FROM ".DB::table('forum_thread')." WHERE tid='$id'"));
			$posttable = getposttable('p');
			$post = DB::fetch(DB::query("SELECT * FROM ".DB::table($posttable)." WHERE tid='$id' AND first='1'"));

			$arr['title_template'] = lang('spacecp', 'share_thread');
			$arr['body_template'] = '<b>{subject}</b><br>{author}<br>{message}';
			$arr['body_data'] = array(
				'subject' => "<a href=\"forum.php?mod=viewthread&tid=$id\">$thread[subject]</a>",
				'author' => "<a href=\"home.php?mod=space&uid=$thread[authorid]\">$thread[author]</a>",
				'message' => getstr($post['message'], 150, 0, 1, 0, 0, -1)
			);
			$note_uid = $thread['authorid'];
			$note_message = 'share_thread';
			$note_values = array('url'=>"forum.php?mod=viewthread&tid=$id", 'subject'=>$thread['subject']);
			break;

		default:

			$actives = array('share' => ' class="active"');

			$_G['refer'] = 'home.php?mod=space&do=share&view=me';
			$type = 'link';
			$_GET['op'] = 'link';
			$linkdefault = 'http://';
			$generaldefault = '';
			break;
	}

	if(submitcheck('sharesubmit', 0, $seccodecheck, $secqaacheck)) {
		if($type == 'link') {
			$link = dhtmlspecialchars(trim($_POST['link']));
			if($link) {
				if(!preg_match("/^(http|ftp|https|mms)\:\/\/.{4,300}$/i", $link)) $link = '';
			}
			if(empty($link)) {
				showmessage('url_incorrect_format');
			}
			$arr['title_template'] = lang('spacecp', 'share_link');
			$arr['body_template'] = '{link}';

			$link_text = sub_url($link, 45);

			$arr['body_data'] = array('link'=>"<a href=\"$link\" target=\"_blank\">$link_text</a>", 'data'=>$link);
			$parseLink = parse_url($link);
			require_once libfile('function/discuzcode');
			$flashvar = parseflv($link);
			if(empty($flashvar) && preg_match("/\.flv$/i", $link)) {
				$flashvar = array(
					'flv' => IMGDIR.'/flvplayer.swf?&autostart=true&file='.urlencode($link),
					'imgurl' => ''
				);
			}
			if(!empty($flashvar)) {
				$arr['title_template'] = lang('spacecp', 'share_video');
				$type = 'video';
				$arr['body_data']['flashvar'] = $flashvar['flv'];
				$arr['body_data']['host'] = 'flash';
				$arr['body_data']['imgurl'] = $flashvar['imgurl'];
			}
			if(preg_match("/\.(mp3|wma)$/i", $link)) {
				$arr['title_template'] = lang('spacecp', 'share_music');
				$arr['body_data']['musicvar'] = $link;
				$type = 'music';
			}
			if(preg_match("/\.swf$/i", $link)) {
				$arr['title_template'] = lang('spacecp', 'share_flash');
				$arr['body_data']['flashaddr'] = $link;
				$type = 'flash';
			}
		}

		$arr['body_general'] = getstr($_POST['general'], 150, 1, 1, 1, 1);

		$arr['type'] = $type;
		$arr['uid'] = $_G['uid'];
		$arr['username'] = $_G['username'];
		$arr['dateline'] = $_G['timestamp'];


		if(ckprivacy('share', 'feed')) {
			require_once libfile('function/feed');
			feed_add('share',
				'{actor} '.$arr['title_template'],
				array('hash_data' => $feed_hash_data),
				$arr['body_template'],
				$arr['body_data'],
				$arr['body_general'],
				array($arr['image']),
				array($arr['image_link'])
			);
		}

		$arr['body_data'] = serialize($arr['body_data']);

		$setarr = daddslashes($arr);
		$sid = DB::insert('home_share', $setarr, 1);

		include_once libfile('function/stat');
		updatestat('share');

		if($note_uid && $note_uid != $_G['uid']) {
			notification_add($note_uid, 'sharenotice', $note_message, $note_values);
		}

		$needle = $id ? $type.$id : '';
		updatecreditbyaction('createshare', $_G['uid'], array('sharings' => 1), $needle);

		$referer = "home.php?mod=space&do=share&view=$_GET[view]&from=$_GET[from]";
		showmessage('do_success', $referer, array('sid' => $sid), array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1));
	}

	$arr['body_data'] = serialize($arr['body_data']);

	require_once libfile('function/share');
	$arr = mkshare($arr);
	$arr['dateline'] = $_G['timestamp'];
}

include template('home/spacecp_share');
?>