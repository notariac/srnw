<?php
if(!session_id()){ session_start(); }	
include('../../config.php');	
include("../../libs/clasemantem.php");	
$Op     = $_GET["Op"];
$mantem = new dbMantimiento($Conn->GetConexion());
$Sql    = $mantem->__dbMantenimiento($_POST, "form1", "caja", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
$Conn->NuevaTransaccion();
$Consulta = $Conn->Query($Sql);
if (!$Consulta){
    $Conn->TerminarTransaccion("ROLLBACK");
    $Res=2;
    $Mensaje ="Error al intentar ".$Accion[$Op]." los datos del Servicio";
}else{
    if ($Op==0){
        $SQL2 = "SELECT idcaja FROM caja ORDER BY idcaja DESC LIMIT 1";
        $Consulta2 = $Conn->Query($SQL2);
        $row2 = $Conn->FetchArray($Consulta2);
        $IdCaja = $row2[0];
    }else{
        $IdCaja = $_POST["1form1_idcaja"];
    }		
    if ($Op!=4){
        $SQLDelete = "DELETE FROM caja_notaria_comprobante WHERE idcaja=".$IdCaja;
        $result = $Conn->Query($SQLDelete);
        if (!$result) {die("Error in SQL query: ");}
    }
    $Cont	= $_POST["ConComprobante"];
    for ($i=1; $i<=$Cont; $i+= 1){			
        if (isset($_POST["0formD".$i."_idcaja"])){	
            $nPost = array();
            $FormN = "formD".$i;
            foreach($_POST as $ind=>$val){
                if(stripos($ind, $FormN.'_')!==false){
                    $nPost[$ind] = $val;
                    if ($ind=='0formD'.$i.'_idcaja'){
                        $nPost[$ind] = $IdCaja;
                    }
                }
            }
            $mantem = new dbMantimiento($Conn->GetConexion());
            $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "caja_notaria_comprobante", 0);	//Se genera la sentencia SQL de acuerdo a la operación
            $Consulta2 = $Conn->Query($Sql2);
        }
    }		
    $Conn->TerminarTransaccion("COMMIT");
    $Res=1;
    $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
}
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>