<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/clasemantem.php");	
    $Op = $_GET["Op"];
    $mantem = new dbMantimiento($Conn->GetConexion());
    $CMaximo 	= $Conn->Query("SELECT max(anio) FROM reinicio WHERE idnotaria='".$_POST['1form1_idnotaria']."'");
    $FAMaximo 	= $Conn->FetchArray($CMaximo); 
    if($FAMaximo[0]<=$_POST['0form1_anio']){  
        $_SESSION['Anio'] = $_POST['0form1_anio'];
        $Sql = $mantem->__dbMantenimiento($_POST, "form1", "notaria", $Op);	//Se genera la sentencia SQL de acuerdo a la operación    
        $Conn->NuevaTransaccion();
        $Consulta 	= $Conn->Query($Sql);
        if (!$Consulta){
            $Conn->TerminarTransaccion("ROLLBACK");
            $Res=2;
            $Mensaje ="Error al intentar ".$Accion[$Op]." los datos del Servicio";
        }else{
            if ($Op!=4){
                $result = $Conn->Query("DELETE FROM reinicio WHERE idnotaria='".$_SESSION['notaria']."'");
                if (!$result) {die("Error in SQL query: ");}
            }
            $Cont = $_POST["ConNumeracion"];
            for ($i=1; $i<=$Cont; $i+= 1){			
                if (isset($_POST["0formD".$i."_idnotaria"])){	
                    $nPost = array();
                    $FormN = "formD".$i;
                    foreach($_POST as $ind=>$val){
                        if(stripos($ind, $FormN.'_')!==false){
                            $nPost[$ind] = $val;
                            if ($ind=='0formD'.$i.'_idnotaria'){
                                $nPost[$ind] = $_POST['0formD'.$i.'_idnotaria'];
                            }
                        }
                    }
                    $mantem = new dbMantimiento($Conn->GetConexion());
                    $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "reinicio", 0);	//Se genera la sentencia SQL de acuerdo a la operación
                    $Consulta2 = $Conn->Query($Sql2);
                }
            }
            $Conn->TerminarTransaccion("COMMIT");
            $Res=1;
            $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
        }        
    }else{  
        $Res=1;
        $Mensaje ="El año de registro se cerró, consulte a su administrador";
    }
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>