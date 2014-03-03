<?php   
    include('../../config.php');    
    include_once '../../libs/funciones.php';
    $Op = isset($_POST["Op"])?$_POST["Op"]:0;
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled    = "";
    $Enabled2   = "";
    $Guardar    = "";
    $Estado = "1";
    $Actual = "1";
    if($Op==2 || $Op==3)
    {
        $Enabled = "readonly";
        $Guardar = "Op=$Op";
    }
    else
    {
        if($Op==0 || $Op==1)
        {
            $Guardar = "Op=$Op";
        }
    }
    $Enabled2 = "readonly";
    if($Id!='')
    {
        $Select     = "SELECT * FROM cliente WHERE idcliente = '$Id'";
        $Consulta   = $Conn->Query($Select);
        $row        = $Conn->FetchArray($Consulta);     
        $Nombres    =  explode("!", $row[4]);
        $nnom = count($Nombres);
        if($nnom>1) { $Nombre = $Nombres[0]; $ap_paterno = $Nombres[1];  }
            else { $Nombre = $row[4];  $ap_paterno = $row['ape_paterno']; $ap_materno = $row['ap_materno']; }
        $Estado     = $row[16];
        $Guardar    = "$Guardar&Id2=$Id";
    }
    else
    {
        $Id="0";
    }
