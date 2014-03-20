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

  $('.btn-pay').live('click',function(){
    var id = $(this).attr("Id");    
     $.get('frm_creditos_pay.php','idfacturacion='+id,function(data){
        $("#box-frm-pay").empty().append(data);
        $("#box-frm-pay").dialog('open');
        getListPay();
     })
  });

 
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
   })
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
       alert("El monto debe ser mayor que cero (0).");
       $("#monto").focus();
       bval = false;
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
    str = str+str_t+"&tt="+tt;
    $("#load").show('fade');
    $.get('creditos_data.php',str,function(data)
    {
      $("#load").hide('fade');
      $("#tabla tbody").empty().append(data);
    })
}