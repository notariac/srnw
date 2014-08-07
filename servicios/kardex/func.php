<?php
include("num2letraK.php");
$meses = array
(
  'Enero',
  'Febrero',
  'Marzo',
  'Abril',
  'Mayo',
  'Junio',
  'Julio',
  'Agosto',
  'Septiembre',
  'Octubre',
  'Noviembre',
  'Diciembre'
);
function verifLev($idservicio)
{
   $flag = false;
   $idservicio = (int)$idservicio;
   $serv = array(20,53,295,327);
   foreach ($serv as $value) 
   {
      if($value==$idservicio)
        $flag = true;
   }
   return $flag;
}
function setFechaActual($text)
{
    global $meses;
    $dia  = date('d');
    $anio = date('Y');
    $mes  = date('m');
    $html = "{ ".$text." ".str_pad($dia,2,'0',0)." de ".$meses[$mes-1]." del ".$anio." }";
    return $html;
}
/*
  Funcion: participantes
  Parametros: @p1
        @p1: Record de todos los participantes.        
    Resumen: Muestra los datos completos de todos los participantes que intervienen dentro
             del proceso, incluye datos de conyuges y representantes.
*/
function participantes($participantes,$idservicio)
{
    $html = '';
    $c = 0;
    $last_tipo = 0;
    foreach($participantes as $k => $v)
    {
        if($v['idrepresentado']==0)
        {
          $temp = "";
          $f = true;
          if($v['tipo']==2 && verifLev($idservicio)==true)          
          {
            $f = false;
          }
          if($f)
          {
            if($last_tipo != $v['tipo'])        
            {
              $last_tipo = $v['tipo'];
              $c = 0;
            }
            if($v['tipo']==2 && $c==0)
            {
                if($idservicio==100)
                {
                    //poder fuera de registro
                    $tam = strlen($html);
                    $html = substr($html, 0, ($tam-5));

                    $html .= ", aquien en adelante se denominara el {\\b El Poderdante}.\\par";
                    $html .= "{\\b El Poderdante}, es mayor de edad, ".utf8_decode(hábil)." para contratar, con entera libertad, conocimiento y capacidad, ".utf8_decode(según)." lo dispuesto en el ".utf8_decode(artículo)." ".utf8_decode('55º')." de la Ley de Notariado Decreto Legislativo 1049, de lo que doy fe; ".utf8_decode(examinándosele)." para otorgar ";
                    $html .= " {\\b Poder Fuera de Registro, } a favor de: \\par";
                }
                else
                {
                    $html .= "";
                }
            }
            if($v['sexo']=="M")
               $html .= "{\\qj\\b ";
            else
              $html .= "{\\qj\\b ";

            $html .= validValur($v['participante']).", }";
            $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
            if($v['idcliente_tipo']==1)
            {
                //Si es Natural
                $html .= ", quien manifiesta ser de nacionalidad ";
                $html .= "{\\b ".genero("peruano",$v['sexo']).", }";                
                //Obtenemos si tiene algun representante
                $repre = getRepresentante($v['idparticipante'],$participantes);
                $html .= $repre;
                if(trim($repre)=="")
                {                  
                  $html .= utf8_decode(ocupación)." {\\b ".validValur($v['ocupacion'])."}, ";
                  $html .= "estado civil ".validValur($v['estado_civil'])." ";
                  if((int)$v['conyuge']>0)
                    $html .= "con ".getConyuge($participantes,$v['conyuge']);
                  $html .= ", con domicilio en ".validValur($v['dir']).", ";
                  $html .= "distrito de ".validValur($v['distrito']).", ";
                  $html .= "provincia de ".validValur($v['provincia']).", ";
                  $html .= "departamento de ".validValur($v['departamento']).".\\par";                  
                }
            }
            else
            {
              //Si es juridica
              $html .= ", con domicilio en ".validValur($v['dir']).", ";
              $html .= "distrito de ".validValur($v['distrito']).", ";
              $html .= "provincia de ".validValur($v['provincia']).", ";
              $html .= "departamento de ".validValur($v['departamento']).", ";
              $repre = getRepresentante($v['idparticipante'],$participantes);
              $html .= $repre."\\par";
            }
            if($idservicio==96)
            {
                if($v['tipo']==1)
                {
                   $numero_participantes = nOtorgantes($participantes);
                   if($numero_participantes>1)                   
                     { $denominacion = '"Los Vendedores"'; }
                   else
                     {
                        if($v['sexo']=="M")
                            $denominacion = '"El Vendedor"';
                        else
                            $denominacion = '"La Vendedora"';
                     }                                        
                }
                if($v['tipo']==2)
                {
                   $numero_participantes = nAfavor($participantes);
                   if($numero_participantes>1)                   
                     { $denominacion = '"Los Compradores"'; }
                   else
                     {
                        if($v['sexo']=="M")
                            $denominacion = '"El Comprador"';
                        else
                            $denominacion = '"La Compradora"';
                     }                                        
                }
                $len = strlen($denominacion);
                $len -= 2;
                if(($c+1)==$numero_participantes)
                {
                  $texto_ = 'A quien se denominara '.$denominacion.'.';
                  $fill = fill_lines($texto_);
                }
                //$html = substr($html, )
                $html .= '{\\b '.$texto_.'}'.$fill.'\\par ';
            }
            $c += 1;
          }
        }
    }
    return $html;
}
//Genera el parrafo correspondiente a la constancia de pago
function constancia_pago($participantes,$datos,$monto,$moneda,$fecha,$servicio,$documento_notarial)
{
    //$datos (Array):
    //  Exibió Medio de Pago  
    //  [0] -> Monto Total de la Operacion
    //  [1] -> Medio de Pago
    //  [2] -> Entidad Financiera
    //  [3] -> Moneda
    //  [4] -> Fecha Pago
    //  [5] -> Nro Documento
    global $meses;
    $otorgantes = "";
    $co = 0;
    $f = explode("-", $fecha);
    foreach ($participantes as $k => $v) 
    {
        if($v['tipo']==1)
        {
           if($co>0)
           {
              $otorgantes .= " y ";
           }
           $otorgantes .= "{\\b ".validValur($v['participante'])."}";
           $co +=1;
        }
    }

    if($c>1)
      $text = "entregan";
    else 
      $text = "entrega";

    $favorecidos = "";
    $cf = 0;
    foreach ($participantes as $k => $v) 
    {
        if($v['tipo']==2)
        {
           if($cf>0)
           {
              $favorecidos .= " y ";
           }
           $favorecidos .= "{\\b ".validValur($v['participante'])."}";
           $cf +=1;
        }
    }
    $texto = "";
    $texto .= "{\\qj En este acto dejo constancia que ".$otorgantes." ".$text.", en mi presencia, a ".$favorecidos.", ";
    //
    $n = count($datos);
    $medios = "";
    if($n==0)
    {
       //No se exibio medio de pago
      $medios = "efectivo";
      $texto .= " con fecha ".$f[2]." de ".validValur($meses[$f[1]-1])." del ".utf8_decode(año)." ".$f[0].", ";
      $texto .= "el monto en efectivo por la suma de {\\b S/. ".number_format($monto,2)." (".validValur(CantidadEnLetraP($monto)).").}}\\par";
    }
    else
    {
      $c = 0;
      foreach ($datos as $d) 
      {      
        if($c>0)
        {
          $texto .= ", y ";
          $medios .= " y  el ";
        }
        $f = explode("-", $d[4]);     
        $conector = "";
        switch (trim($d[0])) {
             case 'TRANSFERENCIA BANCARIA':
               $conector = "la";
               break;
             case 'TARJETA DE CRÉDITO':
               $conector = "la";
             default:
               $conector = "el";
               break;
           }   
        $texto .= $conector." ".validValur($d[0])." ".utf8_decode(número)." {\\b ".validValur($d[5])."} girado por la entidad financiera ".validValur($d[1]);
        $texto .= " con fecha ".$f[2]." de ".validValur($meses[$f[1]-1])." del ".utf8_decode(año)." ".$f[0].", ";
        $texto .= " por la suma de {\\b S/. ".number_format($d[3],2)." (".validValur(CantidadEnLetraP($d[3])).").}";
        $medios .= $d[0];
        $c+=1;
      }
        $texto .= "}\\par";
    }
    $texto .= "{".$favorecidos.", declara que con ".$conector." ".validValur($medios)." recibido el ".utf8_decode(día)." de hoy, y cuyos datos arriban en el inserto, ";
    $texto .= "dan por cancelado el ".utf8_decode(íntegro)." del precio de venta no teniendo nada que reclamar y renunciando a cualquier hipoteca legal. }\\par";
    $texto .= conformidad($participantes,$datos,$monto,$moneda,$fecha,$servicio,$documento_notarial);
    return $texto;
}
//--------------------------------------------------------------
function conformidad($participantes,$datos,$monto,$moneda,$fecha,$servicio,$documento_notarial)
{
    $texto = "{************************************************************}\\par";
    $n = count($datos);
    if($n>0)
    {
      $texto .= "{De conformidad con los dispuesto por la ley 28194 y el Decreto Supremo 047-2004-EF, dejo constancia de lo siguiente: ======================}\\par";
      $texto .= "{{\\b\ul Primero.-} Que si tuve a la vista el medio de pago. ==================}\\par";
      $texto .= "{{\\b\ul Segundo.-} Que el contrato que se formaliza por esta ".validValur($documento_notarial)." de ".validValur($servicio)." contiene la siguiente ".utf8_decode(información)." relativa al Medio de Pago utilizado: =====}\\par";
    }
    else
    {
      $texto .= "{De conformidad con los dispuesto por la ley 28194 y el Decreto Supremo 047-2004-EF, dejo constancia de lo siguiente: ======================}\\par";
      $texto .= "{{\\b\ul Primero.-} Que no tuve a la vista ".utf8_decode(ningún)." medio de pago. ==================}\\par";
      $texto .= "{{\\b\ul Segundo.-} Que el contrato que se formaliza por esta ".validValur($documento_notarial)." de ".validValur($servicio)." contiene la siguiente ".utf8_decode(información)." relativa al Medio de Pago utilizado: =====}\\par";      
    }
    if($n>0)      
    {
      foreach ($datos as $d) 
      {  
        $t = "{A):}";
        $texto .= $t.fill_lines($t)."\\par";

        $t = "{A.1) Monto total de la ".utf8_decode(operación).": {\\b S/.".number_format($monto,2)."}.}";
        $texto .= $t.fill_lines($t)."\\par";        

        $t = "{A.2) Valor total pagado con Medio de Pago: {\\b S/.".number_format($d[3],2)."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        
        $t  = "{A.3) Moneda en que se ".utf8_decode(realizó).": {\\b ".validValur($d[2])."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        
        $t = "{B):}";
        $texto .= $t.fill_lines($t)."\\par";

        $t = "{B.1) Tipo Medio de Pago: {\\b ".validValur($d[0])."}.}";
        $texto .= $t.fill_lines($t)."\\par";

        $t = "{B.2) ".utf8_decode(Código)." Medio de Pago: {\\b ....}.}";
        $texto .= $t.fill_lines($t)."\\par";
        
        $t = "{C):}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{C.1) Numero de Documento que acredita el uso de Medio de Pago: {\\b ".validValur($d[5])."}.}";
        $texto .= $t.fill_lines($t)."\\par";

        $t = "{D): Empresa del Sistema Financiero que emite el documento: {\\b ".validValur($d[1])."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        
        $t = "{E): Fecha de ".utf8_decode(emisión)." del documento: {\\b ".validValur($d[4])."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        
      }
    }
    else
    {
        $t = "{A):}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{A.1) Monto total de la ".utf8_decode(operación).": {\\b S/.".number_format($monto,2)."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{A.2) Valor total pagado con Medio de Pago: {\\b S/.".number_format($monto,2)."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t  = "{A.3) Moneda en que se ".utf8_decode(realizó).": {\\b ".validValur($moneda)."}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{B):}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{B.1) Tipo Medio de Pago: {\\b Efectivo}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{B.2) ".utf8_decode(Código)." Medio de Pago: {\\b 008}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{C):}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{C.1) Numero de Documento que acredita el uso de Medio de Pago: {\\b No Aplicable}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{D): Empresa del Sistema Financiero que emite el documento: {\\b No Aplicable}.}";
        $texto .= $t.fill_lines($t)."\\par";
        $t = "{E): Fecha de ".utf8_decode(emisión)." del documento: {\\b No Aplicable}.}";
        $texto .= $t.fill_lines($t)."\\par";
    }
    return $texto;
}

