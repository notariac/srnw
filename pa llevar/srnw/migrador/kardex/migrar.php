<?php
include("../../config.php");
include("../config.php");
?>
<script type="text/javascript" src="../../js/jquery.js"></script>
<style type="text/css">
<!--
.Estilo1 {
    color: #003399;
    font-weight: bold;
    font-style: italic;
    vertical-align: middle;
}
body {
    margin-left: 0px;
    margin-top: 0px;
}
-->
</style>	
<table width='250' border='0' cellspacing='0' cellpadding="0">
	<tr>
            <td width="180">
                <div id="DivAvance">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#009900" id="tbPorcentaje">
                  <tr>
                        <td>&nbsp;</td>
                  </tr>
                </table>
                </div>
            </td>
	    <td width="70">
                <span class="Estilo1">
                    <div align="right" id="DivPorciento"></div>
                </span>		
            </td>
	</tr>
</table>
<?php
set_time_limit(0);	
$SqlM = "SELECT cabgeneracion.nrogeneracion, 
                iteatencion.nroatencion, 
                cabgeneracion.fecha, 
                cabgeneracion.kardex, 
                cabgeneracion.idservicio, 
                cabgeneracion.nroescritura, 
                cabgeneracion.nrominuta, 
                cabgeneracion.nroplaca, 
                cabgeneracion.fojainicio, 
                cabgeneracion.fojafinal, 
                cabgeneracion.nroinicio, 
                cabgeneracion.nrofinal, 
                cabgeneracion.archivo, 
                cabgeneracion.firmado, 
                cabgeneracion.fechafirma, 
                cabgeneracion.anio, 
                cabgeneracion.estado, 
                cabgeneracion.fechareg, 
                cabgeneracion.idusuario 
            FROM cabgeneracion LEFT OUTER JOIN iteatencion ON 
                (cabgeneracion.kardex = iteatencion.nrokardex) 
            WHERE cabgeneracion.fechareg = '2013-04-17'
            ORDER BY cabgeneracion.nrogeneracion ASC";	
//die($SqlM);
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont  = 0;
$Cont2 = 0;
$Idx   = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){		
    $FojaInicio		= 0;
    $FojaFin		= 0;
    $NroInicio		= 0;
    $NroFin		= 0;		
    $Id			= $rowM[0];
    $NroAtencion        = $rowM[1];
    $Fecha		= $rowM[2];
    $Correlativo    = $rowM[3];
    $IdServicio		= $rowM[4];
    $Escritura		= $rowM[5];
    $Minuta		= $rowM[6];
    $Placa		= $rowM[7];
    $FojaInicio		= $rowM[8];
    $FojaFin		= $rowM[9];
    $NroInicio		= $rowM[10];
    $NroFin		= $rowM[11];
    $Archivo		= $rowM[12];
    $Firmado		= $rowM[13];
    $FirmadoFecha       = isset($rowM[14])?$rowM[14]:'01/01/1900';
    $Anio		= $rowM[15];		
    $Estado		= $rowM[16] + 1;
    $IdUsuario		= $rowM[18];
    $FecRegistro        = $rowM[17];
    $IdCliente          = 0;//trim(str_replace("'"," ",trim($rowM[1])));
    $IdNotaria          = 1;
    $Direccion          = "";
    $Vacio              = "";
    $Hora               = date("H:i:s");				
    $Sql = "SELECT * FROM kardex WHERE idkardex='$Id'";
    $Consulta = $Conn->Query($Sql);
    $Numero_Rows = $Conn->NroRegistros($Consulta);		
    if ($Numero_Rows==0)
    {
        $SqlA = "SELECT * FROM atencion WHERE idatencion='$NroAtencion' ";
        $ConsultaA = $Conn->Query($SqlA);
        $Numero_RowsA = $Conn->NroRegistros($ConsultaA);
        if ($Numero_RowsA==0)
        {
            //$ConsultaA = $Conn->Query("SELECT ");
            $SqlA = "INSERT INTO atencion (idatencion, direccion, fecha, estado, idusuario, fechareg, idnotaria, anio, hora, idcliente, correlativo) VALUES('$NroAtencion', '$Direccion', '$Fecha', '$Estado', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio', '$Hora', '$IdCliente', '1')";				
            echo $NroAtencion."<br/>";
            $ConsultaA = $Conn->Query($SqlA);
            if (!$ConsultaA) {echo $SqlA.$Conn->GetError()."<br>";}				
            $SqlDA = "INSERT INTO atencion_detalle (idatencion, idservicio, item, cantidad, folios, monto, correlativo, estado, idusuario, fechareg, idnotaria, anio) VALUES('$NroAtencion', '$IdServicio', '1', '1', '1', '0', '$Correlativo', '1', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio')";				
            $ConsultaDA = $Conn->Query($SqlDA);
            if (!$ConsultaDA) { echo $SqlDA.$Conn->GetError()."<br>"; }
        }			
        $Sql = "INSERT INTO kardex ( idatencion, fecha, correlativo, idservicio, escritura, minuta, placa, fojainicio, fojafin, serieinicio, seriefin, archivo, firmado, firmadofecha, estado, idusuario, fechareg, anio, idnotaria, escritura_fecha, minuta_fecha, hijo, ruta, motivo, via) 
                VALUES( '$NroAtencion', '$Fecha', '$Correlativo', '$IdServicio', '$Escritura', '$Minuta', '$Placa', '$FojaInicio', '$FojaFin', '$NroInicio', '$NroFin', '$Archivo', '$Firmado', '$FirmadoFecha', '$Estado', '$IdUsuario', '$FecRegistro', '$Anio', '$IdNotaria', '$Fecha', '$Fecha', '$Vacio', '$Vacio', '$Vacio' ,'$Vacio')";			
        $Consulta = $Conn->Query($Sql);
        $ConsultaS = $Conn->Query("SELECT NEXTVAL('kardex_idkardex_seq')");
        $rowS = $Conn->FetchArray($ConsultaS);
        if ($Id>=$rowS[0]){
            $ConsultaSeg = $Conn->Query('ALTER SEQUENCE kardex_idkardex_seq restart '.$Id);
        }
    }
    if (!$Consulta){
        echo $Sql.$Conn->GetError()."<br>"; 
        $Cont2 = $Cont2 + 1;
    }else{
        $Cont = $Cont + 1;
    }
    $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
    echo "<script> $('#tbPorcentaje').attr('width', '$Porcentaje%'); </script>";
    echo "<script> $('#DivPorciento').html('$Porcentaje%'); </script>";
    $Idx++;
}
if ($Porcentaje==100){
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
}else{
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/warning.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n con problemas'); </script>";
}
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>