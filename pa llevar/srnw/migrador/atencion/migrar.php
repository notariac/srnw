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
$SqlM         = "SELECT nroatencion, nombres, direccion, fecha, estado, idusuario, fechareg, 
                    substr(cast(fecha as text), 1, 4) 
                    FROM cabatencion 
                    where fechareg = '2013-04-17'
                    ORDER BY nroatencion ASC";	
//die($SqlM);
$ConsultaM    = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);
$Cont         = 0;
$Cont2        = 0;
//$Id           = 341;
while($rowM = $ConnM->FetchArray($ConsultaM)){	
    $Id          = $rowM[0];
    $Cliente	 = 0;//trim(str_replace("'"," ",trim($rowM[1])));
    $Direccion	 = trim(str_replace("'"," ",trim($rowM[2])));
    $Fecha	 = $rowM[3];
    $Estado	 = $rowM[4];
    $IdUsuario	 = $rowM[5];
    $FecRegistro = $rowM[6];
    $Anio	 = $rowM[7];	
    $Hora        = date("H:i:s");
    $IdNotaria   = 1;
    $Sql = "SELECT * FROM atencion WHERE idatencion='$Id'";

    $Consulta = $Conn->Query($Sql);
    $Numero_Rows = $Conn->NroRegistros($Consulta);
    if ($Numero_Rows==0)
    {
        $c = $Cont + 1;
        $Sql = "INSERT INTO atencion (idatencion, direccion, fecha, estado, idusuario, fechareg, idnotaria, anio, hora, idcliente, correlativo) VALUES('$Id', '$Direccion', '$Fecha', '$Estado', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio', '$Hora', '$Cliente', '$c')";
        $Consulta = $Conn->Query($Sql);
        $ConsultaS = $Conn->Query("SELECT NEXTVAL('atencion_idatencion_seq')");
        $rowS = $Conn->FetchArray($ConsultaS);
        if ($Id>=$rowS[0])
        {
            $ConsultaSeg = $Conn->Query('ALTER SEQUENCE atencion_idatencion_seq restart '.$Id);
        }
        $SqlD = "SELECT idservicio, cantidad, folios, monto, CASE WHEN correlativo != 0 THEN correlativo END as Coor, CASE WHEN nrokardex <> '0' THEN nrokardex END as Kard, estado FROM iteatencion WHERE nroatencion = '$Id' and  cantidad is not null and nroatencion not in (19077,22918,22919)";
        $ConsultaD = $ConnM->Query($SqlD);
        if (!$ConsultaD) {echo $SqlD.$ConnM->GetError()."<br/>";}
        $ContD = 0;
        while($rowD = $ConnM->FetchArray($ConsultaD))
        {
            $ContD = $ContD + 1;
            $SqlDD = "INSERT INTO atencion_detalle (idatencion, idservicio, item, cantidad, folios, monto, correlativo, estado, idusuario, fechareg, idnotaria, anio) VALUES('$Id', '".$rowD[0]."', '$ContD', '".$rowD[1]."', '".$rowD[2]."', '".$rowD[3]."', '".$rowD[4].$rowD[5]."', '".$rowD[6]."', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio')";
            $ConsultaDD = $Conn->Query($SqlDD);
            if (!$ConsultaDD) {echo $SqlDD.$Conn->GetError()."<br>";}
        }
    }		
    if (!$Consulta)
    {
        echo $Sql.$Conn->GetError()."<br>"; 
        $Cont2 = $Cont2 + 1;
    }
    else
    {
        $Cont = $Cont + 1;
    }
    $Porcentaje = round($Cont * 100 / $Numero_RowsM, 2);
    echo "<script> $('#tbPorcentaje').attr('width', '$Porcentaje%'); </script>";
    echo "<script> $('#DivPorciento').html('$Porcentaje%'); </script>";
    //$Id++;
}
if ($Porcentaje==100){
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
}else{
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/warning.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n con problemas'); </script>";
}
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>