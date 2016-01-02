<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_delete.php 10944 2010-05-18 08:24:54Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/home');

function deletemember($uids, $other = 1) {
	$query = DB::query("DELETE FROM ".DB::table('common_member')." WHERE uid IN ($uids)");
	$numdeleted = DB::affected_rows();
	DB::query("DELETE FROM ".DB::table('common_member_field_forum')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_field_home')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_count')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_log')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_profile')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_security')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_status')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('common_member_validate')." WHERE uid IN ($uids)", 'UNBUFFERED');

	DB::query("DELETE FROM ".DB::table('forum_access')." WHERE uid IN ($uids)", 'UNBUFFERED');
	DB::query("DELETE FROM ".DB::table('forum_moderator')." WHERE uid IN ($uids)", 'UNBUFFERED');

	if($other) {
		DB::query("DELETE FROM ".DB::table('common_member_validate')." WHERE uid IN ($uids)", 'UNBUFFERED');
		DB::query("DELETE FROM ".DB::table('common_member_magic')." WHERE uid IN ($uids)", 'UNBUFFERED');

		$query = DB::query("SELECT uid, attachment, thumb, remote, aid FROM ".DB::table('forum_attachment')." WHERE uid IN ($uids)");
		while($attach = DB::fetch($query)) {
			dunlink($attach);
		}
		DB::query("DELETE FROM ".DB::table('forum_attachment')." WHERE uid IN ($uids)", 'UNBUFFERED');
		DB::query("DELETE FROM ".DB::table('forum_attachmentfield')." WHERE uid IN ($uids)", 'UNBUFFERED');
		deletepost("authorid IN ($uids)", true, false);
	}

	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE uid IN ($uids) OR (id IN ($uids) AND idtype='uid')");

	$doids = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_doing')." WHERE uid IN ($uids)");
	while ($value = DB::fetch($query)) {
		$doids[$value['doid']] = $value['doid'];
	}

	DB::query("DELETE FROM ".DB::table('home_doing')." WHERE uid IN ($uids)");

	$delsql = !empty($doids) ? "doid IN (".dimplode($doids).") OR " : "";
	DB::query("DELETE FROM ".DB::table('home_docomment')." WHERE $delsql uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_share')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_album')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('common_credit_rule_log')." WHERE uid IN ($uids)");
	DB::query("DELETE FROM ".DB::table('common_credit_rule_log_field')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_notification')." WHERE (uid IN ($uids) OR authorid IN ($uids))");

	DB::query("DELETE FROM ".DB::table('home_poke')." WHERE (uid IN ($uids) OR fromuid IN ($uids))");


	$query = DB::query("SELECT filepath, thumb, remote FROM ".DB::table('home_pic')." WHERE uid IN ($uids)");
	while ($value = DB::fetch($query)) {
		deletepicfiles($value);
	}

	DB::query("DELETE FROM ".DB::table('home_pic')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_blog')." WHERE uid IN ($uids)");
	DB::query("DELETE FROM ".DB::table('home_blogfield')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE (uid IN ($uids) OR authorid IN ($uids) OR (id IN ($uids) AND idtype='uid'))");

	DB::query("DELETE FROM ".DB::table('home_visitor')." WHERE (uid IN ($uids) OR vuid IN ($uids))");

	DB::query("DELETE FROM ".DB::table('home_class')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_friend')." WHERE (uid IN ($uids) OR fuid IN ($uids))");

	DB::query("DELETE FROM ".DB::table('home_clickuser')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('common_invite')." WHERE (uid IN ($uids) OR fuid IN ($uids))");

	DB::query("DELETE FROM ".DB::table('common_mailcron').", ".DB::table('common_mailqueue')." USING ".DB::table('common_mailcron').", ".DB::table('common_mailqueue')." WHERE ".DB::table('common_mailcron').".touid IN ($uids) AND ".DB::table('common_mailcron').".cid=".DB::table('common_mailqueue').".cid");

	DB::query("DELETE FROM ".DB::table('common_myinvite')." WHERE (touid IN ($uids) OR fromuid IN ($uids))");
	DB::query("DELETE FROM ".DB::table('home_userapp')." WHERE uid IN ($uids)");
	DB::query("DELETE FROM ".DB::table('home_userappfield')." WHERE uid IN ($uids)");

	DB::query("DELETE FROM ".DB::table('home_show')." WHERE uid IN ($uids)");

	manyoulog('user', $uids, 'delete');

	require_once libfile('function/forum');
	foreach(explode(',', $uids) as $uid) {
		my_thread_log('deluser', array('uid' => $uid));
	}
	return $numdeleted;
}

function deletepost($condition, $unbuffered = true, $deleteattach = true) {
	global $_G;
	loadcache('posttableids');
	$num = 0;
	if(!empty($_G['cache']['posttableids'])) {
		$posttableids = $_G['cache']['posttableids'];
	} else {
		$posttableids = array('0');
	}
	foreach($posttableids as $id) {
		if($id == 0) {
			DB::delete('forum_post', $condition, 0, $unbuffered);
		} else {
			DB::delete("forum_post_$id", $condition, 0, $unbuffered);
		}
		$num += DB::affected_rows();
	}
	if($deleteattach) {
		$query = DB::query("SELECT attachment, thumb, remote, aid FROM ".DB::table('forum_attachment')." WHERE $condition");
		while($attach = DB::fetch($query)) {
			dunlink($attach);
		}
		DB::delete('forum_attachment', $condition, 0, $unbuffered);
		DB::delete('forum_attachmentfield', $condition, 0, $unbuffered);
	}
	return $num;
}

function deletethread($condition, $unbuffered = true) {
	$deletedthreads = 0;
	$query = DB::query("SELECT attachment, thumb, remote, aid FROM ".DB::table('forum_attachment')." WHERE $condition");
	while($attach = DB::fetch($query)) {
		dunlink($attach);
	}
	foreach(array(
		'forum_thread', 'forum_attachment', 'forum_attachmentfield', 'forum_polloption',
		'forum_poll', 'forum_trade', 'forum_activity', 'forum_activityapply',
		'forum_debate', 'forum_debatepost', 'forum_threadmod', 'forum_relatedthread',
		'forum_typeoptionvar', 'forum_postposition', 'forum_pollvoter') as $table) {
		DB::delete($table, $condition, 0, $unbuffered);
		if($table == 'forum_thread') {
			$deletedthreads = DB::affected_rows();
		}
	}
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE ".str_replace('tid', 'id', $condition)." AND idtype='tid'");
	return $deletedthreads;
}

function deletecomments($cids) {
	global $_G;

	$blognums = $newcids = $dels = $counts = array();
	$allowmanage = checkperm('managecomment');

	$query = DB::query("SELECT * FROM ".DB::table('home_comment')." WHERE cid IN (".dimplode($cids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['authorid'] == $_G['uid'] || $value['uid'] == $_G['uid']) {
			$dels[] = $value;
			$newcids[] = $value['cid'];
			if($value['authorid'] != $_G['uid'] && $value['uid'] != $_G['uid']) {
				$counts[$value['authorid']]['coef'] -= 1;
			}
			if($value['idtype'] == 'blogid') {
				$blognums[$value['id']]++;
			}
		}
	}

	if(empty($dels)) return array();

	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE cid IN (".dimplode($newcids).")");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			batchupdatecredit('comment', $uid, array(), $setarr['coef']);
		}
	}
	if($blognums) {
		$nums = renum($blognums);
		foreach ($nums[0] as $num) {
			DB::query("UPDATE ".DB::table('home_blog')." SET replynum=replynum-$num WHERE blogid IN (".dimplode($nums[1][$num]).")");
		}
	}
	return $dels;
}


function deleteblogs($blogids) {
	global $_G;

	$blogs = $newblogids = $counts = array();
	$allowmanage = checkperm('manageblog');

	$query = DB::query("SELECT * FROM ".DB::table('home_blog')." WHERE blogid IN (".dimplode($blogids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$blogs[] = $value;
			$newblogids[] = $value['blogid'];

			if($value['uid'] != $_G['uid']) {
				$counts[$value['uid']]['coef'] -= 1;
			}
			$counts[$value['uid']]['blogs'] -= 1;
		}
	}
	if(empty($blogs)) return array();

	DB::query("DELETE FROM ".DB::table('home_blog')." WHERE blogid IN (".dimplode($newblogids).")");
	DB::query("DELETE FROM ".DB::table('home_blogfield')." WHERE blogid IN (".dimplode($newblogids).")");
	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE id IN (".dimplode($newblogids).") AND idtype='blogid'");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newblogids).") AND idtype='blogid'");
	DB::query("DELETE FROM ".DB::table('home_clickuser')." WHERE id IN (".dimplode($newblogids).") AND idtype='blogid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			batchupdatecredit('publishblog', $uid, array('blogs' => $setarr['blogs']), $setarr['coef']);
		}
	}

	return $blogs;
}

