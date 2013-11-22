<?php
include("../../config.php");	
$IdKardex = (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';	
$SQL      = "SELECT archivo FROM kardex WHERE idkardex='$IdKardex'";
$Consulta = $Conn->Query($SQL);
$row      = $Conn->FetchArray($Consulta);
?>
<script>
    document.location.href = "archivos/<?php echo $row[0];?>";
</script>