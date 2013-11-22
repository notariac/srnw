<?php	
if(!session_id()){ session_start(); }	
    include('../../config.php');
    include('../../config_seguridad.php');	
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Usuario = $_SESSION["Usuario"];
    $Fecha	= date('d/m/Y');
    $FechaD	= date('d/m/Y');
    $Estado = "<label style='color:#FF6600'>PENDIENTE</label>";
    $Anio = $_SESSION["Anio"];
    $Guardar = "Op=".$Op;
    if($Op==2 || $Op==4){
            $Enabled = "readonly";
    }
    $Enabled2 = "readonly";
    if($Id!=''){
            $Select 	= "SELECT * FROM kardex WHERE idkardex = '$Id'";
            $Consulta 	= $Conn->Query($Select);
            $row 	= $Conn->FetchArray($Consulta);
            $Usuario 	= $_SESSION["Usuario"];
            $Fecha	= $Conn->DecFecha($row[2]);
            $Firmado	= $row[13];
            if ($row[15]==1){
                    $Estado = "<label style='color:#003366'>GENERADO</label>";
            }
            if ($row[15]==2){
                    $Estado = "<label style='color:#003366'>TERMINADO</label>";
            }
            if ($row[15]==3){
                    $Estado = "<label style='color:#FF00000'>ANULADO</label>";
            }
            $Anio = $row[18];
            $Sql = "SELECT nombres FROM usuario WHERE idusuario=".$row[16];
            $ConsultaS = $ConnS->Query($Sql);
            $rowS = $ConnS->FetchArray($ConsultaS);
            $Usuario	= $rowS[0];
            $SqlSe = "SELECT descripcion FROM servicio WHERE idservicio=".$row[4];
            $ConsultaSe = $Conn->Query($SqlSe);
            $rowSe = $Conn->FetchArray($ConsultaSe);
            $Servicio = $rowSe[0];
    }
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery.autocomplete.js"></script>
<script>
	var IndexTab = 0;
	$(document).ready(function(){
		$("#ActualizaDatos").dialog({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Actualizar Datos",
			width: 400,
			height: 300,
			show: "scale",
			hide: "scale",
			buttons: 
			{
				"Agregar": function() {
					//GuardarS(1);
					ActualizaS(1,'','');
				},
				Cancelar: function() {
					$("#DivActualizaDatos").html('');
					$("#ActualizaDatos").dialog("close");
				}
			}	   
		});
		
		$("#tabs").tabs({ 
			select: function(event, ui){
				IndexTab = ui.index;
			}
		});
	});
	
	$("#FechaD").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});
	
	$("#FechaDS").datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});	
	function AgregaD(){
		var IdDependencia	= $("#Dependencia").val();
		var Dependencia = document.getElementById("Dependencia").options[document.getElementById("Dependencia").selectedIndex].text;
		var FechaD		= $("#FechaD").val();	
		if (IdDependencia != ''){
			nDest = nDest + 1;
			nDestC = nDestC + 1;
			var miTabla = document.getElementById('ListaMenu2').insertRow(nDest);
			var celda1	= miTabla.insertCell(0);
			var celda2	= miTabla.insertCell(1);
			var celda3	= miTabla.insertCell(2);											
			celda1.innerHTML = "<input type='hidden' name='0formD"  + nDestC + "_idkardex' value='<?php echo $Id;?>' /><input name='0formD"  + nDestC + "_iddependencia' id='IdDependencia"  + nDestC + "' type='hidden' value='" + IdDependencia + "' /><label id='lblDependencia"  + nDestC + "' style='display:none' >" + nDestC + "</label>" + Dependencia;
			celda2.innerHTML = "<input type='hidden' name='3formD"  + nDestC + "_fecha' id='FechaD"  + nDestC + "' value='" + FechaD + "' />" + FechaD;
			celda3.innerHTML = "<img src='../../imagenes/iconos/foto.png' width='16' height='16' onclick='AgregarFoto("  + IdDependencia + ");' style='cursor:pointer;' title='Agregar Imagen' /><img src='../../imagenes/iconos/quitar.png' width='16' height='16' onclick='QuitaD("  + nDestC + ");' style='cursor:pointer;' title='Quitar la Dependencia' />";							
			$('#ConDependencia').val(nDestC);			
			var cssString = 'text-align:center;';
			miTabla.style.cssText = cssString;
			miTabla.setAttribute('style',cssString);			
			var cssString = 'padding-left:5;text-align:left;';
			celda1.style.cssText = cssString;
			celda1.setAttribute('style',cssString);			
			$('#Dependencia').val('');
			$('#FechaD').val('');			
			$('#Dependencia').focus();
			$('#tabs').tabs('add', 'estados.php?IdKardex=<?php echo $Id;?>&IdDependencia=' + IdDependencia, Dependencia);
			$('#tabs').tabs('select', nDest-1);
			NumerarC();
                }
	}
	function QuitaD(Index){	
            var Depende = $('#lblDependencia' + Index).html();		
            $('#tabs').tabs('remove', Depende - 1);		
            var current = window.event.srcElement;   		
            while ( (current = current.parentElement) && current.tagName !="TR");{
                current.parentElement.removeChild(current);
                nDest = nDest - 1;
            }
            NumerarC();
	}
	function NumerarC(){
            var contt = 1;
            nTotal = 0;
            for (var i=1;i<=nDestC;i++){
                try{	
                    document.getElementById('lblDependencia' + i).innerHTML = contt;
                    contt = contt + 1;
                }catch(err){}
            }
	}	
	function AgregaDS(){
            var Indice;
            for (var i=1;i<=nDestC;i++){
                try{	
                    Indice = $('#lblDependencia' + i).html();
                    if (IndexTab==Indice-1){
                        var IdDependencia = $('#IdDependencia' + i).val();
                    }
                }
                catch(err){}
            }

            var Titulo	= $("#Titulo").val();
            var IdSituacion	= $("#Situacion").val();
            var Situacion = document.getElementById("Situacion").options[document.getElementById("Situacion").selectedIndex].text;
            var FechaDS	= $("#FechaDS").val();		
            if (Titulo != ''){
                var contador = 0;
                eval('contador = nDest' + IdDependencia + ' = nDest' + IdDependencia + ' + 1');
                eval('nDestC' + IdDependencia + ' = nDestC' + IdDependencia + ' + 1');
                var miTabla = document.getElementById('ListaS' + IdDependencia).insertRow(contador);
                var celda1	= miTabla.insertCell(0);
                var celda2	= miTabla.insertCell(1);
                var celda3	= miTabla.insertCell(2);
                var celda4	= miTabla.insertCell(3);											
                celda1.innerHTML = "<input type='hidden' name='0formD" + IdDependencia + "S" + contador + "_idkardex' id='IdKardexD" + IdDependencia + "S" + contador + "' value='<?php echo $Id;?>' /><input type='hidden' name='0formD" + IdDependencia + "S" + contador + "_iddependencia' id='IdDependenciaD" + IdDependencia + "S" + contador + "' value='" + IdDependencia + "' /><input type='hidden' name='0formD" + IdDependencia + "S" + contador + "_idsituacion' id='IdSituacionD" + IdDependencia + "S" + contador + "' value='" + IdSituacion + "' /><input type='hidden' name='SituacionD" + IdDependencia + "S" + contador + "' id='SituacionD" + IdDependencia + "S" + contador + "' value='" + Situacion + "' />" + Situacion;
                celda2.innerHTML = "<input type='hidden' name='0formD" + IdDependencia + "S" + contador + "_titulo_numero' id='TituloNumeroD" + IdDependencia + "S" + contador + "' value='" + Titulo + "' />" + Titulo;
                celda3.innerHTML = "<input type='hidden' name='3formD" + IdDependencia + "S" + contador + "_fecha' id='FechaD" + IdDependencia + "S" + contador + "' value='" + FechaDS + "' />" + FechaDS;
                celda4.innerHTML = "<img src='../../imagenes/iconos/ver.png' width='16' height='16' onclick='DatosDS(" + IdSituacion + ", "  + IdDependencia + ");' style='cursor:pointer;' title='Modificar Datos' /><img src='../../imagenes/iconos/quitar.png' width='16' height='16' onclick='QuitaDS("  + contador + ", "  + IdDependencia + ", " + IdSituacion + ");' style='cursor:pointer;' title='Quitar la Situaci&oacute;n' />";							
                $('#ConSituacion' + IdDependencia).val(contador);			
                var cssString = 'text-align:center;';
                miTabla.style.cssText = cssString;
                miTabla.setAttribute('style',cssString);			
                var cssString = 'padding-left:5;text-align:left;';
                celda1.style.cssText = cssString;
                celda1.setAttribute('style',cssString);
                ActualizaS(0, IdDependencia, IdSituacion);			
                $('#Situacion').val('');
                $('#FechaDS').val('');			
                $('#Titulo').focus();
            }
	}
	function QuitaDS(Index, IdDependencia, IdSituacion){		
            var current = window.event.srcElement;   		
            ActualizaS(3, IdDependencia, IdSituacion);		
            while ( (current = current.parentElement) && current.tagName !="TR");{
                current.parentElement.removeChild(current);
                eval('nDest' + IdDependencia + ' = nDest' + IdDependencia + ' - 1');			
            }
	}
	function DatosDS(IdSituacion, IdDependencia){		
            $("#ActualizaDatos").dialog("open");
            $("#DivActualizaDatos").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
            $.ajax({
                url:'estados_modificar.php',
                type:'POST',
                async:true,
                data:'Id=<?php echo $Id;?>&IdSituacion=' + IdSituacion + '&IdDependencia=' + IdDependencia,
                success:function(data){
                   $("#DivActualizaDatos").html(data);
                }
            });
	}
	function GuardarS(Op){
            $.ajax({
                url:'guardar_situacion.php?Op=' + Op,
                type:'POST',
                async:true,
                data:$('#formS').serialize(),
                success:function(data){
                   $("#DivActualizaDatos").html('');
                   $("#ActualizaDatos").dialog("close");
                }
            });
	}	
	function ActualizaS(Op, IdDependencia, IdSituacion){
            $.ajax({
                url:'guardar_situacion_estado.php?Op=' + Op + '&IdKardex=<?php echo $Id;?>&IdDependencia=' + IdDependencia + '&IdSituacion=' + IdSituacion,
                type:'POST',
                async:true,
                data:$('#formS').serialize() + '&' + $('#formD' + IdDependencia).serialize(),
                success:function(data){
                    $("#DivActualizaDatos").html('');
                    $("#ActualizaDatos").dialog("close");
                }
            });
	}	
	function AgregarFoto(IdDependencia){
            var url = 'ftp/index.php?IdKardex=<?php echo $Id;?>&IdDependencia=' + IdDependencia;
            var ventana = window.open(url, 'Buscar', 'width=430, height=250, resizable=no, scrollbars=no');
            ventana.focus();
	}
	function VerFoto(IdDependencia){
            var url = 'ftp/vistaprevia.php?IdKardex=<?php echo $Id;?>&IdDependencia=' + IdDependencia;
            var ventana = window.open(url, 'Buscar', 'width=350, height=400, resizable=no, scrollbars=no');
            ventana.focus();
	}	
	function Cancelar(){
            window.location.href='index.php';
	}	
	function ValidarFormEnt(evt){
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                Guardar(<?php echo $Op;?>);
            }
	}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>" enctype="multipart/form-data">
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="133" class="TituloMant">Nro Kardex :</td>
    <td width="292"><input type="text" class="inputtext" style="text-align:center; font-size:12px; width:65px" name="0form1_correlativo" id="Id" maxlength="2" value="<?php echo $row[3];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Cliente');"/>
      <input type="hidden" name="1form1_idkardex" value="<?php echo $row[0];?>" /><input type="hidden" name="0form1_archivo" value="<?php echo $row[3];?>.doc" /></td>
    <td width="267" align="right">
		<table width="160" border="0" cellspacing="0" cellpadding="0">
      		<tr>
			<td>&nbsp;</td>
			<td align="right"><?php echo $Estado;?></td>
		  </tr>
	    </table>	</td>
  </tr>
  <tr>
    <td width="133" class="TituloMant">Fecha : </td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha" id="Fecha" value="<?php echo $Fecha;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'Servicio');"/></td>
  </tr>
  <tr>
    <td width="133" class="TituloMant">Servicio  :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px;" name="Servicio" id="Servicio"  maxlength="100" value="<?php echo $Servicio;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'NroEscritura');"/></td>
  </tr>
  <tr>
    <td colspan="3" >&nbsp;</td>
    </tr>
  <tr>
    <td colspan="3" align="center" <?php if ($Op==2 || $Op==3 || $Op==4){ echo "style='display:none'";} ?>>
    <table width="350" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><select name="Dependencia" id="Dependencia" class="select" style="font-size:12px;" onchange="Tab('FechaD');" >
          <option value="">-- Selecciones una Dependencia</option>
