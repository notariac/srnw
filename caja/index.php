<?php
if(!session_id()){ session_start(); }
    include('../config.php');
    include("../libs/masterpage.php");
    include("../libs/claseindex.php");
    $TituloVentana = "Facturaci&oacute;n de Ticket";
    $Criterio = "atencion";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT facturacion.idfacturacion, atencion.idatencion, sql_fecha(facturacion.facturacion_fecha, dd/mm/yyyy), comprobante.abreviatura, facturacion.comprobante_serie, facturacion.comprobante_numero, facturacion.dni_ruc, facturacion.nombres, facturacion.total, facturacion.estado,substring(forma_pago.descripcion,1,2), facturacion.idusuario FROM facturacion INNER JOIN forma_pago on facturacion.idforma_pago = forma_pago.idforma_pago INNER JOIN comprobante ON (facturacion.idcomprobante = comprobante.idcomprobante) INNER JOIN atencion ON (facturacion.idatencion = atencion.idatencion)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'facturacion.idfacturacion','2'=>'atencion.idatencion', '3'=>'sql_fecha(facturacion.facturacion_fecha, dd/mm/yyyy)', '4'=>'comprobante.descripcion', '5'=>'facturacion.comprobante_serie', '6'=>'facturacion.comprobante_numero', '7'=>'facturacion.dni_ruc', '8'=>'facturacion.nombres','9'=>'forma_pago.descripcion');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Ticket', 'T3'=>'Fecha', 'T4'=>'Comp.', 'T5'=>'Serie', 'T6'=>'N&uacute;mero', 'T7'=>'Documento', 'T8'=>'Cliente', 'T9'=>'Total', 'T10'=>'Estado','T11'=>'FP','T12'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A5'=>'center', 'A7'=>'center', 'A9'=>'right', 'A10'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'50', 'W3'=>'70', 'W4'=>'50', 'W5'=>'50');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 950;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY facturacion.idfacturacion DESC,atencion.idatencion desc  ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 			//Botones de Acciones
                              'NB'=>'5',	//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 	//Nombre del Botón
                              'BtnI1'=>'modificar.png', 	//Imagen a mostrar
                              'Btn1'=>'Editar', 			//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'10', 	//Item a Comparar
                              'BtnCV1'=>'1',		//Valor de comparación
                              'BtnId2'=>'BtnEliminar', 
                              'BtnI2'=>'eliminar.png', 
                              'Btn2'=>'Anular', 
                              'BtnF2'=>'onclick="Mostrar(this.id, 2);"', 
                              'BtnCI2'=>'10', 
                              'BtnCV2'=>'1',
                              'BtnId3'=>'BtnRestablecer', 
                              'BtnI3'=>'restablecer.png', 
                              'Btn3'=>'Restablecer', 
                              'BtnF3'=>'onclick="Mostrar(this.id, 3);"', 
                              'BtnCI3'=>'10', 
                              'BtnCV3'=>'2',
                              'BtnId4'=>'BtnVer', 	//Nombre del Boton
                              'BtnI4'=>'view_detail.png', 	//Imagen a mostrar
                              'Btn14'=>'Ver', 			//Titulo del Bot�n
                              'BtnF4'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Bot�n
                              'BtnCI4'=>'', 	//Item a Comparar
                              'BtnCV4'=>'',
                              'BtnId5'=>'BtnImprimir', 	//Nombre del Boton
                              'BtnI5'=>'imprimir.png', 	//Imagen a mostrar
                              'Btn15'=>'Imprimir Comprobante', 			//Titulo del Bot�n
                              'BtnF5'=>'onclick="Imprime(this.id);"',	//Eventos del Bot�n
                              'BtnCI5'=>'', 	//Item a Comparar
                              'BtnCV5'=>'');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    $Previo = "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
    $Previo .= "<tr><td width='70' style='font-size:14px'>&nbsp;A&ntilde;o : </td><td><select name='Anio' id='Anio' class='select' style='font-size:12px' onchange='Buscar(0);' >";
    $SelectA 	= "SELECT DISTINCT anio FROM kardex ORDER BY anio DESC";
    $ConsultaA = $Conn->Query($SelectA);
    while($rowA=$Conn->FetchArray($ConsultaA)){
    $Previo .= "<option value='".$rowA[0]."'>".$rowA[0]."</option>";
    }
    $Previo .= "<option value=''>Todos</option>";
    $Previo .= "</select>
        </td>
        </tr>";
    $Previo .= "</table>";		 
    Cabecera($Previo, $FormatoGrilla[7]);
