<?php
if(!session_id()){ session_start(); }	
require('../../libs/fpdf.php');
class PDF extends FPDF{
	function Header(){
		global $Cab, $w, $Titulo;		
		if ($Titulo==1){
                    $this->Titulo();
		}
		$this->SetFont('Arial', 'B', 8);
		$posX = $this->GetX();
		$posY = $this->GetY();
		$this->MultiCell($w[0], 4, utf8_decode($Cab[0]), 1, 'C', false);
                $posX = $posX + $w[0];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[1] + $w[2] + $w[3] + $w[4], 4, $Cab[1], 1, 'C', false);
                $posX = $posX;
                $this->SetXY($posX, $posY + 4);
                $this->SetFont('Arial', 'B', 7);
		$this->MultiCell($w[1], 4, $Cab[2], 1, 'C', false);
                $posX = $posX + $w[1];
                $this->SetXY($posX, $posY + 4);			
		$this->MultiCell($w[2], 4, $Cab[3], 1, 'C', false);
                $posX = $posX + $w[2];
                $this->SetXY($posX, $posY + 4);
                $this->SetFont('Arial', 'B', 6);
		$this->MultiCell($w[3], 4, utf8_decode($Cab[4]), 1, 'C', false);
                $posX = $posX + $w[3];
                $this->SetXY($posX, $posY + 4);
		$this->MultiCell($w[4], 4, utf8_decode($Cab[5]), 1, 'C', false);
                $posX = $posX + $w[4];
                $this->SetXY($posX, $posY);
                $this->SetFont('Arial', 'B', 8);
		$this->MultiCell($w[5], 8, $Cab[6], 1, 'C', false);
                $posX = $posX + $w[5];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[6], 8, $Cab[7], 1, 'C', false);		
	}	
	// Cargar los datos
	function LoadData($data){
		global $Conn, $Cab, $w;		
		while($row	= $Conn->FetchArray($data)){
			$this->SetFont('Arial', '', 6);
			$NumLC = (int)$this->GetStringWidth($row[8]);
			$NumLC = (int)($NumLC / $w[6]);
			$NumLC = $NumLC + 1;
			$Lin = 4 * $NumLC;			
			$Fecha = $Conn->DecFecha($row[2]);			
			$this->SetFont('Arial', '', 7);
			$this->Cell($w[0], $Lin, substr($row[1],1), 1, 0,'C', false);
			$this->Cell($w[1], $Lin, $Fecha, 1, 0, 'C', false);
			$this->Cell($w[2], $Lin, $row[3]." - ".$row[4], 1, 0, 'C', false);
			$this->Cell($w[3], $Lin, $row[5], 1, 0, 'C', false);
			$this->Cell($w[4], $Lin, $row[6], 1, 0, 'C', false);
                        $this->SetFont('Arial', '', 5);
                        $Nombres 	=  explode("!", strtoupper($row[7]));
			$this->Cell($w[5], $Lin, utf8_decode($Nombres[0]." ".$Nombres[1]), 1, 0, 'L', false);
                        $this->SetFont('Arial', '', 6);				
			if($Lin==1){
                            $this->Cell($w[6], $Lin, utf8_decode($row[8]), 1, 1, 'L', false);
			}else{
                            $this->MultiCell($w[6], 4, utf8_decode($row[8]), 1, 'L', false);
			}
		}
	}
	// Una tabla más completa
	function Titulo(){
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 4, utf8_decode('Notaría Cisneros Olano'), 0, 1, 'C');
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 5, utf8_decode('(Indice Alfabético)'), 0, 1, 'C');		
	}
}
	$pdf = new PDF();
	include("../../config.php");
	$Desde = (isset($_GET["Desde"]))?$_GET["Desde"]:'';
	$Hasta = (isset($_GET["Hasta"]))?$_GET["Hasta"]:'';
	$Titulo = (isset($_GET["Titulo"]))?$_GET["Titulo"]:'0';	
	// Títulos de las columnas
	$Cab = array('Kardex Nº', 'Escritura', 'Fecha', 'Fojas', 'Instrumento Nº', 'Minuta Nº', 'Otorgantes', 'Acto Clase de Contrato');
	$w = array(12, 16, 20, 18, 14, 60, 50);
	// Carga de datos
	$SQL = 	"SELECT kardex.idkardex, kardex.correlativo, kardex.escritura_fecha, kardex.fojainicio, kardex.fojafin, kardex.escritura, kardex.minuta, cliente.nombres, servicio.descripcion FROM kardex INNER JOIN kardex_participantes ON (kardex.idkardex = kardex_participantes.idkardex) INNER JOIN cliente ON (kardex_participantes.idparticipante = cliente.idcliente) INNER JOIN servicio ON (kardex.idservicio = servicio.idservicio) WHERE substr(kardex.correlativo, 1, 1) = 'K' AND kardex.escritura_fecha BETWEEN '$Desde' AND '$Hasta' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY cliente.nombres ASC";
	$Consulta 	= $Conn->Query($SQL);
$pdf->SetMargins(10, 5, 10, 10);	
$pdf->AddPage();
$pdf->LoadData($Consulta);
$pdf->Output();
?>