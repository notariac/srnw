<?php 
	header("Content-type: application/vnd.ms-excel; name='excel'");  
    header("Content-Disposition: filename=REPORTE_".date('Y-m-d-h-m-i-s').".xls");  
    header("Pragma: no-cache");  
    header("Expires: 0");
	include('../../config.php');
	include('../../config_seguridad.php');

	$usuarios = array();
	$sql = "select idusuario,login from usuario";
	$q = $ConnS->Query($sql);
	while($row = $ConnS->FetchArray($q))
	{
		$usuarios[$row[0]] = $row[1];
	}

	$sql = "SELECT 	k.idkardex,
					k.escritura,
					k.correlativo,
					s.descripcion as servicio,
					k.fecha,					
					coalesce(t1.np,0) as numero_partc,
					m.descripcion as moneda,
					kaj.monto as monto,
					coalesce(t2.nb,0) as numero_bienes,
					k.idusuario
				FROM	kardex as k inner join servicio as s on s.idservicio = k.idservicio
					left outer join (select idkardex,count(*) as np from kardex_participantes group by idkardex) as t1
					on t1.idkardex = k.idkardex
					left outer join kardex_aj as kaj on kaj.idkardex = k.idkardex
					left outer join moneda as m on m.idmoneda = kaj.idmoneda
					left outer join (select idkardex, count(*) as nb from kardex_bien group by idkardex) as t2 on t2.idkardex = k.idkardex
				WHERE   k.idservicio in (select distinct idservicio from asigna_pdt)
					and k.anio = '2014' 
				order by k.idusuario;";
	 
	 $Consulta = $Conn->Query($sql);
	 $bg = "#C0CC7B";
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content" border="1">
	 	<thead class="ui-widget-header">
	 		  <tr class="ui-widget-header">
			    <th scope="col" >IDKARDEX</th>
			    <th scope="col" >ESCRITURA</th>
			    <th scope="col" >CORRELATIVO</th>
			    <th scope="col" >SERVICIO</th>
			    <th scope="col" >FECHA</th>
			    <th scope="col" >NUM PARTI</th>
			    <th scope="col" >MONEDA</th>
			    <th scope="col" >MONTO</th>
			    <th scope="col" >NUM BIENES</th>
			    <th scope="col" >IDUSUARIO</th>
			  </tr>
			  </thead>
			  <tbody>
			  <?php
			  
			 while($row = $Conn->FetchArray($Consulta))
			 {
			 	?>
			  <tr>
			    <td align="center"><?php echo $row[0]; ?></td>
			    <td align="center"><?php echo $row[1] ?></td>
			    <td align="center"><?php echo $row[2] ?></td>
			    <td align="center"><?php echo $row[3] ?></td>
			    <td align="center"><?php echo $row[4] ?></td>
			    <td align="center"><?php echo $row[5] ?></td>
			    <td align="center"><?php echo $row[6] ?></td>
			    <td align="center"><?php echo $row[7] ?></td>
			    <td align="center"><?php echo $row[8] ?></td>
			    <td align="center"><?php echo $usuarios[$row[9]] ?></td>
			  </tr>
			  <?php
				} 			
			?>
	 		</tbody>
	 	</table>	
	 </div>