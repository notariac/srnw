<?php
    if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php"); 
    include_once '../../libs/funciones.php';
    $TituloVentana = "Registro de Operaciones";
    CuerpoSuperior($TituloVentana);    
?>
<link href="<?php echo $urlDir;?>css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
  function popup(url,width,height){cuteLittleWindow = window.open(url,"littleWindow","location=no,width="+width+",height="+height+",top=80,left=300,scrollbars=yes"); }
  $(document).ready(function(){
    var h = $("#div-rows").height();
    //
    $("#fechai,#fechaf").datepicker({'dateFormat':'dd/mm/yy'});
    $("#gen").click(function(){
        $("#DivContenido").css({"height":"auto"});
        $("#TdContenido").css({"height":"auto"});        
       var str = $("#frm-ro").serialize();
       $.get('data.php',str,function(data){
           w = $(document).width();
           $("#div-rows").css("width",w-55);
           $("#div-rows").empty().append(data);
       });
    });
    $("#gen-excel").click(function(){
        var str = $("#frm-ro").serialize();
        popup('excel.php?'+str,500,500);
    });
  })
</script>
<div>
  <h2 class="ui-widget-header ui-corner-all" style="padding:5px 0; text-align:center">Registro de Operaciones</h2>
  <fieldset class="ui-widget-content ui-corner-all">
    <legend>Parametros para Reporte</legend>
    <form name="frm-ro" id="frm-ro">
      <label for="fechai">Fecha Inicial: </label>
      <input type="text" name="fechai" id="fechai" value="<?php echo date('d/m/Y'); ?>" class="ui-widget-content ui-corner-all text" size="10" style="text-align:center;" />
      <label for="fechaf">Fecha Final: </label>
      <input type="text" name="fechaf" id="fechaf" value="<?php echo date('d/m/Y'); ?>" class="ui-widget-content ui-corner-all text" size="10" style="text-align:center;" />    
      <input type="button" name="gen" id="gen" value="Generar" />
      <input type="button" name="gen-excel" id="gen-excel" value="Excel" />
    </form>
  </fieldset>
  <div id="div-rows" class="ui-corner-all" style="overflow:scroll; margin-left:2px; height:450px; margin-top:10px; ">
  </div>
</div>
<?php
  CuerpoInferior();
?>