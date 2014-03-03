<?
	include('../../config.php');
	
	$Op = $_POST["Op"];
	$Id = isset($_POST["Id"])?$_POST["Id"]:'';
	
	$Enabled	= "";
	$Enabled2	= "";
	$Guardar	= "";
	$Estado = "1";
	if($Op==2 or $Op==3)
	{
		$Enabled = "readonly";
		$Guardar = "Op=".$Op;
	}
	else
	{
		if($Op==0 or $Op==1)
		{
			$Guardar = "Op=".$Op;
		}
	}

	//if($Op!=0)
//	{
		$Enabled2 = "readonly";
	//}
	
	if($Id!='')
	{
		$Select 	= "SELECT * FROM estado_civil WHERE idestado_civil = ".$Id;
		$Consulta 	= $Conn->Query($Select);
		$row 		= $Conn->FetchArray($Consulta);
		
		$Estado = $row[2];
		$Guardar = $Guardar."&Id2=".$Id;
	}
$ArrayP = array(NULL);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/Funciones.js"></script>

<script>
	function CambiaEstado()
	{
		if (document.getElementById('Estado2').checked)
		{
			$('#Estado').val(1);
		}
		else
		{
			$('#Estado').val(0);
		}
	}
	
	function Cancelar()
	{
	 	window.location.href='index.php';
	}
	
	function ValidarFormEnt(evt)
	{
		var keyPressed = (evt.which) ? evt.which : event.keyCode
		if (keyPressed==13)
		{
			 Guardar(<?=$Op?>);
		}
	}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?=$Guardar?>">
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">C&oacute;digo :</td>
    <td><input type="text" class="inputtext" style="text-align:center; width:50px" name="1form1_idestado_civil" id="Id" maxlength="2" value="<?=$row[0]?>" <?=$Enabled2?> onkeypress="CambiarFoco(this, 'Descripcion')"/>
    </td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Descripci&oacute;n :</td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_descripcion" id="Descripcion"  maxlength="100" value="<?=$row[1]?>" <?=$Enabled?> onkeypress="ValidarFormEnt(event)"/></td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Estado :</td>
    <td><input type="checkbox" name="Estado2" id="Estado2" <? if ($Estado==1) echo "checked='checked'"?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?=$Estado?>" /> Activo</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
