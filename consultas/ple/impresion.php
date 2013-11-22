<?php     
    require("../../config.php");

    $sql = "SELECT  extract(year from f.fechareg)||''||substring(cast(f.facturacion_fecha as text),6,2)||'00' as c01, 
                      '' as c02,
                      substring(cast(f.fechareg as text),9,2)||'/'||substring(cast(f.fechareg as text),6,2)||'/'||substring(cast(f.fechareg as text),1,4) as c03, 
                      substring(cast(f.facturacion_fecha as text),9,2)||'/'||substring(cast(f.facturacion_fecha as text),6,2)||'/'||substring(cast(f.facturacion_fecha as text),1,4) as c04,  
                      case f.idcomprobante
                        when 1 then '03'
                        when 2 then '01'
                      else '00' end as c05,
                      f.comprobante_serie as c06,
                      lpad(f.comprobante_numero,7,'0') as c07,
                      '0' as c08, 
                      case f.iddocumento when 1 then '1' 
                             when 2 then '4'
                             when 8 then '6'
                             when 5 then '7'
                             when 7 then 'A'
                        else '0' end as c09,
                      case length(trim(f.dni_ruc)) when 0 then 
                        case f.iddocumento when 8 then '00000000000'
                            else '00000000'
                          end
                           else  case f.iddocumento when 8 then lpad(f.dni_ruc,11,'0')
                          else lpad(f.dni_ruc,8,'0') 
                           end
                          end as c10,   
                      substring(f.nombres,1,59) as c11,                      
                      0.00 as c12,
                      (f.total - f.igv) as c13,
                      f.total as c14,
                      0.00 as c15,
                      '0.00' as c16,
                      '0.00' as c17,
                      '0.00' as c18,
                      '0.00' as c19,
                      0.00 as c20,
                      f.total as c21,
                      cast(f.tipo_cambio as numeric(18,3)) as c22,
                      --substring(cast(f.facturacion_fecha as text),9,2)||'/'||substring(cast(f.facturacion_fecha as text),6,2)||'/'||substring(cast(f.facturacion_fecha as text),1,4) as c23,  
                      '01/01/0001' as c23,
                      --case f.idcomprobante
                      --  when 1 then '03'
                      --  when 2 then '01'
                      --else '00' end as c24,
                      '00' as c24,
                      --f.comprobante_serie as c25,
                      '-' as c25,
                      --lpad(f.comprobante_numero,7,'0') as c26,
                      '-' as c26,
                      1 as c27
                    from facturacion as f
                where   extract(year from f.fechareg)=".$_GET['anio']." 
                    and extract(month from f.fechareg)=".$_GET['mes']."
                order by idfacturacion";    
    $q = $Conn->Query($sql);
    $nf = $Conn->NroColumnas($q);
    $file = "";
    $cont = 1;
    while($r = $Conn->FetchArray($q))
    {
        for($i=0;$i<($nf-1);$i++)
        {
           if($i==1)
           {
             $file .= $cont."|";
           }
           else 
           {
             $file .= $r[$i]."|";
           }                      
        }
        $cont ++;
        $file .= $r[$nf-1]."|".PHP_EOL;
    }     
    $ruc = "10011638126";
    $anio = $_GET['anio'];
    $mes = str_pad($_GET['mes'], 2,'0',0);
    $str = "00140100001111";
    $nombre_archivo = 'files/LE'.$ruc.$anio.$mes.$str.'.txt';
    $contenido = $file;
    fopen($nombre_archivo,'w+');    
    if (is_writable($nombre_archivo)) 
    {
       if (!$gestor = fopen($nombre_archivo, 'a')) 
       {
             echo "No se puede abrir el archivo ($nombre_archivo)";
             exit; 
       }
       if (fwrite($gestor, $contenido) === FALSE) 
       { 
           echo "No se puede escribir al archivo ($nombre_archivo)"; 
           exit; 
       }
       fclose($gestor); 
       chmod($nombre_archivo, 0777);

       $zip = new ZipArchive();

       $filename = 'files/PLE'.$_GET['anio'].'_'.$_GET['mes'].'.zip';
       if($zip->open($filename,ZIPARCHIVE::CREATE)===true) 
       {
              $zip->addFile($nombre_archivo);              
              $zip->close();              
       }
      else 
       {
              echo 'Error creando '.$filename;
       }
       chmod($filename, 0777);
       unlink($nombre_archivo);
       echo $filename;       

    } 
    else 
    { 
        echo "No se puede escribir sobre el archivo $nombre_archivo"; 
    } 
?> 