<?php	
    if(!session_id()){ session_start(); }	
	include('../../config.php');
	include('../../config_seguridad.php');	
	include('../../libs/clasemantem.php');
        $objMantenimiento = new dbMantimiento($Conn->GetConexion());
	$Op = $_POST["Op"];
	$Id = isset($_POST["Id"])?$_POST["Id"]:'';
	$Enabled	= "";
	$Enabled2	= "";
	$Guardar	= "";
	$Usuario        = $_SESSION["Usuario"];
	$Anio           = $_SESSION["Anio"];
	$Fecha          = date('d/m/'.$Anio);
	$Estado         = "<label style='color:#FF6600'>PENDIENTE</label>";	
	$Guardar        = "Op=$Op";	
	if($Op==2 || $Op==3 || $Op==4)
    {
            $Enabled = "readonly";
	}

        $Enabled2       = "readonly";
        if($Id!=''){
            $Select 	= "SELECT * FROM atencion WHERE idatencion = ".$Id." AND idnotaria='".$_SESSION['notaria']."'";
          
            $Consulta 	= $Conn->Query($Select);
            $row 	= $Conn->FetchArray($Consulta);		
            $Usuario 	= $_SESSION["Usuario"];
            $Fecha	= $Conn->DecFecha($row[2]);
            $idC        = $row[9];
	    $Select 	= "SELECT dni_ruc,nombres||' '||coalesce(ape_paterno,'')||' '||coalesce(ap_materno,'') FROM cliente WHERE idcliente='".$row[9]."' ";
            $Consulta 	= $Conn->Query($Select);
            $rowC 	= $Conn->FetchArray($Consulta);	
            if(strpos($rowC[1],"!")==true)
            {
                $Nombre=explode("!", $rowC[1]);
                $row[9] = trim($Nombre[1])." ".trim($Nombre[0]);
            }
            else
            {
                $row[9] = $rowC[1];
            }
            $dni_ruc = $rowC[0];
            if ($row[3]==1){
                    $Estado = "<label style='color:#003366'>ATENDIDO</label>";
            }
            if ($row[3]==2){
                    $Estado = "<label style='color:#FF00000'>ANULADO</label>";
            }
            $Anio = $row[7];
            $Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[4]."'";
            $ConsultaS = $ConnS->Query($Sql);
            $rowS    = $ConnS->FetchArray($ConsultaS);
            $Usuario = $rowS[0];
	}else{
            $Consulta 	= $Conn->Query("SELECT max(idatencion) FROM atencion");
            $row = $Conn->FetchArray($Consulta);  
        }
        $numeroX = pg_num_rows($Conn->Query("SELECT * FROM reinicio WHERE idnotaria='".$_SESSION['notaria']."'"));
        if(isset($_SESSION['Anio']) && $numeroX>1){
            $AnioR      = $objMantenimiento->reinicio($_SESSION['notaria'],$_SESSION['Anio']);
            $Consulta 	= $Conn->Query("SELECT max(correlativo) FROM atencion WHERE idnotaria='".$_SESSION['notaria']."' AND anio>='$AnioR'");
            $rowCantidad = $Conn->FetchArray($Consulta);  
	}else{
            $Consulta 	 = $Conn->Query("SELECT max(correlativo) FROM atencion WHERE idnotaria='".$_SESSION['notaria']."'");
            $rowCantidad = $Conn->FetchArray($Consulta);
        }
$ArrayP = array(NULL);
?>

