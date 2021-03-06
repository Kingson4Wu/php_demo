<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_share.php 6948 2010-03-27 03:55:01Z chenchunshao $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$detail = $_G['gp_detail'];
$uid = $_G['gp_uid'];
$users = $_G['gp_users'];
$sid = $_G['gp_sid'];
$type = $_G['gp_type'];
$hot1 = $_G['gp_hot1'];
$hot2 = $_G['gp_hot2'];
$starttime = $_G['gp_starttime'];
$endtime = $_G['gp_endtime'];
$searchsubmit = $_G['gp_searchsubmit'];
$sids = $_G['gp_sids'];
$ppp = $_G['ppp'];

cpheader();

if(!submitcheck('sharesubmit')) {

	$starttime = !preg_match("/^(0|\d{4}\-\d{1,2}\-\d{1,2})$/", $starttime) ? dgmdate(TIMESTAMP - 86400 * 7, 'Y-n-j') : $starttime;
	$endtime = $_G['adminid'] == 3 || !preg_match("/^(0|\d{4}\-\d{1,2}\-\d{1,2})$/", $endtime) ? dgmdate(TIMESTAMP, 'Y-n-j') : $endtime;

	shownav('topic', 'nav_share');
	showsubmenusteps('nav_share', array(
		array('share_search', !$searchsubmit),
		array('nav_share', $searchsubmit)
	));
	showtips('share_tips');
	echo <<<EOT
<script type="text/javascript" src="static/js/forum_calendar.js"></script>
<script type="text/JavaScript">
function page(number) {
	$('shareforum').page.value=number;
	$('shareforum').searchsubmit.click();
}
</script>
EOT;
	showtagheader('div', 'searchposts', !$searchsubmit);
	showformheader("share", '', 'shareforum');
	showhiddenfields(array('page' => $page));
	showtableheader();
	showsetting('share_search_detail', 'detail', $detail, 'radio');
	showsetting('share_search_uid', 'uid', $uid, 'text');
	showsetting('share_search_user', 'users', $users, 'text');
	showsetting('share_search_sid', 'sid', $sid, 'text');
	showsetting('share_search_icon', 'type', $type, 'text');
	showsetting('share_search_hot', array('hot1', 'hot2'), array('', ''), 'range');
	showsetting('share_search_time', array('starttime', 'endtime'), array($starttime, $endtime), 'daterange');
	showsubmit('searchsubmit');
	showtablefooter();
	showformfooter();
	showtagfooter('div');

} else {
	$sids = authcode($sids, 'DECODE');
	$sidsadd = $sids ? explode(',', $sids) : $_G['gp_delete'];
	include_once libfile('function/delete');
	$deletecount = count(deleteshares($sidsadd));
	$cpmsg = cplang('share_succeed', array('deletecount' => $deletecount));

?>
<script type="text/JavaScript">alert('<?=$cpmsg?>');parent.$('shareforum').searchsubmit.click();</script>
<?php

}

