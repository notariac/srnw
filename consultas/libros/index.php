<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Consulta de Libros";
    $Criterio = "libro";
    $Desde = date('d/m/Y');
    $Hasta = date('d/m/Y');	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT libro.idlibro, libro.idatencion, libro.correlativo, sql_fecha(libro.fecha, dd/mm/yyyy), libro.razonsocial, libro_estado.descripcion, libro.idusuario, libro.estado FROM libro_estado INNER JOIN libro ON (libro_estado.idlibro_estado = libro.estado)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'libro.idatencion', '2'=>'sql_fecha(libro.fecha, dd/mm/yyyy)', '3'=>'libro.correlativo', '4'=>'libro.razonsocial', '5'=>'libro_estado.descripcion', '6'=>'libro.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Atenci&oacute;n', 'T3'=>'Nro Libro', 'T4'=>'Fecha', 'T5'=>'Raz&oacute;n Social', 'T6'=>'Estado', 'T7'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por P�ginas
    $FormatoGrilla[7] = 800;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY libro.correlativo DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 						//Botones de Acciones
                              'NB'=>'0');							  
    $_SESSION['Formato'] = $FormatoGrilla;	
    $Previo = "<table width='400' border='0' cellspacing='0' cellpadding='0'>";
    $Previo .= "<tr><td>&nbsp;</td></tr>";
    $Previo .= "<tr><td width='10'>&nbsp;</td><td>Desde : </td><td><input type='text' name='Desde' id='Desde' style='width:80px' onChange='Buscar(0)' /></td>";
    $Previo .= "<td>Hasta : </td><td><input type='text' name='Hasta' id='Hasta' style='width:80px' onChange='Buscar(0)' /></td></tr>";
    $Previo .= "</table>";		 
    Cabecera($Previo, $FormatoGrilla[7]);
?>
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	var Foco = 'Cliente';
	$(document).ready(function(){			
            $("#Modificar").dialog({
                autoOpen: false,
                modal: true,
                resizable:false,
                title: "Modificar Registro",
                width: 600,
                height: 470,
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
                width: 600,
                height: 470,
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
                        $(this).dialog("close");
                    },
                    Salir: function() {
                        $("#DivImprimir").html('');
                        $(this).dialog("close");
                    }
                }	   
            });
	});	
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
	function Validar(){
            if ($('#RazonSocial').val()==''){
                    alert('Ingrese la Raz&oacute;n Social!');
                    $('#RazonSocial').focus();
                    return false;
            }
            if ($('#Ruc').val()=='' || $('#Ruc').val().length!=11){
                    alert('Ingrese en RUC correctamente!');
                    $('#Ruc').focus();
                    return false;
            }
            if ($('#Direccion').val()==''){
                    alert('Ingrese la Direcci&oacute;n!');
                    $('#Direccion').focus();
                    return false;
            }
            if ($('#Telefono').val()==''){
                    alert('Ingrese el Tel&eacute;fono!');
                    $('#Telefono').focus();
                    return false;
            }
            if ($('#Numero').val()==''){
                    alert('Ingrese el N&uacute;mero de Libro!');
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
            $("#Imprimir").dialog("open");
            $("#DivImprimir").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");	
            $.ajax({
                url:'generaword.php',
                type:'POST',
                async:true,
                data:'IdLibro=' + Id,
                success:function(data){
                   $("#DivImprimir").html(data);
                }
            });
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
                data:'Pagina=' + Pagina + '&Formato=<?php echo serialize($FormatoGrilla);?>' + '&Valor=' + $('#Valor').val() + '&Desde=' + $('#Desde').val() + '&Hasta=' + $('#Hasta').val(),
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
    $('#Valor').focus();
    $('#BtnNuevo').css("display", "none");
    $('#Desde').val('<?php echo $Desde;?>');
    $('#Hasta').val('<?php echo $Hasta;?>');
    Buscar(<?php echo $Op;?>);
</script>
<?php
    Pie();
    CuerpoInferior();
?>