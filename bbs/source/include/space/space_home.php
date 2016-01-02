<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_home.php 10066 2010-05-06 09:30:28Z liguode $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/feed');

if(empty($_G['setting']['feedhotday'])) {
	$_G['setting']['feedhotday'] = 2;
}

$minhot = $_G['setting']['feedhotmin']<1?3:$_G['setting']['feedhotmin'];

if(empty($_GET['view'])) {
	if($space['self']) {
		space_merge($space, 'count');
		if($_G['setting']['showallfriendnum'] && $space['friends'] < $_G['setting']['showallfriendnum']) {
			$_GET['view'] = 'all';
		} else {
			$_GET['view'] = 'we';
		}
	} else {
		$_GET['view'] = 'all';
	}
}
if(empty($_GET['order'])) {
	$_GET['order'] = 'dateline';
}

$perpage = $_G['setting']['feedmaxnum']<20?20:$_G['setting']['feedmaxnum'];
$perpage = mob_perpage($perpage);

if($_GET['view'] == 'all' && $_GET['order'] == 'hot') {
	$perpage = 50;
}

$page = intval($_GET['page']);
if($page < 1) $page = 1;
$start = ($page-1)*$perpage;

ckstart($start, $perpage);

$_G['home_today'] = $_G['timestamp'] - $_G['timestamp']%(3600*24);

$gets = array(
	'mod' => 'space',
	'uid' => $space['uid'],
	'do' => 'home',
	'view' => $_GET['view'],
	'order' => $_GET['order'],
	'appid' => $_GET['appid'],
	'icon' => $_GET['icon']
);
$theurl = 'home.php?'.url_implode($gets);

$need_count = true;
$wheresql = array('1');

if($_GET['view'] == 'all') {

	if($_GET['order'] == 'dateline') {
		$ordersql = "dateline DESC";
		$f_index = '';
		$orderactives = array('dateline' => ' class="a"');
	} else {
		$wheresql['hot'] = "hot>='$minhot'";
		$ordersql = "dateline DESC";
		$f_index = '';
		$orderactives = array('hot' => ' class="a"');
	}

} elseif($_GET['view'] == 'me') {

	$wheresql['uid'] = "uid='$space[uid]'";
	$ordersql = "dateline DESC";
	$f_index = '';

	$diymode = 1;
	if($space['self'] && $_GET['from'] != 'space') $diymode = 0;

} else {

	space_merge($space, 'field_home');

	if(empty($space['feedfriend'])) {
		$need_count = false;
	} else {
		$wheresql['uid'] = "uid IN ('0',$space[feedfriend])";
		$ordersql = "dateline DESC";
		$f_index = 'USE INDEX(dateline)';
	}
}

$appid = empty($_GET['appid'])?0:intval($_GET['appid']);
if($appid) {
	$wheresql['appid'] = "appid='$appid'";
}
$icon = empty($_GET['icon'])?'':trim($_GET['icon']);
if($icon) {
	$wheresql['icon'] = "icon='$icon'";
}
$gid = !isset($_GET['gid'])?'-1':intval($_GET['gid']);
if($gid>=0) {
	$fuids = array();
	$query = db::query("SELECT * FROM ".db::table('home_friend')." WHERE uid='$_G[uid]' AND gid='$gid' ORDER BY num DESC LIMIT 0,100");
	while ($value = db::fetch($query)) {
		$fuids[] = $value['fuid'];
	}
	if(empty($fuids)) {
		$need_count = false;
	} else {
		$wheresql['uid'] = "uid IN (".dimplode($fuids).")";
	}
}
$gidactives[$gid] = ' class="a"';

$feed_users = $feed_list = $user_list = $filter_list  = $list = $mlist = array();
$count = $filtercount = 0;
$multi = '';

