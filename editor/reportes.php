<?php
include_once("Main.php");
class reportes extends Main
{    
    
    function data_cumpleanos($g)
    {
        $sql = "SELECT concat(e.idempleado,' - ',e.nombre,' ',e.apellidos) as empleado,
				        te.descripcion as tipo,
                                        o.descripcion as oficina,
                                        e.fecha_nacimiento
				FROM empleado as e inner join tipo_empleado as te on e.idtipo_empleado = te.idtipo_empleado
                                     inner join oficina as o on e.idoficina = o.idoficina
				WHERE month(e.fecha_nacimiento)=:mes
				ORDER by e.fecha_nacimiento";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':mes',$g,PDO::PARAM_STR);
        
    	$stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_fec_ven_rev($g)
    {
        $sql = "SELECT concat(propietario.idempleado,' - ',propietario.nombre,' ',propietario.apellidos) as propietario,
				        concat(v.marca,' - ',v.modelo,' - ',v.placa) as vehiculo,
				        v.fec_ven_rev as fecha
				FROM vehiculo as v inner join empleado as propietario on propietario.idempleado = v.idpropietario
				WHERE month(v.fec_ven_rev)=:mes and propietario.idtipo_empleado = 3
				ORDER by v.fec_ven_rev";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':mes',$g,PDO::PARAM_STR);
    	$stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_fec_ven_soat($g)
    {
        $sql = "SELECT concat(propietario.idempleado,' - ',propietario.nombre,' ',propietario.apellidos) as propietario,
				        concat(v.marca,' - ',v.modelo,' - ',v.placa) as vehiculo,
				        v.fec_ven_soat as fecha
				FROM vehiculo as v inner join empleado as propietario on propietario.idempleado = v.idpropietario
				WHERE month(v.fec_ven_soat)=:mes and propietario.idtipo_empleado = 3
				ORDER by v.fec_ven_soat";
		    $stmt = $this->db->prepare($sql);
		    $stmt->bindParam(':mes',$g,PDO::PARAM_STR);
    	  $stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
    function data_ingresos($g)
    {
        $sql = "SELECT cm.descripcion as concepto,        
				        case tipo_ingreso when 1 then concat(coalesce(propietario.nombre,' '),' ',coalesce(propietario.apellidos,' '))
                                            else pro.razonsocial end   as recibi,
				        m.chofer,
				        m.placa,
				        m.fecha,
				        m.observacion,
				        md.cantidad*md.monto as total
				FROM movimiento as m inner join movimiento_detalle as md on m.idmovimiento = md.idmovimiento
				        inner join concepto_movimiento as cm on cm.idconcepto_movimiento = md.idconcepto_movimiento                                        
                left outer join proveedor as pro on pro.idproveedor = m.idproveedor
				        left join empleado as propietario on propietario.idempleado = m.idpropietario and propietario.idtipo_empleado = 3                                        
				WHERE m.tipo = 1 AND m.estado = 1 and m.fecha between :f1 and :f2 and m.idoficina = ".$_SESSION['idoficina']."
              and m.serie is not null
				ORDER by m.idmovimiento desc";

        $fechai = $this->fdate($g['fechai'],'EN');
        $fechaf = $this->fdate($g['fechaf'],'EN');
		    $stmt = $this->db->prepare($sql);
		    $stmt->bindParam(':f1',$fechai,PDO::PARAM_STR);
        $stmt->bindParam(':f2',$fechaf,PDO::PARAM_STR);
    	  $stmt->execute();
       	$r2 = $stmt->fetchAll();        
        return array($r2);
    }
    
   function data_egresos($g)
   {
       $sql = "SELECT cm.descripcion as concepto, concat(p.ruc,'-',p.razonsocial) as proveedor, 
                      m.fecha, m.observacion, md.cantidad*md.monto as monto
               FROM movimiento as m inner join movimiento_detalle as md
                    on m.idmovimiento = md.idmovimiento inner join  proveedor as p 
                    on p.idproveedor = m.idproveedor inner join concepto_movimiento as cm
                    on cm.idconcepto_movimiento = md.idconcepto_movimiento 
               WHERE m.tipo = 2 and m.estado = 1 and m.fecha between :f1 and :f2
                and m.idoficina = ".$_SESSION['idoficina']."
                and m.serie is not null
               ORDER BY m.fecha";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':f1',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':f2',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($r2);die;
       return array($r2);
   }
    function data_ventas($g)
   {
       $sql = "SELECT
				venta.fecha,
				venta.hora,
				tipo_documento.descripcion,
				venta.serie,
				venta.numero,
				pasajero.nombre,
				sum(venta_detalle.cantidad *  venta_detalle.precio) as total,
        venta.idventa
			   FROM
				venta
				Inner Join venta_detalle ON venta.idventa = venta_detalle.idventa
				Inner Join pasajero ON pasajero.idpasajero = venta.idpasajero
				Inner Join tipo_documento ON tipo_documento.idtipo_documento = venta.idtipo_documento
				WHERE venta.estado=1 and venta.fecha between :p2 and :p3
              and v.idoficina = ".$_SESSION['idoficina']."
				GROUP BY 
				venta.fecha,
				venta.hora,
				tipo_documento.descripcion,
				venta.serie,
				venta.numero,
				pasajero.nombre,
				venta.idventa";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($r2);die;
       return array($r2);
      }
   
      function data_envio($g)
      {

       $sql = "SELECT   concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)) as fecha,
                        e.hora,
                        concat(chofer.nombre,' ',coalesce(chofer.apellidos,'')) as chofer,
                        v.placa as vechiulos,
                        case remitente.nrodocumento when '00000000' then e.remitente else remitente.nombre end,
                        e.consignado,
                        e.numero,
                        case e.cpago when 0 then e.monto_caja else 0 end as total,
                        e.cpago,
                        0 as tipo,
                        d.descripcion as destino
                        from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente                                              
                            inner join empleado as em on e.idempleado = em.idempleado and em.idtipo_empleado = 1
                            INNER JOIN destino as d on d.iddestino = e.iddestino
                            left outer join envio_salidas as es on es.idenvio = e.idenvio 
                            left outer join salida as s on s.idsalida = es.idsalida
                            left outer join vehiculo as v on v.idvehiculo = s.idvehiculo
                            left outer join empleado as chofer on chofer.idempleado = s.idchofer and chofer.idtipo_empleado = 2                             
			         WHERE    e.tipo_pro = 1 and e.estado <> 0 and  e.fecha between :p2 and :p3 and es.idoficina = ".$_SESSION['idoficina']; 

        $sql_2 = "SELECT   concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)) as fecha,
                                e.hora,
                                chofer.nombre as chofer,
                                v.placa as vechiulos,
                                case remitente.nrodocumento when '00000000' then e.remitente else remitente.nombre end,
                                e.consignado,
                                e.numero ,
                                case e.cpago when 0 then 0 else e.monto_caja end as total,
                                e.cpago,
                                1 as tipo,
                                e.direccion as destino
                                from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente                                                      
                                    inner join empleado as em on e.idempleado = em.idempleado and em.idtipo_empleado = 1
                                    INNER JOIN destino as d on d.iddestino = e.iddestino
                                    left outer join envio_salidas as es on es.idenvio = e.idenvio 
                                    left outer join salida as s on s.idsalida = es.idsalida
                                    left outer join vehiculo as v on v.idvehiculo = s.idvehiculo
                                    left outer join empleado as chofer on chofer.idempleado = s.idchofer and chofer.idtipo_empleado = 2                             
                WHERE e.tipo_pro = 1 and  e.fecha between :p2 and :p3 and e.iddestino = ".$_SESSION['idsucursal']." and e.estado = 3 ";

        $sql_union .= " UNION ALL ";

        $sqlw="";        
        switch ($g['filtro']) 
                {
                  case 0: $sql = $sql.$sql_union.$sql_2;
                          break;
                  case 1: $sql = $sql;
                          break;
                  case 2: $sqlw = " and e.cpago = 1 "; 
                          $sql = $sql.$sqlw;
                          break;
                  case 3: $sqlw = " and e.adomicilio = 1 "; 
                          $sql = $sql.$sqlw;
                          break;
                  case 4: $sql = $sql_2;                           
                          break;                    
                  case 5: $sqlw = " and e.cpago = 1 "; 
                          $sql = $sql_2.$sqlw;
                          break;                    
                  case 6: $sqlw = " and e.adomicilio = 1 "; 
                          $sql = $sql_2.$sqlw;
                          break;
                  default: break;
                } 
       //$sql .= " ORDER BY e.idenvio ";
       //echo $sql;
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($g['fechai']);die;
       return array($r2);
   }
         function data_telegiro($g)
      {

       $sql = "SELECT   concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)) as fecha,
                        e.hora,
                        concat(chofer.nombre,' ',coalesce(chofer.apellidos,'')) as chofer,
                        v.placa as vechiulos,
                        case remitente.nrodocumento when '00000000' then e.remitente else remitente.nombre end,
                        e.consignado,
                        e.numero ,
                        case e.cpago when 0 then e.monto_caja else 0 end as total,
                        e.cpago,
                        0 as tipo,
                        d.descripcion as destino
                        
                        from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente                                              
                            inner join empleado as em on e.idempleado = em.idempleado and em.idtipo_empleado = 1
                            INNER JOIN destino as d on d.iddestino = e.iddestino
                            left outer join salida as s on s.idsalida = e.idsalida
                            left outer join vehiculo as v on v.idvehiculo = s.idvehiculo
                            left outer join empleado as chofer on chofer.idempleado = s.idchofer and chofer.idtipo_empleado = 2                             
        WHERE e.tipo_pro =2  and e.estado <> 0 and  e.fecha between :p2 and :p3 and e.idoficina = ".$_SESSION['idoficina']; 

        $sql_2 = "SELECT   concat(substring(e.fecha,9,2),'/',substring(e.fecha,6,2),'/',substring(e.fecha,1,4)) as fecha,
                                e.hora,
                                chofer.nombre as chofer,
                                v.placa as vechiulos,
                                case remitente.nrodocumento when '00000000' then e.remitente else remitente.nombre end,
                                e.consignado,
                                e.numero ,
                                case e.cpago when 0 then 0 else e.monto_caja end as total,
                                e.cpago,
                                1 as tipo,
                                e.direccion as destino

                                from envio as e inner join pasajero as remitente on remitente.idpasajero = e.idremitente                                                      
                                    inner join empleado as em on e.idempleado = em.idempleado and em.idtipo_empleado = 1
                                    INNER JOIN destino as d on d.iddestino = e.iddestino
                                    left outer join salida as s on s.idsalida = e.idsalida
                                    left outer join vehiculo as v on v.idvehiculo = s.idvehiculo
                                    left outer join empleado as chofer on chofer.idempleado = s.idchofer and chofer.idtipo_empleado = 2                             
                WHERE e.tipo_pro = 2 and  e.fecha between :p2 and :p3 and e.iddestino = ".$_SESSION['idsucursal']." and e.estado = 3 ";

        $sql_union .= " UNION ALL ";

        $sqlw="";        
        switch ($g['filtro']) 
                {
                  case 0: $sql = $sql.$sql_union.$sql_2;
                          break;
                  case 1: $sql = $sql;
                          break;
                  case 2: $sqlw = " and e.cpago = 1 "; 
                          $sql = $sql.$sqlw;
                          break;
                  case 3: $sqlw = " and e.adomicilio = 1 "; 
                          $sql = $sql.$sqlw;
                          break;
                  case 4: $sql = $sql_2;                           
                          break;                    
                  case 5: $sqlw = " and e.cpago = 1 "; 
                          $sql = $sql_2.$sqlw;
                          break;                    
                  case 6: $sqlw = " and e.adomicilio = 1 "; 
                          $sql = $sql_2.$sqlw;
                          break;
                  default: break;
                } 
       //$sql .= " ORDER BY e.idenvio ";
       //echo $sql;
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($g['fechai']);die;
       return array($r2);
   }
    function data_salida($g)
   {
       $sql = "SELECT
                  distinct 
          				salida.fecha_pay,
          				salida.hora_pay,
          				concat(chofer.nombre,' ',chofer.apellidos) as nombre,
          				concat(vehiculo.marca,' - ',vehiculo.placa),
          				salida.numero,
                  salida.monto
          				FROM
          				salida inner join empleado as chofer on chofer.idempleado = salida.idchofer
          				Inner Join empleado ON empleado.idempleado = salida.idempleado and empleado.idtipo_empleado=1                  
          				Inner Join vehiculo ON vehiculo.idvehiculo = salida.idvehiculo
          			  WHERE salida.estado <> 0 and salida.fecha_pay between :p2 and :p3 and salida.idoficina = ".$_SESSION['idoficina']." 
                        and chofer.idtipo_empleado = 2 ";
       $stmt = $this->db->prepare($sql);
       $fechai = $this->fdate($g['fechai'],'EN');
       $fechaf = $this->fdate($g['fechaf'],'EN');
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':p2',$fechai,PDO::PARAM_STR);
       $stmt->bindParam(':p3',$fechaf,PDO::PARAM_STR);
       $stmt->execute();
       $r2 = $stmt->fetchAll();
       //var_dump($g['fechai']);die;
       return array($r2);
   }
   

}
?>