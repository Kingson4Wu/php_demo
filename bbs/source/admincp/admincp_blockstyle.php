<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_blockstyle.php 9957 2010-05-05 10:30:13Z xupeng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();
$operation = in_array($operation, array('add', 'edit', 'delete')) ? $operation : 'list';

loadcache('blockclass');

shownav('portal', 'blockstyle');

if($operation=='add' || $operation=='edit') {

	if($operation=='edit') {
		showsubmenu('blockstyle',  array(
			array('list', 'blockstyle', 0),
			array('edit', 'blockstyle&operation=edit&blockclass='.$_GET['blockclass'].'&styleid='.$_GET['styleid'], 1)
		));
	} else {
		showsubmenu('blockstyle',  array(
			array('list', 'blockstyle', 0),
			array('add', 'blockstyle&operation=add', 1)
		));
	}

	include_once libfile('function/block');

	if(empty($_GET['blockclass'])) {

		$blockclass_sel = '<select name="blockclass">';
		$blockclass_sel .= '<option value="">'.cplang('blockstyle_blockclass_sel').'</option>';
		foreach($_G['cache']['blockclass'] as $key=>$value) {
			foreach($value['subs'] as $subkey=>$subvalue) {
				$blockclass_sel .= "<option value=\"$subkey\">$subvalue[name]</option>";
			}
		}
		$blockclass_sel .= '</select>';
		$adminscript = ADMINSCRIPT;
		$lang_blockclasssel = cplang('blockstyle_blockclass_sel');
		$lang_submit = cplang('submit');
		echo <<<BLOCKCLASSSEL
<form method="get" autocomplete="off" action="$adminscript">
	<div style="margin-top:8px;">
		<table cellspacing="3" cellpadding="3">
			<tr>
				<th>$lang_blockclasssel</th><td>$blockclass_sel</td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td>
					<input type="hidden" name="action" value="blockstyle" />
					<input type="hidden" name="operation" value="add" />
					<input type="submit" value="$lang_submit" class="btn" />
				</td>
			</tr>
		</table>
	</div>
</form>
BLOCKCLASSSEL;

	} else {

		showtips('blockstyle_add_tips');

		if(submitcheck('stylesubmit')) {
			$arr = array(
				'name' => $_POST['name'],
				'blockclass' => $_GET['blockclass'],
			);
			$arr['makethumb'] = strexists($_POST['template'], '{picwidth}') ? 1 : 0;
			$arr['getpic'] = strexists($_POST['template'], '{pic}') ? 1 : 0;
			$arr['getsummary'] = strexists($_POST['template'], '{summary}') ? 1 : 0;
			$arr['settarget'] = strexists($_POST['template'], '{target}') ? 1 : 0;

			$template = array();
			$template['header'] = $template['footer'] = $template['loop'] = '';
			$template['order'] = array();
			$match = array();
			if(preg_match('/^(.*?)(\[loop|\[order)/is', $_POST['template'], $match)) {
				$template['header'] = trim($match[1]);
			}
			$p1 = strrpos($_POST['template'], '[/loop]');
			$p2 = strrpos($_POST['template'], '[/order]');
			if ($p1 || $p2) {
				$template['footer'] = substr($_POST['template'], max($p1, $p2) + 8);
			}
			$match = array();
			if(preg_match('/\[loop\](.*?)\[\/loop]/is', $_POST['template'], $match)) {
				$template['loop'] = trim($match[1]);
			} else {
				$template['loop'] = $_POST['template'];
			}
			$match = array();
			if(preg_match_all('/\[order=(\d+)\](.*?)\[\/order]/is', $_POST['template'], $match)) {
				foreach($match[1] as $key=>$order) {
					$order = intval($order);
					$template['order'][$order] = trim($match[2][$key]);
				}
			}
			$template = dstripslashes($template);
			$template = serialize($template);
			$arr['hash'] = substr(md5($arr['blockclass'].'|'.$template), 8, 8);
			$arr['template'] = addslashes($template);

			if($_GET['styleid']) {
				$styleid = intval($_GET['styleid']);
				DB::update('common_block_style', $arr, array('styleid'=>$styleid));
				$msg = 'blockstyle_edit_succeed';
			} else {
				$styleid = DB::insert('common_block_style', $arr, true);
				$msg = 'blockstyle_create_succeed';
			}
			require_once libfile('function/cache');
			updatecache('blockclass');
			cpmsg($msg, 'action=blockstyle&operation=edit&blockclass='.$_GET[blockclass].'&styleid='.$styleid.'&preview='.($_POST['preview']?'1':'0'), 'succeed');
		}

		if($_GET['styleid']) {
			$_GET['styleid'] = intval($_GET['styleid']);
			$thestyle = DB::fetch_first('SELECT * FROM '.DB::table('common_block_style')." WHERE styleid = '$_GET[styleid]'");
			if(!$thestyle) {
				cpmsg('blockstyle_not_found!');
			}
			$template = unserialize($thestyle['template']);
			$thestyle['template'] = $template['header'];
			$thestyle['template'] .= "\n[loop]\n{$template[loop]}\n[/loop]";
			if(!empty($template['order']) && is_array($template['order'])) {
				foreach($template['order'] as $key=>$value) {
					$thestyle['template'] .= "\n[order={$key}]\n{$value}\n[/order]";
				}
			}
			$thestyle['template'] .= $template['footer'];

			$_GET['blockclass'] = $thestyle['blockclass'];
		} else {
			$_GET['styleid'] = 0;
			$thestyle = array(
				'template' => "<div class=\"module cl\">\n<ul>\n[loop]\n\t<li><a href=\"{url}\"{target}>{title}</a></li>\n[/loop]\n</ul>\n</div>"
			);
		}

		$theclass = block_getclass($_GET['blockclass']);

		if($preview) {
			echo '<h4 style="margin-bottom:15px;">'.lang('preview').'</h4>'.$preview;
		}

		showformheader('blockstyle&operation='.$operation.'&blockclass='.$_GET['blockclass'].'&styleid='.$_GET['styleid']);
		jsinsertunit();
		showtableheader();
		if($_GET['styleid']) {
			showtitle('blockstyle_add_editstyle');
		} else {
			showtitle('blockstyle_add_addstyle');
		}
		showsetting('blockstyle_name', 'name', $thestyle['name'], 'text');
		showtablefooter();

		$template = '';
		foreach($theclass['fields'] as $key=>$value) {
			if($value['name']) {
				$template .= $value['name']. ': <a href="###" onclick="insertunit(\'{'.$key.'}\')">{'.$key.'}</a>';
			}
		}
		$template .= '<br />';
		$template .= cplang('blockstyle_add_loop').': <a href="###" onclick="insertunit(\'[loop]\n\n[/loop]\')">[loop]...[/loop]</a>';
		$template .= cplang('blockstyle_add_order').': <a href="###" onclick="insertunit(\'[order=N]\n\n[/order]\')">[order=N]...[/order]</a>';
		$template .= cplang('blockstyle_add_urltitle').': <a href="###" onclick=\'insertunit("<a href=\"{url}\"{target}>{title}</a>")\'>&lt;a href=...</a>';
		$template .= cplang('blockstyle_add_picthumb').': <a href="###" onclick=\'insertunit("<img src=\"{pic}\" width=\"{picwidth}\" height=\"{picheight}\" />")\'>&lt;img src=...&gt;</a>';
		$template .= '</div><br />';
		$template .= '<textarea cols="100" rows="5" id="jstemplate" name="template" style="width: 95%;" onkeyup="textareasize(this)">'.$thestyle['template'].'</textarea>';
		$template .= '<input type="hidden" name="preview" value="0" /><input type="hidden" name="stylesubmit" value="1" />';
		$template .= '<br /><!--input type="button" class="btn" onclick="this.form.preview=\'1\';this.form.submit()" value="'.$lang['preview'].'">&nbsp; &nbsp;--><input type="submit" class="btn" value="'.$lang['submit'].'"></div><br /><br />';
		echo '<div class="colorbox">';
		echo '<div class="extcredits">';
		echo $template;
		echo '</div>';

		showformfooter();
	}

} elseif($operation=='delete') {

	$_GET['styleid'] = intval($_GET['styleid']);
	$thestyle = DB::fetch_first('SELECT * FROM '.DB::table('common_block_style')." WHERE styleid='$_GET[styleid]'");
	if(empty($thestyle)) {
		cpmsg('blockstyle_not_found', 'action=blockstyle', 'error');
	}
	$styles = array();
	$query = DB::query('SELECT * FROM '.DB::table('common_block_style')." WHERE blockclass='$thestyle[blockclass]' AND styleid != '$_GET[styleid]'");
	while($value=DB::fetch($query)) {
		$styles[$value['styleid']] = $value;
	}
	if(empty($styles)) {
		cpmsg('blockstyle_should_be_kept', 'action=blockstyle', 'error');
	}

	if(submitcheck('deletesubmit')) {
		$_POST['moveto'] = intval($_POST['moveto']);
		$newstyle = DB::fetch_first('SELECT * FROM '.DB::table('common_block_style')." WHERE styleid='$_POST[moveto]'");
		if($newstyle['blockclass'] != $thestyle['blockclass']) {
			cpmsg('blockstyle_blockclass_not_match', 'action=blockstyle', 'error');
		}
		DB::query('UPDATE '.DB::table('common_block')." SET styleid='$_POST[moveto]' WHERE styleid='$_GET[styleid]'");
		DB::query('DELETE FROM '.DB::table('common_block_style')." WHERE styleid = '$_GET[styleid]'");
		updatecache('blockclass');
		cpmsg('blockstyle_delete_succeed', 'action=blockstyle', 'succeed');
	}

	$value = DB::fetch_first('SELECT * FROM '.DB::table('common_block')." WHERE styleid = '$_GET[styleid]' LIMIT 1");
	if($value) {
		showtips('blockstyle_delete_tips');
		showformheader('blockstyle&operation=delete&styleid='.$_GET['styleid']);
		showtableheader();
		$movetoselect = '<select name="moveto">';
		foreach($styles as $key=>$value) {
			$movetoselect .= "<option value=\"$key\">$value[name]</option>";
		}
		$movetoselect .= '</select>';
		showsetting('blockstyle_moveto', '', '', $movetoselect);
		showsubmit('deletesubmit');
		showtablefooter();
		showformfooter();

	} else {
		DB::query('DELETE FROM '.DB::table('common_block_style')." WHERE styleid = '$_GET[styleid]'");
		updatecache('blockclass');
		cpmsg('blockstyle_delete_succeed', 'action=blockstyle', 'succeed');
	}

} else {

	showsubmenu('blockstyle',  array(
		array('list', 'blockstyle', 1),
		array('add', 'blockstyle&operation=add', 0)
	));

	$mpurl = ADMINSCRIPT.'?action=blockstyle';
	$intkeys = array('styleid');
	$strkeys = array('blockclass');
	$randkeys = array();
	$likekeys = array('name');
	$results = getwheres($intkeys, $strkeys, $randkeys, $likekeys);
	$wherearr = $results['wherearr'];
	$mpurl .= '&'.implode('&', $results['urls']);

	$wheresql = empty($wherearr)?'1':implode(' AND ', $wherearr);

	$orders = getorders(array('blockclass'), 'styleid');
	$ordersql = $orders['sql'];
	if($orders['urls']) $mpurl .= '&'.implode('&', $orders['urls']);
	$orderby = array($_GET['orderby']=>' selected');
	$ordersc = array($_GET['ordersc']=>' selected');

	$perpage = empty($_GET['perpage'])?0:intval($_GET['perpage']);
	if(!in_array($perpage, array(10,20,50,100))) $perpage = 20;
	$perpages = array($perpage=>' selected');
	$mpurl .= '&perpage='.$perpage;

	$searchlang = array();
	$keys = array('search', 'likesupport', 'resultsort', 'defaultsort', 'orderdesc', 'orderasc', 'perpage_10', 'perpage_20', 'perpage_50', 'perpage_100',
	'blockstyle_id', 'blockstyle_name', 'blockstyle_blockclass');
	foreach ($keys as $key) {
		$searchlang[$key] = cplang($key);
	}
	$blockclass_sel = '<select name="blockclass">';
	$blockclass_sel .= '<option value="">'.cplang('blockstyle_blockclass_sel').'</option>';
	foreach($_G['cache']['blockclass'] as $key=>$value) {
		foreach($value['subs'] as $subkey=>$subvalue) {
			$selected = (!empty($_GET['blockclass']) && $subkey == $_GET['blockclass'] ? ' selected' : '');
			$blockclass_sel .= "<option value=\"$subkey\"$selected>$subvalue[name]</option>";
		}
	}
	$blockclass_sel .= '</select>';
	$adminscript = ADMINSCRIPT;
	echo <<<SEARCH
<form method="get" autocomplete="off" action="$adminscript">
	<div style="margin-top:8px;">
		<table cellspacing="3" cellpadding="3">
			<tr>
				<th>$searchlang[blockstyle_id]</th><td><input type="text" name="styleid" value="$_GET[styleid]"></td>
				<th>$searchlang[blockstyle_name]*</th><td><input type="text" name="name" value="$_GET[name]">*$searchlang[likesupport]</td>
			</tr>
			<tr>
				<th>$searchlang[blockstyle_blockclass]</th><td colspan="3">$blockclass_sel</td>
			</tr>
			<tr>
				<th>$searchlang[resultsort]</th>
				<td colspan="3">
					<select name="orderby">
					<option value="styleid">$searchlang[defaultsort]</option>
					<option value="blockclass"$orderby[blockclass]>$searchlang[blockstyle_blockclass]</option>
					</select>
					<select name="ordersc">
					<option value="desc"$ordersc[desc]>$searchlang[orderdesc]</option>
					<option value="asc"$ordersc[asc]>$searchlang[orderasc]</option>
					</select>
					<select name="perpage">
					<option value="10"$perpages[10]>$searchlang[perpage_10]</option>
					<option value="20"$perpages[20]>$searchlang[perpage_20]</option>
					<option value="50"$perpages[50]>$searchlang[perpage_50]</option>
					<option value="100"$perpages[100]>$searchlang[perpage_100]</option>
					</select>
					<input type="hidden" name="action" value="blockstyle">
					<input type="submit" name="searchsubmit" value="$searchlang[search]" class="btn">
				</td>
			</tr>
		</table>
	</div>
</form>
SEARCH;

	$start = ($page-1)*$perpage;

	showformheader('blockstyle');
	showtableheader('blockstyle_list');
	showsubtitle(array('blockstyle_name', 'blockstyle_blockclass', 'operation'));

	$multipage = '';
	$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_block_style')." WHERE $wheresql"), 0);
	if($count) {
		include_once libfile('function/block');
		$query = DB::query("SELECT * FROM ".DB::table('common_block_style')." WHERE $wheresql $ordersql LIMIT $start,$perpage");
		while($value = DB::fetch($query)) {
			$theclass = block_getclass($value['blockclass']);
			list($c1, $c2) = explode('_', $value['blockclass']);
			showtablerow('', array('class=""', 'class=""', 'class="td28"'), array(
				$value['name'],
				$theclass['name'],
				"<a href=\"".ADMINSCRIPT."?action=blockstyle&operation=edit&blockclass=$value[blockclass]&styleid=$value[styleid]\">".cplang('blockstyle_edit')."</a>&nbsp;&nbsp;".
				"<a href=\"".ADMINSCRIPT."?action=blockstyle&operation=delete&styleid=$value[styleid]\">".cplang('blockstyle_delete')."</a>"
			));
		}
		$multipage = multi($count, $perpage, $page, $mpurl);
	}

	showsubmit('', '', '', '', $multipage);
	showtablefooter();
	showformfooter();

}


function jsinsertunit() {
?>
<script type="text/JavaScript">
	function isUndefined(variable) {
		return typeof variable == 'undefined' ? true : false;
	}

	function insertunit(text, obj) {
		if(!obj) {
			obj = 'jstemplate';
		}
		$(obj).focus();
		if(!isUndefined($(obj).selectionStart)) {
			var opn = $(obj).selectionStart + 0;
			$(obj).value = $(obj).value.substr(0, $(obj).selectionStart) + text + $(obj).value.substr($(obj).selectionEnd);
			$(obj).selectionStart = opn + strlen(text);
			$(obj).selectionEnd = opn + strlen(text);
		} else if(document.selection && document.selection.createRange) {
			var sel = document.selection.createRange();
			sel.text = text.replace(/\r?\n/g, '\r\n');
			sel.moveStart('character', -strlen(text));
		} else {
			$(obj).value += text;
		}
	}
</script>
<?
}

?>