/*
  Funcion: datos_menor
  Parametros: @p1
        @p1: Record de todos los participantes.        
    Resumen: Muestra los datos de los menores de edad, correspondientes a autorizaciones de viaje.
*/
function datos_menor($participantes)
{
     $html = '';     
     foreach($participantes as $k => $v)
     {
       if($v['tipo']==2)
       {
         if($c>0) $html .= " y ";        
         $c +=1;
         $html .= "{\\qj\\b ".validValur($v['participante'])."}";          
         $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
         $html .= " con {\\b ".calcular_edad($v['fecha_nac'])." ".utf8_decode(años)."} de edad";         
       }
     }
     return $html;
}

/*
  Funcion: participantes_v
  Parametros: @p1, @p2        
        @p1: Record de todos los participantes.
        @p2: Id del servicio (97-> Autorz. Viaje Exterior, 98 -> Autorz. Viaje Interior)
    Resumen: Muestra los participantes y sus datos asociados a estos (Solo Otorgantes) que intervienen 
             dentro de la autorizacion de viaje.
*/
function participantes_v($participantes,$ids)
{
     $html = '';
     $c = 0;
     $flag = false;
     foreach($participantes as $k => $v)
     {
       if($v['tipo']==1&&$v['es_conyuge']==0)
       {
         if($c>0) $html .= " y ";
         if($v['sexo']=="M")                   
            $html .= "";
         else
            $html .= "";            
         $c +=1;
         $html .= "{\\qj\\b ".trim($v['participante'])."}"; 
         $html .= " de nacionalidad ";
         $html .= "{\\b ".genero("peruano",$v['sexo']).", }";
         $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
         if($ids==97)
         {
            if((int)$v['conyuge']>0)
            {
              $html .= " Y ".getConyuge($participantes,$v['conyuge']); 
              $flag = true;
            }
            else
            {
              $html .= ", con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "departamento de ".utf8_decode(validValur($v['departamento']))." ";
            }
         }
         else
         {
            $html .= ", con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
            $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
            $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
            $html .= "departamento de ".utf8_decode(validValur($v['departamento']))." ";
         }
         $html .= $repre;
       }
     }
     if($ids==97&&$flag==true)
     {
        $html .= ", ambos con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "departamento de ".utf8_decode(validValur($v['departamento'])).". \\par ";
     }
     $html .= '.';
     return $html;
}

