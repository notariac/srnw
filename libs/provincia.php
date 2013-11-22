<?php
if(!session_id()){ session_start(); }	
    include('../config.php');
    $IdUbigeo = $_POST["IdUbigeo"];
    $IdDep = $_POST["IdDep"];
    if ($IdUbigeo!=''){
        $Id = substr($IdUbigeo,0,4);
    }else{
        $Id = substr($_SESSION["Ubigeo"], 0, 4);
    }
    $Id2 = substr($IdDep,0,2);	
    $Enab = "";
    $SelectP = "Select * from ubigeo where idubigeo like '".$Id2."%00' AND idubigeo <> '".$Id2."0000' order by idubigeo asc";
    $ConsultaP = $Conn->Query($SelectP);
?>

    <?php
        while($rowP=$Conn->FetchArray($ConsultaP)){
            $Selec="";
            if($Id==substr($rowP[0],0,4)){
                $Selec="selected='selected'";
            }
    ?>
        <option value="<?php echo $rowP[0];?>" <?php echo $Selec;?>><?php echo $rowP[1];?></option>
    <?php
        }
    ?>
