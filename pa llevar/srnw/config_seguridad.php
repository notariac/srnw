<?php	
    $ConnS = new cConexion();		
    $ConnS->SetMotor('postgres');
    $ConnS->SetServidor('192.168.10.1');
    $ConnS->SetPuerto('5432');
    $ConnS->SetUsuario('postgres');
    $ConnS->SetPassword('sistemas2010');
    $ConnS->SetBaseDatos('seguridad2');
    $ConnS->Conectar();
?>