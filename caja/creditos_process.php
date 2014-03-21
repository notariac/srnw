<?php 
include('../config.php');
$oper = $_POST['oper'];
switch ($oper) 
{
	case 1:
			//Obtener el monto total de la deuda
			//Obtener el monto total de los pagos realizados
			//Calcular la diferencia

			$sql = "SELECT	 coalesce(t1.total_p,0) as importe_pa,
								f.total-(coalesce(t1.total_p,0)) as importe_pe,
								f.total as importe_to
						from facturacion as f 
								inner join facturacion_detalle as fd on fd.idfacturacion = f.idfacturacion
								inner join servicio as s on s.idservicio = fd.idservicio
								left outer join 
								  (select sum(monto) as total_p,
								      idfacturacion
							    from facturacion_pagos
							group by idfacturacion ) as t1 on t1.idfacturacion = fd.idfacturacion 
						where fd.idfacturacion = ".$_POST['idfacturacion'];

			$Consulta = $Conn->Query($sql);
		 	$cont = 0;		 
		 	$row = $Conn->FetchArray($Consulta);
		 	$monto = $row['importe_pe'];

		 	$msg = "";
		 	$error = 0;
		 	$_POST['monto'] = str_replace(",", "", $_POST['monto']);
		 	if($monto>=$_POST['monto'])
		 	{
		 		if($_POST['identidad_financiera']=="")
		 			$_POST['identidad_financiera']=99;
	
		 		$monto_restante = $monto-$_POST['monto'];
		 			
		 		$sql = "INSERT INTO facturacion_pagos(idfacturacion, fecha_pago, idforma_pago, 
						            nrodocumento, identidad_financiera, monto, observacion)
						    VALUES (".$_POST['idfacturacion'].", '".$Conn->CodFecha($_POST['fecha_pago'])."', ".$_POST['idforma_pago'].", 
						            '".$_POST['nrodocumento']."', ".$_POST['identidad_financiera'].", ".$_POST['monto'].", '".$_POST['observacion']."');";				
				
				$Conn->Query($sql);

				if($monto_restante==0)
				{
					$sql = "UPDATE facturacion set estado_credito = 1, fecha_pay = '".$Conn->CodFecha($_POST['fecha_pago'])."' 
							where idfacturacion = ".$_POST['idfacturacion'];
					$Conn->Query($sql);
				}
				else
				{
					$sql = "UPDATE facturacion set  fecha_pay = '".$Conn->CodFecha($_POST['fecha_pago'])."' 
							where idfacturacion = ".$_POST['idfacturacion'];
					$Conn->Query($sql);
				}


		 	}
		 	else
		 	{
		 		$msg = "EL MONTO INGRESADO SUPERA EL MONTO TOTAL DE LA DEUDA (S/. ".number_format($monto,2).")";
		 		$error = 1;
		 	}
		 	print_r(json_encode(array($error,$msg,$html)));
		break;
	case 2: 
			$html = getListPay($_POST['idfacturacion'],$Conn);
			echo $html;
			break;
	default:
			echo "Hola default";
		break;
}

function getListPay($idf,$Conn)
{
	$sql = "SELECT fp.idfacturacion_pagos,
			       fp.idfacturacion,
			       fp.fecha_pago,
			       case frp.idforma_pago when 1 then frp.descripcion
					when 2 then frp.descripcion||' Nro '||fp.nrodocumento||' - '||ef.descripcion
					when 5 then frp.descripcion||' Nro '||fp.nrodocumento||' - '||ef.descripcion
				   end as forma_pago,
			       fp.monto,
			       fp.observacion
			FROM   facturacion_pagos as fp inner join forma_pago as frp on
			       frp.idforma_pago = fp.idforma_pago
			       inner join pdt.entidadfinanciera as ef on ef.identidad_financiera = fp.identidad_financiera
			WHERE fp.idfacturacion = ".$idf."
			ORDER BY fp.idfacturacion_pagos ";

	$q = $Conn->Query($sql); 	
	$html = "";
	$c = 0;
	$s = 0;
 	while($r = $Conn->FetchArray($q))
 	{
 		$c += 1;
 		$html .= "<tr>
 					<td align='center'>".$c."</td>
 					<td align='center'>".$Conn->DecFecha($r['fecha_pago'])."</td>
 					<td>".$r['forma_pago']."</td>
 					<td align='right'>".number_format($r['monto'],2)."</td>
 					<td align='left'><span style='font-size:9px;'>".$r['observacion']."</span></td>
 					<td align='center'><a href='#'>Anular</a></td>
 				  </tr>";
 		$s += $r['monto'];
 	}
 	
 	$html .= "<tr><td colspan='4' align='right'><b>S/. TOTAL PAGADO: </b></td>";
 	$html .= "<td align='right'><b>".number_format($s,2)."</b></td>";
 	$html .= "<td>&nbsp;</td></tr>";



 	return $html;
}
?>