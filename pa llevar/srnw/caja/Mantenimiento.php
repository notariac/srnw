<?php	
if(!session_id()){ session_start(); }	
	include('../config.php');
	include('../config_seguridad.php');	
	$Select 	= "SELECT igv, igv_porcentaje FROM notaria WHERE idnotaria = '".$_SESSION["notaria"]."'";
	$Consulta 	= $Conn->Query($Select);
	$rowC 		= $Conn->FetchArray($Consulta);	
	$Igv	= $rowC[0];
	$IgvP	= $rowC[1];	
	$IdCaja	= 0;
	$Caja	= 'CAJA NO AUTORIZADA';		
	$SelectCj 	= "SELECT idcaja, descripcion FROM caja WHERE idnotaria = '".$_SESSION["notaria"]."' AND idresponsable='".$_SESSION["id_user"]."'";
	$ConsultaCj	= $Conn->Query($SelectCj);
	$rowCj 		= $Conn->FetchArray($ConsultaCj);	
	if (isset($rowCj[0]))
    {
        $IdCaja	= $rowCj[0];
        $Caja	= $rowCj[1];
	}
	$Op = $_POST["Op"];
	$Id = isset($_POST["Id"])?$_POST["Id"]:'';
	$IdAtencion = isset($_POST["IdAtencion"])?$_POST["IdAtencion"]:'';	
    if($IdAtencion!="")
    {
            $Sql = "SELECT idatencion, correlativo FROM atencion WHERE correlativo=".$IdAtencion." AND idnotaria='".$_SESSION["notaria"]."' ";              
            $q = $Conn->Query($Sql);
            $r = $Conn->FetchArray($q);
            $Ida=$r[0];
    }
	$Enabled	= "";
	$Enabled2	= "";
	$Guardar	= "";	
	$Usuario = $_SESSION["Usuario"];	
	$Fecha	= date('d/m/Y');
	$FechaC = date('d/m/Y');
	$Estado = "<label style='color:#FF6600'>PENDIENTE</label>";
	$Anio = $_SESSION["Anio"];
	$Guardar = "Op=$Op";	
	if($Op>1){
            $Enabled = "readonly";
	}	
	$Enabled2 = "readonly";	
	$TipoCambio = 0;
	$Credito = 0;	
	if($Id!=''){
            $Select 	= "SELECT * FROM facturacion WHERE idfacturacion = '$Id'";            
            $Consulta 	= $Conn->Query($Select);
            $row 	= $Conn->FetchArray($Consulta);		
            $Select 	= "SELECT * FROM atencion WHERE idatencion = '$row[4]'";
            $Consulta 	= $Conn->Query($Select);
            $rowX 	= $Conn->FetchArray($Consulta);		
            $Usuario 	= $_SESSION["Usuario"];
            $Fecha		= $Conn->DecFecha($row[10]);
            $FechaC		= $Conn->DecFecha($row[19]);
            if ($row[21]==1){ $Estado = "<label style='color:#003366'>CANCELADO</label>"; }
            if ($row[21]==2){ $Estado = "<label style='color:#FF00000'>ANULADO</label>"; }
            $Anio = $row[24];
            $TipoCambio = $row[13];
            $Credito = $row[15];
            $Igv = $row[16];
            $IgvP = $row[17];		
            $Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[22]."'";
            $ConsultaS = $ConnS->Query($Sql);
            $rowS = $ConnS->FetchArray($ConsultaS);
            $Usuario	= $rowS[0];
	}
