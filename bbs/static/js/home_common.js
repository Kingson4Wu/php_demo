/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: home_common.js 10191 2010-05-07 07:32:24Z zhengqingpeng $
*/

var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);
var note_step = 0;
var note_oldtitle = document.title;
var note_timer;

function $(id) {
	return document.getElementById(id);
}

function addSort(obj) {
	if (obj.value == 'addoption') {
		showWindow('addoption', 'home.php?mod=spacecp&ac=blog&op=addoption&handlekey=addoption&oid='+obj.id);
 	}
}

function addOption(sid, aid) {
	var obj = $(aid);
	var newOption = $(sid).value;
	$(sid).value = "";
	if (newOption!=null && newOption!='') {
		var newOptionTag=document.createElement('option');
		newOptionTag.text=newOption;
		newOptionTag.value="new:" + newOption;
		try {
			obj.add(newOptionTag, obj.options[0]);
		} catch(ex) {
			obj.add(newOptionTag, obj.selecedIndex);
		}
		obj.value="new:" + newOption;
	} else {
		obj.value=obj.options[0].value;
	}
}

function checkAll(form, name) {
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.name.match(name)) {
			e.checked = form.elements['chkall'].checked;
		}
	}
}

function cnCode(str) {
	return BROWSER.ie && document.charset == 'utf-8' ? encodeURIComponent(str) : str;
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
					return true;
			}
		}
	}
	return false;
}

function strlen(str) {
	return (is_ie && str.indexOf('\n') != -1) ? str.replace(/\r?\n/g, '_').length : str.length;
}

function getExt(path) {
	return path.lastIndexOf('.') == -1 ? '' : path.substr(path.lastIndexOf('.') + 1, path.length).toLowerCase();
}

function resizeImg(id,size) {
	var theImages = $(id).getElementsByTagName('img');
	for (i=0; i<theImages.length; i++) {
		theImages[i].onload = function() {
			if (this.width > size) {
				this.style.width = size + 'px';
				if (this.parentNode.tagName.toLowerCase() != 'a') {
					var zoomDiv = document.createElement('div');
					this.parentNode.insertBefore(zoomDiv,this);
					zoomDiv.appendChild(this);
					zoomDiv.style.position = 'relative';
					zoomDiv.style.cursor = 'pointer';

					this.title = '���ͼƬ�����´�����ʾԭʼ�ߴ�';

					var zoom = document.createElement('img');
					zoom.src = 'image/zoom.gif';
					zoom.style.position = 'absolute';
					zoom.style.marginLeft = size -28 + 'px';
					zoom.style.marginTop = '5px';
					this.parentNode.insertBefore(zoom,this);

					zoomDiv.onmouseover = function() {
						zoom.src = 'image/zoom_h.gif';
					};
					zoomDiv.onmouseout = function() {
						zoom.src = 'image/zoom.gif';
					};
					zoomDiv.onclick = function() {
						window.open(this.childNodes[1].src);
					};
				}
			}
		}
	}
}

function zoomTextarea(id, zoom) {
	zoomSize = zoom ? 10 : -10;
	obj = $(id);
	if(obj.rows + zoomSize > 0 && obj.cols + zoomSize * 3 > 0) {
		obj.rows += zoomSize;
		obj.cols += zoomSize * 3;
	}
}

function ischeck(id, prefix) {
	form = document.getElementById(id);
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.name.match(prefix) && e.checked) {
			if(confirm("��ȷ��Ҫִ�б�������")) {
				return true;
			} else {
				return false;
			}
		}
	}
	alert('��ѡ��Ҫ�����Ķ���');
	return false;
}

function copyRow(tbody) {
	var add = false;
	var newnode;
	if($(tbody).rows.length == 1 && $(tbody).rows[0].style.display == 'none') {
		$(tbody).rows[0].style.display = '';
		newnode = $(tbody).rows[0];
	} else {
		newnode = $(tbody).rows[0].cloneNode(true);
		add = true;
	}
	tags = newnode.getElementsByTagName('input');
	for(i in tags) {
		if(tags[i].name == 'pics[]') {
			tags[i].value = 'http://';
		}
	}
	if(add) {
		$(tbody).appendChild(newnode);
	}
}

function delRow(obj, tbody) {
	if($(tbody).rows.length == 1) {
		var trobj = obj.parentNode.parentNode;
		tags = trobj.getElementsByTagName('input');
		for(i in tags) {
			if(tags[i].name == 'pics[]') {
				tags[i].value = 'http://';
			}
		}
		trobj.style.display='none';
	} else {
		$(tbody).removeChild(obj.parentNode.parentNode);
	}
}