function deletefeeds($feedids) {
	global $_G;

	$allowmanage = checkperm('managefeed');

	$feeds = $newfeedids = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_feed')." WHERE feedid IN (".dimplode($feedids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$newfeedids[] = $value['feedid'];
			$feeds[] = $value;
		}
	}

	if(empty($newfeedids)) return array();

	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE feedid IN (".dimplode($newfeedids).")");

	return $feeds;
}


function deleteshares($sids) {
	global $_G;

	$allowmanage = checkperm('manageshare');

	$shares = $newsids = $counts = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_share')." WHERE sid IN (".dimplode($sids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$shares[] = $value;
			$newsids[] = $value['sid'];

			if($value['uid'] != $_G['uid']) {
				$counts[$value['uid']]['coef'] -= 1;
			}
			$counts[$value['uid']]['sharings'] -= 1;
		}
	}
	if(empty($shares)) return array();

	DB::query("DELETE FROM ".DB::table('home_share')." WHERE sid IN (".dimplode($newsids).")");
	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE id IN (".dimplode($newsids).") AND idtype='sid'");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newsids).") AND idtype='sid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			batchupdatecredit('createshare', $uid, array('sharings' => $setarr['sharings']), $setarr['coef']);
		}
	}

	return $shares;
}


function deletedoings($ids) {
	global $_G;

	$allowmanage = checkperm('managedoing');

	$doings = $newdoids = $counts = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_doing')." WHERE doid IN (".dimplode($ids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$doings[] = $value;
			$newdoids[] = $value['doid'];

			if($value['uid'] != $_G['uid']) {
				$counts[$value['uid']]['coef'] -= 1;
			}
			$counts[$value['uid']]['doings'] -= 1;
		}
	}

	if(empty($doings)) return array();

	DB::query("DELETE FROM ".DB::table('home_doing')." WHERE doid IN (".dimplode($newdoids).")");
	DB::query("DELETE FROM ".DB::table('home_docomment')." WHERE doid IN (".dimplode($newdoids).")");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newdoids).") AND idtype='doid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			batchupdatecredit('doing', $uid, array('doings' => $setarr['doings']), $setarr['coef']);
		}
	}

	return $doings;
}

