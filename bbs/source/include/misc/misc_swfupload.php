<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: misc_swfupload.php 10092 2010-05-06 11:06:16Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/spacecp');

$op = empty($_GET['op'])?'':$_GET['op'];
$isupload = empty($_GET['cam']) && empty($_GET['doodle']) ? true : false;
$iscamera = isset($_GET['cam']) ? true : false;
$isdoodle = isset($_GET['doodle']) ? true : false;
$fileurl = '';
if(!empty($_POST['uid'])) {
	$_G['uid'] = intval($_POST['uid']);
	if(empty($_G['uid']) || $_POST['hash'] != md5($_G['uid'].UC_KEY)) {
		exit();
	}
	$member = DB::fetch_first("SELECT * FROM ".DB::table('common_member')." WHERE uid='$_G[uid]' LIMIT 1");
	$_G['username'] = addslashes($member['username']);

	loadcache('usergroup_'.$member['groupid']);
	$_G['group'] = $_G['cache']['usergroup_'.$member['groupid']];

} elseif (empty($_G['uid'])) {
	showmessage('to_login', 'member.php?mod=logging&action=login', array(), array('showmsg' => true, 'login' => 1));
}

if($op == "finish") {

	$albumid = intval($_GET['albumid']);

	if($albumid > 0) {
		album_update_pic($albumid);
	}

	$space = getspace($_G['uid']);

	if(ckprivacy('upload', 'feed')) {
		require_once libfile('function/feed');
		feed_publish($albumid, 'albumid');
	}

	exit();

} elseif($op == 'config') {

	$hash = md5($_G['uid'].UC_KEY);
	$uploadurl = urlencode(getsiteurl().'home.php?mod=misc&ac=swfupload'.($iscamera ? '&op=screen' : ($isdoodle ? '&op=doodle&from=':'')));
	if($isupload && !checkperm('allowupload')) {
		$hash = '';
	} else {
		$filearr = $dirstr = array();
		if($iscamera) {
			$directory = dreaddir(DISCUZ_ROOT.'./static/image/foreground');
			foreach($directory as $key => $value) {
				$dirstr = DISCUZ_ROOT.'./static/image/foreground/'.$value;
				if(is_dir($dirstr)) {
					$filearr = dreaddir($dirstr, array('jpg','jpeg','gif','png'));
					if(!empty($filearr)) {
						if(is_file($dirstr.'/categories.txt')) {
							$catfile = @file($dirstr.'/categories.txt');
							$dirarr[$key][0] = trim($catfile[0]);
						} else {
							$dirarr[$key][0] = trim($value);
						}
						$dirarr[$key][1] = trim('static/image/foreground/'.$value.'/');
						$dirarr[$key][2] = $filearr;
					}
				}
			}
		} elseif($isdoodle) {
			$filearr = dreaddir(DISCUZ_ROOT.'./static/image/doodle/big', array('jpg','jpeg','gif','png'));
		}
	}
	$feedurl = urlencode(getsiteurl().'home.php?mod=misc&ac=swfupload&op=finish&random='.random(8).'&albumid=');
	$albumurl = urlencode(getsiteurl().'home.php?mod=space&do=album'.($isdoodle ? '&picid=' : '&id='));
	$max = @ini_get(upload_max_filesize);
	$unit = strtolower(substr($max, -1, 1));
	if($unit == 'k') {
		$max = intval($max)*1024;
	} elseif($unit == 'm') {
		$max = intval($max)*1024*1024;
	} elseif($unit == 'g') {
		$max = intval($max)*1024*1024*1024;
	}
	$albums = getalbums($_G['uid']);

} elseif($op == "screen" || $op == "doodle") {

	if(empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
		$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents("php://input");
	}
	$status = "failure";
	$dosave = true;

	if($op == "doodle") {
		$query = DB::query('SELECT mm.* FROM '.DB::table('common_magic').' cm LEFT JOIN '.DB::table('common_member_magic')." mm ON mm.uid = '$_G[uid]' AND mm.magicid = cm.magicid WHERE cm.identifier = 'doodle'");
		$magic = DB::fetch($query);
		if(empty($magic) || $magic['num'] < 1) {
			$uploadfiles = -8;
			$dosave = false;
		}
	}

	if($dosave && !empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
		$_SERVER['HTTP_ALBUMID'] = addslashes(diconv(urldecode($_SERVER['HTTP_ALBUMID']), 'UTF-8'));
		$from = false;
		if($op == 'screen') {
			$from = 'camera';
		} elseif($_GET['from'] == 'album') {
			$from = 'uploadimage';
		}
		$_G['setting']['allowwatermark'] = 0;
		$uploadfiles = stream_save($GLOBALS['HTTP_RAW_POST_DATA'], $_SERVER['HTTP_ALBUMID'], 'jpg', '', '', 0, $from);
	}

	$uploadResponse = true;
	$picid = $proid = $albumid = 0;
	if($uploadfiles && is_array($uploadfiles)) {
		$status = "success";
		$albumid = $uploadfiles['albumid'];
		$picid =  $uploadfiles['picid'];
		if($op == "doodle") {
			$fileurl = pic_get($uploadfiles['filepath'], 'album', $uploadfiles['thumb'], $uploadfiles['remote'], 0);

			require_once libfile('function/magic');
			usemagic($magic['magicid'], $magic['num'], 1);
			updatemagiclog($magic['magicid'], '2', '1', '0');
			if($albumid > 0) {
				album_update_pic($albumid);
			}
		}
	} else {
		switch ($uploadfiles) {
			case -1:
				$uploadfiles = lang('spacecp', 'inadequate_capacity_space');
				break;
			case -2:
				$uploadfiles = lang('spacecp', 'only_allows_upload_file_types');
				break;
			case -4:
				$uploadfiles = lang('spacecp', 'ftp_upload_file_size');
				break;
			case -8:
				$uploadfiles = lang('spacecp', 'has_not_more_doodle');
				break;
			default:
				$uploadfiles = lang('spacecp', 'mobile_picture_temporary_failure');
				break;
		}
	}

} elseif($_FILES && $_POST) {

	if($_FILES["Filedata"]['error']) {
		$uploadfiles = lang('spacecp', 'file_is_too_big');
	} else {
		$_FILES["Filedata"]['name'] = addslashes(diconv(urldecode($_FILES["Filedata"]['name']), 'UTF-8'));
		$_POST['albumid'] = addslashes(diconv(urldecode($_POST['albumid']), 'UTF-8'));
		$uploadfiles = pic_save($_FILES["Filedata"], $_POST['albumid'], addslashes(diconv(urldecode($_POST['title']), 'UTF-8')));
	}
	$proid = $_POST['proid'];
	$uploadResponse = true;
	$albumid = 0;
	if($uploadfiles && is_array($uploadfiles)) {
		$status = "success";
		$albumid = $uploadfiles['albumid'];
	} else {
		$status = "failure";
	}
}

$newalbumname = dgmdate($_G['timestamp'], 'Ymd');

include template("home/misc_swfupload");
$outxml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$outxml .= diconv(ob_get_contents(), $_G['charset'], 'UTF-8');
obclean();

@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: application/xml; charset=utf-8");
echo $outxml;

?>