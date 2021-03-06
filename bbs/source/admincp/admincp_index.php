<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_index.php 10971 2010-05-19 02:04:58Z monkey $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(@file_exists(DISCUZ_ROOT.'./install/index.php') && !DISCUZ_DEBUG) {
	@unlink(DISCUZ_ROOT.'./install/index.php');
	if(@file_exists(DISCUZ_ROOT.'./install/index.php')) {
		dexit('Please delete install/index.php via FTP!');
	}
}

@include_once DISCUZ_ROOT.'./source/discuz_version.php';
require_once libfile('function/attachment');
$isfounder = isfounder();

$siteuniqueid = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='siteuniqueid'");
if(empty($siteuniqueid) || strlen($siteuniqueid) < 16) {
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$siteuniqueid = 'DX'.$chars[date('y')%60].$chars[date('n')].$chars[date('j')].$chars[date('G')].$chars[date('i')].$chars[date('s')].substr(md5($_G['clientip'].$_G['username'].TIMESTAMP), 0, 4).random(4);
	$temp = array(
		'skey' => 'siteuniqueid',
		'svalue' => $siteuniqueid
	);
	DB::insert('common_setting', $temp, false, true);
}


if(submitcheck('notesubmit', 1)) {
	if(!empty($_G['gp_noteid']) && is_numeric($_G['gp_noteid'])) {
		DB::query("DELETE FROM ".DB::table('common_adminnote')." WHERE id='$_G[gp_noteid]'".($isfounder ? '' : " AND admin='$_G[username]'"));
	}
	if(!empty($_G['gp_newmessage'])) {
		$newaccess = 0;
		$_G['gp_newexpiration'] = TIMESTAMP + (intval($_G['gp_newexpiration']) > 0 ? intval($_G['gp_newexpiration']) : 30) * 86400;
		$_G['gp_newmessage'] = nl2br(dhtmlspecialchars($_G['gp_newmessage']));
		$data = array(
			'admin' => $_G['username'],
			'access' => 0,
			'adminid' => $_G['adminid'],
			'dateline' => $_G['timestamp'],
			'expiration' => $_G['gp_newexpiration'],
			'message' => $_G['gp_newmessage'],
		);
		DB::insert('common_adminnote', $data);
	}
}

$serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
$serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
$serversoft = $_SERVER['SERVER_SOFTWARE'];
$dbversion = DB::result_first("SELECT VERSION()");

if(@ini_get('file_uploads')) {
	$fileupload = ini_get('upload_max_filesize');
} else {
	$fileupload = '<font color="red">'.$lang['no'].'</font>';
}


$dbsize = 0;
$query = DB::query("SHOW TABLE STATUS LIKE '{$_G['config']['db'][1]['tablepre']}%'", 'SILENT');
while($table = DB::fetch($query)) {
	$dbsize += $table['Data_length'] + $table['Index_length'];
}
$dbsize = $dbsize ? sizecount($dbsize) : $lang['unknown'];

if(isset($_G['gp_attachsize'])) {
	$attachsize = DB::result(DB::query("SELECT SUM(filesize) FROM ".DB::table('forum_attachment').""), 0);
	$attachsize = is_numeric($attachsize) ? sizecount($attachsize) : $lang['unknown'];
} else {
	$attachsize = '<a href="'.ADMINSCRIPT.'?action=index&attachsize">[ '.$lang['detail'].' ]</a>';
}

$membersmod = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_member_validate')." WHERE status='0'");
$postsmod = getcountofposts(DB::table('forum_post'), "first='0' AND invisible='-2'");
$threadsdel = $threadsmod = 0;
$query = DB::query("SELECT displayorder FROM ".DB::table('forum_thread')." WHERE displayorder<'0'");
while($thread = DB::fetch($query)) {
	if($thread['displayorder'] == -1) {
		$threadsdel++;
	} elseif($thread['displayorder'] == -2) {
		$threadsmod++;
	}
}

cpheader();
shownav();

showsubmenu('home_welcome', array(), '', array('bbname' => $_G['setting']['bbname']));

$save_mastermobile = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='mastermobile'");
$save_mastermobile = !empty($save_mastermobile) ? authcode($save_mastermobile, 'DECODE', $_G['config']['security']['authkey']) : '';

