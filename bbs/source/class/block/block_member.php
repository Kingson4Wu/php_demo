<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_member.php 10155 2010-05-07 05:27:40Z xupeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class block_member {
	var $setting = array();
	function block_member() {
		$this->setting = array(
			'uids' => array(
				'title' => 'memberlist_uids',
				'type' => 'text'
			),
			'groupid' => array(
				'title' => 'memberlist_groupid',
				'type' => 'mselect',
				'value' => array()
			),
			'special' => array(
				'title' => 'memberlist_special',
				'type' => 'mradio',
				'value' => array(
					array('', 'memberlist_special_nolimit'),
					array('0', 'memberlist_special_hot'),
					array('1', 'memberlist_special_default'),
				),
				'default' => ''
			),
			'orderby' => array(
				'title' => 'memberlist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('credits', 'memberlist_orderby_credits'),
					array('extcredits', 'memberlist_orderby_extcredits'),
					array('posts', 'memberlist_orderby_posts'),
					array('digestposts', 'memberlist_orderby_digestposts'),
					array('regdate', 'memberlist_orderby_regdate'),
					array('show', 'memberlist_orderby_show'),
					array('hourposts', 'memberlist_orderby_hourposts'),
					array('todayposts', 'memberlist_orderby_todayposts'),
					array('weekposts', 'memberlist_orderby_weekposts'),
					array('monthposts', 'memberlist_orderby_monthposts')
				),
				'default' => 'credits'
			),
			'extcredit' => array(
				'title' => 'memberlist_orderny_extcreditselect',
				'type' => 'select',
				'value' => array()
			),
			'startrow' => array(
				'title' => 'memberlist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if($settings['extcredit']) {
			foreach($_G['setting']['extcredits'] as $id => $credit) {
				$settings['extcredit']['value'][] = array($id, $_G['setting']['extcredits'][$id]['title']);
			}
		}
		if($settings['groupid']) {
			$query = DB::query('SELECT groupid, grouptitle FROM '.DB::table('common_usergroup')." WHERE type='member'");
			$settings['groupid']['value'][] = array(0, lang('portalcp', 'block_all_group'));
			while($value=DB::fetch($query)) {
				$settings['groupid']['value'][] = array($value['groupid'], $value['grouptitle']);
			}
		}
		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);

		$uids		= !empty($parameter['uids']) ? explode(',',$parameter['uids']) : array();
		$groupid	= !empty($parameter['groupid']) && !in_array(0, $parameter['groupid']) ? $parameter['groupid'] : array();
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$orderby	= isset($parameter['orderby']) ? (in_array($parameter['orderby'],array('credits','extcredits','posts','digestposts','regdate','hourposts','todayposts','weekposts','monthposts', 'show')) ? $parameter['orderby'] : 'credits') : 'credits';
		$special    = isset($parameter['special']) && strlen($parameter['special']) ? intval($parameter['special']) : null;

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();
		$sqlban = !empty($bannedids) ? ' AND m.uid NOT IN ('.dimplode($bannedids).')' : '';

		$list = array();
		$tablesql = DB::table('common_member')." m LEFT JOIN ".DB::table('common_member_count')." mc ON m.uid = mc.uid";
		$sqlgroupid = !empty($groupid) ? ' AND m.groupid IN ('.dimplode($groupid).')' : '';
		if($uids) {
			$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE m.uid IN (".dimplode($uids).") $sqlban LIMIT $startrow, $items");
		} elseif($special !== null) {
			$special = $special ? 1 : 0;
			$query = DB::query("SELECT m.*, mc.* FROM $tablesql LEFT JOIN ".DB::table('home_specialuser')." su ON m.uid=su.uid WHERE su.status='$special' $sqlgroupid $sqlban LIMIT $startrow, $items");
		} else {
			switch($orderby) {
				case 'credits':
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlgroupid $sqlban ORDER BY m.credits DESC LIMIT $startrow, $items");
					break;
				case 'regdate':
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlgroupid $sqlban ORDER BY m.regdate DESC LIMIT $startrow, $items");
					break;
				case 'extcredits':
					$extcredits = 'extcredits'.(in_array($parameter['extcredit'], range(1, 8)) ? $parameter['extcredit'] : '1');
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlgroupid $sqlban ORDER BY mc.$extcredits DESC LIMIT $startrow, $items");
					break;
				case 'posts':
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlgroupid $sqlban ORDER BY mc.posts DESC LIMIT $startrow, $items");
					break;
				case 'digestposts':
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlgroupid $sqlban ORDER BY mc.digestposts DESC LIMIT $startrow, $items");
					break;
				case 'hourposts';
					$timestamp = TIMESTAMP - 3600;
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid WHERE ms.lastpost>='$timestamp' $sqlgroupid $sqlban ORDER BY mc.posts DESC LIMIT $startrow, $items");
					break;
				case 'todayposts':
					$timestamp = TIMESTAMP - 86400;
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid WHERE ms.lastpost>='$timestamp' $sqlgroupid $sqlban ORDER BY mc.posts DESC LIMIT $startrow, $items");
					break;
				case 'weekposts':
					$timestamp = TIMESTAMP - 604800;
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid WHERE ms.lastpost>='$timestamp' $sqlgroupid $sqlban ORDER BY mc.posts DESC LIMIT $startrow, $items");
					break;
				case 'monthposts':
					$timestamp = TIMESTAMP - 2592000;
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid WHERE ms.lastpost>='$timestamp' $sqlgroupid $sqlban ORDER BY mc.posts DESC LIMIT $startrow, $items");
					break;
				case 'show':
					$tablesql = DB::table('home_show')." s LEFT JOIN ".DB::table('common_member')." m ON s.uid = m.uid LEFT JOIN ".DB::table('common_member_count')." mc ON m.uid = mc.uid";
					$query = DB::query("SELECT m.*, mc.* FROM $tablesql WHERE 1 $sqlban ORDER BY s.credit DESC LIMIT $startrow, $items");
					break;
			}
		}
		$uids = array();
		while($data = DB::fetch($query)){
			$uids[] = intval($data['uid']);
			$list[] = array(
				'id' => $data['uid'],
				'idtype' => 'uid',
				'title' => $data['username'],
				'url' => 'home.php?mod=space&uid='.$data['uid'],
				'pic' => '',
				'picflag' => 0,
				'summary' => '',
				'fields' => array(
					'avatar' => avatar($data['uid'], 'small', true, false, $_G['setting']['avatarmethod'], $_G['setting']['ucenterurl']),
					'avatar_big' => avatar($data['uid'], 'middle', true, false, $_G['setting']['avatarmethod'], $_G['setting']['ucenterurl']),
					'credits' => $data['credits'],
					'extcredits1' => $data['extcredits1'],
					'extcredits2' => $data['extcredits2'],
					'extcredits3' => $data['extcredits3'],
					'extcredits4' => $data['extcredits4'],
					'extcredits5' => $data['extcredits5'],
					'extcredits6' => $data['extcredits6'],
					'extcredits7' => $data['extcredits7'],
					'extcredits8' => $data['extcredits8'],
					'regdate' => $data['regdate'],
					'posts' => $data['posts'],
					'threads' => $data['threads'],
					'digestposts' => $data['digestposts'],
				)
			);
		}
		if($uids) {
			include_once libfile('function/profile');
			$profiles = array();
			$query = DB::query('SELECT * FROM '.DB::table('common_member_profile')." WHERE uid IN (".dimplode($uids).")");
			while($data = DB::fetch($query)) {
				$profile = array();
				foreach($data as $fieldid=>$fieldvalue) {
					$fieldvalue = profile_show($fieldid, $data);
					if(false !== $fieldvalue) {
						$profile[$fieldid] = $fieldvalue;
					}
				}
				$profiles[$data['uid']] = $profile;
			}
			for($i=0,$L=count($list); $i<$L; $i++) {
				$uid = $list[$i]['id'];
				if($profiles[$uid]) {
					$list[$i]['fields'] = array_merge($list[$i]['fields'], $profiles[$uid]);
				}
			}
		}
		return array('html' => '', 'data' => $list);
	}
}

?>