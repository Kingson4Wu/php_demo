<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: portal.php 10257 2010-05-10 01:52:57Z zhengqingpeng $
 */

define('APPTYPEID', 4);
define('CURSCRIPT', 'portal');

require './source/class/class_core.php';
require './source/function/function_home.php';
require './source/function/function_portal.php';

$discuz = & discuz_core::instance();

$cachelist = array('userapp', 'blockclass', 'portalcategory');
$discuz->cachelist = $cachelist;
$discuz->init();

if(empty($_GET['mod']) || !in_array($_GET['mod'], array('list', 'view', 'comment', 'portalcp', 'topic', 'attachment'))) $_GET['mod'] = 'index';

define('CURMODULE', $_GET['mod']);
runhooks();

require_once libfile('portal/'.$_GET['mod'], 'module');

?>