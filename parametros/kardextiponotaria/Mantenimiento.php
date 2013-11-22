<?php
if(!session_id()){ session_start(); }
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
        $Select 	= "SELECT kardex_tipo_notaria.idkardex_tipo, kardex_tipo_notaria.actual,kardex_tipo.descripcion FROM kardex_tipo_notaria INNER JOIN kardex_tipo ON kardex_tipo.idkardex_tipo=kardex_tipo_notaria.idkardex_tipo WHERE kardex_tipo_notaria.idkardex_tipo = '$Id' and kardex_tipo_notaria.idnotaria='".$_SESSION['notaria']."'";
        $Consulta 	= $Conn->Query($Select);
        $row 		= $Conn->FetchArray($Consulta);
        $Actual         = $row[3];
        $Estado         = $row[4];
        $Guardar        = "$Guardar&Id2=$Id";
    }
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery.autocomplete.js"></script>
<script>
function CambiaEstado(){
    if (document.getElementById('Estado2').checked){
        $('#Estado').val(1);
    }else{
        $('#Estado').val(0);
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
var CantidadSgt = 'Precio';
$(document).ready(function(){	        		
    function formatItemC(row){
        return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[0] + "</td></tr></table>";
    }
    function formatResult(row){
        return row[0];
    }
    $('#tipokardex').autocomplete('../../libs/autocompletar/tipokardex.php', {
            autoFill: true,
            width: 350,
            selectFirst: false,
            formatItem: formatItemC, 
            formatResult: formatResult,
            mustMatch : false
    }).result(function(event, item){
            $("#tipokardex").val(item[0]);
            $("#idkardex_tipo").val(item[1]);
            $("#Actual").val("0");
            Tab("Actual");
    });	
});
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant">Tipo Kardex : </td>
    <td>
        <input type="hidden" name="0form1_idnotaria" id="idnotaria" value="<?php echo $_SESSION['notaria'];?>"/>
        <input type="hidden" name="0form1_idkardex_tipo" id="idkardex_tipo" value="<?php echo $row[0];?>"/>
        <input type="text" class="inputtext" style="width:320px; text-transform:uppercase;" name="tipokardex" id="tipokardex"  maxlength="100" value="<?php echo $row[2];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Actual');"/>
    </td>
  </tr>
  <tr>
    <td class="TituloMant">Actual : </td>
    <td>
        <input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="0form1_actual" id="Actual" value="<?php echo $row[1];?>" <?php echo $Enabled;?> onkeypress="ValidarFormEnt(event); return permite(event, 'num');"/>
        <input type="hidden" name="0form1_anio" id="Anio" value="<?php echo date("Y");?>" />
        <input type="hidden" name="0form1_idusuario" id="idUsuario" value="<?php echo $_SESSION["id_user"];?>" />
        <input type="hidden" name="0form1_fechareg" id="idUsuario" value="<?php echo date("Y-m-d");?>" />
    </td>
  </tr>  
</table>
</form>
</div>
<script>
    $("#Abreviatura").focus();
</script>