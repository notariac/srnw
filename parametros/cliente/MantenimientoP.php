<?php	
    include('../../config.php');	
    include_once '../../libs/funciones.php';
    $Op = isset($_POST["Op"])?$_POST["Op"]:0;
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Estado = "1";
    $Actual = "1";
    if($Op==2 || $Op==3){
        $Enabled = "readonly";
        $Guardar = "Op=$Op";
    }else{
        if($Op==0 || $Op==1){
            $Guardar = "Op=$Op";
        }
    }
    $Enabled2 = "readonly";
    if($Id!='')
    {
        $Select 	= "SELECT * FROM cliente WHERE idcliente = '$Id'";
        $Consulta 	= $Conn->Query($Select);
        $row 		= $Conn->FetchArray($Consulta);		
        $Nombres 	=  explode("!", $row[4]);
        $Estado 	= $row[17];
        $Guardar 	= "$Guardar&Id2=$Id";
    }
    else
    {
        $Id="0";
    }
?>
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script>
	$(document).ready(function(){   
            $('.quitR').live('click',function(){
                $(this).parent().parent().remove();
                countRepresentante();
            })
            <?php 
            if($Id==''){
            ?>
            $("#ClienteTipo").val(1);            
            <?php 
            }
            ?>
            function formatItemD(row){
                    return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[0] + "</td></tr></table>";
            }
            function formatItemC(row){
                    return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300'>" + row[0] + "</td></tr></table>";
            }
            function formatItemCD(row){
                    return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='110'>" + row[0] + "</td><td>" + row[1] + "</td></tr></table>";
            }
            function formatResult(row){
                    return row[0];
            }
            function formatResultD(row){
                    return row[0];
            }		
            $('#Direccion').autocomplete('../../libs/autocompletar/direccion.php', {
                autoFill: true,
                width: 350,
                selectFirst: false,
                formatItem: formatItemD, 
                formatResult: formatResult,
                mustMatch : false
            }).result(function(event, item){
                $("#Direccion").val(item[0]);
            });
            $('#Representante').autocomplete('../../libs/autocompletar/cliente.php', {
                autoFill: true,
                width: 350,
                selectFirst: false,
                formatItem: formatItemC, 
                formatResult: formatResult,
                mustMatch : false
            }).result(function(event, item){
                $("#Representante").val(item[0]);
                $("#IdRepresentante").val(item[3]);
                $("#DocRepresentante").val(item[2]);
                $("#Documento").val(item[4]);
            });		
            $('#DocRepresentante').autocomplete('../../libs/autocompletar/clienteD.php', {
                autoFill: true,
                width: 450,
                selectFirst: false,
                formatItem: formatItemCD,
                formatResult: formatResultD,
                mustMatch: false
            }).result(function(event, item) {
                $("#Representante").val(item[1]);
                $("#IdRepresentante").val(item[3]);
                $("#DocRepresentante").val(item[0]);
                $("#Documento").val(item[4]);
            });
	});
	function CambiaEstado(){
            if (document.getElementById('Estado2').checked){
                $('#Estado').val(1);
            }else{
                $('#Estado').val(0);
            }
	}	
	function VerificaCT(){            
            <?php 
            if($Id==''){
            ?>
                $('#DniRuc').val('');
            <?php 
            }
            ?>  
            if ($('#ClienteTipo').val()==1){
                if (<?php echo $Op;?>==0){
                    $('#Documento').val(1);
                }
                $('#razon_nombre').html('Ape.Paterno');
                $('#TrNombres').css("display", "");
                $('#TrSexo').css("display", "");
                $('#TrPartida').css("display", "none");
                $('#TrProfesion').css("display", "");
                $('#TrRepresentante').css("display", "none");
                $('#TrDetalle').css("display", "none");
                $('#DniRuc').attr('maxlength','8');
                $("#bloqueap_materno").css("display","");
            }else{
                if (<?php echo $Op;?>==0){
                    $('#Documento').val(8);
                }
                $('#razon_nombre').html('Raz&oacute;n Social');
                $('#TrNombres').css("display", "none");
                $('#TrSexo').css("display", "none");
                $('#TrPartida').css("display", "");
                $('#TrProfesion').css("display", "none");
                $('#TrRepresentante').css("display", "");
                $('#TrDetalle').css("display", "");
                $('#DniRuc').attr('maxlength','11');
                $("#bloqueap_materno").css("display","none");
            }              
            <?php 
            if($Id==''){
            ?>
                $('#DniRuc').focus();
            <?php 
            }
            ?>  
	}
	function cambioDocumento(){
            <?php 
            if($Id==''){
            ?>
                $('#DniRuc').val('');
            <?php 
            }
            ?>  
            if ($('#Documento').val()==1){
                //DNI                
                $('#DniRuc').attr('maxlength','8');
            }
            if ($('#Documento').val()==5){
                //PASAPORTE               
                $('#DniRuc').attr('maxlength','15');
            }
            if ($('#Documento').val()==2||$('#Documento').val()==3||$('#Documento').val()==4||$('#Documento').val()==6||$('#Documento').val()==7){
                //CARNE DE EXTRANJERIA          
                $('#DniRuc').attr('maxlength','11');
            }
            if ($('#Documento').val()==8){
                //RUC
                $('#DniRuc').attr('maxlength','11');
            }
            <?php 
            if($Id==''){
            ?>
                $('#DniRuc').focus();
            <?php 
            }
            ?>                            
	}
	function CambiaNacionalidad(){
            if ($('#0formP_nacionalidad').val()==1){
                $('#0formP_pais').val('PERU');
                $('#TrUbigeo').css("display", "");
                TraerDepartamento('<?php echo isset($row)?$row[14]:'000000';?>');
            }else{
                $('#0formP_pais').val('');
                $('#TrUbigeo').css("display", "none");
                TraerDepartamento('000000');
            }
	}
	function TraerDepartamento(IdUbigeo){
            document.getElementById("DivDepartamento").innerHTML = "<center><img src='../../imagenes/avance.gif'  width=20 /></center>";  
            $.ajax({
                url:'../../libs/departamento.php',
                type:'POST',
                async:true,
                data:'IdUbigeo=' + IdUbigeo,
                success:function(data){
                       $("#DivDepartamento").html(data);
                       TraerProvincia(IdUbigeo);
                }
             });
	}
	function TraerProvincia(IdUbigeo){	
            IdDep = $('#IdDepartamento').val();
            document.getElementById("DivProvincia").innerHTML = "<center><img src='../../imagenes/avance.gif' width=20 /></center>";  
            $.ajax({
                url:'../../libs/provincia.php',
                type:'POST',
                async:true,
                data:'IdUbigeo=' + IdUbigeo + '&IdDep=' + IdDep,
                success:function(data){
                       $("#DivProvincia").html(data);
                       TraerDistrito(IdUbigeo);
                }
            });
	}
	function TraerDistrito(IdUbigeo){
            IdProv = $('#IdProvincia').val();
            document.getElementById("DivDistrito").innerHTML = "<center><img src='../../imagenes/avance.gif' width=20 /></center>";  
            $.ajax({
                url:'../../libs/distrito.php?Prefi=formP',
                type:'POST',
                async:true,
                data:'IdUbigeo=' + IdUbigeo + '&IdProv=' + IdProv,
                success:function(data){
                    $("#DivDistrito").html(data);
                }
            });
	}	
    function countRepresentante()
    {
        var cont = 0;
        $("#ListaMenu3").find('tbody tr').each(function(){ cont += 1; });
        $('#ConRepresentante').val(cont);
    }

    function addRepresentante()
    {
        var IdCliente   = $("#DniRuc").val();
        var IdRepresentante  = $("#DocRepresentante").val();
        var IdCliente2   = '';
        var IdRepresentante2 = '';      
        var Representante = $("#Representante").val();
        var Cargo  = $("#Cargo").val();
        html = "<tr>";
        html += "<td><input type='hidden' name='0formD"  + nDestRC + "_ruc_cliente' value='"  + IdCliente + "' /><input type='hidden' name='0formD"  + nDestRC + "_dni_representante' value='"  + IdRepresentante + "' /><input type='hidden' name='0formD"  + nDestRC + "_idcliente' value='"  + IdCliente2 + "' /><input type='hidden' name='0formD"  + nDestRC + "_idrepresentante' value='"  + IdRepresentante2 + "' />" + IdRepresentante+"</td>";
        html += "<td><input type='hidden' name='NombreD" + nDestRC + "' id='NombreD" + nDestRC + "' value='" + Representante + "' />" + Representante+"</td>";
        html += "<td><input type='hidden' name='0formD" + nDestRC + "_cargo' id='CargoD" + nDestRC + "' value='" + Cargo + "' />" + Cargo+"</td>";
        html += "<td><img class='quitR' src='../../imagenes/iconos/eliminar.png' width='16' height='16'  style='cursor:pointer'/></td>";                      
        html += "</tr>";
        $("#ListaMenu3").find('tbody').append(html);
        countRepresentante();
        $('#DocRepresentante,#Representante,#Cargo').val('');        
        $('#DocRepresentante').focus();
        return 0;
    }
	function QuitaRepresentante(x)
    {	
        $('#ListaMenu3').find('tbody tr:eq('+x+')').remove();
        countRepresentante();            
	}	
	function Cancelar()
    {
        window.location.href='index.php';
	}	
	function ValidarFormEnt(evt)
    {
        var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
        if (keyPressed == 13 )
        {
            Guardar(<?php echo $Op;?>);
        }
	}
