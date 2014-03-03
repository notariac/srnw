<?php 
include("../libs/masterpage.php");
include("../config.php");		
CuerpoSuperior("Sistema Inform&aacute;tico de Registro Notarial - Migrador");
?>
<div align="center">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="center">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
        <thead>
        <tr>
          <td style="font-size:18px" align="center" height="30">Migrador de Datos</td>
        </tr>
        </thead>
      </table>
    </td>
  </tr>
  <tr>
    <td width="440">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:10px">Profesi&oacute;n</td>
    <td><iframe src="profesion/index.php" width="260" height="20" frameborder="0" scrolling="no"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Ocupaci&oacute;n</td>
    <td><iframe src="ocupacion/index.php" width="260" height="20" frameborder="0" scrolling="no"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Servicios</td>
    <td><iframe src="servicio/index.php" width="260" height="20" frameborder="0" scrolling="no"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Tipo de Participaci&oacute;n</td>
    <td><iframe src="participacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Tipo de Participaci&oacute;n por Servicio </td>
    <td><iframe src="servicio_participacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Situaci&oacute;n (Envios SUNARP) </td>
    <td><iframe src="situacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Ocurrencias (Envios Cartas)</td>
    <td><iframe src="ocurrencia/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Clientes</td>
    <td><iframe src="cliente/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Representantes por Cliente</td>
    <td><iframe src="cliente_representante/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Atenci&oacute;n</td>
    <td><iframe src="atencion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Cartas</td>
    <td><iframe src="carta/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Kardex</td>
    <td><iframe src="kardex/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Kardex - Participantes </td>
    <td><iframe src="kardex_participantes/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Kardex - Derivaci&oacute;n </td>
    <td><iframe src="kardex_derivacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Kardex - Derivaci&oacute;n Situacion </td>
    <td><iframe src="kardex_derivacion_situacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Tipo de Numeraci&oacute;n </td>
    <td><iframe src="libro_numeracion_tipo/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Tipo de Libro</td>
    <td><iframe src="libro_tipo/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Libros</td>
    <td><iframe src="libro/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td style="padding-left:10px">Facturaci&oacute;n</td>
    <td><iframe src="facturacion/index.php" width="260" height="20" frameborder="0" scrolling="No"></iframe></td>
  </tr>
  <tr>
    <td colspan="2">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
        <thead>
            <tr>
                <td style="font-size:18px" align="center" height="30">&nbsp;</td>
            </tr>
        </thead>
      </table>	
    </td>
  </tr>
</table>
</div>
<?php
    CuerpoInferior(); 
?>