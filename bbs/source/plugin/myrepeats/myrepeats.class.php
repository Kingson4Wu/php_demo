<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: myrepeats.class.php 6752 2010-03-25 08:47:54Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_myrepeats {

	function global_footer() {
		global $_G;
		if(!$_G['uid']) {
			return;
		}

		$myrepeatsusergroups = (array)unserialize($_G['cache']['plugin']['myrepeats']['usergroups']);
		if(in_array('', $myrepeatsusergroups)) {
			$myrepeatsusergroups = array();
		}
		if(!in_array($_G['groupid'], $myrepeatsusergroups)) {
			if(isset($_G['cookie']['mrn'])) {
				$count = $_G['cookie']['mrn'];
			} else {
				$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('myrepeats')." WHERE username='$_G[username]'");
				dsetcookie('mrn', $count, 3600);
			}
			if(!$count) {
				return;
			}
		}

		if(isset($_G['cookie']['mrd'])) {
			$userlist = explode("\t", $_G['cookie']['mrd']);
		} else {
			$userlist = array();
			$query = DB::query("SELECT username FROM ".DB::table('myrepeats')." WHERE uid='$_G[uid]'");
			while($user = DB::fetch($query)) {
				$userlist[] = $user['username'];
			}
			dsetcookie('mrd', implode("\t", $userlist), 3600);
		}
		$list = '<script>$(\'um\').innerHTML += \'<p><span id="myrepeats" class="xg1" onmouseover="showMenu(this.id)">['.lang('plugin/myrepeats', 'switch').']</span>&nbsp;</p>\';</script><ul id="myrepeats_menu" class="p_pop" style="display:none;">';
		foreach($userlist as $user) {
			$user = stripslashes($user);
			$list .= '<li><a href="plugin.php?id=myrepeats:switch&username='.rawurlencode($user).'">'.$user.'</a></li>';
		}
		$list .= '<li style="clear:both"><a href="home.php?mod=spacecp&ac=plugin&id=myrepeats:memcp">'.lang('plugin/myrepeats', 'memcp').'</a></li></ul>';
		return $list;
	}

}

class plugin_myrepeats_member extends plugin_myrepeats {

	function logging_myrepeats_output() {
		dsetcookie('mrn', '', -1);
		dsetcookie('mrd', '', -1);
	}

}