<?php 
if(!session_id()){ session_start(); }	
include('../config.php');
include('../config_seguridad.php');

$sql = "SELECT  f.idfacturacion,
				f.nombres,
				f.dni_ruc,
				f.comprobante_serie||'-'||f.comprobante_numero as nrofac,
				f.total as total,
				f.facturacion_fecha,
				case f.idmoneda when 1 then '€' 
                     when 2 then '$'
                     when 3 then 'S/.'
                else '-' end as moneda,
                f.total-(coalesce(t1.total_p,0)) as totalp
		from facturacion as f inner join facturacion_detalle as fd on fd.idfacturacion = f.idfacturacion
				left outer join 
                        (select sum(monto) as total_p,
                            idfacturacion
                          from facturacion_pagos
                         group by idfacturacion ) as t1 on t1.idfacturacion = fd.idfacturacion 
		where fd.idfacturacion = ".$_GET['idfacturacion'];

$Consulta = $Conn->Query($sql);
$r = $Conn->FetchArray($Consulta);
?>

<fieldset class="ui-widget-content ui-corner-all">
	<legend style="font-size:9px">Datos de Factura</legend>
	<label class="labels" style="width:80px;">Cliente: </label>
	<label><b><?php if($r['dni_ruc']!="") echo $r['dni_ruc']."-"; else echo "";  echo $r['nombres']; ?></b></label>
	<br/>
	<label class="labels" style="width:80px;">Nro Factura: </label>
	<label><b><?php echo $r['nrofac']; ?></b></label>
	<label class="labels" style="width:120px;">Fecha Emision: </label>
	<label><b><?php echo $Conn->DecFecha($r['facturacion_fecha']); ?></b></label>
	<label class="labels" style="width:80px;">Importe T.: </label>
	<label><b>S/. <?php echo number_format($r['total'],2); ?></b></label>
	<div style="padding:3px 0 0 3px; margin-top:5px; border-top:1px dotted #CCCCCC;">
		<table width="100%">
			<tr style="border-bottom:1px solid #CCC;">
				<td style="border-bottom:1px solid #CCC;">Item</td>
				<td style="border-bottom:1px solid #CCC;">Servicio</td>
				<td style="border-bottom:1px solid #CCC;">Correl.</td>
				<td style="border-bottom:1px solid #CCC;">Cant.</td>
				<td style="border-bottom:1px solid #CCC;">S/. Monto</td>
				<td style="border-bottom:1px solid #CCC;">S/. Total</td>
				<td style="border-bottom:1px solid #CCC;">&nbsp;</td>
			</tr>
		<?php 
			$s = "SELECT fd.item,
						 s.descripcion,
						 fd.correlativo,
						 fd.cantidad,
						 fd.monto
				  FROM facturacion_detalle as fd inner join servicio as s on s.idservicio = fd.idservicio 
				  WHERE fd.idfacturacion = ".$_GET['idfacturacion'];
			$q = $Conn->Query($s);
			while($rd = $Conn->FetchArray($q))
			{
				?>
				<tr>
					<td align="center"><?php echo $rd[0]; ?></td>
					<td align="left"><?php echo $rd[1]; ?></td>
					<td align="center"><?php echo $rd[2]; ?></td>
					<td align="center"><?php echo $rd[3]; ?></td>
					<td align="center"><?php echo number_format($rd[4],2); ?></td>
					<td align="center"><?php echo number_format($rd[3]*$rd[4],2) ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php
			}
		?>
		</table>
	</div>
</fieldset>
<fieldset class="ui-widget-content ui-corner-all">
<legend style="font-size:9px">Registro de Pago</legend>
<form name="frm-pay" id="frm-pay" action="POST">
	<input type="hidden" name="idfacturacion" id="idfacturacion" value="<?php echo $_GET['idfacturacion']; ?>" />	
	<label class="labels" style="width:100px;">Forma de Pago: </label>
	<select name="idforma_pago" id="idforma_pago" class="text">
		<option value="">-Seleccione-</option>
		<?php 
			$sql = "SELECT idforma_pago, descripcion from forma_pago where idforma_pago in (1,2,5) order by idforma_pago";
			$Consulta = $Conn->Query($sql);
			while($row = $Conn->FetchArray($Consulta))
			{
				?>
				<option value="<?php echo $row[0] ?>"><?php echo $row[1]; ?></option>
				<?php
			}
		?>
	</select>
	<label class="labels" style="width:50px;">Fecha: </label>
	<input type="text" name="fecha_pago" id="fecha_pago" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all text" style="width:75px;text-align:center" />	
	<label class="labels" style="width:50px;">Monto: </label>
	S/. <input type="text" name="monto" id="monto" value="<?php echo number_format($r['totalp'],2) ?>" class="ui-widget-content ui-corner-all text" style="width:80px;text-align:right" />
	<br/>
	<div id="box-datos1" style="display:none">
		<label class="labels" id="label-doc" style="width:100px;">Nro Cheque: </label>
		<input type="text" name="nrodocumento" id="nrodocumento" value="" class="ui-widget-content ui-corner-all text" style="width:160px;" />
		<label class="labels" style="width:70px;">Ent.Finan: </label>
		<select name="identidad_financiera" id="identidad_financiera" style="width:200px;" class="text">
			<option value="">-Seleccione-</option>
		<?php 
			$sql = "SELECT identidad_financiera, descripcion from pdt.entidadfinanciera order by descripcion";
			$Consulta = $Conn->Query($sql);
			while($row = $Conn->FetchArray($Consulta))
			{
				?>
				<option value="<?php echo $row[0] ?>"><?php echo $row[1] ?></option>
				<?php
			}
		?>
		</select>
	</div>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top"><label class="labels" style="width:100px;">Observacion: </label></td>
			<td><textarea name="observacion" id="observacion" rows="2" cols="62" class="ui-widget-content ui-corner-all text" placeholder="Observacion..."></textarea></td>
		</tr>
	</table>	
	<div style="text-align:center; border-top:1px dotted #dadada; margin-top:5px; padding-top:2px;">
		<a href="#" class="myButton" id="add-pay">Registrar Págo</a>
	</div>
	</form>
</fieldset>

<fieldset class="ui-widget-content ui-corner-all">
	<legend style="font-size:9px">Pagos Efectuados</legend>
	<div class="contain">
   		<table id="tabla-pay" class="ui-widget-content">
    		<thead class="ui-widget-header">
        		<tr class="ui-widget-header">
          			<th style="width:30px">Item</th>
          			<th>Fecha</th>
          			<th>Forma Pago</th>
          			<th>Monto</th>
          			<td>Obsv.</td>
          			<th style="width:50px">Anular</th>
        		</tr>
    		</thead>
    		<tbody>

    		</tbody>
   		</table>
	</div>
</fieldset>
