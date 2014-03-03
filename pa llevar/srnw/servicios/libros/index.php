<?php
if(!session_id()){ session_start(); }
if ( strlen ($_SESSION['id_user'])>0 ) {
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    $TituloVentana = "Impresi&oacute;n de Libros";
    $Criterio = "libro";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT libro.idlibro, atencion.idatencion, libro.correlativo, sql_fecha(libro.fecha, dd/mm/yyyy), libro.razonsocial, libro_estado.descripcion, libro.idusuario, libro.estado FROM libro_estado INNER JOIN libro ON (libro_estado.idlibro_estado = libro.estado) INNER JOIN atencion ON (atencion.idatencion= libro.idatencion)";
     
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'atencion.idatencion', '2'=>'sql_fecha(libro.fecha, dd/mm/yyyy)', '3'=>'libro.correlativo', '4'=>'libro.razonsocial', '5'=>'libro_estado.descripcion', '6'=>'libro.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Ticket', 'T3'=>'Nº Libro', 'T4'=>'Fecha', 'T5'=>'Raz&oacute;n Social', 'T6'=>'Estado', 'T7'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A6'=>'center', 'A7'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W6'=>'80', 'W7'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 900;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY libro.idlibro DESC ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 						//Botones de Acciones
                              'NB'=>'3',						//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 		//Nombre del Boton
                              'BtnI1'=>'modificar.png', 		//Imagen a mostrar
                              'Btn1'=>'Editar', 				//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'8', 					//Item a Comparar
                              'BtnCV1'=>'2',					//Valor de comparación
                              'BtnCC1'=>'<',					//Condición
                              'BtnId2'=>'BtnImprimir', 
                              'BtnI2'=>'imprimir.png', 
                              'Btn2'=>'Imprimir', 
                              'BtnF2'=>'onclick="Imprimir(this.id);"', 
                              'BtnCI2'=>'8', 
                              'BtnCV2'=>'1',
                              'BtnCC2'=>'==',
                              'BtnId3'=>'BtnVer', 	//Nombre del Botón
                              'BtnI3'=>'view_detail.png', 	//Imagen a mostrar
                              'Btn3'=>'Ver', 			//Titulo del Botón
                              'BtnF3'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Botón
                              'BtnCI3'=>'', 	//Item a Comparar
                              'BtnCV3'=>'',
                              'BtnCC3'=>'==');						  
    $_SESSION['Formato'] = $FormatoGrilla;
    $Previo = "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
    $Previo .= "<tr><td width='70' style='font-size:14px'>&nbsp;A&ntilde;o : </td><td><select name='Anio' id='Anio' class='select' style='font-size:12px' onchange='Buscar(0);' >";
    $SelectA 	= "SELECT DISTINCT anio FROM kardex ORDER BY anio DESC";
    
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
<script>
	var Foco = 'Cliente';
	$(document).ready(function(){			
		$("#Modificar").dialog({
                    autoOpen: false,                    
                    resizable:false,
                    title: "Modificar Registro",
                    width: 600,
                    height: 470,
                    buttons: {
                        "Actualizar": function() {
                            Op = 1;
                            if (Validar()){
                                //$("#ConfirmaGuardar").dialog("open");
                                if(confirm("Desea confirmar la operacion?"))
                                {
                                    Guardar(Op);
                                }
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
			width: 600,
			height: 470,
			buttons: {
                            Salir: function() {
                                $("#DivVer").html('');
                                $(this).dialog("close");
                            }
			}	   
		});
		$("#Imprimir").dialog({
                    autoOpen: false,                    
                    resizable:false,
                    title: "Imprimir Libro",
                    width: 300,
                    height: 150,
                    buttons: {
                        "Imprimir": function(){
                            Imprimir2($("#RutaArchivo").val());
                            $(this).dialog("close");
                        },
                        Salir: function() {
                            $("#DivImprimir").html('');
                            $(this).dialog("close");
                        }
                    }	   
		});
	});
	function Validar(){
            if ($('#RazonSocial').val()==''){
                    alert('Ingrese la Raz&oacute;n Social!');
                    $('#RazonSocial').focus();
                    return false;
            }
            //if ($('#Ruc').val()=='' || $('#Ruc').val().length!=11){
            //        alert('Ingrese en RUC correctamente!');
            //        $('#Ruc').focus();
            //        return false;
            //}
            if ($('#Direccion').val()==''){
                    alert('Ingrese la Direcci&oacute;n!');
                    $('#Direccion').focus();
                    return false;
            }
            if ($('#Telefono').val()==''){
                    alert('Ingrese el Telefono!');
                    $('#Telefono').focus();
                    return false;
            }
            if ($('#Numero').val()==''){
                    alert('Ingrese el N&uacute;mero de Libro!');
                    $('#Numero').focus();
                    return false;
            }
            if ($('#FolioInicial').val()==''){
                    alert('Ingrese el Folio Inicial!');
                    $('#FolioInicial').focus();
                    return false;
            }
            if ($('#FolioFinal').val()==''){
                    alert('Ingrese el Folio Final!');
                    $('#FolioFinal').focus();
                    return false;
            }
            if ($('#Solicitante').val()==''){
                    alert('Ingrese el Solicitante!');
                    $('#Solicitante').focus();
                    return false;
            }
            if ($('#Dni').val()==''){
                    alert('Ingrese el DNI del Solicitante!');
                    $('#Dni').focus();
                    return false;
            }
            return true;
	}	
	function Guardar(Op){
            $.ajax({
                url:'guardar.php?Op=' + Op,
                type:'POST',
                async:true,
                data:$('#form1').serialize() + '&0form1_estado=1&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>',
                success:function(data){
                    $("#Mensajes").html(data);				
                    $("#DivNuevo").html('');
                    $("#DivModificar").html('');
                    $("#DivEliminacion").html('');
                    $("#DivRestaurar").html('');
                    $("#Nuevo").dialog("close");
                    $("#Modificar").dialog("close");
                    $("#ConfirmaEliminacion").dialog("close");
                    $("#ConfirmaRestauracion").dialog("close");
                    Buscar(Op);
                }
            });
	}	
	function Imprimir(Id){
            $("#Imprimir").dialog("open");
            $("#DivImprimir").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");	
            $.ajax({
                url:'generaword.php',
                type:'POST',
                async:true,
                data:'IdLibro=' + Id,
                success:function(data){
                   $("#DivImprimir").html(data);
                }
            });
	}	
	function Imprimir2(Url){
            var ventana=window.open(Url,'Generacion', 'width=300, height=150, resizable=yes, scrollbars=yes');ventana.focus();
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
            });
            $('#Valor').focus();
	}
	function ValidarEnter(evt, Op){
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
}else{
    header("Location:http://".$_SERVER['HTTP_HOST']."/seguridad/login.php?sesion=1");
}
?>
