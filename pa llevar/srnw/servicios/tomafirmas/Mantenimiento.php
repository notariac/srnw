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
	$Estado = "<label style='color:#FF6600'>PENDIENTE</label>";
	$Anio = $_SESSION["Anio"];
	$Guardar = "Op=".$Op;	
	if($Op==2 or $Op==4){
            $Enabled = "readonly";
	}	
	$Enabled2 = "readonly";	
	if($Id!=''){
            $Select 	= "SELECT * FROM kardex WHERE idkardex = ".$Id;
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
            $Servicio	= $rowSe[0];
	}
?>
<script>	
	function AgregarFoto(IdParticipante){
            var url = 'ftp/index.php?IdKardex=<?php echo $Id;?>&IdParticipante=' + IdParticipante;
            var ventana = window.open(url, 'Buscar', 'width=430, height=250, resizable=no, scrollbars=no');
            ventana.focus();
	}
	function VerFoto(IdParticipante){
            var url = 'ftp/vistaprevia.php?IdKardex=<?php echo $Id;?>&IdParticipante=' + IdParticipante;
            var ventana = window.open(url, 'Buscar', 'width=595.3, height=841.9, resizable=no, scrollbars=no');
            ventana.focus();
	}	
	function CambiaFirmado(Id){
		if (document.getElementById('Firmo2D' + Id).checked)
        {
                    $('#FirmoD' + Id).val(1);
                    $('#FirmoFecha' + Id).css("display", "block");
                    $('#lblFechaD' + Id).css("display", "none");
                    $('#FirmoFecha' + Id).val('<?php echo date('d/m/Y');?>');
                    $('#lblFechaD' + Id).html('<?php echo date('d/m/Y');?>');
		}
        else
        {
                    $('#FirmoD' + Id).val(0);
                    $('#FirmoFecha' + Id).css("display", "none");
                    $('#lblFechaD' + Id).css("display", "block");
                    $('#FirmoFecha' + Id).val('01/01/1990');
                    $('#lblFechaD' + Id).html('01/01/1990');
		}
	}	
	function Cancelar(){
            window.location.href='index.php';
	}	
	function ValidarFormEnt(evt){
            var keyPressed = (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                     Guardar(<?php echo $Op;?>);
            }
	}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>" enctype="multipart/form-data">
<table width="800" border="0" cellspacing="0" cellpadding="0">
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
	    </table>	
    </td>
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
    <td colspan="3" align="center">
		<table width="750" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu2">
                        <tr>
                            <th title="Cabecera" width="100" height="20">Documento</th>
                            <th title="Cabecera" width="80">N&uacute;mero</th>
                            <th title="Cabecera">Participante</th>
                            <th title="Cabecera" width="100">Participaci&oacute;n</th>
                            <th title="Cabecera" width="50">Firma</th>
                            <th title="Cabecera" width="70">Fecha</th>
                            <th title="Cabecera" width="20">&nbsp;</th>
                        </tr>
                        <tbody>
<?php
			$NumRegs = 0;
			$SQL2 = "SELECT kardex_participantes.idkardex, documento.descripcion, kardex_participantes.idparticipante, cliente.dni_ruc, cliente.nombres, kardex_participantes.idparticipacion, participacion.descripcion, kardex_participantes.firmo FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) WHERE kardex_participantes.idkardex='$Id'";
			$Consulta2 = $Conn->Query($SQL2);			
			while($row2 = $Conn->FetchArray($Consulta2)){
				$NumRegs = $NumRegs + 1;				
				$FechaD = '01/01/1990';					
				$Check = "";
                                if(strpos($row2[4], "!")){
                                    $c      = explode("!",$row2[4]);
                                    $row2[4] = $c[1]." ".$c[0];
                                }
				if ($row2[7]==1){
                                    $Check = 'checked="checked"';
                                    $FechaD = $Conn->DecFecha($row2[8]);
				}				
				$EnabledF = $Enabled;
				if ($row2[9]==0){
                                    $EnabledF = 'readonly';
				}
				$EnabledC = $Enabled;
				if ($row2[8]!=''){
                                    $EnabledC = 'readonly';
				}
?>
                        <tr>
                                <td align="center"><input type='hidden' name='0formD<?php echo $NumRegs;?>_idkardex' value='<?php echo $Id;?>' /><?php echo $row2[1];?></td>
                                <td style="padding-left:5px"><input name="0formD<?php echo $NumRegs;?>_idparticipante" id="IdParticipante<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[2];?>" /><?php echo $row2[3];?></td>
                                <td style="padding-right:5px"><?php echo $row2[4];?></td>
                                <td align="center"><input type="hidden" name="0formD<?php echo $NumRegs;?>_idparticipacion" id="IdParticipacionD<?php echo $NumRegs;?>" value="<?php echo $row2[5]?>" /><?php echo $row2[6];?></td>
                                <td align="center"><input type="hidden" name="0formD<?php echo $NumRegs;?>_firmo" id="FirmoD<?php echo $NumRegs;?>" value="<?php echo $row2[7];?>" />
                                <input type="checkbox" name="0formD<?php echo $NumRegs;?>_firmo2" id="Firmo2D<?php echo $NumRegs;?>" <?php echo $Check;?> <?php echo $Enabled;?> onclick="CambiaFirmado(<?php echo $NumRegs;?>);"/></td>
                                <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_firmofecha" id="FirmoFecha<?php echo $NumRegs;?>" value="<?php echo $FechaD;?>" style="width:70px; text-align:center;" size="10" <?php echo $EnabledFe;?>/><label id="lblFechaD<?php echo $NumRegs;?>"><?php echo $FechaD;?></label></td>
                                <td align="center">
                                    <img src="../../imagenes/iconos/foto.png" width="16" height="16" onclick="AgregarFoto(<?php echo $row2[2];?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Agregar Foto del Participante" />
                                    <img src="../../imagenes/iconos/ver.png" width="16" height="16" onclick="VerFoto(<?php echo $row2[2];?>);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Ver Foto del Participante" />										
                                </td>
                        </tr>
<script>
$("#FirmoFecha<?php echo $NumRegs;?>").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    onSelect: function(dateText, inst){
        $('#lblFechaD<?php echo $NumRegs;?>').html(dateText);
    }
});
CambiaFirmado(<?php echo $NumRegs;?>)
</script>
<?php
        }
        echo "<script> var nDest = $NumRegs; var nDestC = $NumRegs; </script>";
?>
                        </tbody>
                </table>
        <input type="hidden" name="ConParticipantes" id="ConParticipantes" value="<?php echo $NumRegs;?>"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant">Adjunta Parte Final : </td>
    <td colspan="2" align="left">
        <iframe border='0' frameborder="0" scrolling="No" src="subirarchivo.php?Nombre=<?php echo $row[3];?>" width="420" height="42"></iframe>
    </td>
  </tr>
  <tr>
    <td width="133">&nbsp;</td>
    <td colspan="2"><input type="hidden" name="0form_anio" value="<?php echo $Anio;?>" /></td>
  </tr>
</table>
</form>
</div>