<?php
error_reporting(E_ALL);
if(!session_id()){session_start();}
include('../../config.php');
include_once '../../libs/funciones.php';
//Fichero Generico de Generacion de Data Integral para el Pdt
$sql="
    SELECT 
kardex.idkardex,
kardex.fecha,
kardex.correlativo,
kardex.idservicio,
kardex.escritura,
kardex.minuta,
kardex.placa,
kardex.firmado,
kardex.firmadofecha,
kardex.escritura_fecha,
kardex.minuta_fecha,
kardex.monto,
kardex.plazoinicial,
kardex.plazofinal,
kardex.fecfirmae,
kardex.exmedpago,
kardex.idmoneda,
kardex_tipo.abreviatura, asigna_pdt.idacto_juridico as actojuridico
FROM servicio 
INNER JOIN kardex  ON (servicio.idservicio = kardex.idservicio) 
INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
INNER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
WHERE EXTRACT('YEAR' FROM kardex.fecfirmae)='{$_REQUEST['anio']}'
AND kardex.idkardex IN (SELECT kp.idkardex FROM kardex_bien kp WHERE kp.idkardex=kardex.idkardex)
AND kardex.idkardex IN (SELECT kp.idkardex FROM kardex_participantes kp WHERE kp.idkardex=kardex.idkardex)
";
$query=$Conn->Execute($sql);
$fichero_aj="";
$fichero_b="";
global $fichero_o;
$fichero_o="";
//Por verificar  cual es la verdadera fecha de firma si fecfirmae o firmadofecha
//Defecto firmadofecha
$indicefirma="fecfirmae";
$secuencia_aj=0;
$actojuridico="";
$numero_escritura="";
$selector="";