$securityadvise = '';
if($isfounder) {
	$securityadvise .= !$_G['config']['admincp']['founder'] ? $lang['home_security_nofounder'] : '';
	$securityadvise .= !$_G['config']['admincp']['checkip'] ? $lang['home_security_checkip'] : '';
	$securityadvise .= $_G['config']['admincp']['runquery'] ? $lang['home_security_runquery'] : '';
	if(!empty($_G['gp_securyservice'])) {
		$_G['gp_new_mastermobile'] = trim($_G['gp_new_mastermobile']);
		if(empty($_G['gp_new_mastermobile'])) {
			$save_mastermobile = $_G['gp_new_mastermobile'];
			DB::query("REPLACE INTO ".DB::table('common_setting')." (skey, svalue) VALUES ('mastermobile', '{$_G['gp_new_mastermobile']}')");
		} elseif($save_mastermobile != $_G['gp_new_mastermobile'] && strlen($_G['gp_new_mastermobile']) == 11 && is_numeric($_G['gp_new_mastermobile']) && (substr($_G['gp_new_mastermobile'], 0, 2) == '13' || substr($_G['gp_new_mastermobile'], 0, 2) == '15')) {
			$save_mastermobile = $_G['gp_new_mastermobile'];
			$_G['gp_new_mastermobile'] = authcode($_G['gp_new_mastermobile'], 'ENCODE', $_G['config']['security']['authkey']);
			DB::query("REPLACE INTO ".DB::table('common_setting')." (skey, svalue) VALUES ('mastermobile', '{$_G['gp_new_mastermobile']}')");
		}
	}

	$view_mastermobile = !empty($save_mastermobile) ? substr($save_mastermobile, 0 , 3).'*****'.substr($save_mastermobile, -3) : '';

	$securityadvise = '<li><p>'.cplang('home_security_service_info').'</p><form method="post" autocomplete="off" action="'.ADMINSCRIPT.'?action=index&securyservice=yes">'.cplang('home_security_service_mobile').': <input type="text" class="txt" name="new_mastermobile" value="'.$view_mastermobile.'" size="30" /> <input type="submit" class="btn" name="securyservice" value="'.cplang($view_mastermobile ? 'submit' : 'home_security_service_open').'"  /> <span class="lightfont">'.cplang($view_mastermobile ? 'home_security_service_mobile_save' : 'home_security_service_mobile_none').'</span></form></li>'.$securityadvise;
}

if($securityadvise) {
	showtableheader('home_security_tips', '', '', 0);
	showtablerow('', 'class="tipsblock"', '<ul>'.$securityadvise.'</ul>');
	showtablefooter();
}

