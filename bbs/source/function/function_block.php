<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_block.php 10915 2010-05-18 04:48:02Z monkey $
 */

function block_script($script) {
	global $_G;
	$var = "block_class_$script";
	if(!isset($_G[$var])) {
		if(@include libfile("block/$script", 'class')) {
			$blockclass = "block_$script";
			$_G[$var] = new $blockclass();
		} else {
			$_G[$var] = null;
		}
	}
	return $_G[$var];
}


function block_get_batch($parameter) {
	global $_G;

	$bids = array();
	$in_bids = $parameter?explode(',', $parameter):array();
	foreach ($in_bids as $bid) {
		$bid = intval($bid);
		if($bid) $bids[$bid] = $bid;
	}
	if($bids) {
		$items = $prelist = array();
		$query = DB::query("SELECT * FROM ".DB::table('common_block_item')." WHERE bid IN (".dimplode($bids).")");
		while ($item = DB::fetch($query)) {
			if($item['enddate'] && $item['enddate'] < TIMESTAMP) {
				continue;
			} elseif(!$item['startdate'] || $item['startdate'] <= TIMESTAMP) {
				if (!empty($items[$item['bid']][$item['displayorder']])) {
					$prelist[$item['bid']][$item['displayorder']] = $item;
				}
				$items[$item['bid']][$item['displayorder']] = $item;
			}
		}
		$query = DB::query("SELECT * FROM ".DB::table('common_block')." WHERE bid IN (".dimplode($bids).")");
		while ($block = DB::fetch($query)) {
			if($items[$block['bid']]) {
				ksort($items[$block['bid']]);
				$newitem = array();
				if (!empty($prelist[$block['bid']])) {
					$countpre = 0;
					foreach ($items[$block['bid']] as $position => $item) {
						if (empty($prelist[$position])) {
							$newitem[$position + $countpre] = $item;
						} else {
							if ($item['itemtype']=='1') {
								if ($prelist[$position]['startdate'] > $item['startdate']) {
									$newitem[$position] = $prelist[$position];
								} else {
									$newitem[$position] = $item;
								}
							} else {
								$newitem[$position] = $prelist[$position];
								$newitem[$position+$countpre] = $item;
								$countpre++;
							}
						}
					}
					ksort($newitem);
				}
				$block['itemlist'] = empty($newitem) ? $items[$block['bid']] : $newitem;
			}
			$_G['block'][$block['bid']] = $block;
		}
	}
}


function block_display_batch($bid) {
	echo block_fetch_content($bid);
}

function block_fetch_content($bid, $isjscall=false, $forceupdate=false) {
	global $_G;
	static $allowmem;

	if($allowmem == null) {
		$allowmem = getglobal('setting/memory/diyblockoutput/enable') && memory('check');
	}

	$str = '';
	$block = empty($_G['block'][$bid])?array():$_G['block'][$bid];
	if(!$block) {
		return;
	}

	if($forceupdate) {
		block_updatecache($bid, true);
		$block = $_G['block'][$bid];
	} elseif($block['cachetime'] > 0 && $_G['timestamp'] - $block['dateline'] > $block['cachetime']) {
		if(empty($_G['blockupdate']) || $block['dateline'] < $_G['blockupdate']['dateline']) {
			$_G['blockupdate'] = array('bid'=>$bid, 'dateline'=>$block['dateline']);
		}
	}

	if($allowmem) {
		$str = memory('get', 'blockcache_'.$bid.'_'.($isjscall ? 'js' : 'htm'));
		if($str !== null) {
			return $str;
		}
	}

	if($isjscall) {
		if($block['summary']) $str .= $block['summary'];
		$str .= block_template($bid);
	} else {
		$classname = !empty($block['classname']) ? $block['classname'].' ' : '';
		$str .= "<div id=\"portal_block_$bid\" class=\"{$classname}block move-span\">";
		if($block['title']) $str .= $block['title'];
		$str .= '<div id="portal_block_'.$bid.'_content" class="content">';
		if($block['summary']) {
			$block['summary'] = stripslashes($block['summary']);
			$str .= "<div class=\"portal_block_summary\">$block[summary]</div>";
		}
		$str .= block_template($bid);
		$str .= '</div>';
		$str .= "</div>";
	}

	if($allowmem) {
		memory('set', 'blockcache_'.$bid.'_'.($isjscall ? 'js' : 'htm'), $str, getglobal('setting/memory/diyblockoutput/ttl'));
	}

	return $str;
}

