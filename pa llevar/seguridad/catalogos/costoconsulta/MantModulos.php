<?php
    if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/clasemantem.php");
    CuerpoSuperior("Mantenimiento de Costo por Consulta");
    $Sql = "UPDATE consulta SET costo='".$_POST['valor1']."', costo2='".$_POST['valor2']."' ";
    $Consulta = $Conn->Query($Sql);
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res     = 2;
        $Mensaje = "Error al intentar modificar los costos de la consulta";
    }else{
        $Conn->TerminarTransaccion("COMMIT");
        $Res     = 1;
        $Mensaje = "Registro modificado Correctamente";
    }
    CuerpoInferior();
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
    setTimeout("location.href='index.php';", 1000);
</script>