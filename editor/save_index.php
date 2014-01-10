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
    $Id = $_POST['idkardex'];

    $sql = "UPDATE kardex set digital = '".addSlash($_POST['cont'])."' WHERE idkardex = ".$Id;            

    $q = $Conn->Query($sql);
    if($q){ echo "1";}      
	  else {echo "Error: ".pg_last_error();}
?>