<?php
$sql="SELECT kardex.*,kardex_bien.*, kardex_tipo.abreviatura, asigna_pdt.idacto_juridico 
FROM servicio 
INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) 
INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
LEFT OUTER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
INNER JOIN kardex_bien ON (kardex.idkardex=kardex_bien.idkardex)
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
	$numero_bienes="";
	$tipo_bien="";
	$idbien="";    
	$tipo_codigoplacas="";
	$numero_codigoplacas="";
	$numserie="";
	$origen="";
	$ubigeo_pais="";
	$fecha_construccion="";
	$descotro="";
	/*
	*Array Permision de Llenado de Datos para Validar las Fuentes del Kardex
	*y solo se genere los datos Correctamente Llenados
	*/
	$array_tipo_codigoplacas=array('01','07','09');
	$array_serie=array('05');
	$array_origen=array('04');
	$array_otros=array('99');
	if(in_array($idbien, $array_tipo_codigoplacas)){
		$tipo_codigoplacas=$query[$i]['tipo_codigoplacas'];
		$numero_codigoplacas=$query[$i]['numero_codigoplacas'];
	}
	if(in_array($idbien, $array_serie)){
		$numserie=$query[$i]['numserie'];
	}
	if(in_array($idbien, $array_origen)){
		$origen=$query[$i]['origen'];
		if($origen==1){
			$ubigeo_pais=$query[$i]['ubigeo'];
		}else{
			$ubigeo_pais=$query[$i]['idpais'];
		}
        if($query[$i]['fecha_construccion']!='1900-01-01')
            $fecha_construccion=$query[$i]['fecha_construccion'];
	}
	if(in_array($idbien, $array_otros)){
		$descotro=$query[$i]['descotro'];
	}
    /*
     * Generacion de Linea de Registro PDT
     */
    $pdt=array(
        $tipo,
        $numero_escritura,
        $fecha_numeracion,
        $nroactosecuencial,
        $tipo_bien,
        $idbien,
        $tipo_codigoplacas,
        $numero_codigoplacas,
        $numserie,
        $origen,
        $ubigeo_pais,
        $fecha_construccion,
        $descotro,
        "\n");
    $fichero.=implode("|", $pdt);
}

$name_file="files/3520{$_REQUEST['anio']}10011638126.";
if(is_file($name_file."BIE"))unlink($name_file."BIE");
$archivo = fopen($name_file."BIE","a+"); 
fputs($archivo,$fichero);
$zip = new ZipArchive();
if(is_file($name_file."zip"))unlink($name_file."zip");
if($zip->open($name_file."zip",ZIPARCHIVE::CREATE)===true) {
        $zip->addFile($name_file."BIE");
        $zip->close();
        echo ".";
}else{
    echo 'No se puede Comprimir el Archivo:'.$name_file;
}
?>
