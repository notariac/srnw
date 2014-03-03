<?php
    if(!session_id()){ session_start(); }
    require("../../config.php");
    $Filtro = $_GET['q'];
    $Sql = "SELECT * FROM pdt.documento_notarial where descripcion ILIKE '%$Filtro%' ORDER BY descripcion ASC";
    $Consulta = $Conn->Query($Sql);
    while($row = $Conn->FetchArray($Consulta)){
        echo $row[1]."|".$row[0]."\n";
    }
?>