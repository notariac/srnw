<?php	
    include('../../config.php');
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Estado     = "1";
    $Actual     = "1";
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
        $Select   = "SELECT * FROM cliente WHERE idcliente = '$Id'";
        $Consulta = $Conn->Query($Select);
        $row      = $Conn->FetchArray($Consulta);
        $Estado   = $row[17];
        $Guardar  = "$Guardar&Id2=$Id";
    }else{
        $Id="0";
    }
$ArrayP = array(NULL);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<script>
function CambiaEstado(){
    if (document.getElementById('Estado2').checked){
        $('#Estado').val(1);
    }else{
        $('#Estado').val(0);
    }
}	
function VerificaCT(){
    if ($('#ClienteTipo').val()==1){
        $('#razon_nombre').html('Nombre Completo');
        $('#DniRuc').attr("maxlength", 8);
        $('#TrSexo').css("display", "");			
    }else{
        $('#razon_nombre').html('Raz&oacute;n Social');
        $('#DniRuc').attr("maxlength", 11);
        $('#TrSexo').css("display", "none");
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
    <td><input type="text" class="inputtext" style="text-align:center; width:70px" name="1form1_idcliente" id="Id" maxlength="2" value="<?php echo $row[0];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/>    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Tipo Cliente  : </td>
    <td><select name="0form1_idcliente_tipo" id="ClienteTipo" class="select" style="font-size:12px" onchange="VerificaCT(); Tab('DniRuc');" >
<?php
$SelectCT 	= "SELECT * FROM cliente_tipo WHERE estado = 1";
$ConsultaCT = $Conn->Query($SelectCT);
        while($rowCT=$Conn->FetchArray($ConsultaCT)){
            $Select = '';
            if ($row[1]==$rowCT[0]){
                    $Select = 'selected="selected"';
            }
?>
            <option value="<?php echo $rowCT[0];?>" <?php echo $Select;?>><?php echo $rowCT[1];?></option>
<?php
        }
?>
            </select>&nbsp;</td>
  </tr>
  <tr id="TrDocumento">
    <td width="130" class="TituloMant">Tipo Documento : </td>
    <td><select name="0form1_iddocumento" id="Documento" class="select" style="font-size:12px" onchange="Tab('DniRuc');" >
<?php
$SelectDoc   = "SELECT * FROM documento WHERE estado = 1";
$ConsultaDoc = $Conn->Query($SelectDoc);
        while($rowDoc=$Conn->FetchArray($ConsultaDoc)){
            $Select = '';
            if ($row[2]==$rowDoc[0]){
                    $Select = 'selected="selected"';
            }
?>
      <option value="<?php echo $rowDoc[0];?>" <?php echo $Select;?>>
        <?php echo $rowDoc[1];?>
      </option>
<?php
        }
?>
    </select></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">N&deg; Documento&nbsp;:</td>
    <td><input type="text" class="inputtext" style="width:110px; text-transform:uppercase;" name="0form1_dni_ruc" id="DniRuc" value="<?php echo $row[3];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'RazonNombre'); return permite(event, 'num');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant"><label id="razon_nombre">Razon Nombre</label>&nbsp;:</td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_nombres" id="RazonNombre"  maxlength="100" value="<?php echo $row[4];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Direcci&oacute;n : </td>
    <td><input type="text"  align="left" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_direccion" id="Direccion" value="<?php echo $row[5];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Email');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Email : </td>
    <td><input type="text" class="inputtext" style="width:280px; " name="0form1_email" id="Email" value="<?php echo $row[6];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Telefono');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Tel&oacute;fono : </td>
    <td><input type="text" class="inputtext" style="width:100px; text-transform:uppercase;" name="0form1_telefonos" id="Telefono" value="<?php echo $row[7];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Sexo')"/></td>
  </tr>
  <tr id="TrSexo">
    <td width="130" class="TituloMant">Sexo : </td>
    <td>
        <select name="0form1_sexo" id="Sexo" style="font-size:12px" >
        <option <?php if ($row[8]=='M') { echo 'selected="selected"';}?> value="M">Maculino</option>
        <option <?php if ($row[8]=='F') { echo 'selected="selected"';}?> value="F">Femenino</option>
        </select>
    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Estado :</td>
    <td>
        <label><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" />
        <input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Activo</label>
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
    VerificaCT();
</script>