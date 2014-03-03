<?php 
	 header("Content-type: application/vnd.ms-excel; name='excel'");  
     header("Content-Disposition: filename=REPORTE_".date('Y-m-d-h-m-i-s').".xls");  
     header("Pragma: no-cache");  
     header("Expires: 0");
	 include('../../config.php');	
	 $sql = "SELECT t1.descr,t2.cant,t1.total
			from (
			select	fd.idservicio as idser,
				s.descripcion as descr,
				sum(fd.monto*fd.cantidad) as total
			from 	facturacion as f  inner join facturacion_detalle as fd on fd.idfacturacion = f.idfacturacion	
				inner join servicio as s on s.idservicio = fd.idservicio
			where Extract(year from f.fechareg)=2013
				and f.idnotaria = 1
				and f.estado <> 2
				and Extract(month from f.fechareg)=12
			group by fd.idservicio, s.descripcion
			) as t1 inner join
			(
			select	sum(fd.cantidad) as cant,
				fd.idservicio	as idserv
			from facturacion as f inner join facturacion_detalle as fd on fd.idfacturacion = f.idfacturacion	
				inner join servicio as s on s.idservicio = fd.idservicio	
			where Extract(year from f.fechareg)=2013
				and f.idnotaria = 1
				and f.estado <> 2
				and Extract(month from f.fechareg)=12
			group by fd.idservicio, s.descripcion
			) as t2 on t1.idser = t2.idserv
			order by descr desc";	 
	 $Consulta = $Conn->Query($sql);
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content" border="1">
	 	<thead class="ui-widget-header">
	 		  <!-- <tr class="ui-widget-header">
			    <th scope="col" bgcolor="#CCCCCC">&nbsp;</th>
			    <th scope="col" bgcolor="#DADADA">SERVICIO</th>
			    <th scope="col" bgcolor="#CCCCCC">CANT</th>
			    <th scope="col" bgcolor="#DADADA">IMPORTE</th>			    			    
			  </tr> -->
			  </thead>
			  <tbody>
			  <?php
			  $c = 0;
			  $nro = 0; //Numero de Registro de Operacion
			  $corre = "";
			  $flag = false;
			  $class="tr-item-1";
			 while($row = $Conn->FetchArray($Consulta))
			 {
			 	$c +=1;
			 	if($corre!=$row['correlativo'])
			 	{
			 		$nro += 1;
			 		$corre = $row['correlativo'];	
			 		$flag = true;	
			 		if($class=="tr-item-1") $class="tr-item-2";
			 			else $class="tr-item-1";
			 	}
			 	else 
			 	{
			 		$flag = false;
			 	}
			 	?>
			 	<tr>
			 		<td align="center">Cantidad</td>
			 		<td colspan="2"><?php echo $row[0]; ?></td>
			 	</tr>
				<tr>
				    <td align="center"><?php echo $row[1]; ?></td>
				    <td align="center">Importe</td>
				    <td align="right"><?php echo $row[2]; ?></td>	
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			  <?php
				}
			?>
	 		</tbody>
	 	</table>	
	 </div>
