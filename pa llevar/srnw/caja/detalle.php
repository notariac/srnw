<?php
if(!session_id()){ session_start(); }
    require("../config.php");	
    $Anio = $_POST['Anio'];
    $Id = $_POST['Id'];	
    $Sql = "SELECT COUNT(*) FROM atencion WHERE idatencion = ".$Id." AND idnotaria='".$_SESSION['notaria']."' AND estado!=2 ";	    
    $Consulta = $Conn->Query($Sql);	
    $row = $Conn->FetchArray($Consulta);	

    $SqlX = "SELECT idatencion FROM atencion WHERE idatencion = ".$Id." AND idnotaria='".$_SESSION['notaria']."' AND estado!=2 ";	    
    $ConsultaX = $Conn->Query($SqlX);	
    $rowX = $Conn->FetchArray($ConsultaX);	
    $SqlTF = "SELECT * FROM facturacion WHERE idatencion = '".$rowX[0]."' and estado = 1 ";
    
    $ConsultaTF = $Conn->Query($SqlTF);
    $rowTF = $Conn->FetchArray($ConsultaTF);
    $n = $Conn->NroRegistros($ConsultaTF);
    if($n!=0){
        echo "1";
    }else{
?>
<table width="700" border="1" cellspacing="1" style="color: #000000;" bgcolor="#ECECEC" id="ListaMenu2">
	<tr>
            <th title="Cabecera" width="50" height="20">Item</th>
            <th title="Cabecera">Servicio</th>
            <th title="Cabecera" width="60">Kardex</th>
            <th title="Cabecera" width="60">Cantidad</th>
            <th title="Cabecera" width="70">Precio</th>
            <th title="Cabecera" width="70">Total</th>
            <th title="Cabecera" width="20">&nbsp;</th>
	</tr>
   	<tbody>
<?php
			$NumRegs = 0;
			$NumRegsA = 0;
			if ($row[0]>0){
				$NumRegsA = 1;
				$SQL2 = "SELECT atencion_detalle.anio, atencion.idatencion, atencion_detalle.item, atencion_detalle.idservicio, servicio.descripcion, atencion_detalle.correlativo, atencion_detalle.cantidad, atencion_detalle.monto, (atencion_detalle.cantidad * atencion_detalle.monto) 
                        FROM atencion_detalle INNER JOIN servicio ON (atencion_detalle.idservicio = servicio.idservicio) 
                                            INNER JOIN atencion ON (atencion.idatencion = atencion_detalle.idatencion) 
                        WHERE atencion.idatencion=".$Id." AND atencion_detalle.idnotaria='".$_SESSION['notaria']."' 
                        ORDER BY atencion_detalle.item ASC";	
                        		
				$Consulta2       = $Conn->Query($SQL2);				
				while($row2      = $Conn->FetchArray($Consulta2)){
					$NumRegs = $NumRegs + 1;
                    $nTotal  = $nTotal + str_replace(',', '', $row2[8]);
?>
				<tr>
                                    <td align="center">
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_anio' value='<?php echo $Anio;?>' />
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_idfacturacion' value='<?php echo $Id;?>' />
                                        <input type="hidden" name="0formD<?php echo $NumRegs;?>_item" id="ItemD<?php echo $NumRegs;?>" value="<?php echo $NumRegs;?>" />
                                        <label id="Item<?php echo $NumRegs;?>"><?php echo $NumRegs;?></label>
                                    </td>
				    <td style="padding-left:5px"><input name="0formD<?php echo $NumRegs;?>_idservicio" id="IdServicio<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[3];?>" /><?php echo $row2[4];?></td>
				    <td style="padding-left:5px"><input name="0formD<?php echo $NumRegs;?>_correlativo" id="Correlativo<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[5];?>" /><?php echo $row2[5];?>&nbsp;</td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_cantidad" id="CantidadD<?php echo $NumRegs;?>" value="<?php echo $row2[6];?>" style="width:60px; text-align:center" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $EnabledC;?>/></td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_monto" id="MontoD<?php echo $NumRegs;?>" value="<?php echo str_replace(',','', $row2[7]);?>" style="width:60px; text-align:right" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $Enabled;?>/></td>
				    <td align="right" style="padding-right:5px"><label id="TotalD<?php echo $NumRegs;?>"><?php echo str_replace(',','', $row2[8]);?></label></td>
				    <td align="center"><img src="../imagenes/iconos/eliminar.png" width="16" height="16" onclick="QuitaServicio(this);" style="cursor:pointer; display:none; <?php if ($Op==2 || $Op==3 or $Op==4){ echo "display:none";}?>" title="Quitar Servicio" /></td>
				</tr>
<?php
				}				
			}
			echo "<script> var nDest = $NumRegs; var nDestC = $NumRegs; </script>";
?>
			</tbody>
		</table>
<input type="hidden" name="ConServicios" id="ConServicios" value="<?php echo $NumRegs;?>"/>
<input type="hidden" name="ConServiciosA" id="ConServiciosA" value="<?php echo $NumRegsA;?>"/>
<?php } ?>