function insertWebImg(obj) {
	if(checkImage(obj.value)) {
		insertImage(obj.value);
		obj.value = 'http://';
	} else {
		alert('ͼƬ��ַ����ȷ');
	}
}

function checkFocus(target) {
	var obj = $(target);
	if(!obj.hasfocus) {
		obj.focus();
	}
}
function insertImage(text) {
	text = "\n[img]" + text + "[/img]\n";
	insertContent('message', text);
}

function insertContent(target, text) {
	var obj = $(target);
	selection = document.selection;
	checkFocus(target);
	if(!isUndefined(obj.selectionStart)) {
		var opn = obj.selectionStart + 0;
		obj.value = obj.value.substr(0, obj.selectionStart) + text + obj.value.substr(obj.selectionEnd);
	} else if(selection && selection.createRange) {
		var sel = selection.createRange();
		sel.text = text;
		sel.moveStart('character', -strlen(text));
	} else {
		obj.value += text;
	}
}

function checkImage(url) {
	var re = /^http\:\/\/.{5,200}\.(jpg|gif|png)$/i;
	return url.match(re);
}

function quick_validate(obj) {
	if($('seccode')) {
		var code = $('seccode').value;
		var x = new Ajax();
		x.get('cp.php?ac=common&op=seccode&code=' + code, function(s){
			s = trim(s);
			if(s != 'succeed') {
				alert(s);
				$('seccode').focus();
		   		return false;
			} else {
				obj.form.submit();
				return true;
			}
		});
	} else {
		obj.form.submit();
		return true;
	}
}

function trim(str) {
	var re = /\s*(\S[^\0]*\S)\s*/;
	re.exec(str);
	return RegExp.$1;
}
function stopMusic(preID, playerID) {
	var musicFlash = preID.toString() + '_' + playerID.toString();
	if($(musicFlash)) {
		$(musicFlash).SetVariable('closePlayer', 1);
	}
}
function showFlash(host, flashvar, obj, shareid) {
	var flashAddr = {
		'youku.com' : 'http://player.youku.com/player.php/sid/FLASHVAR=/v.swf',
		'ku6.com' : 'http://player.ku6.com/refer/FLASHVAR/v.swf',
		'youtube.com' : 'http://www.youtube.com/v/FLASHVAR',
		'5show.com' : 'http://www.5show.com/swf/5show_player.swf?flv_id=FLASHVAR',
		'sina.com.cn' : 'http://vhead.blog.sina.com.cn/player/outer_player.swf?vid=FLASHVAR',
		'sohu.com' : 'http://v.blog.sohu.com/fo/v4/FLASHVAR',
		'mofile.com' : 'http://tv.mofile.com/cn/xplayer.swf?v=FLASHVAR',
		'music' : 'FLASHVAR',
		'flash' : 'FLASHVAR'
	};
	var flash = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="480" height="400">'
	    + '<param name="movie" value="FLASHADDR" />'
	    + '<param name="quality" value="high" />'
	    + '<param name="bgcolor" value="#FFFFFF" />'
	    + '<embed width="480" height="400" menu="false" quality="high" src="FLASHADDR" type="application/x-shockwave-flash" />'
	    + '</object>';
	var videoFlash = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="480" height="450">'
		+ '<param value="transparent" name="wmode"/>'
		+ '<param value="FLASHADDR" name="movie" />'
		+ '<embed src="FLASHADDR" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" width="480" height="450"></embed>'
		+ '</object>';
	var musicFlash = '<object id="audioplayer_SHAREID" height="24" width="290" data="' + STATICURL + 'image/common/player.swf" type="application/x-shockwave-flash">'
		+ '<param value="' + STATICURL + 'image/common/player.swf" name="movie"/>'
		+ '<param value="autostart=yes&bg=0xCDDFF3&leftbg=0x357DCE&lefticon=0xF2F2F2&rightbg=0xF06A51&rightbghover=0xAF2910&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=0x357DCE&slider=0x357DCE&track=0xFFFFFF&border=0xFFFFFF&loader=0xAF2910&soundFile=FLASHADDR" name="FlashVars"/>'
		+ '<param value="high" name="quality"/>'
		+ '<param value="false" name="menu"/>'
		+ '<param value="#FFFFFF" name="bgcolor"/>'
	    + '</object>';
	var musicMedia = '<object height="64" width="290" data="FLASHADDR" type="audio/x-ms-wma">'
	    + '<param value="FLASHADDR" name="src"/>'
	    + '<param value="1" name="autostart"/>'
	    + '<param value="true" name="controller"/>'
	    + '</object>';
	var flashHtml = videoFlash;
	var videoMp3 = true;
	if('' == flashvar) {
		alert('���ֵ�ַ���󣬲���Ϊ��');
		return false;
	}
	if('music' == host) {
		var mp3Reg = new RegExp('.mp3$', 'ig');
		var flashReg = new RegExp('.swf$', 'ig');
		flashHtml = musicMedia;
		videoMp3 = false;
		if(mp3Reg.test(flashvar)) {
			videoMp3 = true;
			flashHtml = musicFlash;
		} else if(flashReg.test(flashvar)) {
			videoMp3 = true;
			flashHtml = flash;
		}
	}
	flashvar = encodeURI(flashvar);
	if(flashAddr[host]) {
		var flash = flashAddr[host].replace('FLASHVAR', flashvar);
		flashHtml = flashHtml.replace(/FLASHADDR/g, flash);
		flashHtml = flashHtml.replace(/SHAREID/g, shareid);
	}

	if(!obj) {
		$('flash_div_' + shareid).innerHTML = flashHtml;
		return true;
	}
	if($('flash_div_' + shareid)) {
		$('flash_div_' + shareid).style.display = '';
		$('flash_hide_' + shareid).style.display = '';
		obj.style.display = 'none';
		return true;
	}
	if(flashAddr[host]) {
		var flashObj = document.createElement('div');
		flashObj.id = 'flash_div_' + shareid;
		obj.parentNode.insertBefore(flashObj, obj);
		flashObj.innerHTML = flashHtml;
		obj.style.display = 'none';
		var hideObj = document.createElement('div');
		hideObj.id = 'flash_hide_' + shareid;
		var nodetxt = document.createTextNode("����");
		hideObj.appendChild(nodetxt);
		obj.parentNode.insertBefore(hideObj, obj);
		hideObj.style.cursor = 'pointer';
		hideObj.onclick = function() {
			if(true == videoMp3) {
				stopMusic('audioplayer', shareid);
				flashObj.parentNode.removeChild(flashObj);
				hideObj.parentNode.removeChild(hideObj);
			} else {
				flashObj.style.display = 'none';
				hideObj.style.display = 'none';
			}
			obj.style.display = '';
		};
	}
}

