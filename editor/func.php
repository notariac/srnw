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
         if($tam>2)
         {
           $t1 = substr($v,0,1);
           $t2 = substr($v,1,$tam);
           $cad .= strtoupper($t1).strtolower($t2)." ";
         }
         else 
         {
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
            {                

                $html .= "De otra parte ";
            }
              

            if($v['sexo']=="M")
               $html .= "Don ";
            else
              $html .= utf8_decode(Doña)." ";

            $html .= "<b>".validValur($v['participante'])."</b>, ";
            $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." <b> ".validValur($v['nrodocumento'])."</b>";
            if($v['idcliente_tipo']==1)
            {
              //Si es Natural
                $html .= ", quien manifiesta ser de nacionalidad ";
                $html .= "<b> ".genero("Peruano",$v['sexo']).", </b>";
                $html .= utf8_decode(ocupación)." <b>".validValur($v['ocupacion'])."</b>, ";
                //Obtenemos si tiene algun representante
                $repre = getRepresentante($v['idparticipante'],$participantes);
                $html .= $repre;
                $html .= "estado civil ".validValur($v['estado_civil'])." ";

                if((int)$v['conyuge']>0)
                  $html .= "con ".getConyuge($participantes,$v['conyuge']);

                $html .= ", con domicilio en ".validValur($v['dir']).", ";
                $html .= "distrito de ".validValur($v['distrito']).", ";
                $html .= "provincia de ".validValur($v['provincia']).", ";
                $html .= "departamento de ".validValur($v['departamento'])."; ";

            }
            else
            {
              //Si es juridica
              $html .= ", con domicilio en ".validValur($v['dir']).", ";
              $html .= "distrito de ".validValur($v['distrito']).", ";
              $html .= "provincia de ".validValur($v['provincia']).", ";
              $html .= "departamento de ".validValur($v['departamento']).", ";
              $repre = getRepresentante($v['idparticipante'],$participantes);
              $html .= $repre."; ";
            }
        
                if($v['tipo']==1)
                {
                   $numero_participantes = nOtorgantes($participantes);
                   $denominacion = denominacion($v['participacion'],$numero_participantes,$v['sexo']);
                   if($numero_participantes==1)
                    {
                       $denominacion = 'a quien se le '.utf8_decode(denominará).' '.'<b>"'.validValur($v['participacion']).'"</b>'.'.=============<br>';
                    }
                   else
                    {
                       $denominacion = 'a quienes se les '.utf8_decode(denominará).' '.'<b>"'.validValur($v['participacion']).'"</b>'.'.=============<br>';
                    }
                }
                if($v['tipo']==2)
                {
                   $numero_participantes = nAfavor($participantes);
                   if($numero_participantes==1)
                   { 
                      $denominacion = 'a quien se le '.utf8_decode(denominará).' '.'<b>"'.validValur($v['participacion']).'"</b>'.'.=============<br>';
                   }
                   else
                   {
                      $denominacion = 'a quienes se les '.utf8_decode(denominará).' '.'<b>"'.validValur($v['participacion']).'"</b>'.'.=============<br>';
                   }
                }

                if(($c+1)==$numero_participantes)
                    $html .= $denominacion;
            

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
     $html = genero("hijo",$v['sexo']);     
     foreach($participantes as $k => $v)
     {
       if($v['tipo']==2)
       {
         if($c>0) $html .= " y ";        
         $c +=1;
         $html .= " <b>".validValur(trim($v['participante']))."</b>";
         $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." <b>".validValur($v['nrodocumento'])."</b>";
         $html .= " con <b>".calcular_edad($v['fecha_nac'])." (".validValur(CantidadEnLetra(calcular_edad($v['fecha_nac']))).") ".utf8_decode(años)."</b> de edad";
         
       }
     }
     return $html.".";
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
     $html = 'Por el presente documento, yo ';
     $c = 0;
     $flag = false;
     foreach($participantes as $k => $v)
     {
       if($v['tipo']==1&&$v['es_conyuge']==0)
       {
         if($c>0) $html .= " y ";
         if($v['sexo']=="M")                   
            $html .= "Don ";
         else
            $html .= utf8_decode(Doña)." ";            
         $c +=1;
         $html .= "<b>".validValur($v['participante'])."</b>"; 
         $html .= " de nacionalidad ";
         $html .= "<b>".genero("Peruano",$v['sexo']).", </b>";
         $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." <b>".validValur($v['nrodocumento'])."</b>";
         if($ids==97)
         {
            if((int)$v['conyuge']>0)
            {
              $html .= " y ".getConyuge($participantes,$v['conyuge']); 
              $flag = true;
            }
            else
            {
              $html .= ", con domicilio en el inmueble ubicado en ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "departamento de ".utf8_decode(validValur($v['departamento']))."";
            }
         }
         else
         {
             $html .= ", con domicilio en el inmueble ubicado en ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "departamento de ".utf8_decode(validValur($v['departamento']))."";
         }
         $html .= $repre;
       }
     }
     if($ids==97&&$flag==true)
     {
        $html .= ", ambos con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "departamento de ".utf8_decode(validValur($v['departamento']))."<br/>";
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
    $html .= '<b>';       
    $c = 0;
    foreach($participantes as $k => $v)
    {      
        $r = verRepresentante($v['idparticipante'],$participantes);
        $t = strlen(trim($r));
        if($t==0)
        {
          if($c%2==0)
          {
              $html .= '} <br/></br>  ';
          }
          $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.validValur($v['participante']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
          $c +=1;      
        }        
    }
    $html .= '</b>';
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
    $html .= '<b>';       
    $c = 0;
    foreach($participantes as $k => $v)
    {
      if($v['tipo']==1)
      {

        if($c%2==0)
        {
            $html .= '<br><br> ';            
        }
        $html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.validValur($v['participante']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
        $c +=1;
      }      
    }
    $html .= '</b>';    
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
          $html .= " y ";
        else
          $html .= " representado por ";
        if($v['sexo']=="M")                   
          $html .= "Don ";
        else
          $html .= utf8_decode(Doña)." ";
        $html .= "<b>".validValur(trim($v['participante']))."</b>";
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
          $html .= "representado por ";
        if($v['sexo']=="M")                   
          $html .= "Don ";
        else
          $html .= utf8_decode(Doña)." ";
        $html .= "<b>".trim($v['participante'])."</b>";
        $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(N°)." <b>".validValur($v['nrodocumento'])."</b>";
        $html .= ", quien manifiesta ser de nacionalidad ";
        $html .= "<b> ".genero("Peruano",$v['sexo']).", </b>";
        $html .= utf8_decode(ocupación)." <b>".validValur($v['ocupacion'])."</b>, ";
        $html .= "estado civil ".validValur($v['estado_civil']).", ";
        $html .= "con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "distrito de ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "provincia de ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "departamento de ".utf8_decode(validValur($v['departamento'])).".<br> ";
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
          if($c>0) $html .= " y ";
          if($v['sexo']=="M")                   
             $html .= "Don ";
          else
            $html .= utf8_decode(Doña)." ";            
          $c +=1;
          $html .= "<b>".validValur($v['participante'])."</b>"; 
          $repre = verRepresentante($v['idparticipante'],$participantes);
          $html .= $repre;
       }        
      }
      $html .= '';
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
          if($c>0) $html .= " y ";
                if($v['sexo']=="M")                   
                   $html .= "Don ";
                else
                  $html .= utf8_decode(Doña)." ";
               $c +=1;      
           $html .= "<b>".validValur($v['participante'])."</b>";   
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
        $html .= "con intervencion de ";
            if($v['sexo']=="M")                   
               $html .= "Don ";
            else
              $html .= utf8_decode(Doña)." ";
            $html .= "<b>".validValur($v['participante'])."</b>";
           $c +=1;    
        }        
      }      
      return $html.'';
}

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

function getConyuge($p,$idc)
{
   $conyuge = "";
    foreach ($p as $key => $value) {
      if($value['idparticipante']==$idc)
      {
          $conyuge = "<b>".validValur($value['participante'])."</b>";
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
     return "[Campo-sin-valor]";
    else
     return trim(fupper(utf8_decode($v)));
}

function denominacion($participacion,$num,$g)
{
    // Retornará la correcta denominacion para los participantes,
    // teniendo en cuenta la cantidad y el género de los participantes y 
    // la estructura de la participacion real.
    $partp = explode(" ", $participantes);
    $n = count($partp);    
    if($n>1)
    {

    }
}
?>