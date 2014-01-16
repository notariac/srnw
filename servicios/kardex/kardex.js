    var participantes = 
    {
        nitem : 0,
        idparticipante  : new Array(),
        participante    : new Array(),
        documento       : new Array(),
        nrodocumento    : new Array(),
        idparticipacion : new Array(),
        participacion   : new Array(),
        tipo            : new Array(), //Otorgante, A favor, interviniente
        idrepresentado  : new Array(),
        conyuge         : new Array(),
        porcentage      : new Array(),
        partida         : new Array(),
        idzona          : new Array(),
        zona            : new Array(),
        estado          : new Array(),
        nuevo           : function (idparticipante,participante,documento,nrodocumento,idparticipacion,participacion,tipo, idrepresentado, conyuge, porcentage,partida,idzona,zona)
                          {
                             this.idparticipante[this.nitem] = idparticipante;
                             this.participante[this.nitem] = participante;                             
                             this.documento[this.nitem] = documento;
                             this.nrodocumento[this.nitem] = nrodocumento;
                             this.idparticipacion[this.nitem] = idparticipacion;
                             this.participacion[this.nitem] = participacion;
                             this.partida[this.nitem] = partida;
                             this.idzona[this.nitem] = idzona;
                             this.zona[this.nitem] = zona;
                             this.tipo[this.nitem] = tipo;
                             if(idrepresentado=='')
                                idrepresentado = 'NULL';
                             this.idrepresentado[this.nitem] = idrepresentado;
                             if(conyuge=='')
                                conyuge = 'NULL';
                             this.conyuge[this.nitem] = conyuge;
                             this.porcentage[this.nitem] = porcentage;
                             this.estado[this.nitem] = true;
                             this.nitem += 1;
                             
                          },
         listar      : function()
                      {
                       var html = "";
                       var cont = 0;
                       for(ii=0;ii<this.nitem;ii++)
                       {
                          if(this.estado[ii])
                          {
                            bg = "#FFFFFF";
                            if(this.conyuge[ii]!=""&&this.conyuge[ii]!="NULL")
                            {
                                bg = "#FFCACA";
                            }
                            else
                            {
                              if(this.search_conyuge(this.idparticipante[ii]))
                              {
                                 bg = "#FFCACA";
                              }
                            }
                            html += '<tr style="background:'+bg+'">';
                            html += '<td align="center">'+this.text_tipo(this.tipo[ii])+'</td>';
                            if(this.documento[ii]=="DOCUMENTO NACIONAL DE INDENTIDAD")
                              this.documento[ii] = "DNI";
                            html += '<td align="center">'+this.documento[ii]+'</td>';
                            html += '<td align="center">'+this.nrodocumento[ii]+'</td>';
                            html += '<td align="left">'+this.participante[ii]+'</td>';
                            porcentage = '';
                            if(this.porcentage[ii]!=""&&this.porcentage[ii]!="-")                            
                               porcentage = '('+this.porcentage[ii]+'%)';                            
                            html += '<td align="center">'+this.participacion[ii]+' '+porcentage+'</td>';
                            
                            html += '<td align="left">'+this.search_representado(this.idrepresentado[ii],this.partida[ii],this.zona[ii])+'</td>';
                            html += '<td align="center"><img  id="item-'+ii+'" class="quit-p" src="../../imagenes/iconos/eliminar.png" width="16" height="16"  style="cursor:pointer; " title="Quitar Participante"></td>';
                            html += "</tr>";
                          }
                       }                                            

                       $("#ListaMenu2").find('tbody').empty().append(html);                       
                    },
        listp     : function()
                    {
                       var cmb = "<option value=''>Ninguno</option>";
                       for(ii=0;ii<this.nitem;ii++)
                       {
                          if(this.estado[ii]==true&&this.search_conyuge(this.idparticipante[ii])!=true)
                          {
                              cmb += "<option value='"+this.idparticipante[ii]+"'>"+this.participante[ii]+" ("+this.participacion[ii]+") </option>";
                          }
                       }
                        return cmb;
                    },
          eliminar    : function(i){this.estado[i] = false; this.listar(); var htmlp = participantes.listp(); $("#list_participantes").empty().append(htmlp);},
          text_tipo : function(t)
                      {
                         str = "";
                         t = parseInt(t);
                         switch(t)
                         {
                            case 1: str = "OT"; break;
                            case 2: str = "FA"; break;
                            case 3: str = "IN"; break;
                            default: break;
                         }
                         return str;
                      },
          search_representado : function(idr,p,z)
                  {
                      var name = "";
                      for(j=0;j<this.nitem;j++)
                       {                          
                          if(this.estado[j])
                          {
                             if(idr==this.idparticipante[j])
                                name = this.participante[j]+' - '+p+' - '+z;
                          }
                      }
                      return name;
                  },
          search_conyuge : function(idp)
                  {
                      var p = false;
                      for(k=0;k<this.nitem;k++)
                       {                          
                          if(this.estado[k])
                          {
                             if(idp==this.conyuge[k])                               
                                p = true;                               
                          }
                      }
                      return p;
                  }
    }

