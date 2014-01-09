<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    $FormatoGrilla = $_SESSION['Formato'];
    $Sql    = $FormatoGrilla[0];
    $Campos = $FormatoGrilla[1];
    $Valor  = trim($_POST['Valor']);
    $Anio   = $_POST['Anio'];
    $Campo  = $Conn->PreparaSQL($Campos, $Valor);
    if ($Anio!=''){
            $Campo = $Campo." AND kardex.anio='".$Anio."'";
    }
    $Sql    = $Conn->VerificarSQL($Sql.$Campo);
    $n = ") ILIKE '%$Valor%' )";
    $pos = strpos($Sql, $n);
    
    $Sql = substr_replace($Sql," AND kardex.idnotaria='".$_SESSION['notaria']."' AND (kardex.estado=1 or kardex.estado=2) ",$pos+strlen($n),0);   
    $Consulta = $Conn->Query($Sql);
    $num_total_registros = $Conn->NroRegistros($Consulta);
    $TAMANO_PAGINA = $FormatoGrilla[6]['TP'];
    $total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);	
    $pagina = $_POST['Pagina'];
    if ($pagina<=$total_paginas)
    {
        $Pag = ($pagina - 1)*$TAMANO_PAGINA;
    }
    else
    {
        $Pag = 0;
        $pagina = 1;
    }
    $Sql = $Sql.$FormatoGrilla[8]." LIMIT ".$FormatoGrilla[6]['TP']." OFFSET ".$Pag;
    $Consulta = $Conn->Query($Sql);
    $Tamano = $FormatoGrilla[7];
?>
<table width="<?php echo $Tamano;?>" border="0" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu">
<thead>
    <tr title="Cabecera" height="25">
<?php
    for ($i=1; $i<$Conn->NroColumnas($Consulta); $i++){
?>
        <th width="<?php echo $FormatoGrilla[5]['W'.$i];?>" scope="col"><?php echo $FormatoGrilla[3]['T'.$i];?></th>
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
    $NumRegs 	= 0;
    while($row = $Conn->FetchArray($Consulta))
    {
    $NumRegs = $NumRegs + 1;
?>
    <tr id="<?php echo $row[0];?>" onmouseover="color_over(this);" onmouseout="color_out(this);" style="background-color:#FFF;">
<?php
        for ($i=1; $i<$Conn->NroColumnas($Consulta)-1; $i++){
?>
        <td align="<?php echo $FormatoGrilla[4]['A'.$i];?>" valign="middle" style="padding-left:5px; padding-right:5px;"><?php echo strtoupper($row[$i-1]);?></td>
<?php
        }
?>
		<td align="center">
<?php
    include('../../config_seguridad.php');
    $Sql = "SELECT login FROM usuario WHERE idusuario=".$row[9];
    $ConsultaS = $ConnS->Query($Sql);
    $rowS = $ConnS->FetchArray($ConsultaS);
    echo $rowS[0];			
?>		
                </td>		
<?php
		if ($FormatoGrilla[9]['Id']!=''){
?>
		<td valign="middle" style="padding-left:5px; padding-right:5px; width:<?php echo $FormatoGrilla[9]['NB']*18;?>px">
<?php			
for ($ii=1; $ii<=$FormatoGrilla[9]['NB']; $ii++){
        if ($FormatoGrilla[9]['BtnCC'.$ii]=='==')
        {
                $Condicion = 1;//$row[$FormatoGrilla[9]['BtnCI'.$ii]-1] == $FormatoGrilla[9]['BtnCV'.$ii];

        }
        if ($FormatoGrilla[9]['BtnCC'.$ii]=='!=')
        {
                $Condicion = $row[$FormatoGrilla[9]['BtnCI'.$ii]-1] != $FormatoGrilla[9]['BtnCV'.$ii];

        }
        if ($FormatoGrilla[9]['BtnCC'.$ii]=='<')
        {
                $Condicion = $row[$FormatoGrilla[9]['BtnCI'.$ii]-1] < $FormatoGrilla[9]['BtnCV'.$ii];

        }
        if ($FormatoGrilla[9]['BtnCC'.$ii]=='>')
        {
                $Condicion = $row[$FormatoGrilla[9]['BtnCI'.$ii]-1] > $FormatoGrilla[9]['BtnCV'.$ii];

        }
        //echo $Condicion;
        if (($Condicion && ($row[9]==$_SESSION['id_user'] || 1==$_SESSION['Admin'])) || ($FormatoGrilla[9]['BtnId'.$ii]=='BtnVer')){
?>
    <img width="15" id="<?php echo $row[0];?>" src="<?php echo $_SESSION["urlDir"].'imagenes/iconos/'.$FormatoGrilla[9]['BtnI'.$ii];?>" <?php echo $FormatoGrilla[9]['BtnF'.$ii];?> title="<?php echo $FormatoGrilla[9]['Btn'.$ii];?>" <?php echo $FormatoGrilla[9]['BtnF'.$ii];?> style="cursor:pointer;"/>
<?php
        }else{
?>
    <img width="15" src="<?php echo $_SESSION["urlDir"];?>imagenes/iconos/blanco.png" border="0"/>
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
    $NumColl = $Conn->NroColumnas($Consulta) + 1;
	if ($FormatoGrilla[9]['Id']!=''){
		$NumColl = $NumColl + 1;
	}
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