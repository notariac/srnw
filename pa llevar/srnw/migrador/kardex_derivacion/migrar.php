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
$SqlM = "SELECT nrokardex, iddependencia FROM itederivacion 
where nrokardex in (SELECT distinct cabgeneracion.kardex
FROM cabgeneracion LEFT OUTER JOIN iteatencion ON (cabgeneracion.kardex = iteatencion.nrokardex) 
WHERE cabgeneracion.fechareg = '2013-04-17')
GROUP BY nrokardex, iddependencia ORDER BY nrokardex";
//die($SqlM);
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Id		= $rowM[0];
    $IdDependencia	= $rowM[1];		
    $SqlK = "SELECT idkardex, idusuario, fechareg, anio FROM kardex WHERE correlativo = '".$Id."' ";
    $ConsultaK = $Conn->Query($SqlK);
    $Numero_RowsK = $Conn->NroRegistros($ConsultaK);
    if ($Numero_RowsK!=0){
        $rowK = $Conn->FetchArray($ConsultaK);
        $IdKardex = $rowK[0];		
        $SqlC = "SELECT * FROM kardex_derivacion WHERE idkardex = ".$IdKardex." AND iddependencia = ".$IdDependencia;
        $ConsultaC = $Conn->Query($SqlC);
        $Numero_RowsC = $Conn->NroRegistros($ConsultaC);
        $rowC = $Conn->FetchArray($ConsultaC);				
        if ($Numero_RowsC==0){
            $Sql = "INSERT INTO kardex_derivacion ";
            $Sql = $Sql."(idkardex, iddependencia, idusuario, fechareg, anio) ";
            $Sql = $Sql."VALUES(".$IdKardex.", ".$IdDependencia.", ".$rowK[1].", '".$rowK[2]."', '".$rowK[3]."');";					
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