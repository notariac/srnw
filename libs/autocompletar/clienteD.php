<?php
require("../../config.php");
$Filtro = $_GET['term'];
$IdComprobante = isset($_GET['IdComprobante'])?$_GET['IdComprobante']:'';	
$Sql = "SELECT cliente.nombres, 
                cliente.direccion, 
                cliente.dni_ruc, 
                cliente.idcliente, 
                documento.descripcion, 
                cliente.telefonos,
                cliente.ape_paterno,
                cliente.ap_materno
         FROM cliente INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) ";
if ($IdComprobante=='')
{
    $Sql = $Sql." WHERE cliente.estado<>0 AND cliente.dni_ruc ILIKE '%$Filtro%'";
}
else
{
    if ($IdComprobante==1){ $Doc = 1; }
    if ($IdComprobante==2){ $Doc = 4; }
    $Sql = $Sql." WHERE cliente.dni_ruc ILIKE '%$Filtro%' AND cliente.iddocumento='$Doc' AND cliente.estado<>0 ";
}	
$Sql .= " limit 10";
$Consulta = $Conn->Query($Sql);
$data = array();
while($row = $Conn->FetchArray($Consulta))
{
    $data[] = array(
                    'nombres'=>str_replace("!","",$row[0])." ".$row['ape_paterno']." ".$row['ap_materno'],
                    'direccion'=>$row[1],
                    'dni_ruc'=>$row[2],
                    'idcliente'=>$row[3],
                    'documento'=>$row[4],
                    'telefonos'=>$row[5]
                    );    
}
print_r(json_encode($data));
?>