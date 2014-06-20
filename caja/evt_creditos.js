$(document).ready(function()
{
  $("#q").focus();
  $("#fechai,#fechaf,#fechaic,#fechafc").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
  });

  $("#box-frm-pay").dialog({
    title: 'Pagos',
    modal:true,
    width:680,
    resizable:true,
    autoOpen:false,
    buttons: {
      'Cerrar':function(){
        $(this).dialog('close');
      }
    }
  });

  $("#box-frm-dr").dialog({
    title: 'Derechos Registrales',
    modal:false,
    width:300,
    resizable:false,
    autoOpen:false,
    buttons: {
      'Cerrar':function(){
        $(this).dialog('close');
      },
      'Grabar': function(){
        saveDR();
      }
    }
  });
  $(".delete_dr").live('click',function(){
     iddr = $(this).attr("id");
     deleteDR(iddr);
  })
  $('.btn-pay').live('click',function(){
    var param = $(this).attr("Id");  
      param = param.split("-")  ;
      id = param[0];
      item = param[1];
     $.get('frm_creditos_pay.php','idfacturacion='+id+'&item='+item,function(data){
        $("#box-frm-pay").empty().append(data);
        $("#box-frm-pay").dialog('open');
        getListPay();
        $("#fecha_pago").datepicker({
          dateFormat: 'dd/mm/yy',
          changeMonth: true,
          changeYear: true
        });
     })
  });

  $('#btn-dr').live('click',function(){
      $("#box-frm-dr").dialog('open');
  })
 
  $("#idforma_pago").live('change',function(){
     var i = $(this).val();
     if(i!="")
     {
       if(i==2||i==5)
       {
          $("#box-datos1").css("display","block");
          if(i==2)        
            $("#label-doc").html('Nro Cheque: ');
          else
            $("#label-doc").html('Nro Cta: ');
       }
       else
       {
          $("#box-datos1").css("display","none");
       }
       $("#monto").focus();
     }     
  });
  $("#add-pay").live('click',function(){
      addPay();
  })
  $("#search").click(function()
  {
      loadGrid();
  });
  $("#export").click(function()
  {
      exportar();
  });

  $("#print").click(function(){
    var v = $("#criterio").val(),
        str = v+"="+$("#"+v).val();       
        tt = $("#tipo_time").val(),
        str_t = "";

    switch(parseInt(tt))
    {
      case 1: str_t = "&anio="+$("#anio").val(); break;
      case 2: str_t = "&mesi="+$("#mesi").val()+"&anioi="+$("#anioi").val()+"&mesf="+$("#mesf").val()+"&aniof="+$("#aniof").val();break;
      case 3: str_t = "&fechai="+$("#fechai").val()+"&fechaf="+$("#fechaf").val(); break;
      default: break;
    }
    str = str+str_t+"&tt="+tt;
    $("#load").show('fade');
    window.open('test.html');
  });

  $("#criterio").change(function(){
     //var v = $(this).val();
     //tooglebox(v);
     $("#q").focus();
  });
  $("#tipo_time").change(function(){
    var v = $(this).val();
    toogleTime(v);
  })
})
function Enter(event)
{
  var keyPressed = (evt.which) ? evt.wich : event.keycode
	if(keypressed==13)
	{
		seach();
	}
}
function tooglebox(v)
{
   var visible = "box-"+v;
   $("#box span").each(function(i,j)
   {
      var id = $("#box").find('span:eq('+i+')').attr("id");
      if(id==visible) { $("#box").find('span:eq('+i+')').show("slow"); $("#"+v).focus(); }
        else { $("#box").find('span:eq('+i+')').hide(); }
   });
}

function toogleTime(v)
{
   var visible = "box-"+v;
   $("#box-time span").each(function(i,j)
   {
      var id = $("#box-time").find('span:eq('+i+')').attr("id");
      if(id==visible) { $("#box-time").find('span:eq('+i+')').show("slow"); $("#"+v).focus(); }
        else { $("#box-time").find('span:eq('+i+')').hide(); }
   })
}