$ArrayP = array(NULL);
?>
<script type="text/javascript" src="../js/Funciones.js"></script>
<script>
	$(document).ready(function()
    {
        
		$("#Id").focus();

        $( "#DniRuc" ).autocomplete({
                minLength: 0,
                source: <?php if($IdAtencion==''){?>'../libs/autocompletar/clienteD.php',<?php }else{?>'../../libs/autocompletar/clienteD.php',<?php } ?>                
                select: function( event, ui ) 
                {
                     $("#Nombres").val(ui.item.nombres);
                     $("#IdCliente").val(ui.item.idcliente);
                     $("#DniRuc").val(ui.item.dni_ruc);
                     $("#Direccion").val(ui.item.direccion);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };
        $( "#Nombres" ).autocomplete({
                minLength: 0,
                source: <?php if($IdAtencion==''){ ?>'../libs/autocompletar/cliente.php', <?php }else{?>'../../libs/autocompletar/cliente.php', <?php } ?>
                select: function( event, ui )
                {
                     $("#Nombres").val(ui.item.nombres);
                     $("#IdCliente").val(ui.item.idcliente);
                     $("#DniRuc").val(ui.item.dni_ruc);
                     $("#Direccion").val(ui.item.direccion);                     
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            };

		if (<?php echo $Op;?>==0){
                    $( "#Id" ).autocomplete({
                            minLength: 0,
                            source: <?php if($IdAtencion==''){ ?>'../libs/autocompletar/ticket.php?idnotaria=<?php echo $_SESSION['notaria'] ?>',<?php }else{?>'../../libs/autocompletar/ticket.php?idnotaria=<?php echo $_SESSION['notaria'] ?>',<?php } ?>                            
                            focus: function(event,ui)
                            {
                                $("#Id").val(ui.item.idatencion);      
                                return false;                     
                            },
                            select: function( event, ui )
                            {
                                LimpiarDatos();
                                $("#Id").val(ui.item.idatencion);
                                $("#IdAtencion").val(ui.item.idatencion);
                                validarTicket();
                                $("#Comprobante").focus();
                                return false;                                
                            }
                        }).data( "autocomplete" )._renderItem = function( ul, item ) {
                            
                            return $( "<li></li>" )
                                .data( "item.autocomplete", item )
                                .append( "<a style='text-align:center'>"+ item.idatencion + "</a>" )
                                .appendTo( ul );
                        };
		}
	});	
	$("#CancelacionFecha").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
	});	
	function validarTicketEnt(evt){
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                validarTicket();
            }
	}
	function validarTicket(){
            var Id = $("#Id").val();
            $.ajax({
                <?php if($IdAtencion==''){ ?>url:'detalle.php',<?php }else{ ?>url:'../../caja/detalle.php',<?php } ?>
                type:'POST',
                async:true,
                data:'Anio=<?php echo $Anio;?>&Id=' + Id,
                success:function(data){
                    if(data=="1"){
                        alert('Ya se encuentra facturado');
                        return false;
                    }else{
                        $('#DivDetalleT').html(data);
                        if ($('#ConServiciosA').val()==0){
                            $("#Nombres").val();
                            $("#Direccion").val();
                            alert('Este ticket no se encuentra disponible');
                        }else{
                            if ($('#ConServiciosA').val()==0){
                                $("#Nombres").val();
                                $("#Direccion").val();
                                alert('Este ticket no posee ning&uacute;n servicio agregado');
                            }else{
                                TraerDatos();
                            }
                        }
                        NumerarC();
                    }
                }
            });
	}
	function TraerDatos(){
            var Id = $("#Id").val();
            LimpiarDatos();
            $.ajax({
                <?php if ($IdAtencion==''){?>url:'ValidarTicket.php',<?php }else{?>url:'../../caja/ValidarTicket.php',<?php } ?>
                type:'POST',
                async:true,
                data:'Anio=<?php echo $Anio;?>&Id=' + Id,
                success:function(data){                    
                    var Datos = data.split('|');
                    if(Datos[0]!="00000000")
                    {
                        $("#DniRuc").val(Datos[0]);
                        $("#Nombres").val(Datos[1]);
                        $("#Direccion").val(Datos[2]);
                        $("#IdCliente").val(Datos[4]);
                    }
                    else 
                    {
                        $("#DniRuc").focus();
                    }
                    $("#TotalTicket").val(Datos[3]);
                }
            });
	}
	function LimpiarDatos(){
            $("#IdCliente").val();
            $("#DniRuc").val();
            $("#Nombres").val();
            $("#Direccion").val();
	}	
	function AgregaD(evt){
            var keyPressed = (evt.which) ? evt.which : event.keyCode
            if (keyPressed==13){
                AgregaServicio();
                event.returnValue = false;
            }
	}
	function AgregaServicio(){
            var IdServicio	= $("#IdServicio").val();
            var Descripcion	= $("#Servicio").val();
            var Legal	= $("#Legal").val();
            var Check	= "";
            if (Legal==1) { Check = 'checked="checked"'; }
            var Folios	= $("#Folios").val();
            var Folio	= $("#Folio").val();
            var FolioT	= "";
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
            var Especial	= $("#Especial").val();
            var Cantidad	= $("#Cantidad").val();
            var CantidadT	= "";
            if (Especial==1){ 
                    CantidadT = 'readonly';
            }else{
                if (Cantidad<=0){
                    alert("Ingrese la Cantidad Correcta");
                    $("#Cantidad").focus();
                    return;
                }
            }		
            var Kardex		= $("#NroKardex").val();
            var Precio		= $("#Precio").val();		
            if (Cantidad != ''){
                    Precio		= (parseFloat(Precio)).toFixed(2);
                    var Importe = parseFloat(Precio) * Cantidad;
                    Importe		= (parseFloat(Importe)).toFixed(2);			
                    nDest = nDest + 1;
                    nDestC = nDestC + 1;
                    var miTabla = document.getElementById('ListaMenu2').insertRow(nDest);
                    var celda1	= miTabla.insertCell(0);
                    var celda2	= miTabla.insertCell(1);s
                    var celda3	= miTabla.insertCell(2);
                    var celda4	= miTabla.insertCell(3);
                    var celda5	= miTabla.insertCell(4);
                    var celda6	= miTabla.insertCell(5);
                    var celda7	= miTabla.insertCell(6);
                    var celda8	= miTabla.insertCell(7);
                    var celda9	= miTabla.insertCell(8);			
                    celda1.innerHTML = "<input type='hidden' name='0formD"  + nDestC + "_idatencion' value='<?php echo $Id;?>' /><input type='hidden' name='0formD"  + nDestC + "_anio' value='<?php echo $Anio;?>' /><input type='hidden' name='0formD" + nDestC + "_item' id='ItemD" + nDestC + "' value='" + nDestC + "' /><label name='Item"  + nDestC + "' id='Item" + nDestC + "' >" + nDestC + "</label>";
                    celda2.innerHTML = "<input name='0formD" + nDestC + "_idservicio' id='IdServicioD" + nDestC + "' type='hidden' value='" + IdServicio + "' />" + Descripcion;
                    celda3.innerHTML = "<input name='LegalD" + nDestC + "' id='LegalD" + nDestC + "'  type='hidden' value='" + Legal + "' /><input type='checkbox' name='Legal"  + nDestC + "' id='Legal"  + nDestC + "' " + Check + " disabled='disabled'/>";
                    celda4.innerHTML = "<input name='0formD" + nDestC + "_folios' id='FoliosD" + nDestC + "' type='text' value='" + Folios + "' style='width:50px; text-align:center' onkeypress='return permite(event, \"num\");' " + FolioT + "/>";
                    celda5.innerHTML = "<input name='0formD" + nDestC + "_cantidad' id='CantidadD" + nDestC + "' type='text' value='" + Cantidad + "' style='width:60px; text-align:center' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");' " + CantidadT + "/>";
                    celda6.innerHTML = "<input name='0formD" + nDestC + "_monto' id='MontoD" + nDestC + "' type='text' value='" + Precio + "' style='width:60px; text-align:right;' onkeypress='CalcularTotalItem(event, " + nDestC + "); return permite(event, \"num\");'/>";
                    celda7.innerHTML = "<label name='TotalD" + nDestC + "' id='TotalD" + nDestC + "' >" + Importe + "</label>";
                    celda8.innerHTML = "<input type='hidden' name='0formD" + nDestC + "_correlativo' id='CorrelativoD" + nDestC + "' value='" + Kardex + "' />" + Kardex;
                    celda9.innerHTML = "<img src='../../imagenes/iconos/eliminar.png' width='16' height='16' onclick='QuitaServicio(" + nDestC + ");' style='cursor:pointer'/>";							
                    $('#ConServicios').val(nDestC);			
                    var cssString = 'text-align:center;';
                    miTabla.style.cssText = cssString;
                    miTabla.setAttribute('style',cssString);			
                    var cssString = 'padding-left:5;text-align:left;';
                    celda2.style.cssText = cssString;
                    celda2.setAttribute('style',cssString);			
                    var cssString = 'padding-right:5;text-align:right;';
                    celda7.style.cssText = cssString;
                    celda7.setAttribute('style',cssString);
                    NumerarC();			
                    $('#Cantidad').val('1');
                    $('#IdProducto').val('');
                    $('#CodProducto').val('');
                    $('#Descripcion').val('');
                    $('#Precio').val('0.00');
                    $('#Servicio').focus();
            }
	}
	function QuitaServicio(x)
    {	
            var current = window.event.srcElement; 
            while ( (current = current.parentElement) && current.tagName !="TR");{
                current.parentElement.removeChild(current);
                nDest = nDest - 1;
                NumerarC();
            }
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
		var nTotal = 0;
		var nIgv = 0;
		var nSubTotal = 0;		
		for (var i=1;i<=nDestC;i++){
			try{	
                document.getElementById('Item' + i).innerHTML = contt;
                $('#0formD' + i + '_item').val(contt);
                contt = contt + 1;
                nTotal =  nTotal + parseFloat($('#TotalD' + i).html());
			}catch(err){}
		}
		var IGV = $('#IgvP').val();
		$('#Total').html(nTotal.toFixed(2));
		$('#Totalg').val(nTotal.toFixed(2));
		$('#IGV').html('0.00');
		$('#SubTotal').html(nTotal.toFixed(2));
		if ($('#Comprobante').val()==2){
                    if ($('#IgvAfecto').val()==1){
                        nSubTotal = nTotal - (nTotal * (IGV/100));
                        $('#SubTotal').html(nSubTotal.toFixed(2));
                        nIgv = nTotal - nSubTotal;
                        $('#IGV').html(nIgv.toFixed(2));
                    }
		}
		$.ajax({
                    <?php if($IdAtencion==''){ ?>url:'CantidadEnLetra.php',<?php }else{ ?>url:'../../caja/CantidadEnLetra.php',<?php } ?>
                    type:'POST',
                    async:true,
                    data:'Importe=' + nTotal.toFixed(2),
                    success:function(data){
                            $('#EnNumeros').html(data);
                    }
		});
	}	
	function ponerCeros(obj){
            var resultado ='';
            for (var i=obj.length; i<=7; i++){
                resultado = '0' + resultado;
            }
            return resultado + obj;
	}
	function VerificaC(){
            if ($('#Comprobante').val()==1){
                $('#IdDocumento').val(1);
                $('#Documento').html('D.N.I. :');
                $('#DniRuc').attr('maxlength', 8);
                $('#TrIGV').css('display', 'none');
            }else{
                $('#IdDocumento').val(8);
                $('#Documento').html('R.U.C. :');
                $('#DniRuc').attr('maxlength', 11);
                $('#TrIGV').css('display', '');			
            }		
            if (<?php echo $Op;?>==0){
                $.ajax({
                    <?php if ($IdAtencion==''){?>url:'comprobante.php',<?php }else{ ?>url:'../../caja/comprobante.php',<?php } ?>
                    type:'POST',
                    async:true,
                    data:'IdCaja=<?php echo $rowCj[0];?>&IdNotaria=<?php echo $_SESSION["notaria"];?>&IdComprobante=' + $('#Comprobante').val(),
                    success:function(data){
			//alert(data);
                        var datos = data.split('|');
                        var Serie = datos[0];
                        var Numero = datos[1];					
                        $('#Serie').val(Serie);
                        $('#Numero').val(ponerCeros(Numero));
                    }
                });
            }		
            NumerarC();
	}	
	function VerificaFP(){}	
	function Cancelar(){
            window.location.href='index.php';
	}	
	function ValidarFormEnt(evt)
        {
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if ( keyPressed == 13 ){
                Guardar(<?php echo $Op;?>);
	        }
	}
	function loadTicket(evt)
        {
            /*
	    var keyPressed = (evt.which) ? evt.which : event.keyCode;
            if ( keyPressed == 13 )
	    {
		var idtiket  = $("#Id").val();
   		LimpiarDatos();validarTicket
                validarTicket();
                $("#Comprobante").focus();
		$("#Id").val(idtiket);
                return false;                                
	    }
	    */

	}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Nº Atenci&oacute;n :</td>
    <td>
        <input type="text" class="inputtext" style="text-align:center; font-size:12px; width:80px" name="Id" id="Id" value="<?php echo $rowX[0];?>" <?php if($Id!=''){echo "disabled";}?> onkeypress="loadTicket(event)" />
        <input type="hidden" class="inputtext" style="text-align:center; font-size:12px; width:80px" name="0form1_idatencion" id="IdAtencion" value="<?php if($Ida!=""){echo $Ida;} else {echo $row[4]; } ?>" <?php if($Id!=''){echo "readonly";}?> onkeypress="validarTicketEnt(this); CambiarFoco(this, 'Comprobante');"/>
        <input type="hidden" name="1form1_idfacturacion" id="IdFacturacion" value="<?php echo $Id;?>" />
        <a href="http://www.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias" target="_blank">Verificar RUC</a>
    </td>
    <td align="right">
		<table width="180" border="0" cellspacing="0" cellpadding="0">
      		<tr>
                <td>
                    <input type="hidden" name="IdCaja" id="IdCaja" value="<?php echo $IdCaja;?>" /><?php echo $Caja;?>&nbsp;</td>
                <td align="right"><?php echo $Estado;?></td>
		     </tr>
	    </table>
	</td>
  </tr>
  <tr>
    <td class="TituloMant">Comprobante :</td>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="160">
                <select name="0form1_idcomprobante" id="Comprobante" class="select" style="font-size:12px; width:150px" onchange="VerificaC(); Tab('Serie');" >
            <?php
                		$SelectLT = "SELECT * FROM comprobante INNER JOIN caja_notaria_comprobante ON caja_notaria_comprobante.idcomprobante=comprobante.idcomprobante WHERE comprobante.estado='1' AND caja_notaria_comprobante.idnotaria='".$_SESSION['notaria']."'";
                            $ConsultaLT = $Conn->Query($SelectLT);

                            while($rowLT=$Conn->FetchArray($ConsultaLT)){
                                $Select = '';
                                if ($row[1]==$rowLT[0]){
                                    $Select = 'selected="selected"';
                                }
            ?>
          <option value="<?php echo $rowLT[0];?>" <?php echo $Select?>><?php echo $rowLT[1];?></option>
<?php
                }
