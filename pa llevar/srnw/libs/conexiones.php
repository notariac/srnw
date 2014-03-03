<?php
class cConexion{
    public $Motor;	//Motor de la Base de Datos
    public $Servidor;	//Dirección IP del Servidor 
    public $Puerto;	//Puerto con el que se comunica al Servidor
    public $Usuario;	//Usuario de la Base de Datos
    public $Password;	//Contraseña del Usuario
    public $BaseDatos;	//Nombre de la Base de Datos
    public $Conexion;	//Conexion activa
    public $Error;	//Ultimo error
    public function SetMotor($motor){ $this->Motor = $motor;}
    public function GetMotor(){ return $this->Motor;}	
    public function SetServidor($servidor){ $this->Servidor = $servidor;}
    public function GetServidor(){ return $this->Servidor;}	
    public function SetPuerto($puerto){ $this->Puerto = $puerto;}
    public function GetPuerto(){ return $this->Puerto;}	
    public function SetUsuario($usuario){ $this->Usuario = $usuario;}
    public function GetUsuario(){ return $this->Usuario;}	
    public function SetPassword($password){ $this->Password = $password;}
    public function GetPassword(){ return $this->Password;}
    public function SetBaseDatos($base){ $this->BaseDatos = $base;}
    public function GetBaseDatos(){ return $this->BaseDatos;}
    public function GetConexion(){ return $this->Conexion;}
    public function GetError(){ return $this->Error;}
    public function Conectar(){
        switch($this->Motor){
            case 'mysql':
                $this->Conexion = mysql_connect($this->Servidor, $this->Usuario, $this->Password);
                mysql_select_db($this->BaseDatos, $this->Conexion);
                break;
            case 'postgres':
                $this->Conexion = pg_connect("host=".$this->Servidor." port=".$this->Puerto." password=".$this->Password." user=".$this->Usuario." dbname=".$this->BaseDatos) 
                    or die("No se puede Conectar a la Base datos :{$this->BaseDatos}<br/>Detalle de Error:".  pg_last_error());
                break;
            case 'mssql':

                break;
        }
    }
    public function Desconectar(){
        switch($this->Motor){
            case 'mysql':
                mysql_close($this->Conexion);
                break;
            case 'postgres':
                pg_close($this->Conexion);
                break;
            case 'mssql':			
                break;
        }
    }
    public function NuevaTransaccion(){
        switch($this->Motor){
            case 'mysql':
                mysql_query("BEGIN");
                break;
            case 'postgres':
                pg_query($this->Conexion, "BEGIN");
                break;
            case 'mssql':			
                break;
        }
    }
    public function TerminarTransaccion($ROLLBACK_COMMIT){
        switch($this->Motor){
            case 'mysql':
                mysql_query($ROLLBACK_COMMIT);
                break;
            case 'postgres':
                pg_query($ROLLBACK_COMMIT);
                break;
            case 'mssql':			
                break;
        }
    }	
    public  function Execute($sql){        
        if($ejec=$this->Query($sql)){
            $return=array();
            while($row=$this->FetchArray($ejec)){
                $return[]=$row;
            }
            return $return;
        }else{return null;}
    }
    public function Query($Sql){
        $this->Error = '';
        switch($this->Motor){
            case 'mysql':
                $Consulta = mysql_query($Sql, $this->Conexion);
                break;
            case 'postgres':
                $Consulta = pg_query($this->Conexion, $Sql);
                if (!$Consulta){
                    $this->Error = pg_last_error($this->Conexion)."<br/>En la Consulta:".$Sql;
                }
                break;
            case 'mssql':			
                break;
        }
        return $Consulta;
    }
    public function FetchArray($Query){
        switch($this->Motor){
            case 'mysql':
                return mysql_fetch_array($Query);
            case 'postgres':
                return pg_fetch_array($Query);
            case 'mssql':			
        }
    }	
    public  function NroRegistros($Query){
        switch($this->Motor){
            case 'mysql':
                return mysql_num_rows($Query);
            case 'postgres':
                return pg_num_rows($Query);
            case 'mssql':			
        }
    }
    public function NroColumnas($Query){
        switch($this->Motor){
            case 'mysql':
                return mysql_num_fields($Query);
            case 'postgres':
                return pg_num_fields($Query);
            case 'mssql':
        }
    }
    public function VerificarSQL($Sql){
        $posicion = strrpos($Sql, "sql_fecha(");
        if ($posicion!=0){
            $Sql = str_replace("sql_fecha(", "to_char(", $Sql);
            $Sql = str_replace("dd/mm/yyyy", "'dd/mm/yyyy'", $Sql);
        }
        return $Sql;
    }	
    public function PreparaSQL($Campos, $Texto){
        switch($this->Motor){
            case 'mysql':
                $TEXT = 'CHAR'; $LIKE = 'LIKE';
                break;
            case 'postgres':
                $TEXT = 'TEXT'; $LIKE = 'ILIKE';
                break;
            case 'mssql':			
        }
        $tmp = trim($Texto);
        $cont = 0;
        for ($i = 0; $i < strlen($tmp); $i++){
            if (strpos($tmp, ' ')!=0){
                $cont = $cont + 1;
                $Palabras[$cont] = trim(substr($tmp, 0, strpos($tmp, ' ')));
                $tmp = trim(substr($tmp, strpos($tmp, ' ')));
            }
        }
        $cont = $cont + 1;
        $Palabras[$cont] = trim(substr($tmp, 0));
        $Condicion = "OR";
        if ($cont!=1){
            $Condicion = "AND";
        }
        $tmp2 = " WHERE (CAST(".$Campos[1]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[1]."%'";
        for ($i = 2; $i <= count($Campos); $i++){
            $tmp2 = $tmp2." OR CAST(".$Campos[$i]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[1]."%' ";
        }
        $tmp2 = $tmp2.")";
        for ($x = 2; $x <= $cont; $x++){
            $tmp2 = $tmp2." $Condicion (CAST(".$Campos[1]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[$x]."%'";
            for ($i = 2; $i <= count($Campos); $i++){
                $tmp2 = $tmp2." OR CAST(".$Campos[$i]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[$x]."%' ";
            }
            $tmp2 = $tmp2.")";
        }
        return $tmp2;
    }	
    public function DecFecha($Fec){
        $mifecha = explode("-", $Fec);
        $Fecha = $mifecha[2]."/".$mifecha[1]."/".$mifecha[0];
        return $Fecha;
    }
    public function CodFecha($Fec){
	$mifecha = explode("/", $Fec);
    	$Fecha = $mifecha[2]."-".$mifecha[1]."-".$mifecha[0];
	return $Fecha;
    }	
    public function isDNI($dni)
    {
        $flag = false;
        $patron = "/^[0-9]{8,8}$/";
         if(preg_match($patron, $dni))
         {
            $flag = true;
         }
         return $flag;
    }
    public function isRUC($ruc)
    {
        $flag = false;
        $patron = "/^[0-9]{11,11}$/";
         if(preg_match($patron, $ruc))
         {
            $flag = true;
         }
         return $flag;
    }
    public function isNum($num)
    {
        $num = (float)$num;
        return $num;
    }
    public function isText($text)
    {
        $item = array(" DELETE "," FROM "," SELECT "," DROP "," SET "," UPDATE ");
        $text = strtoupper($text);
        foreach($item as $i)
        {
            $text = str_replace($i, "", $text);
        }
        return $text;
    }
    public function isEmail ($email)
    {
        $r = false;
        if($email!="")
        {
           if( preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
            {
                $r = true;
            }   
        }
        else 
        {
            $r = true;
        }
       return $r;
    }
    public function vParam($param,$type = "TEXT")
    {
        $error = false;
        $msg = "";
        switch($type)
        {
            case "TEXT":
                        $param = rtrim(ltrim($this->isText($param)));                        
                        break;
            case "NUM": 
                        $param = $this->isNum($param);
                        break;
            case "EMAIL": 
                        if(!$this->isEmail($param))
                        {
                            $error = true;
                            $msg = "FORMATO DE EMAIL INCORRECTO";
                        }
                        break;
            default: break;
        }
        return array($param,$error,$msg);
    }
    public function generaActoJuridico($Indice){
        $actos_juridicos = array('01',
                                 '02',
                                 '03',
                                 '04',
                                 '06',
                                 '07',
                                 '08',
                                 '09',
                                 '10',
                                 '11',
                                 '12',
                                 '13',
                                 '14',
                                 '15',
                                 '16',
                                 '17',
                                 '18',
                                 '19',
                                 '20',
                                 '21',
                                 '22',
                                 '23');
        for($i=0; $i< count($actos_juridicos);$i++){
            if($actos_juridicos[$i]=='01'){
                
            }
            if($actos_juridicos[$i]=='02'){
                
            }
            if($actos_juridicos[$i]=='03'){
                
            }
            if($actos_juridicos[$i]=='04'){
                
            }
            if($actos_juridicos[$i]=='06'){
                
            }
            if($actos_juridicos[$i]=='07'){
                
            }
            if($actos_juridicos[$i]=='08'){
                if($Indice=='22'){
                    
                }
                if($Indice=='289'){
                    
                }
            }
            if($actos_juridicos[$i]=='09'){
                if($Indice=='311'){
                    
                }
                if($Indice=='314'){
                    
                }
            }
            if($actos_juridicos[$i]=='12'){
                if($Indice=='102'){
                    
                }
                if($Indice=='315'){
                    
                }
            }
        }
        return "Importable en proceso...";
    }
}
?>