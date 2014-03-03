<?php 
if(!session_id()){ session_start(); }
include("../../config.php");	
include("../../clases/main.php");		
CuerpoSuperior("Mantenimiento de Sistema");	
$Guardar 	= (isset($_GET["Op"]))?$_GET["Op"]:'';
$Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';	
if ($Id != ''){
    $SQL 		= "SELECT * FROM sistemas WHERE idsistema = '$Id'";
    $Consulta	= $Conn->Query($SQL);
    $row		= $Conn->FetchArray($Consulta);		
    $Guardar = "$Guardar&Id2=$Id";
    $Estado = $row[3];
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
function Cancelar(){
    location.href = 'index.php';
}
function ValidarForm(){
    if (document.form1.Descripcion.value == ''){
        alert("La Descripcion del Modulo no puede ser NULA");
        return false;
    }
    if (document.form1.Path.value == ''){
        alert("Para Ingresar un Nuevo Sistema es Necesario que se Ingrese el Path del Mismo");
        return false;
    }
    document.form1.submit();
}
function Sobre(obj){
    obj.style.width=90;
}
function Fuera(obj){
    obj.style.width=85;
}
</script>
<form action="guardar.php?Op=<?php echo $Guardar;?>" method="post" enctype="multipart/form-data" name="form1">
  <table width="509" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="Titulo">Mantenimiento de Sistema</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="75" class="MantTitulo">Código :</td>
      <td width="424" class="MantItem">
        <input type="text" name="1form1_idsistema" id="Codigo" class="inputtext" size="4" style="text-transform:uppercase" readonly="readonly" value="<?php echo (isset($Id))?$Id:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Descripción :</td>
      <td class="MantItem">
        <input name="0form1_descripcion" type="text" class="inputtext" id="Descripcion" style="text-transform:uppercase" size="60" maxlength="60" value="<?php echo (isset($row[1]))?$row[1]:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Path :</td>
      <td class="MantItem"><input type="text" name="0form1_path" id="Path" class="inputtext" value="<?php echo (isset($row[2]))?$row[2]:'';?>" size="60" maxlength="60"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Imgen:</td>
      <td class="MantItem">
        <input name="Imagen" type="file" id="Imagen" size="47" />
      </td>
    </tr>
    <tr>
      <td class="MantTitulo" valign="top">Referencia :</td>
      <td class="MantItem"><div id="Div_Padre">
        <label>
          <textarea name="0form1_referencia" id="Referencia" cols="55" rows="5" class="inputtext" style="text-transform:uppercase"><?php echo isset($row[4])?$row[4]:"";?></textarea>
        </label>
      </div></td>
    </tr>
    <tr>
      <td class="MantTitulo">Estado :</td>
      <td class="MantItem"><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Registro Activo</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr class="Pie">
      <td colspan="2" align="center" ><table width="500" border="0" cellspacing="0" cellpadding="0">
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
    document.form1.Descripcion.focus();
</script>
<?php
CuerpoInferior();
?>