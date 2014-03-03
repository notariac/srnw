<?php
$sql="
SELECT kardex.*,kardex_participantes.*, cliente.*,kardex_tipo.abreviatura, asigna_pdt.idacto_juridico 
FROM servicio 
INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) 
INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
LEFT OUTER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
INNER JOIN kardex_participantes ON (kardex.idkardex=kardex_participantes.idkardex)
INNER JOIN cliente ON (cliente.idcliente=kardex_participantes.idparticipante)    
WHERE EXTRACT('YEAR' FROM kardex.fecfirmae)='{$_REQUEST['anio']}'
";
//die($sql);
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
	$nroactosecuencial=$i;
	$numero_bienes=$i;
	$numero_otorgantes=$i;
    $tipodocumento=$query[$i]['iddocumento'];
    $numerodocumento=$query[$i]['dni_ruc'];
    $tipootorgante=$query[$i]['idparticipacion'];
    $tipopersona=$query[$i]['idcliente_tipo'];
    $ubigeo_pais="";
    $porcentaje_participacion="";
    $razonsocial_nombre="";
    $ape_paterno="";
    $ape_materno="";
    $primer_nombre="";
    $segundo_nombre="";
    $genero_renta3categoria="";//(1) Si (0) No
    $bien_enajenado="";
    $numero_operacion_1662="";
    $pago_segunda_categoria="";
	
    /*
     * Generacion de Linea de Registro PDT
     */
    $pdt=array(
        $tipo,
        $numero_escritura,
        $fecha_numeracion,
        $nroactosecuencial,
        $numero_bienes,
        $numero_otorgantes,
        $tipodocumento,
        $numerodocumento,
        $tipootorgante,
        $tipopersona,
        $ubigeo_pais,
        $porcentaje_participacion,
        $razonsocial_nombre,
        $ape_paterno,
        $ape_materno,
        $primer_nombre,
        $segundo_nombre,
        $genero_renta3categoria,
        $bien_enajenado,
        $numero_operacion_1662,
        $pago_segunda_categoria,
        "\n");
    $fichero.=implode("|", $pdt);
}

$name_file="files/3520{$_REQUEST['anio']}10011638126.";
if(is_file($name_file."OTO"))unlink($name_file."OTO");
$archivo = fopen($name_file."OTO","a+"); 
fputs($archivo,$fichero);
$zip = new ZipArchive();
if(is_file($name_file."zip"))unlink($name_file."zip");
if($zip->open($name_file."zip",ZIPARCHIVE::CREATE)===true) {
        $zip->addFile($name_file."OTO");
        $zip->close();
        echo ".";
}else{
    echo 'No se puede Comprimir el Archivo:'.$name_file;
}
?>
