<?php 
include("../../config.php"); 
include_once '../../libs/funciones.php';

$SQL = "SELECT idkardex,idmoneda,monto
		FROM kardex_aj where idkardex = ".$_POST['idk']." and idacto_juridico = ".$_POST['idaj'];
$Consulta 	= $Conn->Query($SQL);
$row 		= $Conn->FetchArray($Consulta);

$_POST['m'] = str_replace(",", "", $_POST['m']);
$_POST['m'] = str_replace("'", "", $_POST['m']);
$_POST['m'] = str_replace("|", "", $_POST['m']);
$_POST['m'] = str_replace("`", "", $_POST['m']);

if($row['idkardex']!="")
{
	$sql = "UPDATE kardex_aj set monto = ".$_POST['m'].", idmoneda = ".$_POST['idm']."
			 where idkardex = ".$_POST['idk']." and idacto_juridico = ".$_POST['idaj'];
}
else
{
	$sql = "INSERT INTO kardex_aj(idkardex, idacto_juridico, idmoneda, monto) 
			values(".$_POST['idk'].",".$_POST['idaj'].",".$_POST['idm'].",".$_POST['m'].")";
}
//echo $sql;
$Conn->Query($sql);

?>