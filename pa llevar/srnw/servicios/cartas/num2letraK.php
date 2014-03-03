<?php
function unidad($numuero){
    switch ($numuero){
        case 9:{
            $numu = "NUEVE";
            break;
        }
        case 8:{
            $numu = "OCHO";
            break;
        }
        case 7:{
            $numu = "SIETE";
            break;
        }		
        case 6:{
            $numu = "SEIS";
            break;
        }		
        case 5:{
            $numu = "CINCO";
            break;
        }		
        case 4:{
            $numu = "CUATRO";
            break;
        }		
        case 3:{
            $numu = "TRES";
            break;
        }		
        case 2:{
            $numu = "DOS";
            break;
        }		
        case 1:{
            $numu = "UN";
            break;
        }		
        case 0:{
            $numu = "";
            break;
        }		
    }
    return $numu;	
}
function decena($numdero){	
    if ($numdero >= 90 && $numdero <= 99){
            $numd = "NOVENTA ";
            if ($numdero > 90) $numd = $numd."Y ".(unidad($numdero - 90));
    }else if ($numdero >= 80 && $numdero <= 89){
            $numd = "OCHENTA ";
            if ($numdero > 80) $numd = $numd."Y ".(unidad($numdero - 80));
    }else if ($numdero >= 70 && $numdero <= 79){
            $numd = "SETENTA ";
            if ($numdero > 70) $numd = $numd."Y ".(unidad($numdero - 70));
    }else if ($numdero >= 60 && $numdero <= 69){
            $numd = "SESENTA ";
            if ($numdero > 60) $numd = $numd."Y ".(unidad($numdero - 60));
    }else if ($numdero >= 50 && $numdero <= 59){
            $numd = "CINCUENTA ";
            if ($numdero > 50) $numd = $numd."Y ".(unidad($numdero - 50));
    }else if ($numdero >= 40 && $numdero <= 49){
            $numd = "CUARENTA ";
            if ($numdero > 40) $numd = $numd."Y ".(unidad($numdero - 40));
    }else if ($numdero >= 30 && $numdero <= 39){
            $numd = "TREINTA ";
            if ($numdero > 30) $numd = $numd."Y ".(unidad($numdero - 30));
    }else if ($numdero >= 20 && $numdero <= 29){
            if ($numdero == 20) $numd = "VEINTE ";
            else $numd = "VEINTI".(unidad($numdero - 20));
    }else if ($numdero >= 10 && $numdero <= 19){
        switch ($numdero){
            case 10:{
                $numd = "DIEZ ";
                break;
            }
            case 11:{		 		
                $numd = "ONCE ";
                break;
            }
            case 12:{
                $numd = "DOCE ";
                break;
            }
            case 13:{
                $numd = "TRECE ";
                break;
            }
            case 14:{
                $numd = "CATORCE ";
                break;
            }
            case 15:{
                $numd = "QUINCE ";
                break;
            }
            case 16:{
                $numd = "DIECISEIS ";
                break;
            }
            case 17:{
                $numd = "DIECISIETE ";
                break;
            }
            case 18:{
                $numd = "DIECIOCHO ";
                break;
            }
            case 19:{
                $numd = "DIECINUEVE ";
                break;
            }
        }	
    }
    else $numd = unidad($numdero);
return $numd;
}
function centena($numc){
        if ($numc >= 100){
                if ($numc >= 900 && $numc <= 999){
                        $numce = "NOVECIENTOS ";
                        if ($numc > 900) $numce = $numce.(decena($numc - 900));
                }else if ($numc >= 800 && $numc <= 899){
                        $numce = "OCHOCIENTOS ";
                        if ($numc > 800) $numce = $numce.(decena($numc - 800));
                }else if ($numc >= 700 && $numc <= 799){
                        $numce = "SETECIENTOS ";
                        if ($numc > 700) $numce = $numce.(decena($numc - 700));
                }else if ($numc >= 600 && $numc <= 699){
                        $numce = "SEISCIENTOS ";
                        if ($numc > 600) $numce = $numce.(decena($numc - 600));
                }else if ($numc >= 500 && $numc <= 599){
                        $numce = "QUINIENTOS ";
                        if ($numc > 500) $numce = $numce.(decena($numc - 500));
                }else if ($numc >= 400 && $numc <= 499){
                        $numce = "CUATROCIENTOS ";
                        if ($numc > 400) $numce = $numce.(decena($numc - 400));
                }else if ($numc >= 300 && $numc <= 399){
                        $numce = "TRESCIENTOS ";
                        if ($numc > 300) $numce = $numce.(decena($numc - 300));
                }else if ($numc >= 200 && $numc <= 299){
                        $numce = "DOSCIENTOS ";
                        if ($numc > 200) $numce = $numce.(decena($numc - 200));
                }else if ($numc >= 100 && $numc <= 199){
                        if ($numc == 100) $numce = "CIEN ";
                        else $numce = "CIENTO ".(decena($numc - 100));
                }
        }
        else $numce = decena($numc);
        return $numce;	
}
function miles($nummero){
    if ($nummero >= 1000 && $nummero < 2000){
            $numm = "MIL ".(centena($nummero%1000));
    }
    if ($nummero >= 2000 && $nummero <10000){
            $numm = unidad(Floor($nummero/1000))." MIL ".(centena($nummero%1000));
    }
    if ($nummero < 1000) $numm = centena($nummero);
    return $numm;
}
function decmiles($numdmero){
    if ($numdmero == 10000){ $numde = "DIEZ MIL"; }
    if ($numdmero > 10000 && $numdmero <20000){ $numde = decena(Floor($numdmero/1000))."MIL ".(centena($numdmero%1000)); }
    if ($numdmero >= 20000 && $numdmero <100000){ $numde = decena(Floor($numdmero/1000))." MIL ".(miles($numdmero%1000)); }		
    if ($numdmero < 10000) $numde = miles($numdmero);
    return $numde;
}		

