<?php
if(!session_id()){ session_start(); }	
require('../../libs/fpdf.php');
class PDF extends FPDF{
	function LoadData($data){
		global $Conn, $Cab, $w;		
		$this->SetFont('Arial', 'B', 8);
		$posX = $this->GetX();
		$posY = $this->GetY();
		$this->MultiCell($w[0], 4, $Cab[0], 1, 'C', false);
                $posX = $posX + $w[0];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[1], 4, $Cab[1], 1, 'C', false);
                $posX = $posX + $w[1];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[2], 4, $Cab[2], 1, 'C', false);
                $posX = $posX + $w[2];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[3], 8, $Cab[3], 1, 'C', false);
                $posX = $posX + $w[3];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[4], 8, $Cab[4], 1, 'C', false);		
		while($row	= $Conn->FetchArray($data)){			
			$Fecha = $Conn->DecFecha($row[2]);
			$FechaD = $Conn->DecFecha($row[3]);			
			//Determinamos el Nro de Lineas
			$NumLD = (int)$this->GetStringWidth($row[4]);
			$NumLD = (int)($NumLD / $w[3]);
			$NumLD = $NumLD + 1;
			$Lin = $NumLD;
			$NumLC = (int)$this->GetStringWidth($row[5]);
			$NumLC = (int)($NumLC / $w[4]);
			$NumLC = $NumLC + 1;
			if ($NumLD<$NumLC){
                            $Lin = $NumLC;
			}
			$Lin = 4 * $Lin;
			$posX = $this->GetX();
			$posY = $this->GetY();		
			$this->SetFont('Arial', '', 8);
			$this->MultiCell($w[0], $Lin, $row[1], 1, 'C', false);
                        $posX = $posX + $w[0];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[1], $Lin, $Fecha, 1, 'C', false);
			$posX = $posX + $w[1];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[2], $Lin, $FechaD, 1, 'C', false);
			$this->SetFont('Arial', '', 7);
                        $posX = $posX + $w[2];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[3], 4, $row[4], 1, 'L', false);
                        $posX = $posX + $w[3];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[4], 4, $row[5], 1, 'L', false);
		}
	}
	// Una tabla más completa
	function Titulo(){
            $this->SetFont('Arial', '', 14);
            $this->Cell(0, 6, 'INDICE DE CARTAS NOTARIALES', 0, 1, 'C');
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
	$Cab = array('NRO. DE ORDEN', 'FECHA DE INGRESO', 'FECHA  DE DILIGENCIA', 'DESTINATARIO', 'REMITENTE');
	$w = array(18, 20, 20, 70, 70);
	// Carga de datos
	$SQL      = "SELECT idcarta, correlativo, fecha, entrega_fecha, destinatario, remitente FROM carta WHERE fecha BETWEEN '$Desde' AND '$Hasta' AND idnotaria='".$_SESSION['notaria']."' ORDER BY fecha ASC";
	$Consulta = $Conn->Query($SQL);
$pdf->SetMargins(5, 5, 5);	
$pdf->AddPage();
if ($Titulo==1){
    $pdf->Titulo();
}
$pdf->LoadData($Consulta);
$pdf->Output();
?>