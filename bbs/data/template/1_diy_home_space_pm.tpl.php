<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_pm');
0
|| checktplrefresh('./template/default/home/space_pm.htm', './template/default/common/seditor.htm', 1380628682, 'diy', './data/template/1_diy_home_space_pm.tpl.php', './template/default', 'home/space_pm')
|| checktplrefresh('./template/default/home/space_pm.htm', './template/default/common/userabout.htm', 1380628682, 'diy', './data/template/1_diy_home_space_pm.tpl.php', './template/default', 'home/space_pm')
;?>
<?php $_G['home_tpl_titles'] = array('短消息'); include template('common/header'); ?><div id="pt" class="wp">
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo;
<a href="home.php"><?=$_G['setting']['navs']['4']['navname']?></a> &rsaquo; 
<a href="home.php?mod=space&amp;do=pm">消息</a>
</div>

<style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div>

<div id="ct" class="wp cl">
<div class="mn">
<div class="ch">
<label class="wx"><a href="<?=$_G['setting']['navs']['4']['filename']?>"><?=$_G['setting']['navs']['4']['navname']?></a></label>
</div>

<div class="bm">
<h1 class="mt"><img alt="pm" src="<?=STATICURL?>image/feed/pm.gif" class="vm" /> 消息</h1>
<ul class="tb cl">
<li class="a"><a href="home.php?mod=space&amp;do=pm">短消息</a></li>
<li><a href="home.php?mod=space&amp;do=notice">通知</a></li>
<? if($_G['setting']['my_app_status']) { ?>
<li><a href="home.php?mod=space&amp;do=notice&amp;view=userapp">应用消息</a></li>
<? } ?>
<li class="o"><a href="home.php?mod=spacecp&amp;ac=pm">发送消息</a></li>
</ul>

