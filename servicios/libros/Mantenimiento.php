<?php
if(!session_id()){ session_start(); }	
    include('../../config.php');
    include('../../config_seguridad.php');
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Usuario = $_SESSION["Usuario"];
    $Fecha	= date('d/m/Y');
    $Estado = "<label style='color:#FF6600'>PENDIENTE</label>";
    $Anio = $_SESSION["Anio"];
    $FolioI		= 1;
    $FolioF		= 1;
    $Guardar = "Op=$Op";
    if($Op==2 || $Op==4)
    {
        $Enabled = "readonly";
    }
    $Enabled2 = "readonly";
    if($Id!='')
    {
        $Select 	= "SELECT libro.*, 
                            c.nombres||' '||coalesce(c.ape_paterno,'')||' '||coalesce(c.ap_materno,'') as nombre,
                            c.dni_ruc,
                            c.direccion,
                            c.telefonos,
                            case libro.idcliente when 0 then c.idcliente else libro.idcliente end as idcliente
                    FROM libro inner join atencion as a on a.idatencion = libro.idatencion 
                              inner join cliente as c on a.idcliente = c.idcliente
                    WHERE libro.idlibro = '$Id'";

        $Consulta 	= $Conn->Query($Select);

        $row 	= $Conn->FetchArray($Consulta);
        $Usuario 	= $_SESSION["Usuario"];
        $Fecha	= $Conn->DecFecha($row[2]);
        $FolioI	= $row[10];
        $FolioF	= $row[11];
        if ($row[15]==1)
        {
            $Estado = "<label style='color:#003366'>POR IMPRIMIR</label>";
        }
        if ($row[15]==2)
        {
            $Estado = "<label style='color:#003366'>CANCELADO</label>";
        }
        if ($row[15]==3)
        {
            $Estado = "<label style='color:#FF00000'>ANULADO</label>";
        }
        $Anio = $row[18];
        $Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[16]."'";
        $ConsultaS = $ConnS->Query($Sql);
        $rowS = $ConnS->FetchArray($ConsultaS);
        $Usuario	= $rowS[0];
    }
$ArrayP = array(NULL);
?>
<script>
  function gettomos()
  {
    var idtl = $("#LibroTipo").val(),
    ruc = $("#Ruc").val();
    $.get('../../libs/autocompletar/last_libro.php','idtl='+idtl+'&ruc='+ruc,function(data)
    {
        if(parseInt(data)!=0)
        {
          $("#last_book").empty().append("ULTIMO TOMO GENERADO: "+data);
          $("#last_book").css("display","inline-block");
        }
        else
        {
          $("#last_book").empty().append("NO SE ENCONTRÓ HISTORIAL PARA ESTA EMPRESA");
          $("#last_book").css("display","inline-block");
        }
        
    });  
  }
	var CantidadSgt = 'Precio';
	$(document).ready(function()
  {
      $("#newCliente").click(function()
      {
          $("#dnewCliente").load('../../parametros/cliente/MantenimientoA.php',function(){            
              $.getScript("../../parametros/cliente/script.js",function(){
                  $("#dnewCliente").dialog("open");   
                  $("#dni_ruc").focus();                
              });            
          });
          
      });

            gettomos();
            $("#LibroTipo").change(function()
            {
               gettomos();
            });
            $( "#RazonSocial" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/empresas.php',
                focus: function( event, ui ) 
                {                         
                    $("#RazonSocial").val(ui.item.nombres);            
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#RazonSocial").val(ui.item.nombres);                     
                     $("#Direccion").val(ui.item.direccion);
                     $("#Ruc").val(ui.item.dni_ruc);                     
                     $("#Telefono").val(ui.item.telefonos); 
                     $("#Direccion").focus();                             
                       return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            };  
            $("#Fecha").datepicker({dateFormat:'dd/mm/yyyy','changeYear':true,'changeMonth':true});
            $( "#Ruc" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/empresasD.php',
                focus: function( event, ui ) 
                {
                    $( "#DocRepresentante" ).val( ui.item.dni_ruc );                              
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#RazonSocial").val(ui.item.nombres);                     
                     $("#Direccion").val(ui.item.direccion);
                     $("#Ruc").val(ui.item.dni_ruc);                     
                     $("#Telefono").val(ui.item.telefonos); 
                     $("#Direccion").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };

            $( "#Solicitante" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/solicitante.php',
                focus: function( event, ui ) 
                {
                    $("#Dni").val(ui.item.dni_ruc);            
                    return false;
                },
                select: function( event, ui ) 
                {

                     $("#Dni").val(ui.item.dni_ruc);
                     $("#Solicitante").val(ui.item.nombre);
                     $("#Dni").focus();                             
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+item.dni_ruc+" - "+ item.nombre + "</a>" )
                    .appendTo( ul );
            };  
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

	});	
	function Cancelar(){
            window.location.href='index.php';
	}	
	function ValidarFormEnt(evt){
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                Guardar(<?php echo $Op;?>);
            }
	}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="550" border="0" cellspacing="0" cellpadding="0" style="float:left;">
<tr>
    <td width="98" class="TituloMant">&nbsp;&nbsp;&nbsp;Nº Libro :</td>
    <td width="222" align="left"><input type="text" class="inputtext" style="text-align:center; font-size:12px; width:50px" name="0form1_correlativo" id="Id" maxlength="2" value="<?php echo $row[3];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Cliente');"/>
      <input type="hidden" name="1form1_idlibro" value="<?php echo $row[0];?>" />
      &nbsp;&nbsp;&nbsp;Fecha : <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha" id="Fecha" value="<?php echo $Fecha;?>"  onkeypress="CambiarFoco(event, 'Servicio');"/>
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
</table>

<fieldset class="ui-widget-content ui-corner-all" style="text-align:left">
  <legend >Datos del Cliente</legend>
<table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="98" class="TituloMant">Cliente  :</td>
    <td colspan="2">
      <input type="text" class="inputtext" style="font-size:12px; width:90px;" name="0form1_idcliente" id="idcliente"  maxlength="10" value="<?php echo $row['idcliente'];?>" <?php echo $Enabled;?> readonly="" />      
      <a href="javascript:" id="newCliente"><img width="20" src="<?php echo '../../'.$urlDir;?>/imagenes/adduser.png"></a>
    </td>    
  </tr>
  <tr>
    <td width="98" class="TituloMant">Raz&oacute;n Social  :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px;" name="0form1_razonsocial" id="RazonSocial"  maxlength="100" value="<?php echo str_replace("!", "", $row['nombre']);?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Ruc');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">R.U.C. :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:90px; text-transform:uppercase;" name="0form1_ruc" id="Ruc"  maxlength="11" value="<?php echo $row['dni_ruc'];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr>
    <td width="98" class="TituloMant">Direcci&oacute;n :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_direccion" id="Direccion"  maxlength="100" value="<?php echo $row[6];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Telefono');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Tel&eacute;fono : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:90px; text-transform:uppercase;" name="0form1_telefono" id="Telefono"  maxlength="100" value="<?php echo $row['telefonos'];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FolioInicial');"/>
      
    </td>
  </tr>
   
