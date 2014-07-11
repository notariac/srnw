<?php 
	include('../../config.php');	
	$sql = "SELECT  k.idkardex,
					k.correlativo,
					'I' as c05,
					case kt.abreviatura when 'K' then 
						case s.idservicio 
							when 15 then 'G' 
							when 224 then 'G'
							when 306 then 'G'
						ELSE 'E' end 
					    when 'V' THEN 'T' 
					END as c06,
					k.escritura as c07,
					k.fecha as c08,
					'' as c09, -- New
					'' as c10, -- New
					'C' as c11,
					'U' as c12,
					1 as c13,
					representante.tipo as c14,
					representante.incripcion as c15,
					representante.residencia as c16,
					representante.tipo_documento as c17,
					representante.dni_ruc as c18,			
					representante.ape_paterno as c19,
					representante.ap_materno as c20,
					representante.nombres as c21,
					case representante.nacionalidad when 1 then 'NACIONAL' ELSE 'EXTRANJERO' END as c22,
					representante.fecha_nac as c23,
					representante.estado_civil as c24,
					representante.idprofesion as c25,
					representante.profesion as c26,
					representante.idcargo as c27,
					representante.partida as c28,
					representante.zona_registral as c29,
					representante.direccion as c30,
					representante.departamento as c31,
					representante.provincia as c32,
					representante.distrito as c33,
					representante.telefonos as c34,
					representante.conyuge as c35,
					representante.apepa_conyuge as c36,
					representante.apema_conyuge as c37,
					representante.nombre_conyuge as c38,
					otorgante.c39,
					otorgante.c40,
					otorgante.c41,
					otorgante.c42,
					otorgante.c43,
					otorgante.c44,
					otorgante.c45,
					otorgante.c46,
					otorgante.c47,
					otorgante.c48,
					otorgante.c49,
					otorgante.c50,
					otorgante.c51,
					otorgante.c52,
					otorgante.c53,
					otorgante.c54,
					otorgante.c55,
					otorgante.c56,
					otorgante.c57,
					otorgante.c58,
					otorgante.c59,
					otorgante.c60,
					otorgante.c61,
					otorgante.c62,
					(cast(case otorgante.c63 when '' then '0' when '-' then '0' else otorgante.c63 end as numeric)*k.monto/100) as c63,
					afavor.c64,
					afavor.c65,
					afavor.c66,
					afavor.c67,
					afavor.c68,
					afavor.c69,
					afavor.c70,
					afavor.c71,
					afavor.c72,
					afavor.c73,
					afavor.c74,
					afavor.c75,
					afavor.c76,
					afavor.c77,
					afavor.c78,
					afavor.c79,
					afavor.c80,
					afavor.c81,
					afavor.c82,
					afavor.c83,
					afavor.c84,
					afavor.c85,
					afavor.c86,
					afavor.c87,
					(cast(case afavor.c88 when '' then '0' when '-' then '0' else afavor.c88 end as numeric)*k.monto/100) as c88,
					s.ro as c89,
					coalesce(k.monto,0) as c90,
					case coalesce(dfp.idforma_pago,0) when 0 then '001' else cast(dfp.idforma_pago as varchar) end as c91,
					case coalesce(dfp.montopagado,0) when 0 then coalesce(k.monto,0) else coalesce(dfp.montopagado,0) end as c92,
					k.descripcion as c93,
					1 as c94,
					k.idoportunidad_pago as c95,
					'' as c96,
					k.origen_fondos as c97,
					case m.idmoneda when 3 then 'PEN'
							when 2 then 'USD'
							WHEN 1 then 'EUR'
						else 'O'
					end as c98,
					0 as c99,
					case kb.nropartida when '' then 2 else 1 end as c100,
					kb.nropartida as c101,
					kb.idzona as c102,
					'' as c103
				from kardex as k inner join servicio as s on s.idservicio = k.idservicio
					inner join kardex_tipo as kt on kt.idkardex_tipo = s.idkardex_tipo	
					left outer join moneda as m on m.idmoneda = k.idmoneda
					left outer join 
						(select kp.idkardex,
							kp.idparticipante, 
							r2.tipo,
							1 as incripcion,
							1 as residencia,
							d.ro as tipo_documento,
							c.dni_ruc,			
							c.ape_paterno,
							c.ap_materno ,
							c.nombres,
							c.nacionalidad,
							c.fecha_nac,
							ec.ro as estado_civil,
							prof.idprofesion,
							prof.descripcion as profesion,
							cargo.idcargo,
							kp.partida,
							kp.zona as zona_registral,
							c.direccion,
							case uuu.idubigeo when '000000' then '' else uuu.descripcion end as departamento,
							case uu.idubigeo when '000000' then '' else uu.descripcion end as provincia,
							case u.idubigeo when '000000' then '' else u.descripcion end as distrito,
							c.telefonos,
							'N' as conyuge,
							'' as apepa_conyuge,
							'' as apema_conyuge,
							'' as nombre_conyuge
						 from kardex_participantes as kp inner join cliente as c on c.idcliente = kp.idparticipante
							inner join kardex_participantes as r2 on r2.idparticipante = kp.idrepresentado
							inner join documento as d on d.iddocumento = c.iddocumento
							inner join estado_civil as ec on ec.idestado_civil = c.idestado_civil
							inner join ro.profesion as prof on prof.idprofesion = c.idprofesion
							inner join ro.cargo as cargo on cargo.idcargo = c.idcargo
							inner join cliente as c2 on c2.idcliente = r2.idparticipante
							inner join ubigeo as u on u.idubigeo = c.idubigeo
							inner join ubigeo as uu on uu.idubigeo = substring(c.idubigeo,1,4)||'00'
							inner join ubigeo as uuu on uuu.idubigeo = substring(c.idubigeo,1,2)||'0000'
						 where kp.idrepresentado is not null
						 ) as representante on representante.idkardex = k.idkardex

						 left outer join (
						 select  kp.idkardex,
							kp.idparticipante, 
							1 as c39,
							c.idcliente_tipo as c40,
							d.ro as c41,
							c.dni_ruc as c42,
							c.dni_ruc as c43,	
							c.ape_paterno as c44,
							c.ap_materno as c45,
							c.nombres as c46,
							c.nacionalidad as c47,
							c.fecha_nac as c48,
							ec.ro as c49,
							prof.idprofesion as c50,
							prof.descripcion as c51,
							'' as c52,
							cargo.idcargo as c53,	
							c.direccion as c54,
							case uuu.idubigeo when '000000' then '' else uuu.descripcion end as c55,
							case uu.idubigeo when '000000' then '' else uu.descripcion end as c56,
							case u.idubigeo when '000000' then '' else u.descripcion end as c57,							
							c.telefonos as c58,
							case kp.conyuge when null then 'N'
								else 'S'
							end as c59,	
							c2.ape_paterno as c60,
							c2.ap_materno as c61,
							c2.nombres as c62,
							kp.porcentage as c63
						 from kardex_participantes as kp inner join cliente as c on c.idcliente = kp.idparticipante	
							inner join documento as d on d.iddocumento = c.iddocumento
							inner join estado_civil as ec on ec.idestado_civil = c.idestado_civil
							inner join ro.profesion as prof on prof.idprofesion = c.idprofesion
							inner join ro.cargo as cargo on cargo.idcargo = c.idcargo	
							inner join ubigeo as u on u.idubigeo = c.idubigeo
							inner join ubigeo as uu on uu.idubigeo = substring(c.idubigeo,1,4)||'00'
							inner join ubigeo as uuu on uuu.idubigeo = substring(c.idubigeo,1,2)||'0000'
							left outer join cliente as c2 on c2.idcliente = kp.conyuge
						 where kp.tipo = 1
						 ) as otorgante on otorgante.idkardex = k.idkardex		 
						 left outer join (
						 select  kp.idkardex,
							kp.idparticipante, 
							1 as c64,
							c.idcliente_tipo as c65,
							d.ro as c66,
							c.dni_ruc as c67,
							c.dni_ruc as c68,	
							c.ape_paterno as c69,
							c.ap_materno as c70,
							c.nombres as c71,
							c.nacionalidad as c72,
							c.fecha_nac as c73,
							ec.ro as c74,
							prof.idprofesion as c75,
							prof.descripcion as c76,
							'' as c77,
							cargo.idcargo as c78,	
							c.direccion as c79,
							case uuu.idubigeo when '000000' then '' else uuu.descripcion end as c80,
							case uu.idubigeo when '000000' then '' else uu.descripcion end as c81,
							case u.idubigeo when '000000' then '' else u.descripcion end as c82,							
							c.telefonos as c83,
							case kp.conyuge when null then 'N'
								else 'S'
							end as c84,	
							c2.ape_paterno as c85,
							c2.ap_materno as c86,
							c2.nombres as c87,
							kp.porcentage as c88
						 from kardex_participantes as kp inner join cliente as c on c.idcliente = kp.idparticipante	
							inner join documento as d on d.iddocumento = c.iddocumento
							inner join estado_civil as ec on ec.idestado_civil = c.idestado_civil
							inner join ro.profesion as prof on prof.idprofesion = c.idprofesion
							inner join ro.cargo as cargo on cargo.idcargo = c.idcargo	
							inner join ubigeo as u on u.idubigeo = c.idubigeo
							inner join ubigeo as uu on uu.idubigeo = substring(c.idubigeo,1,4)||'00'
							inner join ubigeo as uuu on uuu.idubigeo = substring(c.idubigeo,1,2)||'0000'
							left outer join cliente as c2 on c2.idcliente = kp.conyuge
						 where kp.tipo = 2
						 ) as afavor on afavor.idkardex = k.idkardex						 
						 left outer join kardex_bien as kb on kb.idkardex = k.idkardex
						 left outer join detalle_forma_pago as dfp on dfp.idkardex = k.idkardex
				WHERE 
						kt.abreviatura in ('K','V')					    
						and k.fecha between '".$Conn->CodFecha($_GET['fechai'])."' and '".$Conn->CodFecha($_GET['fechaf'])."'
				ORDER BY k.idkardex, k.fecha";	 
	 //echo "k.fecha between '".$Conn->CodFecha($_GET['fechai'])."' and '".$Conn->CodFecha($_GET['fechaf'])."'";
	 $Consulta = $Conn->Query($sql);
	 $n = $Conn->NroRegistros($Consulta);
	 
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content">
	 	<thead class="ui-widget-header">
	 		  <tr class="ui-widget-header">
			    <th scope="col" bgcolor="#CCCCCC">&nbsp;</th>
			    <th colspan="10" scope="col" bgcolor="#DADADA">Datos de Identificacion del Registro de la Operacion</th>
			    <th colspan="25" scope="col" bgcolor="#CCCCCC">Datos de identificacion de las personas que realizan la operacion
			    							en calidad de representante (Solo personas naturales)</th>
			    <th colspan="25" scope="col" bgcolor="#DADADA">Datos de Identificacion de las personas en cuyos nombres se realiza 
			    							la operacion.</th>			    
			    <th colspan="25" scope="col" bgcolor="#CCCCCC">Datos de Identificacion de las personas a favor de quienes se realiza la operacion</th>
			    <th colspan="15" scope="col" bgcolor="#dadada">Datos Relacionados a la descripcion de la operacion</th>
			  </tr>
			  <tr class="ui-widget-header" >
			    <th rowspan="2" style="border-right:2px solid #CCC">Codigo Fila</th> <!-- c03 -->
			    <th rowspan="2">N° de la Operacion</th> <!-- c04 -->
			    <th rowspan="2">Tipo de Envio</th> <!-- c05 -->
			    <th rowspan="2">Tipo de Instrumento Público Notarial Protocolar</th> <!-- c06 -->
			    <th rowspan="2">N° del Instrumento Público Notarial Protocolar</th> <!-- c07 -->
			    <th rowspan="2">Fecha del Instrumento Público Notarial Protocolar</th> <!-- c08 -->
			    <th rowspan="2">Fecha del Instrumento Público Notarial Protocolar Aclaratorio</th> <!-- c09 -->
			    <th rowspan="2">Fecha del Instrumento Público Notarial Protocolar Aclaratorio</th> <!-- c10 -->
			    <th rowspan="2">Conclusión del Instrumento Público Notarial Protocolar</th> <!-- c11 -->
			    <th rowspan="2">Modalidad de la operación</th><!-- c12 -->
			    <th rowspan="2" style="border-right:2px solid #CCC">Número de operaciones que contiene la operación Múltiple</th><!-- c13 -->
			    
			    <th rowspan="2" >Tipo de Participacion</th>
			    <th rowspan="2">Inscripcion Registral</th>
			    <th rowspan="2">Condicion de Residencia</th>
			    <th rowspan="2">Tipo de Documento</th>
			    <th rowspan="2">Numero de Documento</th>			    
			    <th rowspan="2">Apellido Paterno</th>
			    <th rowspan="2">Apellido Materno</th>
			    <th rowspan="2">Nombres</th>
			    <th rowspan="2">Nacionalidad</th>
			    <th rowspan="2">Fecha Nacimiento</th>
			    <th rowspan="2">Estado Civil</th>
			    <th rowspan="2">Ocupacion</th>
			    <th rowspan="2">Otra Ocupacion</th>
			    <th rowspan="2">Cargo</th>
			    <th rowspan="2">Codigo de Partida Registral</th>
			    <th rowspan="2">Numero de Zona Registral</th>
			    <th rowspan="2">Direccion</th>
			    <th rowspan="2">Departamento</th>
			    <th rowspan="2">Provincia</th>
			    <th rowspan="2">Distrito</th>
			    <th rowspan="2">Telefono</th>
			    <th rowspan="2">¿Conyuge?</th>
			    <th rowspan="2">Apellidos Paterno de Conyuge</th>
			    <th rowspan="2">Apellidos Materno de Conyuge</th>
			    <th rowspan="2" style="border-right:2px solid #CCC">Nombres de Conyuge</th>


			    <th rowspan="2">Condicion de Residencia</th>
			    <th rowspan="2">Tipo de Persona</th>			    
			    <th rowspan="2">Tipo de Documento</th>
			    <th rowspan="2">Numero de Documento</th>
			    <th rowspan="2">Numero de RUC</th>
			    <th rowspan="2">Apellido Paterno o Razon Social</th>
			    <th rowspan="2">Apellido Materno</th>
			    <th rowspan="2">Nombres</th>
			    <th rowspan="2">Nacionalidad</th>
			    <th rowspan="2">Fecha Nacimiento</th>
			    <th rowspan="2">Estado Civil</th>
			    <th rowspan="2">Ocupacion</th>
			    <th rowspan="2">Otra Ocupacion</th>
			    <th rowspan="2">Codigo CIIU de Ocupacion</th>
			    <th rowspan="2">Cargo</th>			    
			    <th rowspan="2">Direccion</th>
			    <th rowspan="2">Departamento</th>
			    <th rowspan="2">Provincia</th>
			    <th rowspan="2">Distrito</th>
			    <th rowspan="2">Telefono</th>
			    <th rowspan="2">¿Conyuge?</th>
			    <th rowspan="2">Apellidos Paterno de Conyuge</th>
			    <th rowspan="2">Apellidos Materno de Conyuge</th>
			    <th rowspan="2">Nombres de Conyuge</th>			    
			    <th rowspan="2">Monto de Participacion S/.</th>


			    <th rowspan="2">Condicion de Residencia</th> <!-- c64 -->
			    <th rowspan="2">Tipo de Persona</th>			    
			    <th rowspan="2">Tipo de Documento</th>
			    <th rowspan="2">Numero de Documento</th>
			    <th rowspan="2">Numero de RUC</th>
			    <th rowspan="2">Apellido Paterno o Razon Social</th>
			    <th rowspan="2">Apellido Materno</th>
			    <th rowspan="2">Nombres</th>
			    <th rowspan="2">Nacionalidad</th>
			    <th rowspan="2">Fecha Nacimiento</th>
			    <th rowspan="2">Estado Civil</th>
			    <th rowspan="2">Ocupacion</th>
			    <th rowspan="2">Otra Ocupacion</th>
			    <th rowspan="2">Codigo CIIU de Ocupacion</th>
			    <th rowspan="2">Cargo</th>			    
			    <th rowspan="2">Direccion</th>
			    <th rowspan="2">Departamento</th>
			    <th rowspan="2">Provincia</th>
			    <th rowspan="2">Distrito</th>
			    <th rowspan="2">Telefono</th>
			    <th rowspan="2">¿Conyuge?</th>
			    <th rowspan="2">Apellidos Paterno de Conyuge</th>
			    <th rowspan="2">Apellidos Materno de Conyuge</th>
			    <th rowspan="2">Nombres de Conyuge</th>			    
			    <th rowspan="2">Monto de Participacion S/.</th>

			    <th rowspan="2">Tipo de Operacion</th>
			    <th rowspan="2">Monto total de la Operacion</th>
			    <th rowspan="2">Tipo de Fondos</th>
			    <th rowspan="2">Montos Relacionados</th>
			    <th rowspan="2">Descripcion del Bien Materia</th>
			    <th rowspan="2">Forma de Pago</th>
			    <th rowspan="2">Oportunidad de Pago</th>
			    <th rowspan="2">Otras Oportunidades de Pagos</th>
			    <th rowspan="2">Origen de Fondos</th>
			    <th rowspan="2">Moneda</th>
			    <th rowspan="2">Tipo de Cambio</th>
			    <th rowspan="2">Incripcion Registral</th>
			    <th rowspan="2">Numero de Partida Registral</th>
			    <th rowspan="2">Codigo de Zona Registral</th>
			    <th rowspan="2">Otros Registros</th>

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
			    <td align="center"><?php echo $c; ?></td>
			    <td align="center"><?php echo str_pad($nro,8,'0',STR_PAD_LEFT); ?></td>
			    <td align="center"><?php echo $row['c05']; ?></td>
			    <td align="center"><?php echo $row['c06']; ?></td>
			    <td align="center"><?php echo $row['c07']; ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['c08']) ?></td>
			    <td align="center"><?php echo $row['c09']; ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['c10']) ?></td>
			    <td align="center"><?php echo $row['c11'] ?></td>
			    <td align="center"><?php echo $row['c12'] ?></td>
			    <td align="center"><?php echo $row['c13'] ?></td>

			    <!-- Datos de identificacion de las personas que realizan la operacion
			    	 en calidad de representante (Solo personas naturales) -->
			    <td align="center"><?php echo $row['c14'] ?></td>
			    <td align="center"><?php echo $row['c15'] ?></td>
			    <td align="center"><?php echo $row['c16'] ?></td>
			    <td align="center"><?php echo $row['c17'] ?></td>
			    <td align="center"><?php echo $row['c18'] ?></td>

			    <?php 
			    	$nombre = $row['c21'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c19'] = $allname[2];
			    		$row['c20'] = $allname[3];
			    		$row['c21'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c19'] = $allname[1];
			    		$row['c20'] = $allname[2];
			    		$row['c21'] = $allname[0];
			    	}
			    ?>

			    <td align="center"><?php echo $row['c19'] ?></td>
			    <td align="center"><?php echo $row['c20'] ?></td>
			    <td align="center"><?php echo $row['c21'] ?></td>
			    <td align="center"><?php echo $row['c22'] ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['c23']) ?></td>
			    <td align="center"><?php echo $row['c24'] ?></td>
			    <td align="center"><?php echo $row['c25'] ?></td>
			    <td align="center"><?php echo $row['c26'] ?></td>
			    <td align="center"><?php echo $row['c27'] ?></td>
			    <td align="center"><?php echo $row['c28'] ?></td>
			    <td align="center"><?php echo $row['c29'] ?></td>
			    <td align="center"><?php echo $row['c30'] ?></td>
			    <td align="center"><?php echo $row['c31'] ?></td>
			    <td align="center"><?php echo $row['c32'] ?></td>
			    <td align="center"><?php echo $row['c33'] ?></td>
			    <td align="center"><?php echo $row['c34'] ?></td>
			    <td align="center"><?php echo $row['c35'] ?></td>
			    <?php 
			    	$nombre = $row['c38'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c36'] = $allname[2];
			    		$row['c37'] = $allname[3];
			    		$row['c38'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c36'] = $allname[1];
			    		$row['c37'] = $allname[2];
			    		$row['c38'] = $allname[0];
			    	}
			    ?>
			    <td align="center"><?php echo $row['c36'] ?></td>
			    <td align="center"><?php echo $row['c37'] ?></td>
			    <td align="center"><?php echo $row['c38'] ?></td>

			    <td align="center"><?php echo $row['c39'] ?></td>
			    <td align="center"><?php echo $row['c40'] ?></td>
			    <td align="center"><?php echo $row['c41'] ?></td>
			    <td align="center"><?php echo $row['c42'] ?></td>
			    <td align="center"><?php echo $row['c43'] ?></td>
			    <?php 
			    	$nombre = $row['c46'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c44'] = $allname[2];
			    		$row['c45'] = $allname[3];
			    		$row['c46'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c44'] = $allname[1];
			    		$row['c45'] = $allname[2];
			    		$row['c46'] = $allname[0];
			    	}
			    ?>
			    <td align="center"><?php echo $row['c44'] ?></td>
			    <td align="center"><?php echo $row['c45'] ?></td>
			    <td align="center"><?php echo $row['c46'] ?></td>
			    <td align="center"><?php echo $row['c47'] ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['c48']) ?></td>
			    <td align="center"><?php echo $row['c49'] ?></td>
			    <td align="center"><?php echo $row['c50'] ?></td>
			    <td align="center"><?php echo $row['c51'] ?></td>
			    <td align="center"><?php echo $row['c52'] ?></td>
			    <td align="center"><?php echo $row['c53'] ?></td>
			    <td align="center"><?php echo $row['c54'] ?></td>
			    <td align="center"><?php echo $row['c55'] ?></td>
			    <td align="center"><?php echo $row['c56'] ?></td>
			    <td align="center"><?php echo $row['c57'] ?></td>
			    <td align="center"><?php echo $row['c58'] ?></td>
			    <td align="center"><?php echo $row['c59'] ?></td>
			    <?php 
			    	$nombre = $row['c62'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c60'] = $allname[2];
			    		$row['c61'] = $allname[3];
			    		$row['c62'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c60'] = $allname[1];
			    		$row['c61'] = $allname[2];
			    		$row['c62'] = $allname[0];
			    	}
			    ?>
			    <td align="center"><?php echo $row['c60'] ?></td>
			    <td align="center"><?php echo $row['c61'] ?></td>
			    <td align="center"><?php echo $row['c62'] ?></td>
			    <td align="center"><?php echo number_format($row['c63'],2) ?></td>


			    <td align="center"><?php echo $row['c64'] ?></td>
			    <td align="center"><?php echo $row['c65'] ?></td>
			    <td align="center"><?php echo $row['c66'] ?></td>
			    <td align="center"><?php echo $row['c67'] ?></td>
			    <td align="center"><?php echo $row['c68'] ?></td>
			    <?php 
			    	$nombre = $row['c71'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c69'] = $allname[2];
			    		$row['c70'] = $allname[3];
			    		$row['c71'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c69'] = $allname[1];
			    		$row['c70'] = $allname[2];
			    		$row['c71'] = $allname[0];
			    	}
			    ?>
			    <td align="center"><?php echo $row['c69'] ?></td>
			    <td align="center"><?php echo $row['c70'] ?></td>
			    <td align="center"><?php echo $row['c71'] ?></td>
			    <td align="center"><?php echo $row['c72'] ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['c73']) ?></td>
			    <td align="center"><?php echo $row['c74'] ?></td>
			    <td align="center"><?php echo $row['c75'] ?></td>
			    <td align="center"><?php echo $row['c76'] ?></td>
			    <td align="center"><?php echo $row['c77'] ?></td>
			    <td align="center"><?php echo $row['c78'] ?></td>
			    <td align="center"><?php echo $row['c79'] ?></td>
			    <td align="center"><?php echo $row['c80'] ?></td>
			    <td align="center"><?php echo $row['c81'] ?></td>
			    <td align="center"><?php echo $row['c82'] ?></td>
			    <td align="center"><?php echo $row['c83'] ?></td>
			    <td align="center"><?php echo $row['c84'] ?></td>
			    <?php 
			    	$nombre = $row['c87'];
			    	$allname = explode(" ", $nombre);
			    	$n = count($allname);
			    	if($n>3)
			    	{			    		
			    		$row['c85'] = $allname[2];
			    		$row['c86'] = $allname[3];
			    		$row['c87'] = $allname[0]." ".$allname[1];
			    	}
			    	elseif($n>2)
			    	{
			    		$row['c85'] = $allname[1];
			    		$row['c86'] = $allname[2];
			    		$row['c87'] = $allname[0];
			    	}
			    ?>
			    <td align="center"><?php echo $row['c85'] ?></td>
			    <td align="center"><?php echo $row['c86'] ?></td>
			    <td align="center"><?php echo $row['c87'] ?></td>
			    <td align="center"><?php echo number_format($row['c88'],2) ?></td>

			    <td align="center"><?php echo $row['c89'] ?></td>
			    <td align="center"><?php echo $row['c90'] ?></td>
			    <td align="center"><?php echo $row['c91'] ?></td>
			    <td align="center"><?php echo number_format($row['c92'],2) ?></td>
			    <td align="center"><?php echo $row['c93'] ?></td>
			    <td align="center"><?php echo $row['c94'] ?></td>
			    <td align="center"><?php echo $row['c95'] ?></td>
			    <td align="center"><?php echo $row['c96'] ?></td>
			    <td align="center"><?php echo $row['c97'] ?></td>
			    <td align="center"><?php echo $row['c98'] ?></td>
			    <td align="center"><?php echo $row['c99'] ?></td>
			    <td align="center"><?php echo $row['c100'] ?></td>
			    <td align="center"><?php echo $row['c101'] ?></td>
			    <td align="center"><?php echo $row['c102'] ?></td>
			    <td align="center"><?php echo $row['c103'] ?></td>    
			  </tr>
			  <?php
				}
			?>
	 		</tbody>
	 	</table>	
	 </div>