<?php
if( !session_id() ){ session_start(); }
    include('../../config.php');	
    include("../../libs/clasemantem.php");	
    $Op	= $_GET["Op"];
    $Ac = $Op;
    if( $Op==1 ){ $Op=0; }
    $mantem   = new dbMantimiento( $Conn->GetConexion() );               
    $ConsultaT = $Conn->Query("DELETE FROM kardex_tipo_notaria WHERE idnotaria='".$_SESSION['notaria']."' AND idkardex_tipo='".$_POST['0form1_idkardex_tipo']."' ");
    $Sql = $mantem->__dbMantenimiento($_POST, "form1", "kardex_tipo_notaria", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
    $Conn->NuevaTransaccion();
    $Consulta = $Conn->Query($Sql);
    $Op=$Ac;
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje = "Error al intentar ".$Accion[$Op]." los datos del Kardex por Notar&iacute;a";
    }else{
        $Conn->TerminarTransaccion("COMMIT");
        $Res=1;
        $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";        
    }    
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>