/*
  Funcion: participantes_firma
  Parametros: @p1        
        @p1: Record de todos los participantes.
    Resumen: Muestra los nombres de los participantes ordenados para realizar las firmas
*/
function participantes_firma($participantes)
{
    $html .= '{\\qj ';       
    $c = 0;
    foreach($participantes as $k => $v)
    {      
        $r = verRepresentante($v['idparticipante'],$participantes);
        $t = strlen(trim($r));
        if($t==0)
        {
          if($c%2==0)
          {
              $html .= '} \\par \\par \\par \\par {\\qj\\b ';
          }
          $html .= validValur($v['participante']).'     ';
          $c +=1;      
        }        
    }
    $html .= '}';
    return $html;
}
/*
  Funcion: participantes_firma_v
  Parametros: @p1        
        @p1: Record de todos los participantes.
    Resumen: Muestra los nombres de los participantes que solo son otorgantes
            ordenados para realizar las firmas. Empleado para autorizaciones de 
            viajes donde solo firman los apoderados.
*/
function participantes_firma_v($participantes)
{
    $html .= '{\\qj ';       
    $c = 0;
    foreach($participantes as $k => $v)
    {
      if($v['tipo']==1)
      {

        if($c%2==0)
        {
            $html .= '} \\par \\par {\\qj ';            
        }
        $html .= $v['participante'].'     ';        
        $c +=1;
      }      
    }
    $html .= '}';
    return $html;
}
/*
	Funcion: verRepresentante
	Parametros: @p1, @p2 
				@p1: Representa el id del participante principal.
				@p2: Record de todos los participantes.
    Resumen: Buscará el participante que tenga en su columna de idrepresentado el id que pasamos en el
			 parametro @p1, para luego mostrarlo (Solo Nombre) como representante.
*/
function verRepresentante($idp,$participantes)
{
	$html = "";
	$c = 0;
	foreach($participantes as $k => $v)
    {
  		if($v['idrepresentado']==$idp)
  		{
  			if($c>0)
  				$html .= " Y ";
        else
          $html .= "}\par Representado por \t\t: \t{\\b";
  			if($v['sexo']=="M")                   
          $html .= "";
        else
          $html .= " ";
  			$html .= "{\\qj\\b ".validValur($v['participante'])."}";
  			$c += 1;
       }
	}
	return $html;
}

