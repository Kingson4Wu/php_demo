<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: my.php 10915 2010-05-18 04:48:02Z monkey $
 */

require_once('../../source/class/class_core.php');
require_once('../../source/function/function_home.php');

$cachelist = array();
$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init_cron = false;
$discuz->init_setting = true;
$discuz->init_user = false;
$discuz->init_session = false;

$discuz->init();

require_once DISCUZ_ROOT . './api/manyou/Manyou.php';

class My extends Manyou {

	function onSiteGetAllUsers($from, $userNum, $friendNum = 2000, $isExtra) {
		$totalNum = getcount('common_member', '');

		$sql = 'SELECT s.*
				FROM %s s
				ORDER BY s.uid
				LIMIT %d, %d';
		$sql = sprintf($sql, DB::table('common_member'), $from, $userNum);
		$query = DB::query($sql);

		$spaces = $uIds = array();
		while($row = DB::fetch($query)) {
			$spaces[$row['uid']] = $row;
			$uIds[] = $row['uid'];
		}

		$users = $this->getUsers($uIds, $spaces, true, $isExtra, true, $friendNum, true);
		$result = array('totalNum'	=> $totalNum,
						'users'		=> $users
					   );
		return $result;
	}

	function onSiteGetUpdatedUsers($num) {
		$totalNum = getcount('common_member_log', '');

		$users = array();
		if ($totalNum) {
			$sql = sprintf('SELECT uid, action FROM %s ORDER BY dateline LIMIT %d', DB::table('common_member_log'), $num);
			$query = DB::query($sql);
			$deletedUsers = $userLogs = $uIds = array();
			$undeletedUserIds = array();
			while($row = DB::fetch($query)) {
				$uIds[] = $row['uid'];
				if ($row['action'] == 'delete') {
					$deletedUsers[] = array('uId' => $row['uid'],
											'action' => $row['action'],
										   );
				} else {
					$undeletedUserIds[] = $row['uid'];
				}
				$userLogs[$row['uid']] = $row;
			}

			$updatedUsers = $this->getUsers($undeletedUserIds, false, true, true, false);

			foreach($updatedUsers as $k => $v) {
				$updatedUsers[$k]['action'] = $userLogs[$v['uId']]['action'];
				$updatedUsers[$k]['updateType'] = 'all';
			}

			$users = array_merge($updatedUsers, $deletedUsers);

			if ($uIds) {
				$sql = sprintf('DELETE FROM %s WHERE uid IN (%s)', DB::table('common_member_log'), dimplode($uIds));
				DB::query($sql);
			}
		}

		$result = array('totalNum'	=> $totalNum, 'users'		=> $users);
		return $result;
	}

	function onSiteGetUpdatedFriends($num) {
		$friends = array();
		$totalNum = getcount('home_friendlog', '');

		if ($totalNum) {
			$sql = sprintf('SELECT * FROM %s ORDER BY dateline LIMIT %d', DB::table('home_friendlog'), $num);
			$query = DB::query($sql);
			while ($friend = DB::fetch($query)) {
				$friends[] = array('uId'	=> $friend['uid'],
								   'uId2'	=> $friend['fuid'],
								   'action'	=> $friend['action']
								  );

				$sql = sprintf('DELETE FROM %s WHERE uid = %d AND fuid = %d', DB::table('home_friendlog'), $friend['uid'], $friend['fuid']);
				DB::query($sql);
			}
		}

		$result = array('totalNum'	=> $totalNum,
						'friends'	=> $friends
					   );
		return $result;
	}

	function onSiteGetStat($beginDate = null, $num = null, $orderType = 'ASC') {
		$sql = 'SELECT * FROM ' . DB::table('common_stat');
		if ($beginDate) {
			$sql .= sprintf(' WHERE daytime >= %d', $beginDate);
		}
		$sql .= " ORDER BY daytime $orderType";
		if ($num) {
			$sql .= " LIMIT $num ";
		}
		$query = DB::query($sql);
		$result = array();
		$fields = array('login' => 'loginUserNum',
						'doing' => 'doingNum',
						'blog'	=> 'blogNum',
						'pic'	=> 'photoNum',
						'poll'	=> 'pollNum',
						'event'	=> 'eventNum',
						'share'	=> 'shareNum',
						'thread' => 'threadNum',
						'docomment' => 'doingCommentNum',
						'blogcomment' => 'blogCommentNum',
						'piccomment' => 'photoCommentNum',
						'pollcomment' => 'pollCommentNum',
						'eventcomment' => 'eventCommentNum',
						'sharecomment'	=> 'shareCommentNum',
						'pollvote'	=> 'pollUserNum',
						'eventjoin'	=> 'eventUserNum',
						'post'	=> 'postNum',
						'wall'	=> 'wallNum',
						'poke'	=> 'pokeNum',
						'click'	=> 'clickNum',
					   );
		while($row = DB::fetch($query)) {
			$stat = array('date' => $row['daytime']);
			foreach($row as $k => $v) {
				if (array_key_exists($k, $fields)) {
					$stat[$fields[$k]] = $v;
				}
			}
			$result[] = $stat;
		}
		return $result;
	}

	function onUsersGetInfo($uIds, $fields = array(), $isExtra = false) {
		$users = $this->getUsers($uIds, false, true, $isExtra, false);
		$result = array();
		if ($users) {
			if ($fields) {
				foreach($users as $key => $user) {
					foreach($user as $k => $v) {
						if (in_array($k, $fields)) {
							$result[$key][$k] = $v;
						}
					}
				}
			}
		}

		if (!$result) {
			$result = $users;
		}

		return $result;
	}

	function onUsersGetFriendInfo($uId, $num = MY_FRIEND_NUM_LIMIT, $isExtra = false) {
		$users = $this->getUsers(array($uId), false, true, $isExtra, true, $num, false, true);

		$where = array('uid' => $uId);
		$totalNum = getcount('home_friend', $where);
		$friends = $users[0]['friends'];
		unset($users[0]['friends']);
		$result = array('totalNum'  => $totalNum,
						'friends' => $friends,
						'me'    => $users[0],
					   );
		return $result;
	}

	function onUsersGetExtraInfo($uIds) {
		$result = $this->getExtraByUsers($uIds);
		return $result;
	}

	function onFriendsGet($uIds, $friendNum = MY_FRIEND_NUM_LIMIT) {
		$result = array();
		if ($uIds) {
			foreach($uIds as $uId) {
				$result[$uId] = $this->_getFriends($uId, $friendNum);
			}
		}
		return $result;
	}

	function onFriendsAreFriends($uId1, $uId2) {
		$query = DB::query("SELECT uid FROM ".DB::table('home_friend')."  WHERE uid='$uId1' AND fuid='$uId2'");
		$result = false;
		if($friend = DB::fetch($query)) {
			$result = true;
		}

		return $result;
	}

	function onUserApplicationAdd($uId, $appId, $appName, $privacy, $allowSideNav, $allowFeed, $allowProfileLink,  $defaultBoxType, $defaultMYML, $defaultProfileLink, $version, $displayMethod, $displayOrder = null, $userPanelArea = null, $canvasTitle = null,  $isFullscreen = null , $displayUserPanel = null) {
		global $_G;

		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$sql = sprintf('SELECT appid FROM %s WHERE uid = %d AND appid = %d', DB::table('home_userapp'), $uId, $appId);
		$query = DB::query($sql);
		$row = DB::fetch($query);

		if ($row['appid']) {
			$errCode = '170';
			$errMessage = 'Application has been already added';
			return new ErrorResponse($errCode, $errMessage);
		}

		switch($privacy) {
			case 'public':
				$privacy = 0;
				break;
			case 'friends':
				$privacy = 1;
				break;
			case 'me':
				$privacy = 3;
				break;
			case 'none':
				$privacy = 5;
				break;
			default:
				$privacy = 0;
		}

		$narrow = ($defaultBoxType == 'narrow') ? 1 : 0;

		$setarr = array('uid'		=> $uId,
						'appid'	=> $appId,
						'appname'	=> $appName,
						'privacy'	=> $privacy,
						'allowsidenav'	=> $allowSideNav,
						'allowfeed'		=> $allowFeed,
						'allowprofilelink'	=> $allowProfileLink,
						'narrow'		=> $narrow
					   );
		if ($displayOrder !== null) {
			$setarr['displayorder'] = $displayOrder;
		}
		DB::insert('home_userapp', $setarr);

		$fields = array('uid'		=> $uId,
						'appid'	=> $appId,
						'profilelink'	=> $defaultProfileLink,
						'myml'			=> $defaultMYML
					   );
		$result = DB::insert('home_userappfield', $fields, 1);

		updatecreditbyaction('installapp', $uId, array(), $appId);

		DB::query("UPDATE ".DB::table('common_member_status')." SET lastactivity='$_G[timestamp]' WHERE uid='$uId'");

		$displayMethod = ($displayMethod == 'iframe') ? 1 : 0;
		$this->refreshApplication($appId, $appName, $version, $userPanelArea, $canvasTitle, $isFullscreen, $displayUserPanel, $displayMethod, $narrow, null, null);
		return 1;
	}

	function onUserApplicationRemove($uId, $appIds) {
		$sql = sprintf('DELETE FROM %s WHERE uid = %d AND appid IN (%s)', DB::table('home_userapp'), $uId, dimplode($appIds));
		$res = DB::query($sql);

		$result = DB::affected_rows();

		$sql = sprintf('DELETE FROM %s WHERE uid = %d AND appid IN (%s)', DB::table('home_userappfield'), $uId, dimplode($appIds));
		$res = DB::query($sql);

		return $result;
	}

