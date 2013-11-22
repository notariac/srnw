<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Envio de Servicio";
    $Criterio = "kardex";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT kardex.idkardex, atencion.correlativo, kardex.correlativo, sql_fecha(kardex.fecha, dd/mm/yyyy), servicio.descripcion, kardex.escritura, kardex.minuta, kardex.placa, kardex_estado.descripcion, kardex.idusuario, kardex.estado FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_estado ON (kardex.estado = kardex_estado.idkardex_estado) INNER JOIN atencion_detalle ON (atencion_detalle.idatencion = kardex.idatencion) INNER JOIN atencion ON (atencion_detalle.idatencion = atencion.idatencion)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex.idatencion', '2'=>'atencion.correlativo', '3'=>'kardex.correlativo', '4'=>'sql_fecha(kardex.fecha, dd/mm/yyyy)', '5'=>'servicio.descripcion', '6'=>'kardex.escritura', '7'=>'kardex.minuta','8'=>'kardex.placa', '9'=>'kardex.estado', '10'=>'kardex.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operación
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Ticket', 'T3'=>'Nº Kardex', 'T4'=>'Fecha', 'T5'=>'Servicio', 'T6'=>'Nº Escritura', 'T7'=>'Nº Minuta','T8'=>'Nº Placa', 'T9'=>'Estado', 'T10'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center', 'A8'=>'center', 'A9'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'50', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'70', 'W8'=>'70', 'W9'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 950;                                   	//Ancho de la Tabla
    $FormatoGrilla[8] = " AND kardex.estado <> 3 ORDER BY kardex.correlativo DESC "; 	//Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 						//Botones de Acciones
                              'NB'=>'2',						//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 		//Nombre del Botón
                              'BtnI1'=>'modificar.png', 		//Imagen a mostrar
                              'Btn1'=>'Editar', 				//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'11', 					//Item a Comparar
                              'BtnCV1'=>'1',					//Valor de comparación
                              'BtnCC1'=>'==',					//Condicion
                              'BtnId2'=>'BtnVer', 				//Nombre del Boton
                              'BtnI2'=>'view_detail.png', 		//Imagen a mostrar
                              'Btn12'=>'Ver', 					//Titulo del Botón
                              'BtnF2'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Botón
                              'BtnCI2'=>'', 					//Item a Comparar
                              'BtnCV2'=>'',
                              'BtnCC2'=>'==');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<script>
	var Foco = 'Cliente';
	$(document).ready(function(){			
		$("#Modificar").dialog({
			autoOpen: false,			
			resizable:false,
			title: "Modificar Registro",
			width: 650,
			height: 600,
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
			resizable:false,
			title: "Ver Registro",
			width: 650,
			height: 550,
			buttons: {
				Salir: function() {
                                    $("#DivVer").html('');
                                    $(this).dialog("close");
				}
			}	   
		});
		$("#Imprimir").dialog({
			autoOpen: false,			
			resizable:false,
			title: "Imprimir Libro",
			width: 300,
			height: 200,
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
		if ($('#RazonSocial').val()==''){
			alert('Ingrese la Raz&oacute;n Social!');
			$('#RazonSocial').focus();
			return false;
		}
		if ($('#Ruc').val()==''){
			alert('Ingrese en RUC!');
			$('#Ruc').focus();
			return false;
		}
		if ($('#Direccion').val()==''){
			alert('Ingrese la Direccion!');
			$('#Direccion').focus();
			return false;
		}
		if ($('#Telefono').val()==''){
			alert('Ingrese el Telefono!');
			$('#Telefono').focus();
			return false;
		}
		if ($('#Numero').val()==''){
			alert('Ingrese el N&uacte;mero de Libro!');
			$('#Numero').focus();
			return false;
		}
		if ($('#FolioInicial').val()==''){
			alert('Ingrese el Folio Inicial!');
			$('#FolioInicial').focus();
			return false;
		}
		if ($('#FolioFinal').val()==''){
			alert('Ingrese el Folio Final!');
			$('#FolioFinal').focus();
			return false;
		}
		if ($('#Solicitante').val()==''){
			alert('Ingrese el Solicitante!');
			$('#Solicitante').focus();
			return false;
		}
		if ($('#Dni').val()==''){
			alert('Ingrese el DNI del Solicitante!');
			$('#Dni').focus();
			return false;
		}
		return true;
	}	
	function Guardar(Op){
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
</script>
<script>
Buscar(<?php echo $Op;?>);
$('#Valor').focus();
$('#BtnNuevo').css("display", "none");
</script>
<?php
    Pie();
    CuerpoInferior();
?>
<div id="ActualizaDatos" title="Actualizacion" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivActualizaDatos" style="width:100%"></div></td>
        </tr>
    </table>
</div>