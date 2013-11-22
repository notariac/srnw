<?
    if(!session_start())
    {
        session_start();
    }
    include('../../config.php');

    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");
	
	$TituloVentana = "Mantenimiento del Estado Civil";
	$Criterio = "estado_civil";
	
    CuerpoSuperior($TituloVentana);

    $Op = isset($_GET['Op'])?$_GET['Op']:0;

    $FormatoGrilla = array ();
    $Sql = "SELECT id".$Criterio.", descripcion, estado ";
    $Sql = $Sql." FROM ".$Criterio;
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'id'.$Criterio, '2'=>'descripcion');  				//Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op;                                                            //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Estado');   //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'left', 'A3'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'60', 'W2'=>'380', 'W3'=>'95');                                 //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 600;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY descripcion ASC ";                                   //Orden de la Consulta
	$FormatoGrilla[9] = array('Id'=>'1', 												//Botones de Acciones
							  'NB'=>'3',	//Número de Botones a agregar
							  'BtnId1'=>'BtnModificar', 	//Nombre del Boton
							  'BtnI1'=>'modificar.png', 	//Imagen a mostrar
							  'Btn1'=>'Editar', 			//Titulo del Botón
							  'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
							  'BtnCI1'=>'', 	//Item a Comparar
							  'BtnCV1'=>'',		//Valor de comparación
							  'BtnId2'=>'BtnEliminar', 
							  'BtnI2'=>'eliminar.png', 
							  'Btn2'=>'Eliminar', 
							  'BtnF2'=>'onclick="Eliminar(this.id)"', 
							  'BtnCI2'=>'3', 
							  'BtnCV2'=>'1',
							  'BtnId3'=>'BtnRestablecer', 
							  'BtnI3'=>'restablecer.png', 
							  'Btn3'=>'Restablecer', 
							  'BtnF3'=>'onclick="Restablecer(this.id)"', 
							  'BtnCI3'=>'3', 
							  'BtnCV3'=>'0');
							  
    $_SESSION['Formato'] = $FormatoGrilla;

    Cabecera('', 600);
?>
<link href="<?=$urlDir?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$urlDir?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	var Foco = 'Descripcion';
	$(document).ready(function(){
		$("#Nuevo").dialog			//Ventanas de Dialogo Modal	para Agregar un Nuevo Registro
		({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Agregar Registro",
			width: 550,
			height: 220,
			show: "scale",
			hide: "scale",
			buttons: 
			{
				"Agregar": function() {
					Guardar(0);
				},
				Cancelar: function() {
					$("#DivNuevo").html('');
					$(this).dialog("close");
				}
			}	   
		});
		$("#Modificar").dialog		//Ventanas de Dialogo Modal	para Modificar un Registro
		({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Modificar Registro",
			width: 550,
			height: 220,
			show: "scale",
			hide: "scale",
			buttons: 
			{
				"Actualizar": function() {
					Guardar(1);
				},
				Cancelar: function() {
					$("#DivModificar").html('');
					$(this).dialog("close");
				}
			}	   
		});
	 });
	
	function Guardar(Op)
	{
		GuardarP(Op);
	}
</script>
<script>
	var Id=''
	var Id2=''
	var IdAnt=''
	var Pagina = <?=$pagina?>;
	var nPag = <?=$TAMANO_PAGINA?>;

	function Buscar(Op)
	{
		$.ajax({
				url:'<?=$urlDir?>libs/grilla.php',
				type:'POST',
				async:true,
				data:'Pagina=' + Pagina + '&Formato=<?=serialize($FormatoGrilla)?>' + '&Valor=' + $('#Valor').val(),
				success:function(data){
					$("#DivDetalle").html(data);
					$('#TotalReg').html($('#NumReg').val());
					var optInit = getOptionsFromForm();
					$("#Pagination").pagination($('#NumReg').val(), optInit);
				 }
		})
		$('#Valor').focus();
	}

	function ValidarEnter(evt, Op)
 	{
    	Buscar(Op);
        //ValidarEnterG(evt, Op)
 	}
</script>
<script>
    Buscar(<?=$Op?>);
    $('#Valor').focus();
</script>
<?
    Pie();
    CuerpoInferior();
?>