<script stype="text/javascript">
	var CantidadSgt = 'Precio';
  $(document).ready(function(){
    $("#Folios").change(function(){
            calculaTotalLibros();
     });
    $('.img-quit').live('click',function(){
         $(this).parent().parent().remove();         
         NumerarC();
    })
    $("#newCliente").click(function()
    {
        $("#dnewCliente").load('../../parametros/cliente/MantenimientoA.php',function(){            
            $.getScript("../../parametros/cliente/script.js",function(){
                $("#dnewCliente").dialog("open");   
                $("#dni_ruc").focus();                
            });            
        });
        
    });


$('#existe').click(function() 
 {
  if($(this).is(':checked'))
   {
    $("#NroKardex").attr('readonly',false);
    $("#historial").css("display","inline-block");
   } else {$("#NroKardex").attr('readonly',true);
           $("#historial").css("display","none");
          }
 });


    $("#dnewCliente").dialog({
        autoOpen: false,                    
                    resizable:false,
                    title: "Nuevo Cliente",
                    height:550,
                    width: 750,                       
                    buttons: {
                        "Grabar": function() {                            
                            saveCliente();                           
                        },
                        "Cancelar": function() {
                            
                            $(this).dialog("close");
                        }
                    }
    })
    $("#NumAtencion").dialog({
                    autoOpen: false,
                    modal:true,
                    resizable:false,
                    title: "Nº de Ticket Generado",
                    height:300,
                    width: 450,
                    show: "scale",
                    hide: "scale",
                    buttons: {
                        "Generar Comprobante": function() {
                            $("#DivGuardar").html('');
                            $(this).dialog("close");
                            GenerarComprobante(<?php echo $Op;?>);
                        },
                        "Aceptar": function() {
                            $("#DivGuardar").html('');
                            $(this).dialog("close");
                        }
                    }
    });   
    function formatItemC(row){
       return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[0] + "</td></tr></table>";
    }
    function formatItemS(row){
                    var Tipo = "";
                    if (row[6]!=""){
                            Tipo = 'Tipo de Kardex = ';
                    }
                    return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[1] + "</td><td  width='50' align='right' style='padding-right:10px'>" + row[2] + "</td></tr></table>";
    }
    function formatResult(row){
                    return row[0];
    }
     
    $( "#Servicio" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/servicio.php',
                focus: function( event, ui ) 
                {                    
                    //$("#Cliente").val(ui.item.nombres);            
                    return false;
                },
                select: function( event, ui ) 
                {
                      $("#IdServicio").val(ui.item.idservicio);
                      $("#Servicio").val(ui.item.descripcion);
                      $("#lblLegal").html('Nº Legal');
                      if (ui.item.legal=='1'){$("#lblLegal").html('Servicio Legal');}
                      $("#Legal").val(ui.item.legal);     
                      $('#Cantidad').attr("readonly", false);
                      $("#lblEspecial").html('');     
                      $("#TrKardex").css("display", "none");
                      if (ui.item.especial=='1')
                      {
                        $("#lblEspecial").html('Numeraci&oacute;n Especial (' + ui.item.abreviatura + ')');
                        $('#Cantidad').val(1);
                        // $('#Cantidad').attr("readonly", true);        
                        $("#TrKardex").css("display", "");        //Activa el tr donde se seleccionaran los Kardex que estan libres.
                        $('#lblKardex').html('Kardex Nº :');        
                       


                        //TraerKardex(ui.item.abreviatura, ui.item.idservicio);
                       


                        $('#Precio').focus();
                      }
                      $("#Especial").val(ui.item.especial);      
                      CantidadSgt = 'Precio';
                      $("#TrFolios").css("display", "none");
                      if (ui.item.folios=='1')
                      {
                        $("#TrFolios").css("display", "");
                        CantidadSgt = 'Folios';       
                        $("#TrKardex").css("display", "");        //Activa el tr donde se seleccionaran los Libros que estan libres.
                        $('#lblKardex').html('Libro Nº :');       
                       // TraerLibros();
                      }
                      $("#Folio").val(ui.item.folios);     
                      $("#Precio").val(ui.item.precio);
                      $('#Cantidad').focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a style='font-size:11px'>"+ item.descripcion+" - <b>"+item.precio+"</b></a>")
                    .appendTo( ul );
            };

    $( "#Cliente" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cliente.php',
                focus: function( event, ui ) 
                {                    
                    //$("#Cliente").val(ui.item.nombres);            
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#Cliente").val(ui.item.nombres);
                     $("#idcliente").val(ui.item.idcliente);
                     $("#Direccion").val(ui.item.direccion);       
                     $("#dni_ruc").val(ui.item.dni_ruc);              
                     $("#Servicio").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            };


            $( "#dni_ruc" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/clienteD.php',
                focus: function( event, ui ) 
                {                    
                    //$("#Cliente").val(ui.item.nombres);            
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#Cliente").val(ui.item.nombres);
                     $("#idcliente").val(ui.item.idcliente);
                     $("#dni_ruc").val(ui.item.dni_ruc);
                     $("#Direccion").val(ui.item.direccion);                     
                     $("#Servicio").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc + " - "+item.nombres+"</a>" )
                    .appendTo( ul );
            };

            $( "#NroKardex" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/kardex.php',
                focus: function( event, ui ) 
                {
                    //$( "#DocRepresentante" ).val( ui.item.dni_ruc );      
                    $("#NroKardex").val(ui.item.correlativo);            
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#NroKardex").val(ui.item.correlativo);
                     //$("#cargo").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.correlativo + "</a>" )
                    .appendTo( ul );
            };   
        
  }); 
  function AgregaD(evt){
    var keyPressed = (evt.which) ? evt.which : event.keyCode
    if (keyPressed==13){
      AgregaServicio();
      event.returnValue = false;
    }
  }
  function AgregaServicio(){
    var IdServicio  = $("#IdServicio").val();
    var Descripcion = $("#Servicio").val();
    var Legal = $("#Legal").val();
    var Check = "";
    if (Legal==1) { Check = 'checked="checked"'; }
    var Folios  = $("#Folios").val();
    var Folio = $("#Folio").val();
    var FolioT  = "";
    if (Folio==0){ 
      FolioT = 'readonly';
      Folios = 0;
    }else{
                    if (Folios<=0){
                        alert("Ingrese el Nº de Folios");
                        $("#Folios").focus();
                        return;
                    }
    }
    var Especial  = $("#Especial").val();
    var Cantidad  = $("#Cantidad").val();
    var CantidadT = "";
    if (Especial==1){ 
                    CantidadT = 'readonly';
    }else{
                    if (Cantidad<=0){
                        alert("Ingrese la Cantidad Correcta");
                        $("#Cantidad").focus();
                        return;
                    }
    }   
    var Kardex  = $("#NroKardex").val();
                if(Kardex==''){ Kardex='';}
    var Precio  = $("#Precio").val();   
    if (Cantidad != ''){
    
    
     if((IdServicio==118 || IdServicio==245 || IdServicio==197) || Especial==1)
     {
        //alert(Cantidad);
         for(x=1;x<=$("#Cantidad").val();x++)
        {
                    Cantidad=1;

                    Precio    = (parseFloat(Precio)).toFixed(2);
                    var Importe = parseFloat(Precio) * Cantidad;
                    Importe   = (parseFloat(Importe)).toFixed(2);     
                    nDest = nDest + 1;
                    nDestC = nDestC + 1;
                    
            var html = "<tr>";
            html += "<td align='center'><input type='hidden' name='0formD"  + nDestC + "_idnotaria' value='<?php echo $_SESSION['notaria'];?>' /><input type='hidden' name='0formD"  + nDestC + "_idatencion' value='<?php echo $Id;?>' /><input type='hidden' name='0formD"  + nDestC + "_anio' value='<?php echo $Anio;?>' /><input type='hidden' name='0formD" + nDestC + "_item' id='ItemD" + nDestC + "' value='" + nDestC + "' /><label name='Item"  + nDestC + "' id='Item" + nDestC + "' >" + nDestC + "</label></td>";
            html += "<td><input name='0formD" + nDestC + "_idservicio' id='IdServicioD" + nDestC + "' type='hidden' value='" + IdServicio + "' /><input name='Servicio" + nDestC + "' id='ServicioD" + nDestC + "' type='hidden' value='" + Descripcion + "' />" + Descripcion+"</td>";
            html += "<td><input name='LegalD" + nDestC + "' id='LegalD" + nDestC + "'  type='hidden' value='" + Legal + "' /><input type='checkbox' name='Legal"  + nDestC + "' id='Legal"  + nDestC + "' " + Check + " disabled='disabled'/></td>";
            html += "<td><input name='0formD" + nDestC + "_folios' id='FoliosD" + nDestC + "' type='text' value='" + Folios + "' style='width:50px; text-align:center' onkeypress='return permite(event, \"num\");' " + FolioT + "/></td>";
            html += "<td><input name='0formD" + nDestC + "_cantidad' id='CantidadD" + nDestC + "' type='text' value='" + Cantidad + "' style='width:60px; text-align:center' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");' " + CantidadT + "/></td>";
            html += "<td align='right'><input name='0formD" + nDestC + "_monto' id='MontoD" + nDestC + "' type='text' value='" + Precio + "' style='width:60px; text-align:right;' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");'/></td>";
            html += "<td align='right'><label name='TotalD" + nDestC + "' id='TotalD" + nDestC + "' >" + Importe + "</label></td>";
            html += "<td><input type='hidden' name='0formD" + nDestC + "_correlativo' id='CorrelativoD" + nDestC + "' value='" + Kardex + "' />" + Kardex+"</td>";
            html += "<td><img class='img-quit' src='../../imagenes/iconos/eliminar.png' width='16' height='16'  style='cursor:pointer'/></td>";              
            html += "</tr>";

            var miTabla = document.getElementById('ListaMenu2').insertRow(nDest);
            $("#ListaMenu2 tbody").append(html);

           
             $('#ConServicios').val(nDestC);     
           
            NumerarC();                    
           
                   

      }//fin for

      $('#Cantidad').val('1');
                    $('#IdProducto').val('');
                    $('#CodProducto').val('');
                    $('#Descripcion').val('');
                    $('#Precio').val('0.00');
                    $('#Servicio').val('');
                    $('#IdServicio').val('');
                    $('#Cantidad').val('');
                    $('#Precio').val('');
                    $('#Servicio').focus();
                    $('#NroKardex').val('');

     }//fin if
     else{//alert("no cartas");

       Precio    = (parseFloat(Precio)).toFixed(2);
                    var Importe = parseFloat(Precio) * Cantidad;
                    Importe   = (parseFloat(Importe)).toFixed(2);     
                    nDest = nDest + 1;
                    nDestC = nDestC + 1;
                    var html = "<tr>";
                        html += "<td align='center'><input type='hidden' name='0formD"  + nDestC + "_idnotaria' value='<?php echo $_SESSION['notaria'];?>' /><input type='hidden' name='0formD"  + nDestC + "_idatencion' value='<?php echo $Id;?>' /><input type='hidden' name='0formD"  + nDestC + "_anio' value='<?php echo $Anio;?>' /><input type='hidden' name='0formD" + nDestC + "_item' id='ItemD" + nDestC + "' value='" + nDestC + "' /><label name='Item"  + nDestC + "' id='Item" + nDestC + "' >" + nDestC + "</label></td>";
                        html += "<td><input name='0formD" + nDestC + "_idservicio' id='IdServicioD" + nDestC + "' type='hidden' value='" + IdServicio + "' /><input name='Servicio" + nDestC + "' id='ServicioD" + nDestC + "' type='hidden' value='" + Descripcion + "' />" + Descripcion+"</td>";
                        html += "<td><input name='LegalD" + nDestC + "' id='LegalD" + nDestC + "'  type='hidden' value='" + Legal + "' /><input type='checkbox' name='Legal"  + nDestC + "' id='Legal"  + nDestC + "' " + Check + " disabled='disabled'/></td>";
                        html += "<td><input name='0formD" + nDestC + "_folios' id='FoliosD" + nDestC + "' type='text' value='" + Folios + "' style='width:50px; text-align:center' onkeypress='return permite(event, \"num\");' " + FolioT + "/></td>";
                        html += "<td><input name='0formD" + nDestC + "_cantidad' id='CantidadD" + nDestC + "' type='text' value='" + Cantidad + "' style='width:60px; text-align:center' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");' " + CantidadT + "/></td>";
                        html += "<td align='right'><input name='0formD" + nDestC + "_monto' id='MontoD" + nDestC + "' type='text' value='" + Precio + "' style='width:60px; text-align:right;' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");'/></td>";
                        html += "<td align='right'><label name='TotalD" + nDestC + "' id='TotalD" + nDestC + "' >" + Importe + "</label></td>";
                        html += "<td><input type='hidden' name='0formD" + nDestC + "_correlativo' id='CorrelativoD" + nDestC + "' value='" + Kardex + "' />" + Kardex+"</td>";
                        html += "<td><img class='img-quit' src='../../imagenes/iconos/eliminar.png' width='16' height='16'  style='cursor:pointer'/></td>";              
                    html += "</tr>";

                    var miTabla = document.getElementById('ListaMenu2').insertRow(nDest);
                    $("#ListaMenu2 tbody").append(html);

                    $('#ConServicios').val(nDestC);   
                    NumerarC();     
                    $('#Cantidad').val('1');
                    $('#IdProducto').val('');
                    $('#CodProducto').val('');
                    $('#Descripcion').val('');
                    $('#Precio').val('0.00');
                    $('#Servicio').val('');
                    $('#IdServicio').val('');
                    $('#Cantidad').val('');
                    $('#Precio').val('');
                    $('#Servicio').focus();
                    $('#NroKardex').val('');
                   // $('#TrKardex').css('display','none');

     }//fin else
    }//fin if cantidad
  }
  function QuitaServicio(x)
  {  
            
            $("#ListaMenu2 tbody").find('tr:eq('+x+')').remove();
            NumerarC();
            // var current = window.event.srcElement;   
            // while ( (current = current.parentElement) && current.tagName !="TR")
            // {
            //     current.parentElement.removeChild(current);
            //     nDest = nDest - 1;
            //     NumerarC();
            // }
  }
  function CalcularTotalItem(evt, x){
            var keyPressed = (evt.which) ? evt.which : event.keyCode
            if (keyPressed==13){
                $('#TotalD' + x).html(($('#CantidadD' + x).val() * parseFloat($('#MontoD' + x).val())).toFixed(2));
                $('#MontoD' + x).val(parseFloat($('#MontoD' + x).val()).toFixed(2));
                NumerarC();
                event.returnValue = false;
            }
  }
  function NumerarC(){
            var contt = 1;
            nTotal = 0;
            for (var i=1;i<=nDestC;i++){
                try{  
                    document.getElementById('Item' + i).innerHTML = contt;
                    $('#0formD' + i + '_item').val(contt);
                    contt = contt + 1;
                    nTotal =  nTotal + parseFloat($('#TotalD' + i).html());
                }catch(err){}
            }
            $('#Total').html(nTotal.toFixed(2));
  }
  function Cancelar(){
            window.location.href='index.php';
  } 
  function ValidarFormEnt(evt){
            var keyPressed  = (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                Guardar(<?php echo $Op;?>);
            }
  } 
  function TraerLibros(){
            $.ajax({
                url:'Libros.php',
                type:'POST',
                async:true,
                data:'Anio=<?php echo $Anio;?>',
                success:function(data){
                    $("#DivKardex").html(data);
                }
            })
  }
  function TraerKardex(Tipo, Id){
            $.ajax({
                url:'Kardex.php',
                type:'POST',
                async:true,
                data:'Anio=<?php echo $Anio;?>&Tipo=' + Tipo + '&IdServicio=' + Id,
                success:function(data){
                        $("#DivKardex").html(data);
                }
            });
  }
  function historial(){
        kardex=$("#NroKardex").val();
        alert(kardex);
            $.ajax({
                url:'historial.php',
                type:'POST',
                async:true,
                data:'kardex='+kardex,
                success:function(data){
                        //$("#historial").html(data);
                        alert(data);
                }
            });
  }
