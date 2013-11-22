<?php
    if(!session_id()){ session_start(); }	
    include('../../config.php');	
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';	
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Estado = "1";
    $Actual = "1";	
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
        $Select   = "SELECT kardex_tipo_notaria.idkardex_tipo, kardex_tipo.abreviatura, kardex_tipo.descripcion, kardex_tipo_notaria.actual ";
        $Select	 .= "FROM kardex_tipo_notaria INNER JOIN kardex_tipo ON (kardex_tipo_notaria.idkardex_tipo = kardex_tipo.idkardex_tipo) ";
        $Select	 .= "WHERE  kardex_tipo_notaria.anio='".$_SESSION["Anio"]."' AND kardex_tipo_notaria.idnotaria=".$_SESSION["notaria"]." AND kardex_tipo_notaria.idkardex_tipo = '$Id'";
        
        $Consulta = $Conn->Query($Select);
        $row 	  = $Conn->FetchArray($Consulta);		
        $Actual   = $row[3];
        $Guardar  = "$Guardar&Id2=$Id";
    }
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">C&oacute;digo :</td>
    <td><input type="text" class="inputtext" style="text-align:center; width:50px" name="1form1_idkardex_tipo" id="Id" maxlength="2" value="<?php echo $row[0];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Abreviatura : </td>
    <td><input type="text" class="inputtext" style="width:30px; text-transform:uppercase; text-align:center" name="Abreviatura" id="Abreviatura"  maxlength="1" value="<?php echo $row[1];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Descripcion');"/></td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Descripci&oacute;n :</td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="Descripcion" id="Descripcion"  maxlength="100" value="<?php echo $row[2];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Actual');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Actual : </td>
    <td><input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="0form1_actual" id="Actual" value="<?php echo $Actual;?>" <?php echo $Enabled;?> onkeypress="ValidarFormEnt(event); return permite(event, 'num');"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
    $("#Abreviatura").focus();
</script>