<?php
    		$SelectLT 	= "SELECT * FROM dependencia WHERE estado = 1";
			$ConsultaLT = $Conn->Query($SelectLT);
			while($rowLT=$Conn->FetchArray($ConsultaLT)){
?>
          <option value="<?php echo $rowLT[0];?>"><?php echo utf8_decode($rowLT[1]);?></option>
<?php
			}
?>
        </select></td>
        <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px;" name="FechaD" id="FechaD" value="<?php echo $FechaD;?>" /></td>
        <td align="center"><img src="../../imagenes/iconos/add.png" width="16" height="16" onclick="AgregaD();" style="cursor:pointer;" title="Agregar Dependencia" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="100" colspan="3" align="center" valign="top">
		<table width="360" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu2">
                		  		<tr>
									<th title="Cabecera" height="20">Dependencia</th>
									<th title="Cabecera" width="70">Fecha</th>
									<th title="Cabecera" width="53">&nbsp;</th>
              		   			</tr>
                		  		<tbody>
<?php
			$NumReg = 0;
                        $Sql2 = "SELECT kardex_derivacion.idkardex, kardex_derivacion.iddependencia, dependencia.descripcion, kardex_derivacion.fecha ";
			$Sql2 = $Sql2." FROM kardex_derivacion INNER JOIN dependencia ON (kardex_derivacion.iddependencia = dependencia.iddependencia) ";
			$Sql2 = $Sql2." WHERE kardex_derivacion.idkardex = ".$Id;
			$Consulta2 = $Conn->Query($Sql2);
			while($row2=$Conn->FetchArray($Consulta2)){
				$NumReg = $NumReg + 1;
				$FechaD = $Conn->DecFecha($row2[3]);
?>
                		    		<tr>
                		      			<td align="left" style="padding-left:5;">
											<input type='hidden' name='0formD<?php echo $NumReg;?>_idkardex' value='<?php echo $Id;?>' />
											<input type="hidden" name="0formD<?php echo $NumReg;?>_iddependencia" id="IdDependencia<?php echo $NumReg;?>" value="<?php echo $row2[1];?>" /><label id="lblDependencia<?php echo $NumReg;?>" style="display:none"><?php echo $NumReg;?></label><?php echo $row2[2];?>
                                                        </td>
                		      			<td align="center">
											<input type="hidden" name="3formD<?php echo $NumReg;?>_fecha" id="FechaD<?php echo $NumReg;?>" value="<?php echo $FechaD;?>" /><?php echo $FechaD;?>
                                                        </td>
                		      			<td align="center">
											<img src="../../imagenes/iconos/foto.png" width="16" height="16" onclick="AgregarFoto(<?php echo $row2[1];?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Agregar Imagen" />
											<img src="../../imagenes/iconos/ver.png" width="16" height="16" onclick="VerFoto(<?php echo $row2[1];?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Ver Imagen" />
											<img src="../../imagenes/iconos/quitar.png" width="16" height="16" onclick="QuitaD(<?php echo $NumReg;?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar la Dependencia" />										
                                                        </td>
              		      			</tr>
<script>
    $('#tabs').tabs('add', 'estados.php?IdKardex=<?php echo $Id;?>&IdDependencia=<?php echo $row2[1];?>','<?php echo $row2[2];?>');
</script>
<?php
			}
			echo "<script> var nDest = $NumReg; var nDestC = $NumReg; </script>";
