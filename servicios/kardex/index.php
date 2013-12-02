<?php
if( !session_id() ){ session_start(); }
if ( strlen ($_SESSION['id_user'])>0 ) {
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Generaci&oacute;n de Kardex";
    $Criterio = "kardex";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT kardex.idkardex, atencion.idatencion, kardex.correlativo, sql_fecha(kardex.fecha, dd/mm/yyyy), servicio.descripcion, kardex_estado.descripcion, kardex.idusuario, kardex.estado FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_estado ON (kardex.estado = kardex_estado.idkardex_estado) INNER JOIN atencion ON (atencion.idatencion = kardex.idatencion)";
    $FormatoGrilla[0] = $Sql;       //Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'kardex.correlativo', '2'=>'sql_fecha(kardex.fecha, dd/mm/yyyy)', '3'=>'atencion.idatencion', '4'=>'servicio.descripcion', '5'=>'kardex_estado.descripcion', '6'=>'kardex.idusuario');  //Campos por los cuales se hará la búsqueda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Ticket', 'T3'=>'Nº Kardex', 'T4'=>'Fecha', 'T5'=>'Servicio', 'T6'=>'Estado', 'T7'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center');                        //Alineación por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 900;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY atencion.correlativo DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1',                             //Botones de Acciones
                                'NB'=>'5',                   //Número de Botones a agregar
                                'BtnId1'=>'BtnModificar',            //Nombre del Botón
                                'BtnI1'=>'modificar.png',            //Imagen a mostrar
                                'Btn1'=>'Editar', 				//Titulo del Botón
                                'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                                'BtnCI1'=>'8', 					//Item a Comparar
                                'BtnCV1'=>'3',					//Valor de comparación
                                'BtnCC1'=>'<',					//Condicion
                                'BtnId2'=>'BtnEliminar', 
                                'BtnI2'=>'word2.png', 
                                'Btn2'=>'Ver Documento', 
                                'BtnF2'=>'onclick="Imprimir(this.id);"', 
                                'BtnCI2'=>'8', 
                                'BtnCV2'=>'2',
                                'BtnCC2'=>'==',
                                'BtnId3'=>'BtnVer', 	//Nombre del Boton
                                'BtnI3'=>'view_detail.png', 	//Imagen a mostrar
                                'Btn3'=>'Ver', 			//Titulo del Botón
                                'BtnF3'=>'onclick="Mostrar(this.id, 5);"',	//Eventos del Botón
                                'BtnCI3'=>'', 	//Item a Comparar
                                'BtnCV3'=>'',
                                'BtnCC3'=>'==',
                                'BtnId4'=>'BtnPDT', 	//Nombre del Boton
                                'BtnI4'=>'disk.png', 	//Imagen a mostrar
                                'Btn4'=>'PDT Notario', 			//Titulo del Botón
                                'BtnF4'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Botón
                                'BtnCI4'=>'8', 	//Item a Comparar
                                'BtnCV4'=>'1',
                                'BtnCC4'=>'==',
                                'BtnId5'=>'BtnArchivo', 	//Nombre del Boton
                                'BtnI5'=>'word.png', 	//Imagen a mostrar
                                'Btn5'=>'Abrir Plantilla', 			//Titulo del Botón
                                'BtnF5'=>'onclick="ImprimirP(this.id);"',	//Eventos del Botón
                                'BtnCI5'=>'8', 	//Item a Comparar
                                'BtnCV5'=>'1',
                                'BtnCC5'=>'==');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    $Previo = "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
    $Previo .= "<tr><td width='70' style='font-size:14px'>&nbsp;A&ntilde;o : </td><td><select name='Anio' id='Anio' class='select' style='font-size:12px' onchange='Buscar(0);' >";
    $SelectA = "SELECT DISTINCT anio FROM kardex WHERE idnotaria='".$_SESSION['notaria']."' ORDER BY anio DESC";
    $ConsultaA = $Conn->Query($SelectA);
    while($rowA=$Conn->FetchArray($ConsultaA)){
        if($rowA[0]==$_SESSION['Anio']){
            $Select="selected";
        }else{
            $Select="";
        }
        $Previo .= "<option value='".$rowA[0]."' $Select>".$rowA[0]."</option>";
    }
    $Previo .= "<option value=''>Todos</option>";
    $Previo .= "</select></td></tr>";
    $Previo .= "</table>";		 
    Cabecera($Previo, $FormatoGrilla[7]);
