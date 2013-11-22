<?php
if(!session_id()){ session_start(); }	
    include('../../config.php');	
    include("../../libs/clasemantem.php");	
    $Op		= $_GET["Op"];
    $mantem = new dbMantimiento($Conn->GetConexion());
    $Sql = $mantem->__dbMantenimiento($_POST, "form1", "carta", $Op);	//Se genera la sentencia SQL de acuerdo a la operación	

    if ($Op==2){
            $Sql = "Update carta set estado='2' where idcarta='".$_POST['1form1_idcarta']."'";
    }
    if ($Op==3){
            $Sql = "Update carta set estado='0' where idcarta='".$_POST['1form1_idcarta']."'";
    }
    $Conn->NuevaTransaccion();
    $Consulta = $Conn->Query($Sql);
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Carta";
    }else{		
        $Consulta = $Conn->Query(" UPDATE carta SET anio = '".$_POST['2form1_anio']."' WHERE idcarta='".$_POST['1form1_idcarta']."' ");
        $Conn->TerminarTransaccion("COMMIT");
        $Res=1;
        $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
    }
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>