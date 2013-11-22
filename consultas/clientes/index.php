<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Consulta de Servicio por Participante";
    $Criterio = "atencion";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT kardex.idkardex, sql_fecha(kardex.fecha, dd/mm/yyyy), kardex.correlativo as Kardex, servicio.descripcion, cliente.dni_ruc, cliente.nombres, participacion.descripcion, 1 as Estado ";
    $Sql = $Sql."FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_participantes ON (kardex.idkardex = kardex_participantes.idkardex) ";
    $Sql = $Sql." INNER JOIN cliente ON (kardex_participantes.idparticipante = cliente.idcliente) INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex.correlativo', '2'=>'sql_fecha(kardex.fecha, dd/mm/yyyy)',								'3'=>'servicio.descripcion', '4'=>'cliente.dni_ruc', '5'=>'cliente.nombres', '6'=>'participacion.descripcion');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Fecha', 'T3'=>'Kardex', 'T4'=>'Servicio', 'T5'=>'DNI', 'T6'=>'Participante', 'T7'=>'Participaci&oacute;n', 'T8'=>'');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A5'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W5'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por P�ginas
    $FormatoGrilla[7] = 1000;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY kardex.fecha DESC, kardex.idkardex DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'0', 			//Botones de Acciones
                              'NB'=>'0');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<link href="<?php echo $urlDir;?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	var Foco = 'Cliente';
	var IdImp = 0;
	$(document).ready(function(){			
		$("#Nuevo").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Registro de Atenci�n",
                    width: 750,
                    height: 500,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Agregar": function() {
                            Op = 0;
                            $("#ConfirmaGuardar").dialog("open");
                        },
                        Cancelar: function() {
                            $("#DivNuevo").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		$("#Modificar").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Modificar Registro",
                    width: 750,
                    height: 500,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Actualizar": function() {
                            Op = 1;
                            $("#ConfirmaGuardar").dialog("open");
                        },
                        Cancelar: function() {
                            $("#DivModificar").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		$("#Eliminar").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Eliminar Registro",
                    width: 750,
                    height: 500,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Eliminar": function() {
                            Op = 2;
                            $("#ConfirmaGuardar").dialog("open");
                        },
                        Cancelar: function() {
                            $("#DivEliminar").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		$("#Restaurar").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Restaurar Registro",
                    width: 750,
                    height: 500,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Restaurar": function() {
                            Op = 3;
                          $("#DivRestaurar").html('');
                            $(this).dialog("close");  $("#ConfirmaRestauracion").dialog("open");
                        },
                        Cancelar: function() {
                            $("#DivRestaurar").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		$("#Ver").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Ver Registro",
                    width: 750,
                    height: 500,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        Salir: function() {
                            $("#DivVer").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		
		$("#GenComprobante").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Facturaci&oacute;n de Tickets",
                    width: 750,
                    height: 600,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Generar": function() {
                            Op = 0;
                            if (Validar()){
                                    $("#ConfirmaGuardarC").dialog("open");
                            }
                        },
                        Cancelar: function() {
                            $("#DivGenComprobante").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
		$("#ConfirmaGuardarC").dialog({
                    autoOpen: false,
                    modal:true,
                    resizable:false,
                    title: "Confirmaci&oacute;n de Operaci&oacute;n",
                    height:155,
                    width: 350,
                    buttons: {
                        "Aceptar": function() {
                            GuardarC(Op);
                            $(this).dialog("close");
                        },
                        Cancelar: function() {
                            $(this).dialog("close");
                        }
                    }
		});
	 });	
	function GenerarComprobante(Op){
            $("#GenComprobante").dialog("open");
            $("#DivGenComprobante").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
            $.ajax({
                url:'../../caja/Mantenimiento.php',
                type:'POST',
                async:true,
                data:'Op=0&IdAtencion=' + $("#IdAtencionT").val(),
                success:function(data){
                   $("#DivGenComprobante").html(data);
                   $("#Id").focus();
                }
            })
	}	
	function Validar(){
            var RaZo = $('#Nombres').val();
            var Dir = $('#Direccion').val();
            $('#Nombres').val(RaZo.toUpperCase());
            $('#Direccion').val(Dir.toUpperCase());
            if ($('#IdCliente').val()==''){
                arlert('Ingrese los datos del cliente');
                $("#DniRuc").focus();
                return false;
            }
            if ($('#DniRuc').val()==''){
                alert('Ingrese Nº de Documento del cliente');
                $("#DniRuc").focus();
                return false;
            }
            if ($('#Nombres').val()==''){
                alert('Ingrese Nombre del cliente');
                $("#Nombres").focus();
                return false;
            }
            if ($('#Direccion').val()==''){
                alert('Ingrese la Direcci&oacute;n del cliente');
                $("#Direccion").focus();
                return false;
            }
            if ($('#ConServicios').val()==0){
                alert('El Comprobante no posee detalle');
                return false;
            }
            return true;
	}
	function GuardarC(Op){
            $.ajax({
                url:'../../caja/guardar.php?Op=' + Op,
                type:'POST',
                async:true,
                data:$('#form1').serialize() + '&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio=<?php echo $Anio;?>',
                success:function(data){
                    $("#Mensajes").html(data);
                    IdImp = $('#IdFacturacionD').val();
                    $("#ConfirmaImprimir").dialog("open");				
                    $("#DivNuevo").html('');
                    $("#DivModificar").html('');
                    $("#DivEliminar").html('');
                    $("#DivRestaurar").html('');
                    $("#Nuevo").dialog("close");
                    $("#Modificar").dialog("close");
                    $("#Eliminar").dialog("close");
                    $("#Restaurar").dialog("close");
                    $("#ConfirmaRestauracion").dialog("close");
                    Buscar(Op);
                }
            });
	}
	function Imprime(Id){
            IdImp = Id;
            Imprimir();
	}
	function Imprimir(){
            var ventana=window.open('../../caja/impresion.php?Id=' + IdImp,'Generacion', 'width=850, height=500, resizable=yes, scrollbars=yes');ventana.focus();		
	}
	function Guardar(Op){
            $.ajax({
                url:'guardar.php?Op=' + Op,
                type:'POST',
                async:true,
                data:$('#form1').serialize() + '&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>',
                success:function(data){
                    $("#DivNuevo").html('');
                    $("#DivModificar").html('');
                    $("#DivEliminar").html('');
                    $("#DivRestaurar").html('');
                    $("#Nuevo").dialog("close");
                    $("#Modificar").dialog("close");
                    $("#Eliminar").dialog("close");
                    $("#Restaurar").dialog("close");
                    $("#ConfirmaRestauracion").dialog("close");
                    Buscar(Op);
                    $("#NumAtencion").dialog("open");
                    $("#DivNumAtencion").html(data);
                }
            });
	}
	var Id='';
	var Id2='';
	var IdAnt='';
	var Pagina = <?php echo $pagina;?>;
	var nPag = <?php echo $TAMANO_PAGINA;?>;
	function Buscar(Op){
            $.ajax({
                url:'grilla.php',
                type:'POST',
                async:true,
                data:'Pagina=' + Pagina + '&Formato=<?php echo serialize($FormatoGrilla);?>' + '&Valor=' + $('#Valor').val(),
                success:function(data){
                    $("#DivDetalle").html(data);
                    $('#TotalReg').html($('#NumReg').val());
                    var optInit = getOptionsFromForm();
                    $("#Pagination").pagination($('#NumReg').val(), optInit);
                }
            })
            $('#Valor').focus();
	}
	function ValidarEnter(evt, Op){
            Buscar(Op);
 	}
Buscar(<?php echo $Op;?>);
$('#Valor').focus();
$('#BtnNuevo').css("display", "none");
</script>
<?
    Pie();
    CuerpoInferior();
?>
<div id="NumAtencion" title="N&uacute;mero Generado" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivNumAtencion" style="width:100%">&nbsp;</div></td>
        </tr>
    </table>
</div>
<div id="GenComprobante" title="Generaci&oacute;n de Comprobante" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGenComprobante" style="width:100%">&nbsp;</div></td>
        </tr>
    </table>
</div>
<div id="ConfirmaGuardarC" title="Confirmaci&oacute;n de Eliminaci&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardar" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td>&iquest;Desea Confirmar la Operaci&oacute;n?</td>
        </tr>
    </table>
</div>