$onlines = '';
$query = DB::query("SELECT cps.uid,cps.dateline,m.username FROM ".DB::table('common_admincp_session')." cps
	LEFT JOIN ".DB::table('common_member')." m ON m.uid=cps.uid
	ORDER BY dateline DESC");
while($online = DB::fetch($query)) {
	$onlines .= '<a href="home.php?mod=space&uid='.$online['uid'].'" title="'.dgmdate($online['dateline']).'">'.$online['username'].'</a>&nbsp;&nbsp;&nbsp;';
}


echo '<div id="boardnews"></div>';

showtableheader('', 'nobottom fixpadding');
if($membersmod || $threadsmod || $postsmod || $threadsdel) {
	showtablerow('', '', '<h3 class="left margintop">'.cplang('home_mods').': </h3><p class="left difflink">'.
		($membersmod ? '<a href="'.ADMINSCRIPT.'?action=moderate&operation=members">'.cplang('home_mod_members').'</a>(<em class="lightnum">'.$membersmod.'</em>)' : '').
		($threadsmod ? '<a href="'.ADMINSCRIPT.'?action=moderate&operation=threads">'.cplang('home_mod_threads').'</a>(<em class="lightnum">'.$threadsmod.'</em>)' : '').
		($postsmod ? '<a href="'.ADMINSCRIPT.'?action=moderate&operation=replies">'.cplang('home_mod_posts').'</a>(<em class="lightnum">'.$postsmod.'</em>)' : '').
		($threadsdel ? '<a href="'.ADMINSCRIPT.'?action=recyclebin">'.cplang('home_del_threads').'</a>(<em class="lightnum">'.$threadsdel.'</em>)' : '').
		'</p><div class="clear"></div>'
	);
}
showtablefooter();

showtableheader('home_onlines', 'nobottom fixpadding');
echo '<tr><td>'.$onlines.'</td></tr>';
showtablefooter();

showformheader('index');
showtableheader('home_notes', 'fixpadding"', '', '3');

$query = DB::query("SELECT * FROM ".DB::table('common_adminnote')." WHERE access='0' ORDER BY dateline DESC");
while($note = DB::fetch($query)) {
	if($note['expiration'] < TIMESTAMP) {
		DB::query("DELETE FROM ".DB::table('common_adminnote')." WHERE id='$note[id]'");
	} else {
		$note['adminenc'] = rawurlencode($note['admin']);
		$note['expiration'] = ceil(($note['expiration'] - $note['dateline']) / 86400);
		$note['dateline'] = dgmdate($note['dateline'], 'dt');
		showtablerow('', array('', '', ''), array(
			$isfounder || $_G['member']['username'] == $note['admin'] ? '<a href="'.ADMINSCRIPT.'?action=index&notesubmit=yes&noteid='.$note['id'].'"><img src="static/image/admincp/close.gif" width="7" height="8" title="'.cplang('delete').'" /></a>' : '',
			"<span class=\"bold\"><a href=\"home.php?mod=space&username=$note[adminenc]\" target=\"_blank\">$note[admin]</a></span> $note[dateline] (".cplang('validity').": $note[expiration] ".cplang('days').")<br />$note[message]",
		));
	}
}

showtablerow('', array(), array(
	cplang('home_notes_add'),
	'<input type="text" class="txt" name="newmessage" value="" style="width:300px;" />'.cplang('validity').': <input type="text" class="txt" name="newexpiration" value="30" style="width:30px;" />'.cplang('days').'&nbsp;<input name="notesubmit" value="'.cplang('submit').'" type="submit" class="btn" />'
));
showtablefooter();
showformfooter();

loaducenter();

showtableheader('home_sys_info', 'fixpadding');
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_discuz_version'),
	'Discuz! '.DISCUZ_VERSION.' Release '.DISCUZ_RELEASE.' <a href="http://faq.comsenz.com/checkversion.php?product=Discuz&version='.DISCUZ_VERSION.'&release='.DISCUZ_RELEASE.'&charset='.CHARSET.'&dbcharset='.$dbcharset.'" class="lightlink2 smallfont" target="_blank">'.cplang('home_check_newversion').'</a> <a href="http://www.comsenz.com/purchase/discuz/" class="lightlink2 smallfont" target="_blank">&#19987;&#19994;&#25903;&#25345;&#19982;&#26381;&#21153;</a> <a href="http://idc.comsenz.com" class="lightlink2 smallfont" target="_blank">&#68;&#105;&#115;&#99;&#117;&#122;&#33;&#19987;&#29992;&#20027;&#26426;</a>'
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_ucclient_version'),
	'UCenter '.UC_CLIENT_VERSION.' Release '.UC_CLIENT_RELEASE
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_environment'),
	$serverinfo
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_serversoftware'),
	$serversoft
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_database'),
	$dbversion
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_upload_perm'),
	$fileupload
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_database_size'),
	$dbsize
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont"'), array(
	cplang('home_attach_size'),
	$attachsize
));
showtablefooter();