function block_memory_clear($bid) {
	if(memory('check')) {
		memory('rm', 'blockcache_'.$bid.'');
		memory('rm', 'blockcache_'.$bid.'_htm');
		memory('rm', 'blockcache_'.$bid.'_js');
	}
}

function block_updatecache($bid, $forceupdate=false) {
	global $_G;

	if(!$forceupdate && discuz_process::islocked('block_update_cache', 5)) {
		return false;
	}
	block_memory_clear($bid);
	$block = empty($_G['block'][$bid])?array():$_G['block'][$bid];
	if(!$block) {
		return ;
	}
	$obj = block_script($block['script']);
	if($obj) {
		DB::update('common_block', array('dateline'=>TIMESTAMP), array('bid'=>$bid));
		$block['param'] = empty($block['param'])?array():unserialize($block['param']);
		$theclass = block_getclass($block['blockclass']);
		if($block['blockclass']=='portal_article') {
			$parameter = array('aids'=>array());
			$query = DB::query('SELECT aid FROM '.DB::table('portal_article_title')." WHERE bid='$bid'");
			while($value=DB::fetch($query)) {
				$parameter['aids'][] = intval($value['aid']);
			}
			$datalist = array();
			if(!empty($parameter['aids'])) {
				$bannedids = !empty($block['param']['bannedids']) ? explode(',', $block['param']['bannedids']) : array();
				if(!empty($bannedids)) {
					$parameter['aids'] = array_diff($parameter['aids'], $bannedids);
				}
				$bannedids = array_merge($bannedids, $parameter['aids']);
				$block['param']['bannedids'] = implode(',',$bannedids);

				$parameter['aids'] = implode(',', $parameter['aids']);
				$return = $obj->getdata($theclass['style'][$block['styleid']], $parameter);
				$datalist = $return['data'];
			}
			$return = $obj->getdata($theclass['style'][$block['styleid']], $block['param']);
			if($datalist) {
				$return['data'] = array_merge($datalist, $return['data']);
			}
		} else {
			$return = $obj->getdata($theclass['style'][$block['styleid']], $block['param']);
		}

		if($return['data'] === null) {
			$_G['block'][$block['bid']]['summary'] = $return['html'];
			DB::update('common_block', array('summary'=>daddslashes($return['html'])), array('bid'=>$bid));
		} else {
			$_G['block'][$block['bid']]['itemlist'] = block_updateitem($bid, $return['data']);
		}
	} else {
		DB::update('common_block', array('dateline'=>TIMESTAMP+999999, 'cachetime'=>0), array('bid'=>$bid));
	}
	discuz_process::unlock('block_update_cache');
}

