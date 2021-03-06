<?php
ini_set ( "memory_limit" , "500M" );
if(isset($_POST['exportar']))
{
    function dl_file($file)
    {
        if (!is_file($file)) { die("<b>404 File not found!</b>"); }
        $len = filesize($file);
        $filename = basename($file);
        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        $ctype="application/force-download";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $ctype");
        $header="Content-Disposition: attachment; filename=".$filename.";";
        header($header );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len);
        @readfile($file);
        exit;
    }
    $allow_url_override = 1;
    if(!$allow_url_override || !isset($file_to_include))
    {
        $file_to_include = $_FILES["archivo"]["tmp_name"];
    }
    if(!$allow_url_override || !isset($max_rows))
    {
        $max_rows = 0; //USE 0 for no max
    }
    if(!$allow_url_override || !isset($max_cols))
    {
        $max_cols = 5; //USE 0 for no max
    }
    if(!$allow_url_override || !isset($debug))
    {
        $debug = 0;  //1 for on 0 for off
    }
    if(!$allow_url_override || !isset($force_nobr))
    {
        $force_nobr = 1;  //Force the info in cells not to wrap unless stated explicitly (newline)
    }
    require_once("Excel/reader.php");
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($file_to_include);
    error_reporting(E_ALL ^ E_NOTICE);
    function clearText($text,$max,$min,$texpad='0')
    {
        $text = trim($text);
        if($min>0)
        {
            $text = str_pad($text, $min,$texpad, 0);
        }
        $text = substr($text, 0, $max);
        return $text;
    }
    function Num($num,$decimales)
    {
        if(trim($num)=="")
            $n = 0;
        else 
            $n = $num;
        return number_format($n,$decimales,'.','');
    }
    $anio = $_POST['anio'];
    $mes = str_pad($_POST['mes'], 2,'0',0);
    $sql = "";
    for($i=14;$i<=$data->sheets[0]['numRows'];$i++)
    {
        //Verificamos si es un bloque de comprobantes
        $nums = $data->sheets[0]['cells'][$i][7];
        $ncompro = explode("/", $nums); //Numero de comprobante
        $n = count($ncompro);

        if($n>1)
        {
            $ncmin = (int)$ncompro[0];
            $ncmax = (int)$ncompro[$n-1];
            $dif = $ncmax-$ncmin;
        }
        else 
        {
            $dif = 1;
            $ncmin = $ncmax = (int)$ncompro[0];
        }

        for($j=0;$j<$dif;$j++)
        {        
            $namec = $data->sheets[0]['cells'][$i][10];
            $correlativo = trim($data->sheets[0]['cells'][$i][1]);
            if(strtoupper(trim($namec))!="ANULADO"&&strtoupper(trim($namec))!="PERDIDO"&&$correlativo!="")
            {
                $ncomprobante = $ncmin+$j;
                $tipocomprobante = clearText($data->sheets[0]['cells'][$i][4],2,2);
                if($data->sheets[0]['cells'][$i][3]=="")
                {
                    $data->sheets[0]['cells'][$i][3]=$data->sheets[0]['cells'][$i][2];
                }
                //            1                2                       3                                        4
                $sql .= $anio.$mes."00|".$correlativo."|".$data->sheets[0]['cells'][$i][2]."|".$data->sheets[0]['cells'][$i][3]."|";    
                //            5                                        6                       7                8                   9
                $sql .= $tipocomprobante."|".clearText($data->sheets[0]['cells'][$i][5],6,4)."|0|".clearText($ncomprobante,20,10)."|0|";

                $td = $data->sheets[0]['cells'][$i][8];
                if(trim($td)!="")
                {   
                    if($td=='6')
                        $nd = clearText($data->sheets[0]['cells'][$i][9],11,11);
                    else 
                        $nd = $data->sheets[0]['cells'][$i][9];
                }
                else
                {
                    $td = 1;
                    $nd = $data->sheets[0]['cells'][$i][9];
                    if(trim($nd)=="")
                    {
                        $nd = "00000000";
                    }
                }
                //       10     11            12
                $sql .= $td."|".$nd."|".clearText($namec,60,0)."|";            

                $baseImponible01 = Num($data->sheets[0]['cells'][$i][11],2);
                $igv01 = Num($data->sheets[0]['cells'][$i][12],2);

                $baseImponible02 = Num($data->sheets[0]['cells'][$i][15],2);
                $igv02 = Num($data->sheets[0]['cells'][$i][16],2);

                $baseImponible03 = '0.00';
                $igv03 = '0.00';

                $valor = Num($data->sheets[0]['cells'][$i][17],2);
                $isc = Num($data->sheets[0]['cells'][$i][18],2);
                $total = Num($data->sheets[0]['cells'][$i][20],2);
                $tcambio = Num($data->sheets[0]['cells'][$i][21],3);
                //           13                14             15                16             17                18         19        20
                $sql .= $baseImponible01."|".$igv01."|".$baseImpornible02."|".$igv02."|".$baseImpornible03."|".$igv03."|".$valor."|".$isc."|0.00|".$total."|".$tcambio."|";


                $f = '01/01/0001';
                $tipo = '00';
                $serie = '-';
                $numero = '-';

                //      24      25         26         27     
                $sql .= $f."|".$tipo."|".$serie."|".$numero."|";

                $nnn = '-';
                $f = '01/01/0001';
                $tipo = '0';
                $serie = '0';

                //      28       29       30        31     
                $sql .= $nnn."|".$f."|".$tipo."|".$serie."|1|".PHP_EOL;
            }
        }
    }

$ruc = "10011638126";
$str = "00000000001111";
$nombre_archivo = 'LE'.$ruc.$anio.$mes.$str.'.txt';

$contenido = $sql;
fopen($nombre_archivo, 'w');

if (is_writable($nombre_archivo)) {
   if (!$gestor = fopen($nombre_archivo, 'a')) {
         echo "No se puede abrir el archivo ($nombre_archivo)";
         exit;
   }

   if (fwrite($gestor, $contenido) === FALSE) {
       echo "No se puede escribir al archivo ($nombre_archivo)";
       exit;
   }

   fclose($gestor);
   dl_file($nombre_archivo);
   unlink($nombre_archivo);

} else {
   echo "No se puede escribir sobre el archivo $nombre_archivo";
}

}

?>

<h1>PLE</h1>
<form name="frm" id="frm" action="<?=$PHP_SELF?>" method="POST" enctype="multipart/form-data">
    <input type="text" id="anio" name="anio" value="<?php echo date('Y'); ?>" />
    <select name="mes" id="mes">
        <option value="1">Enero</option>
        <option value="2">Febrero</option>
        <option value="3">Marzo</option>
        <option value="4">Abril</option>
        <option value="5">Mayo</option>
        <option value="6">Junio</option>
        <option value="7">Julio</option>
        <option value="8">Agosto</option>
        <option value="9">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
    </select>
    <input type="file" name="archivo" id="archivo" value="" /><input type="submit" name="exportar" id="exportar" value="exportar" />
</form>