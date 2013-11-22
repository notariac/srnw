<?php	
    require('libs/conexiones.php');
    $Conn = new cConexion();		
	//Test de vi
    $Conn->SetMotor('postgres');
    $Conn->SetServidor('192.168.10.1');
    $Conn->SetPuerto('5432');
    $Conn->SetUsuario('postgres');
    $Conn->SetPassword('sistemas2010');
    $Conn->SetBaseDatos('srnw_migrando');
    $Conn->Conectar();	
    $Accion = array("Insertar", "Modificar", "Eliminar", "Restaurar", "Insertado", "Modificado", "Eliminado", "Restaurado");
    $Meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
?>