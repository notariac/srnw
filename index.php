<?php 
if( !session_id() ){ session_start(); }
    include("libs/masterpage.php");
    include("config.php");
    $Sql = "SELECT activo, idubigeo, anio FROM notaria WHERE idnotaria='".$_SESSION["notaria"]."'";
    $Consulta = $Conn->Query($Sql);
    $row = $Conn->FetchArray($Consulta);
    $_SESSION["Anio"] = $row[2];
    $_SESSION["Ubigeo"] = $row[1];
    if ($row[0]==0)	
    {
            echo "<script>window.location='http://".$_SERVER['HTTP_HOST']."/srnw/parametros/opciones/index.php?Activo=0';</script>";
    }		    
    CuerpoSuperior("Sistema Informático de Registro Notarial");
    CuerpoInferior(); 
?>