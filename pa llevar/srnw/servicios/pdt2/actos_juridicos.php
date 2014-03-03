<?php
$sql="SELECT kardex.*, kardex_tipo.abreviatura, asigna_pdt.idacto_juridico 
FROM servicio 
INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) 
INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
LEFT OUTER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
WHERE EXTRACT('YEAR' FROM kardex.fecfirmae)='{$_REQUEST['anio']}'
";
//echo $sql;
$query=$Conn->Execute($sql);
$fichero="";
for($i=0;$i<count($query);$i++){
    $abreviatura=$query[$i]['abreviatura'];    
            /*
             * Variables para Generar Archivo Plano
             */
            if($abreviatura=='V'){
                $tipo=2;//Escrituras
            }else{
                $tipo=1;//Actas Notariales
            }
            $numero_escritura=$query[$i]['escritura'];
            $fecha_numeracion=reformatFecha($query[$i]['escritura_fecha']);
            $fecha_autorizacion=reformatFecha($query[$i]['firmadofecha']);
            $fecha_legalizacion=reformatFecha($query[$i]['escritura_fecha']);
            $actojuridico='';
            $nroactosecuencial='';
            $idmoneda=$query[$i]['idmoneda'];
            $valorcuota=$query[$i]['monto'];
            $plazoinicial=reformatFecha($query[$i]['plazoinicial']);
            $plazofinal=reformatFecha($query[$i]['plazofinal']);
            $nombrecontrato='';
            $fechaminuta=reformatFecha($query[$i]['minuta_fecha']);
            $exmedpago=isset($query[$i]['exmedpago'])?$query[$i]['exmedpago']:0;
            $mediopago='';
            $idmonedamediopago='';
            $montopagado='';
            $fechapago='';
            $nromediopago='';
            $identidad_financiera=  '';
            $sqlforactoj="SELECT public.asigna_pdt.idacto_juridico
                          FROM public.asigna_pdt
                          WHERE idservicio={$query[$i]['idservicio']}";
            $rowactoj=$Conn->Execute($sqlforactoj);
            if(count($rowactoj)==0){
                $fichero.="$tipo|$numero_escritura|$fecha_numeracion|$fecha_autorizacion|$fecha_legalizacion|$actojuridico|$nroactosecuencial|$idmoneda|$valorcuota|$plazoinicial|$plazofinal|$nombrecontrato|$fechaminuta|$exmedpago|$mediopago|$idmonedamediopago|$montopagado|$fechapago|$nromediopago|$identidad_financiera|\n";
            }else{
        for($j=0;$j<count($rowactoj);$j++){
                $actojuridico=$rowactoj[$j]['idacto_juridico'];
                $sqldetallecuota="SELECT 
                    public.detalle_forma_pago.idkardex,
                    public.detalle_forma_pago.idforma_pago,
                    public.detalle_forma_pago.idmoneda,
                    public.detalle_forma_pago.montopagado,
                    public.detalle_forma_pago.fechapago,
                    public.detalle_forma_pago.nromediopago,
                    public.detalle_forma_pago.identidad_financiera
                  FROM
                    public.detalle_forma_pago
                  WHERE idkardex={$query[$i]['idkardex']}  
                      AND idmoneda<>3
                  ";
                 $ejecmontos=$Conn->Execute($sqldetallecuota);
                 if(count($ejecmontos)==0){
                        $fichero.="$tipo|$numero_escritura|$fecha_numeracion|$fecha_autorizacion|$fecha_legalizacion|$actojuridico|$nroactosecuencial|$idmoneda|$valorcuota|$plazoinicial|$plazofinal|$nombrecontrato|$fechaminuta|$exmedpago|$mediopago|$idmonedamediopago|$montopagado|$fechapago|$nromediopago|$identidad_financiera|\n";
                 }else{
                     for($k=0;$k<count($ejecmontos);$k++){            
                        $nroactosecuencial='';
//                        $idmoneda=$ejecmontos[$k]['idmoneda'];

                        $nombrecontrato='';
                        $mediopago=zerofill($ejecmontos[$k]['idforma_pago'],3);
                        $idmonedamediopago=$idmoneda;
                        $montopagado=$ejecmontos[$k]['montopagado'];
                        $fechapago=reformatFecha($ejecmontos[$k]['fechapago']);
                        $nromediopago=$ejecmontos[$k]['nromediopago'];
                        $identidad_financiera=  zerofill($ejecmontos[$k]['identidad_financiera'], 2);
                        $fichero.="$tipo|$numero_escritura|$fecha_numeracion|$fecha_autorizacion|$fecha_legalizacion|$actojuridico|$nroactosecuencial|$idmoneda|$valorcuota|$plazoinicial|$plazofinal|$nombrecontrato|$fechaminuta|$exmedpago|$mediopago|$idmonedamediopago|$montopagado|$fechapago|$nromediopago|$identidad_financiera|\n";
                    }
                 }

            }
    }    
}
$name_file="files/3520{$_REQUEST['anio']}10011638126.";
if(is_file($name_file."ACT"))unlink($name_file."ACT");
$archivo = fopen($name_file."ACT","a+"); 
fputs($archivo,$fichero);
$zip = new ZipArchive();
if($zip->open($name_file."zip",ZIPARCHIVE::CREATE)===true) {
        $zip->addFile($name_file."ACT");
        $zip->close();
        echo ".";
}else{
    echo 'No se puede Comprimir el Archivo:'.$name_file;
}


?>
