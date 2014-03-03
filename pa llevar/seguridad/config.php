<?php	
    require('clases/conexiones.php');	
    $Conn = new cConexion();		
    $Conn->SetMotor('postgres');
    $Conn->SetServidor('192.168.10.1');
    $Conn->SetPuerto('5432');
    $Conn->SetUsuario('postgres');
    $Conn->SetPassword('sistemas2010');
    $Conn->SetBaseDatos('seguridad2');	
    $Conn->Conectar();	
    $Accion = array("Insertar", "Modificar", "Eliminar", "Insertado", "Modificado", "Eliminado");
?>