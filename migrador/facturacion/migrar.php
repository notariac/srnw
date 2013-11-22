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
$SqlM = "SELECT idcomprobante, serie, nrocomprobante, nroatencion, idcliente, 
            CASE WHEN length(ruc) = 11 THEN 2 ELSE 1 END AS Tipo, CASE WHEN length(ruc) = 11 THEN 8 WHEN length(ruc) = 0 THEN 1 ELSE 1 END AS IdDocumento, CASE WHEN length(ruc) = 0 THEN dni WHEN length(ruc) = 11 THEN ruc ELSE '' END AS DocNum, 
            nombres, direccion, fecha, '00:00:00', idmoneda, 0, idforma, credito, 0, 0, total, fecha, detalle, 
            CASE WHEN anulado = 1 THEN 2 ELSE 1 END AS EstadoA, idusuario, fechareg, substr(cast(fechareg as text),1,4) 
            FROM cabfact 
            WHERE fechareg = '2013-04-17'
            ORDER BY idcomprobante, serie, nrocomprobante ASC";

$ConsultaM = $ConnM->Query($SqlM);
if (!$Consulta)
{
    echo $ConnM->GetError()."<br>"; 
}
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
    while($rowM = $ConnM->FetchArray($ConsultaM)){		
        $IdComprobante	= $rowM[0];
        $Serie			= $rowM[1];
        $Numero			= $rowM[2];
        $Atencion		= $rowM[3];
        $IdCliente		= $rowM[4];
        $Tipo			= $rowM[5];
        $IdDocumento    = $rowM[6];
        $DocNum			= trim($rowM[7]);
        $Nombres		= trim(str_replace("'"," ",trim($rowM[8])));
        $Direccion		= trim(str_replace("'"," ",trim($rowM[9])));
        $Fecha			= $rowM[10];
        $Hora			= $rowM[11];
        $IdMoneda		= $rowM[12];
        $TipoCambio		= $rowM[13];
        $IdForma		= $rowM[14];
        $Credito		= $rowM[15];
        $AfectoIGV		= $rowM[16];
        $PorIGV			= $rowM[17];
        $Total			= $rowM[18];
        $FechaCancel    = $rowM[19];
        $Observacion    = $rowM[20];		
        $Estado			= $rowM[21];
        $IdUsuario		= $rowM[22];
        $FecRegistro            = $rowM[23];
        $Anio			= $rowM[24];				
        $SqlC = "SELECT * FROM cliente WHERE dni_ruc='$DocNum' AND nombres='$Nombres'";
        $ConsultaC = $Conn->Query($SqlC);
        $Numero_Rows = $Conn->NroRegistros($ConsultaC);		
        if ($Numero_Rows==0)
        {
            $Sqlc = "INSERT INTO cliente (idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, email, telefonos, estado, idusuario, fechareg, idprofesion) VALUES('$Tipo', '$IdDocumento', '$DocNum', '$Nombres', '$Direccion', '$Correo', '$Telefonos', '$Estado', '$IdUsuario', '".substr($FecRegistro, 0, 10)."',998)";			
            $Consulta = $Conn->Query($Sqlc);			
            if(!$Consulta) die($Sqlc);
            $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
            $rowS = $Conn->FetchArray($ConsultaS);
            $IdCliente = ($rowS[0])-1;
            //$ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$IdCliente);			
        }
        else
        {
            $rowC = $Conn->FetchArray($ConsultaC);
            $IdCliente = $rowC[0];
        }		
        $Sql = "INSERT INTO facturacion(idcomprobante, comprobante_serie, comprobante_numero, idatencion, idcliente, iddocumento, dni_ruc, nombres, direccion, facturacion_fecha, facturacion_hora, idmoneda, tipo_cambio, idforma_pago, credito, igv_afecto, igv, total, cancelacion_fecha, observaciones, estado, idusuario, fechareg, anio) VALUES('$IdComprobante', '$Serie', '$Numero', '$Atencion', '$IdCliente', '$IdDocumento', '$DocNum', '$Nombres', '$Direccion', '$Fecha', '$Hora', '$IdMoneda', '$TipoCambio', '$IdForma', '$Credito', '$AfectoIGV', '$PorIGV', '$Total', '$FechaCancel', '$Observaciones', '$Estado', '$IdUsuario', '".substr($FecRegistro, 0, 10)."', '$Anio')";
        $Consulta = $Conn->Query($Sql);
        if (!$Consulta)
        {
            echo $Sql.$Conn->GetError()."<br>"; 
            $Cont2 = $Cont2 + 1;
            die($Sqlc);
        }
        else
        {
            $ConsultaF = $Conn->Query("SELECT NEXTVAL('facturacion_idfacturacion_seq')");
            $rowF = $Conn->FetchArray($ConsultaF);
            $IdFacturacion = $rowF[0]-1;			
            $SqlD = "SELECT idservicio, CASE WHEN correlativo = 0 THEN kardex WHEN kardex = '0' THEN cast(correlativo as text) ELSE '' END AS Corr, cantidad, monto FROM itefact WHERE idcomprobante = '$IdComprobante' AND nrocomprobante='$Numero' ";
            $ConsultaD = $ConnM->Query($SqlD);			
            $ContD = 0;
            $TotalD = 0;
            while($rowD = $ConnM->FetchArray($ConsultaD))
            {
                $ContD = $ContD + 1;
                $SqlDD = "INSERT INTO facturacion_detalle (idfacturacion, item, idservicio, correlativo, cantidad, monto, idusuario, fechareg, anio) VALUES('$IdFacturacion', '$ContD', '".$rowD[0]."', '".$rowD[1]."', '".$rowD[2]."', '".$rowD[3]."', '$IdUsuario', '$FecRegistro', '$Anio') ";				
                $TotalD = $TotalD + ($rowD[2] * $rowD[3]);
                $ConsultaDD = $Conn->Query($SqlDD);
                if (!$ConsultaDD) {echo $SqlDD.$Conn->GetError()."<br>";}
            }			
            $SqlD = "UPDATE facturacion SET total='$TotalD' WHERE idfacturacion = '$IdFacturacion' ";
            $ConsultaD = $Conn->Query($SqlD);
            $Cont = $Cont + 1;
        }		
        $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
        echo "<script> $('#tbPorcentaje').attr('width', '$Porcentaje%'); </script>";
        echo "<script> $('#DivPorciento').html('$Porcentaje%'); </script>";
    }
if ($Porcentaje==100){
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
}else{
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/warning.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n con problemas'); </script>";
}
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>