<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_mail.php 8214 2010-04-19 06:00:47Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


function sendmail($toemail, $subject, $message, $from = '') {
	global $_G;
	if(!is_array($_G['setting']['mail'])) {
		$_G['setting']['mail'] = unserialize($_G['setting']['mail']);
	}
	$message = preg_replace("/href\=\"(?!http\:\/\/)(.+?)\"/i", 'href="'.$_G['siteurl'].'\\1"', $message);

$message = <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$_G[charset]">
<title>$subject</title>
</head>
<body>
$subject<br />
$message
</body>
</html>
EOT;

	$maildelimiter = $_G['setting']['mail']['maildelimiter'] == 1 ? "\r\n" : ($_G['setting']['mail']['maildelimiter'] == 2 ? "\r" : "\n");
	$mailusername = isset($_G['setting']['mail']['mailusername']) ? $_G['setting']['mail']['mailusername'] : 1;
	$_G['setting']['mail']['port'] = $_G['setting']['mail']['port'] ? $_G['setting']['mail']['port'] : 25;
	$_G['setting']['mail']['mailsend'] = $_G['setting']['mail']['mailsend'] ? $_G['setting']['mail']['mailsend'] : 1;

	if($_G['setting']['mail']['mailsend'] == 3) {
		$email_from = empty($from) ? $_G['setting']['adminemail'] : $from;
	} else {
		$email_from = $from == '' ? '=?'.CHARSET.'?B?'.base64_encode($_G['setting']['sitename'])."?= <".$_G['setting']['adminemail'].">" : (preg_match('/^(.+?) \<(.+?)\>$/',$from, $mats) ? '=?'.CHARSET.'?B?'.base64_encode($mats[1])."?= <$mats[2]>" : $from);
	}

	$email_to = preg_match('/^(.+?) \<(.+?)\>$/',$toemail, $mats) ? ($mailusername ? '=?'.CHARSET.'?B?'.base64_encode($mats[1])."?= <$mats[2]>" : $mats[2]) : $toemail;;

	$email_subject = '=?'.CHARSET.'?B?'.base64_encode(preg_replace("/[\r|\n]/", '', '['.$_G['setting']['sitename'].'] '.$subject)).'?=';
	$email_message = chunk_split(base64_encode(str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message))))));

	$headers = "From: $email_from{$maildelimiter}X-Priority: 3{$maildelimiter}X-Mailer: DISCUZ ".DISCUZ_VERSION."{$maildelimiter}MIME-Version: 1.0{$maildelimiter}Content-type: text/html; charset=".CHARSET."{$maildelimiter}Content-Transfer-Encoding: base64{$maildelimiter}";

	if($_G['setting']['mail']['mailsend'] == 1) {
		if(function_exists('mail') && @mail($email_to, $email_subject, $email_message, $headers)) {
			return true;
		}
		return false;

	} elseif($_G['setting']['mail']['mailsend'] == 2) {

		if(!$fp = fsockopen($_G['setting']['mail']['server'], $_G['setting']['mail']['port'], $errno, $errstr, 30)) {
			runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) CONNECT - Unable to connect to the SMTP server", 0);
			return false;
		}
		stream_set_blocking($fp, true);

		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != '220') {
			runlog('SMTP', "$_G[setting][mail][server]:$_G[setting][mail][port] CONNECT - $lastmessage", 0);
			return false;
		}

		fputs($fp, ($_G['setting']['mail']['auth'] ? 'EHLO' : 'HELO')." uchome\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
			runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) HELO/EHLO - $lastmessage", 0);
			return false;
		}

		while(1) {
			if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
				break;
			}
			$lastmessage = fgets($fp, 512);
		}

		if($_G['setting']['mail']['auth']) {
			fputs($fp, "AUTH LOGIN\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 334) {
				runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) AUTH LOGIN - $lastmessage", 0);
				return false;
			}

			fputs($fp, base64_encode($_G['setting']['mail']['auth_username'])."\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 334) {
				runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) USERNAME - $lastmessage", 0);
				return false;
			}

			fputs($fp, base64_encode($_G['setting']['mail']['auth_password'])."\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 235) {
				runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) PASSWORD - $lastmessage", 0);
				return false;
			}

			$email_from = $_G['setting']['mail']['from'];
		}

		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 250) {
				runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) MAIL FROM - $lastmessage", 0);
				return false;
			}
		}

		fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail).">\r\n");
			$lastmessage = fgets($fp, 512);
			runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) RCPT TO - $lastmessage", 0);
			return false;
		}

		fputs($fp, "DATA\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 354) {
			runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) DATA - $lastmessage", 0);
			return false;
		}

		$headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($email_message.microtime()), 0, 6).rand(100000, 999999).'@'.$_SERVER['HTTP_HOST'].">{$maildelimiter}";

		fputs($fp, "Date: ".gmdate('r')."\r\n");
		fputs($fp, "To: ".$email_to."\r\n");
		fputs($fp, "Subject: ".$email_subject."\r\n");
		fputs($fp, $headers."\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, "$email_message\r\n.\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			runlog('SMTP', "({$_G[setting][mail][server]}:{$_G[setting][mail][port]}) END - $lastmessage", 0);
		}
		fputs($fp, "QUIT\r\n");

		return true;

	} elseif($_G['setting']['mail']['mailsend'] == 3) {

		ini_set('SMTP', $_G['setting']['mail']['server']);
		ini_set('smtp_port', $_G['setting']['mail']['port']);
		ini_set('sendmail_from', $email_from);

		if(function_exists('mail') && @mail($email_to, $email_subject, $email_message, $headers)) {
			return true;
		}
		return false;
	}
}

