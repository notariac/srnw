<?
    if(!session_start())
    {
        session_start();
    }
    include('../../config.php');

    include("../../clases/main.php");
    include("../../clases/claseindex.php");

    CuerpoSuperior("Mantenimiento de Sistemas por Usuario");

    $Op = isset($_GET['Op'])?$_GET['Op']:0;

    $FormatoGrilla = array ();
    $Sql = "SELECT idusuario, dni, nombres, login, estado FROM usuario ";
    $FormatoGrilla[0] = $Sql;                                                           //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'idusuario', '2'=>'dni', '3'=>'nombres', '4'=>'login');  //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op;                                                            //Operacion
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'D.N.I.', 'T3'=>'Nombres', 'T4'=>'Login', 'T5'=>'Estado');   //Títulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'left', 'A4'=>'center', 'A5'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W4'=>'80', 'W5'=>'50');                                  //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                                    //Registro por Páginas
    $FormatoGrilla[7] = 700;                                                            //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY nombres ASC ";                                      //Orden de la Consulta

    $_SESSION['Formato'] = $FormatoGrilla;

    Cabecera();
?>
<script type="text/javascript" src="../../js/jquery-ui-1.8.11.custom.min.js"></script>
<script>
	var Id=''
	var Id2=''
	var IdAnt=''
	var Pagina = <?=$pagina?>;
	var nPag = <?=$TAMANO_PAGINA?>;

	function Operacion(Op)
	{
		var url
		if (Op!=4)
		{
			url='MantUsuario.php?Op=' + Op;
			if (Op!=0)
			{
				if (Id=='')
				{
					alert('Seleccione un Item de la Lista');
					return;
				}
				url = url + '&' + Id;
			}
			location.href = url;
		}
		else
		{
			window.parent.location .href = '../../index.php';
		}
	}

        function CargarPerfiles(IdSistema)
        {
            $.ajax({
                    url:'<?=$urlDir?>clases/perfiles.php',
                    type:'POST',
                    async:true,
                    data:'IdSistema=' + IdSistema,
                    success:function(data){
                        $("#DivPerfiles").html(data);
                        $("#DivPerfiles").dialog({title:'Perfiles : ' + $("#Sistema").val()});
                        $("#DivPerfiles").dialog("open");
                     }
            })
        }
	function Buscar(Op)
	{
            $.ajax({
                    url:'<?=$urlDir?>clases/grilla.php',
                    type:'POST',
                    async:true,
                    data:'Pagina=' + Pagina + '&Formato=<?=serialize($FormatoGrilla)?>' + '&Valor=' + $('#Valor').val(),
                    success:function(data){
                        document.getElementById("DivDetalle").innerHTML = data;
                        $('#TotalReg').html($('#NumReg').val());
                        var optInit = getOptionsFromForm();
                        $("#Pagination").pagination($('#NumReg').val(), optInit);
                     }
            })
            //BuscarG(Op);
            document.getElementById('Valor').focus();
	}

	function ValidarEnter(evt, Op)
        {
            Buscar(Op);
            //ValidarEnterG(evt, Op)
        }
</script>
<script>
    Buscar(<?=$Op?>);
    document.getElementById('Valor').focus();
    $('#BtnNuevo').css('display', 'none');
    $('#BtnEliminar').css('display', 'none');
</script>
<?
    Pie();
    CuerpoInferior();
?>