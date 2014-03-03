<?php
if(!session_id()){ session_start(); }	
    include('../config.php');
    $IdUbigeo = $_POST["IdUbigeo"];
    if ($IdUbigeo!=''){
        $Id = substr($IdUbigeo, 0, 2);
    }else{
        $Id = substr($_SESSION["Ubigeo"], 0, 2);
    }
    $Enab = "";
    $SelectU = "SELECT * FROM ubigeo WHERE idubigeo LIKE '%0000' ORDER BY idubigeo ASC";
    $ConsultaU = $Conn->Query($SelectU);
?>

<?php
while($rowU=$Conn->FetchArray($ConsultaU)){
$Selec="";
    if($Id==substr($rowU[0], 0, 2)){
        $Selec="selected='selected'";
    }
?>
    <option value="<?php echo $rowU[0];?>" <?php echo $Selec;?>><?php echo $rowU[1];?></option>
<?php
}
?>