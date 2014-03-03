<?php 
	header("Content-type: application/vnd.ms-excel; name='excel'");  
    header("Content-Disposition: filename=REPORTE_".date('Y-m-d-h-m-i-s').".xls");  
    header("Pragma: no-cache");  
    header("Expires: 0");
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
	 $bg = "#C0CC7B";
	 ?>
	 <div class="contain">
	 <table class="ui-widget-content" border="1">
	 	<thead class="ui-widget-header">
	 		  <tr class="ui-widget-header">
			    <th scope="col" bgcolor="<?php echo $bg; ?>" >&nbsp;</th>
			    <th colspan="8" scope="col" bgcolor="<?php echo $bg; ?>">Datos de Identificacion del Registro de la Operacion</th>
			    <th colspan="6" scope="col" bgcolor="<?php echo $bg; ?>">Participaci&oacute;n y representaci&oacute;n de las personas involucradas en la operaci&oacute;n</th>
			    <th colspan="25" scope="col" bgcolor="<?php echo $bg; ?>">Datos de identificaci&oacute;n de las personas que intervienen en la operaci&oacute;n</th>
			    <th colspan="13" scope="col" bgcolor="#C0CC7B">Datos relacionados con la descripción de la operaci&oacute;n<br />
			    (Acto/Contrato extendido en Instrumento P&uacute;blico Notarial Protocolar)</th>
			  </tr>
			  <?php $w = 75; ?>
			  <tr class="ui-widget-header">
			    <th width="50" rowspan="2" bgcolor="<?php echo $bg; ?>">Codigo Fila</th>
			    <th width="71" rowspan="2" bgcolor="<?php echo $bg; ?>">Nro de la Operacion</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Tipo de Transaccion</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Tipo de Instrumento P&uacute;blico Notarial Protocolar</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Nro del Instrumento P&uacute;blico Notarial Protocolar</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Fecha del Instrumento P&uacute;blico Notarial Protocolar</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Conclusi&oacute;n del Instrumento P&uacute;blico Notarial Protocolar</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Modalidad de la operaci&oacute;n</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">N&uacute;mero de operaciones que contiene la operaci&oacute;n M&uacute;ltiple</th>
			    <th width="<?php echo $w; ?>" colspan="3" bgcolor="<?php echo $bg; ?>">Roles del Participante</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Persona a la que se representa</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Tipo de representaci&oacute;n</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Fecha de la firma del Instrumento P&uacute;blico Notarial Protocolar</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Condici&oacute;n de residencia (Seg&uacute;n lo declarado en el Instrumento P&uacute;blico Notarial Protocolar)</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Tipo de persona</th>
			    <th width="<?php echo 200; ?>" colspan="3" bgcolor="<?php echo $bg; ?>">Documento de identidad</th>
			    <th width="<?php echo $w+9; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Registro &Uacute;nico de Contribuyente (RUC)</th>
			    <th width="<?php echo 500; ?>" colspan="3" bgcolor="<?php echo $bg; ?>">Nombre completo de la persona</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Pa&iacute;s de nacionalidad</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Fecha de nacimiento</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Estado civil</th>
			    <th width="<?php echo 525; ?>" colspan="4" bgcolor="<?php echo $bg; ?>">Cargo, ocupaci&oacute;n, oficio, profesi&oacute;n, actividad econ&oacute;mica  u objeto social</th>
			    <th width="300" colspan="2" bgcolor="<?php echo $bg; ?>">Inscripci&oacute;n en SUNARP de la Representaci&oacute;n </th>
			    <th width="500" colspan="3" bgcolor="<?php echo $bg; ?>">Domicilio y tel&eacute;fonos</th>
			    <th width="<?php echo $w; ?>" rowspan="2" bgcolor="<?php echo $bg; ?>">Participaci&oacute;n del c&oacute;nyuge</th>
			    <th colspan="3" bgcolor="<?php echo $bg; ?>">Nombre completo del c&oacute;nyuge</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Tipo de fondos con que se realiz&oacute; la operaci&oacute;n</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Tipo de operaci&oacute;n</th>
			    <th rowspan="2" width="220" bgcolor="<?php echo $bg; ?>">Descripci&oacute;n del tipo de operaci&oacute;n (en caso de otros)</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Forma de pago mediante la cual se realiz&oacute; la operaci&oacute;n </th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Oportunidad de pago de la operaci&oacute;n</th>
			    <th rowspan="2" width="220" bgcolor="<?php echo $bg; ?>">Descripci&oacute;n de la oportunidad de pago (en caso de otros)</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Origen de los fondos involucrados en la operaci&oacute;n </th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>" >Moneda en que se realiz&oacute; la operaci&oacute;n</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Monto de la operaci&oacute;n</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Porcentaje de participaci&oacute;n (aplicable s&oacute;lo para constituci&oacute;n de persona jur&iacute;dica)</th>
			    <th rowspan="2" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">Tipo de cambio</th>
			    <th colspan="2" width="<?php echo 234; ?>" bgcolor="<?php echo $bg; ?>">Inscripci&oacute;n en SUNARP del bien materia de la operaci&oacute;n </th>
			  </tr>
			  <tr>
			    <td align="center" bgcolor="<?php echo $bg; ?>">R</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">O</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">B</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Tipo</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Numero</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Pais de Emision</td>
			    <td align="center" width="200" bgcolor="<?php echo $bg; ?>">Apellido paterno / Raz&oacute;n social</td>
			    <td align="center" width="150" bgcolor="<?php echo $bg; ?>">Apellido materno</td>
			    <td align="center" width="150" bgcolor="<?php echo $bg; ?>">Nombres</td>
			    <td width="<?php echo $w; ?>" align="center" bgcolor="<?php echo $bg; ?>">C&oacute;digo de Cargo</td>
			    <td width="<?php echo $w; ?>" align="center" bgcolor="<?php echo $bg; ?>">C&oacute;digo<br />
			    de Ocupaci&oacute;n<br /></td>
			    <td width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>" >C&oacute;digo CIIU</td>
			    <td align="center" width="350" bgcolor="<?php echo $bg; ?>">Descripci&oacute;n 
			    (personas  jur&iacute;dicas y otros)</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">C&oacute;digo de la Zona Registral</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">N&uacute;mero de partida electr&oacute;nica o ficha registral</td>
			    <td align="center" width="400" bgcolor="<?php echo $bg; ?>">Tipo, nombre y n&uacute;mero de la v&iacute;a</td>
			    <td align="center" width="<?php echo $w; ?>" bgcolor="<?php echo $bg; ?>">C&oacute;digo de Ubicaci&oacute;n Geogr&aacute;fica</td>
			    <td width="<?php echo 100; ?>" bgcolor="<?php echo $bg; ?>">Tel&eacute;fonos</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Apellido paterno</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Apellido materno</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">Nombres</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">C&oacute;digo de la Zona Registral</td>
			    <td align="center" bgcolor="<?php echo $bg; ?>">N&uacute;mero de partida electr&oacute;nica o ficha registral</td>
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
			    <td align="center" style='mso-number-format:"@"'><?php echo str_pad($nro,8,'0',STR_PAD_LEFT); ?></td>
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
			    <td align="center" style='mso-number-format:"@"'><?php echo $row['dni']; ?></td>
			    <td align="center"><?php echo $row['pais_emision'];?></td>
			    <td align="center" style='mso-number-format:"@"'><?php echo $row['ruc'] ?></td>
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
			    <td align="center" style='mso-number-format:"@"'><?php echo str_pad($row['idforma_pago'],2,'0',0); ?></td>
			    <td align="center" style='mso-number-format:"@"'><?php echo str_pad($row['idtipo_operacion'],3,'0',0) ?></td>
			    <td align="right"><?php if($row['idtipo_operacion']==99) { echo $row['servicio']; } else { echo "&nbsp;";} ?></td>
			    <td align="center"><?php echo $row['credito']; ?></td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			    <td align="center">
			   	<?php
			   		if($flag) echo $row['moneda']; 
			   			else "&nbsp;";
			   	?>
			   	</td>
			    <td align="right" style='mso-number-format:"0.00"'>
			    <?php 
			    	if($flag) echo number_format($row['monto_total'],2);
		    			else echo "&nbsp;";
			    ?></td>
			    <td>&nbsp;</td>
			    <td align="right" style='mso-number-format:"0.00"'>
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