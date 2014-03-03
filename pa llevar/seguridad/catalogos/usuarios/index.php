<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    CuerpoSuperior("Mantenimiento de Usuario");
    include("../../clases/claseindex.php");
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $FormatoGrilla[0] = "SELECT idusuario, dni, nombres, telefonos, login FROM usuario ";         //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'dni', '2'=>'nombres', '3'=>'telefonos', '4'=>'login');               //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op;                                                            //Operación
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'D.N.I.', 'T3'=>'Nombres', 'T4'=>'Telefonos', 'T5'=>'Login');   //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'left', 'A4'=>'left', 'A5'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W5'=>'90');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 700;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY nombres ASC ";                                      //Orden de la Consulta
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
    var url
    if (Op!=4){
        url='MantUsuario.php?Op=' + Op;
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
</script>
<?php
Pie();
CuerpoInferior();
?>