<?php 	
    require('../../libs/fpdf.php');
	class PDF extends FPDF{	
		function Header(){
                    global $Titulo, $Participante;	
                    $w=$this->GetStringWidth($Titulo)+ 6;			//Calculamos ancho y posición del título.			
                    //******Se Posiciona y Escribe Los Otros Datos******/////(210-$w)/2
                    $this->SetFont('Arial','',14);				
                    $this->SetX(30);
                    $this->SetY(75);
                    $this->Cell(20,5,"Kardex",0,0,'R',false);		
                    $this->Cell(5,5,":",0,0,'C',false);		
                    $this->Cell(80,5,$Titulo,0,1,'L',false);	
                    $this->SetY(82);
                    $this->Cell(20,5,"Participante",0,0,'R',false);		
                    $this->Cell(5,5,":",0,0,'C',false);		
                    $this->Cell(80,5,$Participante,0,1,'L',false);	
                    $this->Ln(1);
		}		
		function ImprimirFoto($Imagen=''){
                    $this->Image($Imagen, 10, 100, 190, 100);
		}
	}	
	include("../../config.php");		
	$NroGeneracion 		= $_GET["IdKardex"];
	$IdParticipante 	= $_GET["IdParticipante"];
	$SQLCabgeneracion 	= "SELECT correlativo FROM kardex WHERE idkardex='$NroGeneracion'";
	$ConsCabgeneracion	= $Conn->Query($SQLCabgeneracion);
	$rowcabgeneracion	= $Conn->FetchArray($ConsCabgeneracion);	
	$SQL 		= "SELECT foto FROM kardex_participantes WHERE idkardex = '$NroGeneracion' AND idparticipante = '$IdParticipante'";
	$Consulta	= $Conn->Query($SQL);
	$row		= $Conn->FetchArray($Consulta);	
	$SQLParticipante 	= "SELECT nombres FROM cliente WHERE idcliente='$IdParticipante'";
	$ConsParticipante	= $Conn->Query($SQLParticipante);
	$rowparticipante	= $Conn->FetchArray($ConsParticipante);	
	$pdf		= new PDF();		
	$Titulo 		= $rowcabgeneracion[0];
	$Participante	= $rowparticipante[0];	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	if($row[0]!=''){
            $pdf->ImprimirFoto('ftp/fotos/'.$row[0]);
	}else{
            $pdf->ImprimirFoto('../../imagenes/pregunta.jpg');
	}	
	$pdf->Output();	
?>