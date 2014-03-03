<?php
    if(!session_start()){ session_start(); }
    $urlDir = $_SESSION["Ruta"];
    $TAMANO_PAGINA = 15;
//capturas la pagina en la q estas
    if (isset($_GET['pagina'])){
      $pagina = $_GET["pagina"];
    }else{
      $pagina = '';
    }
//si estas en la primera pagin ale asignas los valores iniciales
    if (!$pagina){
        $inicio = 0;
        $pagina = 1;
    }else{
        $inicio = ($pagina - 1) * $TAMANO_PAGINA;
    }
    function Cabecera($Previo='', $Tamano=700){
        global $urlDir, $Valor, $Op, $num_total_registros
?>
<link href="<?php echo $urlDir;?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urlDir;?>css/pagination.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $urlDir;?>js/FuncionesGrilla.js"></script>
<script type="text/javascript" src="<?php echo $urlDir;?>js/Funciones.js"></script>
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $urlDir;?>js/jquery.pagination.js"></script>
<script>
function Sobre(obj){
    obj.style.width=90;
}
function Fuera(obj){
    obj.style.width=85;
}	
</script>
<div align="center">
  <table width="<?php echo $Tamano;?>" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">
          <table width="700" border="0" cellspacing="0" cellpadding="0">
        <tr align="center" height="30">
          <td width="170"><img id="BtnNuevo" alt="Nuevo Registro" onclick="Operacion(0);" src="<?php echo $urlDir;?>images/btnNuevo.png" width="85" style="cursor:pointer" onmousemove="Sobre(this);" onmouseout="Fuera(this);"></td>
          <td width="170"><img id="BtnModificar" alt="Modificar Registro" onclick="Operacion(1);" src="<?php echo $urlDir;?>images/btnModificar.png" width="85" style="cursor:pointer" onmousemove="Sobre(this);" onmouseout="Fuera(this);"></td>
          <td width="170"><img id="BtnEliminar" alt="Eliminar Registro" onclick="Operacion(2);" src="<?php echo $urlDir;?>images/btnEliminar.png" width="85" style="cursor:pointer" onmousemove="Sobre(this);" onmouseout="Fuera(this);"></td>
          <td width="170"><img id="BtnCancelar" alt="Cancelar" onclick="Operacion(4);" src="<?php echo $urlDir;?>images/btnCancelar.png" width="85" style="cursor:pointer" onmousemove="Sobre(this);" onmouseout="Fuera(this);"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><?php echo $Previo;?></td>
    </tr>
    <tr>
      <td align="center">
      	<table width="<?php echo $Tamano;?>" border="0">
          <tr class="MantTitulo">
            <td width="360" valign="middle" align="left">Buscar :&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="text" name="Valor" id="Valor" style="width:300px" value="<?php echo $Valor?>" onkeyup="ValidarEnter(this, '<?php echo $Op;?>');"  class="inputtext"/>
            </td><td width="25"><img src="<?php echo $urlDir;?>images/lupa_mini.gif" width="20" height="20" border="0" onclick="Buscar('<?php echo $Op;?>');" style="cursor:pointer;"/></td>
            <td>Total Registros :
              <label id="TotalReg">
                <?php echo $num_total_registros;?>
              </label></td>
          </tr>
          <tr>
            <td colspan="3" valign="top">
                <div id="DivDetalle"></div>
                <table width="<?php echo $Tamano;?>" border="0" cellspacing="1">
                    <tr>
                        <td align="center" class="ListaMenutfoot" style="padding-left:10" >
                            <div id="Pagination" align="center"></div>
                        </td>
                    </tr>
                </table>
<?php
    }
    function Pie(){
        global $urlDir, $num_total_registros, $pagina, $total_paginas, $TAMANO_PAGINA, $Op;
        if ($Op==''){
            $Op=0;
        }
?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
      </td>
    </tr>
  </table>
</div>
<script>
//Paginaci�n
function pageselectCallback(page_index, jq){
    // Get number of elements per pagionation page from form
    var max_elem = Math.min((page_index+1) * <?php echo $TAMANO_PAGINA;?>, $('#NumReg').val());
    // Prevent click event propagation
    return false;
}		
function getOptionsFromForm(){
    var opt = {callback: pageselectCallback};
    // Collect options from the text fields - the fields are named like their option counterparts
    opt['items_per_page'] = <?php echo $TAMANO_PAGINA;?>;
    opt['num_display_entries'] = 5;
    opt['num_edge_entries'] = 1;
    opt['prev_text'] = "<< ";
    opt['next_text'] = " >>";
    opt['current_page'] = Pagina;
    opt['op'] = <?php echo $Op;?>;
    // Avoid html injections in this demo
    return opt;
}
</script>
<?php
    }
?>