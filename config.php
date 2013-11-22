<?php	
    require('libs/conexiones.php');
    $Conn = new cConexion();		
    $Conn->SetMotor('postgres');
    $Conn->SetServidor('localhost');
    $Conn->SetPuerto('5432');
    $Conn->SetUsuario('postgres');
    $Conn->SetPassword('12345678');
    $Conn->SetBaseDatos('srnw');
    $Conn->Conectar();	
    $Accion = array("Insertar", "Modificar", "Eliminar", "Restaurar", "Insertado", "Modificado", "Eliminado", "Restaurado");
    $Meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
?>