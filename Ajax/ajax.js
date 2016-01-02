var xmlHttp;
function A_xmlhttprequest() {
	if(window.ActiveXObject) {
		xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
	} else if(window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest();
	}
}

function funphp100(url) {
	A_xmlhttprequest();
	xmlHttp.open("GET","for.php?id="+url,true);//返回for.php的显示页面的php代码
	xmlHttp.onreadystatechange = byphp;
	xmlHttp.send(null);
}

function byphp() {

  	          var byphp100 =  xmlHttp.responseText;
          document.getElementById('php100').innerHTML = byphp100;


}