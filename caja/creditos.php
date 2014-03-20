<?php 
if(!session_id()){ session_start(); }	
    include("../libs/masterpage.php");	
    $Fecha = date('d/m/Y');	
    CuerpoSuperior("Listado de Escritura por Usuario");
    $meses = array('Ene','Feb','Mar','Abr','May','Jun','Ago','Sep','Oct','Nov','Dic');
?>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<link href="css_creditos.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/required.js"></script>
<script language="JavaScript" src="evt_creditos.js" type="text/javascript"></script>
<div class="ui-widget-content" style="padding:10px 10px 0px">
<h4 style="margin:0 0 4px">FACTURAS LECO - CREDITOS </h4>
<form name="form1" method="post" action="">
<fieldset class="ui-widget-content" style="display:inline-block; width:auto">
  <legend>Filtros</legend>
<label>Busqueda por: </label>
<select name="criterio" id="criterio" class="ui-widget-content">
  <option value="1">Cliente (Raz&oacute;n Social)</option>
  <option value="2">Cliente (RUC)</option>
  <option value="3">N&deg; Factura Leco</option>  
</select>
<div id="box" style="display:inline">
<span id="">
  <label style="margin-left:5px">&nbsp;: </label>
  <input type="text" name="q" id="q" value="" class="ui-widget-content ui-corner-all" style="width:270px; text-align:center"  />
</span>
</div>
</fieldset>
<fieldset id="box-time" class="ui-widget-content" style="display:inline-block; width:auto">
  <legend>Fecha Emisi&oacute;n</legend>
  <label>Tipo: </label>
  <select name="tipo_time" id="tipo_time">    
    <option value="1">Anual</option>
    <option value="2">Mensual</option>    
    <option value="3">Fecha</option>
  </select>
  <span id="box-1" >
  <label style="margin-left:10px" style="display:none">A&ntilde;os: </label>
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
    <label style="margin-left:10px">Del: </label> 
    <input type="text" name="fechai" id="fechai" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all " style="width:80px; text-align:center" />
    <label style="margin-left:10px">al: </label> 
    <input type="text" name="fechaf" id="fechaf" value="<?php echo date('d/m/Y') ?>" class="ui-widget-content ui-corner-all " style="width:80px; text-align:center" />
  </span>
<input type="button" name="search" id="search"  value="Buscar" class="" />
<input type="button" name="export" id="export"  value="a Excel" class="" />
</form>
</div>
<div id="load" style="text-align:center; display:none">Buscando...</div>
<div class="contain">
   <table id="tabla" class="ui-widget-content" width="100%">
    <thead class="ui-widget-header">
        <tr class="ui-widget-header">
          <th rowspan="2" scope="col" >&nbsp;</th>
          <th rowspan="2" style="width:70px"><p class="title-head">N&deg; DE TICKET</p></th>
          <th rowspan="2" style="width:70px" scope="col"><p class="title-head">FECHA EMISION</p></th>
          <th rowspan="2" style="width:70px" scope="col"><p class="title-head">FECHA CANCELACION</p></th>
          <th rowspan="2" style="width:100px"><p class="title-head">N&deg; FACTURA LECO</p></th>          
          <th rowspan="2"  ><p class="title-head">RUC CLIENTE</p></th>
          <th rowspan="2" style=""><p class="title-head">RAZON SOCIAL (CLIENTE)</p></th>                    
          <th rowspan="2" style=""><p class="title-head">SERVICIO</p></th>                    
          <th rowspan="2" style="width:70px"><p class="title-head">MONTO S/.</p></th>      
          <th rowspan="2" style="width:70px"><p class="title-head">TOTAL FACT. S/.</p></th>      
          <th rowspan="2" style="width:70px"><p class="title-head">MONTO PAGADO S/.</p></th>      
          <th rowspan="2" style="width:70px"><p class="title-head">MONTO PENDIE. S/.</p></th>
          <th rowspan="2" style="width:50px"><p class="title-head">ESTADO</p></th>
          <th rowspan="2" style="width:20px" ><p class="title-head">&nbsp;</p></th>      
          <th rowspan="2" style="width:30px" scope="col" >&nbsp;</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
</div>
<div id="box-frm-pay"></div>
<?php
CuerpoInferior();
?>
