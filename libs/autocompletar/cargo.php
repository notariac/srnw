<?php
    require("../../config.php");
    $Filtro = $_GET['term'];    
    $Sql = "SELECT  idcargo,descripcion
            FROM ro.cargo
            WHERE descripcion ilike '%".$Filtro."%'
            limit 6";    
    $Consulta = $Conn->Query($Sql);
    $data = array();
    while($row = $Conn->FetchArray($Consulta))
    {
        $data[] = array(
                        'idc'=>$row[0],
                        'descripcion'=>$row[1]
                     );
    }
    print_r(json_encode($data));
?>