function CalcTotalLibros(evt)
    {
        if (VeriEnter(evt))
        {   
            calculaTotalLibros();
            return false;
        }
    }        
    function calculaTotalLibros()
    {   
        var nf = parseInt($("#Folios").val()),
            r = nf % 100,
            f = 0;
        if(r>0) 
            f = 100-r;                
        nfmx = nf + f;       
        var price = nfmx/100*10;
        $("#Precio").val(price.toFixed(2));
    }
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="98">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="98" class="TituloMant">Nº Atenci&oacute;n :</td>
    <td width="242">
        <input type="hidden" class="inputtext" name="1form1_idatencion" id="Id" value="<?php echo $row[0];?>"/>
        <input type="text" class="inputtext" style="text-align:center; font-size:12px; width:50px" name="Idx" id="Idx" maxlength="2" value="<?php if($Id!=''){ echo $row[0]; }else{ echo ($rowCantidad[0]+1); }?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Cliente');"/>
    </td>
    <td width="160" align="right">
        <table width="160" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
                <td align="right"><?php echo $Estado;?></td>	  
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td width="98" class="TituloMant">Cliente :</td>
    <td colspan="2">
        <input type="text" class="inputtext" style="width:100px" name="dni_ruc" id="dni_ruc" value="<?php echo $dni_ruc; ?>"  /> 
        <input type="text" class="inputtext" style="font-size:12px; width:250px; text-transform:uppercase;" name="Cliente" id="Cliente"  maxlength="100" value="<?php echo $row[9];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/>
        <a href="javascript:" id="newCliente"><img src="<?php echo '../../'.$urlDir;?>imagenes/iconos/nuevo.png"></a>            
    </td>
  </tr>
  <tr>
    <td width="98" class="TituloMant">Direcci&oacute;n :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_direccion" id="Direccion"  maxlength="100" value="<?php echo $row[1];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Servicio');"/></td>
  </tr>
  <tr>
    <td width="98" class="TituloMant">Fecha : </td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha" id="Fecha" value="<?php echo $Fecha;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'Servicio');"/></td>
  </tr>
  <tr>
    <td width="98">&nbsp;</td>
    <td colspan="2"><input type="hidden" name="2form1_anio" value="<?php echo $Anio;?>" /><input type="hidden" name="0form1_idnotaria" id="idnotaria" value="<?php echo $_SESSION['notaria'];?>" /><input type="hidden" name="0form1_idusuario" id="idusuario" value="<?php echo $_SESSION['id_user'];?>" /></td>
  </tr>
