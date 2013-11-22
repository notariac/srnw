<?php 
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
?>
