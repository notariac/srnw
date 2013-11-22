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
		var Desde 	= $('#Desde').val();
		var Hasta 	= $('#Hasta').val();
		var Tipo 	= $('#Tipo').val();
		var Titulo 	= 0;	
		if (document.getElementById('Titulo').checked){
                    Titulo = 1;
		}
		var Url;		
		if (Tipo==1){
                    Url = 'rptEA.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==2){
                    Url = 'rptEC.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==3){
                    Url = 'rptVA.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==4){
                    Url = 'rptVC.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==5){
                    Url = 'rptNA.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==6){
                    Url = 'rptNC.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==7){
                    Url = 'rptAV.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==8){
                    Url = 'rptCN.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==9){
                    Url = 'rptPFR.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==10){
                    Url = 'rptCD.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		if (Tipo==11){
                    Url = 'rptAP.php?Desde=' + Desde + "&Hasta=" + Hasta + '&Titulo=' + Titulo;
		}
		var ventana=window.open(Url,'Impresion','width=800, height=600, resizable=yes, scrollbars=yes, top=50');ventana.focus();
		if (Tipo==11){
                    var ventana2 = window.open(Url + '&Hoja=2','Impresion2','width=800, height=600, resizable=yes, scrollbars=yes, top=50'); ventana.focus();
		}
	}
</script>
<div align="center">
<form name="form1" method="post" action="">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4" align="center" class="Titulo">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
	  	<thead>
        <tr>
          <td style="font-size:18px" align="center" height="30">Listado de Indices</td>
        </tr>
		</thead>
      </table></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td class="cabecera" align="right" style="padding-right:5px">Tipo : </td>
    <td colspan="3">
		<select name="Tipo" id="Tipo">
		  <option value="1">Indice de Escrituras P&uacute;blicas (Alfab&eacute;tico)</option>
		  <option value="2">Indice de Escrituras P&uacute;blicas (Cronol&oacute;gico)</option>
		  <option value="3">Indice de Actas Vehiculares (Alfab&eacute;tico)</option>
		  <option value="4">Indice de Actas Vehiculares (Cronol&oacute;gico)</option>
		  <option value="5">Indice de No Contenciosos (Alfab&eacute;tico)</option>
		  <option value="6">Indice de No Contenciosos (Cronol&oacute;gico)</option>
          <option value="7">Indice de Autorizaci&oacute;n de Viajes (Cronol&oacute;gico)</option>
          <option value="8">Indice de Cartas Notariales (Cronol&oacute;gico)</option>
          <option value="9">Indice de Poderes Fuera de Registro (Cronol&oacute;gico)</option>
		  <option value="10">Indice de Certificados Domiciliarios (Cronol&oacute;gico)</option>
		  <option value="11">Indice de Protestos de Letras y Pagares (Cronol&oacute;gico)</option>
        </select>    </td>
    </tr>
  <tr>
    <td class="cabecera" align="right" style="padding-right:5px">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right" class="cabecera" style="padding-right:5px">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="228" class="cabecera" align="right" style="padding-right:5px">Desde :</td>
    <td><input type="text" name="Desde" id="Desde" class="inputtext" value="<?php echo $Fecha;?>" style="width:80px"/></td>
    <td width="68" align="right" class="cabecera" style="padding-right:5px">Hasta :</td>
    <td width="327"><input type="text" name="Hasta" id="Hasta" class="inputtext" value="<?php echo $Fecha;?>" style="width:80px"/></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>  
  <tr>
    <td colspan="4" align="center"><label style="font-size: 13px;cursor: pointer;"><input type="checkbox" name="Titulo" id="Titulo" value="1" />Con T&iacute;tulo</label></td>
  </tr>  
  <tr>
    <td colspan="4" align="center">&nbsp;</td>
  </tr>  
  <tr>
    <td colspan="4" align="center">
        <table width="80px" border="0" cellspacing="0" cellpadding="0" class="Boton" onclick="Imprimir();" title="Clic para generar documento PDF">
            <tr>
                <td width="30" height="20" align="center" valign="middle"><img src="../../imagenes/iconos/imprimir.png" width="16" height="16" /></td>
                <td width="98" align="center" valign="middle" style="font-size:12px">Imprimir</td>
            </tr>
        </table>   
    </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
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