</table>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="650" border="0" cellspacing="0" cellpadding="0" id="AgregarElemento" <?php if ($Op==2 || $Op==3 || $Op==4){ echo 'style="display:none"';}?>>
      <tr>
        <td width="72" class="TituloMant">Servicio :</td>
        <td><input type="hidden" id="IdServicio" /><input type="text" class="inputtext" style="width:100%; text-transform:uppercase; font-size:12px" name="Servicio" id="Servicio" value="" onkeypress="CambiarFoco(event, 'Cantidad');"/></td>
        <td width="22" align="center"><img src="../../imagenes/buscar.png" width="20" style="cursor:pointer; display:none" /></td>
        <td style="padding-left:10px; color:#003366"><input type="hidden" name="Legal" id="Legal"/><label id="lblLegal"></label></td>
      </tr>
      <tr>
        <td class="TituloMant">Cantidad :</td>
        <td width="388" colspan="2"><input type="text" class="inputtext" style="text-align:center; width:50px" name="Cantidad" id="Cantidad" value="" onkeypress="CambiarFoco(event, CantidadSgt); return permite(event, 'num');"/></td>
        <td width="182" style="padding-left:10px; color:#003366"><input type="hidden" name="Especial" id="Especial" /><label id="lblEspecial"></label></td>
      </tr>
      <tr id="TrFolios" style="display:none">
        <td class="TituloMant">Folios :</td>
        <td colspan="3"><input type="text" class="inputtext" style="text-align:center; width:50px" name="Folios" id="Folios" value="" onkeypress="CalcTotalLibros(event);CambiarFoco(event, 'Precio'); return permite(event, 'num');"/>
          <input type="hidden" name="Folio" id="Folio" />		</td>
      </tr>
      <tr id="TrKardex" style="display:none">
        <td class="TituloMant"><label id="lblKardex">&nbsp;</label></td>
        <td colspan="3">
          <div id="DivKardex">
             <div id="kexiste">
               <input type="text" name="NroKardex" id="NroKardex" style="width:80px;" class="inputtext" readonly>
               <input type="checkbox" name="existe" id="existe"> Existe Kardex
               <a href="" id="historial" style="display:none" onclick="historial()";>Historial</a>
             </div>
          </div>
       </td>
        </tr>
      <tr>
        <td class="TituloMant">Precio : </td>
        <td><input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="Precio" id="Precio" value="" onkeypress="AgregaD(event); return permite(event, 'num');"/></td>
        <td>&nbsp;</td>
        <td align="right">
            <input type="button" name="AddServicio" id="AddServicio" onclick="AgregaServicio();" value="Agregar Servicio" />
            <!-- <label style="cursor:pointer;" onclick="AgregaServicio();">
            <table width="118" border="0" cellspacing="0" cellpadding="0" class="Boton">
                <tr>
                  <td width="20" height="20" align="center" valign="middle"><img src="../../imagenes/iconos/add.png" width="16" height="16" /></td>
                  <td width="98" valign="middle" style="font-size:12px">Agregar Servicio</td>
                </tr>
            </table>
            </label>             -->
        </td>
      </tr>
    </table>
    <input type="hidden" name="0form1_idcliente" id="idcliente" value="<?php if(strlen($idC)>0){ echo $idC; }else{ echo 0; }?>">
    <input type="hidden" name="0form1_correlativo" id="correlativo" value="<?php if($Id!=''){ echo $row[10]; }else{ echo ($rowCantidad[0]+1); }?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
            <table width="700" border="1" cellspacing="1" style="background-color: #FFF;border-color: #000000;" id="ListaMenu2">
			<thead>
                    <tr>
			
                        <th title="Cabecera" width="50" height="20">Item</th>
                        <th title="Cabecera">Servicio</th>
                        <th title="Cabecera" width="40">L</th>
                        <th title="Cabecera" width="50">Folio</th>
                        <th title="Cabecera" width="60">Cantidad</th>
                        <th title="Cabecera" width="70">Precio</th>
                        <th title="Cabecera" width="70">Total</th>
                        <th title="Cabecera" width="70">Correlativo</th>
                        <th title="Cabecera" width="20">&nbsp;</th>
                    </tr>
			</thead>
                    <tbody>
