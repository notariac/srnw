<?php	
if( !session_id() ){ session_start(); }	
    include('../../config.php');	
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';	
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Estado 	= "1";
    $Actual 	= "1";
    $Afecto	= 0;
    if($Op==2 || $Op==3){
        $Enabled = "readonly";
        $Guardar = "Op=$Op";
    }else{
        if($Op==0 || $Op==1){
            $Guardar = "Op=$Op";
        }
    }
    $Enabled2 = "readonly";	
    if($Id!=''){
        if(isset($_SESSION['super_usuario']) && $_SESSION['super_usuario']==8){
            $Select 	= "SELECT * FROM notaria WHERE idnotaria ='".$Id."'";
        }else{
            $Select 	= "SELECT * FROM notaria WHERE idnotaria ='".$_SESSION['notaria']."'";
        }
        $Consulta 	= $Conn->Query($Select);
        $row 		= $Conn->FetchArray($Consulta);		
        $Afecto		= $row[10];
        $Estado 	= $row[6];
        $Guardar 	= "$Guardar&Id2=$Id";
    }
$ArrayP = array(NULL);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<script>	
function AgregaAnioRepeticion(){
    var IdNumeracion	= $("#anio_repite").val();
    var Numeracion	= document.getElementById("anio_repite").options[document.getElementById("anio_repite").selectedIndex].text;			
    nDestP = nDestP + 1;
    nDestPC = nDestPC + 1;
    var miTabla = document.getElementById('ListaMenu3').insertRow(nDestP);
    var celda1	= miTabla.insertCell(0);
    var celda2	= miTabla.insertCell(1);		
    celda1.innerHTML = "<input type='hidden' name='0formD"  + nDestPC + "_idnotaria' id='IdNotariaD"  + nDestPC + "' value='<?php echo $Id;?>' /><input type='hidden' name='0formD"  + nDestPC + "_anio' value='"  + IdNumeracion + "' />" + Numeracion;
    celda2.innerHTML = "<img src='../../imagenes/iconos/eliminar.png' width='16' height='16' onclick='QuitaNumeracion(" + nDestPC + ");' style='cursor:pointer'/>";						
    $('#ConNumeracion').val(nDestPC);		
    var cssString = 'text-align:center;';
    miTabla.style.cssText = cssString;
    miTabla.setAttribute('style',cssString);		
    var cssString = 'text-align:left;';
    celda1.style.cssText = cssString;
    celda1.setAttribute('style',cssString);	
    $('#anio_repite').focus();
}  
function QuitaNumeracion(x){	
    var current = window.event.srcElement;   
    while ( (current = current.parentElement) && current.tagName !="TR");{
        current.parentElement.removeChild(current);
        nDestP = nDestP - 1;
    }
}
function CambiaAfecto(){
    if (document.getElementById('Afecto2').checked){
        $('#Igv').val(1);
        $('#TrAfecto').css("display", "");
    }else{
        $('#Igv').val(0);
        $('#TrAfecto').css("display", "none");
        $('#IgvNro').val(0);
    }
}
function CambiaEstado(){
    if (document.getElementById('Estado2').checked){
        $('#Estado').val(1);
    }else{
        $('#Estado').val(0);
    }
}	
function TraerDepartamento(IdUbigeo){
    document.getElementById("DivDepartamento").innerHTML = "<center><img src='../../imagenes/avance.gif'  width=20 /></center>";  
    $.ajax({
        url:'../../libs/departamento.php',
        type:'POST',
        async:true,
        data:'IdUbigeo=' + IdUbigeo,
        success:function(data){
           $("#DivDepartamento").html(data);
           TraerProvincia('<?php echo isset($row)?$row[3]:''?>');
        }
    });
}
function TraerProvincia(IdUbigeo){	
    IdDep = $('#IdDepartamento').val();
    document.getElementById("DivProvincia").innerHTML = "<center><img src='../../imagenes/avance.gif' width=20 /></center>";  
    $.ajax({
        url:'../../libs/provincia.php',
        type:'POST',
        async:true,
        data:'IdUbigeo=' + IdUbigeo + '&IdDep=' + IdDep,
        success:function(data){
           $("#DivProvincia").html(data);
           TraerDistrito('<?php echo isset($row)?$row[3]:''?>');
       }
    });
}
function TraerDistrito(IdUbigeo){
    IdProv = $('#IdProvincia').val();
    document.getElementById("DivDistrito").innerHTML = "<center><img src='../../imagenes/avance.gif' width=20 /></center>";  
    $.ajax({
        url:'../../libs/distrito.php',
        type:'POST',
        async:true,
        data:'IdUbigeo=' + IdUbigeo + '&IdProv=' + IdProv,
        success:function(data){
           $("#DivDistrito").html(data);
       }
    });
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
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar?>">
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">C&oacute;digo :</td>
    <td><input type="text" class="inputtext" style="text-align:center; width:50px" name="1form1_idnotaria" id="Id" maxlength="2" value="<?php echo $row[0];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/>    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Descripci&oacute;n&nbsp;:</td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_descripcion" id="Descripcion"  maxlength="100" value="<?php echo $row[1];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Notario');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Notario : </td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase; " name="0form1_notario" id="Notario" value="<?php echo $row[2];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr id="TrUbigeo">
    <td class="TituloMant">Ubigeo :</td>
    <td colspan="2">    	
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <div id="DivDepartamento">
                <select name="IdDepartamento" id="IdDepartamento"></select>
            </div>			
            </td>
            <td>
            <div id="DivProvincia">
                <select name="IdProvincia" id="IdProvincia"></select>
            </div>			
            </td>
            <td>
            <div id="DivDistrito">
                <select name="IdDistrito" id="IdDistrito"></select>
            </div>			
            </td>
          </tr>
        </table>  	
    </td>
  </tr>
    <tr>
    <td class="TituloMant">Direcci&oacute;n : </td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase; " name="0form1_direccion" id="Direccion" value="<?php echo $row[4];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Telefonos');"/></td>
  </tr>
    <tr>
      <td class="TituloMant">Tel&eacute;fonos : </td>
      <td><input type="text" class="inputtext" style="width:150px; text-transform:uppercase; " name="0form1_telefonos" id="Telefonos" value="<?php echo $row[5];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Ruc');"/></td>
    </tr>
    <tr>
      <td class="TituloMant">R.U.C. : </td>
      <td><input type="text" class="inputtext" style="width:120px; text-transform:uppercase; " name="0form1_ruc" id="Ruc" value="<?php echo $row[9];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'anio');"/></td>
    </tr>
    <tr>
      <td class="TituloMant">Año : </td>
      <td><input type="text" class="inputtext" style="width:120px; text-transform:uppercase; " name="0form1_anio" id="anio" value="<?php echo $row[13];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Legal2');"/></td>
    </tr>
  <tr>
    <td class="TituloMant">Afecto I.G.V.  : </td>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
              <label style="cursor: pointer;"><input type="checkbox" name="Afecto2" id="Afecto2" <?php if ($Afecto==1) echo "checked='checked'"?> onclick="CambiaAfecto();" />
              <input type="hidden" name="0form1_igv" id="Igv" value="<?php echo $Afecto;?>" />SI</label>
          </td>
          <td>
          <table width="200" border="0" cellspacing="0" cellpadding="0" id="TrAfecto">
            <tr>
              <td width="37%" align="right" style="padding-right:5px"><span class="TituloMant">% : </span></td>
              <td><input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="0form1_igv_porcentaje" id="IgvNro" value="<?php echo $row[11];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'num');"/></td>
              <td>&nbsp;</td>
            </tr>
          </table>            
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Estado :</td>
    <td><label style="cursor: pointer;"><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Activo</label></td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td><input type="hidden" name="0form1_activo" id="Activo" value="1" /></td>
  </tr>
  <tr>
      <td colspan="2" align="center" class="TituloMant">    
      Repite en :
      <select name="anio_repite" id="anio_repite" class="select" style="width: 150px;" align="right">
<?php
        for($i=date('Y');$i>=1960;$i--){
?>
          <option value="<?php echo $i;?>"><?php echo $i;?></option>
<?php
        }
?>
      </select>
      <img src="../../imagenes/iconos/add.png" alt="" width="16" height="16" style="cursor:pointer;" title="Agregar Año de Repetición de la Numeración" onclick="AgregaAnioRepeticion(this);" />
      <table width="300" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu3">
      <tr>
        <th height="20" title="Cabecera">Año de reinicio</th>
        <th title="Cabecera" width="20">&nbsp;</th>
      </tr>
      <tbody>
<?php
$NumRegs = 0;
if ($Op!=0){
        $SQL2 = "SELECT * FROM reinicio WHERE idnotaria='$Id' ";
        $Consulta2 = $Conn->Query($SQL2);			
        while($row2 = $Conn->FetchArray($Consulta2)){
            $NumRegs = $NumRegs + 1;				
?>
<tr>
    <td style="padding-right:5px">
        <input type="hidden" name="0formD<?php echo $NumRegs;?>_idnotaria" id="IdNotariaD<?php echo $NumRegs;?>" value="<?php echo $Id;?>" />
        <input type="hidden" name="0formD<?php echo $NumRegs;?>_anio" id="AnioD<?php echo $NumRegs;?>" value="<?php echo $row2[1];?>" />
        <?php echo $row2[1];?>
    </td>
    <td align="center"><img src="../../imagenes/iconos/eliminar.png" alt="" width="16" height="16" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none"; }?>" title="Quitar Año de Repetición de la Numeración" onclick="QuitaNumeracion(this);" /></td>
</tr>
<?php
        }
}
        echo "<script> var nDestP = $NumRegs; var nDestPC = $NumRegs; </script>";
?>
    </tbody>
    </table>
      <input type="hidden" name="ConNumeracion" id="ConNumeracion" value="<?php echo $NumRegs;?>"/>
      </td>
  </tr>
</table>
</form>
</div>
<script>
    TraerDepartamento('<?php echo isset($row)?$row[3]:''?>');
    CambiaAfecto();
</script>