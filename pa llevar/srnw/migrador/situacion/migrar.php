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
$SqlM         = "SELECT idsituacion, descripcion, estado, 1, now() FROM situacion";
$ConsultaM    = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);
$Cont         = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Cont = $Cont + 1;		
    $Id		= $rowM[0];
    $Descripcion	= $rowM[1];
    $Estado		= $rowM[2];
    $IdUsuario	= $rowM[3];
    $FecRegistro	= $rowM[4];				
    $Sql = "SELECT * FROM situacion WHERE idsituacion='$Id'";
    $Consulta = $Conn->Query($Sql);
    $Numero_Rows = $Conn->NroRegistros($Consulta);		
    if ($Numero_Rows==0){
        $Sql="INSERT INTO situacion ";
        $Sql=$Sql."(idsituacion, descripcion, estado, idusuario, fechareg) ";
        $Sql=$Sql."VALUES(".$Id.", '".$Descripcion."', ".$Estado.", ".$IdUsuario.", '".substr($FecRegistro,0,10)."');";
        $Consulta = $Conn->Query($Sql);
        if (!$Consulta) {
            echo $Sql;
            die("Error en Consulta SQL: ".pg_last_error());
        }
    }
    $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
    echo "<script> $('#tbPorcentaje').attr('width', '".$Porcentaje."%'); </script>";
    echo "<script> $('#DivPorciento').html('".$Porcentaje." %'); </script>";
}
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
    echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
    echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>