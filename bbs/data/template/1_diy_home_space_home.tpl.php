<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_home');
0
|| checktplrefresh('./template/default/home/space_home.htm', './template/default/common/header.htm', 1380628633, 'diy', './data/template/1_diy_home_space_home.tpl.php', './template/default', 'home/space_home')
|| checktplrefresh('./template/default/home/space_home.htm', './template/default/home/space_userabout.htm', 1380628633, 'diy', './data/template/1_diy_home_space_home.tpl.php', './template/default', 'home/space_home')
|| checktplrefresh('./template/default/home/space_home.htm', './template/default/common/footer.htm', 1380628633, 'diy', './data/template/1_diy_home_space_home.tpl.php', './template/default', 'home/space_home')
|| checktplrefresh('./template/default/home/space_home.htm', './template/default/common/css_diy.htm', 1380628633, 'diy', './data/template/1_diy_home_space_home.tpl.php', './template/default', 'home/space_home')
;?>
<?php $_G['home_tpl_titles'] = array('动态'); if(empty($diymode)) { ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>" />
<title><? if(!empty($topic['title'])) { ?><?=$topic['title']?> <? } if(!empty($_G['home_tpl_titles'])) { if(is_array($_G['home_tpl_titles'])) foreach($_G['home_tpl_titles'] as $value) { ?><?=$value?> - <? } } if(!empty($space['username'])) { ?> <?=$space['username']?>- <? } if(!empty($navtitle)) { ?><?=$navtitle?><? } ?> <?=$_G['setting']['bbname']?> <?=$_G['setting']['seotitle']?> - Powered by Discuz!</title>
<?=$_G['setting']['seohead']?>
<meta name="keywords" content="<? if(!empty($metakeywords)) { ?><?=$metakeywords?>,<? } ?><?=$_G['setting']['seokeywords']?>" />
<meta name="description" content="<? if(!empty($metadescription)) { ?><?=$metadescription?><? } ?> <?=$_G['setting']['bbname']?> <?=$_G['setting']['seodescription']?> - Discuz! Board" />
<meta name="generator" content="Discuz! <?=$_G['setting']['version']?>" />
<meta name="author" content="Discuz! Team and Comsenz UI Team" />
<meta name="copyright" content="2001-2010 Comsenz Inc." />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<? if(defined('CURMODULE') && ($_G['basescript'] == 'forum' || $_G['basescript'] == 'group') && (CURMODULE == 'index' || CURMODULE == 'forumdisplay' || CURMODULE == 'group')) { ?><?=$rsshead?><? } ?><link rel="stylesheet" type="text/css" href="data/cache/style_<?=STYLEID?>_common.css?<?=VERHASH?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?=STYLEID?>_home_space.css?<?=VERHASH?>" /><script type="text/javascript">var STYLEID = '<?=STYLEID?>', STATICURL = '<?=STATICURL?>', IMGDIR = '<?=IMGDIR?>', VERHASH = '<?=VERHASH?>', charset = '<?=CHARSET?>', discuz_uid = '<?=$_G['uid']?>', cookiepre = '<?=$_G['config']['cookie']['cookiepre']?>', cookiedomain = '<?=$_G['config']['cookie']['cookiedomain']?>', cookiepath = '<?=$_G['config']['cookie']['cookiepath']?>', attackevasive = '<?=$_G['config']['security']['attackevasive']?>', disallowfloat = '<?=$_G['setting']['disallowfloat']?>', creditnotice = '<? if($_G['setting']['creditnotice']) { ?><?=$_G['setting']['creditnames']?><? } ?>'</script>
<script src="<?=$_G['setting']['jspath']?>common.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>home_friendselector.js?<?=VERHASH?>" type="text/javascript"></script>
<? if($_G['basescript'] == 'forum' || $_G['basescript'] == 'group') { ?>
<script src="<?=$_G['setting']['jspath']?>forum.js?<?=VERHASH?>" type="text/javascript"></script>
<? } elseif($_G['basescript'] == 'home') { ?>
<script src="<?=$_G['setting']['jspath']?>home_cookie.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>home_common.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>home_face.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>home_manage.js?<?=VERHASH?>" type="text/javascript"></script>
<? } elseif($_G['basescript'] == 'userapp') { ?>
<script src="<?=$_G['setting']['jspath']?>home_common.js?<?=VERHASH?>" type="text/javascript"></script>
<? } elseif($_G['basescript'] == 'portal') { ?>
<script src="<?=$_G['setting']['jspath']?>portal.js?<?=VERHASH?>" type="text/javascript"></script>
<? } if($_G['basescript'] != 'portal' && $_GET['diy'] == 'yes' && ($_G['mod'] == 'topic' || $_G['group']['allowdiy']) && !empty($_G['style']['tplfile'])) { ?>
<script src="<?=$_G['setting']['jspath']?>portal.js?<?=VERHASH?>" type="text/javascript"></script>
<? } if($_GET['diy'] == 'yes' && ($_G['mod'] == 'topic' || $_G['group']['allowdiy']) && !empty($_G['style']['tplfile'])) { ?><style type="text/css">
/* 判断：当打开 DIY 面板是加载 */
body { padding-top: 170px; }
#controlpanel { color: #000;position: fixed; top: 0; left: 0; z-index: 200; width: 100%; border-bottom: 1px solid #999; background: #F5F5F5 url(<?=STATICURL?>image/diy/cp-bg.png) repeat-x 0 0; }
* html #controlpanel { position: absolute; top: expression(offsetParent.scrollTop); }
#controlheader { margin: 0 10px; background: url(<?=STATICURL?>image/diy/cp-nav.png) repeat-x 0 0; line-height: 46px; }
#controlheader p { width: 320px; height: 46px; background: url(<?=STATICURL?>image/diy/cp-nav-corner-tr.png) no-repeat 100% 100%; }
#controlheader p a { float: right; margin-top: 8px; height: 27px; line-height: 27px; }
#navsave a { margin-right: 2px; width: 54px; background: url(<?=STATICURL?>image/diy/cp-save.png) no-repeat 0 0; text-indent: -9999px; overflow: hidden; }
#navcancel a { width: 27px; background: url(<?=STATICURL?>image/diy/cp-close.png) no-repeat 0 0; text-indent: -9999px; overflow: hidden; }
#button_redo a, #button_undo a { background: url(<?=STATICURL?>image/diy/cp-step.png) no-repeat 0 0; text-indent: -9999px; overflow: hidden; }
#button_redo a { margin-right: 5px; width: 27px; background-position: 0 -54px; }
#button_redo.unusable a { background-position: 0 -81px; cursor: default; }
#button_undo a { margin-left: 10px; width: 28px; }
#button_undo.unusable a { background-position: 0 -27px; cursor: default; }
#button_more { margin-right: 20px; width: 17px; height: 27px; background: url(<?=STATICURL?>image/diy/cp-more.png) no-repeat 0 0; text-indent: -9999px; overflow: hidden; }
#preview a { margin-right: 2px; width: 43px; background: url(<?=STATICURL?>image/diy/cp-preview.png) no-repeat 0 -27px; text-indent: -9999px; overflow: hidden; }
#preview.unusable a { background-position: 0 0; }
#controlnav { padding-left: 50px; height: 46px; background: transparent url(<?=STATICURL?>image/diy/t-diy.png) no-repeat 0 100%; line-height: 35px; }
#controlnav li { float: left; margin-top: 7px; margin-right: 5px; font-size: 14px; font-weight: 700; }
#controlnav li a { float: left; padding: 0 15px; }
#controlnav li.current { background: url(<?=STATICURL?>image/diy/cp-nav-active.png) no-repeat 0 0; }
#controlnav li.current a { background: url(<?=STATICURL?>image/diy/cp-nav-active.png) no-repeat 100% -50px; }
#controlcontent { margin: 0 10px; padding: 10px; height: 110px; he\ight: 90px; border: solid #CCBDCC; border-width: 0 1px; background: #FFF; overflow: hidden; }
#controlcontent li { float: left; margin-right: 5px; }
#controlcontent li a, #controlcontent li label { float: left; width: 90px; height: 90px; text-align: center; cursor: pointer; }
#controlcontent li a:hover, #controlcontent li label.hover { background: url(<?=STATICURL?>image/diy/cp-item-selected.png) no-repeat 0 0; text-decoration: none; }
#controlcontent li img, #controlcontent li label span { display: block; margin: 7px auto 0; width: 80px; height: 60px; text-align: center; }
#controlcontent li span { background: url(<?=STATICURL?>image/diy/cp-module-type.png) no-repeat 0 0; }
#controlcontent li.module-html span { background-position: -80px -180px; }
#controlcontent li.module-thread span { background-position: -80px 0; }
#controlcontent li.module-forum span { background-position: 0 -180px; }
#controlcontent li.module-member span { background-position: 0 -120px; }
#controlcontent li.module-article span { background-position: -80px -120px; }
#controlcontent li.module-attachment span { background-position: 0 -240px; }
#controlcontent li.module-blog span { background-position: 0 -60px; }
#controlcontent li.module-doing span { background-position: -80px -60px; }
#contentblockclass li ul { padding-left: 50px; width: 80px; height: 90px; border-right: 1px solid #CCC; background-position: 10px 0; background-repeat: no-repeat; }
#contentblockclass li.module-html ul { background-image: url(<?=STATICURL?>image/diy/module-html.png); }
#contentblockclass li.module-forum ul { background-image: url(<?=STATICURL?>image/diy/module-forum.png); }
#contentblockclass li.module-space ul { background-image: url(<?=STATICURL?>image/diy/module-space.png); }
#contentblockclass li.module-forum ul, #contentblockclass li.module-space ul, #contentblockclass li.module-group ul { width: 150px; }
#contentblockclass li.module-group ul { background-image: url(<?=STATICURL?>image/diy/module-group.png); }
#contentblockclass li.module-portal ul { background-image: url(<?=STATICURL?>image/diy/module-article.png); }
#contentblockclass li.module-member ul { background-image: url(<?=STATICURL?>image/diy/module-member.png); }
#contentblockclass li li label { margin-bottom: 3px; width: 60px; height: 24px; border: 1px dotted #B6C7EC; line-height: 24px; overflow: hidden; }
#contentblockclass li li label.hover { border-style: solid; background: none; }
#cpfooter { padding: 0 10px 10px; border-bottom: 1px solid #FFF; }
#cpfooter td { height: 6px; background: #FFF; line-height: 0; overflow: hidden; }
#cpfooter td.l { width: 6px; background: url(<?=STATICURL?>image/diy/cp-nav-corner-bl.png) no-repeat 0 0; }
#cpfooter td.c { height: 5px; border-bottom: 1px solid #CCBDCC; }
#cpfooter td.r { width: 6px; background: url(<?=STATICURL?>image/diy/cp-nav-corner-br.png) no-repeat 0 0; }

/* DIY 部分 */
dl.diy dt { margin-top: -10px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #CCC; line-height: 26px; }
dl.diy dt a { color: #06C; text-decoration: underline; }
dl.diy dt a.activity { color: #222; font-weight: 700; text-decoration: none; }
dl.diy dl { clear: both; padding: 0 10px; }
dl.diy dl dt { float: left; margin: 0; padding: 0; width: 5em; border: none; }
#positiontable td { width: 12px; height: 12px; border: 2px solid #FFF; background: #EEE; line-height: 0; overflow: hidden; cursor: pointer; }
#positiontable td.red{ background-color:red;}
.color_diy { width:310px; float:left; overflow:visible;}
.color_diy .span { float:left; display:inline; line-height:24px;}
.colorwd { margin-left: 2px; width: 26px; height: 26px; border: 1px solid; border-color: #DDD #999 #999 #DDD; vertical-align: middle; }
#controlcontent .diy li a { width: 60px; height: 60px; }
#controlcontent .diy li a:hover { background: none; }
#controlcontent li.thumb img { margin: 0; width: 60px; height: 60px; }
#diyimg_prev div *, #diyimg_next div * { display: none; }
dl.diy .pg { float: left; }
#diyimg_prev .prev, #diyimg_next .nxt { display: block; padding: 0 !important; width: 20px; height: 60px; border: 1px solid #EEE; text-indent: -9999px; }
#diyimg_next .prev { background: url(<?=IMGDIR?>/arw_l.gif) no-repeat 50% 50%; }
#diyimg_next .nxt { background: url(<?=IMGDIR?>/arw_r.gif) no-repeat 50% 50%; }

/* 原CSS */
.hide { display: none; }
#outerbox { width: 970px; margin: auto; }
.float_left { margin: 0 0 0 34px;}
.float_center { margin: auto; }
#header,#wrap,#footer, .wrap { margin: 0 auto; }
#wrap { min-height:320px;}
#header {margin-top:34px; position:relative; height:150px; text-align:center; line-height:100px; z-index:10;}
#footer { height:100px; line-height:100px; width: 970px; border:0;}
#widgets { position:relative; z-index:20; overflow:visible;}
.float_item { position:absolute; z-index:25;}
#nav { border:1px solid #09f; min-height:30px; line-height:28px;}
#nav ul li { float:left; margin:1px 2px;}
.op {position:absolute; right:10px; bottom:10px;}
.op a {display:inline; margin-left:2px; line-height:22px;}
#space_page { width: 100%; overflow: hidden; }
#left { float: left; margin: 0 15px 0 0; width: 190px; overflow: hidden; border:0px solid #09f;}
#center { float: left; margin: 0 15px 0 0; width: 560px; border:0px solid #ccc;}
#right { float: left; width: 180px; border:0px solid #f9f;}
.frame,.tab,.block {position:relative; zoom:1; min-height:20px;}
.move-span {margin:1px;}
.area { margin-bottom: 5px; padding: 3px; background: #DDF0DF; }
body#space .area { padding: 10px 0; border: none; background: transparent; }
.frame,.frame-tab { margin: 5px 0; padding-top: 5px; border: 1px dashed #09F; background: #FFF; }
#space .frame{background-color: transparent; }
.frame-tab { border-color: #FF8500; }
.frame-tab .title .titletext { float: left; margin: 0 2px;}
body#space .frame, body#space .frame-tab { padding: 0; }
.frame-tab .title ul li {cursor:pointer;}
.frame-tab .tabcontent { width:98%; border:1px solid #09f; margin:3px; float:left;}
.tabactivity { background:#ffffff;}
/*.column,.titletext { float:left;}*/
.title .tmpbox { float: left; }

.frame-1-1, .frame-1-1-1, .frame-1-2, .frame-2-1, .frame-1-3, .frame-3-1 { background-image: url(<?=STATICURL?>image/diy/vline.png); background-repeat: repeat-y; }
.frame-1-1, .frame-1-1-1 { background-position: 50% 0; }
.frame-1-1-1 { background-image: url(<?=STATICURL?>image/diy/vline2.png); }
.frame-1-2 { background-position: 33.3% 0; }
.frame-2-1 { background-position: 66.6% 0; }
.frame-1-3 { background-position: 24.9% 0; }
.frame-3-1 { background-position: 74.9% 0; }

.block { margin: 0 0 5px 0; padding: 5px; border: 1px solid #CCC; background: #FFF; }
#space .block{background-color: transparent; }
.block .blocktitle { height: 26px; line-height: 26px; }
.block .blockname { font-weight:bold; margin-left:5px; float:left;}
.block .blockedit { margin-right:5px; float:right; text-decoration:underline; cursor: pointer;}
.block .content { overflow: hidden; }
body#space .block { margin: 0 2px 5px; border: 1px dashed #09F; }

.edit { position: absolute; top: 0; right: 0; z-index: 199; padding: 0 5px; background: red; line-height: 26px; color: #FFF; cursor: pointer; }
.block .edit { background: #369; }
.edit-menu { position: absolute; border-style: solid; border-width: 0 1px 1px 1px; border-color: #DDD #999 #999 #CCC; background: #FFF; }
.mitem { padding: 4px 4px 4px 14px; width: 36px; border-top: 1px solid #DDD; cursor: pointer; }
.mitem:hover { background: #F2F2F2; color: #06C; }

.title { background: #CBFAFD; }
.subtitle { margin: 0 4px; }
.tab-title { background: #FFFAD2 !important; }
.frame-tab .title .move-span { float: left; margin: 0 3px 0 0; padding: 0; width: 100px; border-bottom: none; cursor: pointer; }
.frame-tab .title .move-span .blocktitle { background: #FFFAD2; }

.moving {background:#4ef; position:absolute;}
.tmpbox { margin:4px 0; border:1px dashed #aaa; background:#eee;}
.temp {height:0;border:0;}
.hide {display:none}
.left {float:left;}
.right {float:right;}
.pointer { cursor:pointer;}
.m_left_40 {margin-left:40px;}

.current {background:#fff;}
.activity {background:#fff;}
#currentlayout { color:#f04;}
.td_right { float:left; display:block; line-height:22px;}
.rb {border:2px solid #ff0000;}
.ss em { background: #eee; width: 16px; line-height: 18px; display: block; float: left; margin: 2px; cursor: pointer; padding-left: 7px;}
.ss em.a { color: #09f;}
#contentframe li a, #contentblockclass li label { cursor: move; }
/***原CSS***/
#diy-tg { display: none !important; }
/**space**/
#controlcontent .selector li a { margin: 0 10px 5px 0; padding-left: 30px; width: 60px; height: 30px; line-height: 30px; background: url(<?=STATICURL?>image/diy/cp-item-mini.png) no-repeat 0 0; text-align: left; cursor: pointer; }
#controlcontent .selector li a:hover { background-position: 0 -30px; }
#controlcontent .selector li.activity a { background-position: 0 100%; }

#borderul label { margin-right: 3px; }
.borderul .bordera, .borderul label { display: none; }
.borderul label.y { display: inline; }
</style><? } ?>
</head>

<body id="nv_<? if($_G['basescript'] == 'forum' && !empty($_G['forum']) && $_G['forum']['status'] == 3) { ?>group<? } else { ?><?=$_G['basescript']?><? } ?>" class="pg_<?=CURMODULE?>" onkeydown="if(event.keyCode==27) return false;">
<? if(($_G['mod']!='topic' && $_G['group']['allowdiy'] && !empty($_G['style']['tplfile'])) || (!empty($_G['style']['tplfile']) && $_G['mod']=='topic' && (($_G['group']['allowaddtopic'] && $topic['uid']==$_G['uid']) || $_G['group']['allowmanagetopic']))) { ?>
<a id="diy-tg" href="javascript:openDiy();" title="打开 DIY 面板"><img src="<?=STATICURL?>image/diy/panel-toggle.png" alt="DIY" /></a>
<? } ?>
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<? if($_GET['diy'] == 'yes' && ($_G['mod'] == 'topic' || $_G['group']['allowdiy']) && !empty($_G['style']['tplfile'])) { include template('common/header_diy'); } if(empty($topic) || ($topic['useheader'])) { ?><?php echo adshow("headerbanner/wp a_h"); ?><div id="hd">
<div class="wp">
<div class="hdc cl">
<h2><a href="index.php" title="<?=$_G['setting']['bbname']?>"><?=BOARDLOGO?></a></h2>
<? if($_G['setting']['search']) { ?><?php $slist = array(); if($_G['setting']['search']['portal']['status'] && ($_G['group']['allowsearch'] & 1 || $_G['adminid'] == 1)) { ?><?
$slist[portal] = <<<EOF
<li><a href="javascript:;"><label for="mod_article" onclick="$('sctype').innerHTML = '文章';"><input type="radio" id="mod_article" class="pr" name="mod" value="portal"
EOF;
 if(CURSCRIPT == 'portal') { 
$slist[portal] .= <<<EOF
 checked="checked"
EOF;
 } 
$slist[portal] .= <<<EOF
 />文章</label></a></li>
EOF;
?><? } if($_G['setting']['search']['forum']['status'] && ($_G['group']['allowsearch'] & 2 || $_G['adminid'] == 1)) { ?><?
$slist[forum] = <<<EOF
<li><a href="javascript:;"><label for="mod_thread" onclick="$('sctype').innerHTML = '{$_G['setting']['navs']['2']['navname']}';"><input type="radio" id="mod_thread" class="pr" name="mod" value="forum"
EOF;
 if(CURSCRIPT == 'forum') { 
$slist[forum] .= <<<EOF
 checked="checked"
EOF;
 } 
$slist[forum] .= <<<EOF
 />{$_G['setting']['navs']['2']['navname']}</label></a></li>
EOF;
?><? } if($_G['setting']['search']['blog']['status'] && ($_G['group']['allowsearch'] & 4 || $_G['adminid'] == 1)) { ?><?
$slist[blog] = <<<EOF
<li><a href="javascript:;"><label for="mod_blog" onclick="$('sctype').innerHTML = '日志';"><input type="radio" id="mod_blog" class="pr" name="mod" value="blog"
EOF;
 if(CURSCRIPT == 'home' && $do != 'album') { 
$slist[blog] .= <<<EOF
 checked="checked"
EOF;
 } 
$slist[blog] .= <<<EOF
 />日志</label></a></li>
EOF;
?><? } if($_G['setting']['search']['album']['status'] && ($_G['group']['allowsearch'] & 8 || $_G['adminid'] == 1)) { ?><?
$slist[album] = <<<EOF
<li><a href="javascript:;"><label for="mod_album" onclick="$('sctype').innerHTML = '相册';"><input type="radio" id="mod_album" class="pr" name="mod" value="album"
EOF;
 if(CURSCRIPT == 'home' && $do == 'album') { 
$slist[album] .= <<<EOF
 checked="checked"
EOF;
 } 
$slist[album] .= <<<EOF
 /> 相册</label></a></li>
EOF;
?><? } if($_G['setting']['groupstatus'] && $_G['setting']['search']['group']['status'] && ($_G['group']['allowsearch'] & 16 || $_G['adminid'] == 1)) { ?><?
$slist[group] = <<<EOF
<li><a href="javascript:;"><label for="mod_group" onclick="$('sctype').innerHTML = '{$_G['setting']['navs']['3']['navname']}';"><input type="radio" id="mod_group" class="pr" name="mod" value="group"
EOF;
 if(CURSCRIPT == 'group') { 
$slist[group] .= <<<EOF
 checked="checked"
EOF;
 } 
$slist[group] .= <<<EOF
 />{$_G['setting']['navs']['3']['navname']}</label></a></li>
EOF;
?><? } if($slist) { ?>
<form id="sc" class="y" method="post" autocomplete="off" action="search.php?searchsubmit=yes" target="_blank">
<input name="mod" value="search" type="hidden" />
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input name="srchtype" value="title" type="hidden" />
<label id="sctype" onmouseover="showMenu(this.id)" class="z">搜索</label>
<input type="text" name="srchtxt" id="srchtxt" class="px z" value="请输入搜索内容" prompt="search_kw" autocomplete="off" onfocus="searchFocus(this);" onblur="searchBlur(this);" />
<button id="searchsubmit" name="searchsubmit" prompt="search_submit" type="submit" value="true" class="y">搜索</button>
<div id="sctype_menu" class="p_pop cl" style="display: none">
<ul><? echo implode('', $slist);; ?></ul>
</div>
</form>
<? } } ?>
<div id="nv">
<ul><? if(is_array($_G['setting']['navs'])) foreach($_G['setting']['navs'] as $navsid => $nav) { if($nav['available']) { if($navsid == 6 && !empty($_G['setting']['plugins']['jsmenu'])) { ?>
<?=$nav['nav']?>
<? } else { if(!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1)) { ?><?=$nav['nav']?><? } } } } ?>
</ul>
</div>
<? if(!empty($_G['setting']['plugins']['jsmenu'])) { ?>
<ul class="p_pop h_pop" id="plugin_menu" style="display: none"><? if(is_array($_G['setting']['plugins']['jsmenu'])) foreach($_G['setting']['plugins']['jsmenu'] as $module) { ?>     <? if(!$module['adminid'] || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])) { ?>
     <li><?=$module['url']?></li>
     <? } } ?>
</ul>
<? } if(is_array($_G['setting']['subnavs'])) foreach($_G['setting']['subnavs'] as $subnav) { ?><?=$subnav?>
<? } ?>
</div>

<div id="mu">
<? if($_G['setting']['snavs'][$_G['basescript']]) { ?>
<ul class="cl"><? if(is_array($_G['setting']['snavs'][$_G['basescript']])) foreach($_G['setting']['snavs'][$_G['basescript']] as $item) { ?><li><?=$item?></li>
<? } ?>
</ul>
<? } ?>
</div><?php echo adshow("subnavbanner/a_mu"); ?></div>
</div>

<div id="um" class="wp">
<p>
<? if($_G['uid']) { ?>
<strong><a href="home.php?mod=space" class="noborder" target="_blank"><?=$_G['member']['username']?></a></strong>
<? if($_G['group']['allowinvisible']) { ?><span id="loginstatus" class="xg1"><a href="member.php?mod=switchstatus" title="切换在线状态" onclick="ajaxget(this.href, 'loginstatus');doane(event);"><? if($_G['session']['invisible']) { ?>隐身<? } else { ?>在线<? } ?></a></span><? } ?>
<span class="pipe">|</span><a href="home.php?mod=space&amp;do=home">我的中心</a>
<span class="xg1"><a href="home.php?mod=spacecp">设置</a></span>

<span class="pipe">|</span><a href="home.php?mod=space&amp;do=notice" id="myprompt" <? if($_G['member']['newprompt']) { ?>class="new"<? } ?>>提醒<? if($_G['member']['newprompt']) { ?>(<?=$_G['member']['newprompt']?>)<? } ?></a><span id="myprompt_check"></span>
<span class="pipe">|</span><a href="home.php?mod=space&amp;do=pm" id="pm_ntc"<? if($_G['member']['newpm']) { ?>class="new"<? } ?>>短消息<? if($_G['member']['newpm']) { ?>(<?=$_G['member']['newpm']?>)<? } ?></a>

<? if($_G['group']['allowmanagearticle'] || $_G['group']['allowdiy']) { ?><span class="pipe">|</span><a href="portal.php?mod=portalcp">门户管理</a><? } if($_G['uid'] && $_G['adminid'] > 1) { ?><span class="pipe">|</span><a href="forum.php?mod=modcp&amp;fid=<?=$_G['fid']?>" target="_blank"><?=$_G['setting']['navs']['2']['navname']?>管理</a><? } if($_G['uid'] && ($_G['adminid'] == 1 || $_G['member']['allowadmincp'])) { ?><span class="pipe">|</span><a href="admin.php" target="_blank">管理中心</a><? } ?>
<span class="pipe">|</span><a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?=FORMHASH?>">退出</a>
<? } elseif(!empty($_G['cookie']['loginuser'])) { ?>
<strong><a id="loginuser" class="noborder"><?=$_G['cookie']['loginuser']?></a></strong>
<span class="pipe">|</span><a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href);hideWindow('register');">激活</a>
<span class="pipe">|</span><a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?=FORMHASH?>">退出</a>
<? } else { ?>
<a href="member.php?mod=register" onclick="showWindow('register', this.href);hideWindow('login');" class="noborder"><?=$_G['setting']['reglinkname']?></a>
<span class="pipe">|</span><a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href);hideWindow('register');">登录</a>
<? } ?>
</p>
</div>

<?php if(!empty($_G['setting']['pluginhooks']['global_header'])) echo $_G['setting']['pluginhooks']['global_header']; } ?>

<div id="wp" class="wp"><div id="pt" class="wp">
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo;
<a href="<?=$_G['setting']['navs']['4']['filename']?>"><?=$_G['setting']['navs']['4']['navname']?></a>
</div>
<style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div>
<div id="ct" class="wp cl">
<div class="mn">
<div class="ch">
<label class="wx"><a href="<?=$_G['setting']['navs']['4']['filename']?>"><?=$_G['setting']['navs']['4']['navname']?></a></label>
<?php if(!empty($_G['setting']['pluginhooks']['space_home_header'])) echo $_G['setting']['pluginhooks']['space_home_header']; ?>
</div>

<? if($space['uid'] && $space['self']) { ?>
<div class="mi">
<table cellpadding="0" cellspacing="0">
<tr>
<th class="hm">
<div class="avt"><?php echo avatar($_G[uid],middle); ?></div>
<p class="mbm">
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>" target="_blank" class="o">访问我的空间</a>
<a href="home.php?mod=spacecp" id="usettingli" class="xg1" onmouseover="showMenu(this.id)">设置</a>
</p>
<ul id="usettingli_menu" class="p_pop" style="width: 80px; display:none;">
<li><a href="home.php?mod=spacecp&amp;ac=avatar">编辑头像</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=profile">个人资料</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=credit">我的积分</a></li>													
<li><a href="home.php?mod=spacecp&amp;ac=privacy">隐私筛选</a></li>
<? if($_G['setting']['sendmailday']) { ?>
<li><a href="home.php?mod=spacecp&amp;ac=sendmail">邮件提醒</a></li>
<? } ?>	
<? if(checkperm('allowstat')) { ?>
<li><a href="home.php?mod=misc&amp;ac=stat">趋势统计</a></li>
<? } ?>
</ul>
</th>
<td>
<h3 class="xs2">
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>"<?php g_color($space[groupid]); ?>><?=$space['username']?></a><?php g_icon($space[groupid]); ?></h3>
<p class="mtn xg1 cl"><?php $space[domainurl] = space_domain($space); ?>已有 <a href="home.php?mod=spacecp&amp;ac=credit"><?=$space['credits']?></a> 个积分,用户组:<a href="home.php?mod=spacecp&amp;ac=credit&amp;op=usergroup"><?php g_name($space[groupid]); ?></a>
</p><? include template('home/space_status'); ?></td>
</tr>
</table>
<?php if(!empty($_G['setting']['pluginhooks']['space_home_top'])) echo $_G['setting']['pluginhooks']['space_home_top']; if($space['newprompt']) { ?>
<ul class="mipm cl">
<? if($space['notifications']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/notice.gif" alt="notice" class="vm" /><a href="home.php?mod=space&amp;do=notice"><strong><?=$space['notifications']?></strong> 条新通知</a></li><? } if($space['pendingfriends']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/friend.gif" alt="friend" class="vm" /><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=request"><strong><?=$space['pendingfriends']?></strong> 个好友请求</a></li><? } if($space['groupinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/mtag.gif" alt="mtag" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;type=group"><strong><?=$space['groupinvitations']?></strong> 个群组邀请</a></li><? } if($space['activityinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/event.gif" alt="event" class="vm" /><a href="home.php?mod=spacecp&amp;ac=event&amp;op=eventinvite"><strong><?=$space['activityinvitations']?></strong> 个活动邀请</a></li><? } if($space['myinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/userapp.gif" alt="userapp" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;view=userapp"><strong><?=$space['myinvitations']?></strong> 个应用消息</a></li><? } if($space['pokes']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/poke.gif" alt="poke" class="vm" /><a href="home.php?mod=spacecp&amp;ac=poke"><strong><?=$space['pokes']?></strong> 个新招呼</a></li><? } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_home_promptlink'])) echo $_G['setting']['pluginhooks']['space_home_promptlink']; ?>
</ul>
<? } ?>
</div><?php echo adshow("feed/bm"); ?><div class="bm">
<ul class="tb cl">
<li<?=$actives['we']?>><a href="home.php?mod=space&amp;do=home&amp;view=we">好友的动态</a></li>
<li<?=$actives['me']?>><a href="home.php?mod=space&amp;do=home&amp;view=me">我的动态</a></li>
<li<?=$actives['all']?>><a href="home.php?mod=space&amp;do=home&amp;view=all">随便看看</a></li>
<?php if(!empty($_G['setting']['pluginhooks']['space_home_navlink'])) echo $_G['setting']['pluginhooks']['space_home_navlink']; if($_G['setting']['magics']['thunder']) { ?>
<li class="y"><a id="a_magic_thunder" href="home.php?mod=magic&amp;mid=thunder" onclick="showWindow('magics', this.href, 'get', 0)"><img src="<?=STATICURL?>image/magic/thunder.small.gif" alt="<?=$_G['setting']['magics']['thunder']?>" class="vm" /> <?=$_G['setting']['magics']['thunder']?></a></li>
<? } ?>
</ul>

<? } else { ?>
<div class="bm"><?php $_G['home_tpl_spacemenus'][] = "<a href=\"home.php?mod=space&amp;uid=$space[uid]&amp;do=home&amp;view=me\">TA的近期动态</a>"; include template('home/space_menu'); } if($_GET['view'] == 'all') { ?>
<p class="tbmu">
<a href="home.php?mod=space&amp;do=home&amp;view=all&amp;order=dateline"<?=$orderactives['dateline']?>>最新动态</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=home&amp;view=all&amp;order=hot"<?=$orderactives['hot']?>>热点动态</a>
</p>
<? } elseif($groups) { ?>
<p class="tbmu">
<a href="home.php?mod=space&amp;do=home&amp;view=we"<?=$gidactives['-1']?>>全部好友</a><? if(is_array($groups)) foreach($groups as $key => $value) { ?><span class="pipe">|</span><a href="home.php?mod=space&amp;do=home&amp;view=we&amp;gid=<?=$key?>"<?=$gidactives[$key]?>><?=$value?></a>
<? } ?>
</p>
<? } } else { include template('home/space_header'); ?><div id="ct" class="wp n cl">
<div class="mn">
<div class="bm">
<h1 class="mt">动态</h1>
<? } ?>

<div id="feed_div" class="e">

<? if($hotlist) { ?>
<h4 class="et"><a href="home.php?mod=space&amp;do=home&amp;view=all&amp;order=hot" class="y xw0">查看更多热点 &rsaquo;</a>近期热点推荐</h4>
<ul class="el"><? if(is_array($hotlist)) foreach($hotlist as $value) { ?><?php $value = mkfeed($value); include template('home/space_feed_li'); } ?>
</ul>
<? } if($list) { if(is_array($list)) foreach($list as $day => $values) { if($_GET['view']!='hot') { ?>
<h4 class="et">
<? if($day=='yesterday') { ?>昨天<? } elseif($day=='today') { ?>今天<? } else { ?><?=$day?><? } ?>
</h4>
<? } ?>

<ul class="el"><? if(is_array($values)) foreach($values as $value) { include template('home/space_feed_li'); } ?>
</ul>
<? } } elseif($feed_users) { ?>
<div class="xld xlda mtm"><? if(is_array($feed_users)) foreach($feed_users as $day => $users) { ?><h4 class="et">
<? if($day=='yesterday') { ?>昨天<? } elseif($day=='today') { ?>今天<? } else { ?><?=$day?><? } ?>
</h4><? if(is_array($users)) foreach($users as $user) { ?><?php $daylist = $feed_list[$day][$user[uid]]; ?><?php $morelist = $more_list[$day][$user[uid]]; ?><dl class="bbda cl">
<dd class="m avt">
<? if($user['uid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$user['uid']?>" title="<?=$user['username']?>" target="_blank"><?php echo avatar($user[uid],small); ?></a>
<? } else { ?>
<img src="<?=IMGDIR?>/systempm.gif" />
<? } ?>
</dd>
<dd class="cl">
<ul class="el"><? if(is_array($daylist)) foreach($daylist as $value) { include template('home/space_feed_li'); } ?>
</ul>

<? if($morelist) { ?>
<p class="xg1 cl"><span onclick="showmore('<?=$day?>', '<?=$user['uid']?>', this);" class="unfold">展开</span></p>
<div id="feed_more_div_<?=$day?>_<?=$user['uid']?>" style="display:none;">
<ul class="el"><? if(is_array($morelist)) foreach($morelist as $value) { include template('home/space_feed_li'); } ?>
</ul>
</div>
<? } ?>
</dd>
</dl>
<? } } ?>
</div>
<? } else { ?>
<p class="emp">还没有相关动态</p>
<? } if($filtercount) { ?>
<div class="i" id="feed_filter_notice_<?=$start?>">
根据您的<a href="home.php?mod=spacecp&amp;ac=privacy&amp;op=filter">筛选设置</a>,有 <?=$filtercount?> 条动态被屏蔽 (<a href="javascript:;" onclick="filter_more(<?=$start?>);" id="a_feed_privacy_more">点击查看</a>)
</div>
<div id="feed_filter_div_<?=$start?>" style="display:none;">
<h4 class="et">以下是被屏蔽的动态</h4>
<ul class="el"><? if(is_array($filter_list)) foreach($filter_list as $value) { include template('home/space_feed_li'); } ?>
<li><a href="javascript:;" onclick="filter_more(<?=$start?>);">&laquo; 收起</a></li>
</ul>
</div>
<? } ?>

</div>
<!--/id=feed_div-->

<? if(empty($diymode)) { if($multi) { ?>
<div class="pgs cl"><?=$multi?></div>
<? } ?>

<?php if(!empty($_G['setting']['pluginhooks']['space_home_bottom'])) echo $_G['setting']['pluginhooks']['space_home_bottom']; ?>
<div id="ajax_wait"></div>
</div>
</div>
<!--/content-->

<div class="sd"><? include template('common/userabout'); if($defaultusers) { ?>
<div class="bn">
<h2>好友推荐</h2>
<ul class="ml mls cl"><? if(is_array($defaultusers)) foreach($defaultusers as $key => $value) { ?><li>
<a href="home.php?mod=space&amp;uid=<?=$value['uid']?>" title="<? if($ols[$value['uid']]) { ?>在线<? } ?>" class="avt">
<? if($ols[$value['uid']]) { ?><em class="gol"></em><? } ?><?php echo avatar($value[uid],small); ?></a>
<p><a href="home.php?mod=space&amp;uid=<?=$value['uid']?>" title="<?=$value['username']?>"><?=$value['username']?></a></p>
</li>
<? } ?>
</ul>
</div>
<hr class="da" />
<? } if($visitorlist) { ?>
<div class="bn">
<span class="y">
<? if($_G['setting']['magics']['visit']) { ?>
<span class="xg1"><img src="<?=STATICURL?>image/magic/visit.small.gif" title="<?=$_G['setting']['magics']['visit']?>" class="vm" /> <a id="a_magic_visit" href="home.php?mod=magic&amp;mid=visit" onclick="showWindow('magics',this.href,'get', 0)"><?=$_G['setting']['magics']['visit']?></a></span>
<? } ?>
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>&amp;do=friend&amp;view=visitor">全部</a>
</span>
<h2>最近来访</h2>
<ul class="ml mls cl"><? if(is_array($visitorlist)) foreach($visitorlist as $key => $value) { ?><li>
<? if($value['vusername'] == '') { ?>
<div class="avt"><img src="<?=STATICURL?>image/magic/hidden.gif" alt="匿名" /></div>
<p>匿名</p>
<span><?php echo dgmdate($value[dateline], 'u', 9999, $_G[setting][dateformat]); ?></span>
<? } else { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['vuid']?>" title="<? if($ols[$value['vuid']]) { ?>在线<? } ?>" class="avt">
<? if($ols[$value['vuid']]) { ?><em class="gol"></em><? } ?><?php echo avatar($value[vuid],small); ?></a>
<p><a href="home.php?mod=space&amp;uid=<?=$value['vuid']?>" title="<?=$value['vusername']?>"><?=$value['vusername']?></a></p>
<span><?php echo dgmdate($value[dateline], 'u', 9999, $_G[setting][dateformat]); ?></span>
<? } ?>
</li>
<? } ?>
</ul>
</div>
<hr class="da" />
<? } if($olfriendlist) { ?>
<div class="bn">
<p class="y">
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>&amp;do=friend">全部</a>
</p>
<h2>我的好友</h2>
<ul class="ml mls cl"><? if(is_array($olfriendlist)) foreach($olfriendlist as $key => $value) { ?><li>
<a href="home.php?mod=space&amp;uid=<?=$value['uid']?>" title="<? if($ols[$value['uid']]) { ?>在线<? } ?>" class="avt">
<? if($ols[$value['uid']]) { ?><em class="gol"></em><? } ?><?php echo avatar($value[uid],small); ?></a>
<p><a href="home.php?mod=space&amp;uid=<?=$value['uid']?>" title="<?=$value['username']?>"><?=$value['username']?></a></p>
<span><? if($value['lastactivity']) { ?><?php echo dgmdate($value[lastactivity], 'u', 9999, $_G[setting][dateformat]); } else { ?>热度(<?=$value['num']?>)<? } ?></span>
</li>
<? } ?>
</ul>
</div>
<? } ?>

<div class="drag">
<!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
</div>

<? if($birthlist) { ?>
<hr class="da" />
<div class="bn">
<h2>好友生日提醒</h2>
<table cellpadding="2" cellspacing="4"><? if(is_array($birthlist)) foreach($birthlist as $key => $values) { ?><tr>
<td align="right" valign="top">
<? if($values['0']['istoday']) { ?>今天<? } else { ?><?=$values['0']['birthmonth']?>-<?=$values['0']['birthday']?><? } ?>
</td>
<td style="padding-left:10px;">
<ul><? if(is_array($values)) foreach($values as $value) { ?><li><a href="home.php?mod=space&amp;uid=<?=$value['uid']?>"><?=$value['username']?></a></li>
<? } ?>
</ul>
</td>
</tr>
<? } ?>
</table>
</div>
<? } ?>

<?php if(!empty($_G['setting']['pluginhooks']['space_home_side_bottom'])) echo $_G['setting']['pluginhooks']['space_home_side_bottom']; ?>

</div>
<!--/sidebar-->
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div><?php my_checkupdate(); ?><?php my_showgift(); } else { if($count==$perpage) { ?>
<div class="pgs cl"><div class="pg"><a href="javascript:;" onclick="feed_more();" id="a_feed_more">查看更多动态</a></div></div>
<? } ?>
</div>
</div>
<div class="sd"><div id="pcd" class="bn cl">
<div class="avt avtm">
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>"><?php echo avatar($space[uid],middle); ?></a>
<h2 class="xs2"><?=$space['username']?></h2>
<ul class="xl xl2 cl">
<? if($space['self']) { ?>
<li><a href="home.php?mod=space&amp;diy=yes">装扮空间</a></li>
<li><a href="home.php?mod=space&amp;do=wall">查看留言</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=avatar">编辑头像</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=profile">更新资料</a></li>
<? } else { ?><?php require_once libfile('function/friend');$isfriend=friend_check($space[uid]); if(!$isfriend) { ?>
<li><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?=$space['uid']?>&amp;handlekey=addfriendhk_<?=$space['uid']?>" id="a_friend_li_<?=$space['uid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">加为好友</a></li>
<? } else { ?>
<li><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?=$space['uid']?>&amp;handlekey=ignorefriendhk_<?=$space['uid']?>" id="a_ignore_<?=$space['uid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">解除好友</a></li>
<? } ?>
<li><a href="home.php?mod=space&amp;uid=<?=$space['uid']?>&amp;do=wall">给我留言</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?=$space['uid']?>&amp;handlekey=propokehk_<?=$space['uid']?>" id="a_poke_<?=$space['uid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">打招呼</a></li>
<li><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?=$space['uid']?>&amp;touid=<?=$space['uid']?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?=$space['uid']?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)">发送消息</a></li>
<? } ?>
</ul>
</div>
</div></div>
</div>
<? } ?>



<script type="text/javascript">

function filter_more(id) {
if($('feed_filter_div_'+id).style.display == '') {
$('feed_filter_div_'+id).style.display = 'none';
$('feed_filter_notice_'+id).style.display = '';
} else {
$('feed_filter_div_'+id).style.display = '';
$('feed_filter_notice_'+id).style.display = 'none';
}
}

function close_feedbox() {
var x = new Ajax();
x.get('home.php?mod=spacecp&ac=common&op=closefeedbox', function(s){
$('feed_box').style.display = 'none';
});
}

function showmore(day, uid, e) {
var obj = 'feed_more_div_'+day+'_'+uid;
$(obj).style.display = $(obj).style.display == ''?'none':'';
if(e.className == 'unfold'){
e.innerHTML = '收起';
e.className = 'fold';
} else if(e.className == 'fold') {
e.innerHTML = '展开';
e.className = 'unfold';
}
}

var elems = selector('li[class~=magicthunder]', $('feed_div')); 
for(var i=0; i<elems.length; i++){
magicColor(elems[i]); 
}
</script>	</div>
<? if(empty($topic) || ($topic['usefooter'])) { ?><?php $focusid = getfocus_rand($_G[basescript]); if($focusid !== null) { ?><?php $focus = $_G['cache']['focus']['data'][$focusid]; ?><div class="focus" id="focus">
<h3 class="flb">
<em><? if($_G['cache']['focus']['title']) { ?><?=$_G['cache']['focus']['title']?><? } else { ?>站长推荐<? } ?></em>
<span><a href="javascript:;" onclick="setcookie('nofocus_<?=$focusid?>', 1, 86400);$('focus').style.display='none'" class="flbc" title="关闭">关闭</a></span>
</h3>
<hr class="l" />
<div class="detail">
<h4><a href="<?=$focus['url']?>" target="_blank"><?=$focus['subject']?></a></h4>
<p>
<? if($focus['image']) { ?>
<a href="<?=$focus['url']?>" target="_blank"><img src="<?=$focus['image']?>" onload="thumbImg(this, 1)" _width="58" _height="58" /></a>
<? } ?>
<?=$focus['summary']?>
</p>
</div>
<hr class="l" />
<a href="<?=$focus['url']?>" class="moreinfo" target="_blank">查看</a>
</div>
<? } ?><?php echo adshow("footerbanner/wp a_f/1"); ?><?php echo adshow("footerbanner/wp a_f/2"); ?><?php echo adshow("footerbanner/wp a_f/3"); ?><?php echo adshow("float/a_fl/1"); ?><?php echo adshow("float/a_fr/2"); ?><?php echo adshow("couplebanner/a_fl a_cb/1"); ?><?php echo adshow("couplebanner/a_fr a_cb/2"); ?><?php if(!empty($_G['setting']['pluginhooks']['global_footer'])) echo $_G['setting']['pluginhooks']['global_footer']; ?>
<div id="ft" class="wp cl">
<div id="flk" class="y">
<p>
<strong><a href="<?=$_G['setting']['siteurl']?>" target="_blank"><?=$_G['setting']['sitename']?></a></strong>
<? if($_G['setting']['icp']) { ?>( <a href="http://www.miibeian.gov.cn/" target="_blank"><?=$_G['setting']['icp']?></a>)<? } if($_G['setting']['adminemail']) { ?><span class="pipe">|</span><a href="mailto:<?=$_G['setting']['adminemail']?>">联系我们</a><? } if($_G['uid']) { ?><span class="pipe">|</span><a href="javascript:;" onclick="showWindow('miscreport', 'misc.php?mod=report&url=<?=$_G['currenturl_encode']?>');return false;">举报当前页面</a><? } if($_G['setting']['statcode']) { ?><?=$_G['setting']['statcode']?><? } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_footerlink'])) echo $_G['setting']['pluginhooks']['global_footerlink']; ?>
</p>
<p class="xs0">
GMT<?=$_G['timenow']['offset']?>, <?=$_G['timenow']['time']?>
<? if(debuginfo()) { ?>, <span id="debuginfo">Processed in <?=$_G['debuginfo']['time']?> second(s), <?=$_G['debuginfo']['queries']?> queries<? if($_G['gzipcompress']) { ?>, Gzip On<? } if($_G['memory']) { ?>, <? echo ucwords($_G['memory']); ?> On<? } ?></span><? } ?>.
</p>
</div>
<div id="frt">
<p>Powered by <strong><a href="http://www.discuz.net" target="_blank">Discuz!</a></strong> <em><?=$_G['setting']['version']?></em><? if(!empty($_G['setting']['boardlicensed'])) { ?> <a href="http://license.comsenz.com/?pid=1&amp;host=<?=$_SERVER['HTTP_HOST']?>" target="_blank">Licensed</a><? } ?></p>
<p class="xs0">&copy; 2001-2010 <a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a></p>
</div><?php updatesession(); ?></div>
<? } if($_G['uid'] && !isset($_G['cookie']['checkpm'])) { ?>
<script src="home.php?mod=spacecp&ac=pm&op=checknewpm&rand=<?=$_G['timestamp']?>" type="text/javascript"></script>
<? } if(!isset($_G['cookie']['sendmail'])) { ?>
<script src="home.php?mod=misc&ac=sendmail&rand=<?=$_G['timestamp']?>" type="text/javascript"></script>
<? } if($_GET['diy'] == 'yes' && ($_G['mod'] == 'topic' || $_G['group']['allowdiy']) && (empty($do) || $do != 'index') && !empty($_G['style']['tplfile'])) { ?>
<script src="<?=$_G['setting']['jspath']?>common_diy.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>portal_diy.js?<?=VERHASH?>" type="text/javascript"></script>
<? } if($_GET['diy'] == 'yes' && $space['self'] && $_G['mod'] == 'space' && $do == 'index') { ?>
<script src="<?=$_G['setting']['jspath']?>common_diy.js?<?=VERHASH?>" type="text/javascript"></script>
<script src="<?=$_G['setting']['jspath']?>space_diy.js?<?=VERHASH?>" type="text/javascript"></script>
<? } ?><?php output(); ?></body>
</html>