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
$SqlM = "SELECT registrocartas.nroregistro, iteatencion.nroatencion, registrocartas.fecha, registrocartas.nrocarta, 
                iteatencion.idservicio, registrocartas.persremitente, registrocartas.empremitente, registrocartas.persdestinatario,
                 registrocartas.empdestinatario, registrocartas.direccion, distrito.idubigeo, registrocartas.idocurrencia, 
                 registrocartas.fechaocurrencia, registrocartas.datosocurrencia, registrocartas.mensajero, 
                 registrocartas.persrecepcion, registrocartas.fechaentrega, iteatencion.estado, registrocartas.idusuario, 
                 registrocartas.fechareg, registrocartas.anio 
         FROM registrocartas LEFT OUTER JOIN iteatencion ON (registrocartas.nrocarta = iteatencion.correlativo) 
         INNER JOIN distrito ON (registrocartas.iddepartamento = distrito.iddepartamento) 
         AND (registrocartas.idprovincia = public.distrito.idprovincia) 
         AND (registrocartas.iddistrito = public.distrito.iddistrito) 
         WHERE (iteatencion.idservicio = 118 OR iteatencion.idservicio = 245  )
            and registrocartas.fechareg = '2013-04-17'
        ORDER BY registrocartas.fechareg desc";

$ConsultaM = $ConnM->Query($SqlM);
if (!$ConsultaM) {echo $SqlM.$Conn->GetError()."<br>";}	
    $Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
    $Cont = 0;
    $Cont2 = 0;
    $Idx = 1;
    while($rowM = $ConnM->FetchArray($ConsultaM)){
        $Id			= $rowM[0];
        $IdAtencion		= $rowM[1];
        $Fecha			= $rowM[2];
        $NroCarta		= $rowM[3];
        $IdServicio		= $rowM[4];
        $Remitente		= trim(str_replace("'"," ",trim($rowM[5])));
        $RemitenteE		= trim(str_replace("'"," ",trim($rowM[6])));
        $Destinatario           = trim(str_replace("'"," ",trim($rowM[7])));
        $DestinatarioE          = trim(str_replace("'"," ",trim($rowM[8])));
        $Direccion		= trim(str_replace("'"," ",trim($rowM[9])));
        $Ubigeo			= trim($rowM[10]);
        $IdOcurrencia           = isset($rowM[11])?$rowM[11]:0;
        $OcurrenciaFec          = isset($rowM[12])?$rowM[12]:'1900-01-01';
        $Observacion            = trim(str_replace("'"," ",trim($rowM[13])));
        $Mensajero		= trim(str_replace("'"," ",trim($rowM[14])));
        $Recepciono		= trim(str_replace("'"," ",trim($rowM[15])));
        $EntregaFec		= isset($rowM[16])?$rowM[16]:'1900-01-01';		
        $Estado			= $rowM[17];
        $IdUsuario		= $rowM[18];
        $FecRegistro            = $rowM[19];
        $Anio			= $rowM[20];				
        $IdNotaria		= 1;
        $IdCliente              = 0;
        $Hora                   = date("H:i:s");			
        $Sql = "SELECT * FROM carta WHERE idcarta='$Id' AND correlativo='$NroCarta' AND anio='$Anio'";
        $Consulta = $Conn->Query($Sql);
        $Numero_Rows = $Conn->NroRegistros($Consulta);		
        if ($Numero_Rows==0 ){		
            $SqlA = "SELECT * FROM atencion WHERE idatencion='$IdAtencion'";
            $ConsultaA = $Conn->Query($SqlA);
            $Numero_RowsA = $Conn->NroRegistros($ConsultaA);
            if ($Numero_RowsA==0){
                $SqlA = "INSERT INTO atencion (idatencion, direccion, fecha, estado, idusuario, fechareg, idnotaria, anio, hora, idcliente, correlativo) VALUES('$IdAtencion', '$Direccion', '$Fecha', '$Estado', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio', '$Hora', '$IdCliente', '0')";				
                $ConsultaA = $Conn->Query($SqlA);
                if (!$ConsultaA) {echo $SqlA.$Conn->GetError()."<br>";}				
                $SqlDA = "INSERT INTO atencion_detalle (idatencion, idservicio, item, cantidad, folios, monto, correlativo, estado, idusuario, fechareg, idnotaria, anio) VALUES('$IdAtencion', '$IdServicio', '1', '1', '0', '0', '$NroCarta', '$Estado', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio')";				
                $ConsultaDA = $Conn->Query($SqlDA);
                if (!$ConsultaDA) {echo $SqlDA.$Conn->GetError()."<br>";}
            }			
            $SqlC = "INSERT INTO carta (idcarta, idatencion, fecha, correlativo, idservicio, remitente, remitente_empresa, destinatario, destinatario_empresa, direccion, idubigeo, idocurrencia, ocurrencia_fecha, observaciones, mensajero, recepciono, entrega_fecha, estado, idusuario, fechareg, anio, idnotaria)
                         VALUES('$Idx', '$IdAtencion', '$Fecha', '$NroCarta', '$IdServicio', '$Remitente', '$RemitenteE', '$Destinatario', '$DestinatarioE', '$Direccion', '$Ubigeo', '$IdOcurrencia', '$OcurrenciaFec', '$Observacion', '$Mensajero', '$Recepciono', '$EntregaFec', '$Estado', '$IdUsuario', '$FecRegistro', '$Anio', '$IdNotaria')";
            $ConsultaC = $Conn->Query($SqlC);
            if (!$ConsultaC){
                echo $Sql."<br>".$SqlC."<br>".$Conn->GetError()."<br>";
                $Cont2 = $Cont2 + 1;
            }
            $ConsultaS = $Conn->Query("SELECT NEXTVAL('carta_idcarta_seq')");
            $rowS = $Conn->FetchArray($ConsultaS);
            if ($Id>=$rowS[0]){
                $ConsultaSeg = $Conn->Query('ALTER SEQUENCE carta_idcarta_seq restart '.$Id);
            }
        }
        if (!$ConsultaC){}else{ $Cont = $Cont + 1; }
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