<?php 

//Migrador a la nueva version.
function goNewVersion($html)
{
    $html = str_replace("#dadada", "#fafafa", $html);
    $html = str_replace("14pt", "19px", $html);
    $html = str_replace("13pt", "18px", $html);
    $html = str_replace("12pt", "16px", $html);
    return $html;
}


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
                if($idservicio==100)
                {
                    //poder fuera de registro
                    $tam = strlen($html);
                    $html = substr($html, 0, ($tam-5));

                    $html .= ", aquien en adelante se denominara el <b>El Poderdante.</b>";
                    $html .= "<b>El Poderdante</b>, es mayor de edad, ".utf8_decode(hábil)." para contratar, con entera libertad, conocimiento y capacidad, ".utf8_decode(según)." lo dispuesto en el ".utf8_decode(artículo)." ".utf8_decode('55º')." de la Ley de Notariado Decreto Legislativo 1049, de lo que doy fe; ".utf8_decode(examinándosele)." para otorgar ";
                    $html .= "<b>Poder Fuera de Registro,</b> a favor de:<br>";
                }
                else
                {
                    $html .= "";
                }
            }
              

            if($v['sexo']=="M")
               $html .= "";
            else
              $html .= "";

            $html .= "<b>".validValur($v['participante'])."</b>, ";
            $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(número)." <b> ".validValur($v['nrodocumento'])."</b>";
            if($v['idcliente_tipo']==1)
            {
              //Si es Natural
                $html .= ", quien manifiesta ser de nacionalidad ";
                $html .= "Peruana, ";                
                //Obtenemos si tiene algun representante
                $repre = getRepresentante($v['idparticipante'],$participantes);
                $html .= $repre;

                if(trim($repre)=="")
                {
                    $html .= utf8_decode(ocupación)." <b>".validValur($v['ocupacion'])."</b>, ";
                    $html .= "estado civil ".validValur($v['estado_civil'])."";
                    if((int)$v['conyuge']>0)
                      {$html .= "con ".getConyuge($participantes,$v['conyuge']);}
                    $html .= ", con domicilio en ".validValur($v['dir']).", ";
                    $html .= "Distrito de <b>".validValur($v['distrito'])."</b>, ";
                    $html .= "Provincia de <b>".validValur($v['provincia'])."</b>, ";
                    $html .= "Departamento de <b>".validValur($v['departamento'])."</b>; ";
                }

            }
            else
            {
              //Si es juridica
              $html .= ", con domicilio en ".validValur($v['dir']).", ";
              $html .= "Distrito de ".validValur($v['distrito']).", ";
              $html .= "Provincia de ".validValur($v['provincia']).", ";
              $html .= "Departamento de ".validValur($v['departamento']).", ";
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
         $html .= " con <b>".calcular_edad($v['fecha_nac'])." (".validValur(CantidadEnLetra(calcular_edad($v['fecha_nac']))).") ".utf8_decode(años)."</b> de edad, ";         
         $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(número)." <b>".validValur($v['nrodocumento'])."</b>";
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
         $html .= "<b> Peruana, </b>";
         $html .= "identificado con ".validValur($v['documento'])."  ".utf8_decode(número)." <b>".validValur($v['nrodocumento'])."</b>";
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
              $html .= "Distrito de <b>".utf8_decode(validValur($v['distrito']))."</b>, ";
              $html .= "Provincia de <b>".utf8_decode(validValur($v['provincia']))."</b>, ";
              $html .= "Departamento de <b>".utf8_decode(validValur($v['departamento']))."</b>";
            }
         }
         else
         {
             $html .= ", con domicilio en el inmueble ubicado en ".utf8_decode(validValur($v['dir'])).", ";
              $html .= "Distrito de ".utf8_decode(validValur($v['distrito'])).", ";
              $html .= "Provincia de ".utf8_decode(validValur($v['provincia'])).", ";
              $html .= "Departamento de ".utf8_decode(validValur($v['departamento']))."";
         }
         $html .= $repre;
       }
     }
     if($ids==97&&$flag==true)
     {
        $html .= ", ambos con domicilio en ".utf8_decode(validValur($v['dir'])).", ";
        $html .= "Distrito de ".utf8_decode(validValur($v['distrito'])).", ";
        $html .= "Provincia de ".utf8_decode(validValur($v['provincia'])).", ";
        $html .= "Departamento de ".utf8_decode(validValur($v['departamento']))."<br/>";
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
        $html .= " identificado con ".validValur($v['documento'])."  ".utf8_decode(número)." <b>".validValur($v['nrodocumento'])."</b>";
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
             $html .= "";
          else
            $html .= "";            
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
                   $html .= "";
                else
                  $html .= "";
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
        $html .= " y ";
      else 
        $html .= "con intervencion de ";
            if($v['sexo']=="M")                   
               $html .= "";
            else
              $html .= "";
            $html .= "<b>".validValur($v['participante'])."</b>";
           $c +=1;    
        }        
      }      
      return $html.'';
}


function cuerpoVehiculo($v)
{
    /*    $v (Array)
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
    $text  = "Placa de Rodaje ".utf8_decode(N°).": <b>".$v[12]."</b>.";
    $html .= $text.fill_lines($text)."<br>";
    
    $text  = "Clase: <b>".validValur($v[0])."</b>.";
    $html .= $text.fill_lines($text)."<br>";

    $text  = utf8_decode(Año).": <b> ".validValur($v[2])."</b>.";
    $html .= $text.fill_lines($text)."<br>";

    $text  = "Marca: <b>".validValur($v[1]).".</b>";
    $html .= $text.fill_lines($text)."<br>";

    $text  = "Serie ".utf8_decode(N°).": <b>".$v[7]."</b>.";
    $html .= $text.fill_lines($text)."<br>";

    $text  = "Motor ".utf8_decode(N°).": <b> ".$v[5]."</b>.";
    $html .= $text.fill_lines($text)."<br>";    

    $text  = "Modelo: <b>".$v[3]."</b>.";
    $html .= $text.fill_lines($text)."<br>";    

    $text  = "Carroceria: <b> ".validValur($v[11])."</b>.";
    $html .= $text.fill_lines($text)."<br>";

    $text  = "Color: <b>".validValur($v[4])."</b>.";
    $html .= $text.fill_lines($text)."<br>";

    $text  = utf8_decode(N°)." Ruedas: <b>".$v[8]."</b>.";    
    $html .= $text.fill_lines($text)."<br>";

    return $html;    
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
     {return "<b>[Campo-vacio]</b>";}
    else
    {
      if($v=="Distrito"||$v=="Provincia"||$v=="Departamento")
      {
         $v = "<b>[Campo-vacio]</b>";
      }
     return ucname(trim(utf8_decode($v)));
   }
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
function ucname($string) 
{
    //Convierte el primer caracter de cada palabra a mayuscula
    $string =ucwords(strtolower($string));

    foreach (array('-', '\'') as $delimiter) {
      if (strpos($string, $delimiter)!==false) 
      {
        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
      }
    }
    return $string;
}

function fill_lines($text)
{    
    //$text = clear_tags($text);
    $t = strlen($text);
    $str="";
    $w = 58;
    if($t>0)
    {
        $c = floor($t/$w);
        $n = $w*$c+$w-$t;
        $n = $n-1;
        $str = "";
        for($i=0;$i<=$n;$i++)
        {
          $str .= "=";
        }
    }    
    return $str;
}


?>