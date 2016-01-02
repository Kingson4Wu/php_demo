<?php
if(!empty($_POST['id'])){
	$id=$_POST['id'];
	$fd=$_POST['fd'];
	$val=$_POST['val'];
   $sql = "update tables set `$fd`='$val' where `id`='$id'";
   echo $sql;
}

?>