if(submitcheck('searchsubmit')) {

	$sids = $sharecount = '0';
	$sql = $error = '';
	$users = trim($users);

	if($users != '') {
		$uids = '-1';
		$query = DB::query("SELECT uid FROM ".DB::table('home_share')." WHERE username IN ('".str_replace(',', '\',\'', str_replace(' ', '', $users))."')");
		while($arr = DB::fetch($query)) {
			$uids .= ",$arr[uid]";
		}
		$sql .= " AND s.uid IN ($uids)";
	}

	if($type != '') {
		$query = DB::query("SELECT type FROM ".DB::table('home_share')." WHERE type ='$type'");
		$arr = DB::fetch($query);
		$type = $arr['type'];
		$sql .= " AND s.type='$type'";
	}

	if($starttime != '0') {
		$starttime = strtotime($starttime);
		$sql .= " AND s.dateline>'$starttime'";
	}

	if($_G['adminid'] == 1 && $endtime != dgmdate(TIMESTAMP, 'Y-n-j')) {
		if($endtime != '0') {
			$endtime = strtotime($endtime);
			$sql .= " AND s.dateline<'$endtime'";
		}
	} else {
		$endtime = TIMESTAMP;
	}

	if($sid != '') {
		$sids = '-1';
		$query = DB::query("SELECT sid FROM ".DB::table('home_share')." WHERE sid IN ('".str_replace(',', '\',\'', str_replace(' ', '', $sid))."')");
		while($arr = DB::fetch($query)) {
			$sids .= ",$fidarr[sid]";
		}
		$sql .= " AND  s.sid IN ($sids)";
	}

	if($uid != '') {
		$uids = '-1';
		$query = DB::query("SELECT uid FROM ".DB::table('home_share')." WHERE uid IN ('".str_replace(',', '\',\'', str_replace(' ', '', $uid))."')");
		while($uidarr = DB::fetch($query)) {
			$uids .= ",$uidarr[uid]";
		}
		$sql .= " AND s.uid IN ($uids)";
	}

	$sql .= $hot1 ? " AND s.hot >= '$hot1'" : '';
	$sql .= $hot2 ? " AND s.hot <= '$hot2'" : '';

	if(($_G['adminid'] == 2 && $endtime - $starttime > 86400 * 16) || ($_G['adminid'] == 3 && $endtime - $starttime > 86400 * 8)) {
		$error = 'share_mod_range_illegal';
	}

	if(!$error) {
		if($detail) {
			$pagetmp = $page;
			do{
				$query = DB::query("SELECT * FROM ".DB::table('home_share')." s WHERE 1 $sql ORDER BY s.dateline DESC LIMIT ".(($pagetmp - 1) * $ppp).",{$ppp}");
				$pagetmp--;
			} while(!DB::num_rows($query) && $pagetmp);
			$shares = '';

			require_once libfile('function/share');
			while($share = DB::fetch($query)) {
				$share['dateline'] = dgmdate($share['dateline']);
				$share = mkshare($share);
				$shares .= showtablerow('', '', array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$share[sid]\" />",
					"<a href=\"home.php?mod=space&uid=$share[uid]\" target=\"_blank\">".$share['username']."</a>",
					$share['title_template'],
					$share['body_template'],
					$share['type'],
					$share['dateline']
				), TRUE);
			}
			$sharecount = DB::result_first("SELECT count(*) FROM ".DB::table('home_share')." s WHERE 1 $sql");
			$multi = multi($sharecount, $_G['ppp'], $page, ADMINSCRIPT."?action=share");
			$multi = preg_replace("/href=\"".ADMINSCRIPT."\?action=share&amp;page=(\d+)\"/", "href=\"javascript:page(\\1)\"", $multi);
			$multi = str_replace("window.location='".ADMINSCRIPT."?action=share&amp;page='+this.value", "page(this.value)", $multi);
		} else {
			$sharecount = 0;
			$query = DB::query("SELECT s.sid FROM ".DB::table('home_share')." s WHERE 1 $sql");
			while($share = DB::fetch($query)) {
				$sids .= ','.$share['sid'];
				$sharecount++;
			}
			$multi = '';
		}

		if(!$sharecount) {
			$error = 'share_post_nonexistence';
		}
	}

	showtagheader('div', 'postlist', $searchsubmit);
	showformheader('share&frame=no', 'target="shareframe"');
	showhiddenfields(array('sids' => authcode($sids, 'ENCODE')));
	showtableheader(cplang('share_result').' '.$sharecount.' <a href="###" onclick="$(\'searchposts\').style.display=\'\';$(\'postlist\').style.display=\'none\';" class="act lightlink normal">'.cplang('research').'</a>', 'fixpadding');

	if($error) {
		echo "<tr><td class=\"lineheight\" colspan=\"15\">$lang[$error]</td></tr>";
	} else {
		if($detail) {
			showsubtitle(array('', 'author', 'share_title', 'share_body', 'share_type', 'time'));
			echo $shares;
		}
	}

	showsubmit('sharesubmit', 'delete', $detail ? 'del' : '', '', $multi);
	showtablefooter();
	showformfooter();
	echo '<iframe name="shareframe" style="display:none"></iframe>';
	showtagfooter('div');

}

?>