function cienmiles($numcmero){
    if ($numcmero == 100000) $num_letracm = "CIEN MIL";
    if ($numcmero >= 100000 && $numcmero <1000000){ $num_letracm = centena(Floor($numcmero/1000))." MIL ".(centena($numcmero%1000)); }
    if ($numcmero < 100000) $num_letracm = decmiles($numcmero);
    return $num_letracm;
}	
	
function millon($nummiero){
    if ($nummiero >= 1000000 && $nummiero <2000000){ $num_letramm = "UN MILLON ".(cienmiles($nummiero%1000000)); }
    if ($nummiero >= 2000000 && $nummiero <10000000){ $num_letramm = unidad(Floor($nummiero/1000000))." MILLONES ".(cienmiles($nummiero%1000000)); }
    if ($nummiero < 1000000) $num_letramm = cienmiles($nummiero);
    return $num_letramm;
}	
function decmillon($numerodm){
    if ($numerodm == 10000000) $num_letradmm = "DIEZ MILLONES";
    if ($numerodm > 10000000 && $numerodm <20000000){ $num_letradmm = decena(Floor($numerodm/1000000))."MILLONES ".(cienmiles($numerodm%1000000)); }
    if ($numerodm >= 20000000 && $numerodm <100000000){ $num_letradmm = decena(Floor($numerodm/1000000))." MILLONES ".(millon($numerodm%1000000)); }
    if ($numerodm < 10000000) $num_letradmm = millon($numerodm);
    return $num_letradmm;
}
function cienmillon($numcmeros){
    if ($numcmeros == 100000000) $num_letracms = "CIEN MILLONES"; if ($numcmeros >= 100000000 && $numcmeros <1000000000){ $num_letracms = centena(Floor($numcmeros/1000000))." MILLONES ".(millon($numcmeros%1000000)); }
    if ($numcmeros < 100000000) $num_letracms = decmillon($numcmeros);
    if($numcmeros >= 1000000000) $num_letracms="MIL MILLONES es mucho dinero";
    return $num_letracms;
}			
		
function num2letra($numero){
    $numf = cienmillon($numero);
    return $numf;
}

