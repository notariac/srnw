<?php
include('../libs/num2letra.php');	
$Importe = $_POST['Importe'];
echo CantidadEnLetra($Importe);
?>