	function onUserApplicationUpdate($uId, $appIds, $appName, $privacy, $allowSideNav, $allowFeed, $allowProfileLink, $version, $displayMethod, $displayOrder = null, $userPanelArea = null, $canvasTitle = null,  $isFullscreen = null, $displayUserPanel = null) {
		switch($privacy) {
			case 'public':
				$privacy = 0;
				break;
			case 'friends':
				$privacy = 1;
				break;
			case 'me':
				$privacy = 3;
				break;
			case 'none':
				$privacy = 5;
				break;
			default:
				$privacy = 0;
		}

		$where = sprintf('uid = %d AND appid IN (%s)', $uId, dimplode($appIds));
		$setarr = array(
			'appname'	=> $appName,
			'privacy'	=> $privacy,
			'allowsidenav'	=> $allowSideNav,
			'allowfeed'		=> $allowFeed,
			'allowprofilelink'	=> $allowProfileLink
		);
		if ($displayOrder !== null) {
			$setarr['displayorder'] = $displayOrder;
		}
		DB::update('home_userapp', $setarr, $where);

		$result = DB::affected_rows();

		$displayMethod = ($displayMethod == 'iframe') ? 1 : 0;
		if (is_array($appIds)) {
			foreach($appIds as $appId) {
				$this->refreshApplication($appId, $appName, $version, $userPanelArea, $canvasTitle, $isFullscreen, $displayUserPanel, $displayMethod, null, null, null);
			}
		}

		return $result;
	}

	function onUserApplicationGetInstalled($uId) {
		$sql = sprintf('SELECT appid FROM %s WHERE uid = %d', DB::table('home_userapp'), $uId);
		$query = DB::query($sql);
		$result = array();
		while ($userApp  = DB::fetch($query)) {
			$result[] = $userApp['appid'];
		}
		return $result;
	}

	function onUserApplicationGet($uId, $appIds) {
		$sql = sprintf('SELECT * FROM %s WHERE uid = %d AND appid IN (%s)', DB::table('home_userapp'), $uId, dimplode($appIds));
		$query = DB::query($sql);

		$result = array();
		while($userApp = DB::fetch($query)) {
			switch($userApp['privacy']) {
				case 0:
					$privacy = 'public';
					break;
				case 1:
					$privacy = 'friends';
					break;
				case 3:
					$privacy = 'me';
					break;
				case 5:
					$privacy = 'none';
					break;
				default:
					$privacy = 'public';
			}
			$result[] = array(
						'appId'		=> $userApp['appid'],
						'privacy'	=> $privacy,
						'allowSideNav'		=> $userApp['allowsidenav'],
						'allowFeed'			=> $userApp['allowfeed'],
						'allowProfileLink'	=> $userApp['allowprofilelink'],
						'displayOrder'		=> $userApp['displayorder']
						);
		}
		return $result;
	}

	function onFeedPublishTemplatizedAction($uId, $appId, $titleTemplate, $titleData, $bodyTemplate, $bodyData, $bodyGeneral = '', $image1 = '', $image1Link = '', $image2 = '', $image2Link = '', $image3 = '', $image3Link = '', $image4 = '', $image4Link = '', $targetIds = '', $privacy = '', $hashTemplate = '', $hashData = '', $specialAppid=0) {
		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$friend = ($privacy == 'public') ? 0 : ($privacy == 'friends' ? 1 : 2);

		$images = array($image1, $image2, $image3, $image4);
		$image_links = array($image1Link, $image2Link, $image3Link, $image4Link);

		$titleTemplate = $this->_myStripslashes($titleTemplate);
		$titleData = $this->_myStripslashes($titleData);
		$bodyTemplate = $this->_myStripslashes($bodyTemplate);
		$bodyData = $this->_myStripslashes($bodyData);
		$bodyGeneral = $this->_myStripslashes($bodyGeneral);

		require_once libfile('function/feed');
		$result = feed_add($appId, $titleTemplate, $titleData, $bodyTemplate, $bodyData, $bodyGeneral, $images, $image_links, $targetIds, $friend, $specialAppid, 1);

		return $result;
	}

	function onNotificationsSend($uId, $recipientIds, $appId, $notification) {
		$this->getUserSpace($uId);

		$result = array();

		$notification = $this->_myStripslashes($notification);

		require_once libfile('function/notification');
		foreach($recipientIds as $recipientId) {
			$val = intval($recipientId);
			if($val) {
				$result[$val] = notification_add($val, $appId, $notification, array(), 1) === null;
			} else {
				$result[$recipientId] = null;
			}
		}
		return $result;
	}

	function onNotificationsGet($uId) {
		$notify = $result = array();
		$result = array(
			'message' => array(
				'unread' => 0,
				'mostRecent' => 0
			),
			'notification'   => array(
				'unread' => 0 ,
				'mostRecent' => 0
			),
			'friendRequest' => array(
				'uIds' => array()
			)
		);

		$query = DB::query("SELECT * FROM ".DB::table('home_notification')."  WHERE uid='$uId' AND new='1' ORDER BY id DESC");
		$i = 0;
		while($value = DB::fetch($query)) {
			$i++;
			if(!$result['notification']['mostRecent']) $result['notification']['mostRecent'] = $value['dateline'];
		}
		$result['notification']['unread'] = $i;

		loaducenter();
		$pmarr = uc_pm_list($uId, 1, 1, 'newbox', 'newpm');
		if($pmarr['count']) {
			$result['message']['unread'] = $pmarr['count'];
			$result['message']['mostRecent'] = $pmarr['data'][0]['dateline'];
		}

		$query = DB::query("SELECT * FROM ".DB::table('home_friend_request')."  WHERE uid='$uId' ORDER BY dateline DESC");
		$fIds = array();
		while($value = DB::fetch($query)) {
			if(!$result['friendRequest']['mostRecent']) {
				$result['friendRequest']['mostRecent'] = $value['dateline'];
			}
			$fIds[] = $value['uid'];
		}
		$result['friendRequest']['uIds'] = $fIds;

		return $result;
	}

	function onApplicationUpdate($appId, $appName, $version, $displayMethod, $displayOrder = null, $userPanelArea = null, $canvasTitle = null,  $isFullscreen = null, $displayUserPanel = null) {
		$query = DB::query(sprintf('SELECT appname FROM %s  WHERE appid=%d', DB::table('common_myapp'), $appId));
		$row = DB::fetch($query);
		$result = true;
		if ($row['appname'] != $appName) {
			$fields = array('appname' => $appName);
			$where = array('appid'	=> $appId);
			$result = DB::update('home_userapp', $fields, $where);
		}

		$displayMethod = ($displayMethod == 'iframe') ? 1 : 0;
		$this->refreshApplication($appId, $appName, $version, $userPanelArea, $canvasTitle, $isFullscreen, $displayUserPanel, $displayMethod, null, null, $displayOrder);
		return $result;
	}

	function onApplicationRemove($appIds) {
		$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('home_userapp'), dimplode($appIds));
		$result = DB::query($sql);