?>
<script type="text/javascript" src="../../js/required.js"></script>
<script type="text/javascript">
    var Foco = 'Kardex',
        tipp = '';
    $(document).ready(function()
    {
        $("#Modificar").dialog({
                autoOpen: false,                
                resizable:false,
                title: "Modificar Registro",
                width: 900,
                height: 560,
                buttons: {
                    "Actualizar": function() {
                        Op = 1;
                        if (Validar()){
                            $("#ConfirmaGuardar").dialog("open");
                        }
                    },
                    Cancelar: function() {
                        $("#DivModificar").html('');
                        $(this).dialog("close");
                    }
                }	   
        });
        $("#Ver").dialog({
                autoOpen: false,                
                resizable:false,
                title: "Ver Registro",
                width: 750,
                height: 550,
                buttons: {
                    Salir: function() {
                        $("#DivVer").html('');
                        $(this).dialog("close");
                    }
                }	   
        });
        $("#PDT").dialog({
                autoOpen: false,
                modal: true,
                resizable:false,
                title: "Ver PDT",
                width: 750,
                height: 550,
                buttons: {
                    Salir: function() {
                        $("#DivPDT").html('');
                        $(this).dialog("close");
                    }
                }	   
        });
        $("#Imprimir").dialog({
                autoOpen: false,
                modal: true,
                resizable:false,
                title: "Imprimir Kardex",
                width: 300,
                height: 150,
                buttons: {
                    "Imprimir": function(){
                        Imprimir2($("#RutaArchivo").val());
                    },
                    Salir: function() {
                        $("#DivImprimir").html('');
                        $(this).dialog("close");
                    }
                }	   
        });
});
function Validar()
{

        if ($('#Id').val().substring(0, 1)=='K' || $('#Id').val().substring(0, 1)=='N' || $('#Id').val().substring(0, 1)=='V'){
            if ($('#idoportunidad_pago').val()==''){
                alert('Seleccion la Oportunidad de pago');
                $('#idoportunidad_pago').focus();
                return false;
            }
            if ($('#NroEscritura').val()==''){
                alert('Ingrese el Nº de Escritura!');
                $('#NroEscritura').focus();
                return false;
            }
            if ($('#FojaInicio').val()==''){
                alert('Ingrese la Foja de Inicio!');
                $('#FojaInicio').focus();
                return false;
            }
            if ($('#FojaFin').val()==''){
                alert('Ingrese la Foja Final!');
                $('#FojaFin').focus();
                return false;
            }
            if ($('#SerieInicio').val()==''){
                alert('Ingrese la Serie de Inicio!');
                $('#SerieInicio').focus();
                return false;
            }
            if ($('#SerieFin').val()==''){
                alert('Ingrese la Serie Final!');
                $('#SerieFin').focus();
                return false;
            }
        }
        if ($('#Id').val().substring(0, 1)=='K' || $('#Id').val().substring(0, 1)=='N'){
                if ($('#NroMinuta').val()==''){
                    alert('Ingrese el Nº de Minuta!');
                    $('#NroMinuta').focus();
                    return false;
                }
        }
        if ($('#Id').val().substring(0, 1)=='V'){
            if ($('#Placa').val()==''){
                alert('Ingrese la Placa!');
                $('#Placa').focus();
                return false;
            }
        }
        return true;
}
function Guardar(Op){
    if (Validar())
    {
        var p = json_encode(participantes);
        p = p.replace("&","%26");
        $.ajax({
            url:'guardar.php?Op=' + Op,
            type:'POST',
            async:true,
            data:$('#form1').serialize() + '&0form1_estado=1&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio='+$("#0form1_anio").val()+'&participantes='+p,
            success:function(data){
                $("#Mensajes").html(data);					
                $("#DivNuevo").html('');
                $("#DivModificar").html('');
                $("#DivEliminacion").html('');
                $("#DivRestaurar").html('');
//                if($("#Nuevo").dialog("isOpen")){$("#Nuevo").dialog("close");}
                if($("#Modificar").dialog("isOpen")){$("#Modificar").dialog("close");}
                if($("#ConfirmaEliminacion").dialog("isOpen")){$("#ConfirmaEliminacion").dialog("close");}
                if($("#ConfirmaRestauracion").dialog("isOpen"))$("#ConfirmaRestauracion").dialog("close");
                Buscar(Op);
            }
        })
    }
}
function saveCliente()
{
    var tc = $("#idcliente_tipo").val();
    bval = true;
    bval = bval && $( "#DniRuc" ).required();
    bval = bval && $("#RazonNombre2").required();
    if(tc==1)
    {
        bval = bval && $("#ap_paterno").required();
        bval = bval && $("#ap_materno").required();    
    }    
    if($("#IdDistrito").val()=="000101"&&bval==true){ alert("Complete los datos del ubigeo"); $("#IdDistrito").focus(); return 0; }
    if(tc==2)
    {
        bval = bval && $("#asiento").required();
        bval = bval && $("#partida").required();
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
                $("#Participante"+tipp).val(r[4]);
                $("#IdParticipante"+tipp).val(r[6]);
                $("#DocParticipante"+tipp).val(r[3]);      
                $("#Documento"+tipp).val(r[7]);
            }
        },'json');
    }
    
}
function ImprimirP(Id){
    $("#Imprimir").dialog("open");
    $("#DivImprimir").html("<center><img src='../../imagenes/avance.gif' width='20' /></center>");
    $.ajax({
        url:'generaword.php',
        type:'POST',
        async:true,
        data:'IdKardex=' + Id,
        success:function(data){
               $("#DivImprimir").html(data);
        }
    });
}	
function ImprimirP2(Url){
    var ventana=window.open(Url,'Generacion', 'width=600, height=350, resizable=yes, scrollbars=yes');ventana.focus();
}	
function Imprimir(Id){
    Imprimir2('imprimir.php?IdKardex=' + Id);
}	
function Imprimir2(Url){
    var ventana=window.open(Url,'Generacion', 'width=600, height=350, resizable=yes, scrollbars=yes');ventana.focus();
}
</script>
<script>
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
function ValidarEnter(evt, Op)
{
    Buscar(Op);
}
</script>
<script>
    Buscar(<?php echo $Op;?>);
    $('#Valor').focus();
    $('#BtnNuevo').css("display", "none");
</script>
<?php
    Pie();
    CuerpoInferior();
?>
<div id="NuevoParticipante" title="Mantenimiento" style="display:none;">
    <table width="100%">
        <tr>
            <td><div id="DivNuevoParticipante" style="width:100%"></div></td>
        </tr>
    </table>
</div>
<div id="ConfirmaGuardarParticipante" title="Confirmaci&oacute;n de Operaci&oacute;n" style="display:none;z-index:99">
    <table width="100%">
        <tr>
            <td style="font-size:10px"><div id="DivGuardarParticipante" style="width:100%">&nbsp;</div></td>
        </tr>
        <tr>
            <td><span class="ui-icon ui-icon-help" style="float:left; margin:0 7px 20px 0;"></span>&iquest;Desea Confirmar la Operación?</td>
        </tr>
    </table>
</div>
<?php
}
else{
        header("Location:http://".$_SERVER['HTTP_HOST']."/seguridad/login.php?sesion=1");
    }
?>