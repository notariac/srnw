<?php
    require("../../config.php");
    $Filtro = $_GET['term'];    
    $Sql = "SELECT  idprofesion,descripcion
            FROM ro.profesion
            WHERE descripcion ilike '%".$Filtro."%'
            LIMIT 6";    
    $Consulta = $Conn->Query($Sql);
    $data = array();
    while($row = $Conn->FetchArray($Consulta))
    {
        $data[] = array(
                        'idp'=>$row[0],
                        'descripcion'=>$row[1]
                     );
    }
    print_r(json_encode($data));
?>