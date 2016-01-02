<?php
/*
 * Created on 2013年10月4日
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  //-----------------------------------------------
  /*----------------------
 $fp=fopen("tmp.htm","r"); //只读打开模板
  $str=fread($fp,filesize("tmp.htm"));//读取模板中内容

 
 //echo $str;
 
 
  $str=str_replace("{title}","kxw",$str);
  $str=str_replace("{content}","Kingson_Wu",$str);//替换内容
 //echo $str;
 
 fclose($fp);
 
 $handle=fopen("kxw.htm","w"); //写入方式打开新闻路径
  fwrite($handle,$str); //把刚才替换的内容写进生成的HTML文件
  fclose($handle);
  echo "生成成功";
  ----------------------------*/
  //--------------------------------------------
  
   $con=array(array('新闻标题','新闻内容'),array('新闻标题2','新闻内容2'));

  foreach($con as $id=>$val){
  $title=$val[0];
  $content=$val[1];
  $path=$id.'.htm';//生成的文件名
  $fp=fopen("tmp.htm","r"); //只读打开模板
  $str=fread($fp,filesize("tmp.htm"));//读取模板中内容
  $str=str_replace("{title}",$title,$str);
  $str=str_replace("{content}",$content,$str);//替换内容
  fclose($fp);

  $handle=fopen($path,"w"); //写入方式打开新闻路径
  fwrite($handle,$str); //把刚才替换的内容写进生成的HTML文件
  fclose($handle);
  echo "生成成功";
  }
  // unlink($path); //删除文件
  
?>