?>
<script>
	var Foco = 'Cliente';
	var IdImp = 0;
	$(document).ready(function(){		
            
            $("#Nuevo").dialog({
                autoOpen: false,                
                resizable:false,
                title: "Facturaci&oacute;n de Ticket",
                width: 750,
                height: 600,
                buttons: {
                    "Generar": function() {
                        Op = 0;
                        if (Validar()){
                            $("#ConfirmaGuardar").dialog("open");
                        }
                    },
                    Cancelar: function() {
                        $("#DivNuevo").html('');
                        $(this).dialog("close");
                    }
                }	   
            });
            $("#Modificar").dialog({
                autoOpen: false,                
                resizable:false,
                title: "Modificar Registro",
                width: 750,
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
            $("#Eliminar").dialog({
                autoOpen: false,                
                resizable:false,
                title: "Eliminar Registro",
                width: 750,
                height: 600,
                buttons: {
                    "Anular": function() {
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
                resizable:false,
                title: "Restaurar Registro",
                width: 750,
                height: 500,
                buttons: {
                    "Restaurar": function() {
                        Op = 3;
                        $("#ConfirmaRestauracion").dialog("open");
                    },
                    Cancelar: function() {
                        $("#DivRestaurar").html('');
                        $(this).dialog("close");
                    }
                }	   
            });
            $("#Ver").dialog({
                    autoOpen: false,                    
                    resizable:false,
                    title: "Ver Registro",
                    width: 750,
                    height: 500,
                    buttons: {
                        Salir: function() {
                            $("#DivVer").html('');
                            $(this).dialog("close");
                        }
                    }	   
            });
	});	 
	function Guardar(Op){
            $.ajax({
                url:'guardar.php?Op=' + Op,
                type:'POST',
                async:true,
                data:$('#form1').serialize() + '&0form1_idusuario=<?php echo $IdUsuario;?>&0formD_idusuario=<?php echo $IdUsuario ?>&3formD_fechareg=<?php echo $Fecha; ?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio=<?php echo $Anio;?>',
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
	function Validar(){
		var RaZo = $('#Nombres').val();
		var Dir = $('#Direccion').val();
		$('#Nombres').val(RaZo.toUpperCase());
		$('#Direccion').val(Dir.toUpperCase());		
		if ($('#DniRuc').val()=='')
        {
                    alert('Ingrese Documento Nº del cliente');
                    $("#DniRuc").focus();
                    return false;
		}
		if ($('#Nombres').val()=='')
        {
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
			alert('El comprobante no posee detalle');
			return false;
		}
        var td = $("#Comprobante").val();
        if(td==2)
        {
            var ndoc = $("#DniRuc").val();
            var t = ndoc.lenght;
            if(t!=11)
            {
                alert("Para generar una factura se debe ingresar una persona juridica. (RUC)");
                return false;
            }
        }
		return true;
	}	
	function Imprime(Id){
            IdImp = Id;
            Imprimir();
	}
	function Imprimir(){
            var ventana=window.open('impresion.php?Id=' + IdImp,'Generacion', 'width=850, height=500, resizable=yes, scrollbars=yes');ventana.focus();		
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
                data:'Pagina=' + Pagina + '&Formato=<?php echo serialize($FormatoGrilla);?>' + '&Valor=' + $('#Valor').val() + '&Anio=' + $('#Anio').val(),
                success:function(data){
                    $("#DivDetalle").html(data);
                    $('#TotalReg').html($('#NumReg').val());
                    var optInit = getOptionsFromForm();
                    $("#Pagination").pagination($('#NumReg').val(), optInit);
                }
            });
            $('#Valor').focus();
	}
    function ValidarEnter(evt, Op){
        Buscar(Op);
    }
Buscar(<?php echo $Op;?>);
$('#Valor').focus();
</script>
<?php
    Pie();
    CuerpoInferior();
?>
