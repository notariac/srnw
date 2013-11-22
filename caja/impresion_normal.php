<?php
if(!session_id()){ session_start(); }
	include('../config.php');
	include('../config_seguridad.php');
	include('../libs/num2letra.php');	
	$Id = isset($_GET["Id"])?$_GET["Id"]:'';	
	$Select 	= "SELECT facturacion.idcomprobante, comprobante.abreviatura, facturacion.comprobante_serie, facturacion.comprobante_numero, ";
	$Select		= $Select." facturacion.idatencion, facturacion.nombres, facturacion.direccion, documento.descripcion, facturacion.dni_ruc, ";
	$Select		= $Select." facturacion.credito, forma_pago.descripcion, facturacion.facturacion_fecha, facturacion.facturacion_hora, facturacion.observaciones, ";
	$Select		= $Select." facturacion.igv_afecto, facturacion.igv, facturacion.total, facturacion.cancelacion_fecha, facturacion.idusuario ";
	$Select		= $Select." FROM facturacion INNER JOIN comprobante ON (facturacion.idcomprobante = comprobante.idcomprobante) ";
	$Select		= $Select." INNER JOIN documento ON (facturacion.iddocumento = documento.iddocumento) INNER JOIN forma_pago ON (facturacion.idforma_pago = forma_pago.idforma_pago) ";
	$Select		= $Select." WHERE facturacion.idfacturacion = '$Id'";	
	$Consulta 	= $Conn->Query($Select);
	$row 		= $Conn->FetchArray($Consulta);	
	$Igv            = $row[15];
	$SubTotal       = $row[16];
	$nIgv           = 0;
	if ($row[14] == 1)
  {
            $SubTotal = $row[16] - ($row[16] * ($Igv/100));
            $nIgv       = $row[16] - $SubTotal;
	}	
	$Sql = "SELECT login FROM usuario WHERE idusuario='".$row[18]."'";
	$ConsultaS = $ConnS->Query($Sql);
	$rowS = $ConnS->FetchArray($ConsultaS);
	$Usuario        = $rowS[0];
?>
<style>
.body{font-family:Verdana, Arial, Helvetica, sans-serif;}
.TituloTb{
    border-top-width:1px; 
    border-bottom-width:1px;
    border-top-color:#000000;
    border-bottom-color:#000000;
    border-top-style:solid;
    border-bottom-style:solid;
}
</style>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100">&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px">
      <tr>
        <td width="100">CLIENTE : </td>
        <td><?php echo utf8_decode($row[5]);?></td>
        <td width="150">INTERNO N&deg; : </td>
        <td width="130"><?php echo $row[1]."/".$row[2]."-".$row[3]." (".$row[4].")";?></td>
      </tr>
      <tr>
        <td>DIRECCI&Oacute;N : </td>
        <td><?php echo $row[6];?></td>
        <td>TIPO DE PAGO : </td>
        <td><?php echo $row[10];?></td>
      </tr>
      <tr>
        <td><?php echo $row[7];?> : </td>
        <td><?php echo $row[8];?></td>
        <td>FECHA DE EMISI&Oacute;N :</td>
        <td><?php echo $Conn->DecFecha($row[11]);?> :<?php echo substr($row[12],0,8);?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="200" valign="top" style="padding-top:5px">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" align="center" class="TituloTb">Cant.</td>
        <td align="center" class="TituloTb">Servicio</td>
        <td width="100" align="center" class="TituloTb">Kardex</td>
        <td width="100" align="center" class="TituloTb">P. Unit.</td>
        <td width="100" align="center" class="TituloTb">Importe</td>
      </tr>
<?php
    $SQL2 = "SELECT facturacion_detalle.anio, facturacion_detalle.idfacturacion, facturacion_detalle.item, facturacion_detalle.idservicio, servicio.descripcion, facturacion_detalle.correlativo, facturacion_detalle.cantidad, facturacion_detalle.monto, (facturacion_detalle.cantidad * facturacion_detalle.monto)FROM servicio INNER JOIN facturacion_detalle ON (servicio.idservicio = facturacion_detalle.idservicio) WHERE facturacion_detalle.idfacturacion = '$Id'";
    $Consulta2 = $Conn->Query($SQL2);		
    while($row2 = $Conn->FetchArray($Consulta2)){
?>
      <tr>
        <td align="center"><?php echo $row2[6];?></td>
        <td style="padding-left:5px"><?php echo $row2[4];?></td>
        <td align="center"><?php echo $row2[5];?></td>
        <td align="right" style="padding-right:5px"><?php echo $row2[7];?></td>
        <td align="right" style="padding-right:5px"><?php echo $row2[8];?></td>
      </tr>
<?php
    }
?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" style="padding-left:5px"><?php echo $row[13];?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Son : <?php echo CantidadEnLetra($row[16]);?></td>
        <td width="120" align="right">Sub Total S/. : </td>
        <td width="100" align="right" style="padding-right:5px"><?php if ($row[0]==1){ echo $row[16];}else{echo $SubTotal;}?></td>
      </tr>
      <tr <?php if ($row[0]==1){ echo "style='display:none'";}?>>
        <td>&nbsp;</td>
        <td align="right">I.G.V. : </td>
        <td align="right" style="padding-right:5px"><?php echo number_format($nIgv, 2);?></td>
      </tr>
      <tr>
        <td>Responsable : <?php echo $Usuario?></td>
        <td align="right">Importe Total S/. : </td>
        <td align="right" style="padding-right:5px"><?php echo $row[16];?></td>
      </tr>
    </table></td>
  </tr>
</table>
<script>
    window.print();
</script>