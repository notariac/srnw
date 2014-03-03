<?php 
if(!session_id()){ session_start(); }
	include("../../config.php");
	include("../../clases/main.php");
	include("../../clases/clasemantem.php");
        CuerpoSuperior("Mantenimiento de Acceso por P&eacute;rfil");	
	$Op 	= $_GET["Op"];
	$Id 	= $_GET["Id2"];
	$Cont	= $_POST["Cont"];
	$IdSistema = $_GET["IdSistema"];	
	$Res    = 1;
	$Mensaje = "Registro ".$Accion[$Op + 3]." Correctamente";	
	$SQLDelete = "delete from modulos_perfil where idsistema = ".$IdSistema." and idperfil=".$Id;
	$result = $Conn->Query($SQLDelete);
	if (!$result){
            $Res=2;
            $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Familia: ";
	}	
	for ($i=1; $i<=$Cont; $i+= 1){
            $Check 		= $_POST["ChkPadre".$i];
            $IdModulo 	= $_POST["Padre".$i];
            if(isset($_POST["ChkPadre".$i])){
                if($Check=='on'){
                    $SQL  = "insert into modulos_perfil(idmodulo,idsistema,idperfil) values($IdModulo, $IdSistema, $Id)";
                    $result = $Conn->Query($SQL);
                    if (!$result){
                        $Res=2;
                        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Familia: ";
                    }
                }
            }
	}
	CuerpoInferior();
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
    setTimeout("location.href='index.php';",1000);
</script>	