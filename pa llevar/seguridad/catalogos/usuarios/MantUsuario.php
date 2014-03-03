<?php
if(!session_id()){ session_start(); }
    include("../../config.php");
    include("../../clases/main.php");
    CuerpoSuperior("Mantenimiento de Usuario");
    $Guardar 	= (isset($_GET["Op"]))?$_GET["Op"]:'';
    $Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';
    $Fecha = date('d/m/Y');
    $Estado = 1;
    if ($Id != ''){
        $SQL 		= "SELECT * FROM usuario WHERE idusuario = '$Id'";
        $Consulta	= $Conn->Query($SQL);
        $row		= $Conn->FetchArray($Consulta);
        $Guardar        = "$Guardar&Id2=$Id";
        $Fecha          = $Conn->DecFecha($row[9]);
        $Estado         = $row[10];
        include("../../config_srnw.php");
        $ConsultaS	= $ConnS->Query("SELECT dni, saldo FROM cuenta WHERE dni = '".$row[0]."'");
        $rowC		= $ConnS->FetchArray($ConsultaS);        
        $ConsultaS	= $ConnS->Query("SELECT * FROM notaria WHERE idnotaria = '".$row[12]."'");
        $rowS		= $ConnS->FetchArray($ConsultaS);
    }
?>
<script>
function CambiaEstado(){
    if (document.getElementById('Estado2').checked){
        document.getElementById('Estado').value = 1;
    }else{
        document.getElementById('Estado').value = 0;
    }
}
function EstadoCuenta(valor){
    if ($('#ChkCuenta').is(':checked')){
        $("#CapaSaldo").css("display","block");
        $("#Cuenta").val(valor);
    }else{
        $("#CapaSaldo").css("display","none");
        $("#Cuenta").val("0.00");
    }
}
function Cancelar(){
    location.href = 'index.php';
}
function ValidarForm(){
    if (document.form1.Nombres.value == ''){
      alert("El Nombre del Usuario no puede ser NULA");
      return false;
    }
    document.form1.submit();
}
$(document).ready(function(){
function formatItemC(row){
    return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[0] + "</td></tr></table>";
}
function formatResult(row){
    return row[0];
}
$('#Notaria').autocomplete('../../libs/autocompletar/notaria.php', {
    autoFill: true,
    width: 350,
    selectFirst: false,
    formatItem: formatItemC, 
    formatResult: formatResult,
    mustMatch : false
}).result(function(event, item) {
    $("#Notaria").val(item[0]);
    $("#idnotaria").val(item[1]);
});
});          
function Sobre(obj){
      obj.style.width=90;
}
function Fuera(obj){
      obj.style.width=85;
}
</script>
<script type="text/javascript" src="../../js/popcalendar.js"></script>
<script type="text/javascript" src="../../js/jquery.autocomplete.js"></script>
<link href="../../css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<form action="guardar.php?Op=<?php echo isset($Guardar)?$Guardar:'';?>" method="post" name="form1">
  <table width="572" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#5398FF" class="Titulo">Mantenimiento de Usuario</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td width="107" class="MantTitulo">Código :</td>
      <td colspan="3" class="MantItem">
        <input type="text" name="1form1_idusuario" id="Codigo" class="inputtext" size="4" style="text-transform:uppercase" readonly="readonly" value="<?php echo (isset($Id))?$Id:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Nombres :</td>
      <td colspan="3" class="MantItem">
        <input name="0form1_nombres" type="text" class="inputtext" id="Nombres" style="text-transform:uppercase" size="48" maxlength="200" value="<?php echo (isset($row[5]))?$row[5]:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Dirección :</td>
      <td colspan="3" class="MantItem"><input name="0form1_direccion" type="text" class="inputtext" id="Direccion" style="text-transform:uppercase" size="48" maxlength="48" value="<?php echo (isset($row[6]))?$row[6]:'';?>"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Teléfono :</td>
      <td width="120" class="MantItem"><input name="0form1_telefonos" type="text" class="inputtext" id="Telefono" style="text-transform:uppercase" size="20" maxlength="20" value="<?php echo (isset($row[7]))?$row[7]:'';?>"/></td>
      <td width="41" class="MantTitulo">Dni :</td>
      <td width="241" class="MantItem"><input name="0form1_dni" type="text" class="inputtext" id="Dni" style="text-transform:uppercase" size="8" maxlength="8" value="<?php echo (isset($row[0]))?$row[0]:'';?>"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Fecha de Ingreso :</td>
      <td colspan="3" class="MantItem"><input name="3form1_fechaingreso" type="text" class="inputtext" id="Fecha" style="text-transform:uppercase" size="10" maxlength="10" value="<?php echo $Fecha;?>" readonly="readonly"/>
        <img src="../../images/btn_calendar.gif" style="cursor:pointer" width="20" height="20" onclick="popUpCalendar(form1.Fecha, form1.Fecha, 'dd/mm/yyyy');"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Login :</td>
      <td colspan="3" class="MantItem"><input name="0form1_login" type="text" class="inputtext" id="Login" size="20" maxlength="20" value="<?php echo (isset($row[3]))?$row[3]:'';?>"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Contrase&ntilde;a :</td>
      <td colspan="3" class="MantItem"><input name="0form1_contra" type="password" class="inputtext" id="Contra" size="20" maxlength="20" value="<?php echo (isset($row[4]))?$row[4]:'';?>"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Observación :</td>
      <td colspan="3" class="MantItem">
        <textarea name="0form1_observacion" id="Observacion" cols="45" rows="5" class="inputtext" style="text-transform:uppercase"><?php echo isset($row[8])?$row[8]:''?></textarea>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Estado :</td>
      <td colspan="3" class="MantItem" style="font-size:11px;"><label style="cursor:pointer;"><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'"?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado?>"/> Registro Activo</label></td>
    </tr>
    <tr>
      <td class="MantTitulo">Notaria :</td>
      <td colspan="3" class="MantItem">
        <input type="hidden" name="0form1_idnotaria" id="idnotaria" value="<?php echo isset($row[12])?$row[12]:'';?>"/>
        <input type="text" class="inputtext" size="60" style="text-transform:uppercase; font-size:12px" name="Notaria" id="Notaria" value="<?php echo isset($rowS[1])?$rowS[1]:'';?>" />
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Cuenta :</td>
      <td colspan="3" class="MantItem" style="font-size:11px;">
          <label style="cursor:pointer;">
              <input type="checkbox" name="ChkCuenta" id="ChkCuenta" <?php if ($rowC[0]==$row[0]) echo "checked='checked'";?> onclick="EstadoCuenta('<?php echo number_format($rowC[1], 2);?>');" value="SI"/>Activar Cuenta
          </label>
      </td>
    </tr>
    <tr>      
      <td class="MantTitulo"></td>      
      <td colspan="3" class="MantItem">
          <div id="CapaSaldo" <?php if ($rowC[0]!=$row[0]) echo "style='display:none;'";?>>
              <input type="text" class="inputtext" name="Cuenta" id="Cuenta" value="<?php echo number_format($rowC[1], 2);?>" style="width: 80px;"/>
          </div>
      </td>      
    </tr>    
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr class="Pie">
      <td colspan="4" align="center" ><table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="200" align="center"><img id="BtnAceptar" src="../../images/btnGuardar.png" width="85" style="cursor:pointer" onclick="ValidarForm();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        <td>&nbsp;</td>
        <td width="200" align="center"><img id="BtnCancelar" src="../../images/btnCancelar.png" width="85" style="cursor:pointer" onclick="Cancelar();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<script>
    document.form1.Nombres.focus();
    CalendarInit();
</script>
<?php CuerpoInferior();?>