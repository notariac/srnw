<?php
if(!session_id()){ session_start(); }	
include('../../config.php');	
include("../../libs/clasemantem.php");	
$Op= $_GET["Op"];
$mantem = new dbMantimiento($Conn->GetConexion());
$Sql = $mantem->__dbMantenimiento($_POST, "form1", "libro", $Op);	//Se genera la sentencia SQL de acuerdo a la operación	
if ($Op==2){
    $Sql = "update libro set estado=2 where idlibro=".$_POST['1form1_idlibro'];
}
if ($Op==3){
    $Sql = "update libro set estado=0 where idlibro=".$_POST['1form1_idlibro'];
}	
$Conn->NuevaTransaccion();
$Consulta = $Conn->Query($Sql);
if (!$Consulta){
    $Conn->TerminarTransaccion("ROLLBACK");
    $Res=2;
    $Mensaje ="Error al intentar ".$Accion[$Op]." los datos del Libro";
}else{		
    $Sql = "SELECT COUNT(*) FROM cliente WHERE dni_ruc='".$_POST['0form1_ruc']."'";
    $Consulta = $Conn->Query($Sql);
    $Row = $Conn->FetchArray($Consulta);
    if ($Row[0]==0){
        $SqlC = "INSERT INTO cliente (idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, telefonos) ";
        $SqlC = $SqlC."VALUES (2, 4, '".$_POST['0form1_ruc']."', '".$_POST['0form1_razonsocial']."', '".$_POST['0form1_direccion']."', '".$_POST['0form1_telefono']."')";
        $ConsultaC = $Conn->Query($SqlC);
    }
    $Conn->TerminarTransaccion("COMMIT");
    $Res=1;
    $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
}
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>