<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_feed.php 10747 2010-05-14 07:23:56Z wangjinbo $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();

$operation = $operation ? $operation : 'search';

shownav('topic', 'nav_feed');
$anchor = in_array($operation, array('search', 'global')) ? $operation : 'search';
$current = array($anchor => 1);
showsubmenu('nav_feed', array(
	array('nav_feed', 'feed', $current['search']),
	array('feed_global', 'feed&operation=global', $current['global']),
));


if($operation == 'global') {

	if(!submitcheck('globalsubmit')) {
		$feedid = intval($feedid);
		$feed = array();
		if($feedid) {
			$feed = DB::fetch_first("SELECT * FROM ".DB::table('home_feed')." WHERE feedid='$feedid'");

			if($feed['uid']) {
				$feed = mkfeed($feed);
			}
			$feed['title_template'] = dhtmlspecialchars($feed['title_template']);
			$feed['body_general'] = dhtmlspecialchars($feed['body_general']);

			$feed['dateline'] = dgmdate($feed['dateline'], 'Y-m-d H:i');
		}
		if(empty($feed['dateline'])) {
			$feed['dateline'] = dgmdate($_G['timestamp'], 'Y-m-d H:i');
		}
		showformheader('feed&operation=global');
		echo '<script type="text/javascript" src="'.STATICURL.'image/editor/editor_function.js?'.VERHASH.'"></script>';
		showtableheader();
		showsetting('feed_global_title', 'feednew[title_template]', $feed['title_template'], 'text');
		$src = 'home.php?mod=editor&charset='.CHARSET.'&allowhtml=1&doodle='.(!empty($_G['setting']['magics']['doodle']) ? 1 : 0);
		print <<<EOF
		<tr><td>{$lang['message']}</td><td></td></tr>
		<tr>
			<td colspan="2">
				<textarea class="userData" name="body_template" id="uchome-ttHtmlEditor" style="height:100%;width:100%;display:none;border:0px">$feed[body_template]</textarea>
				<iframe src="$src" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;border: 1px solid #C5C5C5;" height="400"></iframe>
			<td>
		</tr>
EOF;
		showsetting('feed_global_body_general', 'feednew[body_general]', $feed['body_general'], 'text');
		showsetting('feed_global_image_1', 'feednew[image_1]', $feed['image_1'], 'text');
		showsetting('feed_global_image_1_link', 'feednew[image_1_link]', $feed['image_1_link'], 'text');
		showsetting('feed_global_image_2', 'feednew[image_2]', $feed['image_2'], 'text');
		showsetting('feed_global_image_2_link', 'feednew[image_2_link]', $feed['image_2_link'], 'text');
		showsetting('feed_global_image_3', 'feednew[image_3]', $feed['image_3'], 'text');
		showsetting('feed_global_image_3_link', 'feednew[image_3_link]', $feed['image_3_link'], 'text');
		showsetting('feed_global_image_4', 'feednew[image_4]', $feed['image_4'], 'text');
		showsetting('feed_global_image_4_link', 'feednew[image_4_link]', $feed['image_4_link'], 'text');

		showsetting('feed_global_dateline', 'feednew[dateline]', $feed['dateline'], 'text');

		showsubmit('globalsubmit');
		showtablefooter();
		showformfooter();
	} else {
		$feednew = getgpc('feednew');
		$feedid = intval($feednew['feedid']);

		if(empty($feednew['feeduid']) || empty($feedid)) {
			$setarr = array(
				'title_template' => trim($feednew['title_template']),
				'body_template' => trim($feednew['body_template'])
			);
			if(empty($setarr['title_template']) && empty($setarr['body_template'])) {
				cpmsg('sitefeed_error', '', 'error');
			}

		} else {
			$setarr = array();
		}

		$feednew['dateline'] = trim($feednew['dateline']);
		if($feednew['dateline']) {
			require_once libfile('function/home');
			$newtimestamp = dstrtotime($feednew['dateline']);
			if($newtimestamp > $_G['timestamp']) {
				$_G['timestamp'] = $newtimestamp;
			}
		}

		if(empty($feedid)) {
			$_G['uid'] = 0;
			require_once libfile('function/feed');
			$feedid = feed_add('sitefeed',
				trim($feednew['title_template']),array(),
				trim($feednew['body_template']),array(),
				trim($feednew['body_general']),
				array(trim($feednew['image_1']),trim($feednew['image_2']),trim($feednew['image_3']),trim($feednew['image_4'])),
				array(trim($feednew['image_1_link']),trim($feednew['image_2_link']),trim($feednew['image_3_link']),trim($feednew['image_4_link'])),
				'','','',1
			);

		} else {
			if(empty($feednew['feeduid'])) {
				$setarr['body_general'] = trim($feednew['body_general']);
			}
			$setarr['image_1'] = trim($feednew['image_1']);
			$setarr['image_1_link'] = trim($feednew['image_1_link']);
			$setarr['image_2'] = trim($feednew['image_2']);
			$setarr['image_2_link'] = trim($feednew['image_2_link']);
			$setarr['image_3'] = trim($feednew['image_3']);
			$setarr['image_3_link'] = trim($feednew['image_3_link']);
			$setarr['image_4'] = trim($feednew['image_4']);
			$setarr['image_4_link'] = trim($feednew['image_4_link']);

			$setarr['dateline'] = $newtimestamp;
			$setarr['hot'] = intval($feednew['hot']);

			updatetable('feed', $setarr, array('feedid'=>$feedid));
		}
		cpmsg('feed_global_add_success', '', 'succeed');
	}

} else {

	$detail = $_G['gp_detail'];
	$uid = $_G['gp_uid'];
	$users = $_G['gp_users'];
	$feedid = $_G['gp_feedid'];
	$icon = $_G['gp_icon'];
	$hot1 = $_G['gp_hot1'];
	$hot2 = $_G['gp_hot2'];
	$starttime = $_G['gp_starttime'];
	$endtime = $_G['gp_endtime'];
	$searchsubmit = $_G['gp_searchsubmit'];
	$feedids = $_G['gp_feedids'];
	$ppp = $_G['ppp'];
	showtips('feed_tips');
	if(!submitcheck('feedsubmit')) {

		$starttime = !preg_match("/^(0|\d{4}\-\d{1,2}\-\d{1,2})$/", $starttime) ? dgmdate(TIMESTAMP - 86400 * 7, 'Y-n-j') : $starttime;
		$endtime = $_G['adminid'] == 3 || !preg_match("/^(0|\d{4}\-\d{1,2}\-\d{1,2})$/", $endtime) ? dgmdate(TIMESTAMP, 'Y-n-j') : $endtime;

		echo <<<EOT
	<script type="text/javascript" src="static/js/forum_calendar.js"></script>
	<script type="text/JavaScript">
	function page(number) {
		$('feedforum').page.value=number;
		$('feedforum').searchsubmit.click();
	}
	</script>
EOT;
		showtagheader('div', 'searchposts', !$searchsubmit);
		showformheader("feed", '', 'feedforum');
		showhiddenfields(array('page' => $page));
		showtableheader();
		showsetting('feed_search_detail', 'detail', $detail, 'radio');
		showsetting('feed_search_uid', 'uid', $uid, 'text');
		showsetting('feed_search_user', 'users', $users, 'text');
		showsetting('feed_search_feedid', 'feedid', $feedid, 'text');
		showsetting('feed_search_icon', 'icon', $icon, 'text');
		showsetting('feed_search_hot', array('hot1', 'hot2'), array('', ''), 'range');
		showsetting('feed_search_time', array('starttime', 'endtime'), array($starttime, $endtime), 'daterange');
		showsubmit('searchsubmit');
		showtablefooter();
		showformfooter();
		showtagfooter('div');

	} else {
		$feedids = authcode($feedids, 'DECODE');
		$feedidsadd = $feedids ? explode(',', $feedids) : $_G['gp_delete'];
		include_once libfile('function/delete');
		$deletecount = count(deletefeeds($feedidsadd));
		$cpmsg = cplang('feed_succeed', array('deletecount' => $deletecount));

	?>
	<script type="text/JavaScript">alert('<?=$cpmsg?>');parent.$('feedforum').searchsubmit.click();</script>
	<?php

	}

	if(submitcheck('searchsubmit')) {

		$feedids = $feedcount = '0';
		$sql = $error = '';
		$users = trim($users);

		if($users != '') {
			$uids = '-1';
			$query = DB::query("SELECT uid FROM ".DB::table('home_feed')." WHERE username IN ('".str_replace(',', '\',\'', str_replace(' ', '', $users))."')");
			while($feedarr = DB::fetch($query)) {
				$uids .= ",$feedarr[uid]";
			}
			$sql .= " AND f.uid IN ($uids)";
		}

		if($icon != '') {
			$query = DB::query("SELECT icon FROM ".DB::table('home_feed')." WHERE icon ='$icon'");
			$feedarr = DB::fetch($query);
			$icon = $feedarr[icon];
			$sql .= " AND f.icon='$icon'";
		}

		if($starttime != '0') {
			$starttime = strtotime($starttime);
			$sql .= " AND f.dateline>'$starttime'";
		}

		if($_G['adminid'] == 1 && $endtime != dgmdate(TIMESTAMP, 'Y-n-j')) {
			if($endtime != '0') {
				$endtime = strtotime($endtime);
				$sql .= " AND f.dateline<'$endtime'";
			}
		} else {
			$endtime = TIMESTAMP;
		}

		if($feedid != '') {
			$feedids = '-1';
			$query = DB::query("SELECT feedid FROM ".DB::table('home_feed')." WHERE feedid IN ('".str_replace(',', '\',\'', str_replace(' ', '', $feedid))."')");
			while($fidarr = DB::fetch($query)) {
				$feedids .= ",$fidarr[feedid]";
			}
			$sql .= " AND  f.feedid IN ($feedids)";
		}

		if($uid != '') {
			$uids = '-1';
			$query = DB::query("SELECT uid FROM ".DB::table('home_feed')." WHERE uid IN ('".str_replace(',', '\',\'', str_replace(' ', '', $uid))."')");
			while($uidarr = DB::fetch($query)) {
				$uids .= ",$uidarr[uid]";
			}
			$sql .= " AND f.uid IN ($uids)";
		}

		$sql .= $hot1 ? " AND f.hot >= '$hot1'" : '';
		$sql .= $hot2 ? " AND f.hot <= '$hot2'" : '';

		if(($_G['adminid'] == 2 && $endtime - $starttime > 86400 * 16) || ($_G['adminid'] == 3 && $endtime - $starttime > 86400 * 8)) {
			$error = 'feed_mod_range_illegal';
		}

		if(!$error) {
			if($detail) {
				$pagetmp = $page;
				do{
					$query = DB::query("SELECT * FROM ".DB::table('home_feed')." f WHERE 1 $sql ORDER BY f.dateline DESC LIMIT ".(($pagetmp - 1) * $ppp).",{$ppp}");
					$pagetmp--;
				} while(!DB::num_rows($query) && $pagetmp);
				$feeds = '';
				include_once libfile('function/feed');
				while($feed = DB::fetch($query)) {
					$feed['dateline'] = dgmdate($feed['dateline']);

					$feed = mkfeed($feed);

					$feeds .= showtablerow('', '', array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$feed[feedid]\" />",
						$feed['title_template'],
						$feed['body_template'],
						$feed['icon'],
						$feed['dateline']
					), TRUE);
				}
				$feedcount = DB::result_first("SELECT count(*) FROM ".DB::table('home_feed')." f WHERE 1 $sql");
				$multi = multi($feedcount, $ppp, $page, ADMINSCRIPT."?action=feed");
				$multi = preg_replace("/href=\"".ADMINSCRIPT."\?action=feed&amp;page=(\d+)\"/", "href=\"javascript:page(\\1)\"", $multi);
				$multi = str_replace("window.location='".ADMINSCRIPT."?action=feed&amp;page='+this.value", "page(this.value)", $multi);
			} else {
				$feedcount = 0;
				$query = DB::query("SELECT f.feedid FROM ".DB::table('home_feed')." f WHERE 1 $sql");
				while($feed = DB::fetch($query)) {
					$feedids .= ','.$feed['feedid'];
					$feedcount++;
				}
				$multi = '';
			}

			if(!$feedcount) {
				$error = 'feed_post_nonexistence';
			}
		}

		showtagheader('div', 'postlist', $searchsubmit);
		showformheader('feed&frame=no', 'target="feedframe"');
		showhiddenfields(array('feedids' => authcode($feedids, 'ENCODE')));
		showtableheader(cplang('feed_result').' '.$feedcount.' <a href="###" onclick="$(\'searchposts\').style.display=\'\';$(\'postlist\').style.display=\'none\';" class="act lightlink normal">'.cplang('research').'</a>', 'fixpadding');

		if($error) {
			echo "<tr><td class=\"lineheight\" colspan=\"15\">$lang[$error]</td></tr>";
		} else {
			if($detail) {
				showsubtitle(array('', 'feed_title', 'feed_body', 'feed_icon', 'time'));
				echo $feeds;
			}
		}

		showsubmit('feedsubmit', 'delete', $detail ? 'del' : '', '', $multi);
		showtablefooter();
		showformfooter();
		echo '<iframe name="feedframe" style="display:none"></iframe>';
		showtagfooter('div');

	}
}
?>