<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_notice');
0
|| checktplrefresh('./template/default/home/space_notice.htm', './template/default/common/userabout.htm', 1380628678, 'diy', './data/template/1_diy_home_space_notice.tpl.php', './template/default', 'home/space_notice')
;?>
<?php $_G['home_tpl_titles'] = array('����'); include template('common/header'); ?><div id="pt" class="wp">
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo;
<a href="home.php"><?=$_G['setting']['navs']['4']['navname']?></a> &rsaquo; 
<a href="home.php?mod=space&amp;do=notice">��Ϣ</a>
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
<h1 class="mt"><img alt="pm" src="<?=STATICURL?>image/feed/nts.gif" class="vm" /> ����</h1>
<ul class="tb cl">
<li><a href="home.php?mod=space&amp;do=pm">����Ϣ</a></li>
<li<?=$actives['notice']?>><a href="home.php?mod=space&amp;do=notice">֪ͨ</a></li>
<? if($_G['setting']['my_app_status']) { ?>
<li<?=$actives['userapp']?>><a href="home.php?mod=space&amp;do=notice&amp;view=userapp">Ӧ����Ϣ</a></li>
<? } ?>
<li class="o"><a href="home.php?mod=spacecp&amp;ac=pm">������Ϣ</a></li>
</ul>

