<?php
/*
 * Created on 2013��10��5��
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 if($_GET[out]){
 	
 	setcookie('id','');
 	setcookie('pass','');
 	
 	echo " <script>location.href='login-cookie.php'</script>" ;
 	
 }
 
 
 
 
 
 
 
 if($_POST[name]&&$_POST[password])
 {
 	
 	setcookie('id',$_POST[name],time()+3600);//һ��Сʱ
 	setcookie('pass',$_POST[password],time()+3600);//һ��Сʱ
 	
 	echo " <script>location.href='login-cookie.php'</script>" ;
 	
 	 }
 	 
 	 
 	 if($_COOKIE[id]&&$_COOKIE[pass]){
 	 	
 	 	echo "��½�ɹ�<br>�û�����".$_COOKIE[id]."<br>���룺".$_COOKIE[pass];
 	 	echo "<br><a href='login-cookie.php?out=out'>�˳�</a>";
 	 	
 	 }
 
 
 
 
?>

<?
 if(!$_COOKIE[id]||!$_COOKIE[pass]){
 	?>
 	
<form action="" method="post">
  <table width="282" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="93" bordercolor="#6699cc" bgcolor="#6699cc">�û�����</td>
      <td width="172" bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="name" name="name" />
      </label></td>
    </tr>
    <tr>
      <td bordercolor="#6699cc" bgcolor="#6699cc">���룺</td>
      <td bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="password" name="password" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="submit" name="Submit" value="�ύ" />
      </label></td>
    </tr>
  </table>
</form>

<?
 }
?>