function block_template($bid) {
	global $_G;

	$block = empty($_G['block'][$bid])?array():$_G['block'][$bid];

	$theclass = block_getclass($block['blockclass']);// for short
	$thestyle = $theclass['style'][$block['styleid']];// for short
	if(empty($block) || empty($theclass) || empty($thestyle)) {
		return ;
	}
	$template = '';
	if($block['itemlist']) {
		$template = $thestyle['template']['header'];
		$order = 0;
		foreach($block['itemlist'] as $blockitem) {
			$order++;

			if(isset($thestyle['template']['order'][$order])) {
				$theteamplte = $thestyle['template']['order'][$order];
			} else {
				$theteamplte = $thestyle['template']['loop'];
			}

			$blockitem['showstyle'] = !empty($blockitem['showstyle']) ? unserialize($blockitem['showstyle']) : array();
			$blockitem['fields'] = !empty($blockitem['fields']) ? $blockitem['fields'] : array();
			$blockitem['fields'] = is_array($blockitem['fields']) ? $blockitem['fields'] : unserialize($blockitem['fields']);
			$blockitem['picwidth'] = !empty($block['picwidth']) ? intval($block['picwidth']) : 'auto';
			$blockitem['picheight'] = !empty($block['picheight']) ? intval($block['picheight']) : 'auto';
			$blockitem['target'] = !empty($block['target']) ? ' target="_'.$block['target'].'"' : '';

			$fields = array('picwidth'=>array(), 'picheight'=>array(), 'target'=>array());
			$fields = array_merge($fields, $theclass['fields']);
			$searcharr = $replacearr = array();
			foreach($fields as $key=>$field) {
				$replacevalue = isset($blockitem[$key]) ? $blockitem[$key] : (isset($blockitem['fields'][$key]) ? $blockitem['fields'][$key] : '');
				$field['datatype'] = !empty($field['datatype']) ? $field['datatype'] : '';
				if($field['datatype'] == 'int') {// int
					$replacevalue = intval($replacevalue);
				} elseif($field['datatype'] == 'string') {
					$replacevalue = dhtmlspecialchars($replacevalue);
				} elseif($field['datatype'] == 'date') {
					$replacevalue = dgmdate($replacevalue, 'u');
				} elseif($field['datatype'] == 'title') {//title
					$replacevalue = stripslashes($replacevalue);
					$style = block_showstyle($blockitem['showstyle'], 'title');
					if($style) {
						$theteamplte = preg_replace('/title=[\'"]{title}[\'"]/', 'title="{title-title}"', $theteamplte);
						$theteamplte = preg_replace('/alt=[\'"]{title}[\'"]/', 'alt="{alt-title}"', $theteamplte);
						$searcharr[] = '{title-title}';
						$replacearr[] = $replacevalue;
						$searcharr[] = '{alt-title}';
						$replacearr[] = $replacevalue;
						$replacevalue = '<font style="'.$style.'">'.$replacevalue.'</font>';
					}
				} elseif($field['datatype'] == 'summary') {//summary
					$replacevalue = stripslashes($replacevalue);
					$style = block_showstyle($blockitem['showstyle'], 'summary');
					if($style) {
						$replacevalue = htmlspecialchars($replacevalue);
						$replacevalue = '<font style="'.$style.'">'.$replacevalue.'</font>';
					}
				} elseif($field['datatype'] == 'pic') {
					if($blockitem['picflag'] == '1') {
						$replacevalue = $_G['setting']['attachurl'].$replacevalue;
					} elseif ($blockitem['picflag'] == '2') {
						$replacevalue = $_G['setting']['ftp']['attachurl'].$replacevalue;
					}
					if($block['picwidth'] && $block['picheight'] && $block['picwidth'] != 'auto' && $block['picheight'] != 'auto') {
						if($blockitem['makethumb'] == 1) {
							$replacevalue = $_G['setting']['attachurl'].block_thumbpath($block, $blockitem);
						} elseif(!$_G['block_makethumb'] && !$blockitem['makethumb']) {
							require_once libfile('class/image');
							$image = new image();
							$thumbpath = block_thumbpath($block, $blockitem);
							$return = $image->Thumb($replacevalue, $thumbpath, $block['picwidth'], $block['picheight'], 2);
							if($return) {
								DB::update('common_block_item', array('makethumb'=>1), array('itemid'=>$blockitem['itemid']));
								$replacevalue = $_G['setting']['attachurl'].$thumbpath;
							} else {
								DB::update('common_block_item', array('makethumb'=>2), array('itemid'=>$blockitem['itemid']));
							}
							$_G['block_makethumb'] = true;
						}
					}
				}
				$searcharr[] = '{'.$key.'}';
				$replacearr[] = $replacevalue;
			}

			$template .= str_replace($searcharr, $replacearr, $theteamplte);
		}
		$template .= $thestyle['template']['footer'];
	}
	return $template;
}

function block_showstyle($showstyle, $key) {
	$style = '';
	if(!empty($showstyle["{$key}_b"])) {
		$style .= 'font-weight: 900;';
	}
	if(!empty($showstyle["{$key}_i"])) {
		$style .= 'font-style: italic;';
	}
	if(!empty($showstyle["{$key}_u"])) {
		$style .= 'text-decoration: underline;';
	}
	if(!empty($showstyle["{$key}_c"])) {
		$style .= 'color: '.$showstyle["{$key}_c"].';';
	}
	return $style;
}


