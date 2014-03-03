<?php
if( !session_id() ){ session_start(); }
date_default_timezone_set("America/Lima"); 
	if (isset($_SESSION["id_user"])){
            $ahora = date("Y-n-j H:i:s");
            $tiempo_transcurrido = "";
            if (isset($_SESSION["ultimoAcceso"])){
                $antes = $_SESSION["ultimoAcceso"];
                $fechaGuardada = $antes;
                $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
            }else{
                $_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");
                $antes = $_SESSION["ultimoAcceso"];
            }
            if($tiempo_transcurrido >= 120000){
              session_destroy();
              header("Location: http://".$_SERVER['HTTP_HOST']."/seguridad/login.php");
            }else{
                $_SESSION["ultimoAcceso"] = $ahora;
            }
            $Activo = 1;
            $IdUsuario	= $_SESSION["id_user"];		
            $Admin 	= $_SESSION["Admin"];
	}else{
            session_destroy();
            if ("http://".$_SERVER['HTTP_HOST']."/seguridad/login.php"!="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']){
                header("Location: http://".$_SERVER['HTTP_HOST']."/seguridad/login.php");
            }
	}        
	$Fecha = date('d/m/Y');
	$UrlDir = "http://".$_SERVER['HTTP_HOST']."/srnw/";
	$_SESSION["urlDir"] = $UrlDir;
	$nPag = 10;
	//print_r($_SESSION);
	function CuerpoSuperior($TituloVentana){
            global $UrlDir, $Conn, $Anio, $Fecha, $TituloVentana, $IdUsuario, $IdNotaria, $Admin;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title><?php echo $TituloVentana;?></title>
<link href="<?php echo $UrlDir;?>css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery-1.8.21.min.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/JSCookMenu.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery.blockUI.js"></script>
</head>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $UrlDir;?>imagenes/empresa.ico" />
<body>
<script>
    var Tamanyo = [0, 0];
    var Tam = TamVentana();        
    function TamVentana()
    {
        Tamanyo = [$(window).width(), $(window).height()];
        return Tamanyo;
    }	
    function OperMensaje(Mensaje,Op){
        $.growlUI(Mensaje, Op);
    }


</script>
<div id="cab">
 <div id="logo"><img src="<?php echo $UrlDir;?>png/logo.png"></div>
 <div id="sesion">
  <div id="foto"></div>
  <div id="user">
     <a id="name-usr" ><?php echo utf8_decode($_SESSION["Usuario"]);?></a>
     <a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/seguridad/login.php?sesion=1">Cerrar Sesi&oacute;n </a>
     <a href='http://<?php echo $_SERVER['HTTP_HOST'];?>/seguridad/seleccion.php?sesion=1'>Cambiar de M&oacute;dulo</a>
     <a id="calendar"><?php echo $Fecha;?></a>
  </div>
 </div>
</div>

<table style="width:99%;margin:0 auto;" border="0" cellspacing="0" cellpadding="0">

      <tr>
       
        <td height="25" background="<?php echo $UrlDir;?>png/barrac.png"><?php include('menu.php');?></td>
        
      </tr>
   
    </table></td>

  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25" background="<?php echo $UrlDir;?>imagenes/ContEsqSupIzq.jpg">&nbsp;</td>
          <td background="<?php echo $UrlDir;?>imagenes/ContSup.jpg">&nbsp;</td>
          <td width="25" background="<?php echo $UrlDir;?>imagenes/ContEsqSupDer.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td background="<?php echo $UrlDir;?>imagenes/ContLadIzq.jpg">&nbsp;</td>
          <td bgcolor="#FFFFFF" id="TdContenido">
            <div id="DivContenido" style="overflow:auto;">
<?php 
	}    
	function CuerpoInferior(){
		global $UrlDir;
?>
            </div>
          </td>
          <td background="<?php echo $UrlDir;?>imagenes/ContLadDer.jpg">&nbsp;</td>
        </tr>
        <tr>
          <td background="<?php echo $UrlDir;?>imagenes/ContEsqInfIzq.jpg">&nbsp;</td>
          <td background="<?php echo $UrlDir;?>imagenes/ContInf.jpg">&nbsp;</td>
          <td background="<?php echo $UrlDir;?>imagenes/ContEsqInfDer.jpg">&nbsp;</td>
        </tr>
      </table>    
    </td>
  </tr>
  <tr>
    <td><table style="width:99%;margin:0 auto;" border="0" cellspacing="0" cellpadding="0">
      <tr style="font-size:10px">
        <td>&nbsp;</td>
        
      </tr>
      <tr height="40">
        
        <td valign="middle" align="center" background="<?php echo $UrlDir;?>png/green.png" class="foot">Sistema de Registro Notarial Web (v.1.1) - Derechos Reservado</td>
        
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
<?php
 	}
?>
<div id="domMessage" align="center" style="cursor:text;" ></div>
<div id="Mensajes"></div>
<script>
window.onload = function()
{
    document.getElementById('DivContenido').style.height = eval(Tam[1] - 215);
    document.getElementById('TdContenido').height = eval(Tam[1] - 215);
};
window.onresize = function()
{
    Tam = TamVentana();
    document.getElementById('DivContenido').style.height = eval(Tam[1] - 215);
    document.getElementById('TdContenido').height = eval(Tam[1] - 215);
};
</script>
