<?php
error_reporting(E_ALL);
if(!session_id()){session_start();}
include('../../config.php');
include_once '../../libs/funciones.php';
switch ($_REQUEST['tipo_generar']){
    case 'A':include_once 'actos_juridicos.php';
        break;
    case 'B':include_once 'bienes.php';
        break;
    case 'O':include_once 'otorgantes.php';
        break;
    default :die("La Opcion que Intenta Generar no es v&acute;lida");break;
}
function moneda_equi($idmoneda){
    $array=array(
        "2"=>"1",//Equivalencia de Codigo de Dolares
        "3"=>"2"//Equivalencia de Codigo de Soles
        );
    return $array[$idmoneda];
}
?>