/*
  Funcion: getRepresentante
  Parametros: @p1, @p2 
        @p1: Representa el id del participante principal.
        @p2: Record de todos los participantes.
    Resumen: Buscará el participante que tenga en su columna de "idrepresentado" el id que pasamos en el
       parametro @p1, para luego mostrarlo con sus datos completos como representante.
*/
function getRepresentante($idp,$participantes)
{
  $html = "";
  $c = 0;
  foreach($participantes as $k => $v)
    {
      if($v['idrepresentado']==$idp)
      {
          if($c>0)
            $html .= " y ";
          else
            $html .= "representado ".utf8_decode(según)." poder inscrito en la Partida ".utf8_decode(número)." {\b ".validValur($v['partida'])."} del Registro de Mandatos y Poderes de la Oficina Registral de ".validValur($v['zona']).", por ";
          if($v['sexo']=="M")                   
            $html .= "";
          else
            $html .= " ";
          $html .= "{\\qj\\b ".validValur($v['participante'])."}";
          $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
          $html .= ", quien manifiesta ser de nacionalidad ";
          $html .= "{\\b ".genero("peruano",$v['sexo']).", }";
          $html .= utf8_decode(ocupación)." {\\b ".validValur($v['ocupacion'])."}, ";
          $html .= "estado civil ".validValur($v['estado_civil']).", ";
          $html .= "con domicilio en ".(validValur($v['dir'])).", ";
          $html .= "distrito de ".(validValur($v['distrito'])).", ";
          $html .= "provincia de ".(validValur($v['provincia'])).", ";
          $html .= "departamento de ".(validValur($v['departamento'])).".";
          $c += 1;
      }
  }
  return $html;
}


