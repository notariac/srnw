<?php
    include('../../config.php');	
    $IdKardex = $_GET['IdKardex'];
    $IdDependencia = $_GET['IdDependencia'];	
?>
<form id="formD<?php echo $IdDependencia;?>" name="formD<?php echo $IdDependencia;?>" method="post" action="" enctype="multipart/form-data">
<table width="400" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" class="ListaS" id="ListaS<?php echo $IdDependencia;?>">
<tr>
        <th title="Cabecera" height="20" class="ListaSth">Situaci&oacute;n</th>
        <th title="Cabecera" class="ListaSth">Nº T&iacute;tulo </th>
        <th title="Cabecera" width="70" class="ListaSth">Fecha</th>
        <th title="Cabecera" width="35" class="ListaSth">&nbsp;</th>
</tr>
<tbody>
<?php
        $NumRegS = 0;
        $Sql2 = "SELECT kardex_derivacion_situacion.idkardex, kardex_derivacion_situacion.iddependencia, kardex_derivacion_situacion.idsituacion, situacion.descripcion, kardex_derivacion_situacion.titulo_numero, kardex_derivacion_situacion.fecha, kardex_derivacion_situacion.asiento_numero, kardex_derivacion_situacion.partida_numero, kardex_derivacion_situacion.presentacion_fecha, kardex_derivacion_situacion.vencimiento_fecha, kardex_derivacion_situacion.subsanacion_fecha, kardex_derivacion_situacion.observacion, kardex_derivacion_situacion.monto FROM situacion INNER JOIN kardex_derivacion_situacion ON (situacion.idsituacion = kardex_derivacion_situacion.idsituacion) WHERE kardex_derivacion_situacion.idkardex = '$IdKardex' AND kardex_derivacion_situacion.iddependencia='$IdDependencia' ORDER BY kardex_derivacion_situacion.fecha";
        $Consulta2 = $Conn->Query($Sql2);
        while($row2=$Conn->FetchArray($Consulta2)){
            $NumRegS = $NumRegS + 1;
            $FechaD = $Conn->DecFecha($row2[5]);				
?>
<tr>
        <td align="left" style="padding-left:5;">
                <input type='hidden' name='0formD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>_idkardex' id="IdKardex<?php echo $NumRegS;?>D<?php echo $IdDependencia;?>" value='<?php echo $IdKardex;?>' />
                <input type="hidden" name="0formD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>_iddependencia" id="IdDependencia<?php echo $NumRegS;?>D<?php echo $IdDependencia;?>" value="<?php echo $IdDependencia;?>" />
                <input type="hidden" name="0formD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>_idsituacion" id="IdSituacion<?php echo $NumRegS;?>D<?php echo $IdDependencia;?>" value="<?php echo $row2[2];?>" />
                <input type="hidden" name="SituacionD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>" id="SituacionD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>" value="<?php echo $row2[3];?>" /><?php echo $row2[3];?>				
        </td>
        <td align="center" style="padding-left:5;">
                        <input type="hidden" name="0formD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>_titulo_numero" id="TituloNumeroD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>" value="<?php echo $row2[4];?>" /><?php echo $row2[4];?>
        </td>
        <td align="center">
                <input type="hidden" name="3formD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>_fecha" id="FechaD<?php echo $IdDependencia;?>S<?php echo $NumRegS;?>" value="<?php echo $FechaD;?>" /><?php echo $FechaD;?>
        </td>
        <td align="center">
                <img src="../../imagenes/iconos/ver.png" width="16" height="16" onclick="DatosDS(<?php echo $row2[2];?>, <?php echo $IdDependencia;?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Modificar Datos" />
                <img src="../../imagenes/iconos/quitar.png" width="16" height="16" onclick="QuitaDS(<?php echo $NumRegS-1;?>, <?php echo $IdDependencia;?>, <?php echo $row2[2]?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar la Situaci&oacute;n" />
        </td>
</tr>
<?php
        }
        echo "<script> var nDest$IdDependencia = $NumRegS; var nDestC$IdDependencia = $NumRegS; </script>";
?>
</tbody>
</table>
<input type="hidden" name="ConSituacion<?php echo $IdDependencia;?>" id="ConSituacion<?php echo $IdDependencia;?>" value="<?php echo $NumRegS;?>"/>
</form>