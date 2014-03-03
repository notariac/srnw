<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../clases/main.php");
    include("../../clases/claseindex.php");
    CuerpoSuperior("Mantenimiento de Acceso por Perfil");
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
    $FormatoGrilla[8] = " AND estado=1 ORDER BY descripcion ASC ";                                      //Orden de la Consulta
    $_SESSION['Formato'] = $FormatoGrilla;
    Cabecera();
?>
<script type="text/javascript" src="../../js/jquery-ui-1.8.11.custom.min.js"></script>
<script>
	var Id='';
	var Id2='';
	var IdAnt='';
	var Pagina = <?php echo $pagina;?>;
	var nPag = <?php echo $TAMANO_PAGINA;?>;
	function Operacion(Op){
            var url
            if (Op!=4){
                CargarPerfiles(Id)
            }else{
                window.parent.location .href = '../../index.php';
            }
	}
	function CargarPerfiles(IdSistema){
            $.ajax({
                url:'<?php echo $urlDir;?>clases/perfiles.php',
                type:'POST',
                async:true,
                data:'IdSistema=' + IdSistema,
                success:function(data){
                    $("#DivPerfiles").html(data);
                    $("#DivPerfiles").dialog({title:'Perfiles : ' + $("#Sistema").val()});
                    $("#DivPerfiles").dialog("open");
                }
            });
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
            })
            document.getElementById('Valor').focus();
	}
	function ValidarEnter(evt, Op){
            Buscar(Op);
	}
</script>
<script>
    Buscar(<?php echo $Op;?>);
    document.getElementById('Valor').focus();
    $('#BtnNuevo').css('display', 'none');
    $('#BtnEliminar').css('display', 'none');
</script>
<?php
    Pie();
    CuerpoInferior();
?>
<script>
    $(document).ready(function() {
        $("#DivPerfiles").dialog({
            autoOpen: false,
            height: 400,
            width: 350,
            resizable: false,
            modal: true,
            title: 'Perfiles : ',
            buttons: {},
            close: function() {
                $(this).html('');
                $("#DivPerfiles").dialog({width:350, minWidth: 350, maxWidth: 350});
                $("#DivPerfilesM").html('');
            }
	});
    });
    function VermodulosPerfil(IdSistema, IdPerfil){
        $("#DivPerfiles").animate({width:"650"},500);
        $("#DivPerfiles").dialog({width:650, minWidth: 650, maxWidth: 650});
        $.ajax({
            url:'<?php echo $urlDir;?>clases/perfiles_modulos.php',
            type:'POST',
            async:true,
            data:'IdSistema=' + IdSistema + '&IdPerfil='+ IdPerfil,
            success:function(data){
                $("#DivPerfilesM").html(data);
            }
        });
    }	
    function ValidarModulos(){
        document.FormModulos.submit();
    }
    function CancelarModulos(){
        $("#DivPerfiles").animate({width:"350"},500);
        setTimeout('$("#DivPerfilesM").html("")',500);
        setTimeout('$("#DivPerfiles").dialog({width:350, minWidth: 350, maxWidth: 350});', 500);
    }
</script>
<div id="DivPerfiles"></div>