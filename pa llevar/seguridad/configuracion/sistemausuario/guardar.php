<?
	include('../../config.php');
        include("../../clases/main.php");

	include("../../clases/clasemantem.php");

        CuerpoSuperior("Mantenimiento de Sistemas por Usuario");

	$Op     = $_GET["Op"];
        $Id     = $_GET["Id2"];
	$Res    = 1;
	$Mensaje = "Registro ".$Accion[$Op + 3]." Correctamente";

        $Cont	= $_POST["Cont"];
        $SQLDelete = "DELETE FROM usuario_sistemas WHERE idusuario = '$Id'";
	$result = $Conn->Query($SQLDelete);
	if (!$result) {die("Error in SQL query: ");}

        $mantem = new dbMantimiento($Conn->GetConexion());
        for ($i=1; $i<=$Cont; $i+= 1)
	{
            if (isset($_POST["0formd".$i."_idperfil"]))
            {
                $Sql = $mantem->__dbMantenimiento($_POST, "formd".$i, "usuario_sistemas", 0);	//Se genera la sentencia SQL de acuerdo a la operación
                $Conn->NuevaTransaccion();
                $Consulta = $Conn->Query($Sql);
            }

	}

	if (!$Consulta)
	{
		$Conn->TerminarTransaccion("ROLLBACK");
		$Res=2;
		$Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Familia: ";
	}
	else
	{
		$Conn->TerminarTransaccion("COMMIT");
		$Res=1;
		$Mensaje ="Registro ".$Accion[$Op + 3]." Correctamente";
	}

        CuerpoInferior();
?>
<script>
	OperMensaje('<?=$Mensaje?>',<?=$Res?>);
        setTimeout("location.href='index.php';",1000);
</script>