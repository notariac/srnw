<?php
    include('../config.php');
    function addSlash($str)
    {
       $str = str_replace("'",'\"',$str);
       return $str;
    }
    function stripSlash($str)
    {
       $str = str_replace('\"',"'",$str);
       return $str;
    }
    $Id = $_POST['idservicio'];
    $sql = "UPDATE servicio set digital = '".addSlash($_POST['cont'])."' WHERE idservicio = ".$Id;            
    $q = $Conn->Query($sql);
    if($q){ echo "1";}      
	else {echo "Error: ".pg_last_error();}
?>
