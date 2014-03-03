<?php
if(!session_id()){ session_start(); }
    include('../config.php');
    $ndoc = $_GET["ndoc"];    
    $sql = "SELECT count(*) FROM cliente where dni_ruc = '".trim($ndoc)."'";
    $q = $Conn->Query($sql);
    $r = $Conn->FetchArray($q);
    if($r[0]>0){echo "1";}
    	else {echo "0";}
?>