function addParticipantei()
{
  bval = true;
  bval = bval && $("#DocParticipante_i").required();  
  bval = bval && $("#Participante_i").required();
  bval = bval && $("#TipoParticipacion_i").required();
  bval = bval && $("#nro_partida").required();
  bval = bval && $("#idzonar").required();
  var tp = $("#TipoParticipacion_i").val();
  if(tp!=null)
  {
    if(bval) 
    {
       var p1 = $("#IdParticipante_i").val(),
           p2 = $("#Participante_i").val(),
           p3 = $("#Documento_i").val(),
           p4 = $("#DocParticipante_i").val(),
           p5 = $("#TipoParticipacion_i").val(),
           p6 = $("#TipoParticipacion_i option:selected").html(),
           p7 = $("#tabs-participantes").tabs( "option", "selected" )+1;
           p8 = $("#list_participantes").val();
           p9 = "";
           p10 = $("#nro_partida").val();
           p11 = $("#idzonar").val(),
           p12 = $("#idzonar option:selected").html(),
           participantes.nuevo(p1,p2,p3,p4,p5,p6,p7,p8,p9,'',p10,p11,p12);
           participantes.listar();         
           clearFrmIntervinientes();
    }
    
  }
  else
  {
    alert("Debe seleccionar el tipo de participacion");
    $("#TipoParticipacion_i").focus();
  }
}
function verficarJuridico(idcliente)
{
    
    $.get('getrepresentantes.php','Id='+idcliente,function(r)
    {
        $.each(r,function(i,j)
        {
            participantes.nuevo(j.idparticipante,j.participante,j.documento,j.nrodocumento,j.idparticipacion,j.participacion,j.tipo, j.idrepresentado, j.conyuge,j.porcentage,j.partida,j.idzona,j.zona);
        });
        participantes.listar()        ;
    },'json');
    
}
function addParticipante()
{
  bval = true;
  bval = bval && $("#DocParticipante").required();
  bval = bval && $("#Participante").required();
  bval = bval && $("#TipoParticipacion").required();
  var tp = $("#TipoParticipacion").val();

  if(tp!=null)
  {
      if(bval) 
      {
          var flag = false;
          if($('#conyuge').is(':checked')) 
          {
            bval = bval && $("#DocParticipante_c").required();
            bval = bval && $("#Participante_c").required();        
            flag = true;
          }

          if(bval)
            {           
               var p1 = $("#IdParticipante").val(),
                   p2 = $("#Participante").val(),
                   p3 = $("#Documento").val(),
                   p4 = $("#DocParticipante").val(),
                   p5 = $("#TipoParticipacion").val(),
                   p6 = $("#TipoParticipacion option:selected").html(),
                   p7 = $("#tabs-participantes").tabs( "option", "selected" )+1;
                   p8 = "";
                   p9 = $("#IdParticipante_c").val();
                   p  = $("#Porcentage").val();
                   if(typeof(p)!="undefined")
                    { if(flag) p = parseFloat(p)/2; }
                   else { p='';}

                   participantes.nuevo(p1,p2,p3,p4,p5,p6,p7,p8,p9,p,'','','');
                   if(flag)
                   {
                      var  p1 = $("#IdParticipante_c").val(),
                           p2 = $("#Participante_c").val(),
                           p3 = $("#Documento_c").val(),
                           p4 = $("#DocParticipante_c").val(),
                           p5 = $("#TipoParticipacion").val(),
                           p6 = $("#TipoParticipacion option:selected").html(),
                           p7 = $("#tabs-participantes").tabs( "option", "selected" )+1;
                           p8 = "";
                           p9 = "";
                          participantes.nuevo(p1,p2,p3,p4,p5,p6,p7,p8,p9,p,'','','');
                   }            
                participantes.listar();
                verficarJuridico($("#IdParticipante").val());
                clearFrmOtorgante();
                
            }
      }
  }
  else
  {
    alert("Debe seleccionar el tipo de participacion");
    $("#TipoParticipacion").focus();
  }
}  