?>
              		    		</tbody>
  		  </table>
		<input type="hidden" name="ConDependencia" id="ConDependencia" value="<?php echo $NumReg;?>"/>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="TituloMant">Nº T&iacute;tulo: 
          <input type="text" class="inputtext" style="font-size:12px; width:80px;" name="Titulo" id="Titulo" value="" /></td>
        <td><select name="Situacion" id="Situacion" class="select" style="font-size:12px;" onchange="Tab('FechaO');" >
            <option value="">-- Selecciones una Situaci&oacute;n</option>
<?php
    		$SelectLT 	= "SELECT * FROM situacion WHERE estado = 1";
                $ConsultaLT = $Conn->Query($SelectLT);
                while($rowLT=$Conn->FetchArray($ConsultaLT)){
?>
                <option value="<?php echo $rowLT[0];?>"><?php echo $rowLT[1];?></option>
<?php
                }
?>
        </select></td>
        <td><input type="text" class="inputtext" style="font-size:12px; width:80px;" name="FechaDS" id="FechaDS" value="<?php echo $FechaD;?>" /></td>
        <td align="center"><img src="../../imagenes/iconos/add.png" width="16" height="16" onclick="AgregaDS();" style="cursor:pointer;" title="Agregar Situaci&oacute;n" /></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td colspan="3" align="center" class="TituloMant">
        <div id="tabs">
            <ul>
            </ul>
        </div>
    </td>
  </tr>
  <tr>
    <td width="133">&nbsp;</td>
    <td colspan="2"><input type="hidden" name="0form_anio" value="<?php echo $Anio;?>" /></td>
  </tr>
</table>
</form>
</div>