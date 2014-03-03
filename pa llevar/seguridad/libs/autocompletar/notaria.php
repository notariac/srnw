<?php
require("../../config.php");
require("../../config_srnw.php");
$Filtro = $_GET['q'];
$Sql = "SELECT descripcion, idnotaria FROM notaria WHERE descripcion ILIKE '%$Filtro%' OR ruc ILIKE '%$Filtro%' OR notario ILIKE '%$Filtro%' ";
$Consulta = $ConnS->Query($Sql);
while($row = $ConnS->FetchArray($Consulta)){
        echo strtoupper($row[0])."|".$row[1]."\n";
}
?>
