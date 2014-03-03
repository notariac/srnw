<?php
if(!session_id()){ session_start(); }	
require("../../config.php");
$Filtro = $_GET['q'];
$Sql = "SELECT kardex_tipo.descripcion, kardex_tipo.idkardex_tipo FROM kardex_tipo WHERE kardex_tipo.idkardex_tipo not in (select kardex_tipo_notaria.idkardex_tipo from kardex_tipo_notaria WHERE kardex_tipo_notaria.idnotaria='".$_SESSION['notaria']."') AND kardex_tipo.descripcion ILIKE '%$Filtro%' ";
$Consulta = $Conn->Query($Sql);
while($row = $Conn->FetchArray($Consulta)){
    echo $row[0]."|".$row[1]."|".$row[2]."\n";
}
?>