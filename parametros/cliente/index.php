<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Mantenimiento de Cliente";
    $Criterio = "cliente";
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT cliente.idcliente, cliente_tipo.descripcion, cliente.dni_ruc, unir(cliente.nombres,cliente.ape_paterno,cliente.ap_materno), cliente.direccion, cliente.estado FROM cliente INNER JOIN cliente_tipo ON (cliente.idcliente_tipo = cliente_tipo.idcliente_tipo)";
    $FormatoGrilla[0] = $Sql;    
    $FormatoGrilla[1] = array('1'=>'cliente.idcliente', '2'=>'cliente_tipo.descripcion','3'=>'cliente.dni_ruc', '4'=>'unir(cliente.nombres,cliente.ape_paterno,cliente.ap_materno)');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op;
    $FormatoGrilla[3] = array('T1'=>'C&oacute;digo', 'T2'=>'Tipo', 'T3'=>'Nº Documento', 'T4'=>'Nombres / Raz&oacute;n Social', 'T5'=>'Direcci&oacute;n', 'T6'=>'Estado');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A6'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 850;                                         //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY cliente.idcliente DESC ";        //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 			               //Botones de Acciones
                              'NB'=>'3',	                      //Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 	     //Nombre del Botón
                              'BtnI1'=>'modificar.png', 	    //Imagen a mostrar
                              'Btn1'=>'Editar', 			   //Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'', 	//Item a Comparar
                              'BtnCV1'=>'',		//Valor de comparación
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
<script type="text/javascript" src="../../js/required.js"></script>
<script type="text/javascript">
var Foco = 'DniRuc';
$(document).ready(function(){
    RutaM = 'MantenimientoA.php';
    RutaG = 'guardarA.php';
    PrefM = 'formA';		
    $("#Nuevo").dialog({
        autoOpen: false,
        modal: true,
        resizable:false,
        title: "Agregar Registro",
        width: 750,
        height: 550,
        show: "scale",
        hide: "scale",
        buttons: {
            "Agregar": function() {
                save();
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
        width: 750,
        height: 550,
        show: "scale",
        hide: "scale",
        buttons: {
            "Actualizar": function() {
                save();
            },
            Cancelar: function() {
                $("#DivModificar").html('');
                $(this).dialog("close");
            }
        }	   
    });
 });
function save()
{
    bval = true;  
    var tc = $("#idcliente_tipo").val(),
        idd = $("#iddocumento").val(),
        dni_ruc = $("#DniRuc").val(),
        t = dni_ruc.length;

    if($("#iddocumento").val()==1)
    {
        if(t!=8)
        {
            bval = false;
            alert('Por favor, ingrese numero de DNI valido.');
            $("#DniRuc").focus();
        }
    }
    
    if($("#iddocumento").val()==8)
    {        
        if(t==11)
        {            
            if(!esrucok(dni_ruc))
            {
                bval = false;
                alert('Por favor, ingrese numero de RUC valido.');
                $("#DniRuc").focus();
            }
        }
    }

    bval = bval && $("#RazonNombre2").required();

    if($("#IdDistrito").val()=="000101"&&bval==true){ alert("Complete los datos del ubigeo"); $("#IdDistrito").focus(); return 0; }    
    if(bval)
    {
        var str = $("#formP").serialize();
        $.post('guardarA.php',str,function(r){   
            if(r[0]==0)
            {
                alert(r[2]);
                $("#"+r[1]).focus();
            }
            else 
            {
                if($("#Id").val()!=""){alert("Se ha actualizado correctamente.");$("#Modificar").dialog("close");}
                    else {alert("Se ha registrado correctamente"); $("#Nuevo").dialog('close'); }
            }
        },'json');
    }
}
function Guardar(Op)
{
    if (Op!=2)
    {
        var RaZo2 = $('#RazonNombre2').val();
        var RaZo1 = $('#RazonNombre1').val();
        var Dir = $('#Direccion').val();
        $('#RazonNombre2').val(RaZo2.toUpperCase());
        $('#RazonNombre1').val(RaZo1.toUpperCase());
        $('#Direccion').val(Dir.toUpperCase());
    }	
    GuardarP(Op);
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