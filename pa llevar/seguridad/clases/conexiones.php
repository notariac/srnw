<?php
class cConexion{
	var $Motor;		//Motor de la Base de Datos
	var $Servidor;	//Dirección IP del Servidor 
	var $Puerto;	//Puerto con el que se comunica al Servidor
	var $Usuario;	//Usuario de la Base de Datos
	var $Password;	//Contraseña del Usuario
	var $BaseDatos;	//Nombre de la Base de Datos
	var $Conexion;	//Conexion activa
	//
	//Propiedades Públicas
	//
	function SetMotor($motor){ $this->Motor = $motor;}
	function GetMotor(){ return $this->Motor;}
	
	function SetServidor($servidor){ $this->Servidor = $servidor;}
	function GetServidor(){ return $this->Servidor;}
	
	function SetPuerto($puerto){ $this->Puerto = $puerto;}
	function GetPuerto(){ return $this->Puerto;}
	
	function SetUsuario($usuario){ $this->Usuario = $usuario;}
	function GetUsuario(){ return $this->Usuario;}
	
	function SetPassword($password){ $this->Password = $password;}
	function GetPassword(){ return $this->Password;}
	
	function SetBaseDatos($base){ $this->BaseDatos = $base;}
	function GetBaseDatos(){ return $this->BaseDatos;}
	
	function GetConexion(){ return $this->Conexion;}
	//
	//Procedimientos Públicos
	//
	function Conectar()
	{
		switch($this->Motor)
		{
			case 'mysql':
				$this->Conexion = mysql_connect($this->Servidor, $this->Usuario, $this->Password);
				mysql_select_db($this->BaseDatos, $this->Conexion);
				break;
			case 'postgres':
				$this->Conexion = pg_connect("host=".$this->Servidor." port=".$this->Puerto." password=".$this->Password." user=".$this->Usuario." dbname=".$this->BaseDatos);
				break;
			case 'mssql':

				break;
		}
	}
	function Desconectar()
	{
		switch($this->Motor)
		{
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
	function NuevaTransaccion()
	{
		switch($this->Motor)
		{
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
	function TerminarTransaccion($ROLLBACK_COMMIT)
	{
		switch($this->Motor)
		{
			case 'mysql':
				mysql_query($ROLLBACK_COMMIT);
				break;
			case 'postgres':
				pg_query($ROLLBACK_COMMIT);
				break;
			case 'mssql':

		}
	}
	
	function Query($Sql)
	{
		switch($this->Motor)
		{
			case 'mysql':
				return mysql_query($Sql);
			case 'postgres':
				return pg_query($Sql);
			case 'mssql':

		}
	}
	
	function FetchArray($Query)
	{
		switch($this->Motor)
		{
			case 'mysql':
				return mysql_fetch_array($Query);
			case 'postgres':
				return pg_fetch_array($Query);
			case 'mssql':
			
		}
	}
	
	function NroRegistros($Query)
	{
		switch($this->Motor)
		{
			case 'mysql':
				return mysql_num_rows($Query);
			case 'postgres':
				return pg_num_rows($Query);
			case 'mssql':
			
		}
	}

        function NroColumnas($Query)
	{
		switch($this->Motor)
		{
			case 'mysql':
				return mysql_num_fields($Query);
			case 'postgres':
				return pg_num_fields($Query);
			case 'mssql':

		}
	}

        //
        //Funciones de Interpretaci�n
        //
	function PreparaSQL($Campos, $Texto)
	{
		switch($this->Motor)
		{
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
		for ($i = 0; $i < strlen($tmp); $i++)
		{
			if (strpos($tmp, ' ')!=0)
			{
				$cont = $cont + 1;
				$Palabras[$cont] = trim(substr($tmp, 0, strpos($tmp, ' ')));
				$tmp = trim(substr($tmp, strpos($tmp, ' ')));
			}
		}
		$cont = $cont + 1;
		$Palabras[$cont] = trim(substr($tmp, 0));
		
		$Condicion = "OR";
		if ($cont!=1)
		{
			$Condicion = "AND";
		}

		$tmp2 = " WHERE (CAST(".$Campos[1]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[1]."%'";
		for ($i = 2; $i <= count($Campos); $i++)
		{
			$tmp2 = $tmp2." OR CAST(".$Campos[$i]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[1]."%' ";
		}
		$tmp2 = $tmp2.")";
		for ($x = 2; $x <= $cont; $x++)
		{
			$tmp2 = $tmp2." $Condicion (CAST(".$Campos[1]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[$x]."%'";
			for ($i = 2; $i <= count($Campos); $i++)
			{
				$tmp2 = $tmp2." OR CAST(".$Campos[$i]." AS ".$TEXT.") ".$LIKE." '%".$Palabras[$x]."%' ";
			}
			$tmp2 = $tmp2.")";
		}
		return $tmp2;
	}
    function DecFecha($Fec){
        $mifecha = explode("-", $Fec);
        $Fecha = $mifecha[2]."/".$mifecha[1]."/".$mifecha[0];
        return $Fecha;
    }
    function CodFecha($Fec){
	$mifecha = explode("/", $Fec);
    	$Fecha = $mifecha[2]."-".$mifecha[1]."-".$mifecha[0];
	return $Fecha;
    }	
}
?>