function sendmail_cron($toemail, $subject, $message) {

	$toemail = addslashes($toemail);

	$query = DB::query("SELECT * FROM ".DB::table('common_mailcron')." WHERE email='$toemail' LIMIT 1");
	if($value = DB::fetch($query)) {
		$cid = $value['cid'];
	} else {
		$cid = DB::insert('mailcron', array('email'=>$toemail), 1);
	}
	$message = preg_replace("/href\=\"(?!http\:\/\/)(.+?)\"/i", 'href="'.$_G['siteurl'].'\\1"', $message);
	$setarr = array(
		'cid' => $cid,
		'subject' => addslashes($subject),
		'message' => addslashes($message),
		'dateline' => $_G['timestamp']
	);
	DB::insert('common_mailqueue', $setarr);

	return true;
}

function sendmail_touser($touid, $subject, $message, $mailtype='') {
	global $_G;

	if(empty($_G['setting']['sendmailday'])) return false;

	require_once libfile('function/home');
	$tospace = getspace($touid);
	if(empty($tospace['email'])) return false;

	space_merge($tospace, 'field_home');
	space_merge($tospace, 'status');

	$acceptemail = $tospace['acceptemail'];
	if(!empty($acceptemail[$mailtype]) && $_G['timestamp'] - $tospace['lastvisit'] > $_G['setting']['sendmailday']*86400) {
		if(empty($tospace['lastsendmail'])) {
			$tospace['lastsendmail'] = $_G['timestamp'];
		}
		$sendtime = $tospace['lastsendmail'] + $acceptemail['frequency'];

		$query = DB::query("SELECT * FROM ".DB::table('common_mailcron')." WHERE touid='$touid' LIMIT 1");
		if($value = DB::fetch($query)) {
			$cid = $value['cid'];
			if($value['sendtime'] < $sendtime) $sendtime = $value['sendtime'];
			DB::update('common_mailcron', array('email'=>addslashes($tospace['email']), 'sendtime'=>$sendtime), array('cid'=>$cid));
		} else {
			$cid = DB::insert('common_mailcron', array('touid'=>$touid, 'email'=>addslashes($tospace['email']), 'sendtime'=>$sendtime), 1);
		}
		$message = preg_replace("/href\=\"(?!http\:\/\/)(.+?)\"/i", 'href="'.$_G['siteurl'].'\\1"', $message);
		$setarr = array(
			'cid' => $cid,
			'subject' => addslashes($subject),
			'message' => addslashes($message),
			'dateline' => $_G['timestamp']
		);
		DB::insert('common_mailqueue', $setarr);
		return true;
	}
	return false;
}

?>