</table>
  </fieldset>
  <br/>
  <fieldset class="ui-widget-content ui-corner-all" style="text-align:left">
  <legend >Datos del Libro</legend>
  <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="TituloMant">Tipo de Libro : </td>
    <td colspan="2"><select name="0form1_idlibro_tipo" id="LibroTipo" class="select" style="font-size:12px" onchange="" >
<?php
$SelectLT = "SELECT * FROM libro_tipo WHERE estado = 1";
$ConsultaLT = $Conn->Query($SelectLT);
while($rowLT=$Conn->FetchArray($ConsultaLT)){
$Select = '';
    if ($row[8]==$rowLT[0]){
        $Select = 'selected="selected"';
    }
?>
      <option value="<?php echo $rowLT[0];?>" <?php echo $Select;?>><?php echo $rowLT[1];?></option>
<?php
}
?>
  </select></td>
</tr>
  <?php
    $s = "select ";
   ?>
  <tr>
    <td class="TituloMant">Tomo : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:70px; text-transform:uppercase;" name="0form1_numero" id="Numero"  maxlength="8" value="<?php echo $row[9];?>" <?php echo $Enabled;?> />
      <span id="last_book" style="display:none;background:green;color:white;font-size:9; padding:4px 10px">ULTIMO TOMO GENERADO: </span>
    </td>
  </tr>
  <tr>
    <td class="TituloMant">Folios : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:60px; text-transform:uppercase;" name="0form1_folio_inicial" id="FolioInicial" value="<?php echo $FolioI;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'FolioFinal');"/> 
      al 
        <input type="text" class="inputtext" style="font-size:12px; width:60px; text-transform:uppercase;" name="0form1_folio_final" id="FolioFinal" value="<?php echo $FolioF;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'LibroNumeracionTipo');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Numeraci&oacute;n :</td>
    <td colspan="2"><select name="0form1_idlibro_numeracion_tipo" id="LibroNumeracionTipo" class="select" style="font-size:12px" onchange="Tab('Solicitante');" >
<?php
$SelectLNT 	= "SELECT * FROM libro_numeracion_tipo WHERE estado = 1";
$ConsultaLNT = $Conn->Query($SelectLNT);
while($rowLNT=$Conn->FetchArray($ConsultaLNT)){
$Select = '';
        if ($row[12]==$rowLNT[0]){
            $Select = 'selected="selected"';
        }
?>
<option value="<?php echo $rowLNT[0];?>" <?php echo $Select;?>>
<?php echo $rowLNT[1];?>
</option>
<?php
}
?>
    </select></td>
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant">Solicitante : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:250px;" name="0form1_solicitante" id="Solicitante"  maxlength="100" value="<?php echo $row[13];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Dni');"/> 
      D.N.I. : 
        <input type="text" class="inputtext" style="font-size:12px; width:70px; text-transform:uppercase;" name="0form1_solicitante_dni" id="Dni"  maxlength="8" value="<?php echo $row[14];?>" <?php echo $Enabled;?> /></td>
  </tr>
  <tr>
    <td width="98">&nbsp;</td>
    <td colspan="2"><input type="hidden" name="0form1_anio" value="<?php echo $Anio;?>" /></td>
  </tr>
</table>
</fieldset>
</form>
</div>
<div id="dnewCliente"></div>