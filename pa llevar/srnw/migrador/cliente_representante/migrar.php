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
$SqlM = "SELECT idparticipante, iddocumento, nrodocumento, nombres, cargo, estado, 1, now() FROM representantes ORDER BY idparticipante";
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Id             = $rowM[0];
    $IdDocumento	= $rowM[1];
    $NroDocumento	= $rowM[2];
    $Nombres	= $rowM[3];
    $Cargo		= $rowM[4];
    $Estado		= $rowM[5];
    $IdUsuario	= $rowM[6];
    $FecRegistro	= $rowM[7];				
    $SqlC = "SELECT dni_ruc, idcliente FROM cliente WHERE idanterior LIKE '%|".$Id."|%' ";
    $ConsultaC = $Conn->Query($SqlC);
    $Numero_RowsC = $Conn->NroRegistros($ConsultaC);
    $rowC = $Conn->FetchArray($ConsultaC);
    $RucC = $rowC[0];
    $IdC = $rowC[1];			
            if ($Numero_RowsC!=0){
                $IdR = 0;
                $SqlR = "SELECT idcliente FROM cliente WHERE iddocumento = '$IdDocumento' AND dni_ruc ='$NroDocumento'";
                $ConsultaR = $Conn->Query($SqlR);
                $Numero_RowsR = $Conn->NroRegistros($ConsultaR);
                if ($Numero_RowsR!=0){
                    $rowR = $Conn->FetchArray($ConsultaR);
                    $IdR = $rowR[0];
                }else{
                    $ConsultaMC = $Conn->Query("SELECT MAX(idcliente)+1 FROM cliente");
                    $rowMC = $Conn->FetchArray($ConsultaMC);
                    $IdR = $rowMC[0];				
                    $Sql = "INSERT INTO cliente (idcliente, idcliente_tipo, iddocumento, dni_ruc, nombres,idprofesion) VALUES(".$rowMC[0].", 1, ".$IdDocumento.", '".$NroDocumento."', '".$Nombres."',998);";				
                    $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
                    $rowS = $Conn->FetchArray($ConsultaS);
                    if ($rowMC[0]>=$rowS[0]){
                        $ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$rowMC[0]);
                    }				
                    $Consulta = $Conn->Query($Sql);
                    if (!$Consulta) {
                        echo $Sql.$Conn->GetError()."<br>";
                        die("Error en Consulta SQL: ".$Conn->GetError());
                    }
                }						
                $SqlCR = "SELECT * FROM cliente_representante WHERE ruc_cliente='".$RucC."' AND dni_representante='".$NroDocumento."'";
                $ConsultaCR = $Conn->Query($SqlCR);
                $Numero_RowsCR = $Conn->NroRegistros($ConsultaCR);			
                if ($Numero_RowsCR==0){
                    $Sql = "INSERT INTO cliente_representante ";
                    $Sql = $Sql."(ruc_cliente, dni_representante, cargo, idcliente, idrepresentante) ";
                    $Sql = $Sql."VALUES('".$RucC."', '".$NroDocumento."', '".$Cargo."', ".$IdC.", ".$IdR.");";
                }else{
                    $Sql = "UPDATE cliente_representante SET cargo = '".$Cargo."', idcliente = ".$IdC.", idrepresentante = ".$IdR." WHERE ruc_cliente='".$RucC."' AND dni_representante='".$NroDocumento."'";				
                }
                $Consulta = $Conn->Query($Sql);
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
if ($Porcentaje==100){
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
}else{
    echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/warning.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n con problemas'); </script>";
}
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>