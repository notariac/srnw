<?php 
if(!session_id()){ session_start(); }	
    include("../../libs/masterpage.php");	
    $Fecha = date('d/m/Y');	
    CuerpoSuperior("Listado de Escritura por Usuario");
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script>	
function Imprimir(){
    var Desde=document.form1.Desde.value;
    var Hasta=document.form1.Hasta.value;
    var Url='impresion.php?Desde=' + Desde + "&Hasta=" + Hasta;
    var ventana=window.open(Url,'Impresion','width=800, height=600, resizable=yes, scrollbars=yes');ventana.focus();
}
</script>
<div align="center">
<form name="form1" method="post" action="">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="5" align="center" class="Titulo">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
	<thead>
        <tr>
          <td style="font-size:18px" align="center" height="30">Generaci&oacute;n de Escritura por Usuario</td>
        </tr>
	</thead>
      </table>        
    </td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td width="228" class="cabecera" align="right" style="padding-right:5px">Desde:</td>
    <td colspan="2"><input type="text" name="Desde" id="Desde" class="inputtext" value="<?php echo $Fecha;?>" style="width:80px"/></td>
    <td width="68" align="right" class="cabecera" style="padding-right:5px">Hasta:</td>
    <td width="327"><input type="text" name="Hasta" id="Hasta" class="inputtext" value="<?php echo $Fecha;?>" style="width:80px"/></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>  
  <tr>
    <td colspan="5" align="center">
    <table width="80px" border="0" cellspacing="0" cellpadding="0" class="Boton" onclick="Imprimir();" style="cursor: pointer;">
      <tr>
        <td width="30" height="20" align="center" valign="middle"><img src="../../imagenes/iconos/imprimir.png" width="16" height="16" /></td>
        <td width="98" align="center" valign="middle" style="font-size:12px">Imprimir</td>
      </tr>
    </table>        
    </td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
</table>
</form>
</div>
<?php
    CuerpoInferior();
?>
<script>
$("#Desde").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true
});	
$("#Hasta").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true
});
</script>