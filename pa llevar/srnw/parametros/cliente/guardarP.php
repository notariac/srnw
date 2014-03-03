<?php
if(!session_id()){session_start();}
    include('../../config.php');
    include("../../libs/clasemantem.php");
    $Op		= $_GET["Op"];
    $mantem = new dbMantimiento($Conn->GetConexion());
    $nPost = array();
    $FormN = "formP";
    foreach($_POST as $ind=>$val){
        if(stripos($ind, $FormN.'_')!==false)
        {
            $nPost[$ind] = $val;
            if ($ind=='0formP_nombres')
            {
                $nPost[$ind] = $_POST['RazonNombre1']." ! ".$_POST['RazonNombre2'];
            }
        }
    }	
    $Sql = $mantem->__dbMantenimiento($nPost, $FormN, "cliente", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
    $Conn->NuevaTransaccion();
    $Consulta 	= $Conn->Query($Sql);
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos del Cliente";
    }else{
        if ($Op==0){
            $SQL2 = "SELECT idcliente FROM cliente WHERE idusuario='".$_SESSION['id_user']."' ORDER BY idcliente DESC LIMIT 1";
            $Consulta2 = $Conn->Query($SQL2);
            $row2 = $Conn->FetchArray($Consulta2);
            $IdParticipante = $row2[0];
        }else{
            $IdParticipante = $_POST["1formP_idcliente"];
        }
        $IdCliente = $_POST["0formP_dni_ruc"];
        if ($Op!=4){
            $SQLDelete = "DELETE FROM cliente_representante WHERE cliente_representante.idcliente = '$IdParticipante'";
            $result = $Conn->Query($SQLDelete);
            if (!$result) {die("Error in SQL query: ");}
        }
        $Cont	= $_POST["ConRepresentante"];
        for ($i=1; $i<=$Cont; $i+= 1){			
            if (isset($_POST["0formD".$i."_ruc_cliente"])){	
                $nPost = array();
                $FormN = "formD".$i;
                foreach($_POST as $ind=>$val){
                    if(stripos($ind, $FormN.'_')!==false){
                        $nPost[$ind] = $val;
                        if ($ind=='0formD'.$i.'_ruc_cliente'){
                                $nPost[$ind] = $IdCliente;
                        }
                        if ($ind=='0formD'.$i.'_idcliente'){
                                $nPost[$ind] = $IdParticipante;
                        }
                        if ($ind=='0formD'.$i.'_idrepresentante'){
                            if ($_POST["0formD".$i."_idrepresentante"]==''){
                                $SqlP = "SELECT idcliente FROM cliente WHERE dni_ruc='".$_POST["0formD".$i."_dni_representante"]."'";
                                $ConsultaP = $Conn->Query($SqlP);
                                $rowP = $Conn->FetchArray($ConsultaP);
                                if ($rowP[0]!=''){
                                        $IdParticipante2 = $rowP[0];
                                }else{
                                    $ConsultaMC = $Conn->Query("SELECT MAX(idcliente)+1 FROM cliente");
                                    $rowMC = $Conn->FetchArray($ConsultaMC);
                                    $Sql = "INSERT INTO cliente (idcliente, idcliente_tipo, iddocumento, dni_ruc, nombres) VALUES(".$rowMC[0].", 1, 1, '".$_POST["0formD".$i."_dni_representante"]."', '".$_POST["NombreD".$i]."')";
                                    $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
                                    $rowS = $Conn->FetchArray($ConsultaS);
                                    if ($rowMC[0]>=$rowS[0]){
                                        $ConsultaSeg = $Conn->Query('ALTER SEQUENCE cliente_idcliente_seq restart '.$rowMC[0]);
                                    }
                                    $Consulta = $Conn->Query($Sql);
                                    $IdParticipante2 = $rowMC[0];
                                }
                            }else{
                                    $IdParticipante2 = $_POST["0formD".$i."_idrepresentante"];
                            }
                            $nPost[$ind] = $IdParticipante2;
                        }
                    }
                }
                $mantem = new dbMantimiento($Conn->GetConexion());
                $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "cliente_representante", 0);	//Se genera la sentencia SQL de acuerdo a la operación
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
<input name="IdParticipanteC" id="IdParticipanteC" type="hidden" value="<?php echo $IdParticipante;?>" />