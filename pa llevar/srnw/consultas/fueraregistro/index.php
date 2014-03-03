<?php
    if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Poderes Fuera de Registro";
    $Criterio = "kardex";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT kardex.idkardex, kardex.idatencion, kardex.correlativo, sql_fecha(kardex.fecha, dd/mm/yyyy), cliente.nombres, kardex_estado.descripcion, kardex.idusuario, kardex.estado FROM kardex INNER JOIN kardex_estado ON (kardex.estado = kardex_estado.idkardex_estado) INNER JOIN atencion ON (kardex.idatencion = atencion.idatencion) INNER JOIN cliente ON (cliente.idcliente = atencion.idcliente)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex.idatencion', '2'=>'sql_fecha(kardex.fecha, dd/mm/yyyy)', '3'=>'kardex.correlativo', '4'=>'cliente.nombres', '5'=>'kardex_estado.descripcion', '6'=>'kardex.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Atenci&oacute;n', 'T3'=>'Nº Kardex', 'T4'=>'Fecha', 'T5'=>'Cliente', 'T6'=>'Estado', 'T7'=>'Usuario');   			//Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por P�ginas
    $FormatoGrilla[7] = 900;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " AND kardex.idservicio=100 ORDER BY kardex.correlativo DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'0', 						//Botones de Acciones
                              'NB'=>'0');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<link href="<?php echo $urlDir;?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $urlDir?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	var Foco = 'Cliente';
	$(document).ready(function(){			
		$("#Modificar").dialog({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Modificar Registro",
			width: 750,
			height: 550,
			show: "scale",
			hide: "scale",
			buttons: {
                            "Actualizar": function() {
                                Op = 1;
                                if (Validar()){
                                    $("#ConfirmaGuardar").dialog("open");
                                }
                            },
                            Cancelar: function() {
                                $("#DivModificar").html('');
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
			height: 550,
			show: "scale",
			hide: "scale",
			buttons: {
                            Salir: function() {
                                $("#DivVer").html('');
                                $(this).dialog("close");
                            }
			}	   
		});
		$("#Imprimir").dialog({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Imprimir Libro",
			width: 300,
			height: 200,
			show: "scale",
			hide: "scale",
			buttons: {
                            "Imprimir": function(){
                                Imprimir2($("#RutaArchivo").val());
                            },
                            Salir: function() {
                                $("#DivImprimir").html('');
                                $(this).dialog("close");
                            }
			}	   
		});
	 });
	function Validar(){
		if ($('#Id').val().substring(0, 1)=='K' || $('#Id').val().substring(0, 1)=='N' || $('#Id').val().substring(0, 1)=='V'){
			if ($('#NroEscritura').val()==''){
				alert('Ingrese el Nº de Escritura!');
				$('#NroEscritura').focus();
				return false;
			}
			if ($('#FojaInicio').val()==''){
				alert('Ingrese la Foja de Inicio!');
				$('#FojaInicio').focus();
				return false;
			}
			if ($('#FojaFin').val()==''){
				alert('Ingrese la Foja Final!');
				$('#FojaFin').focus();
				return false;
			}
			if ($('#SerieInicio').val()==''){
				alert('Ingrese la Serie de Inicio!');
				$('#SerieInicio').focus();
				return false;
			}
			if ($('#SerieFin').val()==''){
				alert('Ingrese la Serie Final!');
				$('#SerieFin').focus();
				return false;
			}
		}
		if ($('#Id').val().substring(0, 1)=='K' || $('#Id').val().substring(0, 1)=='N'){
                    if ($('#NroMinuta').val()==''){
                        alert('Ingrese el Nº e Minuta!');
                        $('#NroMinuta').focus();
                        return false;
                    }
		}
		if ($('#Id').val().substring(0, 1)=='V'){
                    if ($('#Placa').val()==''){
                        alert('Ingrese la Placa!');
                        $('#Placa').focus();
                        return false;
                    }
		}
		return true;
	}	
	function Guardar(Op){
		if (Validar()){
			$.ajax({
				url:'guardar.php?Op=' + Op,
				type:'POST',
				async:true,
				data:$('#form1').serialize() + '&0form1_estado=1&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio=<?php echo $Anio;?>',
				success:function(data){
                                    $("#Mensajes").html(data);					
                                    $("#DivNuevo").html('');
                                    $("#DivModificar").html('');
                                    $("#DivEliminacion").html('');
                                    $("#DivRestaurar").html('');
                                    $("#Nuevo").dialog("close");
                                    $("#Modificar").dialog("close");
                                    $("#ConfirmaEliminacion").dialog("close");
                                    $("#ConfirmaRestauracion").dialog("close");
                                    Buscar(Op);
				}
			});
		}
	}
	function ImprimirP(Id){
		$("#Imprimir").dialog("open");
		$("#DivImprimir").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");		
		$.ajax({
			 url:'generaword.php',
			 type:'POST',
			 async:true,
			 data:'IdKardex=' + Id,
			 success:function(data){
			 	$("#DivImprimir").html(data);
			 }
		});
	}	
	function ImprimirP2(Url){
            var ventana=window.open(Url,'Generacion', 'width=300, height=150, resizable=yes, scrollbars=yes');ventana.focus();
	}	
	function Imprimir(Id){
            Imprimir2('imprimir.php?IdKardex=' + Id);
	}	
	function Imprimir2(Url){
            var ventana=window.open(Url,'Generacion', 'width=300, height=150, resizable=yes, scrollbars=yes');ventana.focus();
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
<?php
    Pie();
    CuerpoInferior();
?>
<div id="NuevoParticipante" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivNuevoParticipante" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="ConfirmaGuardarParticipante" title="Confirmaci�n de Operacion" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardarParticipante" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-help" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Confirmar la Operaci&oacute;n?</td>
        </tr>
    </table>
</div>