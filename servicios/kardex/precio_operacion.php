<?php 
include("../../config.php"); 
include_once '../../libs/funciones.php';

$SQL = "SELECT idkardex,idmoneda,monto
		FROM kardex_aj where idkardex = ".$_GET['idk']." and idacto_juridico = ".$_GET['idaj'];
$Consulta 	= $Conn->Query($SQL);
$row 		= $Conn->FetchArray($Consulta);


?>
<label for="idmoneda_aj">&nbsp;&nbsp;&nbsp;Moneda: </label>                                
<select name="idmoneda_aj" id="idmoneda_aj">
    <?php 
    	echo opt_combo("select * from moneda", $row['idmoneda'], $Conn);
    ?>
</select>                                  
<label for="PrecioOperacion" title="Precio de la operacion">&nbsp;&nbsp;&nbsp;Precio Oper.: </label>
<input type="text" align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="PrecioOperacion" id="PrecioOperacion" value="<?php if($row['monto']!="") echo number_format($row['monto'],2); else echo "0.00"; ?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FormaPago'); return permite(event, 'num');"/>