<? if($view=='userapp') { ?>

<script type="text/javascript">
function manyou_add_userapp(hash, url) {
if(isUndefined(url)) {
$(hash).innerHTML = "<tr><td colspan=\"2\">�ɹ������˸���Ӧ����Ϣ</td></tr>";
} else {
$(hash).innerHTML = "<tr><td colspan=\"2\">��������������...</td></tr>";
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
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp">ȫ��Ӧ����Ϣ</a><? if(is_array($apparr)) foreach($apparr as $type => $val) { ?><span class="pipe">|</span>
<a href="home.php?mod=userapp&amp;id=<?=$val['0']['appid']?>&amp;uid=<?=$space['uid']?>" title="<?=$val['0']['typename']?>"><img src="http://appicon.manyou.com/icons/<?=$val['0']['appid']?>" alt="<?=$val['0']['typename']?>" class="vm" /></a>
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp&amp;type=<?=$val['0']['appid']?>"> <?php echo count($val); ?> �� <?=$val['0']['typename']?> <? if($val['0']['type']) { ?>����<? } else { ?>����<? } ?></a>
<? } ?>
</div>

<? if($list) { if(is_array($list)) foreach($list as $key => $invite) { ?><h4 class="mtw mbm">
<a href="home.php?mod=space&amp;do=notice&amp;view=userapp&amp;op=del&amp;appid=<?=$invite['0']['appid']?>" class="y xg1">���Ը�Ӧ�õ���������</a>
<a href="home.php?mod=userapp&amp;id=<?=$invite['0']['appid']?>&amp;uid=<?=$space['uid']?>" title="<?=$apparr[$invite['0']['appid']]?>"><img src="http://appicon.manyou.com/icons/<?=$invite['0']['appid']?>" alt="<?=$apparr[$invite['0']['appid']]?>" class="vm" /></a> 
���� <?php echo count($invite); ?> �� <?=$invite['0']['typename']?> <? if($invite['0']['type']) { ?>����<? } else { ?>����<? } ?>
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
<div class="emp">û���µ�Ӧ�����������</div>
<? } } else { ?>

<div class="tbmu" style="display:none;">
<a href="home.php?mod=space&amp;do=notice" <? if(empty($type)) { ?>class="a"<? } ?>>ȫ��֪ͨ</a><? if(is_array($noticetypes)) foreach($noticetypes as $key => $name) { ?><span class="pipe">|</span><a href="home.php?mod=space&amp;do=notice&amp;type=<?=$key?>" <? if($key==$type) { ?>class="a"<? } ?>><?=$name?></a>
<? } ?>
</div>
<div class="tbmu">��ʾ������о���Щ֪ͨ�������ɧ��ʱ������֪ͨ�Ҳ������Сͼ�꣬�����δ���֪ͨ��</div>

<? if($newprompt) { ?>
<ul class="mipm cl mtm">
<? if($space['notifications']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/notice.gif" alt="notice" class="vm" /><a href="home.php?mod=space&amp;do=notice"><strong><?=$space['notifications']?></strong> ����֪ͨ</a></li><? } if($space['pendingfriends']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/friend.gif" alt="friend" class="vm" /><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=request"><strong><?=$space['pendingfriends']?></strong> ����������</a></li><? } if($space['groupinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/mtag.gif" alt="mtag" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;type=group"><strong><?=$space['groupinvitations']?></strong> ��Ⱥ������</a></li><? } if($space['activityinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/event.gif" alt="event" class="vm" /><a href="home.php?mod=spacecp&amp;ac=event&amp;op=eventinvite"><strong><?=$space['activityinvitations']?></strong> �������</a></li><? } if($space['myinvitations']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/userapp.gif" alt="userapp" class="vm" /><a href="home.php?mod=space&amp;do=notice&amp;view=userapp"><strong><?=$space['myinvitations']?></strong> ��Ӧ����Ϣ</a></li><? } if($space['pokes']) { ?><li class="brm"><img src="<?=STATICURL?>image/feed/poke.gif" alt="poke" class="vm" /><a href="home.php?mod=spacecp&amp;ac=poke"><strong><?=$space['pokes']?></strong> �����к�</a></li><? } ?>
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
<a class="d b" href="home.php?mod=spacecp&amp;ac=common&amp;op=ignore&amp;authorid=<?=$value['authorid']?>&amp;type=<?=$value['type']?>&amp;handlekey=addfriendhk_<?=$value['authorid']?>" id="a_note_<?=$value['id']?>" onclick="showWindow(this.id, this.href, 'get', 0);">����</a>
<span class="xg1 xw0"><?php echo dgmdate($value[dateline], 'u'); ?></span>
</dt>
<dd style="<?=$value['style']?>">
<?=$value['note']?> 
</dd>

<? if($value['from_num']) { ?>
<dd class="xg1 xw0">���� <?=$value['from_num']?> ����֪ͬͨ������</dd>
<? } if($value['authorid'] && !$value['isfriend']) { ?>
<dd class="mtw">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?=$value['authorid']?>&amp;handlekey=addfriendhk_<?=$value['authorid']?>" id="add_note_friend_<?=$value['authorid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">��Ϊ����</a>
<span class="pipe">|</span>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?=$value['authorid']?>" id="a_poke_<?=$value['authorid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">���к�</a>
</dd>
<? } ?>
</dl>
<? } ?>
</div>

<? if($view!='userapp' && $space['notifications']) { ?>
<div class="mtm mbm"><a href="home.php?mod=space&amp;do=notice&amp;ignore=all">���� <?=$value['from_num']?> ����֪ͬͨ������ &rsaquo;</a></div>
<? } if($multi) { ?><div class="pgs cl"><?=$multi?></div><? } } else { ?>
<div class="emp">û���µ�֪ͨ</div>
<? } } ?>
</div>
</div>
<div class="sd"><? if($_G['uid']) { ?>
<div id="pp">
<div class="pmo" id="newpmbox" onclick="showUser();">
<em>
<? if($_G['member']['newpm']) { ?>
<a href="javascript:;">�����¶���Ϣ</a>
<? } else { ?>
<a href="javascript:;" class="b">�����ϵ��</a>
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
<label class="wx" id="ppc"><a href="home.php?mod=space&amp;do=home">�ҵ�����</a></label>
</div>
</div>
<div id="ppc_menu" style="display: none;">
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE]; ?><?php getpanelapp(); ?><div class="appl cl">
<ul>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=doing&amp;fchannel=xmenum"><img src="<?=STATICURL?>image/feed/doing.gif" alt="" />��¼</a>
<span><a href="home.php?mod=space&amp;do=doing&amp;fchannel=xmenum">+����</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=album&amp;fchannel=xmenua"><img src="<?=STATICURL?>image/feed/album.gif" alt="" />���</a>
<span><a href="home.php?mod=spacecp&amp;ac=upload&amp;fchannel=xmenua">+�ϴ�</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=blog&amp;fchannel=xmenub"><img src="<?=STATICURL?>image/feed/blog.gif" alt="" />��־</a>
<span><a href="home.php?mod=spacecp&amp;ac=blog&amp;fchannel=xmenub">+����</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=share&amp;fchannel=xmenus"><img src="<?=STATICURL?>image/feed/share.gif" alt="" />����</a>
<span><a id="k_share" onclick="showWindow(this.id, this.href, 'get', 0);" href="home.php?mod=spacecp&amp;ac=share&amp;fchannel=xmenus">+���</a></span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=thread&amp;&amp;from=home&amp;fchannel=xmenut"><img src="<?=STATICURL?>image/feed/thread.gif" alt="" />����</a>
<span><a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=0&amp;&amp;from=home&amp;fchannel=xmenut">+����</a></span>
</li>
<? if($_G['setting']['groupstatus']) { ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=group&amp;fchannel=xmenug"><img src="<?=STATICURL?>image/feed/group.gif" alt="" />Ⱥ��</a>
<span><a href="forum.php?mod=group&amp;action=create&amp;fchannel=xmenug">+����</a></span>
</li>
<? } ?>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=activity&amp;fchannel=xmenuhd"><img src="<?=IMGDIR?>/activitysmall.gif" alt="" />�</a>
<span>
<? if($_G['setting']['activityforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['activityforumid']?>&amp;special=4&amp;from=home&amp;fchannel=xmenuhd">+����</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=4&amp;from=home&amp;fchannel=xmenuhd">+����</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=poll&amp;fchannel=xmenup"><img src="<?=IMGDIR?>/pollsmall.gif" alt="" />ͶƱ</a>
<span>
<? if($_G['setting']['pollforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['pollforumid']?>&amp;special=1&amp;from=home&amp;fchannel=xmenup">+����</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=1&amp;from=home&amp;fchannel=xmenup">+����</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=reward&amp;fchannel=xmenur"><img src="<?=IMGDIR?>/rewardsmall.gif" alt="" />����</a>
<span>
<? if($_G['setting']['rewardforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['rewardforumid']?>&amp;special=3&amp;from=home&amp;fchannel=xmenur">+����</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=3&amp;from=home&amp;fchannel=xmenur">+����</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=debate&amp;fchannel=xmenud"><img src="<?=IMGDIR?>/debatesmall.gif" alt="" />����</a>
<span>
<? if($_G['setting']['debateforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['debateforumid']?>&amp;special=5&amp;from=home&amp;fchannel=xmenud">+����</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=5&amp;from=home&amp;fchannel=xmenud">+����</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=trade&amp;fchannel=xmenugood"><img src="<?=IMGDIR?>/tradesmall.gif" alt="" />��Ʒ</a>
<span>
<? if($_G['setting']['tradeforumid']) { ?>
<a href="forum.php?mod=post&amp;action=newthread&amp;fid=<?=$_G['setting']['tradeforumid']?>&amp;special=2&amp;from=home&amp;fchannel=xmenugood">+����</a>
<? } else { ?>
<a onclick="showWindow('nav', this.href)" href="forum.php?mod=misc&amp;action=nav&amp;special=2&amp;from=home&amp;fchannel=xmenugood">+����</a>
<? } ?>
</span>
</li>
<li onmouseover="this.className='hvr'" onmouseout="this.className=''">
<a href="home.php?mod=space&amp;do=favorite&amp;fchannel=xmenus"><img src="<?=STATICURL?>image/feed/favorite.gif" alt="" />�ղ�</a>
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
<a href="home.php?mod=space&amp;do=friend&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/friend.gif" alt="" />����</a>
<span><a href="home.php?mod=spacecp&amp;ac=invite&amp;fchannel=xmenuf">+����</a></span>
</li>
<li><a href="home.php?mod=task&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/task.gif" alt="" />����</a></li>
<? if($_G['setting']['magicstatus']) { ?><li><a href="home.php?mod=magic&amp;fchannel=xmenuf"><img src="<?=STATICURL?>image/feed/magic.gif" alt="" />����</a></li><? } ?>
<li><img src="<?=STATICURL?>image/feed/medal.gif" alt="" /><a href="home.php?mod=medal&amp;fchannel=xmenuf">ѫ��</a></li>
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
<label class="wx">��û�е�¼</label>
</div>

<? } ?><div class="drag">
<!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
</div>

</div>
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div><? include template('common/footer'); ?>