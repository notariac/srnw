<?php 
if(!session_id()){ session_start(); }	
    include("../../libs/masterpage.php");	
    $Fecha = date('d/m/Y');	
    CuerpoSuperior("Listado de Escritura por Usuario");
    $meses = array('Ene','Feb','Mar','Abr','May','Jun','Ago','Sep','Oct','Nov','Dic');
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<style>
  p.title-head { font-size: 9px; font-weight: bold;}
  #tabla tbody tr td {font-size:10px; font-family:arial;}
</style>
<script>	
$(document).ready(function(){
  $("#fechai,#fechaf").datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true
  });

  $("#search").click(function(){
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
    $.get('data.php',str,function(data)
    {
      $("#load").hide('fade');
      $("#tabla tbody").empty().append(data);
    })
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
    // $.get('data.php',str,function(data)
    // {
    //   $("#load").hide('fade');
    //   $("#tabla tbody").empty().append(data);
    // });

  });

  $("#criterio").change(function(){
     var v = $(this).val();
     tooglebox(v);
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
</script>
<div class="ui-widget-content" style="padding:10px 10px 0px">
<h4 style="margin:0 0 4px">INDICES </h4>
<form name="form1" method="post" action="">
<fieldset class="ui-widget-content" style="display:inline-block; width:auto">
  <legend>Filtros</legend>
<label>Busqueda por: </label>
<select name="criterio" id="criterio" class="ui-widget-content">
  <option value="escritura">Escritura</option>
  <option value="correlativo">Kardex</option>
  <option value="servicio">Contrato</option>
  <option value="participantes">Participantes</option>
</select>

<div id="box" style="display:inline">
<span id="box-escritura">
  <label style="margin-left:20px">El numero de escritura: </label>
  <input type="text" name="escritura" id="escritura" value="" class="ui-widget-content ui-corner-all" style="width:50px; text-align:center"  />
</span>
<span id="box-correlativo" style="display:none">
  <label style="margin-left:20px">Ingrese el Numero de Kardex: </label>
  <input type="text" name="correlativo" id="correlativo" value="" class="ui-widget-content ui-corner-all" style="width:80px; text-align:center"  />
</span>
<span id="box-servicio" style="display:none">
  <label style="margin-left:20px">Ingrese el Servicio: </label>
  <input type="text" name="servicio" id="servicio" value="" class="ui-widget-content ui-corner-all" style="width:350px; text-align:center"  />
</span>
<span id="box-participantes" style="display:none">
  <label style="margin-left:20px">Ingrese los participantes: </label>
  <input type="text" name="participantes" id="participantes" value="" class="ui-widget-content ui-corner-all" style="width:350px; text-align:center"  />
</span>
</div>
</fieldset>
<fieldset id="box-time" class="ui-widget-content" style="display:inline-block; width:auto">
  <legend>Tiempo</legend>
  <label>Tipo: </label>
  <select name="tipo_time" id="tipo_time">
    <option value="1">Anual</option>
    <option value="2">Mensual</option>
    <option value="3">Fecha</option>
  </select>

  <span id="box-1">
  <label style="margin-left:10px">A&ntilde;os: </label>
  <select id="anio" name="anio" class="ui-widget-content ui-corner-all">
  <option value=""><?php echo utf8_decode("TODOS AÑOS"); ?></option>
      <?php 
        $anio = date('Y');
        for($i=$anio;$i>=1996;$i--)
        {
           ?>
           <option value="<?php echo $i ?>"><?php echo $i ?></option>
           <?php
        }
      ?>
  </select>
  </span>
  <span id="box-2" style="display:none">
    <label style="margin-left:20px">Meses entre: </label>
    <select name="mesi" id="mesi">
      <?php foreach ($meses as $key => $value) {
          ?>
          <option value="<?php echo str_pad($key+1, 2,'0',0); ?>"><?php echo $value; ?></option>
          <?php
      } ?>
    </select>
    <input type="text" name="anioi" id="anioi" value="<?php echo date('Y'); ?>" style="width:40px;" class="ui-widget-content ui-corner-all " />

    y

    <select name="mesf" id="mesf">
      <?php foreach ($meses as $key => $value) {
          ?>
          <option value="<?php echo str_pad($key+1, 2,'0',0); ?>"><?php echo $value; ?></option>
          <?php
      } ?>
    </select>
    <input type="text" name="aniof" id="aniof" value="<?php echo date('Y'); ?>" style="width:40px;" class="ui-widget-content ui-corner-all " />

  </span>
  <span id="box-3" style="display:none">
    
    <label style="margin-left:10px">Fecha Inicial: </label> 
    <input type="text" name="fechai" id="fechai" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all " style="width:80px; text-align:center" />
    <label style="margin-left:10px">Fecha Final: </label> 
    <input type="text" name="fechaf" id="fechaf" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all " style="width:80px; text-align:center" />
  </span>
</fieldset>
<input type="button" name="search" id="search"  value="Buscar" class="" />
<input type="button" name="print" id="print"  value="Imprimir" class="" />
</form>
</div>
<div id="load" style="text-align:center; display:none">Buscando...</div>
<div class="contain">
   <table id="tabla" class="ui-widget-content">
    <thead class="ui-widget-header">
        <tr class="ui-widget-header">
          <th rowspan="2" scope="col" >&nbsp;</th>
          <th rowspan="2" style="width:50px" scope="col"><p class="title-head">KARDEX</p></th>
          <th rowspan="2" width="20"><p class="title-head">ESCRITURA</p></th>
          <th rowspan="2"><p class="title-head">MINUTA</p></th>
          <th rowspan="2" style="width:50px"><p class="title-head">FOJAS INI</p></th>
          <th rowspan="2" style="width:50px"><p class="title-head">FOJAS FIN</p></th>
          <th rowspan="2"><p class="title-head">DIG.</p></th>
          <th rowspan="2" style="width:70px"><p class="title-head">FECHA ESCRITURA</p></th>
          <th rowspan="2"><p class="title-head">CONTRATO</p></th>          
          <th rowspan="2"><p class="title-head">PARTICIPANTES</p></th>          
          <th colspan="4" style="border-left:2px solid #999;" class="ui-widget-content"><p class="title-head">INSCRIPCION</p></th>
        </tr>
        <tr>
          <th style="width:80px;border-left:2px solid #999;" class="ui-widget-content"><p class="title-head">ASIENTO</p></th>
          <th style="width:80px;" class="ui-widget-content"><p class="title-head">PARTIDA</p></th>
          <th class="ui-widget-content"><p class="title-head">ANOTACIONES DE INSCRIPCION</p></th>
          <th style="width:60px" class="ui-widget-content"><p class="title-head">FECHA</p></th>          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
</div>
<?php
CuerpoInferior();
?>
