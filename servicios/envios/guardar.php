<?php
if(!session_start()){session_start();}	
	include('../../config.php');	
	include("../../libs/clasemantem.php");	
	$Op = $_GET["Op"];	
	if ($Op!=4){
            $SQLDelete = "DELETE FROM kardex_derivacion WHERE idkardex='".$_POST['1form1_idkardex']."'";
            $result = $Conn->Query($SQLDelete);
            if (!$result) {die("Error in SQL query: ");}
	}		
	$Cont = $_POST["ConDependencia"];
	for ($i=1; $i<=$Cont; $i+= 1){			
            if (isset($_POST["0formD".$i."_idkardex"])){	
                $nPost = array();
                $FormN = "formD".$i;
                foreach($_POST as $ind=>$val){
                    if(stripos($ind, $FormN.'_')!==false){
                        $nPost[$ind] = $val;
                    }
                }
                $mantem = new dbMantimiento($Conn->GetConexion());
                $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "kardex_derivacion", 0);	//Se genera la sentencia SQL de acuerdo a la operación
                $Consulta2 = $Conn->Query($Sql2);
            }
	}	
	$Conn->TerminarTransaccion("COMMIT");
	$Res=1;
	$Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>