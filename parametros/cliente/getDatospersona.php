<?php 
$error = 0;
try
{
	$client = new SoapClient("ticket.wsdl");
	$cadena = array('user' => '3405897345','password' => '5784905794');
	$session = $client->__soapCall('getTicket',$cadena);
} 
	catch(SoapFault $fault)
{
	$error = 1;
	$error_text = "SOAP Fault: ".$fault->faultstring;
}

if($error==0)
{
		
		$client2 = new SoapClient("data.wsdl");
		if(!isset($_POST['dni']))
			$_POST['dni']="44538236";
		$request = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsd="WSDataVerification">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <wsd:getDatapersonal soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
				         <xmlDocumento xsi:type="xsd:string">
				         <![CDATA[<IN>
				         <CONSULTA>
				                <DNI>'.$_POST['dni'].'</DNI>
				         </CONSULTA>
				         <IDENTIFICACION>
				                <CODUSER>N00003</CODUSER>
				                <CODTRANSAC>5</CODTRANSAC>	
				                <CODENTIDAD>03</CODENTIDAD>
				                <SESION>'.$session.'</SESION>
				         </IDENTIFICACION>
					    </IN>]]>
				         </xmlDocumento>
				      </wsd:getDatapersonal>
				   </soapenv:Body>
				</soapenv:Envelope>';

		$uri = "http://ws.pide.gob.pe/reniec/WSDataVerificationBinding";
		$datos = $client2->__doRequest($request, $uri,'getDatapersonal',SOAP_1_1);
	

		$cad = explode("&lt;OUT&gt;",$datos);
		$cad = explode("&lt;/OUT&gt;",$cad[1]);

		$xml_raw = htmlspecialchars_decode($cad[0]);
		$xml_raw = '<?xml version="1.0" encoding="ISO-8859-1"?><OUT>'.$xml_raw.'</OUT>';

		$xml = new SimpleXMLElement($xml_raw);

		$data = array();
		$n = count($xml->RESPUESTA);

		if($n>0)
		{
			foreach ($xml->RESPUESTA as $r)
			{
				$data[] = array('NOMBRES'=>utf8_decode($r->NOMBRES),
								'APPAT'=>utf8_decode($r->APPAT),
								'APMAT'=>utf8_decode($r->APMAT),
								'FENAC'=>utf8_decode($r->FENAC),
								'SEXO'=>utf8_decode($r->SEXO));
			}	
		}
		else
		{
			$data[] = array('NOMBRES'=>'0','APPAT'=>'El DNI no existe ó es DNI de menor de edad.');
		}
		print_r(json_encode($data));
}
else
{
	$data = array();
	$data[] = array('NOMBRES'=>'0','APPAT'=>$error_text);
	print_r(json_encode($data));
}

?>