/*
	Funcion: verOtorgantes
	Parametros: @p1
				@p1: Record de todos los participantes.
	Resumen: Obtener y devolver el nombre de todos los participantes	
			 que se encuentren en calidad de otorgantes.
*/
function verOtorgantes($participantes)
{
      $html = '';
      $c = 0;
      foreach($participantes as $k => $v)
      {
        if($v['tipo']==1)      
        {
    			if($c>0) $html .= " Y }\par {\\qj\\b\t\t\t\t\t";
          if($v['sexo']=="M")                   
             $html .= "";
          else
            $html .= "";            
          $c +=1;
    		  $html .= "{\\qj\\b ".validValur($v['participante'])."}"; 
    		  $repre = verRepresentante($v['idparticipante'],$participantes);
          $html .= $repre;
       }        
      }
      $html .= '.';
      return $html;
}


/*
	Funcion: verFavorecidos
	Parametros: @p1
				@p1: Record de todos los participantes.
	Resumen: Obtener y devolver el nombre de todos los participantes	
			 que se encuentren en calidad de Favorecidos.
*/
function verFavorecidos($participantes)
{
      $html = '';
      $c = 0;
      foreach($participantes as $k => $v)
      {        
        if($v['tipo']==2)      
        {
    			if($c>0) $html .= " Y }\par {\\qj\\b\t\t\t\t\t";
                if($v['sexo']=="M")                   
                   $html .= "";
                else
                  $html .= "";
               $c +=1;      
    		   $html .= "{\\qj\\b ".validValur($v['participante'])."}";   
           $repre = verRepresentante($v['idparticipante'],$participantes);
           $html .= $repre;        
        }        
      }	  
      $html .= ''; 
      return $html;
}

