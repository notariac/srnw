<?php 
    $Titulo 	= $_POST["Titulo"];
    $Mensaje	= $_POST["Mensaje"];
?>
<table width="450" border="0" cellspacing="0" bgcolor="#FFFF9D" class="tbmensaje">
  <tr>
    <td colspan="2" align="center" style="font-size:18px; color:#000; text-decoration:underline; font-weight:bold" bgcolor="#666666"><?php echo $Titulo;?></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td valign="top" style="font-size:14px; color:#F00; font-weight:bold">&nbsp;</td>
  </tr>
  <tr>
    <td width="146" align="center"><img src="http://localhost/seguridad/images/Cancelar.png" width="50" height="50" /></td>
    <td width="444" valign="top" style="font-size:12px; color:#F00; font-weight:bold"><?php echo $Mensaje;?></td>
  </tr>
  <tr>
    <td colspan="2" style="height:10px" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="btnaceptar" type="button" value="Aceptar" onClick="OcultarCapa();" class="button"></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>