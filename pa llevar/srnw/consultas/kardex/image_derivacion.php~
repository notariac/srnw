<?php 
if(!session_id()){ session_start(); }
    include("../../config.php");
    $NroKardex = (isset($_GET["NroKardex"]))?$_GET["NroKardex"]:'';
    $sql="SELECT kardex_derivacion.imagen 
          FROM kardex_derivacion 
          INNER JOIN kardex ON (kardex_derivacion.idkardex = kardex.idkardex) 
          WHERE kardex.correlativo='$NroKardex' AND kardex.idnotaria=1";
    $q=pg_query($sql);
    $r=pg_fetch_array($q);
?>
<script>
    function CerrarVentana(){
        window.close();
    }
    function Volver(){
        window.history.back();
    }
</script>
<style type="text/css">
body{
    background:#000;
}
.TitDetalle{
    font-family:"Courier New", Courier, monospace;
    font-size:12px		
}
.Titulo {
    font-family:"Courier New", Courier, monospace;
    color:#333333;
    font-size:16px;
    font-weight:bold;
    text-align:center;
}
.CajaTexto {
    padding-left:5;
    padding-right:5;
    background:#fff;
    border:#000 solid 1px; 
    color:#000;
    font-family:"Courier New", Courier, monospace;
    background-color:#D9E5F2;
    font-size:12px;
}
-->
</style>
<body topmargin="0">
<table width="723" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" >
  <tr>
    <td colspan="2" align="left" class="TitDetalle" style="color:#009900">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="TitDetalle" style="color:#009900">N&ordm; de Kardex:<?php echo $NroKardex;?></td>
    <td align="right">
        <input type="button" name="Volver" id="Volver" value="Volver" style="cursor:pointer; fnt-family:'comic Snas MS'; font-size:10px" onClick="Volver()">
        <input type="button" name="Cerrar" id="Cerrar" value="Cerrar Ventana" style="cursor:pointer; font-family:'comic Sans MS'; font-size:10px" onClick="CerrarVentana();" /></td>
  </tr>
  <tr>
    <td colspan="3"><hr /></td>
  </tr>
  <tr>
    <td colspan="3"><img src='../../servicios/envios/imagenes/<?php echo $r[0];?>' width='900'/></td>
  </tr>
</table>
</body>
