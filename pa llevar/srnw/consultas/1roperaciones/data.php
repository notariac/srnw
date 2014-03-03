<?php 
	include('../../config.php');
	$sql = "SELECT 	k.idkardex,
					k.correlativo,
					'I' as tipo,
					case kt.abreviatura when 'K' then 
						case s.idservicio when 15 then 2 ELSE 1 end 
					     when 'V' THEN 3 END as tipo_instrumento,
					k.escritura,
					k.fecha as fecha_instrumento,
					1 as conclusion,
					'U' as modalidad,
					1 as noper,
					kp.idparticipante,			
					p.idparticipacion,
					p.tipo as tipo_p,
					p.descripcion,
					cr.idcliente as alque_repre,
					(select y.tipo as participacion_r
					from kardex_participantes as x inner join participacion as y on 
						x.idparticipacion = y.idparticipacion
					where idkardex = k.idkardex and x.idparticipante = cr.idcliente ) as tipo_repre,
					fecfirmae,
					'' as recidencia, -- Falta
					c.idcliente_tipo as tipo_persona,
					d.descripcion as nametipo_doc,
					case c.idcliente_tipo when 1 then d.iddocumento else null end as tipo_doc,
					case c.idcliente_tipo when 1 then c.dni_ruc else '' end as dni,
					substring(c.pais,1,2) as pais_emision,
					case c.idcliente_tipo when 2 then c.dni_ruc else '' end as ruc,
					c.nombres,
					substring(c.pais,1,2) as nacionalidad,
					c.fecha_nac as fecha_nacimiento,
					c.idestado_civil,
					ec.descripcion,
					substring(ec.descripcion,1,1) as estado_civil,
					c.idcargo,
				        case c.idcargo when 999 then c.otro_cargo else '' end as otro_cargo,
					c.idprofesion,
					'' as ciiu, -- Falta
					case c.idprofesion when 999 then c.otra_profesion else pr.descripcion end as ocupacion,
					'' as zona,
					c.partida,
					c.direccion,
					c.idubigeo,
					c.telefonos,
					'N' as conyugue,
					'' as nombre_conyugue,
					f.idforma_pago,
					fp.descripcion as tipo_fondo,
					f.idmoneda,
					fd.idservicio,
				        toper.idtipo_operacion,	
					s.descripcion as servicio,
					case f.credito when 0 then 1 else 2 end as forma_pago,
					'' as oportunidad_pago,
					'' as origen_fondos,
					CASE m.descripcion WHEN 'EURO' THEN 'EUR'
							   WHEN 'DOLAR' THEN 'USD'
							   WHEN 'NUEVO SOL' THEN 'PEN' 
						END as moneda,
					sum(fd.monto) as monto_total,
					'' as porcentaje,
					f.tipo_cambio
				FROM 	kardex as k inner join atencion as a on a.idatencion = k.idatencion
					inner join servicio as s on s.idservicio = k.idservicio
					inner join kardex_tipo as kt on kt.idkardex_tipo = s.idkardex_tipo
					inner join kardex_participantes as kp on kp.idkardex = k.idkardex
					inner join participacion as p on p.idparticipacion = kp.idparticipacion
					inner join cliente as c on c.idcliente = kp.idparticipante
					left outer join cliente_representante as cr on cr.idrepresentante = c.idcliente
					inner join documento as d on d.iddocumento = c.iddocumento
					inner join estado_civil as ec on ec.idestado_civil = c.idestado_civil
					inner join ro.cargo as ca on ca.idcargo = c.idcargo
					left outer join profesion as pr on pr.idprofesion = c.idprofesion
					left outer join facturacion_detalle as fd on fd.correlativo = k.correlativo
					inner join facturacion as f on f.idfacturacion = fd.idfacturacion
					inner join moneda as m on m.idmoneda = f.idmoneda
					inner join forma_pago as fp on fp.idforma_pago = f.idforma_pago
				        inner join ro.tipo_operacion as toper on toper.idtipo_operacion = s.idtipo_operacion
				WHERE  k.fecha between '".$Conn->CodFecha($_GET['fechai'])."' and '".$Conn->CodFecha($_GET['fechaf'])."'
				GROUP BY k.idkardex,
					k.correlativo,
					kt.abreviatura,
					k.escritura,
					k.firmadofecha,	
					kp.idparticipante,			
					p.idparticipacion,
					p.descripcion,
					cr.idcliente,
					k.fecfirmae,
					c.idcliente_tipo,
					d.descripcion,
					c.idcliente_tipo,
					c.pais,
					c.idcliente_tipo,
					c.nombres,		
					c.idestado_civil,
					ec.descripcion,
					ec.descripcion,
					c.idcargo,
					c.idprofesion,
					c.idprofesion,
					c.partida,
					c.direccion,
					c.idubigeo,
					c.telefonos,
					s.idservicio,
					c.dni_ruc,
					c.otra_profesion,
					pr.descripcion,
					f.idmoneda,
					m.descripcion,
					f.idforma_pago,
					fp.descripcion,
					fd.idservicio,
					s.descripcion,
					f.tipo_cambio,
					f.credito,
					d.iddocumento,
					k.fecha,
					p.tipo,
			        c.fecha_nac,
			        c.otro_cargo,
			        toper.idtipo_operacion 
			        ORDER BY k.correlativo";

	 $Consulta = $Conn->Query($sql);
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content">
	 	<thead class="ui-widget-header">
	 		  <tr class="ui-widget-header">
			    <th scope="col">&nbsp;</th>
			    <th colspan="8" scope="col">Datos de Identificacion del Registro de la Operacion</th>
			    <th colspan="6" scope="col">Participación y representación de las personas involucradas en la operación</th>
			    <th colspan="25" scope="col">Datos de identificación de las personas que intervienen en la operación</th>
			    <th colspan="13" scope="col">Datos relacionados con la descripción de la operación<br />
			    (Acto/Contrato extendido en Instrumento Público Notarial Protocolar)</th>
			  </tr>
			  <tr class="ui-widget-header">
			    <th rowspan="2">Codigo Fila</th>
			    <th rowspan="2">N° de la Operacion</th>
			    <th rowspan="2">Tipo de Transaccion</th>
			    <th rowspan="2">Tipo de Instrumento Público Notarial Protocolar</th>
			    <th rowspan="2">N° del Instrumento Público Notarial Protocolar</th>
			    <th rowspan="2">Fecha del Instrumento Público Notarial Protocolar</th>
			    <th rowspan="2">Conclusión del Instrumento Público Notarial Protocolar</th>
			    <th rowspan="2">Modalidad de la operación</th>
			    <th rowspan="2">Número de operaciones que contiene la operación Múltiple</th>
			    <th colspan="3">Roles del Participante</th>
			    <th rowspan="2">Persona a la que se representa</th>
			    <th rowspan="2">Tipo de representación</th>
			    <th rowspan="2">Fecha de la firma del Instrumento Público Notarial Protocolar</th>
			    <th rowspan="2">Condición de residencia (Según lo declarado en el Instrumento Público Notarial Protocolar)</th>
			    <th rowspan="2">Tipo de persona</th>
			    <th colspan="3">Documento de identidad</th>
			    <th rowspan="2">Registro Único de Contribuyente (RUC)</th>
			    <th colspan="3">Nombre completo de la persona</th>
			    <th rowspan="2">País de nacionalidad</th>
			    <th rowspan="2">Fecha de nacimiento</th>
			    <th rowspan="2">Estado civil</th>
			    <th colspan="4">Cargo, ocupación, oficio, profesión, actividad económica  u objeto social</th>
			    <th colspan="2">Inscripción en SUNARP de la Representación </th>
			    <th colspan="3">Domicilio y teléfonos</th>
			    <th rowspan="2">Participación del cónyuge</th>
			    <th colspan="3">Nombre completo del cónyuge</th>
			    <th rowspan="2">Tipo de fondos con que se realizó la operación</th>
			    <th rowspan="2">Tipo de operación</th>
			    <th rowspan="2">Descripción del tipo de operación (en caso de otros)</th>
			    <th rowspan="2">Forma de pago mediante la cual se realizó la operación </th>
			    <th rowspan="2">Oportunidad de pago de la operación</th>
			    <th rowspan="2">Descripción de la oportunidad de pago (en caso de otros)</th>
			    <th rowspan="2">Origen de los fondos involucrados en la operación </th>
			    <th rowspan="2">Moneda en que se realizó la operación</th>
			    <th rowspan="2">Monto de la operación</th>
			    <th rowspan="2">Porcentaje de participación (aplicable sólo para constitución de persona jurídica)</th>
			    <th rowspan="2">Tipo de cambio</th>
			    <th colspan="2">Inscripción en SUNARP del bien materia de la operación </th>
			  </tr>
			  <tr>
			    <td align="center">R</td>
			    <td align="center">O</td>
			    <td align="center">B</td>
			    <td align="center">Tipo</td>
			    <td align="center">Numero</td>
			    <td align="center">Pais de Emision</td>
			    <td align="center">Apellido paterno / Razón social</td>
			    <td align="center">Apellido materno</td>
			    <td align="center">Nombres</td>
			    <td align="center">Código de Cargo</td>
			    <td align="center">Código<br />
			    de Ocupación<br /></td>
			    <td>Código CIIU</td>
			    <td align="center">Descripción<br />
			    (personas  jurídicas y otros)</td>
			    <td align="center">Código de la Zona Registral</td>
			    <td align="center">Número de partida electrónica o ficha registral</td>
			    <td align="center">Tipo, nombre y número de la vía</td>
			    <td align="center">Código de Ubicación Geográfica</td>
			    <td>Teléfonos</td>
			    <td align="center">Apellido paterno</td>
			    <td align="center">Apellido materno</td>
			    <td align="center">Nombres</td>
			    <td align="center">Código de la Zona Registral</td>
			    <td align="center">Número de partida electrónica o ficha registral</td>
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
			    <td align="center"><?php echo $row['tipo']; ?></td>
			    <td align="center"><?php echo $row['tipo_instrumento']; ?></td>
			    <td align="center"><?php echo $row['escritura']; ?></td>
			    <td align="center"><?php echo $Conn->DecFecha($row['fecha_instrumento']) ?></td>
			    <td align="center"><?php echo $row['conclusion']; ?></td>
			    <td align="center"><?php echo $row['modalidad'] ?></td>
			    <td align="center"><?php echo $row['noper'] ?></td>
			    <?php
			    	$r = "";
			    	$o = "";
			    	$b = "";
			    	if($row['idparticipacion']==28) 
			    	{
			    		$r = "R";
                        if($row['tipo_p']==1)
                        {
                            $o = "O";
                            $b = "";
                        }
                        elseif($row['tipo_p']==2)
                        {                                                
                            $o = "";
                            $b = "B";
                        }
			    	}
			    	else 
                        {
                            if($row['tipo_p']==1)
                            {
                                $r = "";
                                $o = "O";
                                $b = "";
                            }
                            elseif($row['tipo_p']==2) 
                            {
                                $r = "";
                                $o = "";
                                $b = "B";
                            }
                        }
    		
			    	
			    ?>
			    <td align="center"><?php echo $r ?></td>
			    <td align="center"><?php echo $o ?></td>
			    <td align="center"><?php echo $b ?></td>
                            <?php 
                                $tipo_repre = "";
                                if($row['tipo_repre']==1)
                                    {                                            
                                            $tipo_repre = "O";
                                    }
                                    elseif($row['tipo_repre']==2) 
                                    {                                            
                                            $tipo_repre = "B";                                            
                                    }
                            ?>
			    <td align="center"><?php echo $tipo_repre; ?></td>
			    <td>&nbsp;</td>
			    <td align="center"><?php echo $Conn->DecFecha($row['fecfirmae']) ?></td>
			    <td align="center"><?php echo $row['recidencia'] ?></td>
			    <td align="center"><?php echo $row['tipo_persona']; ?></td>
			    <td align="center"><?php echo $row['tipo_doc']; ?></td>
			    <td align="center"><?php echo $row['dni']; ?></td>
			    <td align="center"><?php echo $row['pais_emision'];?></td>
			    <td align="center"><?php echo $row['ruc'] ?></td>
			    <?php 
			    $namec = str_replace("!", "", $row['nombres']);
			    if($row['tipo_persona']==2)
			    {
			    	$name = $namec;
			    	$app = "";
			    	$apm = "";
			    }
			    else 
			    {
			    	$str = explode(" ",$namec);
			    	$name = $str[2]." ".$str[3];
			    	$app = $str[0];
			    	$apm = $str[1];
			    }
			    ?>
			    <td><?php echo $name; ?></td>
			    <td><?php echo $app; ?></td>
			    <td><?php echo $apm; ?></td>
			    <td align="center"><?php echo $row['nacionalidad'] ?></td>
			    <td align="center"><?php echo $row['fecha_nacimiento'] ?></td>
			    <td align="center"><?php echo $row['estado_civil'] ?></td>
			    <td align="center"><?php echo $row['idcargo'] ?></td>
			    <td align="center"><?php echo $row['idprofesion'] ?></td>
			    <td align="center">&nbsp;</td>
			    <td align="left"><?php echo $row['ocupacion']; ?></td>
			    <td align="center"><?php echo $row['idzona']; ?></td>
			    <td align="center"><?php echo $row['partida']; ?></td>
			    <td align="left"><?php echo $row['direccion']; ?></td>
			    <td align="center"><?php echo $row['idubigeo']; ?></td>
			    <td align="center"><?php echo $row['telefonos']; ?></td>
			    <td align="center"><?php echo $row['conyugue']; ?></td>
			    <td align="center"><?php echo $row['nombre_conyugue'] ?></td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			    <td align="center"><?php echo str_pad($row['idforma_pago'],2,'0',0); ?></td>
			    <td align="center"><?php echo str_pad($row['idtipo_operacion'],3,'0',0) ?></td>
			    <td align="right"><?php if($row['idtipo_operacion']==99) { echo $row['servicio']; } else { echo "&nbsp;";} ?></td>
			    <td align="center"><?php echo $row['idforma_pago']; ?></td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			    <td align="center">
			   	<?php
			   		if($flag) echo $row['moneda']; 
			   			else "&nbsp;";
			   	?>
			   	</td>
			    <td align="right">
			    <?php 
			    	if($flag) echo number_format($row['monto_total'],2);
		    			else echo "&nbsp;";
			    ?></td>
			    <td>&nbsp;</td>
			    <td align="right">
			    <?php 
			    	if($flag) echo number_format($row['tipo_cambio'],2); 
			    		else echo "&nbsp;";
			    ?></td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			  </tr>
			  <?php
				} 			
			?>
	 		</tbody>
	 	</table>	
	 </div>