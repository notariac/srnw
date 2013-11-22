<?php
//include('../libs/conexiones.php');	
$ConnM = new cConexion();		
$ConnM->SetMotor('postgres');
$ConnM->SetServidor('192.168.10.1');
$ConnM->SetPuerto('5432');
$ConnM->SetUsuario('postgres');
$ConnM->SetPassword('sistemas2010');
$ConnM->SetBaseDatos('notariado');
$ConnM->Conectar();	
$Accion = array("Insertar", "Modificar", "Eliminar", "Restaurar", "Insertado", "Modificado", "Eliminado", "Restaurado");
$Meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
?>