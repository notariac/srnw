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