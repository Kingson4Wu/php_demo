<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: thread_printable.php 8578 2010-04-21 07:34:43Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$thisbg = '#FFFFFF';
$posttable = getposttablebytid($_G['tid']);
$query = DB::query("SELECT p.*, m.username, m.groupid FROM ".DB::table($posttable)." p
		LEFT JOIN ".DB::table('common_member')." m ON m.uid=p.authorid
		WHERE p.tid='$_G[tid]' AND p.invisible='0' ORDER BY p.dateline LIMIT 100");

while($post = DB::fetch($query)) {

	$post['dateline'] = dgmdate($post['dateline'], 'u');
	$post['message'] = discuzcode($post['message'], $post['smileyoff'], $post['bbcodeoff'], sprintf('%00b', $post['htmlon']), $_G['forum']['allowsmilies'], $_G['forum']['allowbbcode'], $_G['forum']['allowimgcode'], $_G['forum']['allowhtml'], ($_G['forum']['jammer'] && $post['authorid'] != $_G['uid'] ? 1 : 0));
	$post['message'] = preg_replace("/\[hide\]\s*(.+?)\s*\[\/hide\]/is", '', $post['message']);

	if($post['attachment']) {
		$attachment = 1;
	}
	$post['attachments'] = array();
	if($post['attachment'] && $_G['group']['allowgetattach']) {
		$_G['forum_attachpids'] .= ",$post[pid]";
		$post['attachment'] = 0;
		if(preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $post['message'], $matchaids)) {
			$_G['forum_attachtags'][$post['pid']] = $matchaids[1];
		}
	}

	$postlist[$post['pid']] = $post;
}

if($_G['forum_attachpids']) {
	require_once libfile('function/attachment');
	parseattach($_G['forum_attachpids'], $_G['forum_attachtags'], $postlist);
}

include template('forum/viewthread_printable');

?>