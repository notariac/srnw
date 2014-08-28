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

    $c = ($_POST['cont']);

    $c = str_replace('“', '"', $c);
    $c = str_replace('”', '"', $c);

    $sql = "UPDATE kardex set digital = '".addSlash($c)."' WHERE idkardex = ".$Id;            

    $q = $Conn->Query($sql);
    if($q){ echo "1";}      
	  else {echo "Error: ".pg_last_error();}
?>