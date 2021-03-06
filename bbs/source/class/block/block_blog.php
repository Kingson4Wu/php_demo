<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: block_blog.php 10773 2010-05-14 10:53:42Z xupeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class block_blog {
	var $setting = array();
	function block_blog() {
		$this->setting = array(
			'blogids'	=> array(
				'title' => 'bloglist_blogids',
				'type' => 'text'
			),
			'uids'	=> array(
				'title' => 'bloglist_uids',
				'type' => 'text',
			),
			'catid' => array(
				'title' => 'bloglist_catid',
				'type'=>'mselect',
			),
			'picrequired' => array(
				'title' => 'bloglist_picrequired',
				'type' => 'radio',
				'default' => '0'
			),
			'orderby' => array(
				'title' => 'bloglist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('dateline', 'bloglist_orderby_dateline'),
					array('viewnum', 'bloglist_orderby_viewnum'),
					array('replynum', 'bloglist_orderby_replynum'),
					array('hot', 'bloglist_orderby_hot')
				),
				'default' => 'dateline'
			),
			'hours' => array(
				'title' => 'bloglist_hours',
				'type' => 'mradio',
				'value' => array(
					array('', 'bloglist_hours_nolimit'),
					array('1', 'bloglist_hours_hour'),
					array('24', 'bloglist_hours_day'),
					array('168', 'bloglist_hours_week'),
					array('720', 'bloglist_hours_month'),
					array('8760', 'bloglist_hours_year'),
				),
				'default' => ''
			),
			'titlelength' => array(
				'title' => 'bloglist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength'	=> array(
				'title' => 'bloglist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'bloglist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if(!empty($settings['catid'])) {
			$settings['catid']['value'][] = array(0, lang('portalcp', 'block_all_category'));
			loadcache('blogcategory');
			foreach($_G['cache']['blogcategory'] as $value) {
				if($value['level'] == 0) {
					$settings['catid']['value'][] = array($value['catid'], $value['catname']);
					if($value['children']) {
						foreach($value['children'] as $catid2) {
							$value2 = $_G['cache']['blogcategory'][$catid2];
							$settings['catid']['value'][] = array($value2['catid'], '-- '.$value2['catname']);
							if($value2['children']) {
								foreach($value2['children'] as $catid3) {
									$value3 = $_G['cache']['blogcategory'][$catid3];
									$settings['catid']['value'][] = array($value3['catid'], '---- '.$value3['catname']);
								}
							}
						}
					}
				}
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
		$blogids	= !empty($parameter['blogids']) ? explode(',',$parameter['blogids']) : array();
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$catid		= !empty($parameter['catid']) ? $parameter['catid'] : array();
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$hours		= isset($parameter['hours']) ? intval($parameter['hours']) : '';
		$titlelength = $parameter['titlelength'] ? intval($parameter['titlelength']) : 40;
		$summarylength = $parameter['summarylength'] ? intval($parameter['summarylength']) : 80;
		$orderby	= isset($parameter['orderby']) && in_array($parameter['orderby'],array('dateline', 'viewnum', 'replynum', 'hot')) ? $parameter['orderby'] : 'dateline';
		$picrequired = !empty($parameter['picrequired']) ? 1 : 0;

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$datalist = $list = array();
		$wheres = array();
		if($blogids) {
			$wheres[] = 'b.blogid IN ('.dimplode($blogids).')';
		}
		if($bannedids) {
			$wheres[] = 'b.blogid NOT IN ('.dimplode($bannedids).')';
		}
		if($uids) {
			$wheres[] = 'b.uid IN ('.dimplode($uids).')';
		}
		if($catid) {
			$wheres[] = 'b.catid IN ('.dimplode($catid).')';
		}
		if($hours) {
			$timestamp = TIMESTAMP - 3600 * $hours;
			$wheres[] = "b.dateline >= '$timestamp'";
		}
		$tablesql = $fieldsql = '';
		if($style['getpic'] || $style['getsummary'] || $picrequired) {
			if($picrequired) {
				$wheres[] = "bf.pic != ''";
			}
			$tablesql = ' LEFT JOIN '.DB::table('home_blogfield')." bf ON b.blogid = bf.blogid";
			$fieldsql = ', bf.pic, b.picflag, bf.message';
		}
		$wheresql = $wheres ? implode(' AND ', $wheres) : '1';
		$sql = "SELECT b.* $fieldsql FROM ".DB::table('home_blog')." b $tablesql WHERE $wheresql ORDER BY b.$orderby DESC";
		$query = DB::query($sql." LIMIT $startrow,$items;");
		while($data = DB::fetch($query)) {
			if(empty($data['pic'])) {
				$data['pic'] = STATICURL.'image/common/nophoto.gif';
				$data['picflag'] = '0';
			} else {
				$data['pic'] = preg_replace('/\.thumb\.jpg$/', '', $data['pic']);
				$data['pic'] = 'album/'.$data['pic'];
				$data['picflag'] = $data['remote'] == '1' ? '2' : '1';
			}
			$list[] = array(
				'id' => $data['blogid'],
				'idtype' => 'blogid',
				'title' => cutstr($data['subject'], $titlelength),
				'url' => 'home.php?mod=space&uid='.$data[uid].'&do=blog&id='.$data['blogid'],
				'pic' => $data['pic'],
				'picflag' => $data['picflag'],
				'summary' => $data['message'] ? preg_replace("/&amp;[a-z]+\;/i", '', cutstr(strip_tags($data['message']), $summarylength)) : '',
				'fields' => array(
					'dateline'=>$data['dateline'],
					'uid'=>$data['uid'],
					'username'=>$data['username'],
					'replynum'=>$data['replynum'],
					'viewnum'=>$data['viewnum'],
					'click1'=>$data['click1'],
					'click2'=>$data['click2'],
					'click3'=>$data['click3'],
					'click4'=>$data['click4'],
					'click5'=>$data['click5'],
					'click6'=>$data['click6'],
					'click7'=>$data['click7'],
					'click8'=>$data['click8'],
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>