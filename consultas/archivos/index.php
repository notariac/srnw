<?php
    if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Directorio de Archivos";
    $Criterio = "kardex";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT kardex.idkardex, kardex.idatencion, kardex.correlativo, sql_fecha(kardex.fecha, dd/mm/yyyy), servicio.descripcion, kardex_estado.descripcion, kardex.idusuario, kardex.estado FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_estado ON (kardex.estado = kardex_estado.idkardex_estado)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex.idatencion', '2'=>'sql_fecha(kardex.fecha, dd/mm/yyyy)', '3'=>'kardex.correlativo', '4'=>'servicio.descripcion', '5'=>'kardex_estado.descripcion', '6'=>'kardex.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Atenci&oacute;n', 'T3'=>'Nº Kardex', 'T4'=>'Fecha', 'T5'=>'Servicio', 'T6'=>'Estado', 'T7'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por P�ginas
    $FormatoGrilla[7] = 900;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " AND kardex.archivo is not null ORDER BY kardex.correlativo DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 						//Botones de Acciones
                              'NB'=>'2',						//N�mero de Botones a agregar
                              'BtnId1'=>'BtnArchivo', 	//Nombre del Boton
                              'BtnI1'=>'word.png', 	//Imagen a mostrar
                              'Btn1'=>'Abrir Archivo', 			//Titulo del Bot�n
                              'BtnF1'=>'onclick="ImprimirA(this.id);"',	//Eventos del Bot�n
                              'BtnCI1'=>'8', 	//Item a Comparar
                              'BtnCV1'=>'1',
                              'BtnCC1'=>'==',
                              'BtnId2'=>'BtnArchivo', 	//Nombre del Boton
                              'BtnI2'=>'open.gif', 	//Imagen a mostrar
                              'Btn2'=>'Abrir Parte Adjuntado', 			//Titulo del Bot�n
                              'BtnF2'=>'onclick="ImprimirP(this.id);"',	//Eventos del Bot�n
                              'BtnCI2'=>'8', 	//Item a Comparar
                              'BtnCV2'=>'1',
                              'BtnCC2'=>'==');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<link href="<?php echo $urlDir;?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script>	
	function ImprimirA(Id){
            Imprimir('imprimirA.php?IdKardex=' + Id);
	}	
	function ImprimirP(Id){
            Imprimir('imprimirP.php?IdKardex=' + Id);
	}	
	function Imprimir(Url){
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
            });
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
<div id="ConfirmaGuardarParticipante" title="Confirmaci&oacute;n de Operaci&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardarParticipante" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-help" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Confirmar la Operaci&oacute;n?</td>
        </tr>
    </table>
</div>