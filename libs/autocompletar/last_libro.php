<?php 
	require("../../config.php");
	if(trim($_GET['ruc'])!="")
	{

		$sql = "SELECT numero
				from libro
				where ruc = '".$_GET['ruc']."' and idlibro_tipo=".$_GET['idtl']."
				order by numero desc 
				limit 1";
		
		$Consulta = $Conn->Query($sql);
	    $tomo = 0;
	    while($row = $Conn->FetchArray($Consulta))
	    {
	        $tomo = $row['numero'];
	    }
	    echo $tomo;
	}
	else
	{
		echo 0;
	}
	
?>