<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_logging.php 10517 2010-05-12 02:42:13Z zhaoxiongfei $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

if(!in_array($_G['gp_action'], array('login', 'logout', 'seccode'))) {
	showmessage('undefined_action', NULL);
}

$ctl_obj = new logging_ctl();
$method = 'on_'.$_G['gp_action'];
$ctl_obj->$method();

class logging_ctl {

	var $var = null;

	function logging_ctl() {
		require_once libfile('function/misc');
		require_once libfile('function/login');
		loaducenter();
	}

	function on_login() {
		global $_G;
		if($_G['uid']) {
			$_G['member_ucsynlogin'] = '';
			$param = array('username' => $_G['member']['username'], 'ucsynlogin' => $_G['member_ucsynlogin'], 'uid' => $_G['member']['uid']);
			showmessage('login_succeed', dreferer(), $param, array('showdialog' => 1, 'locationtime' => 1));
		}

		if(!($_G['member_loginperm'] = logincheck())) {
			showmessage('login_strike');
		}

		$seccodecheck = $_G['setting']['seccodestatus'] & 2;
		$_G['member_seccodescript'] = '';

		if($seccodecheck && $_G['setting']['seccodedata']['loginfailedcount']) {
			$seccodecheck = DB::result_first("SELECT count(*) FROM ".DB::table('common_failedlogin')." WHERE ip='$_G[clientip]' AND count>='".$_G['setting']['seccodedata']['loginfailedcount']."' AND $_G[timestamp]-lastupdate<=900");
			$_G['member_seccodescript'] = '<script type="text/javascript" reload="1">if($(\'seccodelayer\').innerHTML == \'\') ajaxget(\'member.php?mod=logging&action=seccode\', \'seccodelayer\');</script>';
		}

		$invite = getinvite();

		if(!submitcheck('loginsubmit', 1, $seccodecheck)) {

			$_G['referer'] = dreferer();

			$thetimenow = '(GMT '.($_G['setting']['timeoffset'] > 0 ? '+' : '').$_G['setting']['timeoffset'].') '.
				dgmdate(TIMESTAMP, 'u').

			$cookietimecheck = !empty($_G['cookie']['cookietime']) ? 'checked="checked"' : '';

			if($seccodecheck) {
				$seccode = random(6, 1) + $seccode{0} * 1000000;
			}

			$username = !empty($_G['cookie']['loginuser']) ? htmlspecialchars($_G['cookie']['loginuser']) : '';
			include template('member/login');

		} else {

			$_G['uid'] = $_G['member']['uid'] = 0;
			$_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
			$result = userlogin($_G['gp_username'], $_G['gp_password'], $_G['gp_questionid'], $_G['gp_answer'], $_G['setting']['autoidselect'] ? 'auto' : $_G['gp_loginfield']);

			if($result['status'] > 0) {
				setloginstatus($result['member'], $_G['gp_cookietime'] ? 2592000 : 0);
				DB::query("UPDATE ".DB::table('common_member_status')." SET lastip='".$_G['clientip']."', lastvisit='".time()."' WHERE uid='$_G[uid]'");
				$_G['member_ucsynlogin'] = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';

				include_once libfile('function/stat');
				updatestat('login');
				updatecreditbyaction('daylogin', $_G['uid']);
				checkusergroup($_G['uid']);
				if($invite['id']) {
					DB::update("common_invite", array('fuid'=>$uid, 'fusername'=>$username), array('id'=>$invite['id']));
					updatestat('invite');
				}
				if($invite['uid']) {
					require_once libfile('function/friend');
					friend_make($invite['uid'], $invite['username'], false);
					dsetcookie('invite_auth', '', -86400 * 365);
				}

				if(!empty($_G['inajax'])) {
					$_G['setting']['msgforward'] = unserialize($_G['setting']['msgforward']);
					$mrefreshtime = intval($_G['setting']['msgforward']['refreshtime']) * 1000;
					loadcache('usergroups');
					$usergroups = $_G['cache']['usergroups'][$_G['groupid']]['grouptitle'];
					$message = 1;
					include template('member/login');
				} else {
					$param = array('username' => $_G['member']['username'], 'ucsynlogin' => $_G['member_ucsynlogin'], 'uid' => $_G['member']['uid']);
					if($_G['groupid'] == 8) {
						showmessage('login_succeed_inactive_member', 'home.php?mod=space&do=home', $param);
					} else {
						showmessage('login_succeed', $invite?'home.php?mod=space&do=home':dreferer(), $param);
					}
				}
			} elseif($result['status'] == -1) {
				$auth = authcode($result['ucresult']['username']."\t".FORMHASH, 'ENCODE');
				$location = 'member.php?mod=register&action=activation&auth='.rawurlencode($auth);
				if($_G['inajax']) {
					$message = 2;
					include template('member/login');
				} else {
					showmessage('login_activation', $location);
				}
			} else {
				$password = preg_replace("/^(.{".round(strlen($_G['gp_password']) / 4)."})(.+?)(.{".round(strlen($_G['gp_password']) / 6)."})$/s", "\\1***\\3", $_G['gp_password']);
				$errorlog = dhtmlspecialchars(
					TIMESTAMP."\t".
					($result['ucresult']['username'] ? $result['ucresult']['username'] : dstripslashes($_G['gp_username']))."\t".
					$password."\t".
					"Ques #".intval($_G['gp_questionid'])."\t".
					$_G['clientip']);
				writelog('illegallog', $errorlog);
				loginfailed($_G['member_loginperm']);
				$fmsg = $result['ucresult']['uid'] == '-3' ? (empty($_G['gp_questionid']) || $answer == '' ? 'login_question_empty' : 'login_question_invalid') : 'login_invalid';
				showmessage($fmsg, '', array('loginperm' => $_G['member_loginperm'], 'seccodescript' => $_G['member_seccodescript']));
			}

		}

	}

	function on_logout() {
		global $_G;

		$_G['member_ucsynlogout'] = $_G['setting']['allowsynlogin'] ? uc_user_synlogout() : '';

		if($_G['gp_formhash'] != $_G['formhash']) {
			showmessage('logout_succeed', dreferer(), array('formhash' => FORMHASH, 'ucsynlogout' => $_G['member_ucsynlogout']));
		}

		clearcookies();
		$_G['groupid'] = $_G['member']['groupid'] = 7;
		$_G['uid'] = $_G['member']['uid'] = 0;
		$_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
		$_G['setting']['styleid'] = $_G['setting']['styleid'];

		showmessage('logout_succeed', dreferer(), array('formhash' => FORMHASH, 'ucsynlogout' => $_G['member_ucsynlogout']));
	}

	function on_seccode() {

		$seccodecheck = 1;
		include template('common/header_ajax');
		include template('common/seccheck');
		include template('common/footer_ajax');

	}
}

function clearcookies() {
	global $_G;
	foreach(array('sid', 'auth', 'visitedfid', 'onlinedetail', 'loginuser', 'activationauth', 'disableprompt', 'indextype') as $k) {
		dsetcookie($k);
	}
	$_G['uid'] = $_G['adminid'] = $_G['member']['credits'] = 0;
	$_G['username'] = $_G['member']['password'] = '';
}
?>