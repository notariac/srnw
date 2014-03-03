<?php
    function opt_combo($sql,$idseleccion,$Conexion){
            $html_option="";
            $ConsultaLT = $Conexion->Query($sql);
            while($row=$Conexion->FetchArray($ConsultaLT)){
                $Select = '';
                 if ($row[0]==$idseleccion){
                    $Select = 'selected="selected"';
                 }
                 $html_option.="<option value='{$row[0]}' {$Select}>{$row[1]}</option>";
                 
           }
           return $html_option;
    }    
    function opt_combo2($sql,$idseleccion,$Conexion){
            $html_option="";
            $ConsultaLT = $Conexion->Query($sql);
            while($row=$Conexion->FetchArray($ConsultaLT)){
                $Select = '';
                 if ($row[0]==$idseleccion){
                    $Select = 'selected="selected"';
                 }
                 $html_option.="<option value='{$row[0]}' {$Select}>".zerofill($row[0], 3)."-{$row[1]}</option>";
                 
           }
           return $html_option;
    } 
    function zerofill($entero, $largo){
    // Limpiamos por si se encontraran errores de tipo en las variables
    $entero = (int)$entero;
    $largo = (int)$largo;     
    $relleno = '';     
    /**
     * Determinamos la cantidad de caracteres utilizados por $entero
     * Si este valor es mayor o igual que $largo, devolvemos el $entero
     * De lo contrario, rellenamos con ceros a la izquierda del número
     **/
    if (strlen($entero) < $largo) {
        $relleno=str_repeat('0', $largo-strlen($entero));
    }
    return $relleno . $entero;
    }
    function reformatFecha($fecha){
            if(strlen($fecha)>1){
                $fecha=explode("-", $fecha);
                return $fecha[2]."/".$fecha[1]."/".$fecha[0];
            }else{
                return "";
            }
            
    }
    function nombres_algoritm($nombres){
        
    }
    
?>