function CantidadEnLetra($tyCantidad){  
    $tyCantidad = round($tyCantidad * 100) / 100; 
    $lyCantidad = (int)$tyCantidad; 
    $lyCentavos = ($tyCantidad - $lyCantidad) * 100; 
    $lyCentavos = round($lyCentavos * 100) / 100; 
    $lyCentavos = substr("00",0,2-strlen($lyCentavos)).$lyCentavos;
    $laUnidades = Array("UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUN", "VEINTIDOS", "VEINTITRES", "VEINTICUATRO", "VEINTICINCO", "VEINTISEIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE"); 
    $laDecenas = Array("DIEZ", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"); 
    $laCentenas = Array("CIENTO", "DOSCIENTOS", "TRESCIENTOS", "CUATROCIENTOS", "QUINIENTOS", "SEISCIENTOS", "SETECIENTOS", "OCHOCIENTOS", "NOVECIENTOS"); 
    $lnNumeroBloques = 0; 
do{ 
    $lnNumeroBloques++; 
    $lnPrimerDigito = 0; 
    $lnSegundoDigito = 0; 
    $lnTercerDigito = 0; 
    $lcBloque = ""; 
    $lnBloqueCero = 0; 
    for($i = 1; $i <= 3; $i++){ 
        $lnDigito = substr($lyCantidad, strlen($lyCantidad)-1,1);
        if($lnDigito != 0){ 
            switch($i){ 
                case 1: 
                    $lcBloque = " " . $laUnidades[$lnDigito - 1]; 
                    $lnPrimerDigito = $lnDigito; 
                    break; 
                case 2: 
                    if ($lnDigito <= 2){ 
                        $lcBloque = " " . $laUnidades[($lnDigito * 10) + $lnPrimerDigito - 1]; 
                    }else{ 
                        if($lnPrimerDigito != 0){ 
                            $y =" Y"; 
                        }else{ 
                            $y=" "; 
                        }                         
                        $lcBloque = " " . $laDecenas[$lnDigito - 1] . $y . $lcBloque; 
                    } 
                    $lnSegundoDigito = $lnDigito; 
                    break; 
                case 3: 
                    if($lnDigito == 1 and $lnPrimerDigito == 0 and $lnSegundoDigito == 0){ 
                        $cien = "CIEN"; 
                    }else{ 
                        $cien = $laCentenas[$lnDigito - 1]; 
                    } 
                    $lcBloque = " " . $cien . $lcBloque; 
                    $lnTercerDigito = $lnDigito; 
                    break; 
            } 
        }else{ 
            $lnBloqueCero = $lnBloqueCero + 1; 
        } 
        $lyCantidad = $lyCantidad / 10; 
        $lyCantidad = (int)$lyCantidad; 
        if ($lyCantidad == 0){ 
            break; 
        } 
    } 
    switch($lnNumeroBloques){ 
        case 1: 
            $CantidadEnLetra = $lcBloque; 
            $CORTALETRA = substr($CantidadEnLetra, -2);  
            if ($CORTALETRA == "UN"){ 
                $CantidadEnLetra = $lcBloque . "O"; 
            } 
            break; 
        case 2: 
            if ($lcBloque == " UN"){ 
                if($lnBloqueCero != 3){ 
                    $mil= " MIL"; 
                } 
                $CantidadEnLetra = $mil . $CantidadEnLetra; 
                $CORTALETRA = substr($CantidadEnLetra, -2); 
                if ($CORTALETRA == "UN"){ 
                    $CantidadEnLetra = $lcBloque; 
                } 
            }else{ 
                if($lnBloqueCero != 3){ 
                    $mil=" MIL"; 
                } 
                $CantidadEnLetra = $lcBloque . $mil . $CantidadEnLetra; 
                $CORTALETRA = substr($CantidadEnLetra, -2); 
                if (CORTALETRA == "UN"){ 
                    $CantidadEnLetra = $lcBloque . "O"; 
                } 
            } 
            break; 
        case 3: 
            if($lnPrimerDigito == 1 And $lnSegundoDigito == 0 And $lnTercerDigito == 0){ 
                $millon= " MILLON"; 
            }else{ 
                $millon= " MILLONES"; 
            } 
            $CantidadEnLetra = $lcBloque . $millon . $CantidadEnLetra; 
            $CORTALETRA = substr($CantidadEnLetra, -2); 
            if ($CORTALETRA == "UN"){ 
                $CantidadEnLetra = $lcBloque . "O"; 
            } 
            break; 
        } 
}while($lyCantidad > 0); 
    $con=" CON "; 
    $CantidadEnLetra = $CantidadEnLetra; 
    return $CantidadEnLetra; 
} 
?>