function clearFrmOtorgante()  
{
    $("#IdParticipante").val("");
    $("#Participante").val("");
    $("#Documento").val("");
    $("#DocParticipante").val("");

    $("#IdParticipante_c").val("");
    $("#Participante_c").val("");
    $("#Documento_c").val("");
    $("#DocParticipante_c").val("");   

    $("#conyuge").attr("checked",false);
    $("#box-conyuge").hide("fade");
}
function clearFrmIntervinientes()
{
  $("#IdParticipante_i").val("");
  $("#Participante_i").val("");
  $("#Documento_i").val("");
  $("#DocParticipante_i").val("");
  $("#nro_partida").val("");
  $("#idzonar").val("");
}
function load_participantes()
{
  var idk = $("#iddkardex").val();
  if(idk!="")
  {
     $.get('getparticipantes.php','Id='+idk,function(r)
     {
        $.each(r,function(i,j)
        {
          participantes.nuevo(j.idparticipante,j.participante,j.documento,j.nrodocumento,j.idparticipacion,j.participacion,j.tipo, j.idrepresentado, j.conyuge,j.porcentage,j.partida,j.idzona,j.zona);
        });
        participantes.listar()        ;
     },'json')
  }
}
function loadParticipacion(t)
{
   var idk = $("#iddkardex").val();
   $.get('getparticipacion.php','Id='+idk+'&tipo='+t,function(data){
        $("#TipoParticipacion").empty().append(data);
   })
}
$(document).ready(function()
{

  $("#upload_minuta").click(function(){      
      $("#box-form-minuta").dialog('open');
  });
  $("#gen_escritura").click(function(){
    
  });
  $("#upload_escritura").click(function(){
    $("#box-form-escritura").dialog('open');
  });
  $("#open_escritura").click(function(){

  });

  $("#box-form-minuta").dialog({
        autoOpen: false,        
        resizable:false,
        title: "Subir Minuta",
        width: 300
  });

  $("#box-form-escritura").dialog({
      autoOpen: false,
      resizable: false,
      title: "Subir la Escritura",
      width: 300
  });


  $('.quit').live('click',function(){
    $(this).parent().parent().remove();
    nDestx = nDestx - 1;
  });
  $('.img-quit').live('click',function(){
         $(this).parent().parent().remove();                  
    });
  $("#TipoParticipacion").change(function(){
    verifPorcentaje();
    $("#Porcentage").focus();
  });
  load_participantes();
  verifPorcentaje();
	$("#print_digitales").live('click',function(){
          var tipo = $("#edicion_impri").val();
          var kard = $("#iddkardex").val();
          window.open('../../editor/'+tipo+'.php?idkardex='+kard,'width=600,height=300');
      })
      $("#IdDepartamento2").change(function(){
            TraerProvincia($(this).val());
            $("#IdProvincia2").focus();
        });
        $("#IdProvincia2").change(function(){
            TraerDistrito($(this).val());
            $("#IdDistrito2").focus();
        });
       TraerDepartamento('000000');
       $("#NuevoParticipante").dialog(
       {
        autoOpen: false,
        modal: true,
        resizable:false,
        title: "Agregar Participante",
        width: 700,
        height: 500,
        buttons: {
            "Agregar": function() 
            {
                Op = 1;
                if (ValidarP())
                {
                    $("#ConfirmaGuardarParticipante").dialog("open");
                }
            },
            Cancelar: function() 
            {
                    $("#DivNuevoParticipante").html('');
                    $("#NuevoParticipante").dialog("close");
            }
        }	   
        });
       $("#formBienes").dialog({
        autoOpen: false,        
        resizable:false,
        title: "Bienes",
        width: 700,
        height: 400,
        buttons: {
            "Aceptar": function() {
                validarFrmBienes();            
            },
            "Salir": function() {
                    $(this).dialog("close");
            }
        }	   
        });
        $("#ConfirmaGuardarParticipante").dialog({
          autoOpen: false,          
          resizable:false,
          title: "Confirmaci&oacute;n de Operaci&oacute;n",
          height:155,
          width: 350,
            buttons: {
                "Aceptar": function() {
                    GuardarP();
                    $("#ConfirmaGuardarParticipante").dialog("close");
                },
                Cancelar: function() {
                    $("#DivGuardar").html('');
                    $(this).dialog("close");
                }
           }
          });	
        $("#listaUbigeo").dialog({
          autoOpen: false,          
          resizable:false,
          title: "Seleccion de Ubigeo",
          width: 300,
          height: 180,
          buttons: {
              "Seleccionar": function() {
                  var IdDistrito=$("#IdDistrito2").val();
                  if(IdDistrito=='000101'){
                    alert("Seleccione Un Distrito Valido");
                  }else{
                    $("#ubigeo").val(IdDistrito);
                    $("#listaUbigeo").dialog("close");
                  }
              },
              "Cancelar": function() {
                      $(this).dialog("close");
              }
          }	   
          });
          $("#addMedioPago").button({icons:{primary:'ui-icon-plus'},text:false}).click(function(e){
              e.preventDefault();
              AgregaMedioPago();
          });
          $("#addParticipante,#addParticipantei").button({icons:{primary:'ui-icon-plus'}}).click(function(e){
              e.preventDefault();              
          });
          $("#nuevoBien").button({icons:{primary:'ui-icon-document'}}).click(function(e){
              e.preventDefault();
              nuevoBien();
          });
          $("#updateBien").button({icons:{primary:'ui-icon-pencil'}}).click(function(e){
              e.preventDefault();
          });
          $("#deleteBien").button({icons:{primary:'ui-icon-close'}}).click(function(e){
              e.preventDefault();
          });
          $("#nuevoBien,#updateBien,#deleteBien").css("width","90px");
          $("#searchUbigeo").button({icons:{primary:'ui-icon-search'}}).click(function(e){
                e.preventDefault();
                $("#listaUbigeo").dialog("open");
                TraerDepartamento('000000');
          });
          $(".origen").click(function(){
             var valorOrigen=$(this).val();
             if(valorOrigen==1){
                 $("#divubigeo").css("display","block");
                 $("#divpaises").css("display","none");                 
             }else if(valorOrigen==2){
                 $("#divubigeo").css("display","none");
                 $("#divpaises").css("display","block");
                 //visibility
                 //hidden
             
             }
          });
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
          $("#idacto_juridico").change(function(){
             verificarEstadoCampos($(this).val()); 
          });
          $("#idbien").change(function(){
                verificarEstadoCampoBienes($(this).val());
          });

          $( "#DocParticipante" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/clienteD.php',
                
                select: function( event, ui ) 
                {
                     $("#Participante").val(ui.item.nombres);
                     $("#IdParticipante").val(ui.item.idcliente);
                     $("#DocParticipante").val(ui.item.dni_ruc);
                     $("#Documento").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };

          $( "#Participante" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cliente.php',                
                select: function( event, ui )
                {
                     $("#Participante").val(ui.item.nombres);
                     $("#IdParticipante").val(ui.item.idcliente);
                     $("#DocParticipante").val(ui.item.dni_ruc);
                     $("#Documento").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            };

        $("#tabs,#pdt_notario").tabs({selected:0});
        $("#tabs-participantes").tabs({
           selected:0,
           select: function( event, ui ) {
              loadParticipacion((ui.index+1));
              if(ui.index==0) 
                {
                  $('#box-contenido').appendTo('#tabs-otorgante');
                  $("#text_tipo").empty().append("Otorgante: ");                  
                }
              if(ui.index==1) 
                {                   
                  $('#box-contenido').appendTo('#tabs-afavor'); 
                  $("#text_tipo").empty().append("A Favor: ");
                }
              if(ui.index==2)
              {
                 var htmlp = participantes.listp();
                 $("#list_participantes").empty().append(htmlp);
              }
           }
        });

        $("#Minuta_Fecha,#Plazoi,#Plazof,#FecFirmaE,#FechaPago,#EscrituraFecha,#fsalida,#fretorno").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            minDate: formatoMinDate,
            showButtonPanel: true
	});
        $("#fecha_construccion").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });



        //Code news
        $( "#DocParticipante_c" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/clienteD.php',
                
                select: function( event, ui ) 
                {
                     $("#Participante_c").val(ui.item.nombres);
                     $("#IdParticipante_c").val(ui.item.idcliente);
                     $("#DocParticipante_c").val(ui.item.dni_ruc);
                     $("#Documento_c").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };

          $( "#Participante_c" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cliente.php',                
                select: function( event, ui )
                {
                     $("#Participante_c").val(ui.item.nombres);
                     $("#IdParticipante_c").val(ui.item.idcliente);
                     $("#DocParticipante_c").val(ui.item.dni_ruc);
                     $("#Documento_c").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            }; 

            $("#conyuge").click(function(){
                verifConyuge();
            });
             $("#addParticipante").click(function(){addParticipante();});
             $("#addParticipantei").click(function(){addParticipantei();});


             $( "#DocParticipante_i" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/clienteD.php',
                
                select: function( event, ui ) 
                {
                     $("#Participante_i").val(ui.item.nombres);
                     $("#IdParticipante_i").val(ui.item.idcliente);
                     $("#DocParticipante_i").val(ui.item.dni_ruc);
                     $("#Documento_i").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.dni_ruc +" - " + item.nombres + "</a>" )
                    .appendTo( ul );
            };

          $( "#Participante_i" ).autocomplete({
                minLength: 0,
                source: '../../libs/autocompletar/cliente.php',                
                select: function( event, ui )
                {
                     $("#Participante_i").val(ui.item.nombres);
                     $("#IdParticipante_i").val(ui.item.idcliente);
                     $("#DocParticipante_i").val(ui.item.dni_ruc);
                     $("#Documento_i").val(ui.item.documento);
                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>"+ item.nombres + "</a>" )
                    .appendTo( ul );
            }; 
            $(".quit-p").live('click',function(){
                var i = $(this).attr("id");
                i = i.split("-");
                participantes.eliminar(i[1]);      
            });
});

function verifConyuge()
{  
  var ip = $("#IdParticipante").val();
  if(ip!="")
  {
     dp = $("#DocParticipante").val();
     t = dp.length;
     if(t==8)
     {
        if($('#conyuge').is(':checked')) $("#box-conyuge").show("fade");
         else $("#box-conyuge").hide("fade");
        $("#DocParticipante_c").focus();
     }
     else
     {
       alert("Solo se puede agregar conyuge a las personas naturales");
       $("#DocParticipante").focus();
       $("#conyuge").attr("checked",false);

     }
  }
  else
  {
     alert("Para agregar el conyuge, primero debe ingresar el otorgante.");
     $("#conyuge").attr("checked",false);
     $("#DocParticipante").focus();
  }
  
}

function verifPorcentaje()
{
    var vis = $("#box-porcentage").css("display");
    if(vis!="none")
    {
      var cidtp = $("#TipoParticipacion").val(),
              c = 100;
      $("#ListaMenu2 tbody").each(function(i,j)
      {         
         idtp = $("#IdParticipacionD"+(i+1)).val();
         if(idtp==cidtp)
         {            
            var oij = $("#porcentageD"+(i+1)).val();
            if(typeof(oij)!="undefined")            
              c -= parseFloat();
         }
      });
      if(c<0) c=0;      
      $("#Porcentage").val(c);
    }    
}
function AgregaParticipante()
{
    var  IdParticipante      = $("#IdParticipante").val();
         Participante        = $("#Participante").val(),
         Documento           = $("#Documento").val(),
         NumDocumento        = $("#DocParticipante").val(),
         IdTipoParticipacion = $("#TipoParticipacion").val(),
         porcentage          = $("#Porcentage").val();
    if(IdParticipante.length==0 || Participante.length==0 || Documento.length==0)
    {
        alert("Se necesita la información del participante");
        return false;
    }
    if(porcentage.length==0)
    {
      alert("Ingrese el porcentaje de participacion");
      $("#Porcentage").focus();
      return false;
    }
    if(IdTipoParticipacion==null)
    {
        alert("Se Necesita un Tipo de Participante");
        return false;
    }

    var TipoParticipacion   = document.getElementById("TipoParticipacion").options[document.getElementById("TipoParticipacion").selectedIndex].text;			
    nDest = nDest + 1;
    nDestC = nDestC + 1;

    var html = "<tr>";
    html += "<td align='center'><input type='hidden' name='0formD"  + nDestC + "_idkardex' value='"+idKardex+"' />" + Documento+"</td>";
    html += "<td><input name='0formD" + nDestC + "_idparticipante' id='IdParticipanteD" + nDestC + "' type='hidden' value='" + IdParticipante + "' />" + NumDocumento+"</td>";
    html += "<td>"+Participante+"</td>";
    html += "<td><input name='0formD" + nDestC + "_idparticipacion' id='IdParticipacionD" + nDestC + "' type='hidden' value='" + IdTipoParticipacion + "' />" + TipoParticipacion+"</td>";
    html += "<td align='center'><input name='0formD" + nDestC + "_porcentage' id='porcentageD" + nDestC + "' type='hidden' value='" + porcentage + "' />" + porcentage+"</td>";
    html += "<td align='center'><img src='../../imagenes/iconos/eliminar.png' width='16' height='16' onclick='QuitaParticipante(" + nDestC + ");' style='cursor:pointer'/></td>";           

    $("#ListaMenu2 tbody").append(html);
    $('#ConParticipantes').val(nDestC);		
    $('#IdParticipante').val('');
    $('#Participante').val('');
    $('#Documento').val('');
    $('#NumDocumento').val('');
    $('#DocParticipante').val('');		
    $('#DocParticipante').focus();
    verifPorcentaje();
	}
  function AgregaMedioPago()
  {
            var IdFormaPago         = $("#FormaPago").val();
            var IdMoneda            = $("#Moneda").val();
            var MontoPagado         = $("#MontoPagado").val();
            var FechaPago           = $("#FechaPago").val();
            var NroMontoPago        = $("#NroMedioPago").val();
            var IdEntidadFinanciera = $("#EntidadFinanciera").val();
            var FormaPago   = document.getElementById("FormaPago").options[document.getElementById("FormaPago").selectedIndex].text;			
            var Moneda   = document.getElementById("Moneda").options[document.getElementById("Moneda").selectedIndex].text;			
            var EntidadFinanciera   = document.getElementById("EntidadFinanciera").options[document.getElementById("EntidadFinanciera").selectedIndex].text;			
            if(IdFormaPago.length==0 || IdMoneda.length==0 || MontoPagado.length==0 || 
                FechaPago.length==0 || NroMontoPago.length==0 || IdEntidadFinanciera.length==0){
                alert("Seleccione los campos necesarios");
                return false;
            }
            nDestx = nDestx + 1;
            nDestCx = nDestCx + 1;
            var miTabla = document.getElementById('ListaMenu4').insertRow(nDestx);
            var celda1	= miTabla.insertCell(0);
            var celda2	= miTabla.insertCell(1);
            var celda3	= miTabla.insertCell(2);
            var celda4	= miTabla.insertCell(3);
            var celda5	= miTabla.insertCell(4);		
            var celda6	= miTabla.insertCell(5);		
            var celda7	= miTabla.insertCell(6);		
            celda1.innerHTML = "<input type='hidden' name='0formX"  + nDestCx + "_idkardex' value='"+idKardex+"' />"+"<input type='hidden' name='0formX"  + nDestCx + "_idforma_pago' value='" + IdFormaPago + "' />" + FormaPago;
            celda2.innerHTML = "<input type='hidden' name='0formX"  + nDestCx + "_idmoneda' value='" + IdMoneda + "' />" + Moneda;
            celda3.innerHTML = "<input type='hidden' name='0formX"  + nDestCx + "_montopagado' value='" + MontoPagado + "' />" + MontoPagado;
            celda4.innerHTML = "<input type='hidden' name='3formX"  + nDestCx + "_fechapago' value='" + FechaPago + "' />" + FechaPago;
            celda5.innerHTML = "<input type='hidden' name='0formX"  + nDestCx + "_nromediopago' value='" + NroMontoPago + "' />" + NroMontoPago;						
            celda6.innerHTML = "<input type='hidden' name='0formX"  + nDestCx + "_identidad_financiera' value='" + IdEntidadFinanciera + "' />" + EntidadFinanciera;						
            celda7.innerHTML = "<img class='quit' src='../../imagenes/iconos/eliminar.png' width='16' height='16' style='cursor:pointer' />";						
            $('#ConMedioPago').val(nDestCx);	
            $('#FormaPago').val('');
            $('#Moneda').val('');
            $('#MontoPagado').val('');
            $('#FechaPago').val('');
            $('#NroMedioPago').val('');		
            $('#EntidadFinanciera').val('');		
            $('#FormaPago').focus();
        }

function QuitaMedioPago(x)
{	
            var current = window.event.srcElement;   
            while ( (current = current.parentElement) && current.tagName !="TR");{
                current.parentElement.removeChild(current);
                nDestx = nDestx - 1;
            }
	}	
function QuitaParticipante(x){	
            var current = window.event.srcElement;   
            while ( (current = current.parentElement) && current.tagName !="TR");{
                current.parentElement.removeChild(current);
                nDest = nDest - 1;
            }
	}	
function CambiaFirmado(){
            if (document.getElementById('Firmado2').checked){
                $('#Firmado').val(1);
            }else{
                $('#Firmado').val(0);
            }
	}	
function NuevoParticipante(t)
{
    tipp = t;    
    $("#dnewCliente").load('../../parametros/cliente/MantenimientoA.php',function(){
        $("#dni_ruc").focus();
        $.getScript("../../parametros/cliente/script.js",function()
        {
            $("#dnewCliente").dialog("open");
            $("#dni_ruc").focus();
        });            
    });
	}
  
  $("#dnewCliente").dialog({
        autoOpen: false,                    
                    resizable:false,
                    title: "Nuevo Cliente",
                    height:550,
                    width: 750,
                    buttons: {
                        "Grabar": function() 
                        {
                            saveCliente();                            
                        },
                        "Cancelar": function() {
                            
                            $(this).dialog("close");
                        }
                    }
    });
  
function GuardarP(){
            if (ValidarP()){
                var RaZo2 = $('#RazonNombre2').val();
                var RaZo1 = $('#RazonNombre1').val();
                var Dir = $('#Direccion').val();			
                $('#RazonNombre2').val(RaZo2.toUpperCase());
                $('#RazonNombre1').val(RaZo1.toUpperCase());
                $('#Direccion').val(Dir.toUpperCase());			
                $.ajax({
                    url:'../../parametros/cliente/guardarP.php?Op=0',
                    type:'POST',
                    async:true,
                    data:$('#formP').serialize() + '&0formP_estado=1&0formP_idusuario='+id_user+'&3formP_fechareg='+FechaP,
                    success:function(data){
                        $("#Mensajes").html(data);
                        $("#IdParticipante").val($("#IdParticipanteC").val());
                        $("#Participante").val($("#RazonNombre2").val().toUpperCase() + ' ' + $("#RazonNombre1").val().toUpperCase());
                        $("#Documento").val("D.N.I.");
                        $("#DocParticipante").val($("#DniRuc").val());
                        $("#TipoParticipacion").val();					
                        $("#DivNuevoParticipante").html('');
                        $("#NuevoParticipante").dialog("close");
                        $("#ConfirmaGuardarParticipante").dialog("close");
                    }
                });
            }
}
function ValidarP(){
            return true;
	}	
function Cancelar(){
            window.location.href='index.php';
	}	
function CambiarArchivo(Archivo){
            $('#Archivo').val(Archivo);
	}	
function ValidarFormEnt(evt){
            var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
            if (keyPressed == 13 ){
                Guardar(Op);
            }
	}
function ocultarTR(){
            if($("input:radio[name='0form1_exmedpago']:checked'").val()==0){
                $("#forma_pago_tr1").css("display", "none");
                $("#ListaMenu4").css("display", "none");
            }else{
                $("#forma_pago_tr1").css("display", "inline");
                $("#ListaMenu4").css("display", "inline");
            }            
        }
var verificarEstadoCampos=function(id){
    var plazos=new Array(2,6,10);
    var mediospago=new Array(4,10);
    if(in_array(id,plazos)){
        $("#plazos").css("visibility","visible");
    }
    else{
        $("#plazos").css("visibility","hidden");
    }
    if(in_array(id,mediospago)){
        $("#mediospago").css("visibility","visible");
        ocultarTR();      
//        $("#forma_pago_tr1").css("display","block")
    }else{
        $("#mediospago").css("visibility","hidden")
        $("#forma_pago_tr1").css("display","none")
    }
}
var verificarEstadoCampoBienes=function(id){
    var codigosplacas=new Array(1,7,9);
    var numeroserie=new Array('05');
    var otro=new Array('99');
    var origen=new Array('04');
    if(in_array(id,codigosplacas)){
        $("#codigosplacas").css("display","block");
    }else{
        $("#codigosplacas").css("display","none");
    }
    if(in_array(id,numeroserie)){
        $("#serie").css("display","block");
    }else{
        $("#serie").css("display","none");
    }
    if(in_array(id,otro)){
        $("#otro").css("display","block");
    }else{
        $("#otro").css("display","none");
    }
    if(in_array(id,origen)){
        $("#origen").css("display","block");
    }else{
        $("#origen").css("display","none");
    }
    
}
function TraerDepartamento(IdUbigeo){           
$("#load-departamento").css("display","inline-block");            
        $.post('../../libs/departamento.php','IdUbigeo=' + IdUbigeo,function(data){                            
            $("#load-departamento").css("display","none");   
            $("#IdDepartamento2").empty().append(data);
            TraerProvincia(IdUbigeo);
        });
}
function TraerProvincia(IdUbigeo){	
  IdDep = $('#IdDepartamento2').val();
        $("#load-provincia").css("display","inline-block");   
        $.post('../../libs/provincia.php','IdUbigeo=' + IdUbigeo + '&IdDep=' + IdDep,function(data){                
            $("#load-provincia").css("display","none");
            $("#IdProvincia2").empty().append(data);
            TraerDistrito(IdUbigeo);
        });
}
function TraerDistrito(IdUbigeo){
    IdProv = $('#IdProvincia2').val();
        $("#load-distrito").css("display","inline-block");
        $.post('../../libs/distrito.php?Prefi=formP','IdUbigeo=' + IdUbigeo + '&IdProv=' + IdProv,function(data){
            $("#load-distrito").css("display","none");
            $("#IdDistrito2").empty().append(data);
        }); 
}	
/*
 * Logica de Bienes
 */
function nuevoBien(){
        
        $("#codigosplacas,#serie,#otro,#origen").css("display","none");
        $("#divpaises,#divubigeo").css("display","none");
        $(".origen").attr("checked",false);
        $(".tipo_bien[value='B']").attr("checked",true);
        $("#idbien,#idpais").val(0);
        $("#numero_codigoplacas,#numserie,#descotro,#ubigeo").val("");
        $("#fecha_construccion").val("//");
        TraerDepartamento('000000');
        $("#formBienes").dialog("open");
}

function validarFrmBienes()
{
    var nrotipobienes=$(".tipo_bien:checked").length;
    var idbien=$("#idbien").val();
    if(nrotipobienes==0){
        Mensaje("Aun no Se ha Seleccionado un Tipo de Bien <br/> Por favor Seleccione Uno","","",function(){
            
        });
    }
    else
    {
        var bval=true;
        bval=bval&&$("#idbien").combo();
        if(bval)
        {
            AgregarBien();
        }
    }
}
function AgregarBien()
{
    var tipo_bien=$(".tipo_bien:checked").val();
    var tipo_bien_nombre=(tipo_bien=='B')?'BIENES':'Acciones&nbsp;Y&nbsp;Derechos';
    var idbien=$("#idbien").val();
    var partida = $("#nropartida").val();
    var idzona = $("#idzona").val();
    var idbien_nombre = $("#idbien option:selected").html();
    var tipo_codigoplacas = $(".tipo_codigoplacas:checked").val();
    var tipo_codigoplacas_nombre = "";
    var codigosplacas_permitidos = new Array(1,7,9);
    if(in_array(idbien,codigosplacas_permitidos))
    {
        switch(parseInt(tipo_codigoplacas))
        {
            case 1:tipo_codigoplacas_nombre="Nº de placa";break;
            case 2:tipo_codigoplacas_nombre="Nº de serie";break;
            case 3:tipo_codigoplacas_nombre="Nº de motor";break;
            default:alert("Ningna de las Opciones Validas:"+tipo_codigoplacas);break;
        }
    }            
    var numero_codigoplacas=$("#numero_codigoplacas").val();
    var numserie=$("#numserie").val();
    var descotro=$("#descotro").val();
    var origen=$(".origen:checked").val();
    var origen_nombre="";
    if(origen!=undefined)
    {
        origen_nombre=(origen==1)?'NACIONAL':'EXTRANGERO';
    }
    else
    {
        origen=0;
    }
    var ubigeo=$("#ubigeo").val();
    var ubigeo_nombre="";
    if(ubigeo!=undefined||ubigeo!=null||ubigeo!="")
    {
        if($("#IdDistrito2 option:selected").html()!="SELECCIONE UN DISTRITO")
        {
            ubigeo_nombre=$("#IdDistrito2 option:selected").html();
        }
    }                
    var idpais=$("#idpais").val();
    var idpais_nombre=$("#idpais option:selected").val();
    var fecha_construccion=$("#fecha_construccion").val();
    var quitar = '<img class="img-quit" src="../../imagenes/iconos/eliminar.png" width="16" height="16" style="cursor:pointer">';
    if(fecha_construccion.length<10)fecha_construccion="01/01/1900";            
    nDestb = nDestb+ 1;
    nDestCb = nDestCb + 1;

    var $miTabla = $('#TablaBienes tbody');

    var variables_hidden = "";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_idkardex' value='"+idKardex+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_idbien' value='"+idbien+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_tipo_bien' value='"+tipo_bien+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_tipo_codigoplacas' value='"+tipo_codigoplacas+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_numero_codigoplacas' value='"+numero_codigoplacas+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_numserie' value='"+numserie+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_descotro' value='"+descotro+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_origen' value='"+origen+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_ubigeo' value='"+ubigeo+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_idpais' value='"+idpais+"' />";
    variables_hidden+="<input type='hidden' name='3formB"+nDestCb+"_fecha_construccion' value='"+fecha_construccion+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_nropartida' value='"+partida+"' />";
    variables_hidden+="<input type='hidden' name='0formB"+nDestCb+"_idzona' value='"+idzona+"' />";

    
    html = "<tr class='tr-body'>";
    html += "<td>"+tipo_bien_nombre+" "+variables_hidden+"</td>";
    html += "<td>"+idbien_nombre+"</td>";
    html += "<td>"+tipo_codigoplacas_nombre+"</td>";
    html += "<td>"+numero_codigoplacas+"</td>";
    html += "<td>"+numserie+"</td>";
    html += "<td>"+origen_nombre+"</td>";
    html += "<td>"+ubigeo_nombre+"</td>";
    html += "<td>"+descotro+"</td>";
    html += "<td>"+partida+"</td>";
    html += "<td>"+idzona+"</td>";
    html += "<td>"+quitar+"</td>";
    html += "</tr>";

    $miTabla.append(html);

    $('#ConBienes').val(nDestCb);	
    $("#formBienes").dialog("close");
}