/*
	Funcion: verIntervinientes
	Parametros: @p1
				@p1: Record de todos los participantes.
	Resumen: Obtener y devolver el nombre de todos los participantes	
			 que se encuentren en calidad de Intervinientes, más nó representantes.
*/
function verIntervinientes($participantes)
{
      $html = '';
      $c = 0;
      foreach($participantes as $k => $v)
      {
        if($v['tipo']==3&&$v['idrepresentado']>0)      
        {
            if($c>0) 
				$html .= " Y }\par {\\qj\\b\t\t\t\t\t";
			else 
				$html .= "con intervencion de ";
            if($v['sexo']=="M")                   
               $html .= "";
            else
              $html .= "";
            $html .= "{\\qj\\b ".validValur($v['participante'])."}";
           $c +=1;    
        }        
      }      
      return $html.'.';
}

/*
  Funcion: cuerpoVehiculo
  Parametros: @p1
        @p1: Array que contiene todos los datos de los vehiculos.
  Resumen: Mostrar los datos y caracteristicas de los vehiculos.
*/
function cuerpoVehiculo($v)
{
    /* $v (Array)
          [0] -> Clase Vehiculo
          [1] -> Marca Vehiculo
          [2] -> Año Fabricacion
          [3] -> Modelo Vehiculo
          [4] -> Color Vehiculo
          [5] -> Motor
          [6] -> Nro de Cilindros
          [7] -> Serie
          [8] -> Nro de ruedas
          [9] -> Combustible
          [10]-> Fecha de Inscripcion
          [11]-> Carroceria
          [12]-> Nro Placa

          $html = "";    
          $html .= "{\\qj PLACA DE RODAJE ".utf8_decode(N°).": \b ".$v[12].".} \\par";
          $html .= "{\\qj CLASE: \b ".$v[0].".} \\par";
          $html .= "{\\qj ".utf8_decode(AÑO).": \b ".$v[2].".} \\par ";
          $html .= "{\\qj MARCA: \b ".$v[1].". } \\par";
          $html .= "{\\qj SERIE ".utf8_decode(N°).": \b ".$v[7].".} \\par";
          $html .= "{\\qj MOTOR ".utf8_decode(N°).": \b ".$v[5].".} \\par";
          $html .= "{\\qj MODELO: \b ".$v[3].".} \\par ";
          $html .= "{\\qj CARROCERIA: \b ".$v[11].".} \\par";
          $html .= "{\\qj COLOR: \b ".$v[4].".} \\par ";
          $html .= "{\\qj ".utf8_decode(N°)." RUEDAS: \b ".$v[8].". } \\par"; 

    */



    $html = "";    
    $text  = "{\\qj Placa de Rodaje ".utf8_decode(N°).": \b ".$v[12].".}";
    $html .= $text.fill_lines($text)."\\par";
    
    $text  = "{\\qj Clase: \b ".validValur($v[0]).".}";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj ".utf8_decode(Año).": \b ".validValur($v[2]).".}";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj Marca: \b ".validValur($v[1]).". }";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj Serie ".utf8_decode(N°).": \b ".$v[7].".}";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj Motor ".utf8_decode(N°).": \b ".$v[5].".}";
    $html .= $text.fill_lines($text)."\\par";    

    $text  = "{\\qj Modelo: \b ".validValur($v[3]).".}";
    $html .= $text.fill_lines($text)."\\par";    

    $text  = "{\\qj Carroceria: \b ".validValur($v[11]).".}";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj Color: \b ".validValur($v[4]).".}";
    $html .= $text.fill_lines($text)."\\par";

    $text  = "{\\qj ".utf8_decode(N°)." Ruedas: \b ".$v[8].". }";    
    $html .= $text.fill_lines($text)."\\par";

    return $html;    
}