foreach ($query as $row){
    $abreviatura=$row['abreviatura'];    
    /*
    * Variables para Generar Archivo Plano
     * Escrituras:2   Actas Notariales:1
    */           
    if(getSelector($actojuridico)==  getSelector($row['actojuridico'])&&$row['escritura']==$numero_escritura)$secuencia_aj++;
    else $secuencia_aj=1;
    if($abreviatura=='V'){$tipo=2;}
    else{$tipo=1;}
    $numero_escritura=$row['escritura'];
    $fecha_numeracion=reformatFecha($row['escritura_fecha']);
    $fecha_autorizacion=reformatFecha($row[$indicefirma]);
    $fecha_legalizacion=reformatFecha($row['escritura_fecha']);
    $actojuridico=  zerofill($row['actojuridico'], 2);
    $idmoneda=moneda_equi($row['idmoneda']);
    $valorcuota=$row['monto'];
    $plazoinicial=reformatFecha($row['plazoinicial']);
    $plazofinal=reformatFecha($row['plazofinal']);
    $nombrecontrato='';
    $fechaminuta=reformatFecha($row['minuta_fecha']);
    $exmedpago='';
    $mediopago='';
    $idmonedamediopago='';
    $montopagado='';
    $fechapago='';
    $nromediopago='';
    $identidad_financiera=  '';
    //Generacion de Fichero de Bienes
    $fichero_b.=generaFicheroBienes($Conn, $row['idkardex'], $tipo, $numero_escritura, $fecha_numeracion, $secuencia_aj);
    if(in_array($actojuridico, array("04","10"))){
       $exmedpago=isset($row['exmedpago'])?$row['exmedpago']:0;       
       $sqldetallecuota="
                 SELECT 
                    idkardex,idforma_pago,
                    idmoneda,montopagado,
                    fechapago,nromediopago,
                    identidad_financiera
                  FROM public.detalle_forma_pago
                  WHERE idkardex={$row['idkardex']} AND idmoneda<>1";
       $ejecmontos=$Conn->Execute($sqldetallecuota);
       if(count($ejecmontos)>0){
          //Validar que no sea el ultimo 
          foreach ($ejecmontos as $k => $row2) {
               if($k!=0)$secuencia_aj++;
               $mediopago=zerofill($row2['idforma_pago'],3);
               $idmonedamediopago=moneda_equi($row2['idmoneda']);
               $montopagado=$row2['montopagado'];
               $fechapago=reformatFecha($row2['fechapago']);
               $nromediopago="ND".$row2['nromediopago'];
               $identidad_financiera=  zerofill($row2['identidad_financiera'], 2);
               $ar=array(
                   $tipo,$numero_escritura,$fecha_numeracion,$fecha_autorizacion,$fecha_legalizacion,$actojuridico,$secuencia_aj,$idmoneda,$valorcuota,$plazoinicial,$plazofinal,$nombrecontrato,$fechaminuta,$exmedpago,$mediopago,$idmonedamediopago,$montopagado,$fechapago,$nromediopago,$identidad_financiera
               );
               $fichero_aj.=returnRow($ar);
          }
          
       }else{
           $ar=array(
                   $tipo,$numero_escritura,$fecha_numeracion,$fecha_autorizacion,$fecha_legalizacion,$actojuridico,$secuencia_aj,$idmoneda,$valorcuota,$plazoinicial,$plazofinal,$nombrecontrato,$fechaminuta,$exmedpago,$mediopago,$idmonedamediopago,$montopagado,$fechapago,$nromediopago,$identidad_financiera
               );
           $fichero_aj.=returnRow($ar);
       }
       
    }
}
$name_file="files/3520{$_REQUEST['anio']}10011638126.";
if(is_file($name_file."ACT"))unlink($name_file."ACT");
if(is_file($name_file."BIE"))unlink($name_file."BIE");
if(is_file($name_file."OTG"))unlink($name_file."OTG");
if(is_file($name_file."zip"))unlink($name_file."zip");
$archivo_aj = fopen($name_file."ACT","a+"); 
fputs($archivo_aj,$fichero_aj);
$archivo_b = fopen($name_file."BIE","a+"); 
fputs($archivo_b,$fichero_b);
$archivo_o = fopen($name_file."OTG","a+"); 
fputs($archivo_o,$fichero_o);
$zip = new ZipArchive();
if($zip->open($name_file."zip",ZIPARCHIVE::CREATE)===true) {
        $zip->addFile($name_file."ACT");
        $zip->addFile($name_file."BIE");
        $zip->addFile($name_file."OTG");
        $zip->close();
        echo ".";
}else{
    echo 'No se puede Comprimir el Archivo:'.$name_file;
}
//echo "B<br/>";
//echo $fichero_b;
//echo "<br/>AJ<br/>";
//die($fichero_aj);
function generaFicheroBienes($Conn,$idkardex,$tipo,$numero_escritura,$fecha_numeracion,$secuencia_aj){
    $sql="SELECT 
        pdt.bien.descripcion AS bien,
        public.ubigeo.descripcion AS nombreubigeo,
        pdt.pais.nombre AS pais,  
        k.idbien, k.tipo_bien,
        k.tipo_codigoplacas,
        k.numero_codigoplacas,
        k.numserie,k.descotro,
        k.origen,k.ubigeo,
        k.idpais,k.fecha_construccion
      FROM
        public.kardex_bien k
        INNER JOIN pdt.bien ON (k.idbien = pdt.bien.idbien)
        LEFT OUTER JOIN public.ubigeo ON (k.ubigeo = public.ubigeo.idubigeo)
        LEFT OUTER JOIN pdt.pais ON (k.idpais = pdt.pais.idpais)
      WHERE k.idkardex={$idkardex}";
    $query=$Conn->Execute($sql);
    $tipo_bien="";
    $idbien="";
    $tipo_codigoplacas="";
    $numero_codigoplacas="";
    $numserie="";
    $origen="";
    $ubigeo_pais="";
    $fecha_construccion="";
    $descotro="";
    //Fichero
    $fichero="";
    $secuencia_b=0;
    foreach ($query as $row) {
        $secuencia_b++;
        $idbien=$row['idbien'];
        /*
	*Array Permision de Llenado de Datos para Validar las Fuentes del Kardex
	*y solo se genere los datos Correctamente Llenados
	*/
	$array_tipo_codigoplacas=array('01','07','09');
	$array_serie=array('05');
	$array_origen=array('04');
	$array_otros=array('99');
	if(in_array($idbien, $array_tipo_codigoplacas)){
		$tipo_codigoplacas=$row['tipo_codigoplacas'];
		$numero_codigoplacas=$row['numero_codigoplacas'];
	}
	if(in_array($idbien, $array_serie)){
		$numserie=$row['numserie'];
	}
	if(in_array($idbien, $array_origen)){
		$origen=$row['origen'];
		if($origen==1){
			$ubigeo_pais=$row['ubigeo'];
		}else{
			$ubigeo_pais=$row['idpais'];
		}
        if($row['fecha_construccion']!='1900-01-01')
            $fecha_construccion=$row['fecha_construccion'];
	}
	if(in_array($idbien, $array_otros)){
		$descotro=$row['descotro'];
	}  
        $pdt=array(
        $tipo,$numero_escritura,
        $fecha_numeracion,
        $secuencia_aj,$secuencia_b,
        $tipo_bien,$idbien,
        $tipo_codigoplacas,$numero_codigoplacas,
        $numserie,$origen,$ubigeo_pais,
        $fecha_construccion,$descotro);
        $fichero.=returnRow($pdt);    
        getFicheroOtorgantes($Conn,$idkardex,$tipo,$numero_escritura,$fecha_numeracion,$secuencia_aj,$secuencia_b);
    }   
    return $fichero;
    
}
function getFicheroOtorgantes($Conn,$idkardex,$tipo,$numero_escritura,$fecha_numeracion,$secuencia_aj,$secuencia_b){
    global $fichero_o;
    $sql="
    SELECT 
  c.iddocumento,
  k.idparticipacion,
  c.idcliente_tipo,
  c.dni_ruc,
  c.nombres,
  c.nacionalidad,
  c.idubigeo
FROM
  public.cliente c
  INNER JOIN public.kardex_participantes k ON (c.idcliente = k.idparticipante) 
 WHERE k.idkardex={$idkardex} ";
 $q=$Conn->Execute($sql);
 $secuencia_o=0;
 $porcentajeparticipacion=100/count($q);
 
 foreach($q as $row){ 
     $secuencia_o++;
     $tipodocumento=$row['iddocumento'];
     $numerodocumento=$row['dni_ruc'];
     $tipootorgante=$row['idparticipacion'];
     $tipopersona="";
     $ubigeo_pais="";
//     $porcentaje_participacion="";
     $razonsocial_nombre="";
     $ape_paterno="";
     $ape_materno="";
     $nombres=$row['nombres'];
     $nombres=  explode("!", $nombres);
     if(count($nombres)==2){
         $ape_paterno=$nombres[0];
         $razonsocial_nombre=$nombres[1];
         $ape_materno="";
     }else if(count($nombres)==3){
        $razonsocial_nombre=$nombres[2];
        $ape_paterno=$nombres[0];
        $ape_materno=$nombres[1];
     }else{
        if(count($nombres)==0){
            $razonsocial_nombre="";
            $ape_paterno="";
            $ape_materno="";
        } else{
            $razonsocial_nombre="";
            $ape_paterno="";
            $ape_materno="";
        }
     }
    
     $primer_nombre="";
     $segundo_nombre="";
     $genero_renta3categoria="";
     $bien_enajenado="";
     $numero_operacion_1662="";
     $pago_segunda_categoria="";
    $pdt=array(
        $tipo,
        $numero_escritura,
        $fecha_numeracion,
        $secuencia_aj,
        $secuencia_b,
        $secuencia_o,
        $tipodocumento,
        $numerodocumento,
        $tipootorgante,
        $tipopersona,
        $ubigeo_pais,
        $porcentajeparticipacion,
        $razonsocial_nombre,
        $ape_paterno,
        $ape_materno,
        $primer_nombre,
        $segundo_nombre,
        $genero_renta3categoria,
        $bien_enajenado,
        $numero_operacion_1662,
        $pago_segunda_categoria);
    $fichero_o.=returnRow($pdt); 
 }
}
function getSelector($actojuridico){
    $arrayactos=array(
    array("1"),
    array("2","3","4","6"),
    array("7","8","9","10"),
    array("11","12","13","14"),
    array("15","16","17","18"),
    array("19","20","21","22"),
    array("23")
    );
    $selector="";
    foreach ($arrayactos as $key => $value) {
        foreach ($value as $key2 => $value2) {
            if(in_array($actojuridico, $value))$selector=$key;
        }
    }
    return $selector;
}
function returnRow($ar){
    array_push($ar,"\n");
    return implode("|",$ar);
}
function moneda_equi($idmoneda){
    $array=array(
        "2"=>"1",//Equivalencia de Codigo de Dolares
        "3"=>"2"//Equivalencia de Codigo de Soles
        );
    return isset($array[$idmoneda])?$array[$idmoneda]:'';
}
?>