?>
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script type="text/javascript">
$(function() 
    {       
        loadBasic();
        $("#DniRuc").live('change',function()
        {                
            var ndoc = $(this).val();
            if(ndoc!="")    
            {
                $.get('../../libs/nrodoc.php','ndoc='+ndoc,function(n)
                {
                    if(n=="1"){ alert("Este numero de documento ya se registro en el sistema."); 
                    $("#DniRuc").focus(); }                        
                });
            }
        });        
        $('.text').focus(function(){
            $(this).addClass('ui-state-highlight');                
        });
        $('.text').blur(function(){
            $(this).removeClass('ui-state-highlight');
        });
        $('.quitR').live('click',function()
        {
            $(this).parent().parent().remove();
            countRepresentante();
        });
        $("#idcliente_tipo").live("change",function(){
            changeTipoCli();
        })
        $("#iddocumento").live("change",function(){
            changeDoc($(this).val());
            $("#DniRuc").focus();
        });
        $("#idcargo").change(function(){
            changeCargo();
        });
        $("#idprofesion").change(function(){
            changeProfesion();
        });
        $("#IdDepartamento").change(function(){
            TraerProvincia($(this).val());
            $("#IdProvincia").focus();
        });
        $("#IdProvincia").change(function(){
            TraerDistrito($(this).val());
            $("#IdDistrito").focus();
        });
        $("#nacionalidad").change(function(){
            if($(this).val()==1) { $("#pais").val("PERU"); }
                else { $("#pais").val(""); $("#pais").focus(); }
        });
        //DocRepresentante
        $( "#DocRepresentante" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/clienteD.php',
                focus: function( event, ui ) 
                {
                    $( "#DocRepresentante" ).val( ui.item.dni_ruc );                              
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#Representante").val(ui.item.nombres);
                     $("#IdRepresentante").val(ui.item.idcliente);
                     $("#DocRepresentante").val(ui.item.dni_ruc);
                     $("#Documento").val(ui.item.documento);
                     $("#Cargo").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };
        $( "#Representante" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cliente.php',
                focus: function( event, ui ) 
                {
                    //$( "#DocRepresentante" ).val( ui.item.dni_ruc );      
                    $("#Representante").val(ui.item.nombres);            
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#Representante").val(ui.item.nombres);
                     $("#IdRepresentante").val(ui.item.idcliente);
                     $("#DocRepresentante").val(ui.item.dni_ruc);
                     $("#Documento").val(ui.item.documento);
                     $("#cargo").focus();
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            };   

            $( "#desprof" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/profesion.php',
                focus: function( event, ui ) 
                {                    
                    $("#desprof").val(ui.item.descripcion);    
                    $("#idprofesion").val(ui.item.idp);        
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#idprofesion").val(ui.item.idp);
                     changeProfesion();
                     if(ui.item.idp!=999)
                     {
                        $("#descarg").focus();
                     }                     
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.descripcion + "</a>" )
                    .appendTo( ul );
            };  


            $( "#descarg" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cargo.php',
                focus: function( event, ui ) 
                {                    
                    $("#descarg").val(ui.item.descripcion);    
                    $("#idcargo").val(ui.item.idc);        
                    return false;
                },
                select: function( event, ui ) 
                {
                     $("#idcargo").val(ui.item.idc);
                     changeCargo();
                     if(ui.item.idc!=999)
                     {
                        $("#descarg").focus();
                     }                     
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.descripcion + "</a>" )
                    .appendTo( ul );
            };  
    });
    function changeProfesion()
    {
        if($("#idprofesion").val()==999)
            {
                $("#divOProfesion").css("display","block");
                $("#otra_profesion").focus();
            }
            else 
            {
                $("#divOProfesion").css("display","none");
            }    
    }
    function changeCargo()
    {
        if($("#idcargo").val()==999)
            {
                $("#divOCargo").css("display","block");
                $("#otro_cargo").focus();
            }
            else 
            {
                $("#divOCargo").css("display","none");
            } 
    }
    function countRepresentante()
    {
        var cont = 0;
        $("#ListaMenu3").find('tbody tr').each(function(){ cont += 1; });
        $('#ConRepresentante').val(cont);
    }
    function addRepresentante()
    {
        var ndoc   = $("#DocRepresentante").val();
        var IdRepresentante  = $("#IdRepresentante").val();        
        var Representante = $("#Representante").val();
        var Cargo  = $("#Cargo").val();
        html = "<tr>";
        html += "<td><input type='hidden' name='dDocRepresentante[]' value='"+ndoc+"' />"+ndoc+"</td>";
        html += "<td><input type='hidden' name='dIdRepresentante[]' value='"+IdRepresentante+"' />"+Representante+"</td>";
        html += "<td><input type='hidden' name='dCargo[]'  value='" + Cargo + "' />" + Cargo+"</td>";
        html += "<td align='center'><img class='quitR' src='../../imagenes/iconos/eliminar.png' width='16' height='16'  style='cursor:pointer'/></td>";                      
        html += "</tr>";
        $("#ListaMenu3").find('tbody').append(html);
        countRepresentante();
        $('#DocRepresentante,#IdRepresentante,#Representante,#Cargo').val('');        
        $('#DocRepresentante').focus();
        return 0;
    }
    
    function changeTipoCli() 
    {
        if($("#idcliente_tipo").val()==1)
        {
            //Per. Natural
            $("#nombre_cliente").html("Nombre:");
            $("#apellidos,#divProfesion,#divCargo,#divSexCivil,#fprofesion").css("display","block");
            $("#divpa").css("display","none");
            changeDoc(1);
            $("#divRepre").fadeOut();
        }
        else 
        {
            //Per. Juridica
            $("#nombre_cliente").html("Razon Social:");
            $("#apellidos,#divProfesion,#divCargo,#divSexCivil,#fprofesion").css("display","none");
            $("#divpa").css("display","block");
            $("#divRepre").fadeIn();
            $("#idprofesion").val(998);
            $("#idcargo").val(998);
            changeDoc(8);
        }
    }
    function changeDoc(id)
    {        
        $("#iddocumento").val(id);
        if (id==1){ $('#DniRuc').attr('maxlength','8'); }
        if (id==5){ $('#DniRuc').attr('maxlength','15'); }
        if (id==2||id==3||id==4||id==6||id==7){$('#DniRuc').attr('maxlength','11');}
        if (id==8){$('#DniRuc').attr('maxlength','11');}
        $("#DniRuc").focus();
    }
    function loadBasic()
    {
        changeTipoCli();
        var iu = $("#idubigeo").val();
        TraerDepartamento(iu);        
        if($("#idprofesion").val()!=999){$("#divOProfesion").css("display","none");}
        if($("#idcargo").val()!=999){$("#divOCargo").css("display","none");}
        if($("#idcliente_tipo").val()==1){$("#divRepre").css("display","none");}
        if($("#Id").val()==""){
            $("#idprofesion").val(998);
            $("#idcargo").val(998);
        }
        $("#fechanac").datepicker({dateFormat:'dd/mm/yy',changeYear:true,changeMonth:true});        
    }
    function TraerDepartamento(IdUbigeo)
    {

        $("#load-departamento").css("display","inline-block");            
        $.post('../../libs/departamento.php','IdUbigeo=' + IdUbigeo,function(data){                            
            $("#load-departamento").css("display","none");   
            $("#IdDepartamento").empty().append(data);
            TraerProvincia(IdUbigeo);
        });
    }
    function TraerProvincia(IdUbigeo)
    {  
        IdDep = $('#IdDepartamento').val();
        $("#load-provincia").css("display","inline-block");   
        $.post('../../libs/provincia.php','IdUbigeo=' + IdUbigeo + '&IdDep=' + IdDep,function(data){                
            $("#load-provincia").css("display","none");
            $("#IdProvincia").empty().append(data);
            TraerDistrito(IdUbigeo);
        });
    }
    function TraerDistrito(IdUbigeo)
    {
        IdProv = $('#IdProvincia').val();
        $("#load-distrito").css("display","inline-block");
        $.post('../../libs/distrito.php?Prefi=formP','IdUbigeo=' + IdUbigeo + '&IdProv=' + IdProv,function(data){
            $("#load-distrito").css("display","none");
            $("#IdDistrito").empty().append(data);
        });            
    }   
</script>
<style>fieldset { margin-bottom: 10px; border: 1px solid #dadada !important; }
        legend { color:#666;} </style>
<div>
<form id="formP" name="formP" method="post" action="guardar.php?<?php echo $Guardar;?>">
    <fieldset class="ui-widget-content ui-corner-all"  >
        <legend>Datos Basicos</legend>    
    <label class="labels">Codigo: </label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="text-align:center; width:50px; font-size:12px;" name="idcliente" id="Id" maxlength="2" value="<?php echo isset($row[0])?$row[0]:'';?>"  readonly="" />    
    <br/>
    <label class="labels">Tipo Cliente:</label>
    <select name="idcliente_tipo" id="idcliente_tipo" class="ui-widget-content ui-corner-all text" style="font-size:12px;" >
    <?php
            $SelectCT   = "SELECT * FROM cliente_tipo WHERE estado = 1";
            $ConsultaCT = $Conn->Query($SelectCT);
            while($rowCT=$Conn->FetchArray($ConsultaCT))
            {
                $Select = '';
                if ($row[1]==$rowCT[0])
                {
                    $Select = 'selected="selected"';
                }
    ?>
          <option value="<?php echo $rowCT[0];?>" <?php echo $Select;?>><?php echo $rowCT[1];?></option>
    <?php
            }
    ?>
    </select>    
    <label class="labels" style="width:70px">Tipo Doc:</label>
    <select name="iddocumento" id="iddocumento" class="ui-widget-content ui-corner-all text" style="font-size:12px; width:100px;" >
    <?php
        $SelectDoc  = "SELECT * FROM documento WHERE estado = 1";
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
    </select>    
    <label class="labels" style="width:60px">N&deg; Doc:</label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="width:110px;" name="dni_ruc" id="DniRuc" value="<?php echo isset($row[3])?$row[3]:'';?>" <?php echo $Enabled;?>  title="Ingrese el numero del documento"  />
    <br/>
    </fieldset>

    <fieldset class="ui-widget-content ui-corner-all " >
    <legend>Datos Personales</legend>    
    <label class="labels" id="nombre_cliente">Nombre:</label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="width:450px;" name="RazonNombre2" id="RazonNombre2" title="Ingrese el Nombre del Cliente" maxlength="100" value="<?php if($Nombre!=""){ echo $Nombre;};?>" <?php echo $Enabled;?> />
    <br/>
    <div id="apellidos">
    <label class="labels">Apellidos Paterno:</label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="width:450px; text-transform:uppercase; font-size:12px;" name="ap_paterno" id="ap_paterno" title="Ingrese el apellido paterno" maxlength="100" value="<?php echo $ap_paterno;?>" <?php echo $Enabled;?> />
    <br/>
    <label class="labels">Apellidos Materno:</label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="width:450px; text-transform:uppercase; font-size:12px;" name="ap_materno" id="ap_materno" maxlength="100" title="Ingrese el apellido materno" value="<?php echo $ap_materno;?>" <?php echo $Enabled;?> />
    <br/>
    </div>
    <div id="divSexCivil">
    <label class="labels">Sexo:</label>
    <select name="sexo" class="ui-widget-content ui-corner-all text" style="width:100px" id="Sexo">
        <option <?php if ($row[8]=='M') { echo 'selected="selected"';}?> value="M">MASCULINO</option>
        <option <?php if ($row[8]=='F') { echo 'selected="selected"';}?> value="F">FEMENINO</option>
    </select>    
    <label class="labels" style="width:60px">E. Civil: </label>
    <select name="idestado_civil" id="idestado_civil" class="ui-widget-content ui-corner-all text " style="font-size:12px; width:100px;" >
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
    <label for="fechanac" class="labels" style="width:80px">Fecha Nac.: </label>
    <input type="text" name="fechanac" id="fechanac" value="<?php echo date('d/m/Y',strtotime('-18 year')); ?>" class="ui-widget-content ui-corner-all text" style="text-align:center;width:80px;"  />    
    </div>    
    <div id="divpa" style="display:none">
        <label class="labels">Partida:</label>
        <input type="text" name="partida" id="partida" title="Ingrese la Partida" class="ui-widget-content ui-corner-all text" value="<?php echo $row['partida']; ?>" style="width:180px" maxlenght="20" />
        <label class="labels" style="width:100px">Asiento: </label>
        <input type="text" name="asiento" id="asiento" title="Ingrese el asiento" class="ui-widget-content ui-corner-all text" value="<?php echo $row['asiento']; ?>" size="20" maxlenght="20" />
        <br/>
    </div>
    <label class="labels">e-mail/Correo: </label>
    <input type="text" name="email" id="email" class="ui-widget-content ui-corner-all text" style="width:250px;text-transform:lowercase; " value="<?php echo $row['email']; ?>"  />
    <label class="labels" style="width:80px">Telefonos: </label>
    <input type="text" name="telefonos" id="telefonos" class="ui-widget-content ui-corner-all text" style="width:105px" value="<?php echo $row['telefonos']; ?>"  />
    </fieldset>

    <fieldset class="ui-widget-content ui-corner-all">
    <legend>Datos de Ubicacion</legend>  
    <label class="labels">Ubigeo:</label>    
        <select name="IdDepartamento" id="IdDepartamento" class="ui-widget-content ui-corner-all text">
            <option value="">-Departamento-</option>
        </select>
        <img id="load-departamento" src='../../imagenes/avance.gif'  width=20 style="display:none"/>
        <select name="IdProvincia" id="IdProvincia" class="ui-widget-content ui-corner-all text">
            <option value="">-Provincia-</option>
        </select>
        <img id="load-provincia" src='../../imagenes/avance.gif'  width=20 style="display:none" />
        <select name="IdDistrito" id="IdDistrito" class="ui-widget-content ui-corner-all text">
            <option value="">-Distrito-</option>
        </select>
        <img id="load-distrito" src='../../imagenes/avance.gif'  width=20  style="display:none"/>
        <input type="hidden" name="idubigeo" id="idubigeo" value="<?php if($row['idubigeo']=="" or $row['idubigeo']=="000000"){echo "220901";} else {echo $row['idubigeo'];} ?>" />
    <br/>
    <label class="labels">Direcci&oacute;n:</label>
    <input type="text"  align="left" class="ui-widget-content ui-corner-all text" style="width:450px; text-transform:uppercase; font-size:12px;" name="direccion" id="Direccion" title="Ingrese la direccion" value="<?php echo isset($row[5])?$row[5]:'';?>" <?php echo $Enabled;?> />
    <br/>
    <label class="labels">Nacionalidad:</label>
    <select name="nacionalidad" id="nacionalidad" class="ui-widget-content ui-corner-all text" style="width:180px;">
        <option <?php if ($row[10]=='1') { echo 'selected="selected"';}?> value="1">PERUANO</option>
        <option <?php if ($row[10]=='2') { echo 'selected="selected"';}?> value="2">EXTRANJERA</option>
    </select>
    <label class="labels" style="width:80px;">Pais: </label>
    <input type="text" class="ui-widget-content ui-corner-all text" style="width:175px; text-transform:uppercase; font-size:12px;" name="pais" id="pais" value="<?php echo isset($row[11])?$row[11]:'PERU';?>" <?php echo $Enabled;?> />
    <br/>
    </fieldset>    
    <fieldset id="fprofesion" class="ui-widget-content ui-corner-all">
        <legend>Profesion / Cargo</legend>
        <div id="divProfesion">
            <label class="labels">Profesi&oacute;n:</label>
            <input type="text" name="desprof" id="desprof" value="" class="ui-widget-content ui-corner-left text"  />
            <select name="idprofesion" id="idprofesion" class="ui-widget-content ui-corner-right text" style="font-size:12px; width:300px " onchange="" >
            <?php
            $SelectDoc = "SELECT * FROM ro.profesion WHERE estado = 1 ORDER BY idprofesion,descripcion ASC";
            $ConsultaDoc = $Conn->Query($SelectDoc);
            while($rowDoc=$Conn->FetchArray($ConsultaDoc))
            {
                $Select = '';
                if ($row['idprofesion']==$rowDoc[0])
                {
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
            <br/>
            </div>
            <div id="divOProfesion" >
                <label class="labels">Otra Profesion:</label>
                <input type="text" name="otra_profesion" id="otra_profesion" value="" class="ui-widget-content ui-corner-all text" size="48" maxlengt="100" style="width:450px" />
                <br/>
            </div>    
            <div id="divCargo">
            <label class="labels">Cargo:</label>
            <input type="text" name="descarg" id="descarg" value="" class="ui-widget-content ui-corner-left text"  />
            <select name="idcargo" id="idcargo" class="ui-widget-content ui-corner-right text" style=" width:300px" >
            <?php
                $SelectDoc  = "SELECT * FROM ro.cargo WHERE estado = 1 order by idcargo,descripcion asc";
                $ConsultaDoc = $Conn->Query($SelectDoc);
                while($rowDoc=$Conn->FetchArray($ConsultaDoc))
                {
                    $Select = '';
                    if ($row['idcargo']==$rowDoc[0])
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
            
            </div>
            <div id="divOCargo">
            <label class="labels">Otro Cargo:</label>
            <input type="text" name="otro_cargo" id="otro_cargo" value="" class="ui-widget-content ui-corner-all text" size="48" maxlengt="100" style="width:450px" />
            </div>




    </fieldset>
    
    
    <!-- Representantes. -->
    <div id="divRepre" style="width:710px; margin:10px auto;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="AgregarElemento" <?php if ($Op==2 || $Op==3 || $Op==4){ echo 'style="display:none"';}?>>
          <tr>
            <td width="150" class="TituloMant">Representante :</td>
            <td colspan="2">
              <input type="text" class="ui-widget-content ui-corner-all text" style="width:110px; text-transform:uppercase; font-size:12px" name="DocRepresentante" id="DocRepresentante" value="" />
              <input type="text" class="ui-widget-content ui-corner-all text" style="width:350px; text-transform:uppercase; font-size:12px" name="Representante" id="Representante" value="" />
              <input type="hidden" name="IdRepresentante" id="IdRepresentante" />              
          </td>
          </tr>
          <tr>
            <td class="TituloMant">Cargo :</td>
            <td width="368"><input type="text" class="ui-widget-content ui-corner-all text" style="width:320px; text-transform:uppercase; font-size:12px" name="Cargo" id="Cargo" value="" /></td>
            <td width="182" style="padding-left:10px; color:#003366">
              <input type="button" name="addParticipante" id="addParticipante" onclick="addRepresentante()" value="Agregar Participante" />              
            </td>
          </tr>
        </table>
        <table width="710" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu3" style="margin-top:5px;">
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
                    if ($row2[9]==0)
                    {
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
            ?>
          </tbody>
        </table>
        <br/>
    </div>
    <label class="labels">Estado:</label>
    <label style="cursor: pointer;"><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> />
    <input type="hidden" name="estado" id="estado" value="<?php echo $Estado;?>" /> Activo</label>
</form>
