<?php 
    require_once("maxUpload.class.php");
    $IdKardex 	= (isset($_GET["IdKardex"]))?$_GET["IdKardex"]:'';
    $IdDependencia = (isset($_GET["IdDependencia"]))?$_GET["IdDependencia"]:'';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Ingrese la Foto del Participante</title>
   <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
    $myUpload = new maxUpload();
    $myUpload->uploadFile($IdKardex, $IdDependencia);
?>
</body>