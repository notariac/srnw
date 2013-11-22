<?php	
if(!session_id()){session_start();}
include('../../config.php');
include('../../config_seguridad.php');
$Op = $_POST["Op"];
$Id = isset($_POST["Id"])?$_POST["Id"]:'';
$Enabled	= "";
$Enabled2	= "";
$Guardar	= "";	
$Estado 	= "1";	
if($Op==2 || $Op==3)
{
    $Enabled = "readonly";
    $Guardar = "Op=$Op";
}
else
{
    if($Op==0 || $Op==1)
    {
        $Guardar = "Op=$Op";
    }
}
$Enabled2 = "readonly";	
if($Id!=''){
    $Select 	= "SELECT * FROM caja WHERE idcaja = '$Id' AND idnotaria='".$_SESSION['notaria']."'";
    $Consulta 	= $Conn->Query($Select);
    $row 	= $Conn->FetchArray($Consulta);		
    $Estado 	= $row[3];
    $Guardar 	= "$Guardar&Id2=$Id";
}
$ArrayP = array(NULL);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
  $('.quit').live('click',function(){
      $(this).parent().parent().remove();
      nDestP = nDestP - 1;
  })
})
function CambiaEstado()
{
    if (document.getElementById('Estado2').checked){
        $('#Estado').val(1);
    }else{
        $('#Estado').val(0);
    }
}	
function AgregaComprobante()
{
    var IdComprobante	= $("#Comprobante").val();
    var Comprobante	= document.getElementById("Comprobante").options[document.getElementById("Comprobante").selectedIndex].text;
    var Serie		= $("#Serie").val();
    var Correlativo	= $("#Correlativo").val();
    if(Serie=='')
    {
        return false;
    }
    if(Correlativo=='')
    {
        return false;
    }
    nDestP = nDestP + 1;
    nDestPC = nDestPC + 1;
    var miTabla = document.getElementById('ListaMenu3').insertRow(nDestP);
    var celda1	= miTabla.insertCell(0);
    var celda2	= miTabla.insertCell(1);
    var celda3	= miTabla.insertCell(2);
    var celda4	= miTabla.insertCell(3);
    celda1.innerHTML = "<input type='hidden' name='0formD"  + nDestPC + "_idcaja' id='IdCajaD"  + nDestPC + "' value='<?php echo $Id;?>' /><input type='hidden' name='0formD"  + nDestPC + "_idnotaria' id='idnotariaD"  + nDestPC + "' value='<?php echo $_SESSION['notaria'];?>' /><input type='hidden' name='0formD"  + nDestPC + "_idcomprobante' value='"  + IdComprobante + "' />" + Comprobante;
    celda2.innerHTML = "<input type='text' name='0formD"  + nDestPC + "_serie' id='SerieD"  + nDestPC + "' style='width:40px; text-align:center' maxlength='3' value='" + Serie + "' onkeypress='return permite(event, \"num\")\'/>";
    celda3.innerHTML = "<input type='text' name='0formD"  + nDestPC + "_correlativo' id='CorrelativoD"  + nDestPC + "' style='width:60px; text-align:center' maxlength='7' value='" + Correlativo + "' onkeypress='return permite(event, \"num\")\'/>";
    celda4.innerHTML = "<img class='quit' src='../../imagenes/iconos/eliminar.png' width='16' height='16'  style='cursor:pointer'/>";
    $('#ConComprobante').val(nDestPC);
    var cssString = 'text-align:center;';
    miTabla.style.cssText = cssString;
    miTabla.setAttribute('style',cssString);
    var cssString = 'padding-left:5;text-align:left;';
    celda1.style.cssText = cssString;
    celda1.setAttribute('style',cssString);
    $('#Comprobante').focus();
    $("#Serie").val("");
    $("#Correlativo").val("");
}
function QuitaComprobante(x){	
    var current = window.event.srcElement;   
    while ( (current = current.parentElement) && current.tagName !="TR");
    {
        current.parentElement.removeChild(current);
        nDestP = nDestP - 1;
    }
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
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">C&oacute;digo :</td>
    <td><input type="text" class="inputtext" style="text-align:center; width:50px" name="1form1_idcaja" id="Id" maxlength="2" value="<?php echo $row[0];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/>    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Descripci&oacute;n&nbsp;:</td>
    <td><input type="hidden" name="0form1_idnotaria" id="idnotaria" value="<?php echo $_SESSION['notaria'];?>" /><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_descripcion" id="Descripcion"  maxlength="100" value="<?php echo $row[1];?>" <?php echo $Enabled;?> /></td>
  </tr>
  <tr>
    <td class="TituloMant">Responsable : </td>
    <td><select name="0form1_idresponsable" id="Responsable" class="select" style="width:300px; font-size:12px">
<?php
        $SelectKT = "SELECT idusuario, nombres FROM usuario WHERE estado = 1 AND idnotaria='".$_SESSION['notaria']."' ORDER BY nombres ASC";
        $ConsultaKT = $ConnS->Query($SelectKT);
        while($rowKT=$ConnS->FetchArray($ConsultaKT)){
            $Select = '';
            if ($row[2]==$rowKT[0]){
                    $Select = 'selected="selected"';
            }
?>
<option value="<?php echo $rowKT[0];?>" <?php echo $Select;?>><?php echo $rowKT[1];?></option>
<?php
        }
?>
    </select></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Estado :</td>
    <td>
        <label style="cursor: pointer;font-size: 11px;">
        <input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" />
        <input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Activo
        </label>
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
	<table width="400" border="0" cellspacing="1">
      <tr>
        <td><select name="Comprobante" id="Comprobante" class="select" style="width:150px; font-size:12px">
<?php
          $SelectKT = "SELECT idcomprobante, descripcion FROM comprobante WHERE estado = 1 ORDER BY descripcion ASC";
          $ConsultaKT = $Conn->Query($SelectKT);
          while($rowKT=$Conn->FetchArray($ConsultaKT)){
?>
<option value="<?php echo $rowKT[0];?>" <?php echo $Select;?>><?php echo $rowKT[1];?></option>
<?php
          }
?>
        </select></td>
        <td>Serie : 
          <input type="text" name="Serie" id="Serie" style="width:40px; text-align:center" maxlength="3" onkeypress="return permite(event, 'num')"/></td>
        <td>Actual :
          <input type="text" name="Correlativo" id="Correlativo" style="width:60px; text-align:center" maxlength="7" onkeypress="return permite(event, 'num')"/></td>
        <td width="20"><img src="../../imagenes/iconos/add.png" alt="" width="16" height="16" style="cursor:pointer;" title="Agregar Comprobante" onclick="AgregaComprobante(this);" /></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td colspan="2" align="center"><table width="400" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu3">
      <tr>
        <th height="20" title="Cabecera">Comprobante</th>
        <th width="40" title="Cabecera">Serie</th>
        <th width="60" title="Cabecera">Actual</th>
        <th title="Cabecera" width="20">&nbsp;</th>
      </tr>
      <tbody>
<?php
    $NumRegs = 0;
    if ($Op!=0){
        $SQL2 = "SELECT caja_notaria_comprobante.idcaja, caja_notaria_comprobante.idnotaria, caja_notaria_comprobante.idcomprobante, comprobante.descripcion, caja_notaria_comprobante.serie, caja_notaria_comprobante.correlativo FROM comprobante INNER JOIN caja_notaria_comprobante ON (comprobante.idcomprobante = caja_notaria_comprobante.idcomprobante) WHERE caja_notaria_comprobante.idcaja ='$Id' AND caja_notaria_comprobante.idnotaria='".$_SESSION['notaria']."'";
        $Consulta2 = $Conn->Query($SQL2);			
        while($row2 = $Conn->FetchArray($Consulta2)){
            $NumRegs = $NumRegs + 1;				
?>
        <tr>
          <td style="padding-left:5px">
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idcaja" id="IdCajaD<?php echo $NumRegs;?>" value="<?php echo $row2[0];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idnotaria" id="IdNotariaD<?php echo $NumRegs;?>" value="<?php echo $row2[1];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idcomprobante" id="IdComprobanteD<?php echo $NumRegs;?>" value="<?php echo $row2[2];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idnotaria" id="IdnotariaD<?php echo $NumRegs;?>" value="<?php echo $_SESSION['notaria'];?>" />
            <?php echo $row2[3];?>
          </td>
          <td><input type="text" name="0formD<?php echo $NumRegs;?>_serie" id="SerieD<?php echo $NumRegs;?>" style="width:40px; text-align:center" maxlength="3" value="<?php echo $row2[4];?>" onkeypress="return permite(event, 'num')"/></td>
          <td><input type="text" name="0formD<?php echo $NumRegs;?>_correlativo" id="CorrelativoD<?php echo $NumRegs;?>" style="width:60px; text-align:center" maxlength="7" value="<?php echo $row2[5];?>" onkeypress="return permite(event, 'num')"/></td>
          <td align="center"><img class="quit" src="../../imagenes/iconos/eliminar.png" alt="" width="16" height="16" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar Participacion"  /></td>
        </tr>
<?php
            }
    }
		echo "<script> var nDestP = $NumRegs; var nDestPC = $NumRegs; </script>";
?>
      </tbody>
    </table>
      <input type="hidden" name="ConComprobante" id="ConComprobante" value="<?php echo $NumRegs;?>"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
    VerificaCT();
    CambiaEspecial();
    CambiaCorrelativo();
</script>