</script>
<div align="center">
<form id="formP" name="formP" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">C&oacute;digo :</td>
    <td>
        <input type="text" class="inputtext" style="text-align:center; width:50px; font-size:12px;" name="1formP_idcliente" id="Id" maxlength="2" value="<?php echo isset($row[0])?$row[0]:'';?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/>    
    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Tipo Cliente : </td>
    <td><select name="0formP_idcliente_tipo" id="ClienteTipo" class="select" style="font-size:12px;" onchange="VerificaCT(); Tab('DniRuc');" >
<?php
        $SelectCT   = "SELECT * FROM cliente_tipo WHERE estado = 1";
        $ConsultaCT = $Conn->Query($SelectCT);
        while($rowCT=$Conn->FetchArray($ConsultaCT)){
            $Select = '';
            if ($row[1]==$rowCT[0]){
                    $Select = 'selected="selected"';
            }
?>
      <option value="<?php echo $rowCT[0];?>" <?php echo $Select;?>><?php echo $rowCT[1];?></option>
<?php
        }
?>
            </select>&nbsp;</td>
  </tr>
  <tr id="TrDocumento">
    <td width="130" class="TituloMant">Tipo Documento : </td>
    <td><select name="0formP_iddocumento" id="Documento" class="select" style="font-size:12px;" onchange="cambioDocumento();" >
