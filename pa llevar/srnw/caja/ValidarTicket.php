<?php
require("../config.php");	
$Anio = $_POST['Anio'];
$Id = $_POST['Id'];	
$Sql = "SELECT cliente.dni_ruc,cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,''),
	case when length(rtrim(ltrim(atencion.direccion)))>1 then atencion.direccion else cliente.direccion end as direccion,
	cliente.idcliente 
	FROM atencion INNER JOIN cliente ON cliente.idcliente=atencion.idcliente WHERE atencion.idatencion=".$Id." AND atencion.anio='".$Anio."'";

$Consulta = $Conn->Query($Sql);
$row = $Conn->FetchArray($Consulta);	
$SqlT = "SELECT SUM(atencion_detalle.monto) FROM atencion_detalle WHERE atencion_detalle.idatencion = ".$Id;
$ConsultaT = $Conn->Query($SqlT);
$rowT = $Conn->FetchArray($ConsultaT);	
echo $row[0]."|".$row[1]."|".$row[2]."|".$rowT[0]."|".$row[3]."\n";
?>
