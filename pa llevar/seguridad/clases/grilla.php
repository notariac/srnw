<?php
if(!session_id()){ session_start(); }
    include('../config.php');
    $FormatoGrilla = $_SESSION['Formato'];
    $Sql    = $FormatoGrilla[0];
    $Campos = $FormatoGrilla[1];
    $Valor  = $_POST['Valor'];
    $Campo  = $Conn->PreparaSQL($Campos, $Valor);
    $Sql    = $Sql.$Campo;
    $Consulta = $Conn->Query($Sql);
    $num_total_registros = $Conn->NroRegistros($Consulta);
    $TAMANO_PAGINA = $FormatoGrilla[6]['TP'];
    $pagina = $_POST['Pagina'];
    $Pag = ($pagina - 1)*$TAMANO_PAGINA;
    $total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);
    $Sql = $Sql.$FormatoGrilla[8]." LIMIT ".$FormatoGrilla[6]['TP']." OFFSET ".$Pag;
    $Consulta = $Conn->Query($Sql);
    $Tamano = $FormatoGrilla[7];
?>
<table width="<?php echo $Tamano;?>" border="0" cellspacing="1" bordercolor="#000000" bgcolor="#E6E6E6" id="ListaMenu">
<thead>
    <tr title="Cabecera" height="25">
<?php
    for ($i=1; $i<=$Conn->NroColumnas($Consulta); $i++){
?>
        <th width="<?php echo $FormatoGrilla[5]['W'.$i];?>" scope="col"><?php echo $FormatoGrilla[3]['T'.$i];?></th>
<?php
    }
?>
    </tr>
</thead>
<tbody>
<?php
    $NumRegs 	= 0;
    while($row = $Conn->FetchArray($Consulta)){
        $NumRegs = $NumRegs + 1;
?>
    <tr id="<?php echo $row[0];?>" onclick="SeleccionaId(this);" onmouseover="color_over(this);" onmouseout="color_out(this);" style="background-color:#FFF;">
<?php
        for ($i=1; $i<=$Conn->NroColumnas($Consulta); $i++){
?>
        <td align="<?php echo $FormatoGrilla[4]['A'.$i];?>" valign="middle" style="padding-left:5px; padding-right:5px;"><?php echo strtoupper($row[$i-1]);?></td>
<?php
        }
?>
    </tr>
<?php
    }
    $registros = $num_total_registros - ($TAMANO_PAGINA*($pagina-1));
    for ($i = $registros+1; $i <= $TAMANO_PAGINA; $i++){
?>
            <tr style="cursor:default;" colspan="<?php echo $Conn->NroColumnas($Consulta);?>" ><td>&nbsp;</td></tr>
<?php
    }
?>
</tbody>
</table>
<input type="hidden" id="NumReg" value="<?php echo $num_total_registros;?>">
<input type="hidden" id="Pagina" value="<?php echo $pagina;?>">
<input type="hidden" id="TotalPaginas" value="<?php echo $total_paginas;?>">