if($need_count) {
	$query = DB::query("SELECT * FROM ".DB::table('home_feed')." $f_index
		WHERE ".implode(' AND ', $wheresql)."
		ORDER BY $ordersql
		LIMIT $start,$perpage");

	if($_GET['view'] == 'me') {
		while ($value = DB::fetch($query)) {
			if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
				$value = mkfeed($value);

				if($value['dateline']>=$_G['home_today']) {
					$list['today'][] = $value;
				} elseif ($value['dateline']>=$_G['home_today']-3600*24) {
					$list['yesterday'][] = $value;
				} else {
					$theday = dgmdate($value['dateline'], 'Y-m-d');
					$list[$theday][] = $value;
				}
			}
			$count++;
		}
	} else {
		$hash_datas = array();
		$more_list = array();
		$uid_feedcount = array();

		while ($value = DB::fetch($query)) {
			if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
				$value = mkfeed($value);
				if(ckicon_uid($value)) {

					if($value['dateline']>=$_G['home_today']) {
						$dkey = 'today';
					} elseif ($value['dateline']>=$_G['home_today']-3600*24) {
						$dkey = 'yesterday';
					} else {
						$dkey = dgmdate($value['dateline'], 'Y-m-d');
					}

					$maxshownum = 3;
					if(empty($value['uid'])) $maxshownum = 10;

					if(empty($value['hash_data'])) {
						if(empty($feed_users[$dkey][$value['uid']])) $feed_users[$dkey][$value['uid']] = $value;
						if(empty($uid_feedcount[$dkey][$value['uid']])) $uid_feedcount[$dkey][$value['uid']] = 0;

						$uid_feedcount[$dkey][$value['uid']]++;

						if($uid_feedcount[$dkey][$value['uid']]>$maxshownum) {
							$more_list[$dkey][$value['uid']][] = $value;
						} else {
							$feed_list[$dkey][$value['uid']][] = $value;
						}

					} elseif(empty($hash_datas[$value['hash_data']])) {
						$hash_datas[$value['hash_data']] = 1;
						if(empty($feed_users[$dkey][$value['uid']])) $feed_users[$dkey][$value['uid']] = $value;
						if(empty($uid_feedcount[$dkey][$value['uid']])) $uid_feedcount[$dkey][$value['uid']] = 0;


						$uid_feedcount[$dkey][$value['uid']] ++;

						if($uid_feedcount[$dkey][$value['uid']]>$maxshownum) {
							$more_list[$dkey][$value['uid']][] = $value;
						} else {
							$feed_list[$dkey][$value['uid']][$value['hash_data']] = $value;
						}

					} else {
						$user_list[$value['hash_data']][] = "<a href=\"home.php?mod=space&uid=$value[uid]\">$value[username]</a>";
					}


				} else {
					$filtercount++;
					$filter_list[] = $value;
				}
			}
			$count++;
		}
	}

	$multi = simplepage($count, $perpage, $page, $theurl);
}

$olfriendlist = $visitorlist = $task = $ols = $birthlist = $hotlist = $guidelist = array();
$oluids = array();
$groups = array();
$defaultusers = array();