function deletespace($uid) {
	global $_G;

	$allowmanage = checkperm('managedelspace');

	if($allowmanage) {
		DB::query("UPDATE ".DB::table('common_member')." SET status='1' WHERE uid='$uid'");
		if($_G['setting']['my_app_status']) manyoulog('user', $uid, 'delete');
		return true;
	} else {
		return false;
	}
}

function deletepics($picids) {
	global $_G;

	$sizes = $pics = $newids = $counts = array();
	$allowmanage = checkperm('managealbum');

	$albumids = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_pic')." WHERE picid IN (".dimplode($picids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$pics[] = $value;
			$newids[] = $value['picid'];

			if($value['uid'] != $_G['uid']) {
				$counts[$value['uid']]['coef'] -= 1;
			}
			$sizes[$value['uid']] = $sizes[$value['uid']] + $value['size'];
			$albumids[$value['albumid']] = $value['albumid'];
		}
	}
	if(empty($pics)) return array();

	DB::query("DELETE FROM ".DB::table('home_pic')." WHERE picid IN (".dimplode($newids).")");
	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE id IN (".dimplode($newids).") AND idtype='picid'");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newids).") AND idtype='picid'");
	DB::query("DELETE FROM ".DB::table('home_clickuser')." WHERE id IN (".dimplode($newids).") AND idtype='picid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			$attachsize = intval($sizes[$uid]);
			batchupdatecredit('uploadimage', $uid, array('attachsize' => -$attachsize), $setarr['coef']);
			unset($sizes[$uid]);
		}
	}
	if($sizes) {
		foreach ($sizes as $uid => $setarr) {
			$attachsize = intval($sizes[$uid]);
			updatemembercount($uid, array('attachsize' => -$attachsize), false);
		}
	}

	require_once libfile('function/spacecp');
	foreach ($albumids as $albumid) {
		if($albumid) {
			album_update_pic($albumid);
		}
	}

	deletepicfiles($pics);

	return $pics;
}

