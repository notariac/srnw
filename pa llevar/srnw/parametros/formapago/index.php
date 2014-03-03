<?php
    if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Mantenimiento de Forma de Pago";
    $Criterio = "forma_pago";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT idforma_pago, descripcion, estado ";
    $Sql = $Sql." FROM forma_pago ";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'idforma_pago', '2'=>'descripcion');  				//Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op;                                                            //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Estado');   //T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'left', 'A3'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'60', 'W2'=>'380', 'W3'=>'95');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por P�ginas
    $FormatoGrilla[7] = 850;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY idforma_pago ASC ";                                   //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 												//Botones de Acciones
                                  'NB'=>'3',	//N�mero de Botones a agregar
                                  'BtnId1'=>'BtnModificar', 	//Nombre del Boton
                                  'BtnI1'=>'modificar.png', 	//Imagen a mostrar
                                  'Btn1'=>'Editar', 			//Titulo del Bot�n
                                  'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Bot�n
                                  'BtnCI1'=>'', 	//Item a Comparar
                                  'BtnCV1'=>'',		//Valor de comparaci�n
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
    Cabecera('', 850);
?>
<script>
	var Foco = 'Descripcion';
	$(document).ready(function(){
		$("#Nuevo").dialog({
			autoOpen: false,
			modal: true,
			resizable:false,
			title: "Agregar Registro",
			width: 550,
			height: 220,
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
			height: 220,
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
	var Id='';
	var Id2='';
	var IdAnt='';
	var Pagina = <?php echo $pagina;?>;
	var nPag = <?php echo $TAMANO_PAGINA;?>;
	function Buscar(Op){
		$.ajax({
                    url:'<?php echo $urlDir;?>libs/grilla.php',
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
</script>
<?php
    Pie();
    CuerpoInferior();
?>