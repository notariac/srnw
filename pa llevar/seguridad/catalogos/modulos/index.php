<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/claseindex.php");
    CuerpoSuperior("Mantenimiento de M&oacute;dulos");
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT modulos.idmodulo, modulos.descripcion, Padre.descripcion, sistemas.descripcion FROM modulos INNER JOIN sistemas ON modulos.idsistema = sistemas.idsistema INNER JOIN modulos AS Padre ON modulos.idpadre = Padre.idmodulo ";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'modulos.idmodulo', '2'=>'modulos.descripcion', '3'=>'Padre.descripcion', '4'=>'sistemas.descripcion');  //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op;                                                            //Operación
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Descripci&oacute;n', 'T3'=>'Dependencia', 'T4'=>'Sistema');   //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'left', 'A3'=>'left', 'A4'=>'left');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'90');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 700;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY modulos.idsistema, modulos.idpadre, modulos.orden ";                                      //Orden de la Consulta
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
                url='MantModulos.php?Op=' + Op;
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