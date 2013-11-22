<?php
include("../../config.php");	
$IdKardex = (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';	
$SQL 		= "SELECT correlativo FROM kardex WHERE idkardex='$IdKardex'";
$Consulta 	= $Conn->Query($SQL);
$row 		= $Conn->FetchArray($Consulta);
?>
<script>
    document.location.href = "../../servicios/tomafirmas/partes/<?php echo $row[0];?>Parte.doc";
</script>