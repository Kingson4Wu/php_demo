<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('header_ajax');?>
<?php ob_end_clean();
ob_start();
@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: application/xml; charset=".CHARSET);
echo '<?xml version="1.0" encoding="'.CHARSET.'"?>'; ?><root><![CDATA[