<?php
if(!session_id()){ session_start(); }	
    include('../config.php');
    $IdUbigeo = $_POST["IdUbigeo"];
    $IdProv = $_POST["IdProv"];	
    $Prefi = isset($_GET['Prefi'])?$_GET['Prefi']:'form1';	
    if ($IdUbigeo!=''){
        $Id = $IdUbigeo;
    }else{
        $Id = $_SESSION["Ubigeo"];
    }
    $Id2 = substr($IdProv,0,4);	
    $Enab = "";
    $SelectD = "Select * from ubigeo where idubigeo like '$Id2%' AND SUBSTRING(idubigeo,5,2)  <>  '00' order by idubigeo asc";
    $ConsultaD = $Conn->Query($SelectD);

while($rowD=$Conn->FetchArray($ConsultaD)){
$Selec="";
    if($Id==$rowD[0]){
        $Selec="selected='selected'";
    }
?>
    <option value="<?php echo $rowD[0];?>" <?php echo $Selec;?>><?php echo $rowD[1];?></option>
<?php
}
?>