function block_setting($script, $values = array()) {
	global $_G;

	$return = array();
	if(!$obj = block_script($script)) return $return;
	return block_makeform($obj->getsetting(), $values);
}

function block_makeform($blocksetting, $values){
	$return = array();
	foreach($blocksetting as $settingvar => $setting) {
		$varname = in_array($setting['type'], array('mradio', 'mcheckbox', 'select', 'mselect')) ?
			($setting['type'] == 'mselect' ? array('parameter['.$settingvar.'][]', $setting['value']) : array('parameter['.$settingvar.']', $setting['value']))
			: 'parameter['.$settingvar.']';
		$value = $values[$settingvar] != '' ? dstripslashes($values[$settingvar]) : $setting['default'];
		$setname = $setting['title'];
		$type = $setting['type'];
		$s = '';
		$langscript = substr($setname, 0, strpos($setname, '_'));
		$check = array();
		if($type == 'radio') {
			$value ? $check['true'] = "checked" : $check['false'] = "checked";
			$value ? $check['false'] = '' : $check['true'] = '';
			$s .= '<ul><li'.($check['true'] ? ' class="checked"' : '').'><input type="radio" class="pr" name="'.$varname.'" value="1" '.$check['true'].'>&nbsp;'.lang('core', 'yes').'</li>'.
				'<li'.($check['false'] ? ' class="checked"' : '').'><input type="radio" class="pr" name="'.$varname.'" value="0" '.$check['false'].'>&nbsp;'.lang('core', 'no').'</li></ul>';
		} elseif($type == 'text' || $type == 'password' || $type == 'number') {
			$s .= '<input name="'.$varname.'" value="'.dhtmlspecialchars($value).'" type="'.$type.'" class="px" />';
		} elseif($type == 'textarea') {
			$s .= '<textarea rows="4" name="'.$varname.'" cols="50" class="pt">'.dhtmlspecialchars($value).'</textarea>';
		} elseif($type == 'select') {
			$s .= '<select name="'.$varname[0].'" class="ps">';
			foreach($varname[1] as $option) {
				$selected = $option[0] == $value ? ' selected="selected"' : '';
				$s .= '<option value="'.$option[0].'"'.$selected.'>'.lang('block/'.$langscript, $option[1]).'</option>';
			}
			$s .= '</select>';
		} elseif($type == 'mradio') {
			if(is_array($varname)) {
				$radiocheck = array($value => ' checked');
				$s .= '<ul'.(empty($varname[2]) ?  ' class="pr"' : '').'>';
				foreach($varname[1] as $varary) {
					if(is_array($varary) && !empty($varary)) {
						$s .= '<li'.($radiocheck[$varary[0]] ? ' class="checked"' : '').'><input class="pr" type="radio" name="'.$varname[0].'" value="'.$varary[0].'"'.$radiocheck[$varary[0]].'>&nbsp;'.lang('block/'.$langscript, $varary[1]).'</li>';
					}
				}
				$s .= '</ul>';
			}
		} elseif($type == 'mcheckbox') {
			$s .= '<ul class="nofloat">';
			foreach($varname[1] as $varary) {
				if(is_array($varary) && !empty($varary)) {
					$checked = is_array($value) && in_array($varary[0], $value) ? ' checked' : '';
					$s .= '<li'.($checked ? ' class="checked"' : '').'><input class="pc" type="checkbox" name="'.$varname[0].'[]" value="'.$varary[0].'"'.$checked.'>&nbsp;'.lang('block/'.$langscript, $varary[1]).'</li>';
				}
			}
			$s .= '</ul>';
		} elseif($type == 'mselect') {
			$s .= '<select name="'.$varname[0].'" multiple="multiple" size="10" class="ps">';
			foreach($varname[1] as $option) {
				$selected = is_array($value) && in_array($option[0], $value) ? ' selected="selected"' : '';
				$s .= '<option value="'.$option[0].'"'.$selected.'>'.lang('block/'.$langscript, $option[1]).'</option>';
			}
			$s .= '</select>';
		} else {
			$s .= $type;
		}
		$return[] = array('title' => lang('block/'.$langscript, $setname), 'html' => $s);
	}
	return $return;
}
function block_updateitem($bid, $items=array()) {
	global $_G;
	$block = $_G['block'][$bid];
	if(!$block) {
		$block = DB::fetch_first('SELECT * FROM '.DB::table('common_block')." WHERE bid='$bid'");
		$_G['block'][$bid] = $block;
	}
	if(!$block) {
		return false;
	}
	$block['shownum'] = max($block['shownum'], 1);
	$showlist = array_fill(1, $block['shownum'], array());
	$archivelist = array();
	$prelist = array();
	$manualkeys = array();
	$autokeys = array();
	$modlist = array();
	$query = DB::query('SELECT * FROM '.DB::table('common_block_item')." WHERE bid='$bid'");
	while($value=DB::fetch($query)) {
		$key = $value['idtype'].'_'.$value['id'];
		if($value['itemtype'] == '1') {
			if($value['startdate'] > TIMESTAMP) {
				$prelist[] = $value;
			} elseif((!$value['startdate'] || $value['startdate'] <= TIMESTAMP) &&
				(!$value['enddate'] || $value['enddate'] > TIMESTAMP)) {
				$showlist[$value['displayorder']] = $value;
				$key = $value['idtype'].'_'.$value['id'];
				$manualkeys[$key] = true;
			} else {
				$archivelist[$value['itemid']] = $value;
			}
		} elseif($value['itemtype'] == '2') {
			$modlist[$key] = $value;
			$archivelist[$value['itemid']] = $value;
		} else {
			$archivelist[$value['itemid']] = $value;
			$autokeys[$key] = $value['itemid'];
		}
	}
	$itemindex = 0;
	for($i=1; $i<=$block['shownum']; $i++) {
		if($showlist[$i]) {
			if($block['picwidth'] && $block['picheight']) {
				if(file_exists($_G['setting']['attachdir'].block_thumbpath($block, $showlist[$i]))) {
					$showlist[$i]['makethumb'] = 1;
				} else {
					$showlist[$i]['makethumb'] = 0;
				}
			}
		} else {
			$key = $items[$itemindex]['idtype'].'_'.$items[$itemindex]['id'];
			while(!empty($manualkeys[$key])) {
				$itemindex++;
				$key = $items[$itemindex]['idtype'].'_'.$items[$itemindex]['id'];
			}
			if(!isset($items[$itemindex])) {
				break;
			}
			if(isset($modlist[$key])) {
				$modlist[$key]['displayorder'] = $i;
				$showlist[$i] = $modlist[$key];
			} else {
				$items[$itemindex]['displayorder'] = $i;
				if($block['picwidth'] && $block['picheight']) {
					if(file_exists($_G['setting']['attachdir'].block_thumbpath($block, $items[$itemindex]))) {
						$items[$itemindex]['makethumb'] = 1;
					} else {
						$items[$itemindex]['makethumb'] = 0;
					}
				}
				$items[$itemindex]['fields'] = serialize($items[$itemindex]['fields']);

				$key = $items[$itemindex]['idtype'].'_'.$items[$itemindex]['id'];
				if($autokeys[$key]) {
					$items[$itemindex]['itemid'] = $autokeys[$key];
					unset($archivelist[$autokeys[$key]]);
				}
				$showlist[$i] = $items[$itemindex];
			}
			$itemindex++;
		}
	}
	if($archivelist) {
		$delids = array_keys($archivelist);
		DB::query('DELETE FROM '.DB::table('common_block_item')." WHERE bid='$bid' AND itemid IN (".dimplode($delids).")");
		$inserts = array();
		foreach($archivelist as $value) {
			$value = daddslashes($value);
			$inserts[] = "('$value[bid]', '$value[id]', '$value[idtype]', '$value[title]',
				 '$value[url]', '$value[pic]', '$value[summary]', '$value[showstyle]', '$value[related]',
				 '$value[fields]', '$value[displayorder]', '$value[startdate]', '$value[enddate]', '$value[picflag]', '$value[makethumb]')";
		}
		DB::query('REPLACE INTO '.DB::table('common_block_item_archive')."(bid, id, idtype, title, url, pic, summary, showstyle, related, `fields`, displayorder, startdate, enddate, picflag, makethumb) VALUES ".implode(',', $inserts));
	}
	$inserts = $itemlist = array();
	$itemlist = array_merge($showlist, $prelist);
	foreach($itemlist as $value) {
		if($value) {
			$value = daddslashes($value);
			$inserts[] = "('$value[itemid]', '$bid', '$value[itemtype]', '$value[id]', '$value[idtype]', '$value[title]',
				 '$value[url]', '$value[pic]', '$value[summary]', '$value[showstyle]', '$value[related]',
				 '$value[fields]', '$value[displayorder]', '$value[startdate]', '$value[enddate]', '$value[picflag]', '$value[makethumb]')";
		}
	}
	if($inserts) {
		DB::query('REPLACE INTO '.DB::table('common_block_item')."(itemid, bid, itemtype, id, idtype, title, url, pic, summary, showstyle, related, `fields`, displayorder, startdate, enddate, picflag, makethumb) VALUES ".implode(',', $inserts));
	}

	$showlist = array_filter($showlist);
	return $showlist;
}