<p class="tbmu">
<a href="home.php?mod=space&amp;do=pm&amp;filter=newpm"<?=$actives['newpm']?>>未读消息</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=privatepm"<?=$actives['privatepm']?>>私人消息</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=systempm"<?=$actives['systempm']?>>系统消息</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=announcepm"<?=$actives['announcepm']?>>公共消息</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=ignore"<?=$actives['ignore']?>>忽略列表</a>
</p>
<? if($touid) { ?>
<p class="tbmu">
你们的历史记录:
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=1"<?=$actives['1']?>>最近一天</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=2"<?=$actives['2']?>>最近两天</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=3"<?=$actives['3']?>>最近三天</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=4"<?=$actives['4']?>>本周</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=5"<?=$actives['5']?>>全部</a>
</p>
<? } if($_GET['subop'] == 'view') { if($list) { ?>
<div id="pm_ul" class="xld xlda"><? if(is_array($list)) foreach($list as $key => $value) { ?><dl class="cl bbda" id="pmlist_<?=$value['pmid']?>">
<dd class="m avt">
<? if($value['msgfromid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['msgfromid']?>"><?php echo avatar($value[msgfromid],small); ?></a>
<? } else { ?>
<img src="<?=IMGDIR?>/systempm.gif" alt="systempm" />
<? } ?>
</dd>
<dt>
<? if($value['msgfromid']) { ?><a href="home.php?mod=space&amp;uid=<?=$value['msgfromid']?>" class="xi2"><?=$value['msgfrom']?></a> <? } ?><span class="xg1 xw0"><?php echo dgmdate($value[dateline], 'u'); ?></span>
</dt>
<dd><?=$value['message']?></dd>
</dl>
<? } ?>
</div>
<? } else { ?>
<div class="emp">
当前没有相应的短消息。
</div>
<? } if($touid && $list) { ?>
<div id="pm_ul_post" class="xld xlda">
<dl class="cl">
<dd class="m avt">
<a href="home.php?mod=space&amp;uid=<?=$space['uid']?>"><?php echo avatar($space[uid],small); ?></a>
</dd>
<dt><a href="home.php?mod=space&amp;uid=<?=$space['uid']?>" class="xi2"><?=$space['username']?></a></dt>
<dd>
<form id="pmform" class="pmform" name="pmform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=send&amp;touid=<?=$touid?>&amp;pmid=<?=$pmid?>&amp;daterange=<?=$daterange?>" onsubmit="parsepmcode(this);">
<div class="tedt">
<div class="bar"><?php $seditor = array('reply', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies')); ?><div class="fpd">
<? if(in_array('bold', $seditor['1'])) { ?>
<a href="javascript:;" title="文字加粗" class="fbld" onclick="seditor_insertunit('<?=$seditor['0']?>', '[b]', '[/b]')">B</a>
<? } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="设置文字颜色" class="fclr" id="<?=$seditor['0']?>forecolor" onclick="showColorBox(this.id, 2, '<?=$seditor['0']?>');doane(event)">Color</a>
<? } if(in_array('img', $seditor['1'])) { ?>
<a href="javascript:;" title="图片" class="fmg" onclick="seditor_insertunit('<?=$seditor['0']?>', '[img]', '[/img]')">Image</a>
<? } if(in_array('link', $seditor['1'])) { ?>
<a href="javascript:;" title="添加链接" class="flnk" onclick="seditor_insertunit('<?=$seditor['0']?>', '[url]', '[/url]')">Link</a>
<? } if(in_array('quote', $seditor['1'])) { ?>
<a href="javascript:;" title="引用" class="fqt" onclick="seditor_insertunit('<?=$seditor['0']?>', '[quote]', '[/quote]')">Quote</a>
<? } if(in_array('code', $seditor['1'])) { ?>
<a href="javascript:;" title="代码" class="fcd" onclick="seditor_insertunit('<?=$seditor['0']?>', '[code]', '[/code]')">Code</a>
<? } if(in_array('smilies', $seditor['1'])) { ?>
<a href="javascript:;" class="fsml" id="<?=$seditor['0']?>sml" onclick="showMenu({'ctrlid':this.id,'evt':'click','layer':2});doane(event)">Smilies</a>
<script src="data/cache/common_smilies_var.js?<?=VERHASH?>" type="text/javascript" reload="1"></script>
<script type="text/javascript" reload="1">smilies_show('<?=$seditor['0']?>smiliesdiv', <?=$_G['setting']['smcols']?>, '<?=$seditor['0']?>');</script>
<? } ?>
</div></div>
<div class="area">
<textarea rows="3" cols="40" name="message" class="pt" id="replymessage" onkeydown="ctrlEnter(event, 'pmsubmit');"></textarea>
</div>
</div>
<p class="mtn">
<button type="submit" name="pmsubmit" id="pmsubmit" value="true" class="pn"><strong>回复</strong></button>
</p>
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
</form>
</dd>
</dl>
</div>
<? } } elseif($_GET['subop'] == 'ignore') { ?>

<form id="ignoreform" name="ignoreform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=ignore">
<table cellspacing="0" cellpadding="0" width="100%" class="tfm">
<caption>
<p>添加到该列表中的用户给您发送短消息时将不予接收</p>
								<p>添加多个忽略人员名单时用逗号 "," 隔开，如“张三,李四,王五”</p>
								<p>如需禁止所有用户发来的短消息，请设置为 "&#123;ALL&#125;"</p>
</caption>
<tr>
<td><textarea id="ignorelist" name="ignorelist" cols="80" rows="5" class="pt" style="width: 95%;" onkeydown="ctrlEnter(event, 'ignoresubmit');"><?=$ignorelist?></textarea></td>
</tr>
<tr>
<td><button type="submit" name="ignoresubmit" id="ignoresubmit" value="true" class="pn"><strong>保存</strong></button></td>
</tr>
</table>
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
</form>

<? } else { if($count) { ?>
<div class="xld xlda"><? if(is_array($list)) foreach($list as $key => $value) { ?><dl id="pmlist_<? if($value['touid']) { ?><?=$value['touid']?><? } else { ?><?=$value['pmid']?><? } ?>" class="bbda cl">
<dd class="m avt">
<? if($value['touid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['touid']?>"><?php echo avatar($value[touid],small); ?></a>
<? } else { ?>
<img src="<?=IMGDIR?>/systempm.gif" alt="systempm" />
<? } ?>
</dd>
<dt>
<? if($_G['gp_filter']!='announcepm') { if($_G['gp_filter']=='systempm') { ?>
<a class="d" href="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;folder=inbox&amp;pmid=<?=$value['pmid']?>&amp;handlekey=delpmhk_<?=$value['pmid']?>" id="a_delete_<?=$value['pmid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">删除</a>
<? } else { ?>
<a class="d" href="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;folder=inbox&amp;deluid=<?=$value['touid']?>&amp;handlekey=delpmhk_<?=$value['pmid']?>" id="a_delete_<?=$value['pmid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">删除</a>
<? } } if($value['touid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['touid']?>" class="xi2"><?=$value['msgfrom']?></a>
<? } ?>
<span class="xg1 xw0"><?php echo dgmdate($value[dateline], 'u'); ?></span>
</dt>
<dd<? if($value['new']) { ?> class="xw1"<? } ?>><?=$value['message']?></dd>
<dd class="pns">
<? if($value['touid']) { ?>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;pmid=<?=$value['pmid']?>&amp;touid=<?=$value['touid']?>&amp;daterange=<?=$value['daterange']?>" class="xi2">查看详情 &rsaquo;</a>
<? } else { ?>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;pmid=<?=$value['pmid']?>" class="xi2">查看详情 &rsaquo;</a>
<? } ?>
</dd>
</dl>
<? } ?>
</div>
<? if($multi) { ?><div class="pgs cl"><?=$multi?></div><? } } else { ?>
<div class="emp">
当前没有相应的短消息。
</div>
<? } } ?>

</div>
</div>
<div class="sd"><? if($_G['uid']) { ?>
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

<? } ?><div class="drag">
<!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
</div>

</div>
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div><? include template('common/footer'); ?>