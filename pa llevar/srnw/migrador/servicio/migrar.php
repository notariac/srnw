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
$SqlM = "SELECT idservicio, descripcion, precio, legal, ";
$SqlM = $SqlM." (SELECT COUNT(*) FROM kardex_servicio WHERE idservicio=S.idservicio) as Especial, ";
$SqlM = $SqlM." (SELECT MAX(idkardex) FROM kardex_servicio WHERE idservicio=S.idservicio) as IdKardex, ";
$SqlM = $SqlM." indsimple, correlasimple, ";
$SqlM = $SqlM." CASE WHEN (SELECT MAX(idkardex) FROM kardex_servicio WHERE idservicio=S.idservicio)=1 THEN 0 ";
$SqlM = $SqlM." WHEN (SELECT MAX(idkardex) FROM kardex_servicio WHERE idservicio=S.idservicio)=2 THEN 0 ";
$SqlM = $SqlM." WHEN (SELECT MAX(idkardex) FROM kardex_servicio WHERE idservicio=S.idservicio)=6 THEN 0 ELSE 1 END as Reinicio, ";
$SqlM = $SqlM." usofolios, estado, 1, now() FROM servicios S ORDER BY idservicio ASC";
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);
$Cont = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Cont           = $Cont + 1;		
    $Id		= $rowM[0];
    $Descripcion	= $rowM[1];
    $Precio		= $rowM[2];
    if ($Precio=='') {$Precio=0;}
    $Legal		= $rowM[3];
    $Especial	= $rowM[4];
    if ($Especial>1) {$Especial=1;}
    $IdKardexTipo	= $rowM[5];
    if ($IdKardexTipo=='') {$IdKardexTipo=0;}
    $Simple		= $rowM[6];
    $SimpleCorr	= $rowM[7];
    if ($SimpleCorr=='') {$SimpleCorr=0;}
    $Reinicio	= $rowM[8];
    $Folios		= $rowM[9];
    $Estado		= $rowM[10];
    $IdUsuario	= $rowM[11];
    $FecRegistro	= $rowM[12];				
    $Sql = "SELECT * FROM servicio WHERE idservicio=".$Id;
    $Consulta = $Conn->Query($Sql);
    $Numero_Rows = $Conn->NroRegistros($Consulta);		
    if ($Numero_Rows==0){
        $Sql="INSERT INTO servicio ";
        $Sql=$Sql."(idservicio, descripcion, precio, legal, especial, idkardex_tipo, correlativo, reinicio, folios, estado, idusuario, fechareg) ";
        $Sql=$Sql."VALUES(".$Id.", '".$Descripcion."', '".$Precio."', ".$Legal.", ".$Especial.", ".$IdKardexTipo.", ".$Simple.", ".$Reinicio.", ".$Folios.", ".$Estado.", ".$IdUsuario.", '".substr($FecRegistro,0,10)."');";
        $Consulta = $Conn->Query($Sql);
        if (!$Consulta) {
            echo $Sql."<br>";
            die("Error en Consulta SQL: ".pg_last_error());
        }
    }
    $SqlSN = "SELECT COUNT(idservicio) FROM servicio_notaria WHERE idnotaria=1 AND anio='2012' AND idservicio=".$Id;
    $ConsultaSN = $Conn->Query($SqlSN);
    $rowSN = $Conn->FetchArray($ConsultaSN);		
    if ($rowSN[0]==0){
        $SqlSN2 = "INSERT INTO servicio_notaria(idservicio, idnotaria, correlativo, anio) VALUES (".$Id.", 1, ".$SimpleCorr.", '2012')";
    }else{
        $SqlSN2 = "UPDATE servicio_notaria SET correlativo=".$SimpleCorr." WHERE idnotaria=1 AND anio='2012' AND idservicio=".$Id;
    }
    $ConsultaSN2 = $Conn->Query($SqlSN2);
    if (!$ConsultaSN2) {
        echo $SqlSN2."<br>";
        die("Error en Consulta SQL: ".pg_last_error());
    }		
    $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
    echo "<script> $('#tbPorcentaje').attr('width', '".$Porcentaje."%'); </script>";
    echo "<script> $('#DivPorciento').html('".$Porcentaje." %'); </script>";
}
echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>