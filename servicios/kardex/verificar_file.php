<?php 
include('../../config.php');
session_start();

$sql = "SELECT archivo from kardex where idkardex = ".$_POST['idk'];
$q = $Conn->Query($sql);
$r = $Conn->FetchArray($q);

if($r['archivo']=="")
	echo "0";
else
	echo "1";
?>