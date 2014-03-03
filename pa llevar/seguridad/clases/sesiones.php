<?php 
if(!session_id()){ session_start(); }	
include("../config.php");	
$IdSistema = $_GET["IdSistema"];
$IdUsuario = $_SESSION["id_user"];	
$SQL = "SELECT sistemas.path, usuario_sistemas.idperfil, usuario_sistemas.administrador FROM usuario_sistemas INNER JOIN sistemas ON sistemas.idsistema = usuario_sistemas.idsistema WHERE usuario_sistemas.idusuario = '$IdUsuario' AND usuario_sistemas.idsistema = '$IdSistema'";
$Consulta               = $Conn->Query($SQL);
$row                    = $Conn->FetchArray($Consulta);
$_SESSION["IdSistema"]	= $IdSistema;
$_SESSION["Ruta"] 	= "http://".$_SERVER['HTTP_HOST']."/".$row[0];
$_SESSION["IdPerfil"]	= $row[1];
$_SESSION["Activo"] 	= 1;

$_SESSION["Admin"] 	= $row[2];
echo "<script>location.href='http://".$_SERVER['HTTP_HOST']."/".$row[0]."'</script>";	
?>