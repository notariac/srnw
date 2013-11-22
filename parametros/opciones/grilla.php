<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    $FormatoGrilla = $_SESSION['Formato'];
    $Sql    = $FormatoGrilla[0];
    $Campos = $FormatoGrilla[1];
    $Valor  = trim($_POST['Valor']);
    $Campo  = $Conn->PreparaSQL($Campos, $Valor);
    $Sql    = $Sql.$Campo;
    $Sql    = $Sql.$FormatoGrilla[8];
   //echo $Sql;
    $n = ") ILIKE '%$Valor%' )";
    $pos = strpos($Sql, $n);
    if(isset($_SESSION['super_usuario']) && $_SESSION['super_usuario']==8){
        $ConsultaX = $Conn->Query("SELECT * FROM notaria WHERE idnotaria != '".$_SESSION['notaria']."' ORDER BY idnotaria ASC");
        while($cx = $Conn->FetchArray($ConsultaX)){
            $Cq = $Cq." OR ".substr($Campo, 6)." AND notaria.idnotaria='".$cx[0]."' ";
        }
    }else{
        $Cq="";
    }	
    $Sql = substr_replace($Sql," AND notaria.idnotaria='".$_SESSION["notaria"]."' ".$Cq,$pos+strlen($n),0);    
    //echo $Sql;
    $Consulta = $Conn->Query($Sql);
    $num_total_registros = $Conn->NroRegistros($Consulta);
    $TAMANO_PAGINA = $FormatoGrilla[6]['TP'];
    $total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);	
    $pagina     = $_POST['Pagina'];
    if ($pagina<=$total_paginas){
        $Pag    = ($pagina - 1)*$TAMANO_PAGINA;
    }else{
        $Pag    = 0;
        $pagina = 1;
    }
    $Sql = $Sql." LIMIT ".$FormatoGrilla[6]['TP']." OFFSET ".$Pag;
    $Consulta = $Conn->Query($Sql);
    $Tamano = $FormatoGrilla[7];
?>
<table width="<?php echo $Tamano;?>" border="0" cellspacing="1" bordercolor="#000" bgcolor="#ECECEC" id="ListaMenu">
<thead>
    <tr title="Cabecera" height="25">
<?php
    for ($i=1; $i<=$Conn->NroColumnas($Consulta)-1; $i++){
?>
    <th width="<?php echo $FormatoGrilla[5]['W'.$i]?>" scope="col"><?php echo $FormatoGrilla[3]['T'.$i]?></th>
<?php
    }
    if ($FormatoGrilla[9]['Id']!=''){
?>
        <th scope="col">&nbsp;</th>
<?php			
    }
?>
    </tr>
</thead>
<tbody>
<?php
    $NumRegs = 0;
    while($row = $Conn->FetchArray($Consulta)){
        $NumRegs = $NumRegs + 1;
?>
    <tr id="<?php echo $row[0];?>" onmouseover="color_over(this);" onmouseout="color_out(this);" style="background-color:#FFF;">
<?php
        for ($i=1; $i<=$Conn->NroColumnas($Consulta)-1; $i++){
?>
        <td align="<?php echo $FormatoGrilla[4]['A'.$i];?>" valign="middle" style="padding-left:5px; padding-right:5px;"><?php echo strtoupper($row[$i-1]);?></td>
<?php
        }		
        if ($FormatoGrilla[9]['Id']!=''){
?>
        <td valign="middle" style="padding-left:5px; padding-right:5px; width:<?php echo $FormatoGrilla[9]['NB']*12; ?>px">
<?php			
        for ($ii=1; $ii<=$FormatoGrilla[9]['NB']; $ii++){
            if ($row[$FormatoGrilla[9]['BtnCI'.$ii]-1]==$FormatoGrilla[9]['BtnCV'.$ii]){
?>
            <img width="15" id="<?php echo $row[0];?>" src="<?php echo $_SESSION["urlDir"].'imagenes/iconos/'.$FormatoGrilla[9]['BtnI'.$ii];?>" <?php echo $FormatoGrilla[9]['BtnF'.$ii];?> title="<?php echo $FormatoGrilla[9]['Btn'.$ii];?>" <?php echo $FormatoGrilla[9]['BtnF'.$ii];?> style="cursor:pointer;"/>
<?php
            }
        }
?>
        </td>
<?php
        }
?>
    </tr>
<?php
    }
    $registros = $num_total_registros - ($Pag);
    $NumColl = $Conn->NroColumnas($Consulta);
    if ($FormatoGrilla[9]['Id']!=''){ $NumColl = $NumColl + 1; }
    for ($i = $registros+1; $i <= $TAMANO_PAGINA; $i++){
?>
            <tr style="cursor:default;" bgcolor="#ECECEC"><td colspan="<?php echo $NumColl;?>">&nbsp;</td></tr>
<?php
    }
?>
    <tr>
        <th colspan="<?php echo $NumColl;?>"><div id="Pagination" align="center"></div></th>
    </tr>
</tbody>
</table>
<input type="hidden" id="NumReg" value="<?php echo $num_total_registros;?>">
<input type="hidden" id="Pagina" value="<?php echo $pagina;?>">
<input type="hidden" id="TotalPaginas" value="<?php echo $total_paginas;?>">