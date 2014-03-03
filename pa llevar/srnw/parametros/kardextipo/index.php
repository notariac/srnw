<?
    if(!session_start())
    {
        session_start();
    }
    include('../../config.php');

    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");
	
	$TituloVentana = "Mantenimiento de Tipos de Kardex";
	$Criterio = "kardex_tipo";
	
    CuerpoSuperior($TituloVentana);

    $Op = isset($_GET['Op'])?$_GET['Op']:0;

    $FormatoGrilla = array ();
    $Sql = "SELECT kardex_tipo_notaria.idkardex_tipo, kardex_tipo.abreviatura, kardex_tipo.descripcion, kardex_tipo_notaria.actual ";
    $Sql = $Sql." FROM kardex_tipo_notaria INNER JOIN kardex_tipo ON (kardex_tipo_notaria.idkardex_tipo = kardex_tipo.idkardex_tipo)";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex_tipo_notaria.idkardex_tipo', '2'=>'kardex_tipo.abreviatura', '3'=>'kardex_tipo.descripcion');	//Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op; 	                                                         //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Abreviatura', 'T3'=>'Descripci&oacute;n', 'T4'=>'Actual', 'T5'=>'Estado');   //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'left', 'A4'=>'right', 'A5'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'60', 'W2'=>'80', 'W3'=>'310', 'W4'=>'80', 'W5'=>'95');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 700;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " AND CAST(kardex_tipo_notaria.anio AS INT)=".$Anio." ORDER BY kardex_tipo.descripcion ASC ";                                   //Orden de la Consulta
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
							  'BtnCI2'=>'5', 
							  'BtnCV2'=>'1',
							  'BtnId3'=>'BtnRestablecer', 
							  'BtnI3'=>'restablecer.png', 
							  'Btn3'=>'Restablecer', 
							  'BtnF3'=>'onclick="Restablecer(this.id)"', 
							  'BtnCI3'=>'5', 
							  'BtnCV3'=>'0');
							  
    $_SESSION['Formato'] = $FormatoGrilla;
		 
    Cabecera($Previo, $FormatoGrilla[7]);
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
			height: 280,
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
			height: 280,
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
