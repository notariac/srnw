<?php
session_start();
include("../../config.php");
$Consulta = $Conn->Query("SELECT * FROM notaria WHERE idnotaria='".$_SESSION['notaria']."'");
$notaria = $Conn->FetchArray($Consulta);
$desde = $_GET['d'];
$hasta = $_GET['h'];
$tipo = $_GET['t'];
$filecontent="Hoy ".$Conn->generaActoJuridico("Nada");
$downloadfile="3520".date('Y').$notaria[9].".ACT";
header ("Content-Disposition: attachment; filename=\"$downloadfile\"" ); 
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($filecontent));
header("Pragma: no-cache");
header("Expires: 0");
echo $filecontent;
?>