?>
        </select>
        </td>
        <td width="65"><input type="text" class="inputtext" style="font-size:12px; width:50px; text-transform:uppercase;" name="0form1_comprobante_serie" id="Serie"  maxlength="3" value="<?php echo $row[2];?>" <?php if($Id!=''){echo "readonly";}?>  onkeypress="CambiarFoco(event, 'Numero');"/> 
          -</td>
        <td><input type="text" class="inputtext" style="font-size:12px; width:70px; text-transform:uppercase;" name="0form1_comprobante_numero" id="Numero"  maxlength="7" value="<?php echo $row[3];?>" <?php if($Id!=''){echo "readonly";}?>  onkeypress="CambiarFoco(event, 'Numero');"/></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="TituloMant">Cliente :</td>
    <td colspan="2">
      <input type="hidden" name="0form1_idnotaria" id="IdNotaria" value="<?php echo $_SESSION['notaria'];?>" />
      <input type="hidden" name="0form1_idcliente" id="IdCliente" value="<?php echo $row[5];?>" />
      <input type="hidden" name="0form1_iddocumento" id="IdDocumento" value="<?php echo $row[6];?>" /><label id="Documento" style="font-size:12px">DNI : </label>
      <input type="text" class="inputtext" style="font-size:12px; width:110px; text-transform:uppercase; text-align:center" name="0form1_dni_ruc" id="DniRuc"  maxlength="11" value="<?php echo $row[7];?>" <?php echo $Enabled;?> onkeypress="return permite(event,'num')" />      
      <input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_nombres" id="Nombres"  maxlength="100" value="<?php echo $row[8];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Direcci&oacute;n :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_direccion" id="Direccion"  maxlength="100" value="<?php echo $row[9];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Fecha');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Fecha Facturaci&oacute;n : </td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_facturacion_fecha" id="Fecha" value="<?php echo $Fecha;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'FormaPago');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="TituloMant"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Moneda : </td>
        <td><select name="0form1_idmoneda" id="Moneda" class="select" style="font-size:12px; width:100px" onchange="Tab('TipoCambio');" >
