<?php  
if(!session_id()){ session_start(); }
if ( strlen ($_SESSION['id_user'])>0 ) {
    $urlDir = $_SESSION["urlDir"];
    $TAMANO_PAGINA = 10;
//capturas la pagina en la q estas
    if (isset($_GET['pagina']))
    {
      $pagina = $_GET["pagina"];
    }
    else
    {
      $pagina = '';
    }
//si estas en la primera pagina le asignas los valores iniciales
    if (!$pagina){
        $inicio = 0;
        $pagina = 1;
    }else{
        $inicio = ($pagina - 1) * $TAMANO_PAGINA;
    }
    function Cabecera($Previo='', $Tamano=700){
        global $urlDir, $Fecha, $Valor, $Op, $num_total_registros, $TituloVentana, $IdUsuario, $Criterio;
?>
<link href="<?php echo $urlDir;?>css/pagination.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urlDir;?>css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $urlDir;?>js/FuncionesGrilla.js"></script>
<script type="text/javascript" src="<?php echo $urlDir;?>js/Funciones.js"></script>
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery.pagination.js"></script>
<script>
var RutaM = 'Mantenimiento.php';
var RutaG = 'guardar.php';
var PrefM = 'form1';	
var timeoutHnd;
$(document).ready(function()
{
        $("#Valor").live('keyup',function()
        {            
            if(timeoutHnd)
            clearTimeout(timeoutHnd)
            timeoutHnd = setTimeout(val_enter,500);
        });
        $("#ConfirmaGuardar").dialog({
            autoOpen: false,
            modal:true,
            resizable:false,
            title: "Confirmaci&oacute;n de Operaci&oacute;n",
            height:155,
            width: 350,
            buttons: {
                "Aceptar": function() {
                    Guardar(Op);
                    $(this).dialog("close");
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }
        });
        $("#ConfirmaEliminacion").dialog({
            autoOpen: false,
            modal:true,
            resizable:false,
            title: "Confirmaci&oacute;n de Eliminaci&oacute;n",
            height:155,
            buttons: {
                "Eliminar": function() {
                    Guardar(2);
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }
        });
        $("#ConfirmaRestauracion").dialog({
            autoOpen: false,
            modal:true,
            resizable:false,
            title: "Confirmaci&oacute;n de Restauraci&oacute;n",
            height:155,
            buttons: {
                "Restaurar": function() {
                    Guardar(3);
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }
        });
        $("#ConfirmaImprimir").dialog({
            autoOpen: false,
            modal:true,
            resizable:false,
            title: "Confirmaci&oacute;n de Operaci&oacute;n",
            height:155,
            width: 350,
            buttons: {
                "Aceptar": function() {
                    Imprimir();
                    $("#ConfirmaImprimir").dialog("close");
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }
        });
 });	 
function val_enter()
{
    ValidarEnter($("#Valor").val(), '<?php echo $Op;?>');
}
function Mostrar(Id, Op){
    if (Op==0){
        $("#Nuevo").dialog("open");
        $("#DivNuevo").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }		
    if (Op==1){
        $("#Modificar").dialog("open");
        $("#DivModificar").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }
    if (Op==2){
        $("#Eliminar").dialog("open");
        $("#DivEliminar").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }
    if (Op==3){
        $("#Restaurar").dialog("open");
        $("#DivRestaurar").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }
    if (Op==4){
        $("#PDT").dialog("open");
        $("#DivPDT").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }
    if (Op==5){
        $("#Ver").dialog("open");
        $("#DivVer").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    }
    $.ajax({
        url:RutaM,
        type:'POST',
        async:true,
        data:'Op=' + Op + '&Id=' + Id,
        success:function(data){
           if (Op==0){$("#DivNuevo").html(data);}
           if (Op==1){$("#DivModificar").html(data);}
           if (Op==2){$("#DivEliminar").html(data);}
           if (Op==3){$("#DivRestaurar").html(data);}
           if (Op==4){$("#DivPDT").html(data);}
           if (Op==5){$("#DivVer").html(data);}
           $("#" + Foco).focus();
        }
    });
}
function Eliminar(Id){
    $("#DivEliminacion").html('<form id="' + PrefM + '" name="' + PrefM + '"><input type="hidden" name="1' + PrefM + '_id<?php echo $Criterio;?>" id="Id" value="' + Id + '" "/></form>');
    $("#ConfirmaEliminacion").dialog("open");
}
function Restablecer(Id){
    $("#DivRestaurar").html('<form id="' + PrefM + '" name="' + PrefM + '"><input type="hidden" name="1' + PrefM + '_id<?php echo $Criterio;?>" id="Id" value="' + Id + '" "/></form>');
    $("#ConfirmaRestauracion").dialog("open");
}	
function GuardarP(Op){
    $.ajax({
        url:RutaG +'?Op=' + Op,
        type:'POST',
        async:true,
        data:$('#' + PrefM).serialize() + '&0' + PrefM + '_idusuario=<?php echo $IdUsuario;?>&3' + PrefM + '_fechareg=<?php echo $Fecha;?>',
        success:function(data){
            $("#Mensajes").html(data);				
            $("#DivNuevo").html('');
            $("#DivModificar").html('');
            $("#DivEliminar").html('');
            $("#DivEliminacion").html('');
            $("#DivRestaurar").html('');
            $("#Nuevo").dialog("close");
            $("#Modificar").dialog("close");
            $("#Eliminar").dialog("close");
            $("#ConfirmaEliminacion").dialog("close");
            $("#ConfirmaRestauracion").dialog("close");
            Buscar(Op);
        }
    });
}
</script>
<div align="center">
  <table width="<?php echo $Tamano;?>" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td style="font-size:10px">&nbsp;</td>
    </tr>
    <tr>
      <td>        
        <h3 style="height:21px; padding:5px 0 0 0; text-transform:uppercase; font-size:11px; margin:0; text-align:center" class="ui-widget-header ui-corner-top"><?php echo $TituloVentana;?></h3>
         <div style="width:<?php echo $Tamano-21;?>px; padding:10px; margin:0 0 10px; border-top:0" class="ui-widget-content ui-corner-bottom">
            <div style="float:left"><?php echo $Previo;?></div>
            <table width="100%" border="0" >
                <tr style="font-size:15px">
                  <td valign="middle" align="left" width="65">Buscar :&nbsp;</td>
                  <td valign="middle" align="left"><input type="text" name="Valor" id="Valor" style="width:100%" value="<?php echo $Valor;?>"  class="inputtext"/></td>
                  <td width="70"><img src="<?php echo $urlDir;?>imagenes/iconos/restablecer.png" width="20" height="20" border="0" onclick="Buscar('<?php echo $Op;?>');" style="cursor:pointer;" title="Refrescar"/>&nbsp;<img src="<?php echo $urlDir;?>imagenes/iconos/nuevo.png" id="BtnNuevo" width="20" height="20" border="0" onclick="Mostrar('', 0);" style="cursor:pointer" title="Nuevo Registro"/></td>
                  <td align="right" width="150">Total Registros :&nbsp;<label id="TotalReg"><?php echo $num_total_registros;?></label></td>
                </tr>
            </table>
        </div>
      </td>
    </tr>
    <tr>
      <td align="center">
      	<table width="<?php echo $Tamano;?>" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" valign="top">
                <div id="DivDetalle"></div>
                <?php
                }
                    function Pie()
                    {
                        global $urlDir, $num_total_registros, $pagina, $total_paginas, $TAMANO_PAGINA, $Op;
                        if ($Op==''){
                            $Op=0;
                    }
                ?>            
            </td>
        </tr>
        <tr>
            <td style="font-size:10px">&nbsp;</td>
        </tr>
    </table>      
      </td>
    </tr>
  </table>
</div>
<script>
	//Paginación
	function pageselectCallback(page_index, jq){
            // Get number of elements per pagionation page from form
            var max_elem = Math.min((page_index+1) * <?php echo $TAMANO_PAGINA;?>, $('#NumReg').val());		
            // Prevent click event propagation
            return false;
	}		
	function getOptionsFromForm(){
            var opt = {callback: pageselectCallback};
            // Collect options from the text fields - the fields are named like their option counterparts
            opt['items_per_page'] = <?php echo $TAMANO_PAGINA;?>;
            opt['num_display_entries'] = 5;
            opt['num_edge_entries'] = 1;
            opt['prev_text'] = "<< ";
            opt['next_text'] = " >>";
            opt['current_page'] = Pagina;
            opt['op'] = <?php echo $Op;?>;
            // Avoid html injections in this demo
            return opt;
	}
</script>
<div id="Nuevo" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivNuevo" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="Modificar" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivModificar" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="Eliminar" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivEliminar" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="Restaurar" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivRestaurar" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="Ver" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivVer" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="PDT" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivPDT" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="Imprimir" title="Imprimir" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivImprimir" style="width:100%"></div></td>
        </tr>
        <tr>
            <td>&iquest;Desea Imprimir?</td>
        </tr>
    </table>
</div>
<div id="ConfirmaEliminacion" title="Confirmaci&oacute;n de Eliminaci&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivEliminacion" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-trash" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Eliminar este Registro?</td>
        </tr>
    </table>
</div>
<div id="ConfirmaRestauracion" title="Confirmaci&oacute;n de Restauraci&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivRestaurar2" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-unlocked" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Restaurar este Registro?</td>
        </tr>
    </table>
</div>
<div id="ConfirmaGuardar" title="Confirmaci&oacute;n de Operaci&oacute;n" style="display:none;z-index:999 !important">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardar" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td>&iquest;Desea Confirmar la Operaci&oacute;n?</td>
        </tr>
    </table>
</div>
<div id="ConfirmaImprimir" title="Confirmaci&oacute;n de Impresi&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivImprimir" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-print" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Imprimir el comprobante?</td>
        </tr>
    </table>
</div>
<?php
    }
}else{
    header("Location:http://".$_SERVER['HTTP_HOST']."/seguridad/login.php?sesion=1");
}
?>