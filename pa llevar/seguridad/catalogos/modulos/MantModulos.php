<?php
if(!session_id()){ session_start(); }
    include("../../config.php");
    include("../../clases/main.php");
    CuerpoSuperior("Mantenimiento de M&oacutedulos");
    $Guardar            = (isset($_GET["Op"]))?$_GET["Op"]:'';
    $Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';
    $Estado = 0;
    if($Id != '')
    {
        $SQL 	= "SELECT * FROM modulos WHERE idmodulo = '$Id'";
        $Consulta	= $Conn->Query($SQL);
        $row		= $Conn->FetchArray($Consulta);
        $Guardar        = "$Guardar&Id2=$Id";
        $Estado         = $row[7];
    }
?>
<script>
function CambiaEstado(){
    if (document.getElementById('Estado2').checked)
    {
        document.getElementById('Estado').value = 1;
    }
    else
    {
        document.getElementById('Estado').value = 0;
    }
}        
function VerModulos(IdModulo){
    var IdSistema = $('#IdSistema').val();
    $("#Div_Padre").append("<center><img src='../../images/avance.gif' /></center>");
    $.ajax({
        url:'../../clases/modulos.php',
        type:'POST',
        async:true,
        data:'IdSistema=' + IdSistema + '&IdModulo=' + IdModulo,
        success:function(data){$("#Div_Padre").html(data);}
     });
}        
function Cancelar(){
    location.href = 'index.php';
}
function ValidarForm(){
    if (document.form1.Descripcion.value == ''){
        alert("La Descripci&oacute;n del Perfil no puede ser NULA");
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
      <td colspan="2" class="Titulo">Mantenimiento de M&oacute;dulos del Sistema</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="75" class="MantTitulo">Código :</td>
      <td width="424" class="MantItem">
        <input type="text" name="1form1_idmodulo" id="Codigo" class="inputtext" size="4" style="text-transform:uppercase" readonly="readonly" value="<?=(isset($Id))?$Id:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Sistema :</td>
      <td class="MantItem">
        <select name="2form1_idsistema" id="IdSistema" style="width:200px" class="select" onchange="VerModulos();">
        	<option value="0">--Seleccione el Sistema--</option>
            <?php
                    $SQL1 	= "SELECT * FROM sistemas WHERE estado=1";
                    $Consulta1	= $Conn->Query($SQL1);
                    while($row1	= $Conn->FetchArray($Consulta1)){
                        $Selected="";
                        if($row[1]==$row1[0]){
                                $Selected="selected='selected'";
                        }
            ?>
            	<option value="<?php echo $row1[0];?>" <?php echo $Selected;?>><?php echo $row1[1];?></option>
            <?php   }   ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Descripción :</td>
      <td class="MantItem">
        <input name="0form1_descripcion" type="text" class="inputtext" id="Descripcion" style="" size="60" maxlength="60" value="<?php echo (isset($row[2]))?$row[2]:'';?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Url :</td>
      <td class="MantItem"><input type="text" name="0form1_url" id="Url" class="inputtext" value="<?php echo (isset($row[3]))?$row[3]:'';?>" size="60" maxlength="60"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Imgen :</td>
      <td class="MantItem">
        <input name="Imagen" type="file" id="Imagen" size="47" />
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Padre :</td>
      <td class="MantItem"><div id="Div_Padre"><select name="0form1_idpadre" id="IdPadre" style="width:200px" class="select">
      	  	<option value="0">--Seleccione el Padre--</option>
	      </select></div></td>
    </tr>
    <tr>
      <td class="MantTitulo">Orden :</td>
      <td class="MantItem"><input type="text" name="0form1_orden" id="Orden" class="inputtext" size="2" style="text-transform:uppercase" maxlength="20" value="<?php echo (isset($row[5]))?$row[5]:'';?>"/></td>
    </tr>
    <tr>
      <td class="MantTitulo">Estado :</td>
      <td class="MantItem"><input type="checkbox" name="Estado2" id="Estado2" <?php if($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Registro Activo</td>
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
document.form1.Descripcion.focus()
IdSistema 	= document.getElementById("IdSistema").value;
IdModulo	= <?php echo isset($row[4])?$row[4]:'""';?>;
VerModulos(IdModulo);
</script>
<?php CuerpoInferior();?>