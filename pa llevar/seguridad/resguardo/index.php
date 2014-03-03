<? 
	include("../clases/main.php");
	
	CuerpoSuperior(utf8_encode("Resguardo de Información - Generar BackUp"));
?>

<?
	function hacer_resguardo($path, $etapa)
	{
  		$host		= "192.168.10.104";
  		$usuario	= "postgres";
  		$password	= "sistemas2010";
  		$nombre_bd	= "notariado";
  		$archivo 	= "prueba.bak";
  		$comando 	= "pg_dump -u ".$usuario." -d ".$nombre_bd." > /opt/lampp/htdocs/seguridad/resguardo/".$archivo;
		print "$comando";
  		$salida		= shell_exec($comando);
  		echo $salida;
  		if ($salida)
		{
    		$jr_error = error_get_last();
  		}
	}
	
	function ping($ip) 
	{ 
		$Puerto = array(445, 631, 5900);
		$Contador = 0;
		foreach ($Puerto as $value) 
		{
			$estado = "";
			$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 
			socket_set_nonblock($sock);
			@socket_connect($sock, $ip, $value); 
			socket_set_block($sock);
			switch(socket_select($r = array($sock), $w = array($sock), $f = array($sock), 5)) 
			{ 
				case 0: 
					$estado = "TimeOut"; 
					break;
				case 1: 
					$estado = "On"; 
					break;
				case 2: 
					$estado = "Off"; 
					break; 
				default:
					$estado = "";
					break;
			}
			if ($estado=="On")
			{
				$Contador = $Contador + 1;
				echo $Resultado = $ip.":".$value." = ".$estado."<br>";
				break;
			}
		}
		if ($Contador==0)
		{
			echo $Resultado = $ip.":".$value." = Off"."<br>";
		}
	}  
	//hacer_resguardo($path, $etapa);
	echo $salida = ping('192.168.10.116');
	echo $salida = ping('192.168.10.222');
	
	CuerpoInferior() 
?>