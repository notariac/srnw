<?php
if(!session_id()){session_start();}
    include('../../config.php');
    include("../../libs/clasemantem.php");
    	
	$oper = 0;
	if(isset($_POST['idcliente'])&&$_POST['idcliente']!="")
	{
		$oper = 1;
		$idcliente = $Conn->vParam($_POST['idcliente'],'NUM');
	}

	$oprofesion="";
	$ocargo="";
	if($_POST['idcliente_tipo']==1)
	{
		if($_POST['idprofesion']==998){$oprofesion = $_POST['otra_profesion'];}
		if($_POST['idcargo']==998){$ocargo = $_POST['otro_cargo'];}	
	}
	elseif($_POST['idcliente_tipo']==2) 
	{
		$_POST['idprofesion'] = 999;$oprofesion="";
		$_POST['idcargo'] = 999; $ocargo = "";		
	}
	//Validaciones
	$bval = $Conn->vParam($_POST['idcliente_tipo'],"NUM");
	$idcliente_tipo = $bval[0];
	$bval = $Conn->vParam($_POST['iddocumento'],"NUM");
	$iddocumento = $bval[0];
	$bval = $Conn->vParam($_POST['dni_ruc'],"TEXT");
	$dni_ruc = $bval[0];

	if($iddocumento==1)
	{ 
		$t = strlen($dni_ruc);
		if($t==8)
		{
			if(!$Conn->isDNI($dni_ruc))
			{
				print_r(json_encode(array('0','DniRuc','DNI: Formato incorrecto'))); die;
			}
		}		
	}
	elseif($iddocumento==8) 
	{
		$t = strlen($dni_ruc);
		if($t==11)
		{
			if(!$Conn->isRUC($dni_ruc))
			{
				print_r(json_encode(array('0','DniRuc','RUC: Formato incorrecto'))); die;
			}
		}		
	}

	//Verificamos si el dni o ruc ya esta en el sistema (Solo si es nuevo)
	if($_POST['idcliente']=="")
	{
		if(trim($dni_ruc)!="")
		{			
			$sql = "SELECT count(*) FROM cliente where dni_ruc = '".trim($dni_ruc)."'";		
		    $q = $Conn->Query($sql);
		    $r = $Conn->FetchArray($q);
		    if($r[0]>0){print_r(json_encode(array('0','DniRuc','El numero de documento ya existe en el sistema '))); die;}    	
		}
	}
	$bval = $Conn->vParam($_POST['RazonNombre2'],"TEXT");
	$RazonNombre2 = $bval[0];
	$bval = $Conn->vParam($_POST['direccion'],"TEXT");
	$direccion = $bval[0];
	$bval = $Conn->vParam($_POST['email'],"EMAIL");
	if(!$bval[1]) {$email = $bval[0];}
		else { print_r(json_encode(array('0','email','email: Formato incorrecto'))); die;}
	$bval = $Conn->vParam($_POST['telefonos'],"TEXT");
	$telefonos = $bval[0];
	$bval = $Conn->vParam($_POST['sexo'],"TEXT");
	$sexo = $bval[0];
	$bval = $Conn->vParam($_POST['idestado_civil'],"TEXT");
	$idestado_civil = $bval[0];
	$bval = $Conn->vParam($_POST['nacionalidad'],"NUM");
	$nacionalidad = $bval[0];
	$bval = $Conn->vParam($_POST['pais'],"TEXT");
	$pais = $bval[0];
	$bval = $Conn->vParam($_POST['idprofesion'],"NUM");
	$idprofesion = $bval[0];
	$bval = $Conn->vParam($_POST['IdDistrito'],"TEXT");
	$IdDistrito = $bval[0];
	$bval = $Conn->vParam($_POST['estado'],"NUM");
	$estado = $bval[0];		
	$bval = $Conn->vParam($_POST['partida'],"TEXT");
	$partida = $bval[0];
	$bval = $Conn->vParam($_POST['asiento'],"TEXT");
	$asiento = $bval[0];
	$bval = $Conn->vParam($_POST['ap_paterno'],"TEXT");	
	$ap_paterno = $bval[0];
	$bval = $Conn->vParam($_POST['ap_materno'],"TEXT");
	$ap_materno = $bval[0];
	$bval = $Conn->vParam($_POST['oprofesion'],"TEXT");
	$oprofesion = $bval[0];
	$bval = $Conn->vParam($_POST['idcargo'],"NUM");
	$idcargo = $bval[0];
	$bval = $Conn->vParam($_POST['otro_cargo'],"TEXT");
	$otro_cargo = $bval[0];
		

	switch ($oper) {
		case 0:
			$sql = "SELECT descripcion from documento where iddocumento = ".$iddocumento;
			$q = $Conn->Query($sql);
			$row  = $Conn->FetchArray($q);
			$documento_name = $row[0];

			$sql = "INSERT INTO cliente(idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, 
							            email, telefonos, sexo, idestado_civil, nacionalidad, pais, idprofesion, 
							            idubigeo, idcliente_representante, representante_cargo, estado, 
							            idusuario, fechareg, partida, asiento, idanterior, ape_paterno, 
							            ap_materno, otra_profesion, idcargo, otro_cargo, fecha_nac
							            )
							    VALUES ({$idcliente_tipo}, {$iddocumento}, '{$dni_ruc}', '{$RazonNombre2}', '{$direccion}', 
							            '{$email}', '{$telefonos}', '{$sexo}', {$idestado_civil}, 
							            {$nacionalidad}, 
							            '{$pais}', 
							            {$idprofesion}, 
							            '{$IdDistrito}', 0, '', {$estado}, 
							            {$_SESSION['id_user']}, 
							            '".date('Y-m-d')."', 
							            '{$partida}', 
							            '{$asiento}', '', 
							            '{$ap_paterno}', 
							            '{$ap_materno}', 
							            '{$oprofesion}', {$idcargo}, '{$otro_cargo}', '".$Conn->CodFecha($_POST['fechanac'])."') RETURNING idcliente";			
			$q = $Conn->Query($sql);			
			if($q) {
						$row  = $Conn->FetchArray($q);
						$idcliente = $row[0];
						$n = count($_POST['dDocRepresentante']);
						if($n>0&&$idcliente_tipo==2)
						{
							//Insercion de Representantes
							foreach($_POST['dDocRepresentante'] as $i => $r)
							{
								$sql = "INSERT INTO cliente_representante
											(ruc_cliente, dni_representante, cargo, idcliente, idrepresentante)
									 VALUES ('{$dni_ruc}', '{$_POST['dDocRepresentante'][$i]}', '{$_POST['dCargo'][$i]}', {$idcliente},{$_POST['dIdRepresentante'][$i]});";								
								$Conn->Query($sql);
							}
						}
						print_r(json_encode(array('1','','Se ha registrado correctamente',$dni_ruc,$RazonNombre2.' '.$ap_paterno.' '.$ap_materno,$direccion,$idcliente,$documento_name))); 
				    }
				else { print_r(json_encode(array('0','','Ha ocurrido un error')));	}
			break;
		case 1:
				$sql = "UPDATE cliente
					   	SET idcliente_tipo={$idcliente_tipo}, 
					   		iddocumento={$iddocumento}, 
					   		dni_ruc='{$dni_ruc}', 
					   		nombres='{$RazonNombre2}', 
					       	direccion='{$direccion}', 
					       	email='{$email}', 
					       	telefonos='{$telefonos}', 
					       	sexo='{$sexo}', 
					       	idestado_civil={$idestado_civil}, 
					       	nacionalidad={$nacionalidad}, 
					       	pais='{$pais}', 
					       	idprofesion={$idprofesion}, 
					       	idubigeo='{$IdDistrito}', 
					       	idcliente_representante=0, 
					       	representante_cargo='', 
					       	estado={$estado}, 
					       	idusuario={$_SESSION['id_user']}, 
					       	fechareg='".date('Y-m-d')."', 
					       	partida='{$partida}', 
					       	asiento='{$asiento}', 
					       	idanterior='', 
					       	ape_paterno='{$ap_paterno}', 
					       	ap_materno='{$ap_materno}', 
					       	otra_profesion='{$oprofesion}', 
					       	idcargo={$idcargo}, 
					       	otro_cargo='{$otro_cargo}', 
					       	fecha_nac='".$Conn->CodFecha($_POST['fechanac'])."'
					 WHERE idcliente = {$_POST['idcliente']}";
					 
				$q = $Conn->Query($sql);			
				if($q) 
				{	 
					$sql = "DELETE FROM cliente_representante WHERE idcliente =  {$_POST['idcliente']}";

					$q = $Conn->Query($sql);
					if($q) 
					{ 
						$n = count($_POST['dDocRepresentante']);
						if($n>0&&$idcliente_tipo==2)
						{
							//Insercion de Representantes
							foreach($_POST['dDocRepresentante'] as $i => $r)
							{
								$sql = "INSERT INTO cliente_representante
											(ruc_cliente, dni_representante, cargo, idcliente, idrepresentante)
									 VALUES ('{$dni_ruc}', '{$_POST['dDocRepresentante'][$i]}', '{$_POST['dCargo'][$i]}', {$idcliente},{$_POST['dIdRepresentante'][$i]});";								
								//echo $sql;	 
								$Conn->Query($sql);
							}
						}
						print_r(json_encode(array('1'.'','Se ha registrado correctamente'))); 
					}

				}
				else { print_r(json_encode(array('0','','Ha ocurrido un error')));	}
				break;	
		default:
			# code...
			break;
	}


?>    
