<?php
function values($v)
{
  if($v==NULL||$v=="")
  {
    $v = 'NULL';
  }
  return $v;
}
	function addSlash($str)
    {
       $str = str_replace("'",'\"',$str);
       return $str;
    }

    function stripSlash($str)
    {
       $str = str_replace('\"',"'",$str);
       return $str;
    }
    function fupper($text)
    {
       //$text = utf8_decode($text);
       $t = explode(" ",$text);
       $cad = "";
       foreach($t as $v)
       {
         $tam = strlen($v);
         if($tam>3)
         {
           $t1 = substr($v,0,1);
           $t2 = substr($v,1,$tam);
           $cad .= strtoupper($t1).strtolower($t2)." ";
         }
         else {
          $cad .= strtolower($v)." ";
         }
         
       }
      return $cad;
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
              $html .= "DE OTRA PARTE ";

            if($v['sexo']=="M")
               $html .= "DON {\\qj\\b\\fs22 ";
            else
              $html .= utf8_decode(DOÑA)." {\\qj\\b\\fs22 ";

            $html .= validValur($v['participante']).", }";
            $html .= "IDENTIFICADO CON ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
            if($v['idcliente_tipo']==1)
            {
              //Si es Natural
                $html .= ", QUIEN MANIFIESTA SER DE NACIONALIDAD ";
                $html .= "{\\b ".genero("PERUANO",$v['sexo']).", }";
                $html .= utf8_decode(OCUPACIÓN)." {\\b".validValur($v['ocupacion'])." }, ";
                //Obtenemos si tiene algun representante
                $repre = getRepresentante($v['idparticipante'],$participantes);
                $html .= $repre;
                $html .= "ESTADO CIVIL ".validValur($v['estado_civil'])." ";

                if((int)$v['conyuge']>0)
                  $html .= "CON ".getConyuge($participantes,$v['conyuge']);

                $html .= ", CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
                $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
                $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
                $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento'])).". \\par ";

            }
            else
            {
              //Si es juridica
              $html .= ", CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento'])).", ";
              $repre = getRepresentante($v['idparticipante'],$participantes);
              $html .= $repre;

            }
            $c += 1;
          }
        }
    }
    return $html;
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
         if($c>0) $html .= " Y ";        
         $c +=1;
         $html .= "{\\qj\\b\\fs22 ".trim($v['participante'])."}";          
         $html .= " IDENTIFICADO CON ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
         $html .= " CON {\\b ".calcular_edad($v['fecha_nac'])." ".utf8_decode(AÑOS)."} DE EDAD";
         
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
         if($c>0) $html .= "Y ";
         if($v['sexo']=="M")                   
            $html .= "DON ";
         else
            $html .= utf8_decode(DOÑA)." ";            
         $c +=1;
         $html .= "{\\qj\\b\\fs22 ".trim($v['participante'])."}"; 
         $html .= " DE NACIONALIDAD ";
         $html .= "{\\b ".genero("PERUANO",$v['sexo']).", }";
         $html .= "IDENTIFICADO CON ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
         if($ids==97)
         {
            if((int)$v['conyuge']>0)
            {
              $html .= " Y ".getConyuge($participantes,$v['conyuge']); 
              $flag = true;
            }
            else
            {
              $html .= ", CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento']))." ";
            }
         }
         else
         {
            $html .= ", CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
            $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
            $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
            $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento']))." ";
         }
         $html .= $repre;
       }
     }
     if($ids==97&&$flag==true)
     {
        $html .= ", AMBOS CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento'])).". \\par ";
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
    $html .= '{\\qj\\fs22 ';       
    $c = 0;
    foreach($participantes as $k => $v)
    {      
        $r = verRepresentante($v['idparticipante'],$participantes);
        $t = strlen(trim($r));
        if($t==0)
        {
          if($c%2==0)
          {
              $html .= '} \\par \\par {\\qj\\b\\fs22 ';
          }
          $html .= trim($v['participante']).'     ';
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
    $html .= '{\\qj\\fs22 ';       
    $c = 0;
    foreach($participantes as $k => $v)
    {
      if($v['tipo']==1)
      {

        if($c%2==0)
        {
            $html .= '} \\par \\par {\\qj\\fs22 ';            
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
          $html .= " REPRESENTADO POR ";
  			if($v['sexo']=="M")                   
          $html .= "DON ";
        else
          $html .= utf8_decode(DOÑA)." ";
  			$html .= "{\\qj\\b\\fs22 ".trim($v['participante'])."}";
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
          $html .= " Y ";
        else
          $html .= "REPRESENTADO POR ";
        if($v['sexo']=="M")                   
          $html .= "DON ";
        else
          $html .= utf8_decode(DOÑA)." ";
        $html .= "{\\qj\\b\\fs22 ".trim($v['participante'])."}";
        $html .= " IDENTIFICADO CON ".validValur($v['documento'])."  ".utf8_decode(N°)." {\\b ".validValur($v['nrodocumento'])."}";
        $html .= ", QUIEN MANIFIESTA SER DE NACIONALIDAD ";
        $html .= "{\\b ".genero("PERUANO",$v['sexo']).", }";
        $html .= utf8_decode(OCUPACIÓN)." {\\b".validValur($v['ocupacion'])." }, ";
        $html .= "ESTADO CIVIL ".validValur($v['estado_civil']).", ";
        $html .= "CON DOMICILIO EN ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "DISTRITO DE ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "PROVINCIA DE ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "DEPARTAMENTO DE ".utf8_decode(validValur($v['departamento'])).". \\par ";
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
    			if($c>0) $html .= " Y ";
          if($v['sexo']=="M")                   
             $html .= "DON ";
          else
            $html .= utf8_decode(DOÑA)." ";            
          $c +=1;
    		  $html .= "{\\qj\\b\\fs22 ".validValur($v['participante'])."}"; 
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
    			if($c>0) $html .= " Y ";
                if($v['sexo']=="M")                   
                   $html .= "DON ";
                else
                  $html .= utf8_decode(DOÑA)." ";
               $c +=1;      
    		   $html .= "{\\qj\\b\\fs22 ".validValur($v['participante'])."}";   
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
				$html .= " Y ";
			else 
				$html .= "CON INTERVENCION DE ";
            if($v['sexo']=="M")                   
               $html .= "DON ";
            else
              $html .= utf8_decode(DOÑA)." ";
            $html .= "{\\qj\\b\\fs22 ".validValur($v['participante'])."}";
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
    $html = "";    
    $html .= "{\\qj\\fs22 PLACA DE RODAJE ".utf8_decode(N°)." ".$v[12].".- ";
    $html .= "CLASE: ".$v[0].".- ";
    $html .= utf8_decode(AÑO).": ".$v[2].".- ";
    $html .= "MARCA: ".$v[1].".- ";
    $html .= "SERIE ".utf8_decode(N°).": ".$v[7].".- ";
    $html .= "MOTOR ".utf8_decode(N°).": ".$v[5].".- ";
    $html .= "MODELO: ".$v[3].".- ";
    $html .= "CARROCERIA: ".$v[11].".- ";
    $html .= "COLOR: ".$v[4].".- ";
    $html .= utf8_decode(N°)." RUEDAS: ".$v[8].". }\\par";    
    return $html;    
}

function genero($palabra,$sexo)
{
    $t = strlen($palabra);
    $p = substr($palabra, 0,$t-1);
    $g = "";
    if($sexo=="M")
      $g = "O";
    else
      $g = "A";
    return $p.$g;
}
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
function nOtorgantes($p)
{ $c = 0;
    foreach ($p as $key => $value) {
      if($value['tipo']==1)      
          $c += 1;
    }
    return $c;
}
function nAfavor($p)
{
    $c = 0;
    foreach ($p as $key => $value) {
      if($value['tipo']==2)      
          $c += 1;
    }
    return $c;
}
function validValur($v)
{
    if(trim($v)=="")
     return "<<Campo-sin-valor>>";
    else
     return trim(utf8_decode($v));
}
?>