<?php
		$NumRegs = 0;
        $nTotal=0;
		if ($Op!=0){
			$Consulta2 = $Conn->Query("SELECT atencion_detalle.item, atencion_detalle.idservicio, servicio.descripcion, servicio.legal, atencion_detalle.folios, atencion_detalle.cantidad, atencion_detalle.monto, (atencion_detalle.cantidad * atencion_detalle.monto), atencion_detalle.correlativo, servicio.folios FROM atencion_detalle INNER JOIN servicio ON (atencion_detalle.idservicio = servicio.idservicio) INNER JOIN atencion ON (atencion_detalle.idatencion = atencion.idatencion) WHERE atencion.anio = '$Anio' AND atencion.correlativo = '".$row[10]."' AND atencion.idatencion='".$row[0]."' AND atencion.idnotaria='".$_SESSION['notaria']."'");			
			while($row2 = $Conn->FetchArray($Consulta2)){
				$NumRegs = $NumRegs + 1;				
				$Check = "";
				if ($row2[3]==1){
					$Check = 'checked="checked"';
				}				
				$EnabledF = $Enabled;
				if ($row2[9]==0){
					$EnabledF = 'readonly';
				}
				$EnabledC = $Enabled;
				if ($row2[8]!=''){
					$EnabledC = 'readonly';
				}				
				$nTotal = $nTotal + str_replace(',', '', $row2[7]);
?>
				<tr>
                                    <td align="center">
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_idnotaria' value='<?php echo $_SESSION['notaria'];?>' />
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_idatencion' value='<?php echo $Id;?>' />
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_anio' value='<?php echo $Anio;?>' />
                                        <input type="hidden" name="0formD<?php echo $NumRegs;?>_item" id="ItemD<?php echo $NumRegs;?>" value="<?php echo $NumRegs;?>" />
                                        <label id="Item<?php echo $NumRegs;?>"><?php echo $NumRegs;?></label>
                                    </td>
				    <td style="padding-left:5px">
                                        <input name="0formD<?php echo $NumRegs;?>_idservicio" id="IdServicio<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[1];?>" />
                                        <input name="Servicio<?php echo $NumRegs;?>" id="Servicio<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[2];?>" /><?php echo $row2[2];?>
                                    </td>
				    <td align="center"><input type="checkbox" name="LegalD<?php echo $NumRegs;?>" id="LegalD<?php echo $NumRegs;?>" <?php echo $Check;?> disabled="disabled"/></td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_folios" id="FoliosD<?php echo $NumRegs;?>" value="<?php echo $row2[4];?>" style="width:50px; text-align:center" onkeypress="return permite(event, 'num');" <?php echo $EnabledF;?>/></td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_cantidad" id="CantidadD<?php echo $NumRegs;?>" value="<?php echo $row2[5];?>" style="width:60px; text-align:center" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $EnabledC;?>/></td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_monto" id="MontoD<?php echo $NumRegs;?>" value="<?php echo str_replace(',','', $row2[6]);?>" style="width:60px; text-align:right" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $Enabled;?>/></td>
				    <td align="right" style="padding-right:5px"><label id="TotalD<?php echo $NumRegs;?>"><?php echo str_replace(',','', $row2[7]);?></label></td>
				    <td align="center"><input type="hidden" name="0formD<?php echo $NumRegs;?>_correlativo" id="CorrelativoD<?php echo $NumRegs;?>" value="<?php echo $row2[8];?>" /><?php echo $row2[8];?></td>
				    <td align="center"><img class="img-quit" src="../../imagenes/iconos/eliminar.png" width="16" height="16"  style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar Servicio" /></td>
				</tr>
<?php
			}
		}
			echo "<script> var nDest = $NumRegs; var nDestC = $NumRegs; </script>";
?>
			</tbody>
		</table>
			<input type="hidden" name="ConServicios" id="ConServicios" value="<?php echo $NumRegs;?>"/>
		</td>
  </tr>
  <tr>
    <td height="35" align="right" valign="middle">
    <table width="200" border="1" cellspacing="1" cellpadding="0" id="GrillaT">
      <tr>
        <th align="center" scope="row">Monto Total (S/.) : </th>
        <td align="right" width="80" style="padding-right:5px; font-size:14px"><label id="Total">0.00</label></td>
      </tr>
    </table>	
    </td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px">Generado por : <?php echo $Usuario;?></td>
  </tr>
</table>
</form>
</div>
<script>
    NumerarC();
</script>
<div id="dnewCliente"></div>
