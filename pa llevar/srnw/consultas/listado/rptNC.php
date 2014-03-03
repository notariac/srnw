<?php
if(!session_id()){ session_start(); }	
require('../../libs/fpdf.php');
class PDF extends FPDF{
	function LoadData($data){
		global $Conn, $Cab, $w;		
		$this->SetFont('Arial', 'B', 9);
		$posX = $this->GetX();
		$posY = $this->GetY();
		$this->MultiCell($w[0], 4, $Cab[0], 1, 'C', false);
                $posX = $posX + $w[0];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[1], 4, $Cab[1], 1, 'C', false);
                $posX = $posX + $w[1];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[2], 4, $Cab[2], 1, 'C', false);
                $posX = $posX + $w[1];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[3], 8, $Cab[3], 1, 'C', false);
                $posX = $posX + $w[3];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[4], 8, $Cab[4], 1, 'C', false);
                $posX = $posX + $w[4];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[5], 8, $Cab[5], 1, 'C', false);
                $posX = $posX + $w[5];
                $this->SetXY($posX, $posY);
		$this->MultiCell($w[6], 8, $Cab[6], 1, 'C', false);		
		while($row	= $Conn->FetchArray($data)){
                    //Otorgantes
                    $SQLParticipante 	 = "SELECT cliente.nombres FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN kardex ON (kardex_participantes.idkardex = kardex.idkardex) WHERE participacion.tipo = 1 AND kardex_participantes.idkardex = '".$row[0]."' AND kardex.idnotaria='".$_SESSION['notaria']."' ";
                    $ConsParticipante 	 = $Conn->Query($SQLParticipante);			
                    $ConO = $Conn->NroRegistros($ConsParticipante);			
                    $Fecha = $Conn->DecFecha($row[5]);
                    //Determinamos el Nro de Lineas
                    $Lin = $ConO;
                    $NumLC = (int)$this->GetStringWidth($row[4]);
                    $NumLC = (int)($NumLC / $w[3]);
                    $NumLC = $NumLC + 1;
                    if ($Lin<$NumLC){
                        $Lin = $NumLC;
                    }
                    $Lin = 4 * $Lin;
                    $posX = $this->GetX();
                    $posY = $this->GetY();		
                    $this->SetFont('Arial', '', 8);
                    $this->MultiCell($w[0], $Lin, $row[2], 1, 'C', false);
                        $posX = $posX + $w[0];
                        $this->SetXY($posX, $posY);
                    $this->MultiCell($w[1], $Lin, $row[3], 1, 'C', false);
                        $posX = $posX + $w[1];
                        $this->SetXY($posX, $posY);
                    $this->MultiCell($w[2], $Lin, $row[2], 1, 'C', false);
                    $this->SetFont('Arial', '', 7);
                    $posX = $posX + $w[2];
                    $Cont = 0;
                    while($rowparticipante	= $Conn->FetchArray($ConsParticipante)){
                        $this->SetXY($posX, $posY + $Cont);
                        $Nombres 	=  explode("!", $rowparticipante[0]);
                        $this->MultiCell($w[3], 4, utf8_decode($Nombres[1]." ".$Nombres[0]), 1, 'L', false);
                        $Cont = $Cont + 4;
                    }
                    for ($i=$Conn->NroRegistros($ConsParticipante); $i<$Lin/4; $i++){
                        $this->SetXY($posX, $posY + $Cont);
                        $this->MultiCell($w[3], 4, '', 1, 'L', false);
                        $Cont = $Cont + 4;
                    }
                    $this->SetFont('Arial', '', 8);
                        $posX = $posX + $w[3];
                        $this->SetXY($posX, $posY);
                    $this->SetFont('Arial', '', 7);
                    $this->MultiCell($w[4], 4, $row[4], 1, 'C', false);
                        $posX = $posX + $w[4];
                        $this->SetXY($posX, $posY);
                    $this->SetFont('Arial', '', 8);
                    $this->MultiCell($w[5], $Lin, $Fecha, 1, 'C', false);
                        $posX = $posX + $w[5];
                        $this->SetXY($posX, $posY);
                    $this->MultiCell($w[6], $Lin, $row[6], 1, 'C', false);
		}
	}
	// Una tabla más completa
	function Titulo(){
            $this->SetFont('Arial', '', 14);
            $this->Cell(0, 6, 'INDICE DE NO CONTENCIOSOS', 0, 1, 'C');
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
    $Cab = array('NRO. DE SOL.', 'NRO. DE MINUTA', 'NRO. DE ORDEN', 'TITULAR', 'PROCESO', 'FECHA', 'FOLIO');
    $w = array(18, 18, 18, 65, 40, 20, 20);
    // Carga de datos
    $SQL  = "SELECT kardex.idkardex, kardex.correlativo, kardex.escritura, kardex.minuta, servicio.descripcion, kardex.escritura_fecha, kardex.fojafin FROM kardex INNER JOIN servicio ON (servicio.idservicio = kardex.idservicio) WHERE substr(kardex.correlativo, 1, 1) = 'N' AND kardex.escritura_fecha BETWEEN '$Desde' AND '$Hasta' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY kardex.escritura_fecha ASC, kardex.escritura ASC";
    $Consulta 	= $Conn->Query($SQL);
    //$data = $pdf->LoadData($Consulta);
$pdf->SetMargins(5, 5, 5);	
$pdf->AddPage();
if ($Titulo==1){
    $pdf->Titulo();
}
$pdf->LoadData($Consulta);
$pdf->Output();
?>