<?php
// Crear una nueva instancia de imagen
$im = imagecreatetruecolor(50, 20);
$negro = imagecolorallocate($im, 0, 0, 0);
$blanco = imagecolorallocate($im, 255, 255, 255);

// Hacer el fondo blanco
imagefilledrectangle($im, 0, 0, 49, 19, $blanco);

// Cargar la fuente gd y escribir 'Hola'
$fuente = imageloadfont('./04b.gdf');
imagestring($im, $fuente, 0, 0, 'Hola', $negro);

// Imprimir al navegador
header('Content-type: image/png');

imagepng($im);
imagedestroy($im);
?>