function deletepicfiles($pics) {
	global $_G;
	$remotes = array();
	include_once libfile('function/home');
	foreach ($pics as $pic) {
		pic_delete($pic['filepath'], 'album', $pic['thumb'], $pic['remote']);
	}
}

function deletealbums($albumids) {
	global $_G;

	$sizes = $dels = $newids = $counts = array();
	$allowmanage = checkperm('managealbum');

	$query = DB::query("SELECT * FROM ".DB::table('home_album')." WHERE albumid IN (".dimplode($albumids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$dels[] = $value;
			$newids[] = $value['albumid'];
		}
		$counts[$value['uid']]['albums'] -= 1;
	}
	if(empty($dels)) return array();

	$pics = $picids = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_pic')." WHERE albumid IN (".dimplode($newids).")");
	while ($value = DB::fetch($query)) {
		$pics[] = $value;
		$picids[] = $value['picid'];
		$sizes[$value['uid']] = $sizes[$value['uid']] + $value['size'];
		if($value['uid'] != $_G['uid']) {
			$counts[$value['uid']]['coef'] -= 1;
		}
	}

	DB::query("DELETE FROM ".DB::table('home_pic')." WHERE albumid IN (".dimplode($newids).")");
	DB::query("DELETE FROM ".DB::table('home_album')." WHERE albumid IN (".dimplode($newids).")");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newids).") AND idtype='albumid'");
	if($picids) DB::query("DELETE FROM ".DB::table('home_clickuser')." WHERE id IN (".dimplode($picids).") AND idtype='picid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			$attachsize = intval($sizes[$uid]);
			batchupdatecredit('uploadimage', $uid, array('albums' => $setarr['albums'], 'attachsize' => -$attachsize), $setarr['coef']);
		}
	}
	if($sizes) {
		foreach ($sizes as $uid => $value) {
			$attachsize = intval($sizes[$uid]);
			updatemembercount($uid, array('attachsize' => -$attachsize), false);
		}
	}

	if($pics) {
		deletepicfiles($pics);
	}

	return $dels;
}

function deletepolls($pids) {
	global $_G;


	$counts = $polls = $newpids = array();
	$allowmanage = checkperm('managepoll');

	$query = DB::query("SELECT * FROM ".DB::table('home_poll')." WHERE pid IN (".dimplode($pids).")");
	while ($value = DB::fetch($query)) {
		if($allowmanage || $value['uid'] == $_G['uid']) {
			$polls[] = $value;
			$newpids[] = $value['pid'];

			if($value['uid'] != $_G['uid']) {
				$counts[$value['uid']]['coef'] -= 1;
			}
			$counts[$value['uid']]['polls'] -= 1;
		}
	}
	if(empty($polls)) return array();

	DB::query("DELETE FROM ".DB::table('home_poll')." WHERE pid IN (".dimplode($newpids).")");
	DB::query("DELETE FROM ".DB::table('home_pollfield')." WHERE pid IN (".dimplode($newpids).")");
	DB::query("DELETE FROM ".DB::table('home_polloption')." WHERE pid IN (".dimplode($newpids).")");
	DB::query("DELETE FROM ".DB::table('home_polluser')." WHERE pid IN (".dimplode($newpids).")");
	DB::query("DELETE FROM ".DB::table('home_comment')." WHERE id IN (".dimplode($newpids).") AND idtype='pid'");
	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".dimplode($newpids).") AND idtype='pid'");

	if($counts) {
		foreach ($counts as $uid => $setarr) {
			batchupdatecredit('createpoll', $uid, array('polls' => $setarr['polls']), $setarr['coef']);
		}
	}

	return $polls;

}


