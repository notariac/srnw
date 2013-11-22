<?php
require("../../config.php");
$Filtro = $_GET['term'];	
$Sql = "SELECT idatencion, correlativo FROM atencion 
        WHERE cast(idatencion as text) ilike '%$Filtro%' AND idnotaria='".$_GET['idnotaria']."' 
                and estado not in (1,2)
        ORDER BY idatencion desc 
        limit 10 ";	

$Consulta = $Conn->Query($Sql);
while($row = $Conn->FetchArray($Consulta))
{
    	$data[] = array(
    					'idatencion'=>$row[0],
    					'correlativo'=>$row[1]
    				   );
}
print_r(json_encode($data));
?>