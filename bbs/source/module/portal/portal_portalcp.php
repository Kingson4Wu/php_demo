<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: portal_portalcp.php 9843 2010-05-05 05:38:57Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = in_array($_GET['ac'], array('comment', 'article', 'block', 'portalblock', 'topic', 'diy', 'upload', 'category'))?$_GET['ac']:'index';
if(empty($ac)) {
	showmessage('portalcp_operation_invalid');
}
require_once libfile('function/portalcp');
require_once libfile('portalcp/'.$ac, 'include');
?>