<?php
        $SelectDoc 	= "SELECT * FROM documento WHERE estado = 1";
        $ConsultaDoc = $Conn->Query($SelectDoc);
        while($rowDoc=$Conn->FetchArray($ConsultaDoc)){
        $Select = '';
        if ($row[2]==$rowDoc[0]){
                $Select = 'selected="selected"';
        }
?>
      <option value="<?php echo $rowDoc[0];?>" <?php echo $Select;?>>
        <?php echo $rowDoc[1];?>
      </option>
<?php
        }
?>
    </select></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">N&deg; Documento&nbsp;:</td>
    <td><input type="text" class="inputtext" style="width:110px; text-transform:uppercase; font-size:12px;" name="0formP_dni_ruc" id="DniRuc" value="<?php echo isset($row[3])?$row[3]:'';?>" <?php echo $Enabled;?> onkeypress="return ValidarRUC(event, 'Documento', this.value, 'RazonNombre1');"/></td>
  </tr>
  <tr id="TrNombres">
    <td class="TituloMant">Nombre : </td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase; font-size:12px;" name="RazonNombre2" id="RazonNombre2" maxlength="100" value="<?php echo trim(isset($Nombres[1])?$Nombres[1]:'');?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant"><label id="razon_nombre">Razon / Apellidos </label>&nbsp;:</td>
    <td>
        <input type="text" class="inputtext" style="width:350px; text-transform:uppercase; font-size:12px;" name="RazonNombre1" id="RazonNombre1" maxlength="100" value="<?php echo trim(isset($Nombres[0])?$Nombres[0]:'');?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/>
        <input type="hidden" class="inputtext" name="0formP_nombres" id="Nombre" value="<?php echo $row[4];?>"/>
    </td>
  </tr>
  <tr id="bloqueap_materno">
    <td width="130" class="TituloMant" ><label for="ape_materno">Ap.Materno</label>&nbsp;:</td>
    <td>
        <input type="text" class="inputtext" style="width:350px; text-transform:uppercase; font-size:12px;" name="0formP_ap_materno" id="ap_materno" maxlength="100" value="<?php echo $row['ap_materno'];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/>
    </td>
  </tr>
  <tr id="TrUbigeo">
    <td class="TituloMant">Ubigeo :</td>
    <td colspan="2">    	
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td>
            <div id="DivDepartamento">
                <select name="IdDepartamento" id="IdDepartamento"></select>
            </div>			
            </td>
            <td>
            <div id="DivProvincia">
                <select name="IdProvincia" id="IdProvincia"></select>
            </div>			
            </td>
            <td>
            <div id="DivDistrito">
                <select name="IdDistrito" id="IdDistrito"></select>
            </div>			
            </td>
            </tr>
        </table>  	
    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Direcci&oacute;n : </td>
    <td><input type="text"  align="left" class="inputtext" style="width:350px; text-transform:uppercase; font-size:12px;" name="0formP_direccion" id="Direccion" value="<?php echo isset($row[5])?$row[5]:'';?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Email');"/></td>
  </tr>
  <tr id="TrPartida">
    <td class="TituloMant">Partida : </td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input type="text" class="inputtext" style="width:110px; text-transform:uppercase; font-size:12px;" name="0formP_partida" id="0formP_partida" value="<?php echo isset($row[20])?$row[20]:'';?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, '0formP_asiento');"/></td>
          <td>Asiento : </td>
          <td><input type="text" class="inputtext" style="width:110px; text-transform:uppercase; font-size:12px;" name="0formP_asiento" id="0formP_asiento" value="<?php echo isset($row[21])?$row[21]:'';?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'RazonNombre');"/></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Nacionalidad : </td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
            <?php
            $row[10]=isset($row[10])?$row[10]:'';
            ?>
            <select name="0formP_nacionalidad" id="0formP_nacionalidad" class="select" onchange="CambiaNacionalidad()" style="font-size:12px">
                <option <?php if ($row[10]=='1') { echo 'selected="selected"';}?> value="1">PERUANO</option>
                <option <?php if ($row[10]=='2') { echo 'selected="selected"';}?> value="2">EXTRANJERA</option>
            </select>		
        </td>
        <td>Pais : </td>
        <td><input type="text" class="inputtext" style="width:150px; text-transform:uppercase; font-size:12px;" name="0formP_pais" id="0formP_pais" value="<?php echo isset($row[11])?$row[11]:'';?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Sexo')"/></td>
      </tr>
    </table></td>
  </tr>
  <tr id="TrSexo">
    <td width="130" class="TituloMant">Sexo : </td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <?php
            $row[8]=isset($row[8])?$row[8]:'';
          ?>
          <td><select name="0formP_sexo" class="select" style="font-size:12px" id="Sexo">
            <option <?php if ($row[8]=='M') { echo 'selected="selected"';}?> value="M">MASCULINO</option>
            <option <?php if ($row[8]=='F') { echo 'selected="selected"';}?> value="F">FEMENINO</option>
          </select></td>
          <td>Estado Civil : </td>
          <td><select name="0formP_idestado_civil" id="0formP_idestado_civil" class="select" style="font-size:12px;" onchange="Tab('DniRuc');" >
