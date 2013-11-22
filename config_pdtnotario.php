<?php	
    $ConnN = new cConexion();		
    $ConnN->SetMotor('postgres');
    $ConnN->SetServidor('localhost');
    $ConnN->SetPuerto('5432');
    $ConnN->SetUsuario('postgres');
    $ConnN->SetPassword('ericson');
    $ConnN->SetBaseDatos('pdt_notario');
    $ConnN->Conectar();
?>