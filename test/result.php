<?php

$url = "http://weather.tq121.com.cn/detail.php?city=$_GET[city]"; //Ŀ��վ
//�������ַ�Ǵ������---��city=��β������ҳ����$_GET[city]ȡ��

$fp = @fopen($url, "r") or die("��ʱ");
$fcontents = file_get_contents($url);

eregi("<img src=\"images/20080821.gif\" width=\"480\" height=\"55\" border=\"0\"></a></td>(.*)<td width=\"21\" valign=\"top\">&nbsp;</td>", $fcontents, $regs);
//��ȡ��ҳԴ������Ϣ
$regs[1]=str_replace("src=\"../images/","src=\"http://weather.tq121.com.cn/images/",$regs[1]);
//�滻ͼƬ·���������·���滻ΪԶ�̵�·��

echo  $regs[1];//��ȡ���ݴ�ŵ������±�Ϊ1

?>
