<?php
    require("../../config.php");
    $Filtro = $_GET['term'];
    $IdComprobante = isset($_GET['IdComprobante'])?$_GET['IdComprobante']:'';
    $Sql = "SELECT  replace(cliente.nombres, '!', '') as nombres,
                cliente.direccion, 
                cliente.dni_ruc, 
                cliente.idcliente, 
                documento.descripcion, 
                cliente.telefonos,
                cliente.ape_paterno,
                cliente.ap_materno
            FROM    cliente INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento)";
    if ($IdComprobante=='')
    {
        $Sql = $Sql." WHERE cliente.dni_ruc<>'' and cliente.estado<>0 and (replace(cliente.nombres,'!','')||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,'') ilike '%$Filtro%' ) ORDER BY cliente.idcliente ASC ";

    }
    else
    {
        if ($IdComprobante==1){ $Doc = 1; }
        if ($IdComprobante==2){ $Doc = 8; }
        $Sql = $Sql." WHERE  cliente.dni_ruc<>'' and  cliente.estado<>0 and (replace(cliente.nombres,'!','')||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,'') ilike '%$Filtro%' ) AND cliente.iddocumento='$Doc' ORDER BY cliente.idcliente ASC ";
    }
    $Sql .= " limit 10";
    $Consulta = $Conn->Query($Sql);
    $data = array();
    while($row = $Conn->FetchArray($Consulta))
    {
        $data[] = array(
                        'nombres'=>str_replace("!","",$row[0])." ".$row['ape_paterno']." ".$row['ap_materno'],
                        'direccion'=>$row[1],
                        'dni_ruc'=>$row[2],
                        'idcliente'=>$row[3],
                        'documento'=>$row[4],
                        'telefonos'=>$row[5]
                     );       
    }
    print_r(json_encode($data));
?>