<?php
    if(!session_id()){ session_start(); }
    require("../../config.php");
    $Filtro = $_GET['term'];
    $Id = $_POST['IdServicio']; 
    $Sql = "SELECT correlativo,anio
            FROM kardex 
            WHERE correlativo ILIKE '%$Filtro%' and digital <> ''
            ORDER BY correlativo ASC limit 5";

    $Consulta = $Conn->Query($Sql);
    $data = array();
    
    while($row = $Conn->FetchArray($Consulta)){
        $data[] = array(
                        'correlativo'=>$row[0],
                        'anio' => $row[1]                        
                        );
    }
    print_r(json_encode($data));
?>