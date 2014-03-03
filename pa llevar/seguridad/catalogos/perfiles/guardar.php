<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/clasemantem.php");
    CuerpoSuperior("Mantenimiento de Perfil");
    $Op         = $_GET["Op"];
    $Res        = 1;
    $Mensaje    = "Registro ".$Accion[$Op + 3]." Correctamente";
    $mantem = new dbMantimiento($Conn->GetConexion());
    $Sql = $mantem->__dbMantenimiento($_POST, "form1", "perfiles", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
    $Conn->NuevaTransaccion();
    $Consulta = $Conn->Query($Sql);
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Familia: ";
    }else{
        $Conn->TerminarTransaccion("COMMIT");
        $Res=1;
        $Mensaje ="Registro ".$Accion[$Op + 3]." Correctamente";
    }
    CuerpoInferior();
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
    setTimeout("location.href='index.php';", 1000);
</script>