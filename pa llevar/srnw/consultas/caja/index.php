<?php 
if(!session_id()){ session_start(); }
include("../../libs/masterpage.php");	
include("../../config.php");	
$Consultaf	= $Conn->Query("Select now()");
$rowf	= $Conn->FetchArray($Consultaf);
$Fech	= $Conn->DecFecha(substr($rowf[0], 0, 10));	
CuerpoSuperior("Consulta de Comprobantes de Caja");
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/Funciones.js" type="text/javascript"></script>
<script>
  $(document).ready(function(){
    $("#Fecha,#Fechaf").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });
  })
	var OpcImpresion=1;
	function OpImpresion(Op)
  {
            OpcImpresion = Op;
            if(Op==1)
            {
                ocultarM("FilaComprobante");
                
                ocultarM("FilaPago");
            }
            if(Op==2)
            {
                ocultarM("FilaPago");
                
                MostrarM("FilaComprobante");
            }
            if(Op==3)
            {	
                
                ocultarM("FilaComprobante");
                MostrarM("FilaPago");
            }
	}	
	function ocultarM(Div){
            $("#" + Div).css("display", "none");
	}
	function MostrarM(Div){
            $("#" + Div).css("display", "");
	}	
	function Imprimir()
  {
            var Fecha = document.form1.Fecha.value,
                Fechaf = document.form1.Fechaf.value,
                Comprobante	= "",
                TipoPago 	= "";
            if(OpcImpresion==2)
            {
                var IdComprobante = document.form1.IdComprobante.value;
                if(IdComprobante == 0 )
                {
                    alert('Seleccione el Comprobante de Generaci&oacute;n')
                    return
                }
                Comprobante="&IdComprobante=" + IdComprobante;
            }
            if(OpcImpresion==3)
            {
                var Tipo=document.form1.TipoPago1.value;
                if(Tipo==3){
                    alert('Seleccione el Tipo de Pago');
                    return
                }
                TipoPago="&TipoPago=" + Tipo;
            }
            var Pagina = "impresion.php?Fecha=" + Fecha + "&Fechaf="+Fechaf+"&Opcion=" + OpcImpresion + Comprobante + TipoPago;
            var ventana=window.open(Pagina, 'Impresion', 'width=650, height=350, resizable=yes, scrollbars=yes'); ventana.focus();
	}	
</script>
<div align="center">
<form name="form1" method="post" action="">
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="7" align="center" class="Titulo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
            <thead>
                <tr>
                    <td style="font-size:18px" align="center" height="30">Consulta de Comprobantes de Caja</td>
                </tr>
            </thead>
        </table>            
    </td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td width="163" class="cabecera" align="right">Rango Fechas : Desde:&nbsp;</td>
    <td colspan="6">      
      <input type="text" name="Fecha" id="Fecha" class="inputtext" readonly="readonly" value="<?php echo $Fecha;?>" style="width:80px"/>
      &nbsp;&nbsp;&nbsp;Hasta:
      <input type="text" name="Fechaf" id="Fechaf" class="inputtext" readonly="readonly" value="<?php echo $Fecha;?>" style="width:80px"/>
    </td>      
      
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="198" align="center" colspan="2"><label style="cursor: pointer;font-size: 13px;"><input type="radio" name="radio" id="General" value="General" checked="checked" onclick="OpImpresion(1);">&nbsp;Consulta General</label></td>
    <td width="198" align="center" colspan="2"><label style="cursor: pointer;font-size: 13px;"><input type="radio" name="radio" id="Comprobante" value="Comprobante" onclick="OpImpresion(2);">&nbsp;Por Comprobante</label></td>
    <td width="198" class="cabecera" colspan="2"><label style="cursor: pointer;font-size: 13px;"><input type="radio" name="radio" id="TipoPago" value="TipoPago" onclick="OpImpresion(3);" />&nbsp;Por Tipo de Pago</label></td>
    
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr id="FilaComprobante">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="cabecera">Comprobante:</td>
    <td colspan="3">
    <label>
      <select name="IdComprobante" id="IdComprobante" class="select_1">
      	<option value="0" >--Seleccione el Comprobante--</option>
        <?php 
            $SQL        = "SELECT * FROM comprobante WHERE estado=1 ORDER BY descripcion ASC";
            $Consulta   = $Conn->Query($SQL);
            while ($row = $Conn->FetchArray($Consulta)){	
        ?>
            <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
        <?php }?>
      </select>
    </label>
    </td>
  </tr>
  <tr id="FilaPago">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" align="right" class="cabecera">Tipo de Pago:</td>
    <td><label>
      <select name="TipoPago1" id="TipoPago1" class="select_1">
      	<option value="" >--Seleccione el Tipo--</option>
         <?php 
            $SQL        = "SELECT idforma_pago,descripcion FROM forma_pago WHERE estado=1 ORDER BY descripcion ASC";
            $Consulta   = $Conn->Query($SQL);
            while ($row = $Conn->FetchArray($Consulta)){  
        ?>
          <option value="<?php echo $row[0] ?>"><?php echo utf8_decode($row[1]) ?></option>
        <?php } ?>
      </select>
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="FilaPLE" style="display:none">
    <td align="right">A&ntilde;o:</td>
    <td><input type="text" name="anio" id="anio" value="<?php echo date('Y'); ?>" class="select_1" size="4" /></td>
    <td colspan="2" align="right" class="cabecera">Mes:</td>
    <td>
      <label>
      
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="7" align="center">
        <table width="80px" border="0" cellspacing="0" cellpadding="0" class="Boton" onclick="Imprimir();" style="cursor: pointer;">
          <tr>
            <td width="30" height="20" align="center" valign="middle"><img src="../../imagenes/iconos/imprimir.png" width="16" height="16" /></td>
            <td width="98" align="center" valign="middle" style="font-size:12px;">Imprimir</td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="7">&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
    ocultarM("FilaComprobante");
    ocultarM("FilaPago");
</script>
<?php
    CuerpoInferior();
?>