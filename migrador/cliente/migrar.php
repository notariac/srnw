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
$SqlM = "SELECT idcliente AS Id, idtipocliente AS Tipo, CASE WHEN length(ruc) = 0 THEN 1 WHEN length(ruc) > 8 THEN 4 ELSE 1 END AS IdDocumento, CASE WHEN length(ruc) = 0 THEN dni WHEN length(ruc) > 8 THEN ruc ELSE '' END AS DocNum, nombres, direccion, correo, telefonos, estado, 1, now(), 0 as Pas FROM cliente UNION SELECT idparticipante AS Id, CASE WHEN iddocumento = 4 THEN 2 WHEN iddocumento = 1 THEN 1 WHEN iddocumento = 2 THEN 1 WHEN iddocumento = 3 THEN 1 ELSE 0 END AS Tipo, iddocumento, nrodocumento, nombres, direccion, '', '', 1, 1, now(), 1 as Pas FROM participante ORDER BY Pas ASC, Id ASC";	
//die($SqlM);
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont = 0;
$Cont2 = 0;
    while($rowM = $ConnM->FetchArray($ConsultaM)){		
        $Id		= $rowM[0];
        $Tipo		= $rowM[1];
        $IdDocumento	= $rowM[2];
        $DocNum		= trim($rowM[3]);
        $Nombres	= trim(str_replace("'"," ",trim($rowM[4])));
        $Direccion	= trim(str_replace("'"," ",trim($rowM[5])));
        $Correo		= trim($rowM[6]);
        $Telefonos	= trim($rowM[7]);
        $Estado		= $rowM[8];
        $IdUsuario	= $rowM[9];
        $FecRegistro	= $rowM[10];
        $Participante	= $rowM[11];				
        $Sql = "SELECT * FROM cliente WHERE dni_ruc='$DocNum' AND nombres='$Nombres'";
        //die($Sql);
        $Consulta = $Conn->Query($Sql);
        $Numero_Rows = $Conn->NroRegistros($Consulta);		
        if ($Numero_Rows==0){
                $Sql = "INSERT INTO cliente ";
                if ($Participante==0)
                {
                    // $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
                    // $rowS = $Conn->FetchArray($ConsultaS);
                    
                    // if ($Id>=$rowS[0])
                    // {
                    //     $Sql = $Sql."(idcliente, idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, email, telefonos, estado, idusuario, fechareg)";
                    //     $Sql .= " VALUES('$Id', '$Tipo', '$IdDocumento', '".substr($DocNum,0,11)."', '$Nombres', '$Direccion', '$Correo', '$Telefonos', '$Estado', '$IdUsuario', '".substr($FecRegistro,0,10)."');";				                        
                    //     die($Sql);
                    //     $ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$Id);
                        
                    // }
                    // else
                    // {
                    //     $ConsultaMC = $Conn->Query("SELECT MAX(idcliente)+1 FROM cliente");
                    //     $rowMC = $Conn->FetchArray($ConsultaMC);					
                        $Sql = $Sql."(idcliente, idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, email, telefonos, estado, idusuario, fechareg,idprofesion) ";
                        $Sql = $Sql."VALUES(".$Id.", ".$Tipo.", ".$IdDocumento.", '".substr($DocNum,0,11)."', '".$Nombres."', '".$Direccion."', '".$Correo."', '".$Telefonos."', ".$Estado.", ".$IdUsuario.", '".substr($FecRegistro,0,10)."',998);";					
                        //die($Sql);
                        //$ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
                        //$rowS = $Conn->FetchArray($ConsultaS);
                        // if ($rowMC[0]>=$rowS[0])
                        // {
                        //     $ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$rowMC[0]);
                        // }
                    //}
                }
                else
                {
                    $ConsultaMC = $Conn->Query("SELECT MAX(idcliente)+1 FROM cliente");
                    $rowMC = $Conn->FetchArray($ConsultaMC);

                    $Sql = $Sql."(idcliente, idanterior,idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, email, telefonos, estado, idusuario, fechareg,idprofesion) ";
                    $Sql .= "VALUES('".$rowMC[0]."', '|".$Id."|','".$Tipo."', '".$IdDocumento."', '".substr($DocNum,0,11)."', '".$Nombres."', '".$Direccion."', '".$Correo."', '".$Telefonos."', '".$Estado."', '".$IdUsuario."', '".substr($FecRegistro,0,10)."',998)";				
                    $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
                     $rowS = $Conn->FetchArray($ConsultaS);
                     if ($rowMC[0]>=$rowS[0])
                     {
                         $ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$rowMC[0]);
                     }
                }
        }
        //else{}

        $Consulta = $Conn->Query($Sql);
        if (!$Consulta) 
        {
                echo $Sql.$Conn->GetError()."<br>"; 
                $Cont2 = $Cont2 + 1;
        }else{
                $Cont = $Cont + 1;
        }		
        $Porcentaje = round($Cont*100 / $Numero_RowsM, 2);
        echo "<script> $('#tbPorcentaje').attr('width', '$Porcentaje%'); </script>";
        echo "<script> $('#DivPorciento').html('$Porcentaje%'); </script>";
    }
	if ($Porcentaje==100)
    {
            echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/accept.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n exitosa'); </script>";
	}
    else
    {
            echo "<script> $('#DivAvance').html('<img src=\'../../imagenes/iconos/warning.png\' width=\'16\' height=\'16\' /> Migraci&oacute;n con problemas'); </script>";
	}
echo "<script> $('#DivPorciento').attr('bgcolor', '#fff'); </script>";
echo "<script> $('#tbPorcentaje').attr('bgcolor', '#fff'); </script>";
?>