<?php
    $SelectDoc = "SELECT * FROM estado_civil WHERE estado = 1";
    $ConsultaDoc = $Conn->Query($SelectDoc);
    while($rowDoc=$Conn->FetchArray($ConsultaDoc)){
    $Select = '';
    if ($row[9]==$rowDoc[0]){
        $Select = 'selected="selected"';
    }
?>
    <option value="<?php echo $rowDoc[0];?>" <?php echo $Select;?>>
        <?php echo $rowDoc[1];?>
    </option>
<?php
    }
?>
          </select>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr id="TrProfesion">
    <td class="TituloMant">Profesi&oacute;n: </td>
    <td><select name="0formP_idprofesion" id="0formP_idprofesion" class="select" style="font-size:12px; width:400px " onchange="Tab('0formP_idocupacion');" >
    <?php
    $SelectDoc = "SELECT * FROM ro.profesion WHERE estado = 1 ORDER BY idprofesion,descripcion ASC";
    $ConsultaDoc = $Conn->Query($SelectDoc);
    while($rowDoc=$Conn->FetchArray($ConsultaDoc)){
    $Select = '';
        if ($row[12]==$rowDoc[0]){
                $Select = 'selected="selected"';
        }
    ?>
        <option value="<?php echo $rowDoc[0];?>" <?php echo $Select;?>>
        <?php echo $rowDoc[1];?>
        </option>
    <?php
        }
    ?>
    </select></td>
  </tr>
  <tr id="TrOcupacion" >
    <td class="TituloMant">Cargo : </td>
    <td><select name="0formP_idcargo" id="0formP_idcargo" class="select" style="font-size:12px; width:400px" onchange="Tab('DniRuc');" >
