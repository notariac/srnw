<?php 
include('../../config.php');
include('../../config_seguridad.php');	
include_once '../../libs/funciones.php';

$Id = $_GET['Id'];
$tipo = $_GET['tipo'];
$sql = "SELECT idservicio from kardex where idkardex = ".$Id;
$c = $Conn->Query($sql);
$r = $Conn->FetchArray($c);

$sql = "SELECT DISTINCT servicio_participacion.idparticipacion, 
        participacion.descripcion FROM participacion 
        INNER JOIN servicio_participacion ON 
        (participacion.idparticipacion = servicio_participacion.idparticipacion) 
        WHERE estado = 1 AND servicio_participacion.idservicio = '".$r[0]."' and tipo=".$tipo;

$Consulta2 = $Conn->Query($sql);           
$html = "";

while($row = $Conn->FetchArray($Consulta2))
{
	$html .= "<option value='".$row[0]."'>".$row[1]."</option>";
}
echo $html;
?>