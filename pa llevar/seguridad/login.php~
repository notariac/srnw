<?php
$Sesion = isset($_GET["sesion"])?1:0;
if($Sesion==1){
    if(!session_id()){ session_start(); }
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ingrese su Usuario y Contraseña</title>
<style type="text/css"> 
    @import url(css/admin_login.css);
</style>
</head>
<body class="body">
<div id="wrapper">
    <div id="header">
         <!--  <div id="mambo"><img src="images/green.png" alt="Mambo Logo" /></div> -->
    </div>
</div>


<section id="contenedor">

        <section id="principal">
            
            <img src="images/logob.png">
            <article>
            <p>
                Somos una empresa con mas de nueve años de experiencia al servicio de la sociedad
                brindandole seguridad jur&iacute;ca. Contamos con tecnolog&iacute;a de punta y personal
                selecto para realizar en el m&aacute;s breve tiempo cualquier tr&aacute;mite que nos sol&iacute;cite.
                Actualmente contamos con una infraestructura moderna y adecuada acorde con sus
                necesidades. Nuestra fortaleza es la excelencia y r&aacute;pidez en nuestro servicio.
            </p>
            </article>
            
        </section>

        <aside>
        <h5><b style="color:#000">Iniciar</b> <b style="color:#fecc00">Sesion</b></h5> 

         <form action="clases/validarusuario.php" method="post" name="loginForm" id="loginForm">
            <div class="form-block">
                
                <label>Usuario</label>
                <input name="txtusuario" id="txtusuario" type="text" size="15" style="width:150px" />
                
                <br><br>
                
                <label style="margin-left:-14px;">Contraseña</label>
                <input name="txtpass" id="txtpass" type="password"  size="15" style="width:150px" />
                
                <br><br>
                <input type="hidden" name="option" value="login" />

                <div align="center"><input type="submit" name="btnaceptar" id="btnaceptar"  value="Aceptar" /></div>
            </div>
            
            <label style="font-size:12px;text-shadow:5px 5px 10px #000">Si usted no tiene Usuario contacte con el administrador</label>
           
        </form>

        </aside>
    </section>






<!--
<div id="ctr" align="center">
    
	<div class="login">
		
        <div class="login-form">
			
            <img src="images/login.gif" alt="Conexión" />

        <form action="clases/validarusuario.php" method="post" name="loginForm" id="loginForm">
			<div class="form-block">
	        	<div class="inputlabel">Usuario</div>
		    	<div><input name="txtusuario" id="txtusuario" type="text" class="inputbox" size="15" style="font-family:Arial, Helvetica, sans-serif; font-size:11px" /></div>
	        	<div class="inputlabel">Contraseña</div>
		    	<div><input name="txtpass" id="txtpass" type="password" class="inputbox" size="15" style="font-family:Arial, Helvetica, sans-serif; font-size:11px"/></div>
		    	<input type="hidden" name="option" value="login" />
	        	<div align="left"><input type="submit" name="btnaceptar" id="btnaceptar" class="button" value="Aceptar" /></div>
        	</div>
        </form>
        
    	</div>
        
        <div class="login-text">
        <div class="ctr"><img src="images/security.png" width="64" height="64" alt="security" /></div>
        <p>¡Bienvenido!</p><p>Use un nombre de usuario y contraseña válidos para acceder al Sistema Notarial.</p>
    	</div>
        <div class="clr" style="color:#F00; font-weight:bold; font-size:12px"><?php echo isset($Mensaje)?$Mensaje:'';?></div>
	</div>
    -->
</div>


 <footer>
  Software Desarrollado por  ARES DE TARAPOTO S.A.C., Quienes se Reservan todos los Derechos
 </footer>


</body>
</html>
<script>document.loginForm.txtusuario.focus();</script>
