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
$SqlM = "SELECT     distinct a.idgeneracion,    
    a.nroatencion, 
    a.fecha, 
    a.nrolibro, 
    case    when a.catclientes=2 then a.razonsocial 
        when a.catclientes=1 then trim(trim(a.nombreuno) || ' ' || trim(a.nombredos)) || ' ' || trim(a.appaterno) || ' ' || trim(a.apmaterno) end, 
    a.ruc, 
    a.direccion, 
    a.telefono, 
    a.idtipolibro, 
    a.cantlibros, 
    a.folioinicio, 
    a.foliofinal, 
    a.idnumeracion, 
    a.solicitante, 
    a.dni, 
    a.idusuario, 
    a.fechareg, 
    a.anio,
    c.idservicio
FROM generalibros as a 
    inner join cabatencion as b on a.nroatencion = b.nroatencion
    inner join iteatencion as c on c.nroatencion = b.nroatencion
WHERE a.fechareg='2013-04-17'
ORDER BY a.nroatencion ";
die($SqlM);
$ConsultaM = $ConnM->Query($SqlM);
$Numero_RowsM = $ConnM->NroRegistros($ConsultaM);	
$Cont   = 0;
$Cont2  = 0;
$Idx    = 0;
while($rowM = $ConnM->FetchArray($ConsultaM)){
    $Id			= $rowM[0];
    $IdAtencion		= $rowM[1];
    $IdServicio = $rowM['idservicio'];
    $Fecha		= $rowM[2];
    $NroLibro		= $rowM[3];
    $RazonSocial	= trim(str_replace("'"," ",trim($rowM[4])));
    $Ruc		= trim(str_replace("'"," ",trim($rowM[5])));
    if (strlen($Ruc)>11){ $Ruc = substr($Ruc,0,11);}
    $Direccion		= trim(str_replace("'"," ",trim($rowM[6])));
    $Telefono		= trim(str_replace("'"," ",trim($rowM[7])));
    $TipoLibro		= $rowM[8];
    $Cantidad		= $rowM[9];
    $FolioIni		= $rowM[10];
    $FolioFin		= $rowM[11];
    $IdNumeracion	= $rowM[12];
    $Solicitante	= trim(str_replace("'"," ",trim($rowM[13])));
    $Dni		= trim($rowM[14]);
    $IdCliente		= 0;
    $IdNotaria		= 1;
    $Direccion		= "";
    $Hora        = date("H:i:s");
    $Estado		= 0;
    if ($RazonSocial!=''){$Estado=1;}
        $IdUsuario	= $rowM[15];
        $FecRegistro	= $rowM[16];
        $Anio		= $rowM[17];
        $Sql = "SELECT * FROM libro WHERE idlibro='$Id' AND correlativo='$NroLibro' AND anio='$Anio' ";
        $Consulta = $Conn->Query($Sql);
        $Numero_Rows = $Conn->NroRegistros($Consulta);		
        if ($Numero_Rows==0){		
            $SqlA = "SELECT * FROM atencion WHERE idatencion='$IdAtencion' ";
            $ConsultaA = $Conn->Query($SqlA);
            $Numero_RowsA = $Conn->NroRegistros($ConsultaA);
            if ($Numero_RowsA==0)
            {
                if(trim($IdServicio)!="")
                {
                    $SqlA = "INSERT INTO atencion (idatencion, direccion, fecha, estado, idusuario, fechareg, idnotaria, anio, hora, idcliente, correlativo) VALUES('$IdAtencion', '$Direccion', '$Fecha', '$Estado', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio', '$Hora', '$IdCliente', '0')";                
                    $ConsultaA = $Conn->Query($SqlA);
                    if (!$ConsultaA) {echo $SqlA.$Conn->GetError()."<br>";die;}             
                    $SqlDA = "INSERT INTO atencion_detalle (idatencion, idservicio, item, cantidad, folios, monto, correlativo, estado, idusuario, fechareg, idnotaria, anio) VALUES('$IdAtencion', '$IdServicio', '1', '1', '1', '0', '$NroCarta', '1', '$IdUsuario', '$FecRegistro', '$IdNotaria', '$Anio')";
                    $ConsultaDA = $Conn->Query($SqlDA);
                    if (!$ConsultaDA) {echo $SqlDA.$Conn->GetError()."<br>";die;}
                }                
            }
            $SqlC = "INSERT INTO libro (idlibro, idatencion, fecha, correlativo, razonsocial, ruc, direccion, telefono, idlibro_tipo, numero, folio_inicial, folio_final, idlibro_numeracion_tipo, solicitante, solicitante_dni, estado, idusuario, fechareg, anio, idnotaria) 
            VALUES('$Idx', '$IdAtencion', '$Fecha', '$NroLibro', '$RazonSocial', '$Ruc', '$Direccion', '$Telefono', '$TipoLibro', '$Cantidad', '$FolioIni', '$FolioFin', '$IdNumeracion', '$Solicitante', '$Dni', '$Estado', '$IdUsuario', '$FecRegistro', '$Anio', '$IdNotaria')";
            $ConsultaC = $Conn->Query($SqlC);
            if (!$ConsultaC) {echo $Sql."<br>".$SqlC."<br>".$Conn->GetError()."<br>".$rowM[10]."<br>";die;}			
            $ConsultaS = $Conn->Query("SELECT NEXTVAL('libro_idlibro_seq')");
            $rowS = $Conn->FetchArray($ConsultaS);
            if ($Id>=$rowS[0]){
                $ConsultaSeg = $Conn->Query('ALTER SEQUENCE libro_idlibro_seq restart '.$Id);
            }
        }
        if (!$ConsultaC){
            $Cont2 = $Cont2 + 1;
        }else{
            $Cont = $Cont + 1;
        }
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