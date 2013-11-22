<?php 
	if(!session_id()){ session_start(); }
    include('../config.php');
	$op = $_POST['oper'];
	switch ($op) {
		case 1:
			$sql = "INSERT INTO editor.carpeta (idpadre,nombre) values (".$_POST['idpadre'].",'".$_POST['name']."')";			
			$Conn->Query($sql);
			echo "Ok";
			break;
		case 2:
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$sql = "INSERT INTO editor.archivos(idcarpeta, nombre, fecha, hora, fecha_modificacion, 
							hora_modificacion, contenido, estado)
					VALUES (".$_POST['idfolder'].", '".$_POST['name']."', '".$fecha."', '".$hora."', '".$fecha."', '".$hora."', '', 1);";			
			$Conn->Query($sql);
			echo "Ok";
			break;	
		default:
			# code...

			break;
	}
?>