<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('discuz');
0
|| checktplrefresh('./template/default/forum/discuz.htm', './template/default/common/header.htm', 1380628630, 'diy', './data/template/1_diy_forum_discuz.tpl.php', './template/default', 'forum/discuz')
|| checktplrefresh('./template/default/forum/discuz.htm', './template/default/common/userabout.htm', 1380628630, 'diy', './data/template/1_diy_forum_discuz.tpl.php', './template/default', 'forum/discuz')
|| checktplrefresh('./template/default/forum/discuz.htm', './template/default/common/footer.htm', 1380628630, 'diy', './data/template/1_diy_forum_discuz.tpl.php', './template/default', 'forum/discuz')
|| checktplrefresh('./template/default/forum/discuz.htm', './template/default/common/css_diy.htm', 1380628630, 'diy', './data/template/1_diy_forum_discuz.tpl.php', './template/default', 'forum/discuz')
;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<? if(defined('CURMODULE') && ($_G['basescript'] == 'forum' || $_G['basescript'] == 'group') && (CURMODULE == 'index' || CURMODULE == 'forumdisplay' || CURMODULE == 'group')) { ?><?=$rsshead?><? } ?><link rel="stylesheet" type="text/css" href="data/cache/style_<?=STYLEID?>_common.css?<?=VERHASH?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?=STYLEID?>_forum_index.css?<?=VERHASH?>" /><script type="text/javascript">var STYLEID = '<?=STYLEID?>', STATICURL = '<?=STATICURL?>', IMGDIR = '<?=IMGDIR?>', VERHASH = '<?=VERHASH?>', charset = '<?=CHARSET?>', discuz_uid = '<?=$_G['uid']?>', cookiepre = '<?=$_G['config']['cookie']['cookiepre']?>', cookiedomain = '<?=$_G['config']['cookie']['cookiedomain']?>', cookiepath = '<?=$_G['config']['cookie']['cookiepath']?>', attackevasive = '<?=$_G['config']['security']['attackevasive']?>', disallowfloat = '<?=$_G['setting']['disallowfloat']?>', creditnotice = '<? if($_G['setting']['creditnotice']) { ?><?=$_G['setting']['creditnames']?><? } ?>'</script>
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
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo; <?=$_G['setting']['navs']['2']['navname']?>
</div><?php echo adshow("text/wp a_t"); ?><style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div>

<div id="ct" class="wp cl<? if(!$_G['setting']['forumallowside']) { ?> w<? } ?>">

<div class="mn<? if(!$_G['setting']['forumallowside']) { ?> mw<? } ?>">
<div class="content">

<div class="ch">
<label class="wx"><a href="<?=$_G['setting']['navs']['2']['filename']?>"><?=$_G['setting']['navs']['2']['navname']?></a></label>
<?php if(!empty($_G['setting']['pluginhooks']['index_header'])) echo $_G['setting']['pluginhooks']['index_header']; ?>
</div>

<div class="bm cl mtn">
<div class="ptn pbm bbda cl">
<? if(!$_G['setting']['forumallowside']) { ?><p class="y">今日: <em><?=$todayposts?></em>, 帖子: <em><?=$posts?></em>, 会员: <em><?=$_G['cache']['userstats']['totalmembers']?></em></p><? } ?>
<button type="button" class="pn pnc z vm" onclick="showWindow('nav', 'forum.php?mod=misc&action=nav&special=0')" style="margin: -6px 10px 0 0;"><strong>发帖</strong></button>
<? if(!$_G['uid']) { ?>
<p><p>你可以<a href="member.php?mod=register" onclick="showWindow('register', this.href);return false;">注册</a>一个帐号，并以此<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href);return false;">登录</a>，以浏览更多精彩内容，并随时发布观点，与大家交流。</p></p>
<? } elseif(!empty($_G['setting']['search']['forum']['status'])) { ?>
<p>欢迎回来 <strong><?=$_G['member']['username']?></strong>, <? if($lastvisit > 0) { ?>你上次访问时间是在 <?=$lastvisit?>,<? } ?> <a href="home.php?mod=space&amp;uid=<?=$_G['uid']?>&amp;do=thread&amp;view=me" title="我的帖子" class="xi2">我的帖子</a>, <a href="search.php?mod=forum&amp;srchfrom=<?=$newthreads?>&amp;searchsubmit=yes" title="查看新帖" class="xi2">查看新帖</a></p>
<? } ?>
</div>
<? if(empty($gid) && $announcements) { ?>
<div id="an">
<dl class="mtm cl">
<dt>公告:</dt>
<dd>
<div id="anc"><ul id="ancl"><?=$announcements?></ul></div>
</dd>
</dl>
</div>
<script type="text/javascript">announcement();</script>
<? } ?>
</div>