function deletetrasharticle($aids) {
	global $_G;

	$articles = $trashid = array();
	$query = DB::query("SELECT * FROM ".DB::table('portal_article_trash')." WHERE aid IN (".dimplode($aids).")");
	while ($value = DB::fetch($query)) {
		$dels[$value['aid']] = $value['aid'];
		$article = unserialize($value['content']);
		$articles[$article['aid']] = $article;
		if($article['pic']) {
			@unlink($_G['config']['attachdir'].'./'.$article['pic']);
		}
	}

	if($dels) {
		DB::query('DELETE FROM '.DB::table('portal_article_trash')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');
		deletearticlerelated($dels);
	}

	return $articles;
}


function deletearticle($aids, $istrash = 1) {
	global $_G;

	if(empty($aids)) return false;
	$trasharr = $article = $bids = $dels = $attachment = $attachaid = $catids = array();
	$query = DB::query("SELECT * FROM ".DB::table('portal_article_title')." WHERE aid IN (".dimplode($aids).")");
	while ($value = DB::fetch($query)) {
		$catids[] = intval($value['catid']);
		$dels[$value['aid']] = $value['aid'];
		$article[] = $value;
	}
	if($dels) {
		foreach($article as $key => $value) {
			if($istrash) {
				$valstr = daddslashes(serialize($value));
				$trasharr[] = "('$value[aid]', '$valstr')";
			} elseif($value['pic']) {
				pic_delete($value['pic'], 'portal', $value['thumb'], $value['remote']);
				$attachaid[] = $value['aid'];
			}
		}
		if($istrash) {
			if($trasharr) {
				DB::query("INSERT INTO ".DB::table('portal_article_trash')." (`aid`, `content`) VALUES ".implode(',', $trasharr));
			}
		} else {
			deletearticlerelated($dels);
		}

		DB::query('DELETE FROM '.DB::table('portal_article_title')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');

		$catids = array_unique($catids);
		if($catids) {
			foreach($catids as $catid) {
				$cnt = DB::result_first('SELECT COUNT(*) FROM '.DB::table('portal_article_title')." WHERE catid = '$catid'");
				DB::update('portal_category', array('articles'=>$cnt), array('catid'=>$catid));
			}
		}
	}
	return $article;
}

function deletearticlerelated ($dels) {

	DB::query('DELETE FROM '.DB::table('portal_article_count')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');
	DB::query('DELETE FROM '.DB::table('portal_article_content')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');

	$query = DB::query("SELECT * FROM ".DB::table('portal_attachment')." WHERE aid IN (".dimplode($dels).")");
	while ($value = DB::fetch($query)) {
		$attachment[] = $value;
		$attachdel[] = $value['attachid'];
	}
	foreach ($attachment as $value) {
		pic_delete($value['attachment'], 'portal', $value['thumb'], $value['remote']);
	}
	DB::query("DELETE FROM ".DB::table('portal_attachment')." WHERE aid IN (".dimplode($dels).")", 'UNBUFFERED');

	DB::query('DELETE FROM '.DB::table('portal_comment')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');

	DB::query('DELETE FROM '.DB::table('portal_article_related')." WHERE aid IN(".dimplode($dels).")", 'UNBUFFERED');

}

?>