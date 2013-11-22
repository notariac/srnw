<?php 
if(!session_id()){ session_start(); }
include("../../libs/masterpage.php");	
include("../../config.php");	
$Consultaf	= $Conn->Query("Select now()");
$rowf	= $Conn->FetchArray($Consultaf);
$Fech	= $Conn->DecFecha(substr($rowf[0], 0, 10));	
CuerpoSuperior("Consulta de Comprobantes de Caja");
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/Funciones.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
    $("#generar").click(function(){
         var str = $("#form1").serialize();
         $("#loadgin").css("display","inline");
        $.get('impresion.php',str,function(d){
          $("#loadgin").css("display","none");
          $("#enlace").attr("href",d);
          $("#download").show("slow");  
        });        
    })
  })
</script>
<div style="width:322px; margin:0 auto;">
<h3 class="ui-widget-header" style="padding:5px; text-align:center; margin:0">Generacion de Libros Electronicos (PLE)</h3>
<div class="ui-widget-content ui-corner-bottom" style="width:300px; margin:0 auto; padding:10px;">
<form name="form1" id="form1" method="post" action="">
    <label for="anio">Periodo: </label>
    <input type="text" name="anio" id="anio" value="<?php echo date('Y'); ?>" size="4" maxlength="4"/>
    <?php $meses = array('1'=>'Enero',
                         '2'=>'Febrero',
                         '3'=>'Marzo',
                         '4'=>'Abril',
                         '5'=>'Mayo',
                         '6'=>'Junio',
                         '7'=>'Julio',
                         '8'=>'Agosto',
                         '9'=>'Septiembre',
                         '10'=>'Octubre',
                         '11'=>'Noviembre',
                         '12'=>'Diciembre');
     ?>
      <select name="mes" id="mes" class="select_1">
        <?php 
          $cm = date('m');
          foreach($meses as $i => $m)
          {
            $s = "";
            if($i==$cm){$s="selected";}
            ?>
            <option <?php echo $s; ?> value="<?php echo $i; ?>"><?php echo $m; ?></option>
            <?php
          }
        ?>
      </select>
      <input type="button" name="generar" id="generar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="Generar"/>
      <span id="loadgin" style="display:none">Generando ...</span>
      <p style="text-align:center; display:none" id="download">
        <a href="#" id="enlace" style="text-decoration:none">
          <img src="../../png/download.png" style="border:none"/>          
          <span style="color:green; text-align:center; display:block; boder:0;">Descargar el Archivo</span>
      </a></p>
</form>
</div>
</div>
<?php
    CuerpoInferior();
?>