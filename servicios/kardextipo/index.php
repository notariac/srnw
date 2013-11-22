<?php
    if( !session_id() ){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Mantenimiento de Tipo de Kardex";
    $Criterio = "kardex_tipo";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT distinct kardex_tipo_notaria.idkardex_tipo, kardex_tipo.abreviatura, kardex_tipo.descripcion, kardex_tipo_notaria.actual FROM kardex_tipo_notaria INNER JOIN kardex_tipo ON (kardex_tipo_notaria.idkardex_tipo = kardex_tipo.idkardex_tipo)";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex_tipo_notaria.idkardex_tipo', '2'=>'kardex_tipo.abreviatura', '3'=>'kardex_tipo.descripcion'); //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op; //Operación
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Abreviatura', 'T3'=>'Descripci&oacute;n', 'T4'=>'Actual', 'T5'=>'Estado'); //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'left', 'A4'=>'right', 'A5'=>'center'); //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'60', 'W2'=>'80', 'W3'=>'310', 'W4'=>'80', 'W5'=>'95'); //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 800;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY kardex_tipo_notaria.idkardex_tipo ASC "; //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 												//Botones de Acciones
                              'NB'=>'1',	//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 	//Nombre del Botón
                              'BtnI1'=>'modificar.png', 	//Imagen a mostrar
                              'Btn1'=>'Editar', 			//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'', 	//Item a Comparar
                              'BtnCV1'=>'');				 /*AND CAST(kardex_tipo_notaria.idnotaria AS INT)=".$_SESSION['notaria']." */			  
    $_SESSION['Formato'] = $FormatoGrilla;		 
    Cabecera($Previo, $FormatoGrilla[7]);
?>
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
	var Foco = 'Actual';
	$(document).ready(function(){
		$("#Nuevo").dialog({
                    autoOpen: false,
                    modal: true,
                    resizable:false,
                    title: "Agregar Registro",
                    width: 550,
                    height: 280,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Agregar": function() {
                            Guardar(0);
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
                    width: 550,
                    height: 280,
                    show: "scale",
                    hide: "scale",
                    buttons: {
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
	function Guardar(Op){
            GuardarP(Op);
	}        
	var Id=''
	var Id2=''
	var IdAnt=''
	var Pagina = <?php echo $pagina;?>;
	var nPag = <?php echo $TAMANO_PAGINA;?>;
	function Buscar(Op){
            $.ajax({
                url:'grilla.php',
                type:'POST',
                async:true,
                data:'Pagina='+Pagina+'&Formato=<?php echo serialize($FormatoGrilla);?>'+'&Valor='+$('#Valor').val(),
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
</script>
<?php
    Pie();
    CuerpoInferior();
?>