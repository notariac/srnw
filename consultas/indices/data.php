<?php 
include('../../config.php');
include('../../config_seguridad.php');
function ud($anho,$mes)
    { 
       if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
           $dias_febrero = 29; 
       } else { 
           $dias_febrero = 28; 
       }        
       $mes = (int)$mes;
       switch($mes) 
       { 
           case 1: return 31; break; 
           case 2: return $dias_febrero; break; 
           case 3: return 31; break; 
           case 4: return 30; break; 
           case 5: return 31; break; 
           case 6: return 30; break; 
           case 7: return 31; break; 
           case 8: return 31; break; 
           case 9: return 30; break; 
           case 10: return 31; break; 
           case 11: return 30; break; 
           case 12: return 31; break; 
       } 
    }
$sql = "SELECT idusuario,login as nombres 
 		FROM usuario 
 		where estado = 1";
$qs = $ConnS->Query($sql);
$usuarios = array();
while($rows = $ConnS->FetchArray($qs))
{
	$usuarios[$rows['idusuario']] = $rows['nombres'];
}

$sql = "SELECT 	k.idkardex,
				k.correlativo,
				k.escritura,
				k.minuta,
				k.fojainicio,
				k.serieinicio,
				k.fojafin,
				k.seriefin,
				k.escritura_fecha,
				s.descripcion as servicio,
				c.nombres||' '||coalesce(c.ape_paterno,'')||' '||coalesce(c.ap_materno,'')||' ('||ltrim(rtrim(p.descripcion))||') ' as participante,
				kds.asiento_numero,
				kds.partida_numero,
				kds.observacion,
				cast(kds.fecha as varchar) as fecha_kds,
				k.idusuario,
				K.anio,
				'' as usuario
		from kardex as k inner join servicio as s on s.idservicio = k.idservicio
			left outer join kardex_derivacion_situacion as kds on kds.idkardex = k.idkardex and kds.idsituacion=2
			left outer join kardex_participantes as kp on kp.idkardex = k.idkardex 
			inner join cliente as c on c.idcliente = kp.idparticipante
			inner join participacion as p on p.idparticipacion = kp.idparticipacion ";
 
 //$where .= "  and k.anio = '".$_GET['anio']."' and kds.idsituacion = 2";

 if(isset($_GET['escritura']))		
 {
 	$where = " where k.escritura ilike '%".$_GET['escritura']."%'";
 }
 if(isset($_GET['correlativo']))		
 {
 	$where = " where k.correlativo ilike '%".$_GET['correlativo']."%'";
 }
 if(isset($_GET['participantes']))		
 {
 	$where = " where c.nombres||' '||coalesce(c.ape_paterno,'')||' '||coalesce(c.ap_materno,'')||' ('||ltrim(	rtrim(p.descripcion))||') ' ilike  '%".$_GET['participantes']."%'";
 }
 if(isset($_GET['servicio']))		
 {
 	$where = " where s.descripcion ilike  '%".$_GET['servicio']."%'";
 }
 
 switch ($_GET['tt']) {
 	case 1:
 		if($_GET['anio']!="") $where .= "  and k.anio = '".$_GET['anio']."' "; 
 		break;
 	case 2:
 		$fi = "";
 		$ff = "";

 		if($_GET['anioi']>$_GET['aniof'])
 		{
 			$ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']); 			
 			$fi = $_GET['aniof']."-".$_GET['mesf']."-01"; 			
 		}
 		else
 		{
 			if($_GET['anioi']==$_GET['aniof'])
 			{
 				if($_GET['mesi']==$_GET['mesf'])
 				{
 					$ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']);
 					$fi = $_GET['anioi']."-".$_GET['mesi']."-01"; 			 			
 				}
 				else
 				{
 					if($_GET['mesi']>$_GET['mesf'])
 					{
 						$ff = $_GET['anioi']."-".$_GET['mesi']."-".ud($_GET['anioi'],$_GET['mesi']);
 						$fi = $_GET['anioi']."-".$_GET['mesf']."-01"; 			 			
 					}
 					else
 					{
 						$ff = $_GET['anioi']."-".$_GET['mesf']."-".ud($_GET['anioi'],$_GET['mesf']);
 						$fi = $_GET['anioi']."-".$_GET['mesi']."-01"; 			 				
 					}
 				}
 			}
 			else
 			{
 				$ff = $_GET['aniof']."-".$_GET['mesf']."-".ud($_GET['aniof'],$_GET['mesf']); 			
 				$fi = $_GET['anioi']."-".$_GET['mesi']."-01"; 	
 			}
 		}
 		$where .= " and k.escritura_fecha between '".$fi."' and  '".$ff."'";
 		break;
 	case 3: 
 			$where .= " and k.escritura_fecha between '".$Conn->CodFecha($_GET['fechai'])."' and  '".$Conn->CodFecha($_GET['fechaf'])."'";
 			break;
 	default:
 		# code...
 		break;
 }

 $sql = $sql.$where;
 //Agregamos los kardex antiguos
 $sql .= " UNION ALL ";
 //
 $sql .= " SELECT  idkardexm as idkardex,
				correlativo,
				escritura,
				minuta,
				fojas as fojainicio,
				'' as serieinicio,
				cfojas as fojafin,
				'' as seriefin,
				fechae as escritura_fecha,
				contrato as servicio,
				participantes as participante,
				asiento as asiento_numero,
				partida as partida_numero,
				anotaciones as observacion,
				fechaa as fecha_kds,
				0 as idusuario,	
				cast(Extract(year from fechae) as varchar) as anio,
				digitador as usuario
			from kardex_migrado";


 if(isset($_GET['escritura']))		
 {
 	$where = " where escritura ilike '%".$_GET['escritura']."%'";
 }
 if(isset($_GET['correlativo']))		
 {
 	$where = " where correlativo ilike '%".$_GET['correlativo']."%'";
 }
 if(isset($_GET['participantes']))		
 {
 	$where = " where participantes ilike  '%".$_GET['participantes']."%'";
 }
 if(isset($_GET['servicio']))		
 {
 	$where = " where contrato ilike  '%".$_GET['servicio']."%'";
 }
 
 switch ($_GET['tt']) {
 	case 1:
 		if($_GET['anio']!="") $where .= "  and Extract(year from fechae)  = ".$_GET['anio']." "; 
 		break;
 	case 2:
 		$where .= " and fechae between '".$fi."' and  '".$ff."'";
 		break;
 	case 3: 
 			$where .= " and fechae between '".$Conn->CodFecha($_GET['fechai'])."' and  '".$Conn->CodFecha($_GET['fechaf'])."'";
 			break;
 	default:
 		# code...
 		break;
 }

 $sql .= $where;
 $sql .= " order by idkardex desc limit 200 ";
 
 $Consulta = $Conn->Query($sql);
 $c = 0;
 $flag = false;
 $last = "";

 $cont = 1;
 $bg = "#FFFFFF";
 while($row = $Conn->FetchArray($Consulta))
 {
 	$c +=1;
 	if($row['idkardex']==$last)
 	{
 		$flag=true;
 		
 	}
 	else
 	{
 		$flag = false;
 		$last = $row['idkardex'];
 		if($bg=="#E7F6C3")
 		{
 			$bg = "#FFFFFF";
 		}
 		else
 		{
 			$bg = "#E7F6C3";
 		}
 	}
 	if(!$flag)
 	{
 		$sql2 = "SELECT count(*) from kardex_participantes where idkardex = ".$row['idkardex'];

	 	$q = $Conn->Query($sql2);
	 	$r = $Conn->FetchArray($q);
	 	$n = $r[0];	 	
	 	$cont = 1;
 	}	
 	?>
 	<tr style="background:<?php echo $bg; ?>">
 		<td align="center"><?php echo $row['anio']; ?></td>
 		<td align="center"><a href="../kardex/index.php?Valor=<?php echo $row['correlativo']; ?>&Campo=kardex.correlativo&Anio=<?php echo $row['anio']; ?>" target="_blank" style="color:green; font-weight:bold;"><?php echo $row['correlativo']; ?></a></td>
 		<td align="center"><?php echo $row['escritura'] ?></td>
 		<td align="center"><?php echo $row['minuta'] ?></td>
 		<td align="center"><?php echo $row['serieinicio']."/<b>".$row['fojainicio'] ?></b></td>
 		<td align="center">
 		<?php 
 		if(trim($row['fojainicio'])!=""&&trim($row['fojafin'])!="")
		{
			echo $row['seriefin']."/<b>".$row['fojafin']."</b>";
		}
 		?>
 		</td>
 		<td><p style="font-size:8px"><?php if($row['idusuario']!="") echo $usuarios[$row['idusuario']]; else echo $row['usuario'] ?></p></td>
 		<td align="center"><?php echo $Conn->DecFecha($row['escritura_fecha']) ?></td>
 		<td align="left"><?php echo $row['servicio'] ?></td>
 		<td align="left"><?php echo $row['participante'] ?></td>
 	
 			<td align="center" style="border-left:2px solid #999;">
 				<?php echo trim($row['asiento_numero']) ?>
	 		</td>
	 		<td align="center"><?php echo trim($row['partida_numero']) ?></td>
	 		<td><?php echo $row['observacion'] ?></td>
	 		<td><?php echo $Conn->DecFecha($row['fecha_kds']) ?></td> 	
 			
 	</tr>
 	<?php
 }
?>	
<tr>
	<td colspan="20"><?php //echo $sql; ?></td>
</tr>
