<?php
/*
 * Created on 2013��10��4��
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  //-----------------------------------------------
  /*----------------------
 $fp=fopen("tmp.htm","r"); //ֻ����ģ��
  $str=fread($fp,filesize("tmp.htm"));//��ȡģ��������

 
 //echo $str;
 
 
  $str=str_replace("{title}","kxw",$str);
  $str=str_replace("{content}","Kingson_Wu",$str);//�滻����
 //echo $str;
 
 fclose($fp);
 
 $handle=fopen("kxw.htm","w"); //д�뷽ʽ������·��
  fwrite($handle,$str); //�Ѹղ��滻������д�����ɵ�HTML�ļ�
  fclose($handle);
  echo "���ɳɹ�";
  ----------------------------*/
  //--------------------------------------------
  
   $con=array(array('���ű���','��������'),array('���ű���2','��������2'));

  foreach($con as $id=>$val){
  $title=$val[0];
  $content=$val[1];
  $path=$id.'.htm';//���ɵ��ļ���
  $fp=fopen("tmp.htm","r"); //ֻ����ģ��
  $str=fread($fp,filesize("tmp.htm"));//��ȡģ��������
  $str=str_replace("{title}",$title,$str);
  $str=str_replace("{content}",$content,$str);//�滻����
  fclose($fp);

  $handle=fopen($path,"w"); //д�뷽ʽ������·��
  fwrite($handle,$str); //�Ѹղ��滻������д�����ɵ�HTML�ļ�
  fclose($handle);
  echo "���ɳɹ�";
  }
  // unlink($path); //ɾ���ļ�
  
?>
