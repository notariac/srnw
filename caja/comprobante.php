<?php
require("../config.php");
$IdCaja = $_POST['IdCaja'];
$IdNotaria = $_POST['IdNotaria'];
$IdComprobante = $_POST['IdComprobante'];
$Sql = "SELECT serie, (correlativo+1) FROM caja_notaria_comprobante WHERE idcaja='$IdCaja' AND idnotaria='$IdNotaria' AND idcomprobante='$IdComprobante' limit 1";
$Consulta = $Conn->Query($Sql);
while($row = $Conn->FetchArray($Consulta)){
    echo $row[0]."|".$row[1]."\n";
}
?>