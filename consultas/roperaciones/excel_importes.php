<?php 
	 header("Content-type: application/vnd.ms-excel; name='excel'");  
     header("Content-Disposition: filename=REPORTE_".date('Y-m-d-h-m-i-s').".xls");  
     header("Pragma: no-cache");  
     header("Expires: 0");
		include('../../config.php');	
	$sql = "SELECT t1.descr,t2.cant,t1.total
			from (
			select	ad.idservicio as idser,
				s.descripcion as descr,
				sum(f.total) as total
			from facturacion as f inner join atencion_detalle as ad on ad.idatencion = f.idatencion
				inner join servicio as s on s.idservicio = ad.idservicio
			where Extract(year from f.fechareg)=2013
				and f.idnotaria = 1
				and f.estado <> 2
				and Extract(month from f.fechareg)=1
			group by ad.idservicio, s.descripcion
			) as t1 inner join
			(
			select	count(ad.idservicio) as cant,
				ad.idservicio	as idserv
			from facturacion as f inner join atencion_detalle as ad on ad.idatencion = f.idatencion
				inner join servicio as s on s.idservicio = ad.idservicio
				inner join atencion as a on a.idatencion = ad.idatencion
			where Extract(year from f.fechareg)=2013
				and f.idnotaria = 1
				and a.estado <> 2
				and Extract(month from f.fechareg)=1
			group by ad.idservicio, s.descripcion
			) as t2 on t1.idser = t2.idserv
			order by t1.total desc";	 
	 $Consulta = $Conn->Query($sql);
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content" border="1">
	 	<thead class="ui-widget-header">
	 		  <tr class="ui-widget-header">
			    <th scope="col" bgcolor="#CCCCCC">&nbsp;</th>
			    <th scope="col" bgcolor="#DADADA">SERVICIO</th>
			    <th scope="col" bgcolor="#CCCCCC">CANT</th>
			    <th scope="col" bgcolor="#DADADA">IMPORTE</th>			    			    
			  </tr>
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
			  <tr class="<?php echo $class; ?>">
			    <td align="left"><?php echo $row[0]; ?></td>
			    <td align="center"><?php echo $row[1]; ?></td>
			    <td align="right"><?php echo $row[2]; ?></td>	
			  </tr>
			  <?php
				}
			?>
	 		</tbody>
	 	</table>	
	 </div>