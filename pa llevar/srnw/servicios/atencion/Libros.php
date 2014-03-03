<?php
    if(!session_id()){ session_start(); }
?>
<select name="NroKardex" id="NroKardex" class="select" onchange="Tab('Precio');" >
	<option value="">Ninguno</option>
<?php
	include('../../config.php');
	$Anio = $_POST['Anio'];	
	$SelectL = "SELECT L.correlativo FROM libro L WHERE L.anio='2011' AND L.estado=3 AND idnotaria='".$_SESSION["notaria"]."' AND NOT EXISTS(SELECT correlativo FROM libro WHERE idnotaria = '".$_SESSION["notaria"]."' AND correlativo=L.correlativo AND anio = '$Anio' AND estado <= 1) GROUP BY L.correlativo ";
	$ConsultaL = $Conn->Query($SelectL);
	while($rowL=$Conn->FetchArray($ConsultaL)){
?>
	  <option value="<?php echo $rowL[0];?>"><?php echo $rowL[0];?></option>
<?php
	}
?>
</select>