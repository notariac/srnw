<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Mantenimiento de Caja";
    $Criterio = "caja";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT idcaja, descripcion, estado ";
    $Sql = $Sql." FROM caja ";
    $FormatoGrilla[0] = $Sql;             		//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'descripcion');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Estado');   			//Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A3'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W3'=>'100', );     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 500;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " AND CAST(caja.idnotaria AS INT)=".$_SESSION['notaria']." ORDER BY descripcion ASC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 			//Botones de Acciones
                              'NB'=>'3',	//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 	//Nombre del Botón
                              'BtnI1'=>'modificar.png', 	//Imagen a mostrar
                              'Btn1'=>'Editar', 			//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'', 	//Item a Comparar
                              'BtnCV1'=>'',		//Valor de comparaci�n
                              'BtnId2'=>'BtnEliminar', 
                              'BtnI2'=>'eliminar.png', 
                              'Btn2'=>'Eliminar', 
                              'BtnF2'=>'onclick="Eliminar(this.id)"', 
                              'BtnCI2'=>'6', 
                              'BtnCV2'=>'1',
                              'BtnId3'=>'BtnRestablecer', 
                              'BtnI3'=>'restablecer.png', 
                              'Btn3'=>'Restablecer', 
                              'BtnF3'=>'onclick="Restablecer(this.id)"', 
                              'BtnCI3'=>'6', 
                              'BtnCV3'=>'0');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<script>
$(document).ready(function(){
    $("#Nuevo").dialog({
        autoOpen: false,
        modal: true,
        resizable:false,
        title: "Agregar Registro",
        width: 550,
        height: 400,
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
        height: 400,
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