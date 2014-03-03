<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");
    $TituloVentana = "Mantenimiento de Opciones Generales";
    $Criterio = "Servicio";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $nActivo = isset($_GET['Activo'])?$_GET['Activo']:1;
    $FormatoGrilla = array ();
    $Sql = "SELECT notaria.idnotaria, notaria.descripcion, notaria.notario, ubigeo.descripcion, notaria.ruc FROM ubigeo INNER JOIN notaria ON (ubigeo.idubigeo = notaria.idubigeo) ";
    $FormatoGrilla[0] = $Sql;      //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'notaria.idnotaria','2'=>'notaria.descripcion', '3'=>'notaria.notario', '4'=>'ubigeo.descripcion', '5'=>'notaria.direccion', '6'=>'notaria.ruc');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Notario', 'T4'=>'Ubigeo','T5'=>'Direcci&oacute;n', 'T6' => 'R.U.C.' );   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A3'=>'center', 'A4'=>'center', 'A5'=>'center', 'A6'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W4'=>'100', 'W5'=>'70', 'W6'=>'70');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 1000;                                         //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY notaria.idnotaria ASC ";         //Orden de la Consulta // 
    $FormatoGrilla[9] = array('Id'=>'1',                              //Botones de Acciones
                              'NB'=>'1',                              //Número de Botones a agregar
                              'BtnId1'=>'BtnModificar',               //Nombre del Botón
                              'BtnI1'=>'modificar.png',               //Imagen a mostrar
                              'Btn1'=>'Editar',                       //Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Bot�n
                              'BtnCI1'=>'', 	//Item a Comparar
                              'BtnCV1'=>''		//Valor de comparación
                              );							  
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera('', $FormatoGrilla[7]);
?>
<script>
var Foco = 'Descripcion';
$(document).ready(function(){
        $("#Modificar").dialog({
            autoOpen: false,
            modal: true,
            resizable:false,
            title: "Modificar Registro",
            width: 650,
            height: 500,
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
        if (<?php echo $nActivo;?>==0){
                Mostrar(1, 1);
        }
});

function Guardar(Op){
    GuardarP(Op);
    if (<?php echo $nActivo;?>==0){
        window.location.href='index.php';
    }
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
}
function ValidarEnter(evt, Op){
  Buscar(Op);
}
$('#tbBusqueda').css("display", "none");
Buscar(<?php echo $Op;?>);
</script>
<?php
    Pie();
    CuerpoInferior();
?>