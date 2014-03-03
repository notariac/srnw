<?php
    if( !session_id() ){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Registro de Atenci&oacute;n";
    $Criterio = "atencion";	
    $Select = "";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT atencion.idatencion, sql_fecha(atencion.fecha, dd/mm/yyyy), unir(c.nombres,c.ape_paterno,c.ap_materno), atencion_estado.descripcion, atencion.idusuario, atencion.estado FROM atencion_estado INNER JOIN atencion ON (atencion_estado.idatencion_estado = atencion.estado) inner JOIN cliente c ON c.idcliente = atencion.idcliente";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'atencion.idatencion', '2'=>'sql_fecha(atencion.fecha, dd/mm/yyyy)','3'=>'unir(c.nombres,c.ape_paterno,c.ap_materno)', '4'=>'atencion_estado.descripcion', '5'=>'atencion.idusuario');  //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Ticket', 'T2'=>'Fecha', 'T3'=>'Cliente', 'T4'=>'Estado', 'T5'=>'Usuario');   			//TÍtulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'left', 'A4'=>'left', 'A5'=>'center');                        //AlineaciÓn por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W4'=>'80', 'W5'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 750;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY atencion.correlativo DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 			//Botones de Acciones
                              'NB'=>'4',	//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 	//Nombre del Botón
                              'BtnI1'=>'modificar.png', 	//Imagen a mostrar
                              'Btn1'=>'Editar', 			//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'5', 	//Item a Comparar
                              'BtnCV1'=>'0',		//Valor de comparación
                              'BtnId2'=>'BtnEliminar', 
                              'BtnI2'=>'eliminar.png', 
                              'Btn2'=>'Anular', 
                              'BtnF2'=>'onclick="Mostrar(this.id, 2);"', 
                              'BtnCI2'=>'5', 
                              'BtnCV2'=>'0',
                              'BtnId3'=>'BtnRestablecer', 
                              'BtnI3'=>'restablecer.png', 
                              'Btn3'=>'Restablecer', 
                              'BtnF3'=>'onclick="Mostrar(this.id, 3);"', 
                              'BtnCI3'=>'5', 
                              'BtnCV3'=>'2',
                              'BtnId4'=>'BtnVer', 	//Nombre del Boton
                              'BtnI4'=>'view_detail.png', 	//Imagen a mostrar
                              'Btn4'=>'Ver', 			//Titulo del Bot�n
                              'BtnF4'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Bot�n
                              'BtnCI4'=>'', 	//Item a Comparar
                              'BtnCV4'=>'');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    $Previo = "<table width='200' border='0' cellspacing='0' cellpadding='0'><tr><td width='70' style='font-size:14px'>&nbsp;A&ntilde;o : </td><td><select name='Anio' id='Anio' class='select' style='font-size:12px' onchange='Buscar(0);' >";
    $SelectA 	= "SELECT DISTINCT anio FROM atencion WHERE idnotaria='".$_SESSION['notaria']."' ORDER BY anio DESC";
    $ConsultaA = $Conn->Query($SelectA);
    while($rowA=$Conn->FetchArray($ConsultaA)){
        if($rowA[0]==$_SESSION['Anio']){
            $Select="selected";
        }else{
            $Select="";
        }
        $Previo .= "<option value='".$rowA[0]."' $Select>".$rowA[0]."</option>";
    }
    $Previo .= "<option value=''>Todos</option></select></td></tr></table>";
    Cabecera($Previo, $FormatoGrilla[7]);