if($space['self'] && empty($start)) {

	space_merge($space, 'field_home');

	if($_GET['view'] == 'we') {
		require_once libfile('function/friend');
		$groups = friend_group_list();
	}

	$isnewer = ($_G['timestamp']-$space['regdate'] > 3600*24*7) ?0:1;
	if($isnewer) {

		$friendlist = array();
		$query = DB::query("SELECT * FROM ".DB::table('home_friend')." WHERE uid='$space[uid]'");
		while ($value = DB::fetch($query)) {
			$friendlist[$value['fuid']] = 1;
		}

		$query = DB::query("SELECT * FROM ".DB::table('home_specialuser')." WHERE status='1' ORDER BY displayorder");
		while ($value = DB::fetch($query)) {
			if(empty($friendlist[$value['uid']])) {
				$defaultusers[] = $value;
				$oluids[] = $value['uid'];
			}
		}
	}

	if($space['newprompt']) {
		space_merge($space, 'status');
	}

	$query = DB::query("SELECT * FROM ".DB::table('home_visitor')." WHERE uid='$space[uid]' ORDER BY dateline DESC LIMIT 0,12");
	while ($value = DB::fetch($query)) {
		$visitorlist[$value['vuid']] = $value;
		$oluids[] = $value['vuid'];
	}

	if($oluids) {
		$query = DB::query("SELECT * FROM ".DB::table('common_session')." WHERE uid IN (".dimplode($oluids).")");
		while ($value = DB::fetch($query)) {
			if(!$value['invisible']) {
				$ols[$value['uid']] = 1;
			} elseif ($visitorlist[$value['uid']]) {
				unset($visitorlist[$value['uid']]);
			}
		}
	}

	$oluids = array();
	$olfcount = 0;
	if($space['feedfriend']) {
		$query = DB::query("SELECT * FROM ".DB::table('common_session')." WHERE uid IN ($space[feedfriend]) ORDER BY lastactivity DESC LIMIT 32");
		while ($value = DB::fetch($query)) {
			if($olfcount < 16 && !$value['invisible']) {
				$olfriendlist[] = $value;
				$ols[$value['uid']] = 1;
				$oluids[$value['uid']] = $value['uid'];
				$olfcount++;
			}
		}
	}
	if($olfcount < 16) {
		$query = DB::query("SELECT fuid AS uid, fusername AS username, num FROM ".DB::table('home_friend')." WHERE uid='$space[uid]' ORDER BY num DESC, dateline DESC LIMIT 0,32");
		while ($value = DB::fetch($query)) {
			if(empty($oluids[$value['uid']])) {
				$olfriendlist[] = $value;
				$olfcount++;
				if($olfcount == 16) break;
			}
		}
	}

	if($space['feedfriend']) {
		list($s_month, $s_day) = explode('-', dgmdate($_G['timestamp']-3600*24*3, 'n-j'));
		list($n_month, $n_day) = explode('-', dgmdate($_G['timestamp'], 'n-j'));
		list($e_month, $e_day) = explode('-', dgmdate($_G['timestamp']+3600*24*7, 'n-j'));
		if($e_month == $s_month) {
			$wheresql = "sf.birthmonth='$s_month' AND sf.birthday>='$s_day' AND sf.birthday<='$e_day'";
		} else {
			$wheresql = "(sf.birthmonth='$s_month' AND sf.birthday>='$s_day') OR (sf.birthmonth='$e_month' AND sf.birthday<='$e_day' AND sf.birthday>'0')";
		}

		$query = DB::query("SELECT sf.uid,sf.birthyear,sf.birthmonth,sf.birthday,s.username
			FROM ".DB::table('common_member_profile')." sf
			LEFT JOIN ".DB::table('common_member')." s ON s.uid=sf.uid
			WHERE (sf.uid IN ($space[feedfriend])) AND ($wheresql)");
		while ($value = DB::fetch($query)) {
			$value['istoday'] = 0;
			if($value['birthmonth'] == $n_month && $value['birthday'] == $n_day) {
				$value['istoday'] = 1;
			}
			$key = sprintf("%02d", $value['birthmonth']).sprintf("%02d", $value['birthday']);
			$birthlist[$key][] = $value;
			ksort($birthlist);
		}
	}

	if($_G['setting']['feedhotnum'] > 0 && ($_GET['view'] == 'we' || $_GET['view'] == 'all')) {
		$hotlist_all = array();
		$hotstarttime = $_G['timestamp'] - $_G['setting']['feedhotday']*3600*24;
		$query = DB::query("SELECT * FROM ".DB::table('home_feed')." USE INDEX(hot) WHERE dateline>='$hotstarttime' ORDER BY hot DESC LIMIT 0,10");
		while ($value = DB::fetch($query)) {
			if($value['hot']>0 && ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
				if(empty($hotlist)) {
					$hotlist[$value['feedid']] = $value;
				} else {
					$hotlist_all[$value['feedid']] = $value;
				}
			}
		}
		$nexthotnum = $_G['setting']['feedhotnum'] - 1;
		if($nexthotnum > 0) {
			if(count($hotlist_all)> $nexthotnum) {
				$hotlist_key = array_rand($hotlist_all, $nexthotnum);
				if($nexthotnum == 1) {
					$hotlist[$hotlist_key] = $hotlist_all[$hotlist_key];
				} else {
					foreach ($hotlist_key as $key) {
						$hotlist[$key] = $hotlist_all[$key];
					}
				}
			} else {
				$hotlist = array_merge($hotlist, $hotlist_all);
			}
		}
	}
}

dsetcookie('home_readfeed', $_G['timestamp'], 365*24*3600);

$actives = array($_GET['view'] => ' class="a"');

if(empty($cp_mode)) include_once template("diy:home/space_home");

?>