<?php
if(!session_id()){ session_start(); }	
require('../../libs/fpdf.php');
class PDF extends FPDF{
	function LoadData($data){
		global $Conn, $Cab, $w;		
		$this->SetFont('Arial', 'B', 9);
		$posX = $this->GetX();
		$posY = $this->GetY();
		$this->MultiCell($w[0], 8, $Cab[0], 1, 'C', false);
                $posX = $posX + $w[0];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[1], 8, $Cab[1], 1, 'C', false);
                $posX = $posX + $w[1];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[2], 8, $Cab[2], 1, 'C', false);
                $posX = $posX + $w[2];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[3], 8, $Cab[3], 1, 'C', false);
                $posX = $posX + $w[3];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[4], 8, $Cab[4], 1, 'C', false);		
		while($row = $Conn->FetchArray($data)){
			//Otorgantes
			$SQLParticipante 	= "SELECT cliente.nombres, cliente.dni_ruc, cliente.direccion FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN kardex ON (kardex.idkardex= kardex_participantes.idkardex) WHERE participacion.tipo = 1 AND kardex_participantes.idkardex = '".$row[0]."' AND kardex.idnotaria='".$_SESSION['notaria']."'";
			$ConsParticipante 	= $Conn->Query($SQLParticipante);
			$rowparticipante = $Conn->FetchArray($ConsParticipante);
			$Fecha = $Conn->DecFecha($row[2]);
			//Determinamos el Nro de Lineas
			$NumLC = (int)$this->GetStringWidth($rowparticipante[2]);
			$NumLC = (int)($NumLC / $w[4]);
			$NumLC = $NumLC + 1;			
			$Lin = $NumLC;			
			$Lin = 4 * $Lin;
			$posX = $this->GetX();
			$posY = $this->GetY();		
			$this->SetFont('Arial', '', 8);
			$this->MultiCell($w[0], $Lin, $row[1], 1, 'C', false);
                        $posX = $posX + $w[0];
                        $this->SetXY($posX, $posY);
                        $Nombres = explode("!", $rowparticipante[0]);
			$this->MultiCell($w[1], $Lin, utf8_decode($Nombres[1]." ".$Nombres[0]), 1, 'L', false);
                        $posX = $posX + $w[1];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[2], $Lin, $rowparticipante[1], 1, 'C', false);
                        $posX = $posX + $w[2];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[3], $Lin, $Fecha, 1, 'C', false);
                        $posX = $posX + $w[3];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[4], 4, utf8_decode($rowparticipante[2]), 1, 'L', false);
		}
	}
	// Una tabla más completa
	function Titulo(){
            $this->SetFont('Arial', '', 14);
            $this->Cell(0, 6, 'INDICE DE PODER FUERA DE REGISTRO', 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, '(ORDEN CRONOLOGICO)', 0, 1, 'C');
            $this->Ln();
	}
}
	$pdf = new PDF();
	include("../../config.php");	
	$Desde = (isset($_GET["Desde"]))?$_GET["Desde"]:'';
	$Hasta = (isset($_GET["Hasta"]))?$_GET["Hasta"]:'';
	$Titulo = (isset($_GET["Titulo"]))?$_GET["Titulo"]:'0';	
	// Títulos de las columnas
	$Cab = array('NRO.', 'NOMBRES Y APELLIDOS', 'DNI', 'FECHA', 'DIRECCION');
	$w = array(15, 80, 18, 20, 68);
	// Carga de datos
	$SQL = "SELECT kardex.idkardex, kardex.escritura, kardex.escritura_fecha FROM kardex INNER JOIN servicio ON (servicio.idservicio = kardex.idservicio) WHERE substr(kardex.correlativo, 1, 2) = 'CD' AND kardex.escritura_fecha BETWEEN '$Desde' AND '$Hasta' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY kardex.escritura ASC";
	$Consulta 	= $Conn->Query($SQL);
$pdf->SetMargins(5, 5, 5);	
$pdf->AddPage();
if ($Titulo==1){
	$pdf->Titulo();
}
$pdf->LoadData($Consulta);
$pdf->Output();
?>