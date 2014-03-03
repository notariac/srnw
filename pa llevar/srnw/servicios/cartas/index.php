<?php
if( !session_id() ){ session_start(); }
    if ( strlen ($_SESSION['id_user'])>0 ) {
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");
    $TituloVentana = "Registro de Carta";
    $Criterio = "carta";	
    CuerpoSuperior($TituloVentana);
    $Op = isset($_GET['Op'])?$_GET['Op']:0;
    $FormatoGrilla = array ();
    $Sql = "SELECT carta.idcarta, atencion.idatencion, carta.correlativo, sql_fecha(carta.fecha, dd/mm/yyyy), carta.remitente, carta.destinatario,carta.fechareg, carta_estado.descripcion, carta.idusuario, carta.estado FROM carta_estado INNER JOIN carta ON (carta_estado.idcarta_estado = carta.estado) INNER JOIN atencion ON (atencion.idatencion = carta.idatencion)";
    $FormatoGrilla[0] = $Sql;             											//Sentencia SQL
    $FormatoGrilla[1] = array('1'=>'carta.idcarta', '2'=>'atencion.idatencion', '3'=>'carta.correlativo','4'=>'sql_fecha(carta.fechareg, dd/mm/yyyy)', '5'=>'carta.remitente', '6'=>'carta.destinatario', '7'=>'carta.fechareg' ,'8'=>'carta.estado',  '9'=>'carta.idusuario');  //Campos por los cuales se har� la b�squeda
    $FormatoGrilla[2] = $Op; 	   //Operacion
    $FormatoGrilla[3] = array('T1'=>'Nº', 'T2'=>'Ticket', 'T3'=>'Nº Carta', 'T4'=>'Fecha Carta', 'T5'=>'Remitente', 'T6'=>'Destinatario','T7'=>'Fecha Reg', 'T8'=>'Estado', 'T9'=>'Usuario');   			//T�tulos de la Cabecera
    $FormatoGrilla[4] = array('A1'=>'center', 'A2'=>'center', 'A3'=>'center', 'A4'=>'center', 'A7'=>'center', 'A8'=>'center','A9'=>'center');                        //Alineaci�n por Columna
    $FormatoGrilla[5] = array('W1'=>'50', 'W2'=>'70', 'W3'=>'70', 'W4'=>'70', 'W7'=>'80', 'W8'=>'80','W9'=>'80');     //Ancho de las Columnas
    $FormatoGrilla[6] = array('TP'=>$TAMANO_PAGINA);                  //Registro por Páginas
    $FormatoGrilla[7] = 1000;                                   //Ancho de la Tabla
    $FormatoGrilla[8] = " ORDER BY carta.fechareg desc, carta.idcarta desc ";         //Orden de la Consulta
    $FormatoGrilla[9] = array('Id'=>'1', 						//Botones de Acciones
                              'NB'=>'3',						//Número de Botones a agregar
                              'BtnId1'=>'BtnModificar', 		//Nombre del Botón
                              'BtnI1'=>'modificar.png', 		//Imagen a mostrar
                              'Btn1'=>'Editar', 				//Titulo del Botón
                              'BtnF1'=>'onclick="Mostrar(this.id, 1);"',	//Eventos del Botón
                              'BtnCI1'=>'9', 					//Item a Comparar
                              'BtnCV1'=>'3',					//Valor de comparación
                              'BtnCC1'=>'<',					//Condicion
                              'BtnId2'=>'BtnEliminar', 
                              'BtnI2'=>'imprimir.png', 
                              'Btn2'=>'Imprimir', 
                              'BtnF2'=>'onclick="Imprimir(this.id);"', 
                              'BtnCI2'=>'9', 
                              'BtnCV2'=>'1',
                              'BtnCC2'=>'==',
                              'BtnId3'=>'BtnVer', 	//Nombre del Botón
                              'BtnI3'=>'view_detail.png', 	//Imagen a mostrar
                              'Btn13'=>'Ver', 			//Titulo del Botón
                              'BtnF3'=>'onclick="Mostrar(this.id, 4);"',	//Eventos del Botón
                              'BtnCI3'=>'', 	//Item a Comparar
                              'BtnCV3'=>'',
                              'BtnCC3'=>'==');							  
    $_SESSION['Formato'] = $FormatoGrilla;
    $Previo = "<table width='200' border='0' cellspacing='0' cellpadding='0'>";
    $Previo .= "<tr><td width='70' style='font-size:14px'>&nbsp;A&ntilde;o : </td><td><select name='Anio' id='Anio' class='select' style='font-size:12px' onchange='Buscar(0);' >";
    $SelectA 	= "SELECT DISTINCT anio FROM carta ORDER BY anio DESC";
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
            width: 650,
            height: 600,
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
            width: 650,
            height: 600,
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
            height: 200,
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
function Validar(){
    return true;
}	
function Guardar(Op){
    $.ajax({
        url:'guardar.php?Op=' + Op,
        type:'POST',
        async:true,
        data:$('#form1').serialize() + '&0form1_estado=1&0form1_idusuario=<?php echo $IdUsuario;?>&3form1_fechareg=<?php echo $Fecha;?>&0form1_anio=<?php echo $Anio;?>',
        success:function(data){
            $("#Mensajes").html(data);				
            $("#DivNuevo").html('');
            $("#DivModificar").html('');
            $("#DivEliminacion").html('');
            $("#DivRestaurar").html('');
            if(Op==1)
            {
               $("#Modificar").dialog("close"); 
            }
            // $("#Nuevo").dialog("close");
            // 
            // $("#ConfirmaEliminacion").dialog("close");
            // $("#ConfirmaRestauracion").dialog("close");
            Buscar(Op);
        }
    });
}	
function Imprimir(Id){
    Imprimir2('imprimir.php?IdCarta=' + Id);
}	
function Imprimir2(Url){
    var ventana = window.open(Url,'Generacion', 'width=300, height=150, resizable=yes, scrollbars=yes');
    ventana.focus();
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