?>
<script type="text/javascript" src="../../js/required.js"></script>
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script type="text/javascript">
var Foco  = 'Cliente';
var IdImp = 0;
$(document).ready(function()
{
        $("#Nuevo").dialog({
            autoOpen: false,            
            resizable:false,
            title: "Registro de Atenci&oacute;n",
            width: 750,
            height: 500,            
            buttons: {
                "Agregar": function() {
                    Op = 0;
                    if(confirm("Desea confirmar la operacion?"))
                    {
                        Guardar(Op);
                    }
                    //$("#ConfirmaGuardar").dialog("open");
                },
                Cancelar: function() {
                    $("#DivNuevo").html('');
                    $(this).dialog("close");
                }
            }	   
        });
        $("#Modificar").dialog({
            autoOpen: false,
            modal: false,
            resizable:false,
            title: "Modificar Registro",
            width: 750,
            height: 500,
            buttons: {
                "Actualizar": function() {
                    Op = 1;
                    //$("#ConfirmaGuardar").dialog("open");
                    if(confirm("Desea confirmar la operacion?"))
                    {
                        Guardar(Op);
                    }
                },
                Cancelar: function() {
                    $("#DivModificar").html('');
                    $(this).dialog("close");
                }
            }	   
        });
        $("#Eliminar").dialog({
            autoOpen: false,
            modal: false,
            resizable:false,
            title: "Eliminar Registro",
            width: 750,
            height: 500,
            buttons: {
                "Eliminar": function() {
                    Op = 2;
                    //$("#ConfirmaGuardar").dialog("open");
                    if(confirm("Desea confirmar la operacion?"))
                    {
                        Guardar(Op);
                    }
                },
                Cancelar: function() {
                    $("#DivEliminar").html('');
                    $(this).dialog("close");
                }
            }	   
        });
        $("#Restaurar").dialog({
            autoOpen: false,
            modal: true,
            resizable:false,
            title: "Restaurar Registro",
            width: 750,
            height: 500,            
            buttons: {
                "Restaurar": function() {
                    Op = 3;
                    //$("#ConfirmaRestauracion").dialog("open");
                    if(confirm("Desea confirmar la operacion?"))
                    {
                        Guardar(Op);
                    }
                },
                Cancelar: function() {
                    $("#DivRestaurar").html('');
                    $(this).dialog("close");
                }
            }	   
        });
        $("#Ver").dialog({
            autoOpen: false,
            modal: true,
            resizable:false,
            title: "Ver Registro",
            width: 750,
            height: 500,
            buttons: {
                Salir: function() {
                    $("#DivVer").html('');
                    $(this).dialog("close");
                }
            }	   
        });		
        $("#GenComprobante").dialog({
            autoOpen: false,
            modal: true,
            resizable:false,
            title: "Facturaci&oacute;n de Tickets",
            width: 750,
            height: 600,
            buttons: {
                "Generar": function() {
                    Op = 0;
                    if (Validar())
                    {
                            //$("#ConfirmaGuardarC").dialog("open");
                            if(confirm("Desea confirmar la operacion?"))
                            {
                                Guardar(Op);
                            }
                    }
                },
                Cancelar: function() {
                        $("#DivGenComprobante").html('');
                        $(this).dialog("close");
                }
            }	   
        });
        $("#ConfirmaGuardarC").dialog({
            autoOpen: false,
            modal:true,
            resizable:false,
            title: "Confirmaci&oacute;n de Operaci&oacute;n",
            height:155,
            width: 350,
            buttons: {
                "Aceptar": function() {
                    GuardarC(Op);
                    $(this).dialog("close");
                },
                Cancelar: function() {
                    $(this).dialog("close");
                }
            }
        });
});	
function GenerarComprobante(Op){
    $("#GenComprobante").dialog("open");
    $("#DivGenComprobante").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
    $.ajax({
        url:'../../caja/Mantenimiento.php',
        type:'POST',
        async:true,
        data:'Op=0&IdAtencion=' + $("#CorrelativoT").val(),
        success:function(data){
               $("#DivGenComprobante").html(data);
               $("#Id").focus();
        }
    })
}	
function Validar(){
    var RaZo = $('#Nombres').val();
    var Dir = $('#Direccion').val();
    $('#Nombres').val(RaZo.toUpperCase());
    $('#Direccion').val(Dir.toUpperCase());
    if ($('#DniRuc').val()==''){
            alert('Ingrese Nº de Documento del cliente');
            $("#DniRuc").focus();
            return false;
    }
    if ($('#Nombres').val()==''){
            alert('Ingrese Nombre del cliente');
            $("#Nombres").focus();
            return false;
    }
    if ($('#Direccion').val()==''){
            alert('Ingrese la Direcci&oacute;n del cliente');
            $("#Direccion").focus();
            return false;
    }		
    if ($('#ConServicios').val()==0){
            alert('El comprobante no posee detalle');
            return false;
    }
    return true;
}
function GuardarC(Op){
    $.ajax({
        url:'../../caja/guardar.php?Op=' + Op,
        type:'POST',
        async:true,
        data:$('#form1').serialize() + '&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio=<?php echo $Anio;?>',
        success:function(data){
            $("#Mensajes").html(data);
            IdImp = $('#IdFacturacionD').val();
            $("#ConfirmaImprimir").dialog("open");
            $("#DivNuevo").html('');
            $("#DivModificar").html('');
            $("#DivEliminar").html('');
            $("#DivRestaurar").html('');
            $("#Nuevo").dialog("close");
            $("#Modificar").dialog("close");
            $("#Eliminar").dialog("close");
            $("#Restaurar").dialog("close");
            $("#ConfirmaRestauracion").dialog("close");
            Buscar(Op);
        }
    })
}
function saveCliente()
{
    var tc = $("#idcliente_tipo").val();
    bval = true;
    var idd = $("#iddocumento").val();
    if($("#iddocumento").val()==8)
    {        
        var dni_ruc = $("#DniRuc").val();
        if(!esrucok(dni_ruc))
        {
            bval = false;
            alert('Por favor, ingrese numero de RUC valido.');
            $("#DniRuc").focus();
        }
    }
    if(idd!=9)
    {
	bval = bval && $( "#DniRuc" ).required();
    }
    bval = bval && $("#RazonNombre2").required();
    if(tc==1&&idd!=9)
    {
        //bval = bval && $("#ap_paterno").required();
        //bval = bval && $("#ap_materno").required();    
    }    
    if($("#IdDistrito").val()=="000101"&&bval==true){ alert("Complete los datos del ubigeo"); $("#IdDistrito").focus(); return 0; }
    if(tc==2)
    {
       // bval = bval && $("#asiento").required();
       // bval = bval && $("#partida").required();
    }
    if(bval)
    {
        var str = $("#formP").serialize();
        $.post('../../parametros/cliente/guardarA.php',str,function(r){   
            if(r[0]==0)
            {
                alert(r[2]);
                $("#"+r[1]).focus();
            }
            else 
            {
                $("#dnewCliente").dialog("close");
                alert("Se ha registrado correctamente al cliente"); 
                $("#Cliente").val(r[4]);
                $("#idcliente").val(r[6]);
                $("#Direccion").val(r[5]);                     
		$("#dni_ruc").val(r[3]);
                $("#Servicio").focus();                            
            }
        },'json');
    }
    
}
function Imprime(Id){
        IdImp = Id;
        Imprimir();
}
function Imprimir(){
        var ventana=window.open('../../caja/impresion.php?Id=' + IdImp,'Generacion', 'width=850, height=500, resizable=yes, scrollbars=yes');ventana.focus();
}
function Guardar(Op){
    $.ajax({
        url:'guardar.php?Op=' + Op,
        type:'POST',
        async:true,
        data:$('#form1').serialize() + '&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>',
        success:function(data){	
            $("#DivNuevo").html('');
            $("#DivModificar").html('');
            $("#DivEliminar").html('');
            $("#DivRestaurar").html('');
            $("#Nuevo").dialog("close");
            $("#Modificar").dialog("close");
            $("#Eliminar").dialog("close");
            $("#Restaurar").dialog("close");
            $("#ConfirmaRestauracion").dialog("close");
            Buscar(Op);
            $("#NumAtencion").dialog("open");
            $("#DivNumAtencion").html(data);
        }
    })
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
        data:'Pagina=' + Pagina + '&Formato=<?php echo serialize($FormatoGrilla);?>' + '&Valor=' + $('#Valor').val() + '&Anio=' + $('#Anio').val(),
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
<div id="NumAtencion" title="N&uacute;mero Generado" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivNumAtencion" style="width:100%">&nbsp;</div></td>
        </tr>
    </table>
</div>
<div id="GenComprobante" title="Generaci&oacute;n de Comprobante" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGenComprobante" style="width:100%">&nbsp;</div></td>
        </tr>
    </table>
</div>
<div id="ConfirmaGuardarC" title="Confirmaci&oacute;n de Eliminaci&oacute;n" style="display:none;">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardar" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td>&iquest;Desea Confirmar la Operaci&oacute;n?</td>
        </tr>
    </table>
</div>
