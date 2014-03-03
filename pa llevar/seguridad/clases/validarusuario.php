<?php 
if(!session_id()){ session_start(); }	
    include("../config.php");	
    $_SESSION["db_Servidor"] 	= $Conn->GetServidor();
    $_SESSION["db_Puerto"] 	= $Conn->GetPuerto();
    $_SESSION["db_Usuario"] 	= $Conn->GetUsuario();
    $_SESSION["db_PassWord"] 	= $Conn->GetPassword();	
    $Envio                      = $_POST["btnaceptar"];
    $Usuario                    = strtoupper($_POST["txtusuario"]);
    $Contra                     = $_POST["txtpass"];
?>
<style type="text/css">
    <!--
    .Estilo1 {
        color: #003399;
        font-weight: bold;
        font-style: italic;
        vertical-align: middle;
    }
    -->
</style>
    <span class="Estilo1"><div align="center"><img src="../images/avance.gif" width="16" height="16" />Procesando Solicitud </div></span>
    <p>
<?php
$SQLUsuario = "SELECT * FROM usuario WHERE TRIM(login) = '$Usuario' AND TRIM(contra) = '$Contra' AND estado=1";
$Consulta   = $Conn->Query($SQLUsuario);		
if ($row = $Conn->FetchArray($Consulta)){		
    $SQLUsuario1 = "SELECT * FROM usuario_sistemas WHERE idusuario = '".$row[1]."'";
    $Consulta1   = $Conn->Query($SQLUsuario1);
    $row1 = $Conn->FetchArray($Consulta1);
    $_SESSION["super_usuario"] 	= $row1[3];
    $_SESSION["id_user"] 	= $row[1];
    $_SESSION["Usuario"] 	= $row[5];
    $_SESSION["Ruta"]           = $Ruta;
    $_SESSION["Activo"]         = 0;
    $_SESSION["IdPerfil"]	= $row[2];			
    $_SESSION["notaria"]	= $row[12];			
?>
<script>
    document.location = '../seleccion.php'; 
</script>
<?php	}else{      ?>
<script>
    alert('Usted no esta Autorizado para Acceder al Sistema... Consulte con el Administrador');
    document.location ='../login.php';
</script>
<?php	}           ?>