		$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('home_userappfield'), dimplode($appIds));
		$result = DB::query($sql);

		$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('common_myapp'), dimplode($appIds));
		DB::query($sql);

		require_once libfile('function/cache');
		updatecache(array('userapp', 'myapp'));

		return $result;
	}

	function onApplicationSetFlag($applications, $flag) {
		$flag = ($flag == 'disabled') ? -1 : ($flag == 'default' ? 1 : 0);
		$appIds = array();
		if ($applications && is_array($applications)) {
			foreach($applications as $application) {
				$this->refreshApplication($application['appId'], $application['appName'], null, null, null, null, null, null, null, $flag, null);
				$appIds[] = $application['appId'];
			}
		}

		if ($flag == -1) {
			$sql = sprintf('DELETE FROM %s WHERE icon IN (%s)', DB::table('home_feed'), dimplode($appIds));
			DB::query($sql);

			$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('home_userapp'), dimplode($appIds));
			DB::query($sql);

			$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('home_userappfield'), dimplode($appIds));
			DB::query($sql);

			$sql = sprintf('DELETE FROM %s WHERE appid IN (%s)', DB::table('home_myinvite'), dimplode($appIds));
			DB::query($sql);

			$sql = sprintf('DELETE FROM %s WHERE type IN (%s)', DB::table('home_notification'), dimplode($appIds));
			DB::query($sql);
		}

		$result = true;
		return $result;
	}

	function onProfileSetMYML($uId, $appId, $markup, $actionMarkup) {
		$fields = array('myml'	=> $markup,
						'profileLink'	=> $actionMarkup);
		$where = array('uid'	=> $uId,
					   'appid'	=> $appId
					  );
		DB::update('home_userappfield', $fields, $where);
		$result = DB::affected_rows();
		return $result;
	}

	function onProfileSetActionLink($uId, $appId, $actionMarkup) {
		$fields = array('profilelink'	=> $actionMarkup);
		$where = array('uid'	=> $uId,
					   'appid'	=> $appId
					  );
		DB::update('home_userappfield', $fields, $where);
		$result = DB::affected_rows();
		return $result;
	}

	function onCreditGet($uId) {
		global $_G;

		$_G['setting']['myapp_credit'] = '';
		if($_G['setting']['creditstransextra'][7]) {
			$_G['setting']['myapp_credit'] = 'extcredits'.intval($_G['setting']['creditstransextra'][7]);
		} elseif ($_G['setting']['creditstrans']) {
			$_G['setting']['myapp_credit'] = 'extcredits'.intval($_G['setting']['creditstrans']);
		}

		if(empty($_G['setting']['myapp_credit'])) {
			return 0;
		}

		$query = DB::query('SELECT '.$_G['setting']['myapp_credit'].' AS credit FROM '
						   . DB::table('common_member_count') . ' WHERE uid =' . $uId);
		$row = DB::fetch($query);
		return $row['credit'];
	}

	function onCreditUpdate($uId, $credits, $appId, $note) {
		global $_G;

		$_G['setting']['myapp_credit'] = '';
		if($_G['setting']['creditstransextra'][7]) {
			$_G['setting']['myapp_credit'] = 'extcredits'.intval($_G['setting']['creditstransextra'][7]);
		} elseif ($_G['setting']['creditstrans']) {
			$_G['setting']['myapp_credit'] = 'extcredits'.intval($_G['setting']['creditstrans']);
		}

		$errCode = 0;
		$errMessage = 'No Credits Allowed';
		if(empty($_G['setting']['myapp_credit'])) return new ErrorResponse($errCode, $errMessage);

		$where = '';
		$type = 1;
		if ($credits < 0) {
			$where = ' AND ' . $_G['setting']['myapp_credit'] . ' >= ' . abs($credits);
			$type = 0;
		}
		$sql = sprintf('UPDATE %s SET %s = %s + %d WHERE uid=%d %s', DB::table('common_member_count'), $_G['setting']['myapp_credit'], $_G['setting']['myapp_credit'], $credits, $uId, $where);
		$result = DB::query($sql);

		if (DB::affected_rows() < 1) {
			$errCode = 180;
			$errMessage = 'No Credits Enough';
			return new ErrorResponse($errCode, $errMessage);
		}

		$fields = array(
						'uid' => $uId,
						'appid' => $appId,
						'type' => $type,
						'credit' => abs($credits),
						'note' => $note,
						'dateline' => time()
					   );
		$result = DB::insert('home_appcreditlog', $fields, 1);

		$query = DB::query('SELECT '.$_G['setting']['myapp_credit'].' AS credit FROM '
						   . DB::table('common_member_count') . ' WHERE uid =' . $uId);
		$row = DB::fetch($query);
		return $row['credit'];
	}

	function onRequestSend($uId, $recipientIds, $appId, $requestName, $myml, $type) {
		$now = time();
		$result = array();
		$type = ($type == 'request') ? 1 : 0;

		$fields = array('typename'	=> $requestName,
						'appid'	=> $appId,
						'type'	=> $type,
						'fromuid'	=> $uId,
						'dateline'	=> $now
					   );

		foreach($recipientIds as $key => $val) {
			$hash = crc32($appId . $val . $now . rand(0, 1000));
			$hash = sprintf('%u', $hash);
			$fields['touid'] = intval($val);
			$fields['hash'] = $hash;
			$fields['myml'] = str_replace('{{MyReqHash}}', $hash, $myml);
			$result[] = DB::insert('common_myinvite', $fields, 1);

			DB::query("UPDATE ".DB::table('common_member_status')." SET myinvitations=myinvitations+1 WHERE uid='$fields[touid]'");
			DB::query("UPDATE ".DB::table('common_member')." SET newprompt=newprompt+1 WHERE uid='$fields[touid]'");
		}
		return $result;
	}

	function onVideoAuthSetAuthStatus($uId, $status) {
		if ($status == 'approved') {
			$status = 1;
			updatecreditbyaction('videophoto', $uId);
		} else if($status == 'refused') {
			$status = 0;
		} else {
			$errCode = '200';
			$errMessage = 'Error arguments';
			return new ErrorResponse($errCode, $errMessage);
		}

		DB::update('common_member', array('videophotostatus' => $status), array('uid' => $uId));

		$result = DB::affected_rows();
		return $result;
	}

	function onVideoAuthAuth($uId, $picData, $picExt = 'jpg', $isReward = false) {
		global $_G;
		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$pic = base64_decode($picData);
		if (!$pic || strlen($pic) == strlen($picData)) {
			$errCode = '200';
			$errMessage = 'Error argument';
			return new ErrorResponse($errCode, $errMessage);
		}

		$secret = md5($_G['timestamp']."\t".$_G['uid']);
		$picDir = DISCUZ_ROOT . './data/avatar/' . substr($secret, 0, 1);
		if (!is_dir($picDir)) {
			if (!mkdir($picDir, 0777)) {
				$errCode = '300';
				$errMessage = 'Cannot create directory';
				return new ErrorResponse($errCode, $errMessage);
			}
		}

		$picDir .= '/' . substr($secret, 1, 1);
		if (!is_dir($picDir)) {
			if (!@mkdir($picDir, 0777)) {
				$errCode = '300';
				$errMessage = 'Cannot create directory';
				return new ErrorResponse($errCode, $errMessage);
			}
		}

		$picPath = $picDir . '/' . $secret . '.' . $picExt;
		$fp = @fopen($picPath, 'wb');
		if ($fp) {
			if (fwrite($fp, $pic) !== FALSE) {
				fclose($fp);

				DB::update('common_member', array('videophotostatus'=>1), array('uid' => $uId));
				$fields = array('videophoto' => $secret);
				DB::update('common_member_field_home', $fields, array('uid' => $uId));
				$result = DB::affected_rows();

				if ($isReward) {
					updatecreditbyaction('videophoto', $uId);
				}
				return $result;
			}
			fclose($fp);
		}

		$errCode = '300';
		$errMessage = 'Video Auth Error';
		return new ErrorResponse($errCode, $errMessage);
	}

	function onMiniBlogPost($uId, $message, $clientIdentify, $ip = '') {
		$fields = array('uid'		=> $uId,
						'message'	=> $message,
						'from'		=> $clientIdentify,
						'dateline'	=> time()
					   );
		if ($ip) {
			$fields['ip'] = $ip;
		}
		$result = DB::insert('home_doing', $fields, 1);
		return $result;
	}

	function onMiniBlogGet($uId, $num, $beginDate = null, $orderType = 'DESC') {
		$sql = 'SELECT * FROM %s WHERE uid = %d';
		$sql = sprintf($sql, DB::table('home_doing'), $uId);
		if ($beginDate) {
			$sql .= sprintf(' AND dateline >= %s', $beginDate);
		}
		$sql .= sprintf(' ORDER BY dateline %s LIMIT %d', $orderType, $num);
		$query = DB::query($sql);

		$result = array();
		while($doing = DB::fetch($query)) {
			$result[] = array('created' => $doing['dateline'],
							  'message'	=> $doing['message'],
							  'ip'		=> $doing['ip'],
							  'clientIdentify'	=> $doing['from']
							 );
		}
		return $result;
	}

	function onPhotoCreateAlbum($uId, $name, $privacy, $passwd = null, $friendIds = null) {
		require_once libfile('function/spacecp');

		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$privacy = $this->_convertPrivacy($privacy);
		if ($friendIds && is_array($friendIds)) {
			$friends = implode(',', $friendIds);
		} else {
			$friends = '';
		}

		$fields = array(
					'albumname' => $name,
					'friend' => $privacy,
					'password' => $passwd,
					'target_ids' => $friends
					);
		$result = album_creat($fields);
		return $result;
	}

	function onPhotoUpdateAlbum($uId, $aId, $name = null, $privacy = null, $passwd = null, $friendIds = null, $coverId = null) {
		$aId = intval($aId);
		if ($aId < 1) {
			$errCode = 120;
			$errMessage = 'Invalid Album Id';
			return new ErrorResponse($errCode, $errMessage);
		}

		$fields['updatetime'] = time();
		if (is_string($name) && strlen($name) > 0) {
			$fields['albumname'] = $name;
		}

		if ($privacy !== null) {
			$fields['friend'] = $this->_convertPrivacy($privacy);
		}

		if ($passwd !== null) {
			$fields['password'] = $passwd;
		}

		if ($coverId) {
			$query = DB::query('SELECT filepath, remote FROM ' . DB::table('home_pic') . ' WHERE picid=' . $coverId . ' AND uid=' . $uId . ' AND albumid=' . $aId);
			$coverInfo = DB::fetch($query);
			if ($coverInfo && is_array($coverInfo)) {
				$fields['pic'] = $coverInfo['filepath'];
				$fields['picflag'] = $coverInfo['remote']?2:1;
			} else {
				$errCode = 121;
				$errMessage = 'Invalid Picture Id';
				return new ErrorResponse($errCode, $errMessage);
			}
		}

		if ($friendIds && is_array($friendIds)) {
			$fields['target_ids'] = implode(', ', $friendIds);
		}

		DB::update('home_album', $fields, array('uid' => $uId , 'albumid' => $aId));
		$result  = DB::affected_rows();
		return $result;
	}

	function onPhotoRemoveAlbum($uId, $aId, $action = null , $targetAlbumId = null) {
		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$aId = intval($aId);
		if ($aId < 1) {
			$errCode = 120;
			$errMessage = 'Invalid Album Id';
			return new ErrorResponse($errCode, $errMessage);
		}

		if ($action == 'move') {
			$targetAlbumId = intval($targetAlbumId);
			if ($targetAlbumId < 1) {
				$errCode = 120;
				$errMessage = 'Invalid Target Album Id';
				return new ErrorResponse($errCode, $errMessage);
			}

			$sql = 'SELECT  picnum FROM ' . DB::table('home_album') . ' WHERE albumid=' . $aId . ' AND uid=' . $uId;
			$query = DB::query($sql);
			$albumInfo = DB::fetch($query);
			if (!$albumInfo) {
				$errCode = 120;
				$errMessage = 'Invalid Album Id';
				return new ErrorResponse($errCode, $errMessage);
			}

			if ($albumInfo['picnum'] > 0) {
				$sql = sprintf('UPDATE %s SET picnum = picnum + %d, dateline=%d WHERE albumid =%d AND uid=%d',
					DB::table('home_album'), $albumInfo['picnum'], time(), $targetAlbumId , $uId);
				DB::query($sql);
				$existsAlbum = DB::affected_rows();

				if (!$existsAlbum) {
					$errCode = 120;
					$errMessage = 'Invalid Target Album Id';
					return new ErrorResponse($errCode, $errMessage);
				}
				DB::update('home_pic',array('albumid' => $targetAlbumId), array('albumid' => $aId, 'uid' => $uId));
			}
		}

		require_once libfile('function/delete');
		$res = deletealbums(array($aId));
		if ($res && is_array($res)) {
			return true;
		} else {
			$errCode = 124;
			$errMessage = 'Delete Album Failure';
			return new ErrorResponse($errCode, $errMessage);
		}
	}

	function onPhotoGetAlbums($uId) {
		$sql = 'SELECT * FROM ' . DB::table('home_album') . ' WHERE uid = ' . $uId;
		$query = DB::query($sql);
		$albums = array();
		while($album = DB::fetch($query)) {
			$albums[] = $this->_convertAlbum($album);
		}
		return $albums;
	}

	function onPhotoUpload($uId, $aId, $fileName, $fileType, $fileSize, $data, $caption = null) {
		global $_G;

		$res = $this->getUserSpace($uId);
		if (!$res) {
			return new ErrorResponse('1', "User($uId) Not Exists");
		}

		$aId = intval($aId);
		if ($aId < 1) {
			$errCode = 120;
			$errMessage = 'Invalid Album Id';
			return new ErrorResponse($errCode, $errMessage);
		}

		if (!is_string($data) || strlen($data) < 1) {
			$errCode = 123;
			$errMessage = 'Uploaded File Is Not A Valid Image';
			return new ErrorResponse($errCode, $errMessage);
		}

		require_once libfile('function/spacecp');

		global $_SC;
		$attachDir = $_SC['attachdir'];
		$_SC['attachdir'] = DISCUZ_ROOT . './' . $_G['setting']['attachdir'];
		$stream = base64_decode($data);
		$res = stream_save($stream, $aId, $fileType, $fileName, $caption);
		$_SC['attachdir'] = $attachDir;

		$picInfo = array();
		if ($res && is_array($res)) {
			$picInfo['pId'] = $res['picid'];
			$picInfo['src'] = $res['filepath'];
		} else if ($res == -1) {
			$errCode = 122;
			$errMessage = 'No Enough Space';
		} else if ($res == -2) {
			$errCode = 123;
			$errMessage = 'Uploaded File Is Not A Valid Image';
		} else {
			$errCode = 1;
			$errMessage = 'Unknown Error';
		}

		if ($picInfo) {
			return $picInfo;
		} else {
			return new ErrorResponse($errCode, $errMessage);
		}
	}

	function onPhotoGet($uId, $aId, $pIds = null) {
		global $_G;
		$aId = intval($aId);
		if ($aId < 1) {
			$errCode = 120;
			$errMessage = 'Invalid Album Id';
			return new ErrorResponse($errCode, $errMessage);
		}

		$sql = 'SELECT * FROM ' . DB::table('home_pic') . ' WHERE uid=' . $uId. ' AND albumid=' . $aId ;
		if ($pIds && is_array($pIds)) {
			$sql .= ' AND picid IN (' . implode(', ', $pIds) . ' )';
		}
		$query  = DB::query($sql);
		$result = array();
		$k = 0;
		$siteUrl = $this->_getUchomeUrl();
		while ($picInfo = DB::fetch($query)) {

			$r_src = pic_get($picInfo['filepath'], 'album', $picInfo['thumb'], $picInfo['remote'], 0);
			if(!preg_match("/^(http\:\/\/|\/)/i", $r_src)) {
				$r_src = $siteUrl.$r_src;
			}

			$result[$k]['pId'] = $picInfo['picid'];
			$result[$k]['aId'] = $picInfo['albumid'];
			$result[$k]['src'] = $r_src;
			$result[$k]['caption'] = $picInfo['title'];
			$result[$k]['created'] = $picInfo['dateline'];
			$result[$k]['fileName'] = $picInfo['filename'];
			$result[$k]['fileSize'] = $picInfo['size'];
			$result[$k]['fileType'] = $picInfo['type'];
			$k++;
		}
		return $result;
	}

	function onPhotoUpdate($uId, $pId, $aId, $fileName = null, $fileType = null, $fileSize = null, $caption = null, $data = null ) {
		global $_G;

		$res = $this->getUserSpace($uId);
		if ($fileName !== null) {
			$fields['filename'] = $fileName;
		}

		if (is_string($caption) && strlen($caption) > 0) {
			$fields['title'] = $caption;
		}

		if (is_string($data) && strlen($data) > 0) {
			$query = DB::query('SELECT size, title, filename FROM ' . DB::table('home_pic') . ' WHERE picid=' . $pId. ' AND albumid=' . $aId . ' AND uid=' . $uId);
			$picInfo = DB::fetch($query);
			if ($picInfo && is_array($picInfo)) {
				require_once libfile('function/spacecp');

				$attachDir = $_SC['attachdir'];
				$_SC['attachdir'] = DISCUZ_ROOT . './' . $_G['setting']['attachdir'];
				$title = $fields['title'] ? $caption : $picInfo['title'];
				$name  = $fields['filename'] ? $fileName : $picInfo['filename'];
				$stream = base64_decode($data);
				$pic = stream_save($stream, $aId, $fileType, $name, $title, $picInfo['size']);
				$_SC['attachdir'] = $attachDir;

				$newPic = array();
				if ($pic && is_array($pic)) {
					require_once libfile('function/delete');

					deletepics(array($pId));
					DB::update('home_pic', array('picid' => $pId), array('picid' => $pic['picid']));
					$newPic['pId'] = $pId;
					$newPic['src'] = $pic['filepat'];
					return new APIResponse($newPic);
				} else if ($res == -1) {
					$errCode = 122;
					$errMessage = 'No Enough Space';
				} else if ($res == -2) {
					$errCode = 123;
					$errMessage = 'Uploaded File Is Not A Valid Image';
				} else {
					$errCode = 1;
					$errMessage = 'Unknown Error';
				}
			} else {
				$errCode = 121;
				$errMessage = 'Invalid Picture Id';
			}
			return new ErrorResponse($errCode, $errMessage);
		} else {
			$where = array('uid' => $uId, 'albumid' => $aId, 'picid' => $pId);
			DB::update('home_pic', $fields, $where);
			$query = DB::query('SELECT * FROM ' . DB::table('home_pic') . ' WHERE picid=' . $pId . ' AND uid=' . $uId . ' AND albumid=' . $aId);
			$picInfo = DB::fetch($query);
			if($picInfo && is_array($picInfo)) {
				$newPic['pId'] = $pId;
				$newPic['src'] = pic_get($picInfo['filepath'], $picInfo['thumb'], $picInfo['remote'], 0);
				if(!preg_match("/^(http\:\/\/|\/)/i", $newPic['src'])) {
					$newPic['src'] = $this->_getUchomeUrl().$newPic['src'];
				}
				return $newPic;
			} else {
				$errCode = 121;
				$errMessage = 'Invalid Picture Id';
				return new ErrorResponse($errCode, $errMessage);
			}
		}
	}

	function onPhotoRemove($uId, $pIds) {
		$result = false;
		if (!$pIds && !is_array($pIds)) {
			$errCode = 121;
			$errMessage = 'Invalid Picture Id';
			return new ErrorResponse($errCode, $errMessage);
		}

		require_once libfile('function/delete');
		$picInfos = deletepics($pIds);
		$result = array();
		$deleteIds = array();
		foreach ($picInfos as $picInfo) {
			$deleteIds[] = $picInfo['picid'];
			$result[] = array('pId' => $picInfo['picid'], 'status' => true);
		}
		$errorIds = array_diff($pIds, $deleteIds);
		foreach($errorIds as $pId) {
			$result[] = array('pId' => $pId, 'status' => false);
		}
		return $result;
	}

	function _convertAlbum($albumInfo) {
		$siteUrl = $this->_getUchomeUrl();
		if ($albumInfo && is_array($albumInfo)) {
			$convAlbum = array();
			$convAlbum['aId'] = $albumInfo['albumid'];
			$convAlbum['name']= $albumInfo['albumname'];
			$convAlbum['created'] = $albumInfo['dateline'];
			$convAlbum['updated'] = $albumInfo['updatetime'];
			$convAlbum['privacy'] = $this->_convertPrivacy($albumInfo['friend'], true);
			$convAlbum['passwd']  = $albumInfo['passwd'];
			$convAlbum['friendIds'] = ($albumInfo['target_ids']) ? explode(',', $albumInfo['target_ids']) : '';

			if($albumInfo['pic']) {
				$convAlbum['cover'] = pic_cover_get($albumInfo['pic'], $albumInfo['picflag']);
				if(!preg_match("/^(http\:\/\/|\/)/i", $convAlbum['cover'])) {
					$convAlbum['cover'] = $siteUrl.$struct['url'];
				}
			} else {
				$convAlbum['cover'] = '';
			}

			$convAlbum['url']     = $siteUrl . 'space.php?uid=' . $albumInfo['uid'] . '&do=album&id=' . $albumInfo['albumid'];
		} else {
			$convAlbum = false;
		}
		return $convAlbum;
	}

	function _getUchomeUrl() {
		global $_G;
		return dirname(dirname($_G['siteurl'])) . '/';
	}

	function onNewsFeedGet($uId, $num) {
		$result = array();
		$query = DB::query("SELECT * FROM ".DB::table('home_feed')." WHERE uid='$uId' ORDER BY dateline DESC LIMIT 0,$num");
		while($value = DB::fetch($query)) {
			$result[] = array(
				'appId' => $value['appid'],
				'created' => $value['dateline'],
				'type' => $value['icon'],
				'titleTemplate' => $value['title_template'],
				'titleData' => $value['title_data'],
				'bodyTemplate' => $value['body_template'],
				'bodyData' => $value['body_data'],
				'bodyGeneral' => $value['body_general'],
				'image1' => $value['image_1'],
				'image1Link' => $value['image_1_link'],
				'image2' => $value['image_2'],
				'image2Link' => $value['image_2_link'],
				'image3' => $value['image_3'],
				'image3Link' => $value['image_3_link'],
				'image4' => $value['image_4'],
				'image4Link' => $value['image_4_link'],
				'targetIds' => $value['target_ids'],
				'privacy' => $value['friend']==0?'public':($value['friend']==1?'friends':'someFriends')
			);
		}
		return $result;
	}

	function onImbotMsnSetBindStatus($uId, $op, $msn = null) {
		return false;
	}

	function _convertPrivacy($privacy, $u2m = false) {
		$privacys = array(0=>'public', 1=>'friends', 2=>'someFriends', 3=>'me', 4=>'passwd');
		$privacys = ($u2m) ? $privacys : array_flip($privacys);
		return $privacys[$privacy];
	}

	function _spaceInfo2Extra($rows) {
		$privacy = unserialize($rows['privacy']);
		$profilePrivacy = $privacy['profile'];

		$res = array();
		$map = array(
					 'graduateschool' => array('edu', 'school', true),
					 'company' => array('work', 'company', true),
					 'lookingfor' => array('trainwith', 'value'),
					 'interest' => array('interest', 'value'),
					 'bio' => array('intro', 'value')
					 );

		foreach ($map as $dzKey => $myKeys) {
			if ($rows[$dzKey]) {
				$data = array('privacy' => $this->_convertPrivacy($profilePrivacy[$dzKey], true), $myKeys[1] => $rows[$dzKey]);
				if ($myKeys[2]) {
					$res[$myKeys[0]][] = $data;
				} else {
					$res[$myKeys[0]] = $data;
				}
			}
		}

		return $res;
	}

	function _friends2friends($friends , $num, $isOnlyReturnId = false, $isFriendIdKey = false) {
		$i = 1;
		$res = array();
		foreach($friends as $friend) {
			if ($num) {
				if ($i > $num) {
					continue;
				}
			}
			if ($isOnlyReturnId) {
				$row  = $friend['fuid'];
			} else {
				$row = array('uId' => $friend['fuid'],
							   'handle' => $friend['fusername']
							  );
			}
			if ($isFriendIdKey) {
				$res[$friend['fuid']] = $row;
			} else {
				$res[] = $row;
			}
			$i++;
		}
		return $res;
	}

	function _space2user($space) {
		global $_G;

		if(!$space) {
			return array();
		}
		$founders = empty($_G['config']['admincp']['founder'])?array():explode(',', $_G['config']['admincp']['founder']);
		$adminLevel = 'none';
		if($space['groupid'] == 1 && $space['adminid'] == 1) {
			$adminLevel = 'manager';
			if($founders
			   && (in_array($space['uid'], $founders)
				   || (!is_numeric($space['username']) && in_array($space['username'], $founders)))) {
				$adminLevel = 'founder';
			}
		}

		$privacy = unserialize($space['privacy']);
		if (!$privacy) {
			$privacy = array();
		}

		$profilePrivacy = array();
		$map = array('affectivestatus' => 'relationshipStatus',
					 'birthday' => 'birthday',
					 'bloodtype' => 'bloodType',
					 'birthcity' => 'birthPlace',
					 'residecity' => 'residePlace',
					 'mobile' => 'mobile',
					 'qq' => 'qq',
					 'msn' => 'msn');
		$privacys = unserialize($space['privacy']);
		foreach ($map as $dzKey => $myKey) {
			$profilePrivacy[$myKey] = $this->_convertPrivacy($privacys['profile'][$dzKey], true);
		}

		$user = array(
			'uId'		=> $space['uid'],
			'handle'	=> $space['username'],
			'action'	=> $space['action'],
			'realName'	=> $space['realname'],
			'realNameChecked' => $space['realname'] ? true : false,
			'gender'	=> $space['gender'] == 1 ? 'male' : ($space['gender'] == 2 ? 'female' : 'unknown'),
			'email'		=> $space['email'],
			'qq'		=> $space['qq'],
			'msn'		=> $space['msn'],
			'birthday'	=> sprintf('%04d-%02d-%02d', $space['birthyear'], $space['birthmonth'], $space['birthday']),
			'bloodType'	=> empty($space['bloodtype']) ? 'unknown' : $space['bloodtype'],
			'relationshipStatus' => $space['affectivestatus'],
			'birthProvince' => $space['birthprovince'],
			'birthCity'	=> $space['birthcity'],
			'resideProvince' => $space['resideprovince'],
			'resideCity'	=> $space['residecity'],
			'viewNum'	=> $space['views'],
			'friendNum'	=> $space['friends'],
			'myStatus'	=> $space['spacenote'],
			'lastActivity' => $space['lastactivity'],
			'created'	=> $space['regdate'],
			'credit'	=> $space['credits'],
			'isUploadAvatar'	=> $space['avatarstatus'] ? true : false,
			'adminLevel'		=> $adminLevel,

			'homepagePrivacy'	=> $this->_convertPrivacy($privacy['view']['index'], true),
			'profilePrivacyList'	=> $profilePrivacy,
			'friendListPrivacy'	=> $this->_convertPrivacy($privacy['view']['friend'], true)
			);
		return $user;
	}

	function _getFriends($uId, $num = null) {
		global $_G;

		$sql = sprintf('SELECT fuid FROM %s WHERE uid = %d', DB::table('home_friend'), $uId);
		if ($num) {
			$sql .= ' LIMIT 0, ' . $num;
		}
		$fquery = DB::query($sql);
		$friends = array();
		while($friend = DB::fetch($fquery)) {
			$friends[] = $friend['fuid'];
		}
		return $friends;
	}

	function refreshApplication($appId, $appName, $version, $userPanelArea, $canvasTitle, $isFullscreen, $displayUserPanel, $displayMethod, $narrow, $flag, $displayOrder) {
		$fields = array();
		if ($appName !== null && strlen($appName)>1) {
			$fields['appname'] = $appName;
		}
		if ($version !== null) {
			$fields['version'] = $version;
		}
		if ($displayMethod !== null) {
			$fields['displaymethod'] = $displayMethod;
		}
		if ($narrow !== null) {
			$fields['narrow'] = $narrow;
		}
		if ($flag !== null) {
			$fields['flag'] = $flag;
		}
		if ($displayOrder !== null) {
			$fields['displayorder'] = $displayOrder;
		}
		if ($userPanelArea !== null) {
			$fields['userpanelarea'] = $userPanelArea;
		}
		if ($canvasTitle !== null) {
			$fields['canvastitle'] = $canvasTitle;
		}
		if ($isFullscreen !== null) {
			$fields['fullscreen'] = $isFullscreen;
		}
		if ($displayUserPanel !== null) {
			$fields['displayuserpanel'] = $displayUserPanel;
		}

		$sql = sprintf('SELECT * FROM %s WHERE appid = %d', DB::table('common_myapp'), $appId);
		$query = DB::query($sql);
		$result = false;
		if($application = DB::fetch($query)) {
			$where = sprintf('appid = %d', $appId);
			DB::update('common_myapp', $fields, $where);
			$result = true;
		} else {
			$fields['appid'] = $appId;
			$result = DB::insert('common_myapp', $fields, 1);
			$result = true;
		}

		require_once libfile('function/cache');
		updatecache('userapp');

		return $result;
	}

	function getUsers($uIds, $spaces = array(), $isReturnSpaceField = true, $isExtra = true, $isReturnFriends = false, $friendNum = MY_FRIEND_NUM_LIMIT, $isOnlyReturnFriendId = false, $isFriendIdKey = false) {
		if (!$uIds) {
			return array();
		}

		if (!is_array($uIds)) {
			$uIds = (array)$uIds;
		}

		if (!$spaces) {
			$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode(', ', $uIds));
			$query = DB::query($sql);
			$users2 = array();
			while($row = DB::fetch($query)) {
				$spaces[$row['uid']] = $row;
			}
		}

		$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member_count'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaces[$row['uid']] = array_merge($spaces[$row['uid']], $row);
		}

		$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member_field_home'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaces[$row['uid']] = array_merge($spaces[$row['uid']], $row);
		}

		$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member_status'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaces[$row['uid']] = array_merge($spaces[$row['uid']], $row);
		}

		$spaceFields = array();
		if ($isReturnSpaceField) {
			$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member_profile'), implode(', ', $uIds));
			$query = DB::query($sql);
			while($row = DB::fetch($query)) {
				$spaceFields[$row['uid']] = $row;
			}
		}

		$sql = sprintf('SELECT uid, privacy FROM %s WHERE uid IN (%s)', DB::table('common_member_field_home'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaceFields[$row['uid']] = array_merge($spaceFields[$row['uid']], $row);
		}

		$friends = array();
		if ($isReturnFriends) {
			$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('home_friend'), implode(', ', $uIds));
			$query = DB::query($sql);
			while($row = DB::fetch($query)) {
				$friends[$row['uid']][] = $row;
			}
		}

		$users = array();
		foreach($uIds as $uId) {
			$space = $spaces[$uId];
			if ($isReturnSpaceField) {
				$space = array_merge($spaceFields[$uId], $space);
			}
			$user = $this->_space2user($space);
			if (!$user) {
				continue;
			}

			if ($isExtra) {
				$user['extra'] = $this->_spaceInfo2Extra($spaceFields[$uId]);
			}

			if ($isReturnFriends) {
				$user['friends'] = $this->_friends2friends($friends[$uId], $friendNum, $isOnlyReturnFriendId, $isFriendIdKey);
			}
			$users[] = $user;
		}
		return $users;
	}

	function getExtraByUsers($uIds) {
		if (!$uIds) {
			return array();
		}

		if (!is_array($uIds)) {
			$uIds = (array)$uIds;
		}

		$spaceFields = array();
		$sql = sprintf('SELECT * FROM %s WHERE uid IN (%s)', DB::table('common_member_profile'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaceFields[$row['uid']] = $row;
		}

		$sql = sprintf('SELECT uid, privacy FROM %s WHERE uid IN (%s)', DB::table('common_member_field_home'), implode(', ', $uIds));
		$query = DB::query($sql);
		while($row = DB::fetch($query)) {
			$spaceFields[$row['uid']] = array_merge($spaceFields[$row['uid']], $row);
		}

		$users = array();
		foreach($uIds as $uId) {
			$user = array('uId' => $uId,
						  'extra' => $this->_spaceInfo2Extra($spaceFields[$uId]));
			$users[] = $user;
		}

		return $users;
	}

	function getUserSpace($uId) {
		global $_G;

		$space = getspace($uId);
		if (!$space['uid']) {
			return false;
		}

		$_G['uid'] = $space['uid'];
		$_G['username'] = $space['username'];

		return true;
	}

	function _myStripslashes($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = $this->_myStripslashes($val);
			}
		} else {
			$string = ($string === null) ? null : stripslashes($string);
		}
		return $string;
	}

	function onSearchGetUserGroupPermissions($userGroupIds) {
		if (!$userGroupIds) {
			return array();
		}
		$result = SearchHelper::getUserGroupPermissions($userGroupIds);
		return $result;
	}

	function onSearchGetUpdatedPosts($num, $lastPostIds = array()) {

		if ($lastPostIds) {
			$sql = sprintf("DELETE FROM %s WHERE pid IN (%s)", DB::table('forum_postlog'), implode($lastPostIds, ', '));
			DB::query($sql);
		}

		$result = array();

		$totalNum = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('forum_postlog'));
		if (!$totalNum) {
			return $result;
		}
		$result['totalNum'] = $totalNum;

		$sql = sprintf('SELECT * FROM %s
				 ORDER BY dateline
				LIMIT %d', DB::table('forum_postlog'), $num);
		$query = DB::query($sql);
		$pIds = $deletePosts = $updatePostIds = array();
		$unDeletePosts  = array();
		$posts = array();
		while($post = DB::fetch($query)) {
			$pIds[] = $post['pid'];
			if ($post['action'] == 'delete') {
				$deletePosts[$post['pid']] = array('pId' => $post['pid'],
										 'action' => $post['action'],
										 'updated' => date('Y-m-d H:i:s', $post['dateline']),
										);
			} else {
				$unDeletePosts[$post['pid']] = array('pId' => $post['pid'],
													 'action' => $post['action'],
										 			'updated' => date('Y-m-d H:i:s', $post['dateline']),
													);
			}
		}

		if ($pIds) {
			if ($unDeletePosts) {
				$posts = $this->_getPosts(array_keys($unDeletePosts));
				foreach($unDeletePosts as $pId => $updatePost) {
					if ($posts[$pId]) {
						$unDeletePosts[$pId] = array_merge($updatePost, $posts[$pId]);
					} else {
						$unDeletePosts[$pId]['pId'] = 0;
					}
				}
			}
		}
		$result['data'] = $deletePosts + $unDeletePosts;
		$result['ids']['post'] = $pIds;
		return $result;
	}

	function onSearchRemovePostLogs($pIds) {
		if (!$pIds) {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE pid IN (%s)", DB::table('forum_postlog'), implode($pIds, ', '));
		DB::query($sql);
		return true;
	}

	function _preGetPosts($table, $pIds) {
		$sql = sprintf("SELECT * FROM %s WHERE invisible = '0' AND pid IN (%s)",
					   $table, implode(', ', $pIds));
		$query = DB::query($sql);
		$result = array();
		while($post = DB::fetch($query)) {
			$result[$post['pid']] = SearchHelper::convertPost($post);
		}

		return $result;
	}

	function _getPosts($pIds) {
		global $_G;

		$infos = unserialize($_G['setting']['posttable_info']);
		if ($infos) {
			$tables = $archiveTables = array();
			$additionTable = $primaryTable = '';
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				switch($row['type']) {
					case 'primary':
						$primaryTable  = 'forum_post' . $suffix;
						break;
					case 'addition':
						$additionTable  = 'forum_post' . $suffix;
						break;
					case 'archive':
						$archiveTables[] = 'forum_post' . $suffix;
						break;
				}
			}
			$tables = $archiveTables;
			if ($additionTable) {
				array_unshift($tables, $additionTable);
			}
			array_unshift($tables, $primaryTable);
		} else {
			$tables = array('forum_post');
		}

		$tableNum = count($tables);
		$posts = array();
		for($i = 0; $i< $tableNum; $i++) {
			$_posts = $this->_preGetPosts(DB::table($tables[$i]), $pIds);
			if ($_posts) {
				if (!$posts) {
					$posts = $_posts;
				} else {
					$posts = $posts +  $_posts;
				}
				if (count($posts) == count($pIds)) {
					break;
				}
			}
		}

		if ($posts) {
			foreach($posts as $pId => $post) {
				$tIds[$post['pId']] = $post['tId'];
			}

			if ($tIds) {
				$threads = SearchHelper::getThreads($tIds);
				foreach($posts as $pId => $post) {
					$tId = $tIds[$pId];
					$posts[$pId]['isGroup'] = $threads[$tId]['isGroup'];
					if ($post['isThread']) {
						$posts[$pId]['threadInfo'] = $threads[$tId];
					}
				}
			}
		}

		return $posts;
	}

	function onSearchGetPosts($pIds) {
		$authors = array();
		$posts = $this->_getPosts($pIds);
		if ($posts) {
			foreach($posts as $post) {
				$authors[$post['authorId']][] = $post['pId'];
			}

			$authorids = array_keys($authors);
			if ($authorids) {
				$banuids= $uids = array();
				$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
				$query = DB::query($sql);
				while ($author = DB::fetch($query)) {
					$uids[$author['uid']] = $author['uid'];
					if ($author['groupid'] == 4 || $author['groupid'] == 5) {
						$banuids[] = $author['uid'];
					}
				}
				$deluids = array_diff($authorids, $uids);
				foreach($deluids as $deluid) {
					if (!$deluid) {
						continue;
					}
					foreach($authors[$deluid] as $pid) {
						$posts[$pid]['authorStatus'] = 'delete';
					}
				}
				foreach($banuids as $banuid) {
					foreach($authors[$banuid] as $pid) {
						$posts[$pid]['authorStatus'] = 'ban';
					}
				}
			}
		}
		return $posts;
	}

	function _getNewPosts($table, $num, $fromPostId = 0) {

		$result = array();

		$sql = sprintf("SELECT * FROM %s
				WHERE pid > %d
				 ORDER BY pid ASC
				LIMIT %d", $table, $fromPostId, $num);
		$query = DB::query($sql);
		while($post = DB::fetch($query)) {
			$result['maxPid'] = $post['pid'];
			if ($post['invisible'] == 0) {
				$result['data'][$post['pid']] = SearchHelper::convertPost($post);
			}
		}

		return $result;
	}

	function onSearchGetNewPosts($num, $fromPostId = 0) {
		global $_G;

		$infos = unserialize($_G['setting']['posttable_info']);
		if ($infos) {
			$tables = $archiveTables = array();
			$additionTable = $primaryTable = '';
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				switch($row['type']) {
					case 'primary':
						$primaryTable  = 'forum_post' . $suffix;
						break;
					case 'addition':
						$additionTable  = 'forum_post' . $suffix;
						break;
					case 'archive':
						$archiveTables[] = 'forum_post' . $suffix;
						break;
				}
			}
			$tables = $archiveTables;
			if ($additionTable) {
				array_unshift($tables, $additionTable);
			}
			array_unshift($tables, $primaryTable);
		} else {
			$tables = array('forum_post');
		}

		$res = $data = array();
		$debugTables = array('tables' => $tables);
		$tableNum = count($tables);
		$maxPid = 0;
		for($i = 0; $i< $tableNum; $i++) {
			$_posts = $this->_getNewPosts(DB::table($tables[$i]), $num, $fromPostId);
			if ($_posts['data']) {
				if (!$data) {
					$data = $_posts['data'];
				} else {
					$data = $data +  $_posts['data'];
				}
				if ($maxPid < $_posts['maxPid']) {
					$maxPid = $_posts['maxPid'];
				}
				$debugTables['maxPids'][] = array('current_index' => $i,
												   'maxPid' => $_posts['maxPid'],
												  );
			}
		}

		$_postNum = 0;
		for($j = $fromPostId + 1; $j <= $maxPid; $j++) {
			if (array_key_exists($j, $data)) {
				$_postNum++;
				$res['data'][$j] = $data[$j];
				$res['maxPid'] = $j;
				if ($_postNum == $num) {
					break;
				}
			}
		}

		if ($res['data']) {
			$res['debug'] = $debugTables;

			$tIds = $autors = array();
			foreach($res['data'] as $pId => $post) {
				$authors[$post['authorId']][] = $post['pId'];
				$tIds[$pId] = $post['tId'];
			}

			if ($tIds) {
				$threads = SearchHelper::getThreads($tIds);
				foreach ($tIds as $pId => $tId) {
					$res['data'][$pId]['isGroup'] = $threads[$tId]['isGroup'];
					if ($res['data'][$pId]['isThread']) {
						$res['data'][$pId]['threadInfo'] = $threads[$tId];
					}
				}
			}

			$authorids = array_keys($authors);
			if ($authorids) {
				$banuids= $uids = array();
				$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
				$query = DB::query($sql);
				while ($author = DB::fetch($query)) {
					$uids[$author['uid']] = $author['uid'];
					if ($author['groupid'] == 4 || $author['groupid'] == 5) {
						$banuids[] = $author['uid'];
					}
				}
				$deluids = array_diff($authorids, $uids);
				foreach($deluids as $deluid) {
					if (!$deluid) {
						continue;
					}
					foreach($authors[$deluid] as $pid) {
						$res['data'][$pid]['authorStatus'] = 'delete';
					}
				}
				foreach($banuids as $banuid) {
					foreach($authors[$banuid] as $pid) {
						$res['data'][$pid]['authorStatus'] = 'ban';
					}
				}
			}
		}

		return $res;
	}

	function onSearchGetAllPosts($num, $pId = 0, $orderType = 'ASC') {
		global $_G;

		$orderType = strtoupper($orderType);
		$infos = unserialize($_G['setting']['posttable_info']);
		if ($infos) {
			$tables = $archiveTables = array();
			$additionTable = $primaryTable = '';
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				$tables[] = 'forum_post' . $suffix;
				switch($row['type']) {
					case 'primary':
						$primaryTable  = 'forum_post' . $suffix;
						break;
					case 'addition':
						$additionTable  = 'forum_post' . $suffix;
						break;
					case 'archive':
						$archiveTables[] = 'forum_post' . $suffix;
						break;
				}
			}
		} else {
			$tables = array('forum_post');
		}
		$tableNum = count($tables);
		$res = $data = $_tableInfo = array();
		$maxPid = $minPid = 0;
		for($i = 0; $i < $tableNum; $i++) {
			$_posts = $this->_getAllPosts(DB::table($tables[$i]), $num, $pId, $orderType);
			if ($_posts['data']) {
				if (!$data) {
					$data = $_posts['data'];
				} else {
					$data = $data +  $_posts['data'];
				}
				if ($orderType == 'DESC') {
					if (!$minPid) {
						$minPid = $_posts['minPid'];
					}
					if ($minPid > $_posts['minPid']) {
						$minPid = $_posts['minPid'];
					}
					$_tableInfo['minPids'][] = array('current_index' => $i,
													 'minPid' => $_posts['minPid'],
													);
				} else {
					if ($maxPid < $_posts['maxPid']) {
						$maxPid = $_posts['maxPid'];
					}
					$_tableInfo['maxPids'][] = array('current_index' => $i,
													 'maxPid' => $_posts['maxPid'],
													);
				}
			}
		}
		$_postNum = 0;
		if ($orderType == 'DESC') {
			for($j = $pId - 1; $j >= $minPid; $j--) {
				if ($j == 0) {
					break;
				}
				if (array_key_exists($j, $data)) {
					$_postNum++;
					$res['minPid'] = $j;
					$res['data'][$j] = $data[$j];
					if ($_postNum == $num) {
						break;
					}
				}
			}
		} else {
			for($j = $pId + 1; $j <= $maxPid; $j++) {
				if (array_key_exists($j, $data)) {
					$_postNum++;
					$res['data'][$j] = $data[$j];
					$res['maxPid'] = $j;
					if ($_postNum == $num) {
						break;
					}
				}
			}
		}

		if ($res['data']) {
			$_tableInfo['tables'] = $tables;
			$res['debug'] = $_tableInfo;

			$tIds = $authors =  array();
			foreach($res['data'] as $pId => $post) {
				$authors[$post['authorId']][] = $post['pId'];
				$tIds[$post['pId']] = $post['tId'];
			}

			if ($tIds) {
				$threads = SearchHelper::getThreads($tIds);
				foreach($tIds as $_pId => $tId) {
					$res['data'][$_pId]['isGroup'] = $threads[$tId]['isGroup'];
					if ($res['data'][$_pId]['isThread']) {
						$res['data'][$_pId]['threadInfo'] = $threads[$tId];
					}
				}
			}

			$authorids = array_keys($authors);
			if ($authorids) {
				$banuids= $uids = array();
				$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
				$query = DB::query($sql);
				while ($author = DB::fetch($query)) {
					$uids[$author['uid']] = $author['uid'];
					if ($author['groupid'] == 4 || $author['groupid'] == 5) {
						$banuids[] = $author['uid'];
					}
				}
				$deluids = array_diff($authorids, $uids);
				foreach($deluids as $deluid) {
					if (!$deluid) {
						continue;
					}
					foreach($authors[$deluid] as $pid) {
						$res['data'][$pid]['authorStatus'] = 'delete';
					}
				}
				foreach($banuids as $banuid) {
					foreach($authors[$banuid] as $pid) {
						$res['data'][$pid]['authorStatus'] = 'ban';
					}
				}
			}

		}
		return $res;
	}

	function _getAllPosts($table, $num, $pId = 0, $orderType = 'ASC') {
		if ($orderType == 'DESC') {
			$op = '<';
			$key = 'minPid';
		} else {
			$op = '>';
			$key = 'maxPid';
		}
		$sql = sprintf("SELECT * FROM %s
				WHERE pid %s %d
				ORDER BY pid %s
				LIMIT %d", $table, $op, $pId, $orderType, $num);
		$query = DB::query($sql);
		$result = array();
		$tIds = $authors =  array();
		while($post = DB::fetch($query)) {
			$result[$key] = $post['pid'];
			if ($post['invisible'] == 0) {
				$result['data'][$post['pid']] = SearchHelper::convertPost($post);
			}
		}
		return $result;
	}

	function _removeThreads($tIds) {
		global $_G;
		$tables = array();
		$infos = unserialize($_G['setting']['threadtable_info']);
		if ($infos) {
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				$tables[] = 'forum_thread' . $suffix;
			}
		} else {
			$tables = array('forum_thread');
		}

		$tableThreads = array();
		foreach($tables as $table) {
			$_threads = SearchHelper::preGetThreads(DB::table($table), $tIds);
			$tableThreads[$table] = $_threads;
		}

		foreach($tableThreads as $table => $threads) {
			$_tids = $_threadIds = array();
			foreach($threads as $thread) {
				$_tids[] = $thread['tId'];
				$postTable = $thread['postTableId'] ? '-' . $thread['postTableId'] : '';
				$_threadIds[$postTable][] = $thread['tId'];
			}

			if ($_tids) {
				$sql = sprintf('DELETE FROM %s WHERE tid IN (%s)' , DB::table($table), implode(',', $_tids));
				DB::query($sql);
			}
			if ($_threadIds) {
				foreach($_threadIds as $postTable => $_tIds) {
					if ($_tIds) {
						$sql = sprintf('DELETE FROM %s WHERE tid IN (%s)' , DB::table('forum_post' . $postTable), implode(',', $_tIds));
						DB::query($sql);
					}
				}
			}
		}
		return true;
	}

	function onSearchRemovePosts($pIds) {
		global $_G;

		$infos = unserialize($_G['setting']['posttable_info']);
		if ($infos) {
			$tables = $archiveTables = array();
			$additionTable = $primaryTable = '';
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				switch($row['type']) {
					case 'primary':
						$primaryTable  = 'forum_post' . $suffix;
						break;
					case 'addition':
						$additionTable  = 'forum_post' . $suffix;
						break;
					case 'archive':
						$archiveTables[] = 'forum_post' . $suffix;
						break;
				}
			}
			$tables = $archiveTables;
			if ($additionTable) {
				array_unshift($tables, $additionTable);
			}
			array_unshift($tables, $primaryTable);
		} else {
			$tables = array('forum_post');
		}

		$posts = array();
		foreach($tables as $table) {
			$_posts = $this->_preGetPosts(DB::table($table), $pIds);
			$posts[$table] = $_posts;
		}
		foreach($posts as $table => $rows) {
			$tids = $pids = array();
			foreach($rows as $row) {
				if ($row['isThread']) {
					$tids[] = $row['tId'];
				} else {
					$pids[] = $row['pId'];
				}
			}
			if ($pids) {
				$sql = sprintf('DELETE FROM %s WHERE pid IN (%s)' , DB::table($table), implode(',', $pids));
				DB::query($sql);
			}

			if ($tids) {
				$this->_removeThreads($tids);
			}
		}
		return true;
	}

	function onSearchGetUpdatedThreads($num, $lastThreadIds = array(), $lastForumIds = array(), $lastUserIds = array()) {

		if ($lastThreadIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog'). ' WHERE tid IN (' . implode($lastThreadIds, ', ') . ")");
		}
		if ($lastForumIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog') . ' WHERE fid IN (' . implode($lastForumIds, ', ') . ") AND tid = 0");
		}
		if ($lastUserIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog') . ' WHERE uid IN (' . implode($lastUserIds, ', ') . ") AND tid = 0");
		}

		$result = array();

		$totalNum = DB::result_first('SELECT COUNT(*) FROM ' . DB::table('forum_threadlog'));
		if (!$totalNum) {
			return $result;
		}
		$result['totalNum'] = $totalNum;

		$tIds = $deleteThreads = $updateThreadIds = $otherLogs = $ids = array();
		$unDeleteThreads  = array();
		$threads = array();
		$sql = sprintf('SELECT * FROM %s
				 ORDER BY dateline
				LIMIT %d', DB::table('forum_threadlog'), $num);
		$query = DB::query($sql);

		$otherActions = array('mergeforum', 'banuser', 'unbanuser', 'deluser', 'delforum');
		while($thread = DB::fetch($query)) {
			$tIds[] = $thread['tid'];
			if ($thread['action'] == 'delete') {
				$ids['thread'][] = $thread['tid'];
				$deleteThreads[$thread['tid']] = array('tId' => $thread['tid'],
										 'action' => 'delete',
									 	'updated' => date('Y-m-d H:i:s', $thread['dateline']),
										);
			} elseif (in_array($thread['action'], array('banuser', 'unbanuser', 'deluser'))) {
				$ids['user'][] = $thread['uid'];
				$expiry = 0;
				if ($thread['expiry']) {
					$expiry = date('Y-m-d H:i:s', $thread['expiry']);
				}
				$otherLogs[] = array('uId' => $thread['uid'],
									 'isDeletePost' => $thread['otherid'],
									 'action' => $thread['action'],
									 'expiry' => $expiry,
									 'updated' => date('Y-m-d H:i:s', $thread['dateline']),
									);
			} elseif (in_array($thread['action'], array('mergeforum', 'delforum'))) {
				$ids['forum'][] = $thread['fid'];
				$otherLogs[] = array('fId' => $thread['fid'],
									 'otherId' => $thread['otherid'],
									 'action' => $thread['action'],
									 'updated' => date('Y-m-d H:i:s', $thread['dateline']),
									);
			} elseif (in_array($thread['action'], array('merge'))) {
				$ids['thread'][] = $thread['tid'];
				$otherLogs[] = array('tId' => $thread['tid'],
									 'fId' => $thread['fId'],
									 'otherId' => $thread['otherid'],
									 'action' => $thread['action'],
									 'updated' => date('Y-m-d H:i:s', $thread['dateline']),
									);
			} else {
				$ids['thread'][] = $thread['tid'];
				$unDeleteThreads[$thread['tid']] = array('tId' => $thread['tid'],
													 'action'  => $thread['action'],
													 'otherId' => $thread['otherid'],
									 				'updated' => date('Y-m-d H:i:s', $thread['dateline']),
													);
			}
		}

		if ($tIds) {
			if ($unDeleteThreads) {
				$threads = SearchHelper::getThreads(array_keys($unDeleteThreads));
				foreach($unDeleteThreads as $tId => $updateThread) {
					if ($threads[$tId]) {
						$unDeleteThreads[$tId] = array_merge($threads[$tId], $updateThread);
					} else {
						$unDeleteThreads[$tId]['tId'] = 0;
					}
				}
			}
		}
		$result['data'] = $deleteThreads +  $unDeleteThreads + $otherLogs;
		$result['ids'] = $ids;
		return $result;
	}

	function onSearchRemoveThreadLogs($lastThreadIds = array(), $lastForumIds = array(), $lastUserIds = array()) {

		if ($lastThreadIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog') . ' WHERE tid IN (' . implode($lastThreadIds, ', ') . ')');
		}
		if ($lastForumIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog') . ' WHERE fid IN (' . implode($lastForumIds, ', ') . ') AND tid = 0');
		}
		if ($lastUserIds) {
			DB::query('DELETE FROM ' . DB::table('forum_threadlog') . ' WHERE uid IN (' . implode($lastUserIds, ', ') . ') AND tid = 0');
		}

		return true;
	}

	function _getThread($tId) {
		$result = SearchHelper::getThreads(array($tId));
		return $result[$tId];
	}

	function onSearchGetThreads($tIds) {
		$authors = $authorids = array();
		$result = SearchHelper::getThreads($tIds);

		if ($result) {
			foreach($result as $thread) {
				$authors[$thread['authorId']][] = $thread['tId'];
			}
		}

		$authorids = array_keys($authors);
		if ($authorids) {
			$banuids= $uids = array();
			$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
			$query = DB::query($sql);
			while ($author = DB::fetch($query)) {
				$uids[$author['uid']] = $author['uid'];
				if ($author['groupid'] == 4 || $author['groupid'] == 5) {
					$banuids[] = $author['uid'];
				}
			}
			$deluids = array_diff($authorids, $uids);
			foreach($deluids as $deluid) {
				if (!$deluid) {
					continue;
				}
				foreach($authors[$deluid] as $tid) {
					$result[$tid]['authorStatus'] = 'delete';
				}
			}
			foreach($banuids as $banuid) {
				foreach($authors[$banuid] as $tid) {
					$result[$tid]['authorStatus'] = 'ban';
				}
			}
		}
		return $result;
	}

	function _getNewThreads($table, $num, $fromThreadId = 0) {
		$result = array();
		$fromThreadId = intval($fromThreadId);

		$sql = "SELECT * FROM $table
			WHERE tid > $fromThreadId
			ORDER BY tid ASC
			LIMIT $num";
		$query = DB::query($sql);
		while($thread = DB::fetch($query)) {
			$result['maxTid'] = $thread['tid'];
			if ($thread['displayorder'] >= 0) {
				$result['data'][$thread['tid']] = SearchHelper::convertThread($thread);
			}
		}

		return $result;
	}

	function onSearchGetNewThreads($num, $tId = 0) {
		global $_G;
		$tables = array();
		$infos = unserialize($_G['setting']['threadtable_info']);
		if ($infos) {
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				$tables[] = 'forum_thread' . $suffix;
			}
		} else {
			$tables = array('forum_thread');
		}

		$tableNum = count($tables);
		$res = $data = $_tableInfo = array();
		$maxTid = 0;
		for($i = 0; $i < $tableNum; $i++) {
			$_threads = $this->_getNewThreads(DB::table($tables[$i]), $num, $tId);
			if ($_threads['data']) {
				if (!$data) {
					$data = $_threads['data'];
				} else {
					$data = $data +  $_threads['data'];
				}
				if ($maxTid < $_threads['maxTid']) {
					$maxTid = $_threads['maxTid'];
				}
				$_tableInfo['maxTids'][] = array('current_index' => $i,
										'maxTid' => $_threads['maxTid'],
									   );
			}
		}
		$_threadNum = 0;
		for($j = $tId + 1; $j <= $tId + $num * $tableNum; $j++) {
			if (array_key_exists($j, $data)) {
				$_threadNum++;
				$res['maxTid'] = $j;
				$res['data'][$j] = $data[$j];
				if ($_threadNum == $num) {
					break;
				}
			}
		}

		if ($res['data']) {
			$_tableInfo['tables'] = $tables;
			$res['table'] = $_tableInfo;

			$postThreadIds = $authors = array();
			foreach($res['data'] as $tId => $thread) {
				$authors[$thread['authorId']][] = $thread['tId'];
				$postThreadIds[$thread['postTableId']][] = $thread['tId'];
			}


			$threadPosts = SearchHelper::getThreadPosts($postThreadIds);
			foreach($res['data'] as $tId => $v) {
				$res['data'][$tId]['pId'] = $threadPosts[$tId]['pId'];
			}

			$authorids = array_keys($authors);
			if ($authorids) {
				$banuids= $uids = array();
				$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
				$query = DB::query($sql);
				while ($author = DB::fetch($query)) {
					$uids[$author['uid']] = $author['uid'];
					if ($author['groupid'] == 4 || $author['groupid'] == 5) {
						$banuids[] = $author['uid'];
					}
				}
				$deluids = array_diff($authorids, $uids);
				foreach($deluids as $deluid) {
					if (!$deluid) {
						continue;
					}
					foreach($authors[$deluid] as $tid) {
						$res['data'][$tid]['authorStatus'] = 'delete';
					}
				}
				foreach($banuids as $banuid) {
					foreach($authors[$banuid] as $tid) {
						$res['data'][$tid]['authorStatus'] = 'ban';
					}
				}
			}
		}
		return $res;
	}

	function _getAllThreads($table, $num, $tid = 0, $orderType = 'ASC') {

		$result = array();

		$op = ($orderType == 'DESC') ? '<' : '>';
		$key = ($orderType == 'DESC') ? 'minTid' : 'maxTid';
		$sql = sprintf("SELECT * FROM %s
				WHERE tid %s %d
				ORDER BY tid %s
				LIMIT %d", $table, $op, $tid, $orderType, $num);
		$query = DB::query($sql);
		$tIds = array();
		while($thread = DB::fetch($query)) {
			if ($thread['displayorder'] >= 0) {
				$result[$key] = $thread['tid'];
				$result['data'][$thread['tid']] = SearchHelper::convertThread($thread);
			}
		}
		return $result;
	}

	function onSearchGetAllThreads($num, $tId = 0, $orderType = 'ASC') {
		$orderType = strtoupper($orderType);
		$tables = array();

		global $_G;
		$infos = unserialize($_G['setting']['threadtable_info']);
		if ($infos) {
			foreach($infos as $id => $row) {
				$suffix = $id ? "_$id" : '';
				$tables[] = 'forum_thread' . $suffix;
			}
		} else {
			$tables = array('forum_thread');
		}

		$tableNum = count($tables);
		$res = $data = $_tableInfo = array();
		$minTid = $maxTid = 0;
		for($i = 0; $i < $tableNum; $i++) {
			$_threads = $this->_getAllThreads(DB::table($tables[$i]), $num, $tId, $orderType);
			if ($_threads['data']) {
				if (!$data) {
					$data = $_threads['data'];
				} else {
					$data = $data +  $_threads['data'];
				}
				if ($orderType == 'DESC') {
					if (!$minTid) {
						$minTid = $_threads['minTid'];
					}
					if ($minTid > $_threads['minTid']) {
						$minTid = $_threads['minTid'];
					}
					$_tableInfo['minTids'][] = array('current_index' => $i,
													 'minTid' => $_threads['minTid'],
													);
				} else {
					if ($maxTid < $_threads['maxTid']) {
						$maxTid = $_threads['maxTid'];
					}
					$_tableInfo['maxTids'][] = array('current_index' => $i,
													 'maxTid' => $_threads['maxTid'],
													);
				}
			}
		}
		$_threadNum = 0;
		if ($orderType == 'DESC') {
			for($j = $tId - 1; $j >= $minTid; $j--) {
				if ($j == 0) {
					break;
				}
				if (array_key_exists($j, $data)) {
					$_threadNum++;
					$res['minTid'] = $j;
					$res['data'][$j] = $data[$j];
					if ($_threadNum == $num) {
						break;
					}
				}
			}
		} else {
			for($j = $tId + 1; $j <= $maxTid; $j++) {
				if (array_key_exists($j, $data)) {
					$_threadNum++;
					$res['data'][$j] = $data[$j];
					$res['maxTid'] = $j;
					if ($_threadNum == $num) {
						break;
					}
				}
			}
		}

		if ($res['data']) {
			$_tableInfo['tables'] = $tables;
			$res['table'] = $_tableInfo;

			$_tIds = array();
			$authors = array();
			foreach($res['data'] as $tId => $thread) {
				$_tIds[$thread['postTableId']][] = $tId;
				$authors[$thread['authorId']][] = $thread['tId'];
			}

			if ($_tIds) {
				$threadPosts = SearchHelper::getThreadPosts($_tIds);
				foreach($res['data'] as $tId => $v) {
					$res['data'][$tId]['pId'] = $threadPosts[$tId]['pId'];
				}
			}

			$authorids = array_keys($authors);
			if ($authorids) {
				$banuids= $uids = array();
				$sql = sprintf('SELECT uid, username, groupid FROM %s WHERE uid IN (%s)', DB::table('common_member'), implode($authorids, ', '));
				$query = DB::query($sql);
				while ($author = DB::fetch($query)) {
					$uids[$author['uid']] = $author['uid'];
					if ($author['groupid'] == 4 || $author['groupid'] == 5) {
						$banuids[] = $author['uid'];
					}
				}
				$deluids = array_diff($authorids, $uids);
				foreach($deluids as $deluid) {
					if (!$deluid) {
						continue;
					}
					foreach($authors[$deluid] as $tid) {
						$res['data'][$tid]['authorStatus'] = 'delete';
					}
				}
				foreach($banuids as $banuid) {
					foreach($authors[$banuid] as $tid) {
						$res['data'][$tid]['authorStatus'] = 'ban';
					}
				}
			}
		}
		return $res;
	}

	function onSearchGetForums($fIds = array()) {
		return SearchHelper::getForums($fIds);
	}

	function onCommonSetConfig($data) {
		$settings = array();
		if (is_array($data) && $data) {
			foreach($data as $key => $val) {
				if (substr($key, 0, 3) != 'my_') {
					continue;
				}
				$settings[] = "('$key', '$val')";
			}
			if ($settings) {
				DB::query("REPLACE INTO ".DB::table('common_setting')." (`skey`, `svalue`) VALUES ".implode(',', $settings));
				require_once DISCUZ_ROOT . './source/function/function_cache.php';
				updatecache('setting');
				return true;
			}
		}
		return false;
	}

}

$siteId = $_G['setting']['my_siteid'];
$siteKey = $_G['setting']['my_sitekey'];
$timezone = $_G['setting']['timeoffset'];
$language = $_SC['language'] ? $_SC['language'] : 'zh_CN';
$version = $_G['setting']['version'];
$myAppStatus = $_G['setting']['my_app_status'];
$mySearchStatus = $_G['setting']['my_search_status'];

$my = new My($siteId, $siteKey, $timezone, $version, CHARSET, $language, $myAppStatus, $mySearchStatus);
$my->run();

?>