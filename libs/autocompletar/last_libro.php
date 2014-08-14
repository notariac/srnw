<?php 
	require("../../config.php");
	$tomo1 = '0';
	$tomo2 = '0';
	if(trim($_GET['idcli'])!="")
	{
		//Verficamos si existe historial de libros para
		//esta empresa, pero filtrando por idcliente
		$sql = "SELECT numero
				from libro
				where idcliente = '".$_GET['idcli']."' and idlibro_tipo=".$_GET['idtl']."
				order by numero desc 
				limit 1";		
		$Consulta = $Conn->Query($sql);
	    while($row = $Conn->FetchArray($Consulta))
	    {
	        $tomo1 = $row['numero'];
	    }
	    
	    if($tomo1=='0')
	    {
	    	//Verificamos si existe historial de libros para
	    	//esta empresa, pero filtrando por razonsocial
		    $sql = "SELECT numero
					from libro
					where rtrim(ltrim(razonsocial)) ilike '%".trim($_GET['rz'])."%' and idlibro_tipo=".$_GET['idtl']."
							and idcliente = 0
					order by numero desc 
					limit 1";		
			$Consulta = $Conn->Query($sql);	
		    while($row = $Conn->FetchArray($Consulta))
		    {
		        $tomo2 = $row['numero'];
		    }
		    
		    echo $tomo2;
	    }
	    else
	    {
	    	echo $tomo1;
	    }

	}
	else
	{
		echo $tomo1;
	}
	
?>