function userapp_open() {
	var x = new Ajax();
	x.get('home.php?mod=spacecp&ac=common&op=getuserapp&inajax=1', function(s){
		$('my_userapp').innerHTML = s;
		$('a_app_more').className = 'fold';
		$('a_app_more').innerHTML = '����';
		$('a_app_more').onclick = function() {
			userapp_close();
		};
	});
}

function userapp_close() {
	var x = new Ajax();
	x.get('home.php?mod=spacecp&ac=common&op=getuserapp&subop=off&inajax=1', function(s){
		$('my_userapp').innerHTML = s;
		$('a_app_more').className = 'unfold';
		$('a_app_more').innerHTML = 'չ��';
		$('a_app_more').onclick = function() {
			userapp_open();
		};
	});
}

function startMarquee(h, speed, delay, sid) {
	var t = null;
	var p = false;
	var o = $(sid);
	o.innerHTML += o.innerHTML;
	o.onmouseover = function() {p = true};
	o.onmouseout = function() {p = false};
	o.scrollTop = 0;
	function start() {
	    t = setInterval(scrolling, speed);
	    if(!p) {
			o.scrollTop += 2;
		}
	}
	function scrolling() {
	    if(p) return;
		if(o.scrollTop % h != 0) {
	        o.scrollTop += 2;
	        if(o.scrollTop >= o.scrollHeight/2) o.scrollTop = 0;
	    } else {
	        clearInterval(t);
	        setTimeout(start, delay);
	    }
	}
	setTimeout(start, delay);
}

function readfeed(obj, id) {
	if(Cookie.get("read_feed_ids")) {
		var fcookie = Cookie.get("read_feed_ids");
		fcookie = id + ',' + fcookie;
	} else {
		var fcookie = id;
	}
	Cookie.set("read_feed_ids", fcookie, 48);
	obj.className = 'feedread';
}

function showreward() {
	if(Cookie.get('reward_notice_disable')) {
		return false;
	}
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=getreward', function(s){
		if(s) {
			msgwin(s, 2000);
		}
	});
}

