<?php 
include('../../config.php');
include('../../config_seguridad.php');	
include_once '../../libs/funciones.php';

$Id = $_GET['Id'];

$sql = "SELECT 
        kardex_participantes.idkardex, 
        documento.descripcion as documento, 
        kardex_participantes.idparticipante, 
        cliente.dni_ruc, 
        cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,'') as nombres, 
        kardex_participantes.idparticipacion, 
        participacion.descripcion as participacion,
        kardex_participantes.porcentage,
        kardex_participantes.idrepresentado,
        kardex_participantes.tipo,
        kardex_participantes.conyuge,
        kardex_participantes.porcentage,
        kardex_participantes.partida,
        kardex_participantes.idzona,
        zr.zona as zona
        FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) 
        INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) 
        INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) 
        left outer join ro.zona_registral as zr on zr.idzona = kardex_participantes.idzona 
        WHERE kardex_participantes.idkardex = ".$Id." order by kardex_participantes.tipo";
        
$Consulta2 = $Conn->Query($sql);           
$data = array();

while($row = $Conn->FetchArray($Consulta2))
{
	$data[] = array('idparticipante'=>$row['idparticipante'],
					'participante'=>$row['nombres'],
					'documento'=>$row['documento'],
					'nrodocumento'=>$row['dni_ruc'],
					'idparticipacion'=>$row['idparticipacion'],
					'participacion'=>$row['participacion'],
					'tipo'=> $row['tipo'],
					'idrepresentado'=>values($row['idrepresentado']),
					'conyuge'=>values($row['conyuge']),
					'porcentage'=>$row['porcentage'],
					'partida'=>$row['partida'],
					'idzona'=>$row['idzona'],
					'zona'=>$row['zona']
					);
	if($row['conyuge']!="")
	{
		$s = "SELECT cliente.idcliente,
					 cliente.dni_ruc, 
        			 cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,'') as nombres, 
        			 documento.descripcion as documento
        	 from cliente 
        	 	  INNER JOIN documento ON cliente.iddocumento = documento.iddocumento 
        	 where idcliente = ".$row['conyuge'];
        $q = $Conn->Query($s);
        while($r = $Conn->FetchArray($q))
        {
        	$data[] = array('idparticipante'=>$r['idcliente'],
							'participante'=>$r['nombres'],
							'documento'=>$r['documento'],
							'nrodocumento'=>$row['dni_ruc'],
							'idparticipacion'=>$row['idparticipacion'],
							'participacion'=>$row['participacion'],
							'tipo'=> $row['tipo'],
							'idrepresentado'=>'NULL',
							'conyuge'=>'NULL',
							'porcentage'=>$row['porcentage'],
							'partida'=>'',
							'idzona'=>'',
							'zona'=>'');
        }
	}
}

print_r(json_encode($data));
function values($v)
{
	if($v==NULL||$v=="")
	{
		$v = 'NULL';
	}
	return $v;
}
?>