<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_notice');
0
|| checktplrefresh('./template/default/home/space_notice.htm', './template/default/common/userabout.htm', 1380628678, 'diy', './data/template/1_diy_home_space_notice.tpl.php', './template/default', 'home/space_notice')
;?>
<?php $_G['home_tpl_titles'] = array('提醒'); include template('common/header'); ?><div id="pt" class="wp">
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo;
<a href="home.php"><?=$_G['setting']['navs']['4']['navname']?></a> &rsaquo; 
<a href="home.php?mod=space&amp;do=notice">消息</a>
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
<h1 class="mt"><img alt="pm" src="<?=STATICURL?>image/feed/nts.gif" class="vm" /> 提醒</h1>
<ul class="tb cl">
<li><a href="home.php?mod=space&amp;do=pm">短消息</a></li>
<li<?=$actives['notice']?>><a href="home.php?mod=space&amp;do=notice">通知</a></li>
<? if($_G['setting']['my_app_status']) { ?>
<li<?=$actives['userapp']?>><a href="home.php?mod=space&amp;do=notice&amp;view=userapp">应用消息</a></li>
<? } ?>
<li class="o"><a href="home.php?mod=spacecp&amp;ac=pm">发送消息</a></li>
</ul>

<? if($view=='userapp') { ?>

<script type="text/javascript">
function manyou_add_userapp(hash, url) {
if(isUndefined(url)) {
$(hash).innerHTML = "<tr><td colspan=\"2\">成功忽略了该条应用消息</td></tr>";
} else {
$(hash).innerHTML = "<tr><td colspan=\"2\">正在引导您进入...</td></tr>";
}
var x = new Ajax();
x.get('home.php?mod=misc&ac=ajax&op=deluserapp&hash='+hash, function(s){
if(!isUndefined(url)) {
location.href = url;
}
});
}
</script>
<div class="tbmu">
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp">全部应用消息</a><? if(is_array($apparr)) foreach($apparr as $type => $val) { ?><span class="pipe">|</span>
<a href="home.php?mod=userapp&amp;id=<?=$val['0']['appid']?>&amp;uid=<?=$space['uid']?>" title="<?=$val['0']['typename']?>"><img src="http://appicon.manyou.com/icons/<?=$val['0']['appid']?>" alt="<?=$val['0']['typename']?>" class="vm" /></a>
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp&amp;type=<?=$val['0']['appid']?>"> <?php echo count($val); ?> 个 <?=$val['0']['typename']?> <? if($val['0']['type']) { ?>请求<? } else { ?>邀请<? } ?></a>
<? } ?>
</div>

