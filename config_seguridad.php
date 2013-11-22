<?php	
    $ConnS = new cConexion();		
    $ConnS->SetMotor('postgres');
    $ConnS->SetServidor('localhost');
    $ConnS->SetPuerto('5432');
    $ConnS->SetUsuario('postgres');
    $ConnS->SetPassword('12345678');
    $ConnS->SetBaseDatos('seguridad2');
    $ConnS->Conectar();
?>