<?php
        $SelectLT   = "SELECT * FROM moneda WHERE estado = 1";
        $ConsultaLT = $Conn->Query($SelectLT);
        while($rowLT=$Conn->FetchArray($ConsultaLT)){
            $Select = '';
            if ($row[12]==$rowLT[0]){
                    $Select = 'selected="selected"';
            }
?>
          <option value="<?php echo $rowLT[0];?>" <?php echo $Select;?>><?php echo $rowLT[1];?></option>
<?php
        }
?>
          </select></td>
        <td>Tipo de Cambio : 
          <input type="text" class="inputtext" style="font-size:12px; width:50px; text-align:right;" name="0form1_tipo_cambio" id="TipoCambio" value="<?php echo $TipoCambio;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FormaPago');"/></td>
        <td>Forma de pago : 
          <select name="0form1_idforma_pago" id="FormaPago" class="select" style="font-size:12px; width:100px" onchange="VerificaFP(); Tab('Credito');" >
<?php
        $SelectLT = "SELECT * FROM forma_pago WHERE estado = 1 order by idforma_pago asc";
        $ConsultaLT = $Conn->Query($SelectLT);
        while($rowLT=$Conn->FetchArray($ConsultaLT)){
            $Select = '';
            if ($row[14]==$rowLT[0]){
                    $Select = 'selected="selected"';
            }
?>
        <option value="<?php echo $rowLT[0];?>" <?php echo $Select;?>><?php echo $rowLT[1];?></option>
<?php
        }
