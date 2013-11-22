<?php
if(!session_id()){session_start();}	
    include('../config.php');	
    include("../libs/clasemantem.php");	
    $Op	= $_GET["Op"];
    $mantem = new dbMantimiento($Conn->GetConexion());
    $nPost = array();
    $nPost = $_POST;
    if ($_POST['0form1_idcliente']==0)
    {

       
      if(strlen($_POST['0form1_dni_ruc'])>=8 && strlen($_POST['0form1_dni_ruc'])<=11)
      {


        $ver="select idcliente from cliente where dni_ruc='".$_POST['0form1_dni_ruc']."'";
        $verr = $Conn->Query($ver);
        $rowv = $Conn->FetchArray($verr);
        $verif= $Conn->NroRegistros($verr);
        if($verif<=0)   
       {

        $SqlC = "INSERT INTO cliente(idcliente_tipo, iddocumento, dni_ruc, nombres, direccion, estado,idprofesion, idusuario, fechareg)
                 VALUES('1', '".$_POST['0form1_iddocumento']."', '".$_POST['0form1_dni_ruc']."', '".$_POST['0form1_nombres']."', '".$_POST['0form1_direccion']."', '1', '998','".$_POST['0form1_idusuario']."', '".$Conn->CodFecha($_POST['3form1_fechareg'])."');";
       
        $resultC = $Conn->Query($SqlC);		
        $ConsultaS = $Conn->Query("SELECT NEXTVAL('cliente_idcliente_seq')");
        $rowS = $Conn->FetchArray($ConsultaS);
        $IdCliente = $rowS[0]-1;		
        $FormN = "form1";
        foreach($_POST as $ind=>$val)
        {
            if(stripos($ind, $FormN.'_')!==false)
            {
                $nPost[$ind] = $val;
                if ($ind=='0form1'.'_idcliente')
                {
                    $nPost[$ind] = $IdCliente;
                }
            }
        }

       }//fin if nroregistros
       else
       {

        $SQLV = "UPDATE cliente 
                 SET nombres='".$_POST['0form1_nombres']."',direccion='".$_POST['0form1_direccion']."'

                 WHERE dni_ruc='".$_POST['0form1_dni_ruc']."'";
        $resultv = $Conn->Query($SQLV);
        $_POST['0form1_idcliente']=$rowv[0];
       }
      }//fin >=8 && <=11
    }    
    else{

        $SQLV = "UPDATE cliente 
                 SET nombres='".$_POST['0form1_nombres']."',direccion='".$_POST['0form1_direccion']."'

                 WHERE dni_ruc='".$_POST['0form1_dni_ruc']."'";
        $resultv = $Conn->Query($SQLV);
    }

    $Sql = $mantem->__dbMantenimiento($nPost, "form1", "facturacion", $Op);    
    $Conn->NuevaTransaccion();
    /*$Conn->TerminarTransaccion("COMMIT");
    $Conn->NuevaTransaccion();*/
    if ($Op==0){
        /*$SQLT = " SELECT max(idfacturacion) FROM facturacion ";
        $result = $Conn->Query($SQLT);
        $row = $Conn->FetchArray($result);
        $SQLT = "UPDATE facturacion SET anio='".date('Y')."' WHERE idfacturacion='".$row[0]."'";
        $result = $Conn->Query($SQLT);	*/
        $SQLA = "UPDATE atencion SET estado=1 WHERE idatencion=".$_POST['0form1_idatencion'];
        $result = $Conn->Query($SQLA);		
        $SQLA = "UPDATE caja_notaria_comprobante SET correlativo=".(int)$_POST['0form1_comprobante_numero']." WHERE idnotaria=".$_SESSION['notaria']." AND idcaja=".$_POST['IdCaja']." AND idcomprobante=".$_POST['0form1_idcomprobante'];
        $result = $Conn->Query($SQLA);
    }	
    if ($Op==2){
        /*$SQLT = "UPDATE facturacion SET anio='".date('Y')."' WHERE idfacturacion='".$_POST['1form1_idfacturacion']."'";
        $result = $Conn->Query($SQLT);	*/
        $Sql = "UPDATE facturacion SET estado=2 WHERE idfacturacion=".$_POST['1form1_idfacturacion'];		
        $SQLA = "UPDATE atencion SET estado=0 WHERE idatencion=".$_POST['0form1_idatencion'];
        $result = $Conn->Query($SQLA);
    }
    if ($Op==3){
        $SQLT = "UPDATE facturacion SET anio='".date('Y')."' WHERE idfacturacion='".$_POST['1form1_idfacturacion']."'";
        $result = $Conn->Query($SQLT);	
        $Sql = "UPDATE facturacion SET estado=1 WHERE idfacturacion=".$_POST['1form1_idfacturacion'];		
        $SQLA = "UPDATE atencion SET estado=1 WHERE idatencion='".$_POST['0form1_idatencion']."'";
        $result = $Conn->Query($SQLA);
    }
    $Consulta = $Conn->Query($Sql);
    if (!$Consulta){
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Atención";
    }else{
        if ($Op==0){
            $SQL2 = "SELECT idfacturacion FROM facturacion WHERE idusuario='".$_POST['0form1_idusuario']."' ORDER BY idfacturacion DESC LIMIT 1";
            $Consulta2 = $Conn->Query($SQL2);
            $row2 = $Conn->FetchArray($Consulta2);
            $IdFacturacion = $row2[0];
        }else{
            $IdFacturacion = $_POST["1form1_idfacturacion"];
        }		
        if ($Op<3){
            $SQLDelete = "DELETE FROM facturacion_detalle WHERE idfacturacion='$IdFacturacion'";
            $result = $Conn->Query($SQLDelete);
            if (!$result) {die("Error in SQL query: ");}						
            $Cont	= $_POST["ConServicios"];
            for ($i=1; $i<=$Cont; $i+= 1)
            {
                if (isset($_POST["0formD".$i."_idfacturacion"]))
                {
                    $nPost = array();
                    $FormN = "formD".$i;
                    foreach($_POST as $ind=>$val)
                    {
                        if(stripos($ind, $FormN.'_')!==false)
                        {
                            $nPost[$ind] = $val;
                            if ($ind=='0formD'.$i.'_idfacturacion')
                            {
                                $nPost[$ind] = $IdFacturacion;
                            }
                        }
                    }
                    //Agregamos parametros de session
                    $nPost['0formD1_idusuario'] = $_SESSION["id_user"];
                    $nPost['3formD1_fechareg'] = date('d/m/Y');

                    $mantem = new dbMantimiento($Conn->GetConexion());                    
                    $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "facturacion_detalle", 0);	//Se genera la sentencia SQL de acuerdo a la operación                    

                    $Consulta2 = $Conn->Query($Sql2);
                }
            }
        }
        $SQLT = "UPDATE facturacion SET anio='".date('Y')."' WHERE idfacturacion='$IdFacturacion'";
        $result = $Conn->Query($SQLT);	
        $Conn->TerminarTransaccion("COMMIT");
        $Res=1;
        $Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
    }
?>
<script>
    OperMensaje('<?php echo $Mensaje;?>',<?php echo $Res;?>);
</script>
<input type="hidden" name="IdFacturacionD" id="IdFacturacionD" value="<?php echo $IdFacturacion;?>" />