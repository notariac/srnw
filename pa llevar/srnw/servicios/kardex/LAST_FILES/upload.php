<?php
$NombreG = $_GET['Nombre'];
$ruta = "archivos/".$NombreG;
if (move_uploaded_file($HTTP_POST_FILES['qqfile']['tmp_name'], $ruta)){
    echo "{success:true}";
}else{
    echo "{success:false}";
}
?>