?>
            </select></td>
        <td><input type="checkbox" name="Credito2" id="Credito2" <?php if ($Credito==1) echo "checked='checked'";?> onclick="CambiaCredito();" style="display:none" /><input type="hidden" name="0form1_credito" id="Credito" value="<?php echo $Credito;?>" /></td>
        </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="hidden" name="0form1_anio" value="<?php echo $Anio;?>" /></td>
  </tr>
</table>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <div id="DivDetalleT">
		<table width="700" border="1" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#ECECEC" id="ListaMenu2">
    			<tr>
                            <th title="Cabecera" width="50" height="20">Item</th>
				<th title="Cabecera">Servicio</th>
				<th title="Cabecera" width="60">Kardex</th>
				<th title="Cabecera" width="60">Cantidad</th>
				<th title="Cabecera" width="70">Precio</th>
				<th title="Cabecera" width="70">Total</th>
   			    <th title="Cabecera" width="20">&nbsp;</th>
                        </tr>
   			<tbody>
<?php
		$NumRegs = 0;
		if ($Op!=0){
			$SQL2 = "SELECT facturacion_detalle.anio, facturacion_detalle.idfacturacion, facturacion_detalle.item, facturacion_detalle.idservicio, servicio.descripcion, facturacion_detalle.correlativo, facturacion_detalle.cantidad, facturacion_detalle.monto, (facturacion_detalle.cantidad * facturacion_detalle.monto) FROM servicio INNER JOIN facturacion_detalle ON (servicio.idservicio = facturacion_detalle.idservicio) WHERE facturacion_detalle.idfacturacion = '$Id'";//." AND facturacion_detalle.anio = '".$Anio."'";
			$Consulta2 = $Conn->Query($SQL2);			
			while($row2 = $Conn->FetchArray($Consulta2)){
				$NumRegs = $NumRegs + 1;
				$EnabledC = $Enabled;				
				$nTotal = $nTotal + str_replace(',', '', $row2[8]);
?>
				<tr>
                                    <td align="center">
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_anio' value='<?php echo $Anio;?>' />
                                        <input type='hidden' name='0formD<?php echo $NumRegs;?>_idfacturacion' value='<?php echo $Id;?>' />
                                        <input type="hidden" name="0formD<?php echo $NumRegs;?>_item" id="ItemD<?php echo $NumRegs;?>" value="<?php echo $NumRegs;?>" />
                                        <label id="Item<?php echo $NumRegs;?>"><?php echo $NumRegs;?></label>
                                    </td>
				    <td style="padding-left:5px"><input name="0formD<?php echo $NumRegs;?>_idservicio" id="IdServicio<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[3];?>" /><?php echo $row2[4];?></td>
				    <td style="padding-left:5px"><input name="0formD<?php echo $NumRegs;?>_correlativo" id="Correlativo<?php echo $NumRegs;?>" type="hidden" value="<?php echo $row2[5];?>" /><?php echo $row2[5];?>&nbsp;</td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_cantidad" id="CantidadD<?php echo $NumRegs;?>" value="<?php echo $row2[6];?>" style="width:60px; text-align:center" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $EnabledC;?>/></td>
				    <td align="center"><input type="text" name="0formD<?php echo $NumRegs;?>_monto" id="MontoD<?php echo $NumRegs;?>" value="<?php echo str_replace(',','', $row2[7]);?>" style="width:60px; text-align:right" onkeypress="CalcularTotalItem(event, <?php echo $NumRegs;?>); return permite(event, 'num');" <?php echo $Enabled;?>/></td>
				    <td align="right" style="padding-right:5px"><label id="TotalD<?php echo $NumRegs;?>"><?php echo str_replace(',','', $row2[8]);?></label></td>
				    <td align="center"><img src="../imagenes/iconos/eliminar.png" width="16" height="16" onclick="QuitaServicio(this);" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none"; } ?>" title="Quitar Servicio" /></td>
				</tr>
<?php
			}
		}
                echo "<script> var nDest = $NumRegs; var nDestC = $NumRegs; </script>";
