<?php 
	include("../../../config.php");	
	$IdKardex 	= (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';
	$IdParticipante = (isset($_GET["IdParticipante"]))?$_GET["IdParticipante"]:'';	
	$Sql            = "SELECT kardex_participantes.foto, kardex.correlativo, cliente.nombres FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) INNER JOIN kardex ON (kardex_participantes.idkardex = kardex.idkardex) WHERE kardex_participantes.idkardex='$IdKardex' AND kardex_participantes.idparticipante='$IdParticipante'";
	$Consulta 	= $Conn->Query($Sql);
	$row		=  $Conn->FetchArray($Consulta);
	echo $Foto = "fotos/".$row[0];
?>
<center>
  <table border="0" cellspacing="0" style="width:595.3px; height:841.9px">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>N&ordm; de Kardex : <?php echo $row[1];?></td>
    </tr>
    <tr>
      <td align="center"><img src="<?php echo $Foto;?>" width="500"></td>
    </tr>
    <tr>
      <td align="center"><?php echo $row[2];?>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>