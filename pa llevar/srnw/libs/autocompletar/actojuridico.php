<?php
    if(!session_id()){ session_start(); }
    require("../../config.php");
    $Filtro = $_GET['q'];
    $IdDocNotarial = $_GET['id'];
    $Sql = "
        SELECT 
        aj.descripcion, 
        aj.idacto_juridico 
        FROM pdt.acto_documento ad, pdt.acto_juridico aj, pdt.documento_notarial dn 
        WHERE 
        aj.idacto_juridico = ad.idacto_juridico 
        AND dn.iddocumento_notarial = ad.iddocumento_notarial 
        AND ad.iddocumento_notarial='$IdDocNotarial' 
        AND aj.descripcion ilike '%$Filtro%' 
        ORDER BY aj.descripcion ASC";
    $Consulta = $Conn->Query($Sql);
    while($row = $Conn->FetchArray($Consulta)){
        echo $row[0]."|".$row[1]."\n";
    }
?>