<?php
    $SelectDoc 	= "SELECT * FROM ro.cargo WHERE estado = 1";
    $ConsultaDoc = $Conn->Query($SelectDoc);
    while($rowDoc=$Conn->FetchArray($ConsultaDoc))
    {
        $Select = '';
        if ($row[13]==$rowDoc[0])
        {
            $Select = 'selected="selected"';
        }
    ?>
        <option value="<?php echo $rowDoc[0];?>" <?php echo $Select;?>>
            <?php echo $rowDoc[1]; ?>
        </option>
    <?php
    }
?>
          </select>
    </td>    
  </tr>
  <tr id="tr_otro_cargo">
      <td  class="TituloMant">Otro Cargo</td>
      <td coplspan="2"><input type="text" name="otro_cargo" id="otro_cargo" value="" class="inputtext" style="width:400px; text-transform:uppercase; font-size:12px;" /></td></td>  
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="TrRepresentante">
    <td colspan="2" class="TituloMant">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="AgregarElemento" <?php if ($Op==2 || $Op==3 || $Op==4){ echo 'style="display:none"';}?>>
      <tr>
        <td width="150" class="TituloMant">Representante :</td>
        <td colspan="2">
          <input type="text" class="inputtext" style="width:110px; text-transform:uppercase; font-size:12px" name="DocRepresentante" id="DocRepresentante" value="" onkeypress="CambiarFoco(event, 'Representante');"/>
          <input type="text" class="inputtext" style="width:350px; text-transform:uppercase; font-size:12px" name="Representante" id="Representante" value="" onkeypress="CambiarFoco(event, 'Cargo');"/>
          &nbsp;</td>
      </tr>
      <tr>
        <td class="TituloMant">Cargo :</td>
        <td width="368"><input type="text" class="inputtext" style="width:320px; text-transform:uppercase; font-size:12px" name="Cargo" id="Cargo" value="" onkeypress="CambiarFoco(event, 'Cantidad');"/></td>
        <td width="182" style="padding-left:10px; color:#003366">
          <label style="cursor:pointer;" onclick="addRepresentante();">
          <table width="150" border="0" cellspacing="0" cellpadding="0" class="Boton">
            <tr>
              <td width="20" height="20" align="center" valign="middle"><img src="../../imagenes/iconos/add.png" width="16" height="16" /></td>
              <td width="98" valign="middle" style="font-size:12px">Agregar Participante </td>
            </tr>
          </table>
          </label>		  
        </td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="TrDetalle">
    <td colspan="2" align="center" class="TituloMant">
	<table width="600" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu3">
      <thead>
          <tr>
            <th width="80" height="20" title="Cabecera">D.N.I.</th>
            <th title="Cabecera">Representante</th>
            <th title="Cabecera" width="150">Cargo</th>
            <th title="Cabecera" width="20">&nbsp;</th>
          </tr>
      </thead>
      <tbody>
