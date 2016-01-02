<?php

$url = "http://weather.tq121.com.cn/detail.php?city=$_GET[city]"; //目标站
//在输入地址是传入参数---？city=汕尾，在网页中用$_GET[city]取得

$fp = @fopen($url, "r") or die("超时");
$fcontents = file_get_contents($url);

eregi("<img src=\"images/20080821.gif\" width=\"480\" height=\"55\" border=\"0\"></a></td>(.*)<td width=\"21\" valign=\"top\">&nbsp;</td>", $fcontents, $regs);
//截取网页源代码信息
$regs[1]=str_replace("src=\"../images/","src=\"http://weather.tq121.com.cn/images/",$regs[1]);
//替换图片路径，把相对路径替换为远程的路径

echo  $regs[1];//截取内容存放的数组下标为1

?>
