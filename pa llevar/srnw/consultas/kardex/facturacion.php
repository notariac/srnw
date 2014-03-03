<?php
if(!session_id()){ session_start(); }
	include("../../config.php");	
	echo "<meta http-equiv='content-type' content='text/html; charset=iso-8859-1' />";		
	$NroAtencion = $_GET["NroAtencion"];		
	$SQL  = "SELECT comprobante.descripcion, facturacion.comprobante_serie, facturacion.comprobante_numero, facturacion.facturacion_fecha, forma_pago.descripcion, ";
	$SQL .= " moneda.descripcion, facturacion.credito, facturacion.idcomprobante, facturacion.dni_ruc, facturacion.nombres, facturacion.direccion, ";
	$SQL .= " facturacion.igv, facturacion.total, facturacion.estado, facturacion.igv_afecto ";
	$SQL .= " FROM facturacion INNER JOIN comprobante ON (facturacion.idcomprobante = comprobante.idcomprobante) INNER JOIN forma_pago ON (facturacion.idforma_pago = forma_pago.idforma_pago) ";
	$SQL .= " INNER JOIN moneda ON (facturacion.idmoneda = moneda.idmoneda) ";
	$SQL .= "WHERE facturacion.idatencion='$NroAtencion'";	
?>
<style>
.Estilox{
    font-family:Arial;
    font-size:11px;
}
.Borde{
    border:1px solid;
    border-color:#000;
}
.CajaTexto {
    padding-left:5;
    padding-right:5;
    background:#fff;
    border:#000 solid 1px; 
    color:#000;
    font-family:Arial;
    background-color:#D9E5F2;
    font-size:11px;
}
</style>
<?php
$Consulta               = $Conn->Query($SQL);
while($row              = $Conn->FetchArray($Consulta)){		
        $valorIGV	= $row[11];
        $AfectoIGV	= $row[14];
        $Fecha          = $Conn->DecFecha($row[3]);
        $rowmonto 	= $row[12];
?>
<div style="height:10px"></div>
<table width="600" border="0" cellspacing="0" class="Borde" <?php echo $d="";$row[13]==2?$d='bgcolor="#00CCC0"':$d='';echo $d;?>>
  <tr>
    <td colspan="5" class="Estilox" style="color:#804040; font-weight:bold">&nbsp;</td>
  </tr>
  <tr>
    <td class="Estilox" style="color:#804040; font-weight:bold">&nbsp;</td>
    <td colspan="4" class="Estilox" style="color:#804040; font-weight:bold"><u>Detalle de la Facturacion</u></td>
  </tr>
  <tr>
    <td style="color:#F00; font-family:Arial, Helvetica, sans-serif; font-size:12px">&nbsp;</td>
    <td colspan="4" align="center" style="color:#F00; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold"><?php $d=""; $row[13]==2?$d='COMPROBANTE ANULADO':$d='COMPROBANTE CANCELADO'; echo $d;?></td>
  </tr>
  <tr>
    <td width="14" class="Estilox" align="right">&nbsp;</td>
    <td width="89" class="Estilox" align="right">Comprobante:</td>
    <td width="122"><label>
      <input type="text" name="txtcomprobane" id="txtcomprobane" class="CajaTexto" readonly="readonly" value="<?php echo $row[0];?>" />
    </label></td>
    <td width="137" class="Estilox" align="right">Numero:</td>
    <td width="226"><input type="text" name="txtnumero" id="txtnumero" class="CajaTexto" readonly="readonly" value="<?php echo $row[1].'-'.$row[2];?>"/></td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">Fecha:</td>
    <td><input type="text" name="txtfecha" id="txtfecha" class="CajaTexto" value="<?php echo $Fecha;?>" /></td>
    <td class="Estilox" align="right">Forma de Pago:</td>
    <td><input type="text" name="txtforma" id="txtforma" class="CajaTexto" value="<?php echo $row[4];?>"/></td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">Moneda:</td>
    <td><input type="text" name="txtmoneda" id="txtmoneda" class="CajaTexto" value="<?php echo $row[5];?>" /></td>
    <td class="Estilox" align="right">Credito:</td>
    <td><input type="text" name="txtcredito" id="txtcredito" class="CajaTexto"  value="<?php $d=''; $row[6]?$d="CON CREDITO":$d="SIN CREDITO"; echo $d;?>"/></td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">Monto Facturado:</td>
    <td><input type="text" name="txtmonto" id="txtmonto" class="CajaTexto" value="<?php if ($AfectoIGV==1){echo number_format($rowmonto-$rowmonto*$valorIGV/100, 2);}else{echo number_format($rowmonto, 2);}?>"/></td>
    <td class="Estilox" align="right">IGV</td>
    <td><input type="text" name="txtigv" id="txtigv" class="CajaTexto" value="<?php if ($AfectoIGV==1){echo number_format($rowmonto*$valorIGV/100, 2);}else{echo '0.00';}?>"/></td>
  </tr>
  <tr>
    
    <td colspan="2" class="Estilox" align="right">Monto Total Facturado:</td>
    <td colspan="2"><input type="text" name="txtmontototal" id="txtmontototal" class="CajaTexto" value="<?php echo number_format($rowmonto, 2);?>"/></td>
    
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="Estilox" align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  class="Estilox">&nbsp;</td>
    <td colspan="4"  class="Estilox" style="color:#804040; font-weight:bold"><u>Datos del Cliente</u></td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">DNI/RUC:</td>
    <td><input type="text" name="txtdni" id="txtdni" class="CajaTexto" value="<?php echo $row[8];?>" /></td>
    <td class="Estilox" align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">Nombre:</td>
    <td colspan="3"><input name="txtnombre" type="text" class="CajaTexto" id="txtnombre" size="75" maxlength="75"  value="<?php echo $row[9];?>" readonly="readonly"/></td>
  </tr>
  <tr>
    <td class="Estilox" align="right">&nbsp;</td>
    <td class="Estilox" align="right">Direccion:</td>
    <td colspan="3"><input name="txtdireccion" type="text" class="CajaTexto" id="txtdireccion" size="75" maxlength="75" value="<?php echo $row[10];?>" readonly="readonly"/></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
</table>
<div style="height:10px"></div>
<?php
}
?>