<?php if(!empty($_G['setting']['pluginhooks']['index_top'])) echo $_G['setting']['pluginhooks']['index_top']; if(is_array($catlist)) foreach($catlist as $key => $cat) { ?><div class="fl<? if($cat['forumcolumns']) { ?> flg<? } ?> cl">
<div class="h cl">
<? if($cat['moderators']) { ?><span class="d">分区版主: <?=$cat['moderators']?></span><? } ?>
<span class="o">
<img id="category_<?=$cat['fid']?>_img" src="<?=IMGDIR?>/fl_<?=$cat['collapseimg']?>" title="收起/展开" alt="收起/展开" onclick="toggle_collapse('category_<?=$cat['fid']?>');" />
</span>
<h2><a href="forum.php?gid=<?=$cat['fid']?>" style="<? if($cat['extra']['namecolor']) { ?>color: <?=$cat['extra']['namecolor']?>;<? } ?>"><?=$cat['name']?></a></h2>
</div>
<div id="category_<?=$cat['fid']?>" class="c cl" style="<?=$collapse['category_'.$cat['fid']]?>"><? if(is_array($cat['forums'])) foreach($cat['forums'] as $forumid) { ?><?php $forum=$forumlist[$forumid]; if(!$cat['forumcolumns'] || $forum['orderid'] % $cat['forumcolumns'] == 0) { ?>
<hr class="da" <? if($forum['orderid'] == 0 ) { ?>style="display: none;"<? } ?> />
<? } ?>
<div id="forum<?=$forum['fid']?>" class="flc cl"<? if($cat['forumcolumns']) { ?> style="width: <?=$cat['forumcolwidth']?>"<? } ?>>
<div class="icn"<? if(!empty($forum['extra']['iconwidth']) && !empty($forum['icon'])) { ?> style="width: <?=$forum['extra']['iconwidth']?>px;"<? } ?>>
<? if($forum['icon']) { ?>
<?=$forum['icon']?>
<? } else { ?>
<img src="<?=IMGDIR?>/forum<? if($forum['folder']) { ?>_new<? } ?>.gif" alt="" />
<? } ?>
</div>
<dl<? if(!empty($forum['extra']['iconwidth']) && !empty($forum['icon'])) { ?> style="margin-left: <? echo $forum['extra']['iconwidth'] + 10; ?>px;"<? } ?>>
<dt><a href="forum.php?mod=forumdisplay&amp;fid=<?=$forum['fid']?>"<? if($forum['redirect']) { ?> target="_blank"<? } if($forum['extra']['namecolor']) { ?> style="color: <?=$forum['extra']['namecolor']?>;"<? } ?>><?=$forum['name']?></a><? if($forum['todayposts'] && !$forum['redirect']) { ?><em> (<strong><?=$forum['todayposts']?></strong>)</em><? } ?></dt>
<? if(!$forum['redirect']) { ?>
<dd class="i"><em>主题: <?=$forum['threads']?></em><em>帖数: <?=$forum['posts']?></em></dd>
<? } if($forum['description'] && !$cat['forumcolumns']) { ?><dd class="xg2"><?=$forum['description']?></dd><? } if($forum['subforums'] && $cat['forumcolumns'] <= 1) { ?><dd class="sfl">子版块: <?=$forum['subforums']?></dd><? } ?>
<dd>
<? if($forum['permission'] == 1) { ?>
私密版块
<? } else { if($forum['redirect']) { ?>
<a href="forum.php?mod=forumdisplay&amp;fid=<?=$forum['fid']?>" class="xi2">链接到外部地址</a>
<? } elseif(is_array($forum['lastpost'])) { if($cat['forumcolumns'] < 3) { ?>
<a href="forum.php?mod=redirect&amp;tid=<?=$forum['lastpost']['tid']?>&amp;goto=lastpost#lastpost" class="xi2"><? echo cutstr($forum['lastpost']['subject'], 30); ?></a> <?=$forum['lastpost']['dateline']?> <? if($forum['lastpost']['author']) { ?><?=$forum['lastpost']['author']?><? } else { ?>匿名<? } } else { ?>
<a href="forum.php?mod=redirect&amp;tid=<?=$forum['lastpost']['tid']?>&amp;goto=lastpost#lastpost">最后发表: <?=$forum['lastpost']['dateline']?></a>
<? } } else { ?>
从未
<? } } ?>
</dd>
</dl>
</div>
<? } ?>
</div>
</div><?php echo adshow("intercat/bm a_c/$cat[fid]"); } ?>

