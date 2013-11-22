<?php
if(!session_id()){ session_start(); }
	include('../../config.php');	
	$Op = $_POST["Op"];
	$Id = isset($_POST["Id"])?$_POST["Id"]:'';
	$IdSituacion = isset($_POST["IdSituacion"])?$_POST["IdSituacion"]:'';
	$IdDependencia = isset($_POST["IdDependencia"])?$_POST["IdDependencia"]:'';	
	$PresentacionFecha = date('d/m/Y');
	$VencimientoFecha = date('d/m/Y');
	$SubsanacionFecha = date('d/m/Y');	
	$Enabled	= "";	
	if($Op==2 || $Op==4){
		$Enabled = "readonly";
	}
        if($Id!=''){
		$Select 	   = "SELECT * FROM kardex_derivacion_situacion WHERE idkardex = '$Id' AND iddependencia='$IdDependencia' AND idsituacion='$IdSituacion'";
		$Consulta 	   = $Conn->Query($Select);
		$row               = $Conn->FetchArray($Consulta);
		$PresentacionFecha = $Conn->DecFecha($row[7]);
		$VencimientoFecha  = $Conn->DecFecha($row[8]);
		$SubsanacionFecha  = $Conn->DecFecha($row[9]);
	}
?>
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script>
	$("#PresentacionFecha").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});
	$("#VencimientoFecha").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});
	$("#SubsanacionFecha").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});	
	function ValidaSituacion(Id){
		$('#trAsiento').css("display", "none");
		$('#AsientoNumero').attr("disabled", true);
		$('#trPartida').css("display", "none");
		$('#PartidaNumero').attr("disabled", true);		
		$('#trPresentacion').css("display", "none");
		$('#PresentacionFecha').attr("disabled", true);		
		$('#trVencimiento').css("display", "none");
		$('#VencimientoFecha').attr("disabled", true);		
		$('#trSubsanacion').css("display", "none");
		$('#SubsanacionFecha').attr("disabled", true);		
		$('#trMonto').css("display", "none");
		$('#Monto').attr("disabled", true);		
		if (Id==1){
			$('#trMonto').css("display", "");
			$('#Monto').removeAttr("disabled");
		}
		if (Id==2){
			$('#trAsiento').css("display", "");
			$('#AsientoNumero').removeAttr("disabled");			
			$('#trPartida').css("display", "");
			$('#PartidaNumero').removeAttr("disabled");			
			$('#trMonto').css("display", "");
			$('#Monto').removeAttr("disabled");
		}
		if (Id==3){
			$('#trPresentacion').css("display", "");
			$('#PresentacionFecha').removeAttr("disabled");			
			$('#trVencimiento').css("display", "");
			$('#VencimientoFecha').removeAttr("disabled");			
			$('#trSubsanacion').css("display", "");
			$('#SubsanacionFecha').removeAttr("disabled");
		}
		if (Id>3 && Id<9){
			$('#trMonto').css("display", "");
			$('#Monto').removeAttr("disabled");
		}
	}
</script>
<div align="center">
<form id="formS" name="formS" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="350" border="0" cellspacing="0" cellpadding="0">
  <tr id="trAsiento">
      <td width="130" class="TituloMant">N&ordm; Asiento : </td>
    <td>
        <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="0formS_asiento_numero" id="AsientoNumero" value="<?php echo $row[5];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'PartidaNumero');"/>
        <input type="hidden" name="2formS_idkardex" value="<?php echo $Id;?>" />
        <input type="hidden" name="2formS_iddependencia" value="<?php echo $IdDependencia;?>" />
        <input type="hidden" name="2formS_idsituacion" value="<?php echo $IdSituacion;?>" />
    </td>
  </tr>
  <tr id="trPartida">
    <td class="TituloMant">Nº Partida : </td>
    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="0formS_partida_numero" id="PartidaNumero" value="<?php echo $row[6];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'PresentacionFecha');"/></td>
  </tr>
  <tr id="trPresentacion">
    <td class="TituloMant">Fec. Presentaci&oacute;n : </td>
    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="3formS_presentacion_fecha" id="PresentacionFecha" value="<?php echo $PresentacionFecha;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'VencimientoFecha');"/></td>
  </tr>
  <tr id="trVencimiento">
    <td class="TituloMant">Fec. Vencimiento : </td>
    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="3formS_vencimiento_fecha" id="VencimientoFecha" value="<?php echo $VencimientoFecha;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'SubsanacionFecha');"/></td>
  </tr>
  <tr id="trSubsanacion">
    <td class="TituloMant">Fec. Subsanaci&oacute;n : </td>
    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="3formS_subsanacion_fecha" id="SubsanacionFecha" value="<?php echo $SubsanacionFecha;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Monto');"/></td>
  </tr>
  <tr id="trMonto">
    <td class="TituloMant">Monto : </td>
    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-align:right" name="0formS_monto" id="Monto" value="<?php echo $row[10];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Observacion');"/></td>
  </tr>
  <tr>
    <td valign="top" class="TituloMant">Observaci&oacute;n : </td>
    <td>
        <textarea name="0formS_observacion" id="Observacion" rows="2" class="inputtext" style="font-size:12px; width:200px; " onKeyPress="CambiarFoco(event, 'Servicio');" align="left" <?php echo $enabled;?> "<?php echo $Enabled;?>"><?php echo $row[11];?></textarea>
    </td>
  </tr>
</table>

</form>
</div>
<script>
    ValidaSituacion(<?php echo $IdSituacion;?>);
</script>