<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/clasemantem.php");
    CuerpoSuperior("Mantenimiento de Usuario");
    $Op         = $_GET["Op"];
    $id         = $_GET["Id2"];
    $Res        = 1;
    $Mensaje    = "Registro ".$Accion[$Op + 3]." Correctamente";
    $mantem     = new dbMantimiento($Conn->GetConexion());
    $Sql = $mantem->__dbMantenimiento($_POST, "form1", "usuario", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
    $Conn->NuevaTransaccion();
    $Consulta   = $Conn->Query($Sql);  
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res = 2;
        $Mensaje = "Error al intentar ".$Accion[$Op]." los datos del Usuario: ";
    }else{
        $Conn->TerminarTransaccion("COMMIT");
        $Res = 1;
        $Mensaje = "Registro ".$Accion[$Op + 3]." Correctamente";
    }
    include('../../config_srnw.php');
    $ConsultaT  = $ConnS->Query("SELECT * FROM cuenta WHERE dni = '".$_POST['0form1_dni']."'");
    $f = $ConnS->FetchArray($ConsultaT);
    if($_POST['ChkCuenta'] == 'SI'){
        if($f[1] == $_POST['0form1_dni']){
            $ConnS->Query("UPDATE cuenta SET saldo = '".$_POST['Cuenta']."' WHERE dni = '".$_POST['0form1_dni']."' ");
        }else{
            $ConnS->Query("INSERT INTO cuenta(dni, saldo) VALUES('".$_POST['0form1_dni']."','".$_POST['Cuenta']."')");
        }
    }     
    CuerpoInferior();
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
    setTimeout("location.href='index.php';",1000);
</script>