<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_pm');
0
|| checktplrefresh('./template/default/home/space_pm.htm', './template/default/common/seditor.htm', 1380628682, 'diy', './data/template/1_diy_home_space_pm.tpl.php', './template/default', 'home/space_pm')
|| checktplrefresh('./template/default/home/space_pm.htm', './template/default/common/userabout.htm', 1380628682, 'diy', './data/template/1_diy_home_space_pm.tpl.php', './template/default', 'home/space_pm')
;?>
<?php $_G['home_tpl_titles'] = array('����Ϣ'); include template('common/header'); ?><div id="pt" class="wp">
<a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo;
<a href="home.php"><?=$_G['setting']['navs']['4']['navname']?></a> &rsaquo; 
<a href="home.php?mod=space&amp;do=pm">��Ϣ</a>
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
<h1 class="mt"><img alt="pm" src="<?=STATICURL?>image/feed/pm.gif" class="vm" /> ��Ϣ</h1>
<ul class="tb cl">
<li class="a"><a href="home.php?mod=space&amp;do=pm">����Ϣ</a></li>
<li><a href="home.php?mod=space&amp;do=notice">֪ͨ</a></li>
<? if($_G['setting']['my_app_status']) { ?>
<li><a href="home.php?mod=space&amp;do=notice&amp;view=userapp">Ӧ����Ϣ</a></li>
<? } ?>
<li class="o"><a href="home.php?mod=spacecp&amp;ac=pm">������Ϣ</a></li>
</ul>

<p class="tbmu">
<a href="home.php?mod=space&amp;do=pm&amp;filter=newpm"<?=$actives['newpm']?>>δ����Ϣ</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=privatepm"<?=$actives['privatepm']?>>˽����Ϣ</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=systempm"<?=$actives['systempm']?>>ϵͳ��Ϣ</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;filter=announcepm"<?=$actives['announcepm']?>>������Ϣ</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=ignore"<?=$actives['ignore']?>>�����б�</a>
</p>
<? if($touid) { ?>
<p class="tbmu">
���ǵ���ʷ��¼:
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=1"<?=$actives['1']?>>���һ��</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=2"<?=$actives['2']?>>�������</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=3"<?=$actives['3']?>>�������</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=4"<?=$actives['4']?>>����</a><span class="pipe">|</span>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?=$touid?>&amp;daterange=5"<?=$actives['5']?>>ȫ��</a>
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
��ǰû����Ӧ�Ķ���Ϣ��
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
<a href="javascript:;" title="���ּӴ�" class="fbld" onclick="seditor_insertunit('<?=$seditor['0']?>', '[b]', '[/b]')">B</a>
<? } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="����������ɫ" class="fclr" id="<?=$seditor['0']?>forecolor" onclick="showColorBox(this.id, 2, '<?=$seditor['0']?>');doane(event)">Color</a>
<? } if(in_array('img', $seditor['1'])) { ?>
<a href="javascript:;" title="ͼƬ" class="fmg" onclick="seditor_insertunit('<?=$seditor['0']?>', '[img]', '[/img]')">Image</a>
<? } if(in_array('link', $seditor['1'])) { ?>
<a href="javascript:;" title="�������" class="flnk" onclick="seditor_insertunit('<?=$seditor['0']?>', '[url]', '[/url]')">Link</a>
<? } if(in_array('quote', $seditor['1'])) { ?>
<a href="javascript:;" title="����" class="fqt" onclick="seditor_insertunit('<?=$seditor['0']?>', '[quote]', '[/quote]')">Quote</a>
<? } if(in_array('code', $seditor['1'])) { ?>
<a href="javascript:;" title="����" class="fcd" onclick="seditor_insertunit('<?=$seditor['0']?>', '[code]', '[/code]')">Code</a>
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
<button type="submit" name="pmsubmit" id="pmsubmit" value="true" class="pn"><strong>�ظ�</strong></button>
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
<p>��ӵ����б��е��û��������Ͷ���Ϣʱ���������</p>
								<p>��Ӷ��������Ա����ʱ�ö��� "," �������硰����,����,���塱</p>
								<p>�����ֹ�����û������Ķ���Ϣ��������Ϊ "&#123;ALL&#125;"</p>
</caption>
<tr>
<td><textarea id="ignorelist" name="ignorelist" cols="80" rows="5" class="pt" style="width: 95%;" onkeydown="ctrlEnter(event, 'ignoresubmit');"><?=$ignorelist?></textarea></td>
</tr>
<tr>
<td><button type="submit" name="ignoresubmit" id="ignoresubmit" value="true" class="pn"><strong>����</strong></button></td>
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
<a class="d" href="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;folder=inbox&amp;pmid=<?=$value['pmid']?>&amp;handlekey=delpmhk_<?=$value['pmid']?>" id="a_delete_<?=$value['pmid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">ɾ��</a>
<? } else { ?>
<a class="d" href="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;folder=inbox&amp;deluid=<?=$value['touid']?>&amp;handlekey=delpmhk_<?=$value['pmid']?>" id="a_delete_<?=$value['pmid']?>" onclick="showWindow(this.id, this.href, 'get', 0);">ɾ��</a>
<? } } if($value['touid']) { ?>
<a href="home.php?mod=space&amp;uid=<?=$value['touid']?>" class="xi2"><?=$value['msgfrom']?></a>
<? } ?>
<span class="xg1 xw0"><?php echo dgmdate($value[dateline], 'u'); ?></span>
</dt>
<dd<? if($value['new']) { ?> class="xw1"<? } ?>><?=$value['message']?></dd>
<dd class="pns">
<? if($value['touid']) { ?>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;pmid=<?=$value['pmid']?>&amp;touid=<?=$value['touid']?>&amp;daterange=<?=$value['daterange']?>" class="xi2">�鿴���� &rsaquo;</a>
<? } else { ?>
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;pmid=<?=$value['pmid']?>" class="xi2">�鿴���� &rsaquo;</a>
<? } ?>
</dd>
</dl>
<? } ?>
</div>
<? if($multi) { ?><div class="pgs cl"><?=$multi?></div><? } } else { ?>
<div class="emp">
��ǰû����Ӧ�Ķ���Ϣ��
</div>
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