function msgwin(s, t) {

	var msgWinObj = $('msgwin');
	if(!msgWinObj) {
		var msgWinObj = document.createElement("div");
		msgWinObj.id = 'msgwin';
		msgWinObj.style.display = 'none';
		msgWinObj.style.position = 'absolute';
		msgWinObj.style.zIndex = '100000';
		$('append_parent').appendChild(msgWinObj);
	}
	msgWinObj.innerHTML = s;
	msgWinObj.style.display = '';
	msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=0)';
	msgWinObj.style.opacity = 0;
	var sTop = document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
	pbegin = sTop + (document.documentElement.clientHeight / 2);
	pend = sTop + (document.documentElement.clientHeight / 5);
	setTimeout(function () {showmsgwin(pbegin, pend, 0, t)}, 10);
	msgWinObj.style.left = ((document.documentElement.clientWidth - msgWinObj.clientWidth) / 2) + 'px';
	msgWinObj.style.top = pbegin + 'px';
}

function showmsgwin(b, e, a, t) {
	step = (b - e) / 10;
	var msgWinObj = $('msgwin');
	newp = (parseInt(msgWinObj.style.top) - step);
	if(newp > e) {
		msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + a + ')';
		msgWinObj.style.opacity = a / 100;
		msgWinObj.style.top = newp + 'px';
		setTimeout(function () {showmsgwin(b, e, a += 10, t)}, 10);
	} else {
		msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
		msgWinObj.style.opacity = 1;
		setTimeout('displayOpacity(\'msgwin\', 100)', t);
	}
}

function displayOpacity(id, n) {
	if(!$(id)) {
		return;
	}
	if(n >= 0) {
		n -= 10;
		$(id).style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + n + ')';
		$(id).style.opacity = n / 100;
		setTimeout('displayOpacity(\'' + id + '\',' + n + ')', 50);
	} else {
		$(id).style.display = 'none';
		$(id).style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
		$(id).style.opacity = 1;
	}
}

function display(id) {
	var obj = $(id);
	obj.style.display = obj.style.display == '' ? 'none' : '';
}

function urlto(url) {
	window.location.href = url;
}

function explode(sep, string) {
	return string.split(sep);
}

function selector(pattern, context) {
	var re = new RegExp('([a-z0-9]*)([\.#:]*)(.*|$)', 'ig');
	var match = re.exec(pattern);
	var conditions = cc = [];
	if (match[2] == '#')	conditions.push(['id', '=', match[3]]);
	else if(match[2] == '.')	conditions.push(['className', '~=', match[3]]);
	else if(match[2] == ':')	conditions.push(['type', '=', match[3]]);
	var s = match[3].replace(/\[(.*)\]/g,'$1').split('@');
	for(var i=0; i<s.length; i++) {
		if (cc = /([\w]+)([=^%!$~]+)(.*)$/.exec(s[i]))
			conditions.push([cc[1], cc[2], cc[3]]);
	}
	var list = conditions[0] && conditions[0][0] == 'id' ? [document.getElementById(conditions[0][2])] : (context || document).getElementsByTagName(match[1] || "*");
	if(!list || !list.length)	return [];
	if(conditions) {
		var elements = [];
		var attrMapping = {'for': 'htmlFor', 'class': 'className'};
		for(var i=0; i<list.length; i++) {
			var pass = true;
			for(var j=0; j<conditions.length; j++) {
				var attr = attrMapping[conditions[j][0]] || conditions[j][0];
				var val = list[i][attr] || (list[i].getAttribute ? list[i].getAttribute(attr) : '');
				var pattern = null;
				if(conditions[j][1] == '=') {
					pattern = new RegExp('^'+conditions[j][2]+'$', 'i');
				} else if(conditions[j][1] == '^=') {
					pattern = new RegExp('^' + conditions[j][2], 'i');
				} else if(conditions[j][1] == '$=') {
					pattern = new RegExp(conditions[j][2] + '$', 'i');
				} else if(conditions[j][1] == '%=') {
					pattern = new RegExp(conditions[j][2], 'i');
				} else if(conditions[j][1] == '~=') {
					pattern = new RegExp('(^|[ ])' + conditions[j][2] + '([ ]|$)', 'i');
				}
				if(pattern && !pattern.test(val)) {
					pass = false;
					break;
				}
			}
			if(pass) elements.push(list[i]);
		}
		return elements;
	} else {
		return list;
	}
}

function showBlock(cid, oid) {
	if(parseInt(cid)) {
		var listObj = $(oid);
		var x = new Ajax();
		x.get('portal.php?mod=cp&ac=block&operation=getblock&classid='+cid, function(s){
			listObj.innerHTML = s;
		})
	}
}