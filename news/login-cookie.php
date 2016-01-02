<?php
/*
 * Created on 2013年10月5日
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
 	
 	setcookie('id',$_POST[name],time()+3600);//一个小时
 	setcookie('pass',$_POST[password],time()+3600);//一个小时
 	
 	echo " <script>location.href='login-cookie.php'</script>" ;
 	
 	 }
 	 
 	 
 	 if($_COOKIE[id]&&$_COOKIE[pass]){
 	 	
 	 	echo "登陆成功<br>用户名：".$_COOKIE[id]."<br>密码：".$_COOKIE[pass];
 	 	echo "<br><a href='login-cookie.php?out=out'>退出</a>";
 	 	
 	 }
 
 
 
 
?>

<?
 if(!$_COOKIE[id]||!$_COOKIE[pass]){
 	?>
 	
<form action="" method="post">
  <table width="282" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="93" bordercolor="#6699cc" bgcolor="#6699cc">用户名：</td>
      <td width="172" bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="name" name="name" />
      </label></td>
    </tr>
    <tr>
      <td bordercolor="#6699cc" bgcolor="#6699cc">密码：</td>
      <td bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="password" name="password" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" bordercolor="#6699cc" bgcolor="#6699cc"><label>
        <input type="submit" name="Submit" value="提交" />
      </label></td>
    </tr>
  </table>
</form>

<?
 }
?>