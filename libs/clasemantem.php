<?php
class dbMantimiento
{
    private $conn;
    var $mivar = array();
    var $sql = "";
    var $cri = array();
    function __construct($connection_or_string){
        if (is_string($connection_or_string))
      $this->conn = pg_connect($connection_or_string);
        else 
      $this->conn = $connection_or_string;
    }
    function __destruct() {}
    function CodFecha($Fec)
    {
        $mifecha = explode("/", $Fec);
        	$Fecha = $mifecha[2]."-".$mifecha[1]."-".$mifecha[0];
        return $Fecha;
    }
    function _ObtenerArgumentos($fname)
    {
        $Consulta = pg_query($this->conn, "SELECT proargnames FROM pg_proc WHERE proname ='$fname'");
        $row = pg_fetch_array($Consulta);
        $Argg = explode(",", substr(substr($row[0], 1), 0, strlen(substr($row[0], 1))-1));
        return $Argg;
    }	
    function _PrepararArgumentos($fname, $post, $prefix)
    {
            $Argumentos = array();		
            $Argg = $this->_ObtenerArgumentos($fname);
            for ($i = 0; $i < count($Argg); $i++){
                foreach($post as $ind=>$val){
                    if(strpos($ind, $prefix.'_')!==false){
                        $cri = substr($ind, 0, 1);
                        $name = substr($ind, strpos($ind, "_") + 1);					
                        if ('p'.$name==trim($Argg[$i])){
                            if ($cri==3 || $cri==4){
                                $Argumentos[$i] = "".$this->CodFecha($val)."";
                            }else{
                                if ($cri=='s'){
                                    $Argumentos[$i] = "".$val."";	
                                }else{
                                    $Argumentos[$i] = $val;
                                }
                            }
                        }
                    }
                }
            }
            return $Argumentos;
    }	
    function __dbMantenimiento($post, $prefix, $tabla, $op) {
        foreach($post as $ind=>$val){
            if(stripos($ind, $prefix.'_')!==false)				{
                $cri = substr($ind, 0, 1);
                $name = substr($ind, strpos($ind, "_") + 1);
                    switch($cri){
                        case 0:		//Campo normal
                            $this->mivar[$name] = $val;
                            break;
                        case 1:		//Campo Criterio Serial
                            $this->cri[$name] = $val;
                            break;
                        case 2:		//Campo Criterio de Ingreso manual
                            $this->cri[$name] = $val;
                            if($op==0)
                            $this->mivar[$name] = $val;
                            break;
                        case 3:		//Campo Tipo Fecha
                            $this->mivar[$name] = $this->CodFecha($val);
                            break;
                        case 4:		//Campo Criterio Tipo Fecha Ingreso Manual
                            $this->cri[$name] = $this->CodFecha($val);
                            if($op==0)
                            $this->mivar[$name] = $this->CodFecha($val);
                            break;
                    }
            }
        }
        return $this->mantenimiento($tabla, $op);
    }	
    function mantenimiento($tabla, $op){    
        switch($op){
            case 0:
                foreach($this->mivar as $name=>$value){
                    $n[]=$name;
                    $v[]="'".$value."'";
                }
                $this->sql="INSERT INTO $tabla (".implode(", ", $n).") VALUES (".implode(", " ,$v).")";
                break;
            case 1:
                foreach($this->mivar as $name=>$value){
                    $nv[]=$name."='".$value."'";
                }
                foreach($this->cri as $name=>$value){
                    $w[]=$name."='".$value."'";
                }
                $this->sql="UPDATE $tabla SET ".implode(",",$nv)." WHERE ". implode(" AND ", $w);
                break;
            case 2:
                foreach($this->cri as $name=>$value){
                    $w[]=$name."='".$value."'";
                }
                $this->sql="UPDATE $tabla SET estado=0 WHERE ".implode(" AND ", $w);
                break;				
		case 3: // Restablecer Registro
                foreach($this->cri as $name=>$value){
                    $w[]=$name."='".$value."'";
                }
                $this->sql="UPDATE $tabla SET estado=1 WHERE ".implode(" AND ", $w);
                break;
        }
        return $this->sql;
    }
    function reinicio($n1, $n2){
        $A = pg_query($this->conn,"SELECT * FROM reinicio WHERE idnotaria='$n1'");
        $R = array();
        $i=0;
        while($F = pg_fetch_row($A)){
            $R[$i]  =   $F[1];
            $i++;
        }
        $R[$i]=date("Y");
        for($i=0;$i<count($R)-1;$i++){
            if( $n2 >= $R[$i] && $n2 < $R[$i+1] ){
               return $R[$i];
            }
        }
    }
    function generaCode($nro,$pref,$cant){
        $n = $nro+1;
        $nuevo = $n;
        for($i=0;$i<$cant-strlen($n);$i++){
            $nuevo = "0".$nuevo;
        }
        return $pref.$nuevo;
    }
}
?>