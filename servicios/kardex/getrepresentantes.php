<?php 
include('../../config.php');
include('../../config_seguridad.php');	
include_once '../../libs/funciones.php';

$Id = $_GET['Id'];

$sql = "select 	c.idcliente,
                c.dni_ruc,
                c.nombres||' '||c.ape_paterno||' '||c.ap_materno as nombres,
                c2.partida,
                c2.idzona,
                z.zona,
                d.iddocumento,
                d.descripcion as documento
        from cliente_representante as cr inner join cliente as c on cr.idrepresentante = c.idcliente
                inner join cliente as c2 on c2.idcliente = cr.idcliente
                inner join ro.zona_registral as z on z.idzona = c2.idzona
                inner join documento as d on d.iddocumento = c.iddocumento
        where cr.idcliente = ".$Id." and c2.idcliente_tipo=2";
        
$Consulta2 = $Conn->Query($sql);           
$n = $Conn->NroRegistros($Consulta2);
$porcentaje = "";

$data = array();

while($row = $Conn->FetchArray($Consulta2))
{
    $data[] = array('idparticipante'=>$row['idcliente'],
                    'participante'=>$row['nombres'],
                    'documento'=>$row['documento'],
                    'nrodocumento'=>$row['dni_ruc'],
                    'idparticipacion'=>'28',
                    'participacion'=>'REPRESENTANTE',
                    'tipo'=> '3',
                    'idrepresentado'=>$Id,
                    'conyuge'=>'NULL',
                    'porcentage'=>$porcentaje,
                    'partida'=>$row['partida'],
                    'idzona'=>$row['idzona'],
                    'zona'=>$row['zona']
                    );
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