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
$SqlM = "SELECT nrogeneracion, idparticipante, idtipoparticipacion, foto FROM itegeneracion 
where nrogeneracion in (
SELECT distinct cabgeneracion.nrogeneracion
FROM cabgeneracion LEFT OUTER JOIN iteatencion ON (cabgeneracion.kardex = iteatencion.nrokardex) 
WHERE cabgeneracion.fechareg = '2013-04-17'
)
ORDER BY nrogeneracion";
//die($SqlM);
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Id		= $rowM[0];
    $IdParticipante	= $rowM[1];
    $Tipo		= $rowM[2];
    $Foto		= $rowM[3];				
    $SqlC           = "SELECT idcliente FROM cliente WHERE idanterior LIKE '%|$IdParticipante|%' ";
    $ConsultaC = $Conn->Query($SqlC);
    $Numero_RowsC = $Conn->NroRegistros($ConsultaC);
    $rowC = $Conn->FetchArray($ConsultaC);
    $IdC = $rowC[0];			
    if ($Numero_RowsC!=0){
        $SqlCR = "SELECT * FROM kardex_participantes WHERE idkardex='$Id' AND idparticipante='$IdC'";
        $ConsultaCR = $Conn->Query($SqlCR);
        $Numero_RowsCR = $Conn->NroRegistros($ConsultaCR);			
        if ($Numero_RowsCR==0){
            $Sql = "INSERT INTO kardex_participantes (idkardex, idparticipante, idparticipacion, foto) VALUES('$Id', '$IdC', '$Tipo', '$Foto');";				
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