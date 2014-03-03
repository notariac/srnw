<?php
    $IdLibro = $_POST["IdLibro"];	
    echo "<meta http-equiv='content-type' content='text/html; charset=UTF-8' />";	
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
    function relleno($texo){
        $t=strlen($texto);
        $cad = str_pad($texo, 70,'.');
        return $cad;
    }
    function Generartf($IdLibro){	
        include("../../config.php");	
        $SQL = "SELECT libro.razonsocial, libro.ruc, libro.direccion, libro_tipo.descripcion, libro.numero, libro.folio_inicial, libro.folio_final, ";
        $SQL = $SQL."libro_numeracion_tipo.descripcion, libro.solicitante, libro.solicitante_dni, libro.anio, libro.fecha, libro.idlibro, libro.correlativo ";
        $SQL = $SQL."FROM libro INNER JOIN libro_tipo ON (libro.idlibro_tipo = libro_tipo.idlibro_tipo) ";
        $SQL = $SQL."INNER JOIN libro_numeracion_tipo ON (libro.idlibro_numeracion_tipo = libro_numeracion_tipo.idlibro_numeracion_tipo) ";
        $SQL = $SQL."WHERE libro.idlibro ='$IdLibro' ";
        $Consulta 	= $Conn->Query($SQL);
        $row 		= $Conn->FetchArray($Consulta);
        $NroLibro		= $row[13];
        $Anio			= $row[10];
        $Destino = "archivos/$NroLibro-$Anio.rtf"; 
        $Plantilla 	= "plantilla.rtf";
        $txtplantilla	= leef($Plantilla);
        $matriz			= explode("sectd", $txtplantilla);
        $cabecera		= $matriz[0]."sectd";
        $inicio			= strlen($cabecera);
        $final			= strrpos($txtplantilla, "}");
        $largo			= $final - $inicio;
        $cuerpo			= substr($txtplantilla, $inicio, $largo);
        $punt			= fopen($Destino, "w");
        fputs($punt, $cabecera);
        $mes	= array("01"=>"Enero", "02"=>"Febrero", "03"=>"Marzo", "04"=>"Abril", "05"=>"Mayo", "06"=>"Junio", "07"=>"Julio", "08"=>"Agosto", "09"=>"Setiembre", "10"=>"Octubre", "11"=>"Noviembre", "12"=>"Diciembre");
        $Mes1 	= substr($row[11], 5, 7);
        $Mes2	= $mes[substr($Mes1, 0, 2)];
        $Fecha 	= substr($row[11], 8, 10)." de ".$Mes2." del ".substr($row[11], 0, 4); 
        $RazonSocial 	= ucwords(strtolower(utf8_decode($row[0])));
        $despues = $cuerpo;
        $rz=utf8_decode("R.U.C. ".$row[1]);
        $despues = str_replace("#RUC#", relleno($rz), $despues);
        $despues = str_replace("DIRECCION", relleno(utf8_decode(ucwords(strtolower($row[2])))), $despues);
        $despues = str_replace("#CUADERNO#", relleno(utf8_decode(ucwords(strtolower($row[3])))), $despues);
        $despues = str_replace("#NUMERO#", relleno($row[4]), $despues);
        $despues = str_replace("#FOLIOSI#", $row[5], $despues);
        $despues = str_replace("#FOLIOSF#", $row[6], $despues);
        $despues = str_replace("#NUMERACION#", utf8_decode(ucwords(strtolower($row[7]))), $despues);
        $despues = str_replace("#SOLICITANTE#", relleno(utf8_decode(ucwords(strtolower($row[8])))), $despues);
        $despues = str_replace("#DNI#", Completar($row[9], 26), $despues);
        $despues = str_replace("#NROLIBRO#", $NroLibro."-".$Anio, $despues);
        $despues = str_replace("#FECHA#", $Fecha, $despues);        
        
        $despues = str_replace("#RAZON#", strtoupper(relleno($RazonSocial)), $despues);
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
    <td align="center" style="font-family:arial; font-size:18px; color:#090">Felicitaciones!</td>
  </tr>
  <tr>
    <td style="font-family:arial; font-size:18px; color:#090">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" style="font-family:arial; font-size:12px; color:#090">El Libro se ha Generado Correctamente</td>
  </tr>
  <tr>
    <td>
<?php
$salida	= Generartf($IdLibro);
?>
    <input type="hidden" name="RutaArchivo" id="RutaArchivo" value="<?php echo $salida;?>"/></td>
  </tr>
</table>