<?php if(!empty($_G['setting']['pluginhooks']['index_middle'])) echo $_G['setting']['pluginhooks']['index_middle']; if(empty($gid) && $_G['setting']['whosonlinestatus']) { ?>
<div id="online" class="bm oll">
<? if($_G['setting']['whosonlinestatus']) { if($detailstatus) { ?>
<span class="o"><a href="forum.php?showoldetails=no#online" title="关闭"><img src="<?=IMGDIR?>/collapsed_no.gif" alt="关闭" /></a></span>
<h3>
<strong><a href="home.php?mod=space&amp;do=top&amp;view=online">在线会员</a></strong>
<span class="xs1">- <strong><?=$onlinenum?></strong> 人在线
- <strong><?=$membercount?></strong> 会员(<strong><?=$invisiblecount?></strong> 隐身),
<strong><?=$guestcount?></strong> 位游客
- 最高记录是 <strong><?=$onlineinfo['0']?></strong> 于 <strong><?=$onlineinfo['1']?></strong>.</span>
</h3>
<? } else { ?>
<span class="o"><a href="forum.php?showoldetails=yes#online" class="nobdr"><img src="<?=IMGDIR?>/collapsed_yes.gif" alt="" /></a></span>
<h3>
<strong><a href="home.php?mod=space&amp;do=top&amp;view=online">在线会员</a></strong>
<span class="xs1">- 总计 <strong><?=$onlinenum?></strong> 人在线
- 最高记录是 <strong><?=$onlineinfo['0']?></strong> 于 <strong><?=$onlineinfo['1']?></strong>.</span>
</h3>
<? } } else { ?>
<h3><strong><a href="forum.php?mod=member&amp;action=online">在线会员</a></strong></h3>
<? } if($_G['setting']['whosonlinestatus'] && $detailstatus) { ?>
<dl id="onlinelist">
<dt><?=$_G['cache']['onlinelist']['legend']?></dt>
<? if($detailstatus) { ?>
<dd>
<ul class="cl">
<? if($whosonline) { if(is_array($whosonline)) foreach($whosonline as $key => $online) { ?><li title="时间: <?=$online['lastactivity']?>">
<img src="<?=STATICURL?>image/common/<?=$online['icon']?>" alt="" />
<? if($online['uid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$online['uid']?>"><?=$online['username']?></a>
<? } else { ?>
<?=$online['username']?>
<? } ?>
</li>
<? } } else { ?>
<li style="width: auto">当前只有游客或隐身会员在线</li>
<? } ?>
</ul>
</dd>
<? } ?>
</dl>
<? } ?>
</div>
<? } ?>

<?php if(!empty($_G['setting']['pluginhooks']['index_bottom'])) echo $_G['setting']['pluginhooks']['index_bottom']; ?>
</div>
</div>

<? if($_G['setting']['forumallowside']) { ?>
<div id="sd" class="sd pph"><? if($_G['uid']) { ?>
<div id="pp">
<div class="pmo" id="newpmbox" onclick="showUser();">
<em>
<? if($_G['member']['newpm']) { ?>
<a href="javascript:;">您有新短消息</a>
<? } else { ?>
<a href="javascript:;" class="b">最近联系人</a>
<? } ?>
</em>
</div>
<div class="pmfl" id="newpmbox_menu" style="display: none;">
<div class="s">
<input type="text" name="username" id="username" value="" class="px" autocomplete="off" />
</div>
<ul id="userlist"></ul>		
<script type="text/javascript">var USERABOUT_FS = new friendSelector({'searchId':'username', 'showId':'userlist', 'showType':2, 'handleKey':'USERABOUT_FS'});</script>

</div>
<div class="ch">
<label class="wx" id="ppc"><a href="home.php?mod=space&amp;do=home">我的中心</a></label>
</div>
</div>
<div id="ppc_menu" style="display: none;">
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE]; ?><?php getpanelapp(); ?><div class="appl cl">
<ul>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=doing&amp;fchannel=xmenum"><img src="<?=STATICURL?>image/feed/doing.gif" alt="" />记录</a>
<span><a href="home.php?mod=space&amp;do=doing&amp;fchannel=xmenum">+发布</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=album&amp;fchannel=xmenua"><img src="<?=STATICURL?>image/feed/album.gif" alt="" />相册</a>
<span><a href="home.php?mod=spacecp&amp;ac=upload&amp;fchannel=xmenua">+上传</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=blog&amp;fchannel=xmenub"><img src="<?=STATICURL?>image/feed/blog.gif" alt="" />日志</a>
<span><a href="home.php?mod=spacecp&amp;ac=blog&amp;fchannel=xmenub">+发布</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=share&amp;fchannel=xmenus"><img src="<?=STATICURL?>image/feed/share.gif" alt="" />分享</a>
<span><a id="k_share" onclick="showWindow(this.id, this.href, 'get', 0);" href="home.php?mod=spacecp&amp;ac=share&amp;fchannel=xmenus">+添加</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=thread&amp;&amp;from=home&amp;fchannel=xmenut"><img src="<?=STATICURL?>image/feed/thread.gif" alt="" />帖子</a>
<span><a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=0&amp;&amp;from=home&amp;fchannel=xmenut">+发布</a></span>
</li>
<? if($_G['setting']['groupstatus']) { ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=group&amp;fchannel=xmenug"><img src="<?=STATICURL?>image/feed/group.gif" alt="" />群组</a>
<span><a href="forum.php?mod=group&amp;action=create&amp;fchannel=xmenug">+创建</a></span>
</li>
<? } ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=activity&amp;fchannel=xmenuhd"><img src="<?=IMGDIR?>/activitysmall.gif" alt="" />活动</a>
<span>
<? if($_G['setting']['activityforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['activityforumid']?>&amp;special=4&amp;from=home&amp;fchannel=xmenuhd">+发起</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=4&amp;from=home&amp;fchannel=xmenuhd">+发起</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=poll&amp;fchannel=xmenup"><img src="<?=IMGDIR?>/pollsmall.gif" alt="" />投票</a>
<span>
<? if($_G['setting']['pollforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['pollforumid']?>&amp;special=1&amp;from=home&amp;fchannel=xmenup">+发起</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=1&amp;from=home&amp;fchannel=xmenup">+发起</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=reward&amp;fchannel=xmenur"><img src="<?=IMGDIR?>/rewardsmall.gif" alt="" />悬赏</a>
<span>
<? if($_G['setting']['rewardforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['rewardforumid']?>&amp;special=3&amp;from=home&amp;fchannel=xmenur">+发布</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=3&amp;from=home&amp;fchannel=xmenur">+发布</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=debate&amp;fchannel=xmenud"><img src="<?=IMGDIR?>/debatesmall.gif" alt="" />辩论</a>
<span>
<? if($_G['setting']['debateforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['debateforumid']?>&amp;special=5&amp;from=home&amp;fchannel=xmenud">+发起</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=5&amp;from=home&amp;fchannel=xmenud">+发起</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=trade&amp;fchannel=xmenugood"><img src="<?=IMGDIR?>/tradesmall.gif" alt="" />商品</a>
<span>
<? if($_G['setting']['tradeforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['tradeforumid']?>&amp;special=2&amp;from=home&amp;fchannel=xmenugood">+出售</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=2&amp;from=home&amp;fchannel=xmenugood">+出售</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=favorite&amp;fchannel=xmenus"><img src="<?=STATICURL?>image/feed/favorite.gif" alt="" />收藏</a>
</li><?php $apparea = false; if(!empty($_G['my_panelapp'])) { if(is_array($_G['my_panelapp'])) foreach($_G['my_panelapp'] as $appid => $app) { if($app['userpanelarea']==1) { ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<img class="appicn" src="<?=IMGDIR?>/emp.gif" name="<?=$appid?>" alt="<?=$app['appname']?>" /><a href="userapp.php?mod=app&amp;id=<?=$app['appid']?>" title="<?=$app['appname']?>"><?=$app['appname']?></a>
</li><?php unset($_G[my_panelapp][$appid]); } elseif(!$apparea && $app['userpanelarea']==3) { ?><?php $apparea = true; } } } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_appmenu1'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_appmenu1'][$_G['basescript'].'::'.CURMODULE]; ?>
</ul>
</div>

<hr class="da" />
<div class="appl cl">
<ul>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=friend&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/friend.gif" alt="" />好友</a>
<span><a href="home.php?mod=spacecp&amp;ac=invite&amp;fchannel=xmenuf">+邀请</a></span>
</li>
<li><a href="home.php?mod=task&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/task.gif" alt="" />任务</a></li>
<? if($_G['setting']['magicstatus']) { ?><li><a href="home.php?mod=magic&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/magic.gif" alt="" />道具</a></li><? } ?>
<li><img src="<?=STATICURL?>image/feed/medal.gif" alt="" /><a href="home.php?mod=medal&amp;fchannel=xmenuf">勋章</a></li>
<? if(!empty($_G['my_panelapp'])) { if(is_array($_G['my_panelapp'])) foreach($_G['my_panelapp'] as $appid => $app) { if($app['userpanelarea']==2) { ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<img class="appicn" src="<?=IMGDIR?>/emp.gif" name="<?=$appid?>" alt="<?=$app['appname']?>" /><a href="userapp.php?mod=app&amp;id=<?=$app['appid']?>" title="<?=$app['appname']?>"><?=$app['appname']?></a>
</li><?php unset($_G[my_panelapp][$appid]); } elseif(!$apparea && $app['userpanelarea']==3) { ?><?php $apparea = true; } } } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_appmenu2'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_appmenu2'][$_G['basescript'].'::'.CURMODULE]; ?>
</ul>
</div>
<? if($apparea) { ?>
<hr class="da" />
<div class="appl cl">
<ul><? if(is_array($_G['my_panelapp'])) foreach($_G['my_panelapp'] as $appid => $app) { if($app['userpanelarea']==3) { ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<img class="appicn" src="<?=IMGDIR?>/emp.gif" name="<?=$appid?>" alt="<?=$app['appname']?>" /><a href="userapp.php?mod=app&amp;id=<?=$app['appid']?>" title="<?=$app['appname']?>"><?=$app['appname']?></a>
</li>
<? } } ?>
</ul>
</div>
<? } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_bottom'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_bottom'][$_G['basescript'].'::'.CURMODULE]; ?>
</div>

<script type="text/javascript">inituserabout();</script>
<? } else { ?>
<div class="ch">
<label class="wx">你没有登录</label>
</div>

<? } ?><div class="tns">
<table cellpadding="4" cellspacing="0" border="0">
<tr>
<th><p><?=$todayposts?></p>今日发帖</th>
<th><p><?=$posts?></p>帖子</th>
<td><p><?=$_G['cache']['userstats']['totalmembers']?></p>会员</td>
</tr>
</table>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['index_side_top'])) echo $_G['setting']['pluginhooks']['index_side_top']; if(!empty($_G['cache']['heats']['message'])) { ?>
<hr class="l" />
<div class="bn">
<h2><?=$_G['setting']['navs']['2']['navname']?>热点</h2><? if(is_array($_G['cache']['heats']['message'])) foreach($_G['cache']['heats']['message'] as $data) { ?><dl class="xld bbs mbm">
<dt><? if($_G['adminid'] == 1) { ?><a class="d" href="forum.php?mod=misc&amp;action=removeindexheats&amp;tid=<?=$data['tid']?>&amp;from=indexheats" onclick="return removeindexheats()">delete</a><? } ?>
<a href="forum.php?mod=viewthread&amp;tid=<?=$data['tid']?>&amp;from=indexheats"><?=$data['subject']?></a></dt>
<dd><?=$data['message']?></dd>
</dl>
<? } ?>
<ul class="xl"><? if(is_array($_G['cache']['heats']['subject'])) foreach($_G['cache']['heats']['subject'] as $data) { ?><li><? if($_G['adminid'] == 1) { ?><a class="d" href="forum.php?mod=misc&amp;action=removeindexheats&amp;tid=<?=$data['tid']?>" onclick="return removeindexheats()">delete</a><? } ?><a href="forum.php?mod=viewthread&amp;tid=<?=$data['tid']?>&amp;from=indexheats_second"><?=$data['subject']?></a></li>
<? } ?>
</ul>
</div>
<? } ?>
<hr class="l" />
<div class="drag">
<!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
</div>
<? if($_G['cache']['forumlinks']['0'] || $_G['cache']['forumlinks']['1'] || $_G['cache']['forumlinks']['2']) { ?>
<div class="bn lk">
<h2>友情链接</h2>
<? if($_G['cache']['forumlinks']['0']) { ?>
<ul class="m cl"><?=$_G['cache']['forumlinks']['0']?></ul>
<? } if($_G['cache']['forumlinks']['1']) { ?>
<div class="cl mbm">
<?=$_G['cache']['forumlinks']['1']?>
</div>
<? } if($_G['cache']['forumlinks']['2']) { ?>
<ul class="x cl">
<?=$_G['cache']['forumlinks']['2']?>
</ul>
<? } ?>
</div>
<? } ?>
<?php if(!empty($_G['setting']['pluginhooks']['index_side_bottom'])) echo $_G['setting']['pluginhooks']['index_side_bottom']; } ?>
</div>
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div>	</div>
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