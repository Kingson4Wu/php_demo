<? if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('login');
0
|| checktplrefresh('./template/default/member/login.htm', './template/default/common/seccheck.htm', 1380628658, '1', './data/template/1_1_member_login.tpl.php', './template/default', 'member/login')
;?><? include template('common/header'); if(!empty($message)) { ?>
<?=$ucsynlogin?>
<script type="text/javascript" reload="1">
<? if($message == 2) { ?>
hideWindow('login');
showWindow('register', '<?=$location?>');
<? } elseif($message == 1) { ?>
display('main_messaqge');
display('layer_login');
display('layer_message');
<? if($_G['groupid'] == 8) { ?>
$('messageleft').innerHTML = '<p>��ӭ������ <?=$usergroups?> <? echo addslashes($_G['username']); ?></p><p>�����ʺŴ��ڷǼ���״̬</p>';
<? } else { ?>
$('messageleft').innerHTML = '<p>��ӭ������ <?=$usergroups?> <? echo addslashes($_G['username']); ?></p>';
<? } if(!empty($_G['gp_floatlogin'])) { ?>
$('messageright').innerHTML = '<a href="javascript:;" onclick="location.reload()">���ҳ��û����Ӧ���������ˢ��</a>';
setTimeout('location.href = location.href', <?=$mrefreshtime?>);
<? } else { ?><?php $dreferer = str_replace('&amp;', '&', dreferer()); ?>$('messageright').innerHTML = '<a href="<?=$dreferer?>">���ڽ�ת���¼ǰҳ��</a>';
setTimeout("window.location.href='<?=$dreferer?>'", <?=$mrefreshtime?>);
<? } } ?>
setMenuPosition('fwin_login', 'fwin_login', '00');
</script>
<? } else { if(empty($_G['gp_infloat'])) { ?>
<div id="pt" class="wp"><a href="index.php" class="nvhm"><?=$_G['setting']['bbname']?></a> &rsaquo; <?=$navigation?></div>
<div  id="ct" class="wp w cl">
<div class="mn mw">
<div class="ch">
<label class="wx">��¼</label>
</div>
<? } ?>

<div class="blr" id="main_messaqge">
<div id="layer_login">
<h3 class="flb">
<em id="returnmessage"><? if(!empty($_G['gp_infloat'])) { ?>�û���¼<? } ?></em>
<span><? if(!empty($_G['gp_infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('login')" title="�ر�">�ر�</a><? } ?></span>
</h3>
<?php if(!empty($_G['setting']['pluginhooks']['logging_top'])) echo $_G['setting']['pluginhooks']['logging_top']; ?>
<form method="post" autocomplete="off" name="login" id="loginform" class="cl" onsubmit="<? if($_G['setting']['pwdsafety']) { ?>pwmd5('password3');<? } ?>pwdclear = 1;ajaxpost('loginform', 'returnmessage', 'returnmessage', 'onerror');return false;" action="member.php?mod=logging&amp;action=login&amp;loginsubmit=yes<? if(!empty($_G['gp_infloat'])) { ?>&amp;floatlogin=yes<? } ?>">
<div class="c">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="referer" value="<?=$_G['referer']?>" />
<div class="lgfm bm nlf">

<? if($invite) { ?>
<span>
<label><em>�Ƽ���:</em><a href="home.php?mod=space&amp;uid=<?=$invite['uid']?>" target="_blank"><?=$invite['username']?></a></label>
</span>
<? } if($_G['setting']['autoidselect']) { ?>
<div class="ftid sipt lpsw" id="account">
<label for="username">�ʡ��š���</label>
<input type="text" name="username" id="username" autocomplete="off" onblur="$('username_pmenu').style.display='none';" size="36" class="txt" tabindex="1" value="<?=$username?>" />
</div>
<? } else { ?>
<div class="ftid sipt" id="account">
<select name="loginfield" style="float: left;" width="61" id="loginfield">
<option value="username">�û���</option>
<option value="uid">UID</option>
<option value="email">Email</option>
</select>
<input type="text" name="username" id="username" autocomplete="off" size="36" class="txt" tabindex="1" value="<?=$username?>" />
</div>
<? } ?>
<p class="sipt lpsw">
<label for="password3">�ܡ��롡��</label>
<input type="password" id="password3" name="password" onfocus="clearpwd()" onkeypress="detectCapsLock(event, this)" size="36" class="txt" tabindex="1" />
</p>

<div class="ftid sltp">
<select id="questionid" width="234" name="questionid" change="if($('questionid').value > 0) {$('answer').style.display='';} else {$('answer').style.display='none';}">
<option value="0">��ȫ����</option>
<option value="1">ĸ�׵�����</option>
<option value="2">үү������</option>
<option value="3">���׳����ĳ���</option>
<option value="4">������һλ��ʦ������</option>
<option value="5">�����˼�������ͺ�</option>
<option value="6">����ϲ���Ĳ͹�����</option>
<option value="7">��ʻִ�յ������λ����</option>
</select>
</div>
<p><input type="text" name="answer" id="answer" style="display:none" autocomplete="off" size="36" class="sipt" tabindex="1" /></p>

<div id="seccodelayer">
<? if($secqaacheck || $seccodecheck) { ?><?
$sectpl = <<<EOF
<label><em><sec></em><sec></label><label><em style="height:30px">&nbsp;</em><sec></label>
EOF;
?><?php $sechash = 'S'.random(4);
$sectpl = !empty($sectpl) ? explode("<sec>", $sectpl) : array('<br />',': ','<br />','');
$sectpldefault = $sectpl;
$sectplqaa = str_replace('<hash>', 'qaa'.$sechash, $sectpldefault);
$sectplcode = str_replace('<hash>', 'code'.$sechash, $sectpldefault); ?><?
$__STATICURL = STATICURL;$seccheckhtml = <<<EOF

<input name="sechash" type="hidden" value="{$sechash}" />

EOF;
 if($sectpl) { if($secqaacheck) { 
$seccheckhtml .= <<<EOF

{$sectplqaa['0']}��֤�ʴ�{$sectplqaa['1']}<input name="secanswer" id="secqaaverify_{$sechash}" type="text" autocomplete="off" style="width:100px" class="txt px vm" onblur="checksec('qaa', '{$sechash}')" tabindex="1" />
<a href="javascript:;" onclick="updatesecqaa('{$sechash}');doane(event);" class="xi2">��һ��</a>
<span id="checksecqaaverify_{$sechash}"><img src="{$__STATICURL}image/common/none.gif" width="16" height="16" class="vm" /></span>
{$sectplqaa['2']}<span id="secqaa_{$sechash}"></span>
<script type="text/javascript" reload="1">updatesecqaa('{$sechash}');</script>
{$sectplqaa['3']}

EOF;
 } if($seccodecheck) { 
$seccheckhtml .= <<<EOF

{$sectplcode['0']}��֤��{$sectplcode['1']}<input name="seccodeverify" id="seccodeverify_{$sechash}" type="text" autocomplete="off" style="width:100px" class="txt px vm" onblur="checksec('code', '{$sechash}')" tabindex="1" />
<a href="javascript:;" onclick="updateseccode('{$sechash}');doane(event);" class="xi2">��һ��</a>
<span id="checkseccodeverify_{$sechash}"><img src="{$__STATICURL}image/common/none.gif" width="16" height="16" class="vm" /></span>
{$sectplcode['2']}<span id="seccode_{$sechash}"></span>
<script type="text/javascript" reload="1">updateseccode('{$sechash}');</script>
{$sectplcode['3']}

EOF;
 } } 
$seccheckhtml .= <<<EOF


EOF;
?>
<? if(empty($secreturn)) { ?><?=$seccheckhtml?><? } } ?>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['logging_input'])) echo $_G['setting']['pluginhooks']['logging_input']; ?>
</div>
<div class="lgf minf">
<h4>û���ʺţ�<a href="member.php?mod=register" onclick="hideWindow('login');showWindow('register', this.href);return false;" title="ע���ʺ�"><?=$_G['setting']['reglinkname']?></a></h4>
<p><a href="javascript:;" onclick="display('layer_login');display('layer_lostpw');" title="�һ�����">�һ�����</a></p>
<? if(!$_G['setting']['bbclosed']) { ?><p><a href="javascript:;" onclick="ajaxget('member.php?mod=clearcookies&formhash=<?=FORMHASH?>', 'returnmessage', 'returnmessage');return false;" title="����ۼ�">����ۼ�</a></p><? } ?>
<?php if(!empty($_G['setting']['pluginhooks']['logging_side'])) echo $_G['setting']['pluginhooks']['logging_side']; ?>
</div>
</div>
<p class="fsb cl">
<? if($_G['setting']['sitemessage']['login']) { ?><a href="javascript:;" id="custominfo_login" class="y"><img src="<?=IMGDIR?>/info_small.gif" alt="����" /></a><? } ?>
<button class="pn pnc" type="submit" name="loginsubmit" value="true" tabindex="1"><span>��¼</span></button>
<label for="cookietime"><input type="checkbox" class="pc" name="cookietime" id="cookietime" tabindex="1" value="2592000" <?=$cookietimecheck?> /> ��ס�ҵĵ�¼״̬</label>
</p>
</form>
</div>
<div id="layer_lostpw" style="display: none;">
<h3 class="flb">
<em id="returnmessage3">�һ�����</em>
<span><? if(!empty($_G['gp_infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('login')" title="�ر�">�ر�</a><? } ?></span>
</h3>
<form method="post" autocomplete="off" id="lostpwform" class="cl" onsubmit="ajaxpost('lostpwform', 'returnmessage3', 'returnmessage3', 'onerror');return false;" action="member.php?mod=lostpasswd&amp;lostpwsubmit=yes&amp;infloat=yes">
<div class="c">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="handlekey" value="lostpwform" />
<div class="lgfm bm">
<label><em>�û���:</em><input type="text" name="username" size="25" value=""  tabindex="1" class="txt" /></label>
<label><em>Email:</em><input type="text" name="email" size="25" value=""  tabindex="1" class="txt" /></label>
</div>
<div class="lgf minf">
<h4>û���ʺţ�<a href="member.php?mod=register" onclick="hideWindow('login');showWindow('register', this.href);return false;" title="ע���ʺ�"><?=$_G['setting']['reglinkname']?></a></h4>
<p><a href="javascript:;" onclick="display('layer_login');display('layer_lostpw');">���ص�¼</a></p>
</div>
</div>
<p class="fsb cl">
<em>&nbsp;</em>
<button class="pn pnc" type="submit" name="lostpwsubmit" value="true" tabindex="100"><span>�ύ</span></button>
</p>
</form>
</div>
</div>

<div id="layer_message"<? if(empty($_G['gp_infloat'])) { ?> class="f_c blr nfl"<? } ?> style="display: none;">
<h3 class="flb">
<? if(!empty($_G['gp_infloat'])) { ?>
<em>�û���¼</em>
<span><a href="javascript:;" class="flbc" onclick="hideWindow('login')" title="�ر�">�ر�</a></span>
<? } ?>
</h3>
<div class="c"><div class="alert_right">
<div id="messageleft"></div>
<p class="alert_btnleft" id="messageright"></p>
</div>
</div>

<script src="<?=$_G['setting']['jspath']?>forum_md5.js?<?=VERHASH?>" type="text/javascript" reload="1"></script>
<script type="text/javascript" reload="1">
hideWindow('register');
var pwdclear = 0;
function initinput_login() {
document.body.focus();
$('loginform').username.focus();
<? if($_G['setting']['autoidselect']) { ?>
showPrompt('username', 'focus', 'UID/�û���/Email', 0);
<? } else { ?>
simulateSelect('loginfield');
<? } ?>
simulateSelect('questionid');
}
if(BROWSER.ie && BROWSER.ie < 7) {
setTimeout('initinput_login()', 500);
} else {
initinput_login();
}
<? if($_G['setting']['sitemessage']['login']) { ?>
showPrompt('custominfo_login', 'click', '<? echo trim($_G['setting']['sitemessage']['login'][array_rand($_G['setting']['sitemessage']['login'])]); ?>', <?=$_G['setting']['sitemessage']['time']?>);
<? } if($_G['setting']['pwdsafety']) { ?>
var pwmd5log = new Array();
function pwmd5() {
numargs = pwmd5.arguments.length;
for(var i = 0; i < numargs; i++) {
if(!pwmd5log[pwmd5.arguments[i]] || $(pwmd5.arguments[i]).value.length != 32) {
pwmd5log[pwmd5.arguments[i]] = $(pwmd5.arguments[i]).value = hex_md5($(pwmd5.arguments[i]).value);
}
}
}
<? } ?>

function clearpwd() {
if(pwdclear) {
$('password3').value = '';
}
pwdclear = 0;
}

function succeedhandle_lostpwform(url, msg) {
showDialog(msg, 'notice');
hideWindow('login');
}
</script>
<? } ?><?php updatesession(); if(empty($_G['gp_infloat'])) { ?>
</div></div>
</div>
<? } include template('common/footer'); ?>