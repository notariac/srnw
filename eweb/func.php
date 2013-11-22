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
         $t = explode(" ",$text);
         $cad = "";
         foreach($t as $v)
         {
           $tam = strlen($v);
           $t1 = substr($v,0,1);
           $t2 = substr($v,1,$tam);
           $cad .= strtoupper($t1).$t2." ";
         }
	return $cad;
    }
?>