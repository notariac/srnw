<?php
    require("../../config.php");
    $Filtro = $_GET['term'];
    $Sql = "SELECT cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,''), cliente.dni_ruc FROM cliente INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) WHERE cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,'') ILIKE '%$Filtro%' and cliente.iddocumento=1 LIMIT 10";
    $Consulta = $Conn->Query($Sql);
    $data = array();
    while($row = $Conn->FetchArray($Consulta)){
        $Nombre = str_replace("!", "", $row[0]);
        $data[] = array('nombre'=>$Nombre,'dni_ruc'=>$row[1]);        
    }
    print_r(json_encode($data));
?>
