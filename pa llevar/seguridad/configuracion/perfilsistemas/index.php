<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/claseindex.php");
    CuerpoSuperior("P&eacute;rfil por Sistema");
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT idsistema, descripcion, referencia FROM sistemas ";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'idsistema', '2'=>'descripcion', '3'=>'referencia');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op;                                                            //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Referencia');   //T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'left', 'A3'=>'left');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por P�ginas
    $FormatoGrilla[7] = 700;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY descripcion ASC ";                                     //Orden de la Consulta
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera();
?>
<script>
var Id='';
var Id2='';
var IdAnt='';
var Pagina = <?php echo $pagina;?>;
var nPag = <?php echo $TAMANO_PAGINA;?>;
function Operacion(Op){
    var url;
    if (Op!=4){
        url='MantPerfiles.php?Op=' + Op;
        if (Op!=0){
            if (Id==''){
                alert('Seleccione un Item de la Lista');
                return;
            }
            url = url + '&' + Id;
        }
        location.href = url;
    }else{
        window.parent.location .href = '../../index.php';
    }
}
function Buscar(Op){
    $.ajax({
        url:'<?php echo $urlDir;?>clases/grilla.php',
        type:'POST',
        async:true,
        data:'Pagina=' + Pagina + '&Formato=<?php echo serialize($FormatoGrilla);?>' + '&Valor=' + $('#Valor').val(),
        success:function(data){
            document.getElementById("DivDetalle").innerHTML = data;
            $('#TotalReg').html($('#NumReg').val());
            var optInit = getOptionsFromForm();
            $("#Pagination").pagination($('#NumReg').val(), optInit);
        }
    });
    document.getElementById('Valor').focus();
}
function ValidarEnter(evt, Op){
    Buscar(Op);
}
Buscar(<?php echo $Op;?>);
document.getElementById('Valor').focus();
$('#BtnNuevo').css('display', 'none');
$('#BtnEliminar').css('display', 'none');
</script>
<?php
Pie();
CuerpoInferior();
?>