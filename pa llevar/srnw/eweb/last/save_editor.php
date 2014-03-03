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
    $Id = $_POST['idarchivo'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $sql = "UPDATE editor.archivos set contenido = '".addSlash($_POST['cont'])."',
                                      fecha_modificacion = '".$fecha."',
                                      hora_modificacion = '".$hora."'
              WHERE idarchivo = ".$Id;    
    $q = $Conn->Query($sql);
    if($q){ echo "1";}      
    else {echo "Error: ".pg_last_error();}
?>
