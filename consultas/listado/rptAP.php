<?php
if(!session_id()){ session_start(); }	
require('../../libs/fpdf.php');
class PDF extends FPDF{
	function LoadData($data){
		global $Conn, $Cab, $w, $Hoja;		
		$this->SetFont('Arial', 'B', 9);
		$posX = $this->GetX();
		$posY = $this->GetY();
		$this->MultiCell($w[0], 8, $Cab[0], 1, 'C', false);
                $posX = $posX + $w[0];
                $this->SetXY($posX, $posY);
		if ($Hoja==1){
			$this->MultiCell($w[1], 4, $Cab[1], 1, 'C', false);
                        $posX = $posX + $w[1];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[2], 4, $Cab[2], 1, 'C', false);
                        $posX = $posX + $w[2];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[3], 4, $Cab[3], 1, 'C', false);
		}else{
			$this->MultiCell($w[1], 8, $Cab[1], 1, 'C', false);
                        $posX = $posX + $w[1];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[2], 8, $Cab[2], 1, 'C', false);
                        $posX = $posX + $w[2];
                        $this->SetXY($posX, $posY);
			$this->MultiCell($w[3], 8, $Cab[3], 1, 'C', false);
		}
			$posX = $posX + $w[3];
			$this->SetXY($posX, $posY);
		$this->MultiCell($w[4], 8, $Cab[4], 1, 'C', false);		
		while($row	= $Conn->FetchArray($data)){
			//Aceptante / Deudor
			$SQLParticipante 	= "SELECT cliente.nombres FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante)INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN kardex ON (kardex.idkardex = kardex_participantes.idkardex) WHERE participacion.tipo = 2 AND kardex_participantes.idkardex = '".$row[0]."' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY cliente.nombres ASC ";
			$ConsParticipante 	= $Conn->Query($SQLParticipante);
			//Girador
			$SQLParticipante2 	= "SELECT cliente.nombres FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante)INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN kardex ON (kardex.idkardex = kardex_participantes.idkardex) WHERE participacion.tipo = 1 AND kardex_participantes.idkardex = '".$row[0]."' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY cliente.nombres ASC ";
			$ConsParticipante2 	= $Conn->Query($SQLParticipante2);
			$ConO = $Conn->NroRegistros($ConsParticipante);
			$ConA = $Conn->NroRegistros($ConsParticipante2);			
			$Fecha = $Conn->DecFecha($row[4]);
			//Determinamos el Nro de Lineas
			$Lin = $ConA;
			if ($ConA<$ConO){
                            $Lin = $ConO;
			}			
			$Lin = 4 * $Lin;
			$posX = $this->GetX();
			$posY = $this->GetY();		
			$this->SetFont('Arial', '', 8);
			if ($Hoja==1){
                            $this->MultiCell($w[0], $Lin, $row[2], 1, 'C', false);
                            $posX = $posX + $w[0];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[1], $Lin, $row[3], 1, 'C', false);
                            $posX = $posX + $w[1];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[2], $Lin, $Conn->DecFecha($row[4]), 1, 'C', false);
                            $posX = $posX + $w[2];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[3], $Lin, $row[5], 1, 'C', false);				
                            $posX = $posX + $w[3];
                            $Cont = 0;
                            while($rowparticipante	= $Conn->FetchArray($ConsParticipante)){
                                $this->SetXY($posX, $posY + $Cont);
                                $Nombres = explode("!", $rowparticipante[0]);
                                $this->MultiCell($w[4], 4, utf8_decode($Nombres[1]." ".$Nombres[0]), 1, 'L', false);
                                $Cont = $Cont + 4;
                            }
                            for ($i=$Conn->NroRegistros($ConsParticipante); $i<$Lin/4; $i++){
                                $this->SetXY($posX, $posY + $Cont);
                                $this->MultiCell($w[4], 4, '', 1, 'L', false);
                                $Cont = $Cont + 4;
                            }
			}else{
                            $Cont = 0;
                            while($rowparticipante2	= $Conn->FetchArray($ConsParticipante2)){
                                $this->SetXY($posX, $posY + $Cont);
                                $Nombres2 	=  explode("!", $rowparticipante2[0]);
                                $this->MultiCell($w[0], 4, utf8_decode($Nombres2[1]." ".$Nombres2[0]), 1, 'L', false);
                                $Cont = $Cont + 4;
                            }
                            for ($i=$Conn->NroRegistros($ConsParticipante2); $i<$Lin/4; $i++){
                                $this->SetXY($posX, $posY + $Cont);
                                $this->MultiCell($w[0], 4, '', 1, 'L', false);
                                $Cont = $Cont + 4;
                            }
                            $posX = $posX + $w[0];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[1], $Lin, strtoupper($row[6]), 1, 'L', false);
                            $posX = $posX + $w[1];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[2], $Lin, number_format($row[7], 2), 1, 'C', false);
                            $posX = $posX + $w[2];
                            $this->SetXY($posX, $posY);
                            $this->MultiCell($w[3], $Lin, $row[8], 1, 'C', false);				
			}
		}
	}
	// Una tabla más completa
	function Titulo(){
            $this->SetFont('Arial', '', 14);
            $this->Cell(0, 6, 'INDICE DE PROTESTOS DE LETRAS Y PAGARES', 0, 1, 'C');
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
	$Hoja = (isset($_GET["Hoja"]))?$_GET["Hoja"]:'1';	
	// Títulos de las columnas
	if ($Hoja==1){
            $Cab = array('NRO.', 'FECHA DE NOTIFICACION', 'FECHA DE PROTESTO', 'TITULO VALOR', 'ACEPTANTE');
            $w = array(18, 30, 30, 20, 100);
	}else{
            $Cab = array('GIRADOR', 'SOLICITANTE', 'MONTO', 'FOJAS');
            $w = array(80, 80, 20, 20);
	}
	// Carga de datos
	$SQL      = "SELECT kardex.idkardex, kardex.correlativo, kardex.escritura, kardex.motivo, kardex.escritura_fecha, kardex.via, kardex.hijo, kardex.ruta, kardex.fojafin FROM kardex INNER JOIN servicio ON (servicio.idservicio = kardex.idservicio) WHERE substr(kardex.correlativo, 1, 2) = 'AP' AND kardex.escritura_fecha BETWEEN '$Desde' AND '$Hasta' AND kardex.idnotaria='".$_SESSION['notaria']."' ORDER BY kardex.escritura_fecha ASC";
	$Consulta = $Conn->Query($SQL);
$pdf->SetMargins(5, 5, 5);	
$pdf->AddPage();
if ($Titulo==1){
    $pdf->Titulo();
}
$pdf->LoadData($Consulta);
$pdf->Output();
?>