<?php
    $NumRegs = 0;
    $SQL2 = "SELECT cliente_representante.ruc_cliente, cliente_representante.dni_representante, cliente.nombres, cliente_representante.cargo, cliente_representante.idcliente, cliente_representante.idrepresentante FROM cliente INNER JOIN cliente_representante ON (cliente.idcliente = cliente_representante.idrepresentante) WHERE cliente_representante.idcliente = '$Id'";
    $Consulta2 = $Conn->Query($SQL2);			
    while($row2 = $Conn->FetchArray($Consulta2)){
        $NumRegs = $NumRegs + 1;
        $EnabledF = $Enabled;
        if(strpos($row2[2], "!")){
            $Nombres=explode("!",$row2[2]);
            $row2[2]=$Nombres[1]." ".$Nombres[0];
        }
        if ($row2[9]==0){
                $EnabledF = 'readonly';
        }
        $EnabledC = $Enabled;
        if ($row2[8]!=''){
                $EnabledC = 'readonly';
        }				
?>
        <tr>
          <td style="padding-left:5px">
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_ruc_cliente" id="IdClienteD<?php echo $NumRegs;?>" value="<?php echo $row2[0];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_dni_representante" id="IdRepresentanteD<?php echo $NumRegs;?>" value="<?php echo $row2[1];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idcliente" id="IdCliente2D<?php echo $NumRegs;?>" value="<?php echo $row2[4];?>" />
            <input type="hidden" name="0formD<?php echo $NumRegs;?>_idrepresentante" id="IdRepresentante2D<?php echo $NumRegs;?>" value="<?php echo $row2[5];?>" /><?php echo $row2[1];?>
          </td>
          <td style="padding-right:5px"><input type="hidden" name="NombreD<?php echo $NumRegs;?>" id="NombreD<?php echo $NumRegs;?>" value="<?php echo $row2[2];?>" /><?php echo $row2[2];?></td>
          <td align="center"><input type="hidden" name="0formD<?php echo $NumRegs;?>_cargo" id="CargoD<?php echo $NumRegs;?>" value="<?php echo $row2[3];?>" /><?php echo $row2[3];?></td>
          <td align="center"><img src="../../imagenes/iconos/eliminar.png" alt="" width="16" height="16" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar Representante" onclick="QuitaRepresentante(this);" /></td>
        </tr>
<?php
    }
        echo "<script type='text/javascript'> var nDestR = $NumRegs; var nDestRC = $NumRegs; </script>";
?>
      </tbody>
    </table>
      <input type="hidden" name="ConRepresentante" id="ConRepresentante" value="<?php echo $NumRegs;?>"/></td>
    </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Estado :</td>
    <td>
        <label style="cursor: pointer;"><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" />
        <input type="hidden" name="0formP_estado" id="Estado" value="<?php echo $Estado;?>" /> Activo</label>
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script type="text/javascript">
    TraerDepartamento('<?php echo isset($row[14])?$row[14]:'000000';?>');
    VerificaCT();
    CambiaNacionalidad();
</script>