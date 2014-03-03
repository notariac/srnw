<?php 
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
function Completar($Str, $Agr){
    $tamanio = strlen($Str) +  $Agr;
    for ($i=1; $i<80 - $tamanio; $i++){
            $Str = $Str.".";
    }
    return $Str;
}	
function Generartf($IdKardex){	
        include("../../config.php");
        include("num2letraK.php");		
        $mes            = array("01"=>"ENERO", "02"=>"FEBRERO", "03"=>"MARZO", "04"=>"ABRIL", "05"=>"MAYO", "06"=>"JUNIO", "07"=>"JULIO", "08"=>"AGOSTO", "09"=>"SETIEMBRE", "10"=>"OCTUBRE", "11"=>"NOVIEMBRE", "12"=>"DICIEMBRE");	
        $SQL            = "SELECT kardex.correlativo, kardex.escritura, kardex.minuta, servicio.descripcion, kardex.fojainicio, kardex.serieinicio, kardex.fojafin, kardex.seriefin, ";
        $SQL            = $SQL."kardex.escritura_fecha, kardex.minuta_fecha, kardex.idservicio, kardex.ruta, kardex.hijo ";
        $SQL            = $SQL."FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) ";
        $SQL            = $SQL."WHERE kardex.idkardex = ".$IdKardex;
        $Consulta 	= $Conn->Query($SQL);
        $row 		= $Conn->FetchArray($Consulta);		
        $Kardex		= $row[0];
        $Escritura	= CantidadEnLetra($row[1]);
        $EscrituraFecha = $Conn->DecFecha($row[8]);
        $DiaL           = CantidadEnLetra((int)substr($row[8], 8, 2));
        $MesL           = $mes[substr($row[8], 5, 2)];
        $AnioL          = CantidadEnLetra((int)substr($row[8], 0, 4));			
        $Minuta		= CantidadEnLetra($row[2]);
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
        $PrecioProtestos = CantidadEnLetraP(str_replace(',', '', $row[11]));
        $SolicitanteProtestos = strtoupper($row[12]);		
        //Otorgante
        $SQL = "SELECT cliente.nombres, cliente.pais, estado_civil.descripcion, profesion.descripcion, documento.descripcion, ";
        $SQL = $SQL." cliente.dni_ruc, cliente.direccion, cliente.idubigeo ";
        $SQL = $SQL." FROM participacion INNER JOIN kardex_participantes ON (participacion.idparticipacion = kardex_participantes.idparticipacion) INNER JOIN cliente ON (kardex_participantes.idparticipante = cliente.idcliente) INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) INNER JOIN estado_civil ON (cliente.idestado_civil = estado_civil.idestado_civil) INNER JOIN profesion ON (cliente.idprofesion = profesion.idprofesion) WHERE participacion.tipo = 1 AND kardex_participantes.idkardex = ".$IdKardex;
        $ConsultaP 	= $Conn->Query($SQL);
        $rowP 		= $Conn->FetchArray($ConsultaP);			
        $NombresPP 	=  explode("!", $rowP[0]);			
        $NombreP	= utf8_decode($NombresPP[1]." ".$NombresPP[0]);
        $NacionalidadP	= $rowP[1];
        $EstadoCivilP	= $rowP[2];
        $ProfesionP	= $rowP[3];
        $DocumentoP	= $rowP[4];
        $DocumentoNroP	= $rowP[5];
        $DirP		= $rowP[6];
        $UbigeoP	= $rowP[7];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo = '".$UbigeoP."'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DistritoP	= $rowUP[0];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoP,0,4)."00'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $ProvinciaP	= $rowUP[0];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoP,0,2)."0000'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DepartamentoP	= $rowUP[0];		
        // Representante
        $SQLR = "SELECT cliente.nombres, cliente.dni_ruc, ocupacion.descripcion, estado_civil.descripcion, cliente.direccion, cliente.idubigeo ";
        $SQLR = $SQLR." FROM cliente_representante, cliente, ocupacion, estado_civil ";
        $SQLR = $SQLR."WHERE cliente.dni_ruc = cliente_representante.dni_representante AND ocupacion.idocupacion = cliente.idocupacion AND estado_civil.idestado_civil = cliente.idestado_civil AND ";
        $SQLR = $SQLR." cliente_representante.ruc_cliente = '".$DocumentoNroP."'";
        $ConsultaR      = $Conn->Query($SQLR);
        $rowR 		= $Conn->FetchArray($ConsultaR);				
        $NombresRP 	=  explode("!", $rowR[0]);			
        $NombreR	= utf8_decode($NombresRP[1]." ".$NombresRP[0]);
        $DocumentoNroR	= $rowR[1];
        $ProfesionR	= $rowR[2];
        $EstadoCivilR	= $rowR[3];
        $DirR		= $rowR[4];
        $UbigeoR	= $rowR[5];			
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo = '".$UbigeoR."'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DistritoR	= $rowUP[0];
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoR,0,4)."00'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $ProvinciaR	= $rowUP[0];
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoR,0,2)."0000'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DepartamentoR	= $rowUP[0];			
        //A Favor
        $SQL = "SELECT cliente.nombres, cliente.pais, estado_civil.descripcion, profesion.descripcion, documento.descripcion, ";
        $SQL = $SQL." cliente.dni_ruc, cliente.direccion, cliente.idubigeo ";
        $SQL = $SQL." FROM participacion INNER JOIN kardex_participantes ON (participacion.idparticipacion = kardex_participantes.idparticipacion) ";
        $SQL = $SQL." INNER JOIN cliente ON (kardex_participantes.idparticipante = cliente.idcliente) INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) ";
        $SQL = $SQL." INNER JOIN estado_civil ON (cliente.idestado_civil = estado_civil.idestado_civil) INNER JOIN profesion ON (cliente.idprofesion = profesion.idprofesion) ";
        $SQL = $SQL."WHERE participacion.tipo = 2 AND kardex_participantes.idkardex = ".$IdKardex;
        $ConsultaA 	= $Conn->Query($SQL);
        $rowA 		= $Conn->FetchArray($ConsultaA);			
        $NombresAA 	=  explode("!", $rowA[0]);			
        $NombreA	= utf8_decode($NombresAA[1]." ".$NombresAA[0]);
        $NacionalidadA	= $rowA[1];
        $EstadoCivilA	= $rowA[2];
        $ProfesionA	= $rowA[3];
        $DocumentoA	= $rowA[4];
        $DocumentoNroA	= $rowA[5];
        $DirA		= $rowA[6];
        $UbigeoA	= $rowA[7];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo = '".$UbigeoA."'";
        $ConsultaUA 	= $Conn->Query($SQL);
        $rowUA 		= $Conn->FetchArray($ConsultaUA);
        $DistritoA	= $rowUA[0];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoA,0,4)."00'";
        $ConsultaUA 	= $Conn->Query($SQL);
        $rowUA 		= $Conn->FetchArray($ConsultaUA);
        $ProvinciaA	= $rowUA[0];		
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoA,0,2)."0000'";
        $ConsultaUA 	= $Conn->Query($SQL);
        $rowUA 		= $Conn->FetchArray($ConsultaUA);
        $DepartamentoA	= $rowUA[0];
        // Representante
        $SQLR           = "SELECT cliente.nombres, cliente.dni_ruc, ocupacion.descripcion, estado_civil.descripcion, cliente.direccion, cliente.idubigeo ";
        $SQLR           = $SQLR." FROM cliente_representante, cliente, ocupacion, estado_civil ";
        $SQLR           = $SQLR."WHERE cliente.dni_ruc = cliente_representante.dni_representante AND ocupacion.idocupacion = cliente.idocupacion AND estado_civil.idestado_civil = cliente.idestado_civil AND ";
        $SQLR           = $SQLR." cliente_representante.ruc_cliente = '".$DocumentoNroA."'";
        $ConsultaR2	= $Conn->Query($SQLR);
        $rowR2 		= $Conn->FetchArray($ConsultaR2);				
        $NombresRP2 	= explode("!", $rowR2[0]);			
        $NombreR2	= utf8_decode($NombresRP2[1]." ".$NombresRP2[0]);
        $DocumentoNroR2	= $rowR2[1];
        $ProfesionR2	= $rowR2[2];
        $EstadoCivilR2	= $rowR2[3];
        $DirR2		= $rowR2[4];
        $UbigeoR2	= $rowR2[5];			
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo = '".$UbigeoR2."'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DistritoR2	= $rowUP[0];
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoR2,0,4)."00'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $ProvinciaR2	= $rowUP[0];
        $SQL            = "SELECT descripcion FROM ubigeo WHERE idubigeo LIKE '".substr($UbigeoR2,0,2)."0000'";
        $ConsultaUP 	= $Conn->Query($SQL);
        $rowUP 		= $Conn->FetchArray($ConsultaUP);
        $DepartamentoR2	= $rowUP[0];		
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
        $Destino = "archivos/".$Kardex.".rtf";		
        $Plantilla 	= "plantillas/plantilla-".$IdServicio.".rtf";		
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
        $despues = str_replace("#KARDEX#", $Kardex, $despues);
        $despues = str_replace("#NROESCRITURA#", $Escritura, $despues);
        $despues = str_replace("#FECHAESCRITURA#", $EscrituraFecha, $despues);
        $despues = str_replace("#NROMINUTA#", $Minuta, $despues);
        $despues = str_replace("#FECHAMINUTA#", $MinutaFecha, $despues);
        $despues = str_replace("#SERVICIO#", $Servicio, $despues);
        $despues = str_replace("#DIAL#", trim($DiaL), $despues);
        $despues = str_replace("#MESL#", trim($MesL), $despues);
        $despues = str_replace("#ANIOL#", trim($AnioL), $despues);
        $despues = str_replace("#DIAM#", trim($DiaM), $despues);
        $despues = str_replace("#MESM#", trim($MesM), $despues);
        $despues = str_replace("#ANIOM#", trim($AnioM), $despues);
        $despues = str_replace("#PRECIOPRO#", $PrecioProtestos, $despues);
        $despues = str_replace("#SOLICITANTEPRO#", $SolicitanteProtestos, $despues);		
        $despues = str_replace("#CIUDAD#", $DistritoN, $despues);
        $despues = str_replace("#PROVINCIA#", $ProvinciaN, $despues);
        $despues = str_replace("#DEPARTAMENTO#", $DepartamentoN, $despues);
        $despues = str_replace("#NOTARIO#", $NotarioN, $despues);		
        $despues = str_replace("#FOJAI#", $FojaI, $despues);
        $despues = str_replace("#FOJAF#", $FojaF, $despues);
        $despues = str_replace("#SERIEI#", $SerieI, $despues);
        $despues = str_replace("#SERIEF#", $SerieF, $despues);		
        //Otorgante
        $despues = str_replace("#PODERDANTE#", trim($NombreP), $despues);
        $despues = str_replace("#NACIONALIDADP#", trim($NacionalidadP), $despues);
        $despues = str_replace("#ECIVILP#", $EstadoCivilP, $despues);
        $despues = str_replace("#PROFESIONP#", trim($ProfesionP), $despues);
        $despues = str_replace("#DOCUMENTOIDENTIDADP#", $DocumentoP, $despues);
        $despues = str_replace("#NUMDOCP#", $DocumentoNroP, $despues);
        $despues = str_replace("#DIRP#", $DirP, $despues);
        $despues = str_replace("#DISTRITOP#", $DistritoP, $despues);
        $despues = str_replace("#PROVINCIAP#", $ProvinciaP, $despues);
        $despues = str_replace("#DEPARTAMENTOP#", $DepartamentoP, $despues);
        //Representante
        $despues = str_replace("#REPREP#", $NombreR, $despues);
        $despues = str_replace("#DNIREPREP#", $DocumentoNroR, $despues);
        $despues = str_replace("#PROFREPREP#", $ProfesionR, $despues);
        $despues = str_replace("#ECREPREP#", $EstadoCivilR, $despues);
        $despues = str_replace("#DIRP#", $DirR, $despues);
        $despues = str_replace("#DISTRITOP#", $DistritoR, $despues);
        $despues = str_replace("#PROVINCIAP#", $ProvinciaR, $despues);
        $despues = str_replace("#DEPARTAMENTOP#", $DepartamentoR, $despues);		
        //A Favor
        $despues = str_replace("#APODERADO#", $NombreA, $despues);
        $despues = str_replace("#NACIONALIDADA#", $NacionalidadA, $despues);
        $despues = str_replace("#ECIVILA#", $EstadoCivilA, $despues);
        $despues = str_replace("#PROFESIONA#", $ProfesionA, $despues);
        $despues = str_replace("#DOCUMENTOIDENTIDADA#", $DocumentoA, $despues);
        $despues = str_replace("#NUMDOCA#", $DocumentoNroA, $despues);
        $despues = str_replace("#DIRA#", $DirA, $despues);
        $despues = str_replace("#DISTRITOA#", $DistritoA, $despues);
        $despues = str_replace("#PROVINCIAA#", $ProvinciaA, $despues);
        $despues = str_replace("#DEPARTAMENTOA#", $DepartamentoA, $despues);
        //Representante
        $despues = str_replace("#REPREA#", $NombreR2, $despues);
        $despues = str_replace("#DNIREPREA#", $DocumentoNroR2, $despues);
        $despues = str_replace("#PROFREPREA#", $ProfesionR2, $despues);
        $despues = str_replace("#ECREPREA#", $EstadoCivilR2, $despues);
        $despues = str_replace("#DIRA#", $DirR2, $despues);
        $despues = str_replace("#DISTRITOA#", $DistritoR2, $despues);
        $despues = str_replace("#PROVINCIAA#", $ProvinciaR2, $despues);
        $despues = str_replace("#DEPARTAMENTOA#", $DepartamentoR2, $despues);		
        fputs($punt, $despues);
        $saltopag="\par \page \par";
        fputs($punt, $saltopag);		
        fputs($punt, "}");
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