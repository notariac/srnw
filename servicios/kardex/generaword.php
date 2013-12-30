<?php 
session_start();
include('func.php');
include("num2letraK.php");    
//include('../../libs/num2letra.php');
$IdKardex = $_POST["IdKardex"];
echo "<meta http-equiv='content-type' content='text/html; charset=iso-8859-1' />";	
function leef($fichero){
    $texto = file($fichero);
    $tamleef = sizeof($texto);
    for($n=0; $n<$tamleef; $n++){
            $todo = $todo.$texto[$n];
    }
    return $todo;
}	
function Completar($Str, $Agr)
{
    $tamanio = strlen($Str) +  $Agr;
    for ($i=1; $i<80 - $tamanio; $i++){
            $Str = $Str.".";
    }
    return $Str;
}	
function Generartf($IdKardex)
{	
        include("../../config.php");        
        $mes            = array("01"=>"ENERO", "02"=>"FEBRERO", "03"=>"MARZO", "04"=>"ABRIL", "05"=>"MAYO", "06"=>"JUNIO", "07"=>"JULIO", "08"=>"AGOSTO", "09"=>"SETIEMBRE", "10"=>"OCTUBRE", "11"=>"NOVIEMBRE", "12"=>"DICIEMBRE");	
        $SQL            = "SELECT   kardex.correlativo, 
                                    kardex.escritura, 
                                    kardex.minuta, 
                                    servicio.descripcion, 
                                    kardex.fojainicio, 
                                    kardex.serieinicio, 
                                    kardex.fojafin, 
                                    kardex.seriefin, 
                                    kardex.escritura_fecha, 
                                    kardex.minuta_fecha, 
                                    kardex.idservicio, 
                                    kardex.ruta, 
                                    kardex.hijo,
                                    kardex.descripcion,
                                    kardex.archivom,
                                    kardex.clasev, 
                                    kardex.marcav, 
                                    kardex.aniofabv, 
                                    kardex.modelov, 
                                    kardex.colorv,
                                    kardex.motorv, 
                                    kardex.cilindrosv, 
                                    kardex.seriev, 
                                    kardex.ruedasv, 
                                    kardex.combustiblev, 
                                    kardex.fechaincripcionv, 
                                    kardex.carroceriav,
                                    kardex.placa,
                                    kardex.via,
                                    kardex.fecha_salida,
                                    kardex.fecha_retorno,
                                    kardex.motivo,
                                    coalesce(kardex.monto,0) as monto
                            FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) 
                            WHERE kardex.idkardex = ".$IdKardex;        
        $Consulta 	= $Conn->Query($SQL);
        $row 		= $Conn->FetchArray($Consulta);		
        $Kardex		= $row[0];
        $Escritura	= CantidadEnLetra($row[1]);
        $EscrituraFecha = $Conn->DecFecha($row[8]);
        $DiaL           = CantidadEnLetra((int)substr($row[8], 8, 2));
        $MesL           = $mes[substr($row[8], 5, 2)];
        $AnioL          = CantidadEnLetra((int)substr($row[8], 0, 4));			
        $Minuta		      = CantidadEnLetra($row[2]);
        $MinutaFecha    = $Conn->DecFecha($row[9]);
        $DiaM           = CantidadEnLetra((int)substr($row[9], 8, 2));
        $MesM           = $mes[substr($row[9], 5, 2)];
        $AnioM          = CantidadEnLetra((int)substr($row[9], 0, 4));			
        $IdServicio	= $row[10];
        $Servicio	= $row[3];
        $FojaI		= $row[4];
        $SerieI		= $row[5];
        $FojaF		= $row[6];
        $SerieF		= $row[7];
        $idservicio = $row['idservicio'];
        $monto    = $row['monto'];
        $descripcion = $row['descripcion'];
        $minuta = trim($row['archivom']);
        $via = $row['via'];
        $fecha_salida = $Conn->DecFecha($row['fecha_salida']);
        $fecha_retorno = $Conn->DecFecha($row['fecha_retorno']);
        $ruta = $row['ruta'];
        
        $cuerpoVehiculo = cuerpoVehiculo(array($row['clasev'], $row['marcav'], $row['aniofabv'], $row['modelov'], $row['colorv'], $row['motorv'], $row['cilindrosv'], $row['seriev'], $row['ruedasv'], $row['combustiblev'], $row['fechaincripcionv'], $row['carroceriav'], $row['placa']));
        
        $PrecioProtestos = CantidadEnLetraP(str_replace(',', '', $row[11]));
        $SolicitanteProtestos = strtoupper($row[12]);	



        $s = "SELECT 
                kardex_participantes.idkardex, 
                documento.descripcion as documento, 
                kardex_participantes.idparticipante, 
                cliente.dni_ruc, 
                cliente.nombres||' '||coalesce(cliente.ape_paterno,' ')||' '||coalesce(cliente.ap_materno,' ') as nombres, 
                kardex_participantes.idparticipacion, 
                participacion.descripcion as participacion,
                kardex_participantes.porcentage,
                coalesce(kardex_participantes.idrepresentado,0) as idrepresentado, 
                kardex_participantes.tipo,
                kardex_participantes.conyuge,
                kardex_participantes.porcentage,
                kardex_participantes.partida,
                kardex_participantes.idzona,
                zr.zona as zona,
                cliente.direccion,
                cliente.fecha_nac,
                case cliente.idubigeo when '000000' then 'Distrito' else distrito.descripcion end as distrito,
                case cliente.idubigeo when '000000' then 'Provincia' else  provincia.descripcion end as provincia,
                case cliente.idubigeo when '000000' then 'Departamento' else departamento.descripcion end as departamento,
                ec.descripcion as estado_civil,
                cliente.sexo,
                cliente.nacionalidad,
                cliente.pais,                
                case cliente.idprofesion when 999 then cliente.otra_profesion else pro.descripcion end as ocupacion,
                cliente.idcliente_tipo
                FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) 
                INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) 
                INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) 
                inner join ubigeo as distrito on distrito.idubigeo = cliente.idubigeo
                inner join ubigeo as provincia on provincia.idubigeo = substr(cliente.idubigeo,1,4)||'00'
                inner join ubigeo as departamento on departamento.idubigeo = substr(cliente.idubigeo,1,2)||'0000'
                inner join estado_civil as ec on ec.idestado_civil = cliente.idestado_civil
                inner join ro.profesion as pro on pro.idprofesion = cliente.idprofesion
                left outer join ro.zona_registral as zr on zr.idzona = kardex_participantes.idzona 
                where kardex_participantes.idkardex = ".$IdKardex." order by tipo";    
    //echo $s;
    $qp = $Conn->Query($s);    
    $par = array();
    $participacion = "";
    $cont = 1;    
    $lastp = "";

    $data = array();

    while($p = $Conn->FetchArray($qp))
    {                
          $pp = strtolower(trim(str_replace(" ","", $p['participacion'])));
          if($pp==$participacion)
          {
              $cont = $cont+1;            
              $participacion = $pp;
          }
          $pp = $pp.$cont;      

          $par[] = array(
                          'nombres'=>strtoupper($p['nombres']),   //Nombre 
                          'participacion'=>$pp,                   //Participacion
                          'd'.$pp=>$p['dni_ruc'],                         //Nro de Documento
                          'td'.$pp=>$p['documento'],                        //Tipo de Documento
                          'dir'.$pp=>$p['direccion'],                       //Direccion
                          'edad'.$pp=>calcular_edad($p['fecha_nac']),       //Edad
                          'fecha_nac'.$pp=>$p['fecha_nac'],                 //Fecha de Nacimiento
                          'distrito'.$pp=>$p['distrito'],
                          'provincia'.$pp=>$p['provincia'],
                          'departamento'.$pp=>$p['departamento'],
                          'estado_civil'.$pp=>$p['estado_civil']
                         );

          $data[] = array('idparticipante'=>$p['idparticipante'],
                          'participante'=>$p['nombres'],
                          'documento'=>$p['documento'],
                          'nrodocumento'=>$p['dni_ruc'],
                          'idparticipacion'=>$p['idparticipacion'],
                          'participacion'=>$p['participacion'],
                          'tipo'=> $p['tipo'],
                          'idrepresentado'=>$p['idrepresentado'],
                          'conyuge'=>values($p['conyuge']),
                          'porcentage'=>$p['porcentage'],
                          'partida'=>$p['partida'],
                          'idzona'=>$p['idzona'],
                          'zona'=>$p['zona'],
                          'distrito'=>$p['distrito'],
                          'provincia'=>$p['provincia'],
                          'departamento'=>$p['departamento'],
                          'estado_civil'=>$p['estado_civil'],
                          'dir'=>$p['direccion'],                       //Direccion
                          'edad'=>calcular_edad($p['fecha_nac']),       //Edad
                          'fecha_nac'=>$p['fecha_nac'],
                          'sexo'=>$p['sexo'],
                          'nacionalidad'=>$p['nacionalidad'],
                          'pais'=>$p['pais'],
                          'ocupacion'=>$p['ocupacion'],
                          'idcliente_tipo'=>$p['idcliente_tipo'],
                          'es_conyuge'=>0,
                          'fecha_nac'=>$p['fecha_nac']
          );

        if($p['conyuge']!="")
        {
          $s = "SELECT cliente.idcliente,
                       cliente.dni_ruc, 
                       cliente.nombres||' '||coalesce(cliente.ape_paterno,' ')||' '||coalesce(cliente.ap_materno,' ') as nombres, 
                       documento.descripcion as documento,
                       cliente.direccion,
                       cliente.fecha_nac,
                       case cliente.idubigeo when '000000' then 'Distrito' else distrito.descripcion end as distrito,
                       case cliente.idubigeo when '000000' then 'Provincia' else  provincia.descripcion end as provincia,
                       case cliente.idubigeo when '000000' then 'Departamento' else departamento.descripcion end as departamento,
                       'CASADA' as estado_civil,
                       cliente.sexo,
                       cliente.nacionalidad,
                       cliente.pais,
                       case cliente.idprofesion when 999 then cliente.otra_profesion else pro.descripcion end as ocupacion
                 from cliente 
                    INNER JOIN documento ON cliente.iddocumento = documento.iddocumento 
                    inner join ubigeo as distrito on distrito.idubigeo = cliente.idubigeo
                    inner join ubigeo as provincia on provincia.idubigeo = substr(cliente.idubigeo,1,4)||'00'
                    inner join ubigeo as departamento on departamento.idubigeo = substr(cliente.idubigeo,1,2)||'0000'
                    inner join estado_civil as ec on ec.idestado_civil = cliente.idestado_civil
                    inner join ro.profesion as pro on pro.idprofesion = cliente.idprofesion
                 where idcliente = ".$p['conyuge'];
              $q = $Conn->Query($s);
              while($r = $Conn->FetchArray($q))
              {
                $data[] = array('idparticipante'=>$r['idcliente'],
                    'participante'=>$r['nombres'],
                    'documento'=>$r['documento'],
                    'nrodocumento'=>$p['dni_ruc'],
                    'idparticipacion'=>$p['idparticipacion'],
                    'participacion'=>$p['participacion'],
                    'tipo'=> $p['tipo'],
                    'idrepresentado'=>'NULL',
                    'conyuge'=>'NULL',
                    'porcentage'=>$p['porcentage'],
                    'partida'=>'',
                    'idzona'=>'',
                    'zona'=>'',
                    'distrito'=>$r['distrito'],
                    'provincia'=>$r['provincia'],
                    'departamento'=>$r['departamento'],
                    'estado_civil'=>$r['estado_civil'],
                    'dir'=>$r['direccion'],                       //Direccion
                    'edad'=>calcular_edad($r['fecha_nac']),       //Edad
                    'fecha_nac'=>$r['fecha_nac'],
                    'sexo'=>$r['sexo'],
                    'nacionalidad'=>$p['nacionalidad'],
                    'pais'=>$p['pais'],
                    'ocupacion'=>$r['ocupacion'],
                    'idcliente_tipo'=>1,
                    'es_conyuge'=>1,
                    'fecha_nac'=>$p['fecha_nac']
                    );
              }
        }

    }

    //print_r($data);

        //Datos Notaria Notaria
        $SQL            = "SELECT notario, idubigeo FROM notaria";
        $ConsultaN 	= $Conn->Query($SQL);
        $rowN 		= $Conn->FetchArray($ConsultaN);		
        $NotarioN       = $rowN[0];
        $UbigeoN        = $rowN[1];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo = '".$UbigeoN."'";
        $ConsultaUN 	= $Conn->Query($SQL);
        $rowUN 		= $Conn->FetchArray($ConsultaUN);
        $DistritoN	= $rowUN[0];	
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoN,0,4)."00'";
        $ConsultaUN 	= $Conn->Query($SQL);
        $rowUN 		= $Conn->FetchArray($ConsultaUN);
        $ProvinciaN	= $rowUN[0];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoN,0,2)."0000'";
        $ConsultaUN 	= $Conn->Query($SQL);
        $rowUN 		= $Conn->FetchArray($ConsultaUN);
        $DepartamentoN	= $rowUN[0];

        $Destino = "archivos/".$_SESSION['notaria']."/".$Kardex.".doc";		
        $Plantilla 	= "plantillas/".$_SESSION['notaria']."/plantilla-".$IdServicio.".rtf";

        if(!file_exists($Plantilla))
          $Plantilla = "plantillas/plantilla-0.rtf";

        $minuta = "minutas/".$_SESSION['notaria']."/".$minuta;        

        $txtminuta = leef($minuta);
        $matrizm	= explode("sectd", $txtminuta);
        $cabeceram	= $matrizm[0]."sectd";
        $iniciom	= strlen($cabeceram);
        $finalm		= strrpos($txtminuta, "}");
        $largom		= $finalm - $iniciom;
        $cuerpom	= substr($txtminuta, $iniciom, $largom);            

        $txtplantilla	= leef($Plantilla);
        $matriz		= explode("sectd", $txtplantilla);
        $cabecera	= $matriz[0]."sectd";
        $inicio		= strlen($cabecera);
        $final		= strrpos($txtplantilla, "}");
        $largo		= $final - $inicio;
        $cuerpo		= substr($txtplantilla, $inicio, $largo);
        
        $punt		= fopen($Destino, "w");
        fputs($punt, $cabecera);
        $Mes1           = substr($row[11], 5, 7);
        $Mes2           = $mes[substr($Mes1, 0, 2)];
        $Fecha          = substr($row[11], 8, 10)." de ".$Mes2." del ".substr($row[11], 0, 4); 		
        $despues = $cuerpo;
        
        $despues = str_replace("#KARDEX#", validValur($Kardex), $despues);
        $despues = str_replace("#NROESCRITURA#", validValur($Escritura), $despues);
        $despues = str_replace("#FECHAESCRITURA#", validValur($EscrituraFecha), $despues);
        $despues = str_replace("#NROMINUTA#", validValur($Minuta), $despues);
        $despues = str_replace("#FECHAMINUTA#", validValur($MinutaFecha), $despues);
        $despues = str_replace("#SERVICIO#", validValur($Servicio), $despues);
        $despues = str_replace("#DIAL#", validValur(trim($DiaL)), $despues);
        $despues = str_replace("#MESL#", validValur(trim($MesL)), $despues);
        $despues = str_replace("#ANIOL#", validValur(trim($AnioL)), $despues);
        $despues = str_replace("#DIAM#", validValur(trim($DiaM)), $despues);
        $despues = str_replace("#MESM#", validValur(trim($MesM)), $despues);
        $despues = str_replace("#ANIOM#", validValur(trim($AnioM)), $despues);
        $despues = str_replace("#PRECIOPRO#", validValur($PrecioProtestos), $despues);
        $despues = str_replace("#SOLICITANTEPRO#", validValur($SolicitanteProtestos), $despues);		
        $despues = str_replace("#CIUDAD#", validValur($DistritoN), $despues);
        $despues = str_replace("#PROVINCIA#", validValur($ProvinciaN), $despues);
        $despues = str_replace("#DEPARTAMENTO#", validValur($DepartamentoN), $despues);
        $despues = str_replace("#NOTARIO#", validValur($NotarioN), $despues);		
        $despues = str_replace("#FOJAI#", validValur($FojaI), $despues);
        $despues = str_replace("#FOJAF#", validValur($FojaF), $despues);
        $despues = str_replace("#SERIEI#", validValur($SerieI), $despues);
        $despues = str_replace("#SERIEF#", validValur($SerieF), $despues);		
        $despues = str_replace("#DESC_BIEN#", validValur($descripcion), $despues);
        $monto_letra = CantidadEnLetraP($monto);
        $despues = str_replace("#MONTO_LETRA#", $monto_letra, $despues);
        $despues = str_replace("#MONTO#", number_format($monto,2), $despues);

        $despues = str_replace("#VIA#", validValur($via), $despues);
        $despues = str_replace("#FECHA_SALIDA#", validValur($fecha_salida), $despues);
        $despues = str_replace("#FECHA_RETORNO#", validValur($fecha_retorno), $despues);
        $despues = str_replace("#RUTA#", validValur($ruta), $despues);
        
        $part = participantes($data,$IdServicio);
        $despues = str_replace("#PARTICIPANTES#", $part, $despues);       
        $otor = verOtorgantes($data);
        $despues = str_replace("#OTORGANTES#", $otor, $despues);
        $fav = verFavorecidos($data);
        $despues = str_replace("#FAVORECIDOS#", $fav, $despues);
        $interv = verIntervinientes($data);
        $despues = str_replace("#INTERVINIENTES#", $interv, $despues);                  
        $despues = str_replace("#CUERPOMINUTA#", $cuerpom, $despues);             
        $part_firma = participantes_firma($data);
        $despues = str_replace("#PARTICIPANTES_FIRMA#", $part_firma, $despues);

        //********************
        //**Casos especiales**
        //********************
        //Para plantillas vehiculares
          $despues = str_replace("#CUERPOVEHI#", $cuerpoVehiculo, $despues);          

        //Participantes para autorizaciones de viajes 
          $txt = participantes_v($data,$IdServicio);
          $despues = str_replace("#PARTICIPANTES_V#", $txt, $despues);   

        //Datos del menor, para autorizaciones de viajes
          $txt = datos_menor($data);
          $despues = str_replace("#DATOS_MENOR#", $txt, $despues); 

        //
        $part_firma = participantes_firma_v($data);
        $despues = str_replace("#PARTICIPANTES_FIRMA_V#", $part_firma, $despues);                           

        foreach ($par as $key => $value) 
        {   
            $participacion = $value['participacion'];            
            $despues = str_replace("#".$participacion."#", utf8_decode($value['nombres']), $despues);
            $despues = str_replace("#d".$participacion."#", strtoupper($value['d'.$value['participacion']]), $despues);		  
            $despues = str_replace("#td".$participacion."#", strtoupper($value['td'.$value['participacion']]), $despues);
            $despues = str_replace("#dir".$participacion."#", strtoupper(utf8_decode($value['dir'.$value['participacion']])), $despues);                   
            $despues = str_replace("#edad_text".$participacion."#", num2letra($value['edad'.$value['participacion']]), $despues);
            $despues = str_replace("#edad".$participacion."#", strtoupper($value['edad'.$value['participacion']]), $despues);
            $despues = str_replace("#distrito".$participacion."#", strtoupper($value['distrito'.$value['participacion']]), $despues);
            $despues = str_replace("#provincia".$participacion."#", strtoupper($value['provincia'.$value['participacion']]), $despues);
            $despues = str_replace("#departamento".$participacion."#", strtoupper($value['departamento'.$value['participacion']]), $despues);
            $despues = str_replace("#estado_civil".$participacion."#", strtoupper($value['estado_civil'.$value['participacion']]), $despues);
        }

        fputs($punt, $despues);
        $saltopag="\par \page \par";
        fputs($punt, $saltopag);		
        fputs($punt, "}");
        chmod($Destino, 0777);     
        fclose($punt);		
        return $Destino;
}
?>
<table width="250" border="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" style="font-family:arial; font-size:18px; color:#090">¡Felicitaciones!</td>
  </tr>
  <tr>
    <td style="font-family:arial; font-size:18px; color:#090">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" style="font-family:arial; font-size:12px; color:#090">La Plantilla fue Generada Correctamente</td>
  </tr>
  <tr>
    <td>
  <?php
    $salida = Generartf($IdKardex);    
  ?>
    <input type="hidden" name="RutaArchivo" id="RutaArchivo" value="<?php echo $salida;?>"/></td>
  </tr>
</table>