?>
                    </tbody>
		</table>
			<input type="hidden" name="ConServicios" id="ConServicios" value="<?php echo $NumRegs;?>"/>
        </div>		
    </td>
  </tr>
  <tr>
    <td height="35" align="right" valign="middle">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="TituloMant">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="TituloMant">Son : <label id="EnNumeros" style="font-size:12px"></label></td>
          <td width="250" rowspan="2" valign="top"><table width="200" border="1" cellspacing="1" cellpadding="0" id="GrillaT">
            <tr>
              <th align="center" scope="row">Sub Total  : </th>
                <td align="right" width="80" style="padding-right:5px; font-size:14px"><label id="SubTotal">0.00</label></td>
              </tr>
            <tr id="TrIGV">
              <th align="center" scope="row"><input type='hidden' name='0form1_igv_afecto' id="IgvAfecto" value='<?php echo $Igv;?>' />
                <input type='hidden' name='0form1_igv' id="IgvP" value='<?php echo $IgvP;?>' />
                I.G.V. :</th>
                <td align="right" style="padding-right:5px; font-size:14px"><label id="IGV">0.00</label></td>
              </tr>
            <tr>
              <th align="center" scope="row">Total :</th>
                <td align="right" style="padding-right:5px; font-size:14px"><input type='hidden' name='0form1_total' id="Totalg" value='<?php echo $row[18];?>' /><label id="Total">0.00</label></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" class="TituloMant">Fecha de Cancelaci&oacute;n: </td>
              <td><input type="text" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_cancelacion_fecha" id="CancelacionFecha" value="<?php echo $FechaC;?>" <?php if($Id!=''){echo "readonly";}?> onkeypress="CambiarFoco(event, 'Observaciones');"/></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" class="TituloMant">Observaciones : </td>
              <td><textarea name="0form1_observaciones" rows="6" class="inputtext" id="Observaciones" style="font-size:12px; width:280px; text-transform:uppercase;" <?php echo $Enabled;?>><?php echo $row[20];?></textarea></td>
            </tr>
          </table></td>
          </tr>
      </table>
	  </td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px"><span style="padding-right:5px; font-size:14px">
      <input type='hidden' name='TotalTicket' id="TotalTicket"/>
    </span></td>
  </tr>
  <tr>
    <td align="left" valign="middle" style="font-size:12px">Generado por : <?php echo $Usuario;?></td>
  </tr>
</table>
</form>
</div>
<script>
  VerificaC();
        NumerarC(); 
        if ('<?php echo $IdAtencion;?>'!=''){
            $('#Id').val('<?php echo $IdAtencion;?>');
            validarTicket();
        }        
</script>
<?php
    if ($IdCaja==0){
        echo "<script> alert('Usted no esta asignado a alguna Caja'); Cancelar();</script>";
    }
?>
