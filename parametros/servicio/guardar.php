<?php
if(!session_id()){session_start();}
include('../../config.php');
include("../../libs/clasemantem.php");
$Op = $_GET["Op"];
$mantem = new dbMantimiento($Conn->GetConexion());
$Sql = $mantem->__dbMantenimiento($_POST, "form1", "servicio", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
$Conn->NuevaTransaccion();
$Consulta = $Conn->Query($Sql);
if (!$Consulta){
    $Conn->TerminarTransaccion("ROLLBACK");
    $Res=2;
    $Mensaje ="Error al intentar ".$Accion[$Op]." los datos del Servicio";
}else{
    if ($Op==0){
        $SQL2 = "SELECT idservicio FROM servicio ORDER BY idservicio DESC LIMIT 1";
        $Consulta2 = $Conn->Query($SQL2);
        $row2 = $Conn->FetchArray($Consulta2);
        $IdServicio = $row2[0];
    }else{
        $IdServicio = $_POST["1form1_idservicio"];
    }
    if ($_POST["0form1_correlativo"]==1){
            if ($Op==0){
                $SqlS = "SELECT idservicio FROM servicio 
                         WHERE idusuario='".$_POST['0form1_idusuario']."' 
                         ORDER BY idservicio DESC LIMIT 1";
                $ConsultaS = $Conn->Query($SqlS);
                $rowS = $Conn->FetchArray($ConsultaS);
                $IdServicio = $rowS[0];
            }
            $SqlSN = "SELECT COUNT(idservicio) 
                      FROM servicio_notaria 
                      WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='$IdServicio'";
            $ConsultaSN = $Conn->Query($SqlSN);
            $rowSN = $Conn->FetchArray($ConsultaSN);
            /*
             * Registro de Servicios por Notaria
             */
            if ($rowSN[0]==0){
                $SqlSN2 = "INSERT INTO servicio_notaria(idservicio, idnotaria, correlativo) 
                           VALUES ('".$IdServicio."', '".$_SESSION["notaria"]."', '".$_POST["CorrelativoNro"]."')";
            }else{
                $SqlSN2 = "UPDATE servicio_notaria 
                    SET correlativo='".$_POST["CorrelativoNro"]."' 
                    WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='$IdServicio'";
            }
            $ConsultaSN2 = $Conn->Query($SqlSN2);			
    }
    if ($Op!=4){
        $SQLDelete = "
            DELETE FROM servicio_participacion 
            WHERE idservicio='$IdServicio'";
        $result = $Conn->Query($SQLDelete);
        $SQLDeleteT = "DELETE FROM asigna_pdt 
            WHERE idservicio='$IdServicio'";
        $result = $Conn->Query($SQLDeleteT);
        if (!$result) {die("Error in SQL query: ");}
    }
    $Cont = $_POST["ConParticipacion"];
    for ($i=1; $i<=$Cont; $i+= 1){			
        if (isset($_POST["0formD".$i."_idservicio"])){	
            $nPost = array();
            $FormN = "formD".$i;
            foreach($_POST as $ind=>$val){
                if(stripos($ind, $FormN.'_')!==false){
                    $nPost[$ind] = $val;
                    if ($ind=='0formD'.$i.'_idservicio'){
                        $nPost[$ind] = $IdServicio;
                    }
                }
            }
            $mantem = new dbMantimiento($Conn->GetConexion());
            $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "servicio_participacion", 0);	//Se genera la sentencia SQL de acuerdo a la operación
            $Consulta2 = $Conn->Query($Sql2);
        }
    }
    $Cont = $_POST["ConPDT_Notario"];
    for ($i=1; $i<=$Cont; $i+= 1){			
        if (isset($_POST["0formF".$i."_idservicio"])){	
            $nPost = array();
            $FormN = "formF".$i;
            foreach($_POST as $ind=>$val){
                if(stripos($ind, $FormN.'_')!==false){
                    $nPost[$ind] = $val;
                    if ($ind=='0formF'.$i.'_idservicio'){
                        $nPost[$ind] = $IdServicio;
                    }
                }
            }
            $mantem    = new dbMantimiento($Conn->GetConexion());
            $Sql2      = $mantem->__dbMantenimiento($nPost, $FormN, "asigna_pdt", 0);	//Se genera la sentencia SQL de acuerdo a la operación
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