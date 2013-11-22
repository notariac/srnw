<?php
    require("../../config.php");
    $Filtro = $_GET['q'];
    $Sql = "SELECT direccion FROM cliente WHERE direccion ILIKE '%$Filtro%'";	
    $Consulta = $Conn->Query($Sql);
    while($row = $Conn->FetchArray($Consulta)){
        echo strtoupper($row[0])."\n";
    }
?>