showtableheader('home_dev', 'fixpadding');
showtablerow('', array('class="vtop td24 lineheight"'), array(
	cplang('home_dev_copyright'),
	'<span class="bold"><a href="http://www.comsenz.com" class="lightlink2" target="_blank">&#x5eb7;&#x76db;&#x521b;&#x60f3;(&#x5317;&#x4eac;)&#x79d1;&#x6280;&#x6709;&#x9650;&#x516c;&#x53f8; (Comsenz Inc.)</a></span>'
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont team"'), array(
	cplang('home_dev_manager'),
	'<a href="http://www.discuz.net/home.php?mod=space&uid=1" class="lightlink2 smallfont" target="_blank">&#x6234;&#x5FD7;&#x5EB7; (Kevin \'Crossday\' Day)</a>'
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight smallfont team"'), array(
	cplang('home_dev_team'),
	'<a href="http://www.discuz.net/home.php?mod=space&uid=2691" class="lightlink2 smallfont" target="_blank">Liang \'Readme\' Chen</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=1519" class="lightlink2 smallfont" target="_blank">Yang \'Summer\' Xia</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=859" class="lightlink2 smallfont" target="_blank">Hypo \'cnteacher\' Wang</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=16678" class="lightlink2 smallfont" target="_blank">Yang \'Dokho\' Song</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=10407" class="lightlink2 smallfont" target="_blank">Qiang Liu</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=80629" class="lightlink2 smallfont" target="_blank">Ning \'Monkey\' Hou</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=15104" class="lightlink2 smallfont" target="_blank">Xiongfei \'Redstone\' Zhao</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=632268" class="lightlink2 smallfont" target="_blank">Jinbo \'Ggggqqqqihc\' Wang</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=246213" class="lightlink2 smallfont" target="_blank">Lanbo Liu</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=598685" class="lightlink2 smallfont" target="_blank">Guoquan Zhao</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=492114" class="lightlink2 smallfont" target="_blank">Liang \'Metthew\' Xu</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=174393" class="lightlink2 smallfont" target="_blank">Guode \'sup\' Li</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=248739" class="lightlink2 smallfont" target="_blank">Jing \'&#33540;&#23376;\' Zou</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=322293" class="lightlink2 smallfont" target="_blank">Qingpeng \'andy888\' Zheng</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=906359" class="lightlink2 smallfont" target="_blank">Peng \'dingusxp\' Xu</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=401635" class="lightlink2 smallfont" target="_blank">Guosheng \'bilicen\' Zhang</a>
	 <a href="http://www.discuz.net/home.php?mod=space&uid=1186970" class="lightlink2 smallfont" target="_blank">Chunshao \'garygay\' Chen</a>',

));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight team"'), array(
	cplang('home_dev_skins'),
	'<a href="http://www.discuz.net/home.php?mod=space&uid=294092" class="lightlink2 smallfont" target="_blank">Fangming \'Lushnis\' Li</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=674006" class="lightlink2 smallfont" target="_blank">Jizhou \'Iavav\' Yuan</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=362790" class="lightlink2 smallfont" target="_blank">Defeng \'Dfox\' Xu</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=717854" class="lightlink2 smallfont" target="_blank">Ruitao \'Pony.M\' Ma</a>'
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight team"'), array(
	cplang('home_dev_thanks'),
	'<a href="http://www.discuz.net/home.php?mod=space&uid=122246" class="lightlink2 smallfont" target="_blank">Heyond</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=210272" class="lightlink2 smallfont" target="_blank">XiaoDun \'Kenshine\' Fang</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=86282" class="lightlink2 smallfont" target="_blank">Jianxieshui</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=9600" class="lightlink2 smallfont" target="_blank">Theoldmemory</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=2629" class="lightlink2 smallfont" target="_blank">Rain5017</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=26926" class="lightlink2 smallfont" target="_blank">Snow Wolf</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=17149" class="lightlink2 smallfont" target="_blank">Hehechuan</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=9132" class="lightlink2 smallfont" target="_blank">Pk0909</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=248" class="lightlink2 smallfont" target="_blank">feixin</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=675" class="lightlink2 smallfont" target="_blank">Laobing Jiuba</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=13877" class="lightlink2 smallfont" target="_blank">Artery</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=233" class="lightlink2 smallfont" target="_blank">Huli Hutu</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=122" class="lightlink2 smallfont" target="_blank">Lao Gui</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=159" class="lightlink2 smallfont" target="_blank">Tyc</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=177" class="lightlink2 smallfont" target="_blank">Stoneage</a>
	<a href="http://www.discuz.net/home.php?mod=space&uid=7155" class="lightlink2 smallfont" target="_blank">Gregry</a>
	<a href="http://www.7dps.com" class="lightlink2 smallfont" target="_blank">Discuz! Product Support</a>'
));
showtablerow('', array('class="vtop td24 lineheight"', 'class="lineheight"'), array(
	cplang('home_dev_links'),
	'<a href="http://www.comsenz.com" class="lightlink2" target="_blank">&#x516C;&#x53F8;&#x7F51;&#x7AD9;</a>,
		<a href="http://idc.comsenz.com" class="lightlink2" target="_blank">&#x865A;&#x62DF;&#x4E3B;&#x673A;</a>,
		<a href="http://www.comsenz.com/category-51" class="lightlink2" target="_blank">&#x8D2D;&#x4E70;&#x6388;&#x6743;</a>,
		<a href="http://www.discuz.com/" class="lightlink2" target="_blank">&#x44;&#x69;&#x73;&#x63;&#x75;&#x7A;&#x21;&#x20;&#x4EA7;&#x54C1;</a>,
		<a href="http://www.comsenz.com/downloads/styles/discuz" class="lightlink2" target="_blank">&#x6A21;&#x677F;</a>,
		<a href="http://www.comsenz.com/downloads/plugins/discuz" class="lightlink2" target="_blank">&#x63D2;&#x4EF6;</a>,
		<a href="http://faq.comsenz.com" class="lightlink2" target="_blank">&#x6587;&#x6863;</a>,
		<a href="http://www.discuz.net/" class="lightlink2" target="_blank">&#x8BA8;&#x8BBA;&#x533A;</a>'
));
showtablefooter();

echo '</div>';

?>