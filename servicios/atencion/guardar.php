<?php 
    if(!session_id()){session_start();}	
    include('../../config.php');	
    include("../../libs/clasemantem.php");
    $Op	= $_GET["Op"];
    $mantem = new dbMantimiento($Conn->GetConexion());
    $Sql = $mantem->__dbMantenimiento($_POST, "form1", "atencion", $Op);	//Se genera la sentencia SQL de acuerdo a la operación
    if ($Op==2)
    {
        $Sql = "UPDATE atencion SET estado=2 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
    }
    if ($Op==3)
    {
        $Sql = "UPDATE atencion SET estado=0 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
    }	
    $Conn->NuevaTransaccion();
    $Consulta = $Conn->Query($Sql);
    if (!$Consulta)
    {
        $Conn->TerminarTransaccion("ROLLBACK");
        $Res=2;
        $Mensaje ="Error al intentar ".$Accion[$Op]." los datos de la Atención";
    }
    else
    {
        if ($Op==0)
        {
            $SQL2 = "SELECT idatencion, correlativo FROM atencion WHERE idusuario='".$_POST['0form1_idusuario']."' AND idnotaria='".$_SESSION['notaria']."' ORDER BY idatencion DESC LIMIT 1";
            $Consulta2 = $Conn->Query($SQL2);
            $row2 = $Conn->FetchArray($Consulta2);
            $IdAtencion  = $row2[0];
            $Correlativo = $row2[0];
        }
        else
        {
            $IdAtencion  = $_POST["1form1_idatencion"];
            $Correlativo = $_POST["1form1_idatencion"];
        }
?>
<table width="400" border="0" cellspacing="0" cellpadding="0" >
  <tr>
      <td colspan="3">N&ordm; de Ticket Generado : <?php echo $Correlativo;?>
          <input type="hidden" id="IdAtencionT" value="<?php echo $IdAtencion;?>"/>
          <input type="hidden" id="CorrelativoT" value="<?php echo $Correlativo;?>"/>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="80">&nbsp;</td>
  </tr>
  <tr>
    <td width="90" align="center" bgcolor="#CC9900" style="font-size:12px">N&ordm; Kardex</td>
    <td align="center" bgcolor="#CC9900" style="font-size:12px">Servicio</td>
    <td align="center" bgcolor="#CC9900" style="font-size:12px">Precio</td>
  </tr>
<?php		
if ($Op!=4)
{
    $SQLDelete = "DELETE FROM atencion_detalle WHERE idatencion='$IdAtencion' AND idnotaria='".$_SESSION['notaria']."'";
    $result = $Conn->Query($SQLDelete);
    if (!$result) {die("Error in SQL query: ");}
}	
$Total  = 0;		
$Cont   = $_POST["ConServicios"];
$actualK = "";
for ($i=1; $i<=$Cont; $i+= 1){			
    if (isset($_POST["0formD".$i."_idatencion"])){	
        $nPost = array();
        $FormN = "formD".$i;
        foreach($_POST as $ind=>$val)
        {
            if(stripos($ind, $FormN.'_')!==false)
            {
                $nPost[$ind] = $val;
                if ($ind=='0formD'.$i.'_idatencion')
                {
                    $nPost[$ind] = $IdAtencion;
                }
            }
        }
    $SqlCorr = "SELECT servicio.especial, servicio.idkardex_tipo, servicio.correlativo, kardex_tipo.reinicio 
                FROM kardex_tipo INNER JOIN servicio ON (kardex_tipo.idkardex_tipo = servicio.idkardex_tipo)
                WHERE servicio.idservicio='".$_POST['0formD'.$i.'_idservicio']."'";
    $ConsultaCorr = $Conn->Query($SqlCorr);
    $rowCorr = $Conn->FetchArray($ConsultaCorr);				
            if ($Op==0 or $Op==1){					
                    $Pref = '';
                    if ($rowCorr[0]==1)
                    {
                            $Corr = trim($_POST['0formD'.$i.'_correlativo']);
			    $temp_corr = $Corr;
                            if ($Corr=='')
                            {
                                if ($rowCorr[3]==1)
                                {
                                    $SqlKT = "SELECT COUNT(idkardex_tipo) FROM kardex_tipo_notaria WHERE idnotaria='".$_SESSION["notaria"]."' AND idkardex_tipo='".$rowCorr[1]."' AND anio='".$_POST['0formD'.$i.'_anio']."' ";
                                }
                                else
                                {
                                    $SqlKT = "SELECT COUNT(idkardex_tipo) FROM kardex_tipo_notaria WHERE idnotaria='".$_SESSION["notaria"]."' AND idkardex_tipo='".$rowCorr[1]."'";								
                                }
                                $ConsultaKT = $Conn->Query($SqlKT);
                                
                                $rowKT = $Conn->FetchArray($ConsultaKT);								
                                if ($rowKT[0]==0)
                                {
                                    $SqlKC = "INSERT INTO kardex_tipo_notaria(idnotaria, idkardex_tipo, actual, anio) VALUES (".$_SESSION["notaria"].", ".$rowCorr[1].", 1, '".$_POST['0formD'.$i.'_anio']."')";
                                }
                                else
                                {
                                    $SqlKC = "UPDATE kardex_tipo_notaria SET actual=actual+1, anio='".$_POST['0formD'.$i.'_anio']."' WHERE idnotaria='".$_SESSION["notaria"]."' AND idkardex_tipo='".$rowCorr[1]."'";
                                }
                                //echo $SqlKC;
                                $ConsultaKC = $Conn->Query($SqlKC);							
                                $SqlSN = "SELECT lpad(cast(kardex_tipo_notaria.actual as varchar), 6, '0'), kardex_tipo.abreviatura FROM kardex_tipo_notaria INNER JOIN kardex_tipo ON (kardex_tipo_notaria.idkardex_tipo = kardex_tipo.idkardex_tipo) ";
                                if ($rowCorr[3]==1)
                                {
                                    $SqlSN = $SqlSN." WHERE kardex_tipo_notaria.idnotaria='".$_SESSION["notaria"]."' AND kardex_tipo_notaria.idkardex_tipo='".$rowCorr[1]."' AND kardex_tipo_notaria.anio='".$_POST['0formD'.$i.'_anio']."'";
                                }
                                else
                                {
                                    $SqlSN = $SqlSN." WHERE kardex_tipo_notaria.idnotaria='".$_SESSION["notaria"]."' AND kardex_tipo_notaria.idkardex_tipo='".$rowCorr[1]."' ";
                                }
                                
                                $ConsultaSN = $Conn->Query($SqlSN);
                                $rowSN = $Conn->FetchArray($ConsultaSN);
                                $Pref = $rowSN[1];
                                $Corr = $rowSN[0];

                                if (strlen($Pref)>1)
                                {
                                    $Corr = substr($rowSN[0], 1);
                                }							
                            }						
                            if ($_POST['0formD'.$i.'_idservicio']==118 || $_POST['0formD'.$i.'_idservicio']==245){
                                $SqlCo = "SELECT COUNT(correlativo) FROM carta WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."' AND idatencion='$IdAtencion' AND correlativo='".(int)substr($Corr, 2)."' ";
                                $ConsultaCo = $Conn->Query($SqlCo);
                                $rowCo = $Conn->FetchArray($ConsultaCo);
                                if ($rowCo[0]==0){
                                    if ($Corr=='')
                                    {
                                        $Corr = (int)$rowSN[0];
                                    }
                                    $SqlC = "INSERT INTO carta(idatencion, fecha, correlativo, idservicio, idusuario, fechareg, anio, idnotaria, hora) VALUES ('$IdAtencion', '".date('Y-m-d')."', '".  substr($Corr, 2)."', '".$_POST['0formD'.$i.'_idservicio']."', '".$_POST['0form1_idusuario']."', '".date('Y-m-d')."', '".$_POST['0formD'.$i.'_anio']."', '".$_SESSION["notaria"]."', '".date("h:i:s")."')";
                                    $ConsultaC = $Conn->Query($SqlC);
                                }
                            }else{
                                $SqlCo = "SELECT COUNT(correlativo) FROM kardex WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."' AND idatencion='$IdAtencion' AND correlativo='$Corr'";
                                $ConsultaCo = $Conn->Query($SqlCo);
                                $rowCo = $Conn->FetchArray($ConsultaCo);
                                if ($rowCo[0]==0){
				    if($temp_corr==''){
                                    $Consultax1 = $Conn->Query("SELECT max(anio) FROM reinicio WHERE idnotaria='".$_SESSION['notaria']."'");
                                    $rowx1 = $Conn->FetchArray($Consultax1);
                                    $ConsultaCoX = $Conn->Query("SELECT DISTINCT kardex_tipo_notaria.actual,kardex_tipo.abreviatura FROM servicio INNER JOIN atencion_detalle ON servicio.idservicio=atencion_detalle.idservicio INNER JOIN atencion ON atencion.idatencion = atencion_detalle.idatencion INNER JOIN kardex ON kardex.idatencion = atencion.idatencion INNER JOIN kardex_tipo ON kardex_tipo.idkardex_tipo = servicio.idkardex_tipo INNER JOIN kardex_tipo_notaria ON kardex_tipo.idkardex_tipo = kardex_tipo_notaria.idkardex_tipo WHERE atencion.anio >= '".$_POST['0formD'.$i.'_anio']."' AND kardex_tipo_notaria.idnotaria='".$_SESSION['notaria']."' AND kardex_tipo_notaria.idkardex_tipo = '".$rowCorr[1]."' ");
                                    $rowCoX = $Conn->FetchArray($ConsultaCoX);
				    $p = $rowCoX[1];
                                    $numero = $mantem->generaCode($rowCoX[0]-1, "", 6);
                                    $actualK = $Pref.$numero;
                                    $SqlK = "INSERT INTO kardex(idatencion, fecha, correlativo, idservicio, idusuario, fechareg, anio, idnotaria) 
						              VALUES ('$IdAtencion', '".$_POST['3form1_fecha']."', '".$p.$numero."', '".$_POST['0formD'.$i.'_idservicio']."', '".$_POST['0form1_idusuario']."', '".date('Y-m-d')."', '".$_POST['0formD'.$i.'_anio']."', '".$_SESSION["notaria"]."')";
                                    $ConsultaK = $Conn->Query($SqlK);
					}
                                }
                            }						
                    }
                    if ($rowCorr[2]==1){
                        $Corr =     trim($_POST['0formD'.$i.'_correlativo']);
                        $tem_corr = trim($_POST['0formD'.$i.'_correlativo']);
                        if ($Corr==''){
                            if ($rowCorr[3]==1){
                                    $SqlKT = "SELECT COUNT(idservicio) FROM servicio_notaria WHERE idnotaria=".$_SESSION["notaria"]." AND idservicio=".$_POST['0formD'.$i.'_idservicio']." AND anio='".$_POST['0formD'.$i.'_anio']."'";
                            }else{
                                    $SqlKT = "SELECT COUNT(idservicio) FROM servicio_notaria WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='".$_POST['0formD'.$i.'_idservicio']."'";
                            }
                            $ConsultaKT = $Conn->Query($SqlKT);
                            $rowKT = $Conn->FetchArray($ConsultaKT);							
                            if ($rowKT[0]==0){
                                $SqlKC = "INSERT INTO servicio_notaria(idnotaria, idservicio, correlativo, anio) VALUES ('".$_SESSION["notaria"]."', '".$_POST['0formD'.$i.'_idservicio']."', '1', '".$_POST['0formD'.$i.'_anio']."')";
                            }else{
                                $SqlKC = "UPDATE servicio_notaria SET correlativo=correlativo+1, anio='".$_POST['0formD'.$i.'_anio']."' WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='".$_POST['0formD'.$i.'_idservicio']."'";
                            }
                            $ConsultaKC = $Conn->Query($SqlKC);							
                            $SqlSN = "SELECT correlativo FROM servicio_notaria ";
                            if ($rowCorr[3]==1)
                            {
                                $SqlSN = $SqlSN." WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='".$_POST['0formD'.$i.'_idservicio']."' AND anio='".$_POST['0formD'.$i.'_anio']."'";
                            }
                            else
                            {
                                $SqlSN = $SqlSN." WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio='".$_POST['0formD'.$i.'_idservicio']."'";
                            }
                            $ConsultaSN = $Conn->Query($SqlSN);
                            $rowSN = $Conn->FetchArray($ConsultaSN);
                            $Corr = $rowSN[0];
                        }	
			    if ($_POST['0formD'.$i.'_idservicio']==118 || $_POST['0formD'.$i.'_idservicio']==245)
                            {
				if($Op==0){
                                
                                //$SqlCo = "SELECT COUNT(correlativo) FROM carta WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."' AND idatencion='$IdAtencion' AND correlativo='".(int)substr($Corr, 2)."' ";
                                $SqlCo = "SELECT correlativo from  carta WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."' order by idcarta desc limit 1";
                                $ConsultaCo = $Conn->Query($SqlCo);
                                $r = $Conn->FetchArray($ConsultaCo);
                                
                                    $Corr = "";
                                    if($r[0]>0){ $Corr = $r[0]+1;}
                                        else {$Corr = 1;}
                                    $SqlC = "INSERT INTO carta(idatencion, fecha, correlativo, idservicio, idusuario, fechareg, anio, idnotaria, hora) 
                                            VALUES ('$IdAtencion', '".date('Y-m-d')."', '".$Corr."', '".$_POST['0formD'.$i.'_idservicio']."', '".$_POST['0form1_idusuario']."', '".date('Y-m-d')."', '".$_POST['0formD'.$i.'_anio']."', '".$_SESSION["notaria"]."', '".date("h:i:s")."')";
                                    
                                    $ConsultaC = $Conn->Query($SqlC);
                                }
                            }						
                        if ($_POST['0formD'.$i.'_idservicio']==197)
                        {
			   if($Op==0){
                            $j = 0;
                            if($j<$_POST['0formD'.$i.'_cantidad'])
                            {
                                //$SqlCo = "SELECT MAX(correlativo) FROM libro WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."'";
                                $migrado = 0;
                                if($tem_corr!="")
                                {
                                    $Corr=$tem_corr;
                                    $migrado = 1;
                                }
                                else {                                                              


				$SqlCo = "SELECT correlativo FROM libro WHERE anio='".$_POST['0formD'.$i.'_anio']."' AND idnotaria='".$_SESSION["notaria"]."' order by idlibro desc limit 1";
                                $ConsultaCo = $Conn->Query($SqlCo);
                                $rowCo = $Conn->FetchArray($ConsultaCo);
                                $Corr = $rowCo[0]+1;
                               }
                                $SqlC = "INSERT INTO libro(idatencion, fecha, correlativo, razonsocial, direccion, folio_final, idusuario, fechareg, anio, idnotaria,migrado) VALUES ('$IdAtencion', '".date('Y-m-d')."', '$Corr', '".$_POST['0form1_cliente']."', '".$_POST['0form1_direccion']."', '".$_POST['0formD'.$i.'_folios']."', '".$_POST['0form1_idusuario']."', '".date('Y-m-d')."', '".$_POST['0formD'.$i.'_anio']."', '".$_SESSION["notaria"]."',".$migrado.")";
                                $ConsultaC = $Conn->Query($SqlC); 
                                $j++;
                            }
                        }}
                    }
                    $Pref.$Corr;
                    $nPost['0formD'.$i.'_correlativo'] = $Pref.$Corr;				
            }				
            $Importe = $_POST['0formD'.$i.'_cantidad'] * str_replace(',', '', $_POST['0formD'.$i.'_monto']);
            $Total = $Total + $Importe;
            $Servicio = $_POST['Servicio'.$i];
            $Importe = number_format($Importe, 2);
?>
<tr>
<td align="center" style="font-size:12px"><?php echo $nPost['0formD'.$i.'_correlativo'];?></td>
<td style="padding-left:5px; font-size:12px"><?php echo $Servicio;?></td>
<td align="right" style="padding-right:5px; font-size:12px"><?php echo $Importe;?></td>
</tr>
<?php
        if ($Op==2){
            if ($rowCorr[0]==1){
                if ($_POST['0formD'.$i.'_idservicio']==118 || $_POST['0formD'.$i.'_idservicio']==245){
                    $Sql = "UPDATE carta SET estado=3 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                    $Consulta2 = $Conn->Query($Sql);
                }else{
                    $Sql = "UPDATE kardex SET estado=3 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                    $Consulta2 = $Conn->Query($Sql);
                }
            }
            if ($_POST['0formD'.$i.'_idservicio']==197){							
                $Sql = "UPDATE libro SET estado=3 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                $Consulta2 = $Conn->Query($Sql);
            }
        }
        if ($Op==3){
            if ($rowCorr[0]==1){
                echo 'paso';
                if ($_POST['0formD'.$i.'_idservicio']==118 || $_POST['0formD'.$i.'_idservicio']==245){
                    $Sql = "UPDATE carta SET estado=0 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                    $Consulta2 = $Conn->Query($Sql);
                }else{
                    $Sql = "UPDATE kardex SET estado=0 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                    $Consulta2 = $Conn->Query($Sql);
                }
            }
            if ($_POST['0formD'.$i.'_idservicio']==197){
                $Sql = "UPDATE libro SET estado=0 WHERE idatencion='".$_POST['1form1_idatencion']."' AND idnotaria='".$_SESSION['notaria']."'";
                $Consulta2 = $Conn->Query($Sql);
            }
        }
        $mantem = new dbMantimiento($Conn->GetConexion());
        $Sql2 = $mantem->__dbMantenimiento($nPost, $FormN, "atencion_detalle", 0);	//Se genera la sentencia SQL de acuerdo a la operación
        $Consulta2 = $Conn->Query($Sql2);
    }
}		
$Conn->TerminarTransaccion("COMMIT");
$Res=1;
$Mensaje ="Registro ".$Accion[$Op + 4]." Correctamente";
}
?>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right" style="padding-right:5px">Importe Total : </td>
    <td align="right" style="padding-right:5px"><?php echo number_format($Total, 2);?></td>
  </tr>
</table>
<script>
    OperMensaje('<?php echo $Mensaje;?>', <?php echo $Res;?>);
</script>
