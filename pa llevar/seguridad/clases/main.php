<?php
if(!session_id()){ session_start(); }
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
    if($tiempo_transcurrido >= 50000){
        session_destroy();
        header("Location: http://".$_SERVER['HTTP_HOST']."/seguridad/login.php");
    }else{
        $_SESSION["ultimoAcceso"] = $ahora;
    }
    $Activo = 1;
    $IdUsuario  = $_SESSION["id_user"];
}else{
    session_destroy();
    if ("http://".$_SERVER['HTTP_HOST']."/seguridad/login.php"!="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']){
        header("Location: http://".$_SERVER['HTTP_HOST']."/seguridad/login.php");
    }
}
$UrlDir = "http://".$_SERVER['HTTP_HOST']."/seguridad/";
$_SESSION["urlDir"] = $UrlDir;
$nPag = 10;
function CuerpoSuperior($TituloVentana){
    global $UrlDir, $Conn;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $TituloVentana;?></title>
<link href="<?php echo $UrlDir;?>css/template_css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/theme.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/admin_login.css" rel="stylesheet" type="text/css">
<link href="<?php echo $UrlDir;?>css/botones.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/admin_login.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $UrlDir;?>css/jquery-ui-1.8.11.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="<?php echo $UrlDir;?>js/JSCookMenu.js" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo $UrlDir;?>js/ThemeOffice/theme.js" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo $UrlDir;?>js/mambojavascript.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/validaciones.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo $UrlDir;?>js/script.js"></script>
<style>
.TolbarTexto{
	font-family:Arial;
	font-size:12px;
	text-align:center;
	font-style:normal;
	font-weight:bold;
	color:#999999;
}
</style>
<style type="text/css">
<!--
div.growlUI {
	background: url(<?php echo $UrlDir;?>images/ok.png) no-repeat 15px 15px;
	padding-top:0px;
	margin-top:0px;
	background-color:#069;
	border-style:outset;
	border-width:3px;
}
div.growlUI h1, div.growlUI h2 { color: white; padding: 0px 0px 0px 75px; text-align: left }
div.growlUI h1 { font-size: 20px;  font:bold }
div.growlUI h2 { font-size: 16px; }
div.growlUI2 {
	background: url(<?php echo $UrlDir;?>images/error.png) no-repeat 15px 15px;
	padding-top:0px;
	margin-top:0px;
	background-color:#F00;
	border-style:outset;
	border-width:3px
}
div.growlUI2 h1, div.growlUI h2 { color: white; padding: 0px 0px 0px 75px; text-align: left }
div.growlUI2 h1 { font-size: 20px;  font:bold }
div.growlUI2 h2 { font-size: 16px; }
-->
</style>
</head>
<div id="frmcubrir" style="display:none"></div>
<div style="position:absolute; top:20%; left:30%; display:none" id="frmmensaje"></div>
<body>
<script>
    var Tamanyo = [0, 0];
    var Tam = TamVentana();       
    function TamVentana(){
        Tamanyo = [$(window).width(), $(window).height()];
        return Tamanyo;
    }
    function OperMensaje(Mensaje,Op){
        $.growlUI(Mensaje,Op);
    }
</script>

<div id="wrapper">
    <table border="0" cellspacing="0" cellpadding="0" width="100%" >
        <tr id="header">
            <td id="mambo" align="right" height="100">
                <label id="LblTitulo" style="color:#FFF; font-size:18px; font-weight:bold; position:relative; right:20"></label>
            </td>
        </tr>
    </table>
</div>
<div>
<?php include("menu.php"); ?>
</div>
<table width="100%" height="73%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
<tr><td style="height:20px"></td></tr>
  <tr valign="top" align="center" bgcolor="#FFFFFF">
    <td height="350px" valign="top">
    <div style="width:100%; overflow:auto;" id="DivPrincipal">
    <?php 
        }
        function CuerpoInferior(){
        global $UrlDir;
    ?>
    </div>
    <div style="height:10px"></div>
    </td>
  </tr>
  <tr>
      <td class="PieMain"></td>
  </tr>
</table>
<div id="wrapper">
    <div id="header"><div id="mambo2">Derechos Reservador<br>ARES DE TARAPOTO.SAC </div>
    </div>
</div>
</body>
</html>
<div id="domMessage" align="center" style="cursor: text;" ></div>
<?php
        }
?>
<script>
window.onload = function(){
    document.getElementById('DivPrincipal').style.height = eval(Tam[1] - 145);
    $('#LblTitulo').html(window.document.title);
};
window.onresize = function(){
    Tam = TamVentana();
    document.getElementById('DivPrincipal').style.height = eval(Tam[1] - 145);
};
</script>