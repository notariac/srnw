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
$SqlM = "SELECT kardex, iddependencia, idsituacion, nrotitulo, fecha, nroasiento, nropartida, fechapresentacion, fechavencimiento, ";
$SqlM = $SqlM." fechasubsanacion, monto, observacion FROM itesituacion ORDER BY kardex";
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Id		= $rowM[0];
    $IdDependencia	= $rowM[1];
    $IdSituacion	= $rowM[2];
    $Titulo		= $rowM[3];
    $Fecha		= $rowM[4];
    if ($Fecha==''){ $Fecha = '1900-01-01'; }
    $Asiento	= $rowM[5];
    $Partida	= $rowM[6];
    $FechaP		= $rowM[7];
    if ($FechaP==''){ $FechaP = '1900-01-01';}
    $FechaV		= $rowM[8];
    if ($FechaV==''){ $FechaV = '1900-01-01';}
    $FechaS		= $rowM[9];
    if ($FechaS==''){ $FechaS = '1900-01-01';}
    $Monto		= $rowM[10];
    if ($Monto==''){ $Monto = 0;}
    $Observacion	= trim(str_replace("'"," ",trim($rowM[11])));		
    $SqlK = "SELECT idkardex, idusuario, fechareg, anio FROM kardex WHERE correlativo = '$Id' ";
    $ConsultaK = $Conn->Query($SqlK);
    $Numero_RowsK = $Conn->NroRegistros($ConsultaK);
    if ($Numero_RowsK!=0){
        $rowK = $Conn->FetchArray($ConsultaK);
        $IdKardex = $rowK[0];		
        $SqlC = "SELECT * FROM kardex_derivacion_situacion WHERE idkardex = ".$IdKardex." AND iddependencia = ".$IdDependencia." AND idsituacion=".$IdSituacion." AND fecha = '".$Fecha."'";
        $ConsultaC = $Conn->Query($SqlC);
        $Numero_RowsC = $Conn->NroRegistros($ConsultaC);
        $rowC = $Conn->FetchArray($ConsultaC);				
        if ($Numero_RowsC==0){
            $Sql = "INSERT INTO kardex_derivacion_situacion ";
            $Sql = $Sql."(idkardex, iddependencia, idsituacion, titulo_numero, fecha, asiento_numero, partida_numero, presentacion_fecha, ";
            $Sql = $Sql."vencimiento_fecha, subsanacion_fecha, monto, observacion) ";
            $Sql = $Sql."VALUES(".$IdKardex.", ".$IdDependencia.", ".$IdSituacion.", '".$Titulo."', '".$Fecha."', '".$Asiento."', '".$Partida."', ";
            $Sql = $Sql."'".$FechaP."', '".$FechaV."', '".$FechaS."', '".$Monto."', '".$Observacion."');";					
            $Consulta = $Conn->Query($Sql);
        }
    }
    if (!$Consulta){
        echo $Sql.$Conn->GetError()."<br>"; 
        $Cont2 = $Cont2 + 1;
    }else{
        $Cont = $Cont + 1;
    }
    $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
    echo "<script> $('#tbPorcentaje').attr('width', '".$Porcentaje."%'); </script>";
    echo "<script> $('#DivPorciento').html('".$Porcentaje." %'); </script>";
}
echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>