function addPay()
{
   if(validar())
   {
      var str = $("#frm-pay").serialize();
      $.post('creditos_process.php',str+'&oper=1',function(r){
          if(r[0]==0)
          {
             getListPay();
             loadGrid();
          }
          else
          {
             alert(r[1]);
          }
      },'json');
   }
}

function validar()
{
   var bval = true;
       bval = bval && $("#idforma_pago").required();
       bval = bval && $("#monto").required();
  if(bval)
  {
    var monto = parseFloat($("#monto").val());
    if(monto<=0)
    {
       if(!confirm("Estas seguro de registrar un pago por 0.00 S/. y para Tramites Registrales "+$("#monto_tr").val()))
       {
          $("#monto").focus();
          bval = false;
       }       
       
    }
    if(bval)
    {
        var idfp = $("#idforma_pago").val();
        if(idfp==2||idfp==5)
        {
          bval = bval && $("#nrodocumento").required();
          bval = bval && $("#identidad_financiera").required();
        }
    }    
  }
  return bval;
}
function getListPay()
{
  var idf = $("#idfacturacion").val();
  $.post('creditos_process.php','idfacturacion='+idf+'&oper=2',function(data){      
      $("#tabla-pay tbody").empty().append(data);
  });
}

function loadGrid()
{
  var v = $("#criterio").val(),
        str = "criterio="+v+"&q="+$("#q").val(),       
        tt = $("#tipo_time").val(),
        str_t = "";

    switch(parseInt(tt))
    {
      case 1: str_t = "&anio="+$("#anio").val(); break;
      case 2: str_t = "&mesi="+$("#mesi").val()+"&anioi="+$("#anioi").val()+"&mesf="+$("#mesf").val()+"&aniof="+$("#aniof").val();break;
      case 3: str_t = "&fechai="+$("#fechai").val()+"&fechaf="+$("#fechaf").val(); break;
      default: break;
    }
    var estado = $("#estado").val();
    str = str+str_t+"&tt="+tt+"&estado="+estado;
    $("#load").show('fade');
    $.get('creditos_data.php',str,function(data)
    {
      $("#load").hide('fade');
      $("#tabla tbody").empty().append(data);
    })
}

function exportar()
{
  var v = $("#criterio").val(),
        str = "criterio="+v+"&q="+$("#q").val(),       
        tt = $("#tipo_time").val(),
        str_t = "";

    switch(parseInt(tt))
    {
      case 1: str_t = "&anio="+$("#anio").val(); break;
      case 2: str_t = "&mesi="+$("#mesi").val()+"&anioi="+$("#anioi").val()+"&mesf="+$("#mesf").val()+"&aniof="+$("#aniof").val();break;
      case 3: str_t = "&fechai="+$("#fechai").val()+"&fechaf="+$("#fechaf").val(); break;
      default: break;
    }
    var estado = $("#estado").val();
    str = str+str_t+"&tt="+tt+"&estado="+estado;
    popup('creditos_data_excel.php?'+str,500,500);
}
function saveDR()
{
    var str = $("#frm-dr").serialize();
    var idf = $("#idfacturacion").val();
    $.post('creditos_process.php',str+'&idf='+idf+'&oper=3',function(data){
         $.get('frm_creditos_pay.php','idfacturacion='+idf,function(data){
              $("#box-frm-pay").empty().append(data);
              $("#box-frm-pay").dialog('open');
              getListPay();
              $("#fecha_pago").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
              });
           });
         $("#box-frm-dr").dialog('close');
    })
}

function deleteDR(iddr)
{

    var idf = $("#idfacturacion").val();
    $.post('creditos_process.php','&iddr='+iddr+'&oper=4',function(data){
         $.get('frm_creditos_pay.php','idfacturacion='+idf,function(data){
              $("#box-frm-pay").empty().append(data);
              $("#box-frm-pay").dialog('open');
              getListPay();
              $("#fecha_pago").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
              });
           });
         $("#box-frm-dr").dialog('close');
    })
}
function popup(url,width,height){cuteLittleWindow = window.open(url,"littleWindow","location=no,width="+width+",height="+height+",top=80,left=300,scrollbars=yes"); }