function block_thumbpath($block, $item) {
	global $_G;
	$hash = md5($item['pic'].'-'.$item['picflag'].':'.$block['picwidth'].'|'.$block['picheight']);
	return 'block/'.substr($hash, 0, 2).'/'.$hash.'.jpg';
}

function block_getclass($classname) {
	global $_G;
	if(!isset($_G['cache']['blockclass'])) {
		loadcache('blockclass');
	}
	list($c1, $c2) = explode('_', $classname);
	return is_array($_G['cache']['blockclass']) && isset($_G['cache']['blockclass'][$c1]['subs'][$classname]) ? $_G['cache']['blockclass'][$c1]['subs'][$classname] : array();
}

function block_getdiyurl($tplname, $diymod=false) {
	$mod = $id = $script = $url = '';
	$flag = 0;
	if (empty ($tplname)) {
		$flag = 2;
	} else {
		list($script,$tpl) = explode('/',$tplname);
		if (!empty($tpl)) {
			$arr = array();
			preg_match_all('/(.*)\_(\d{1,9})/', $tpl,$arr);
			$mod = empty($arr[1][0]) ? $tpl : $arr[1][0];
			$id = max(intval($arr[2][0]),0);
			switch ($mod) {
				case 'index' :
				case 'discuz' :
					$mod = 'index';
					break;
				case 'space_home' :
					$mod = 'space';
					break;
				case 'forumdisplay' :
					$flag = $id ? 0 : 1;
					$mod .= '&fid='.$id;
					break;
				case 'list' :
					$flag = $id ? 0 : 1;
					$mod .= '&catid='.$id;
					break;
				case 'portal_topic_content' :
					$mod = 'topic';
					$flag = $id ? 0 : 1;
					$mod .= '&topicid='.$id;
					break;
				case 'view' :
					$flag = $id ? 0 : 1;
					$mod .= '&aid='.$id;
					break;
				default :
					break;
			}
		}
		$url = empty($mod) ? '' : $script.'.php?mod='.$mod.($diymod?'&diy=yes':'');
	}
	return array('url'=>$url,'flag'=>$flag);
}

function block_clear() {
	$blocks = array();
	$query = DB::query("SELECT bid, blocktype FROM ".DB::table('common_block'));
	while($value = DB::fetch($query)) {
		if($value['blocktype'] == '0') {
			$blocks[$value['bid']] = 1;
		}
	}
	$query = DB::query("SELECT bid FROM ".DB::table('common_template_block'));
	while($value = DB::fetch($query)) {
		unset($blocks[$value['bid']]);
	}
	$delids = array_keys($blocks);
	if (!empty($delids)) {
		$delids = dimplode($delids);
		DB::query("DELETE FROM ".DB::table('common_block')." WHERE bid IN ($delids)");
		DB::query("DELETE FROM ".DB::table('common_block_item')." WHERE bid IN ($delids)");
		DB::query("OPTIMIZE TABLE ".DB::table('common_block'));
		DB::query("OPTIMIZE TABLE ".DB::table('common_block_item'));
	}
}

?>