<? if($list) { if(is_array($list)) foreach($list as $key => $invite) { ?><h4 class="mtw mbm">
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp&amp;op=del&amp;appid=<?=$invite['0']['appid']?>" class="y xg1">忽略该应用的所有邀请</a>
<a href="home.php?mod=userapp&amp;id=<?=$invite['0']['appid']?>&amp;uid=<?=$space['uid']?>" title="<?=$apparr[$invite['0']['appid']]?>"><img src="http://appicon.manyou.com/icons/<?=$invite['0']['appid']?>" alt="<?=$apparr[$invite['0']['appid']]?>" class="vm" /></a> 
您有 <?php echo count($invite); ?> 个 <?=$invite['0']['typename']?> <? if($invite['0']['type']) { ?>请求<? } else { ?>邀请<? } ?>
</h4>
<div class="xld xlda"><? if(is_array($invite)) foreach($invite as $value) { ?><dl class="bbda cl">
<dd class="m avt mbn">
<a href="home.php?mod=space&amp;uid=<?=$value['fromuid']?>"><?php echo avatar($value[fromuid],small); ?></a>
</dd>
<dt id="<?=$value['hash']?>">
<div class="xw0 xi3"><?=$value['myml']?></div>
</dt>
</dl>
<? } ?>
</div>
<? } if($multi) { ?><div class="pgs cl"><?=$multi?></div><? } } else { ?>
<div class="emp">没有新的应用请求或邀请</div>
<? } } else { ?>

<div class="tbmu" style="display:none;">
<a href="home.php?mod=space&amp;do=notice" <? if(empty($type)) { ?>class="a"<? } ?>>全部通知</a><? if(is_array($noticetypes)) foreach($noticetypes as $key => $name) { ?><span class="pipe">|</span><a href="home.php?mod=space&amp;do=notice&amp;type=<?=$key?>" <? if($key==$type) { ?>class="a"<? } ?>><?=$name?></a>
<? } ?>
</div>
<div class="tbmu">提示：当你感觉有些通知对你造成骚扰时，请点击通知右侧的屏蔽小图标，可屏蔽此类通知。</div>

<? if($newprompt) { ?>
<ul class="mipm cl mtm">
<? if($space['notifications']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/notice.gif" alt="notice" class="vm" /><a href="home.php?mod=space&amp;do=notice"><strong><?=$space['notifications']?></strong> 条新通知</a></li><? } if($space['pendingfriends']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/friend.gif" alt="friend" class="vm" /><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=request"><strong><?=$space['pendingfriends']?></strong> 个好友请求</a></li><? } if($space['groupinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/mtag.gif" alt="mtag" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;type=group"><strong><?=$space['groupinvitations']?></strong> 个群组邀请</a></li><? } if($space['activityinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/event.gif" alt="event" class="vm" /><a href="home.php?mod=spacecp&amp;ac=event&amp;op=eventinvite"><strong><?=$space['activityinvitations']?></strong> 个活动邀请</a></li><? } if($space['myinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/userapp.gif" alt="userapp" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;view=userapp"><strong><?=$space['myinvitations']?></strong> 个应用消息</a></li><? } if($space['pokes']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/poke.gif" alt="poke" class="vm" /><a href="home.php?mod=spacecp&amp;ac=poke"><strong><?=$space['pokes']?></strong> 个新招呼</a></li><? } ?>
</ul>
<? } if($list) { ?>

<div class="xld xlda"><? if(is_array($list)) foreach($list as $key => $value) { ?><dl class="bbda cl">
<dd class="m avt mbn">
<? if($value['authorid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['authorid']?>"><?php echo avatar($value[authorid],small); ?></a>
<? } else { ?>
<img src="<?=IMGDIR?>/systempm.gif" alt="systempm" />
<? } ?>
</dd>
<dt>
<a class="d b" href="home.php?mod=spacecp&amp;ac=common&amp;op=ignore&amp;authorid=<?=$value['authorid']?>&amp;type=<?=$value['type']?>&amp;handlekey=addfriendhk_<?=$value['authorid']?>" id="a_note_<?=$value['id']?>" onclick="showWindow(this.id, this.href, 'get', 0);">屏蔽</a>
<span class="xg1 xw0"><?php echo dgmdate($value[dateline], 'u'); ?></span>
</dt>
<dd style="<?=$value['style']?>">
<?=$value['note']?> 
</dd>

<? if($value['from_num']) { ?>
<dd class="xg1 xw0">还有 <?=$value['from_num']?> 个相同通知被忽略</dd>
<? } if($value['authorid'] && !$value['isfriend']) { ?>
<dd class="mtw">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?=$value['authorid']?>&amp;handlekey=addfriendhk_<?=$value['authorid']?>" id="add_note_friend_<?=$value['authorid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">加为好友</a>
<span class="pipe">|</span>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?=$value['authorid']?>" id="a_poke_<?=$value['authorid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">打招呼</a>
</dd>
<? } ?>
</dl>
<? } ?>
</div>

<? if($view!='userapp' && $space['notifications']) { ?>
<div class="mtm mbm"><a href="home.php?mod=space&amp;do=notice&amp;ignore=all">还有 <?=$value['from_num']?> 个相同通知被忽略 &rsaquo;</a></div>
<? } if($multi) { ?><div class="pgs cl"><?=$multi?></div><? } } else { ?>
<div class="emp">没有新的通知</div>
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