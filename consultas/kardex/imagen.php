<?php 
if(!session_id()){ session_start(); }
	include("../../config.php");	
	$IdDependencia 	= (isset($_GET["IdDependencia"]))?$_GET["IdDependencia"]:'';
	$NroKardex	= (isset($_GET["NroKardex"]))?$_GET["NroKardex"]:'';	
	$SQL 		= "SELECT imagen FROM kardex_derivacion WHERE iddependencia='$IdDependencia' AND kardex='$NroKardex'";
	$Consulta 	= pg_query($conectar,$SQL);
	$row		= pg_fetch_array($Consulta);	
	if($row[0]!=''){
            $RutaImagen= "../../generacion/derivacion/imagenes/".$row[0];
	}else{
            $RutaImagen="../../images/pregunta.jpg";
	}
?>
<style type="text/css">
.TitDetalle{
	font-family:"Courier New", Courier, monospace;
	font-size:12px		
}
.Titulo {
	font-family:"Courier New", Courier, monospace;
	color:#333333;
	font-size:16px;
	font-weight:bold;
	text-align:center
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
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr >
    <td align="left" class="TitDetalle" >
    <form id="frm" name="frm" onSubmit="javascript:Zoom(document.frm.zoom.value);return false;">
        <label>
        Ver al:
      <input name="zoom" type="text" id="zoom" size="5" class="CajaTexto" onChange="Zoom(this.value)" />
        </label>
        %
        <input type="button" name="Imprimir" id="Imprimir" value="Imprimir Esquela" onclick="javascript:window.print();" style="cursor:pointer; font:Arial; font-size:12px" />
        <input type="button" name="Cerrar" id="Cerrar" value="Cerrar" onclick="javascript:window.close();" style="cursor:pointer; font:Arial; font-size:12px" />   
    </form>    </td>
  </tr>
  <tr>
    <td align="right" class="TitDetalle" ><hr /></td>
  </tr>
  <tr>
    <td><img src="<?php echo $RutaImagen;?>" name="img1" width="900" height="700" alt="Esquela de Observaci&oacute;n"></td>
  </tr>
</table>
<script language="javascript"> 
var xx = document.img1.width;
var yy = document.img1.height; 
function Zoom(porcentaje){
        if (isNaN(porcentaje)){
                document.frm.zoom.focus();
                alert ("Por favor, ingrese un porcentaje v&aacute;lido.");
                return;
        }
        if (porcentaje > 100){
                document.frm.zoom.focus();
                alert ("El porcentaje de visualizaci&oacute;n no puede ser mayor al 100%");
                return;
        } 
        if (porcentaje < 1){
                document.frm.zoom.focus();
                alert ("El porcentaje de visualizaci&oacute;n no puede ser menor al 1%");
                return;
        }
        factor = porcentaje / 100;
        document.img1.width  = Math.round(xx*factor);
        document.img1.height = Math.round(yy*factor);
}		
</script> 