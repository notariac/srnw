<?php
    if(!session_id()){ session_start(); }
    require("../../config.php");
    $Filtro = $_GET['term'];
    $Sql = "SELECT servicio.idservicio, servicio.descripcion, servicio.precio, servicio.legal, servicio.especial, servicio.idkardex_tipo, kardex_tipo.abreviatura, servicio.folios FROM servicio INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) WHERE servicio.estado=1 AND servicio.descripcion ILIKE '%$Filtro%' ORDER BY servicio.descripcion ASC limit 15";
    $Consulta = $Conn->Query($Sql);
    $data = array();
    while($row = $Conn->FetchArray($Consulta)){
    	$data[] = array(
    					'idservicio'=>$row['idservicio'],
    					'descripcion'=>$row['descripcion'],
    					'precio'=>number_format($row[2],2),
    					'legal'=>$row[3],
    					'especial'=>$row[4],
    					'idkardex_tipo'=>$row[5],
    					'abreviatura'=>$row[6],
    					'folios'=>$row['folios']
    					);
        //echo $row[0]."|".$row[1]."|".number_format($row[2],2)."|".$row[3]."|".$row[4]."|".$row[5]."|".$row[6]."|".$row[7]."\n";
    }
    print_r(json_encode($data));
?>