//Cambia una palabra al genero que le corresponde dependiendo del sexo del participante
function genero($palabra,$sexo)
{
    $t = strlen($palabra);
    $p = substr($palabra, 0,$t-1);
    $g = "";
    if($sexo=="M")
      $g = "o";
    else
      $g = "a";
    return $p.$g;
}

//Obtiene el nombre completo del participante que está como conyuge.
function getConyuge($p,$idc)
{
   $conyuge = "";
    foreach ($p as $key => $value) {
      if($value['idparticipante']==$idc)
      {
          $conyuge = "{\\b ".validValur($value['participante'])."}";
      }
    }
    return $conyuge;
}

//Obtiene el numero entero de participantes que están como otorgantes
function nOtorgantes($p)
{ $c = 0;
    foreach ($p as $key => $value) {
      if($value['tipo']==1)      
          $c += 1;
    }
    return $c;
}

//Obtiene el numero entero de participantes que están como favorecidos 
function nAfavor($p)
{    
    $c = 0;
    foreach ($p as $key => $value) {
      if($value['tipo']==2)      
          $c += 1;
    }
    return $c;
}


/***********************
  Funciones utilitarias
***********************/
function validValur($v)
{
    //Valida y formatea Valores
    if(trim($v)=="")
     return "<<Campo-sin-valor>>";
    else
     return ucname(trim(utf8_decode($v)));
}

function fill_lines($text)
{    
    $text = clear_tags($text);
    $t = strlen($text);
    $str="";
    $w = 59;
    if($t>0)
    {
        $c = floor($t/$w);
        $n = $w*$c+$w-$t;
        $n = $n-3;
        $str = "";
        for($i=0;$i<=$n;$i++)
        {
          $str .= "=";
        }
    }    
    return $str;
}


function clear_tags($text)
{
   $text = str_replace("{", "", $text);
   $text = str_replace("}", "", $text);
   $text = str_replace("\par", "", $text);
   $text = str_replace("\b", "", $text);
   $text = str_replace("\qj", "", $text);
   $text = str_replace("\q", "", $text);
   $text = str_replace("\ ", "", $text);
   return trim($text);
}

function ucname($string) 
{
    //Convierte el primer caracter de cada palabra a mayuscula
    $string =ucwords(strtolower($string));

    foreach (array('-', '\'') as $delimiter) {
      if (strpos($string, $delimiter)!==false) {
        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
      }
    }
    return $string;
}

function values($v)
{
  if($v==NULL||$v=="")
  {
    $v = 'NULL';
  }
  return $v;
}
function calcular_edad($fecha)
{
 if(trim($fecha)!="")
   {
   list($Y,$m,$d)=explode("-",$fecha);
   return(date("md")<$m.$d?date("Y")-$Y-1:date("Y")-$Y);
   }
else { return "";}
}

function leef($fichero)
{
    $texto = file($fichero);
    $tamleef = sizeof($texto);
    for($n=0; $n<$tamleef; $n++){
            $todo = $todo.$texto[$n];
    }
    return $todo;
} 
function Completar($Str, $Agr)
{
    $tamanio = strlen($Str) +  $Agr;
    for($i=1; $i<80 - $tamanio; $i++)
    {
      $Str = $Str.".";
    }
    return $Str;
}


//Funciones de Edicion
function negrita($texto){ $texto = "{\b ".$texto."}"; }

function w($tx)
{
  //En desarrollo
  $atx = explode(" ", $tx);
  $car = "p";
}

?>