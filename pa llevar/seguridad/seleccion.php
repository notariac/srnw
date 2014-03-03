<?php
if(!session_id()){ session_start(); }
    include("config.php");
    $_SESSION["IdPerfil"] = 0;
    include("clases/main.php");
    //CuerpoSuperior("Seleccione el Sistema a Acceder");
    $IdUsuario = $_SESSION["id_user"];
    //print_r($_SESSION);
?>
<script>
function AbrirSistema(IdSistema){
    location.href="clases/sesiones.php?IdSistema="+IdSistema;
}
function Sobre(Obj){
    Obj.style.background='#CCCCCC'
}
function Fuera(Obj){
    Obj.style.background='#ccc'
}
</script>
<style type="text/css"> 
    @import url(css/admin_login.css);
</style>

<body class="body">

  <section id="contenedor">

    <section id="principal">
      <h5><b style="color:#fecc00">Seleccione el sistema a acceder</b></h5>
      <center><a href="login.php"><b style="color:#000">Cerrar</b> <b style="color:#fecc00">Sesion</b></a></center>

      <article>
      
      <table width="550" border="0" cellspacing="0" align="center">


  <?php
    $SQL  = "SELECT sistemas.descripcion, sistemas.path, sistemas.referencia, sistemas.imagen, sistemas.idsistema FROM sistemas INNER JOIN usuario_sistemas ON (sistemas.idsistema = usuario_sistemas.idsistema) WHERE sistemas.estado = 1 AND usuario_sistemas.idusuario='$IdUsuario' ";
  $Consulta = $Conn->Query($SQL);
  while($row=$Conn->FetchArray($Consulta)){
  ?>

  <tr onClick="AbrirSistema('<?php echo $row[4];?>');">
    <td align="center"><img src="catalogos/sistemas/imagenes/<?php echo $row[3];?>" style="cursor:pointer;"></td>
    <td style="cursor:pointer;"  >

  <div style="font-weight:bold;font-size:18px;font-family:arial;color:#000;">
   <?php echo $row[0];?>
  </div>

  <div style="font-size:10px;font-family:arial;margin:0.55em;color:#fff">
   <?php echo $row[2];?><br><br><br>
  </div>
  
  </td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
      </article>

  <footer style="font-size:14px;">
   Software Desarrollado para  ARES DE TARAPOTO S.A.C., Quienes se Reservan todos los Derechos
  </footer>
</body>














<?php //CuerpoInferior(); ?>