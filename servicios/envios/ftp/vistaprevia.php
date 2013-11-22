<?php 
	include("../../../config.php");	
	$IdKardex 	= (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';
	$IdDependencia  = (isset($_GET["IdDependencia"]))?$_GET["IdDependencia"]:'';	
	$Sql 		= "SELECT imagen FROM kardex_derivacion WHERE idkardex='$IdKardex' AND iddependencia='$IdDependencia'";
	$Consulta 	= $Conn->Query($Sql);
	$row		= $Conn->FetchArray($Consulta);
	$Foto = "../imagenes/".$row[0];
?>
<center>
  <table width="300" border="0" cellspacing="0">
    <tr>
      <td><img src="<?php echo $Foto;?>" width="300"></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>