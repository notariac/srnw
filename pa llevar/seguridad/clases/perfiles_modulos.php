<?php
    include('../config.php');
    $IdSistema = $_POST['IdSistema'];
    $IdPerfil = $_POST['IdPerfil'];
    $SQL 	= "SELECT idperfil, descripcion FROM perfiles WHERE idperfil = '$IdPerfil'";
    $ConsultaP	= $Conn->Query($SQL);
    $rowP	= $Conn->FetchArray($ConsultaP);
    $Guardar    = "$Guardar&Id2=$IdPerfil&IdSistema=$IdSistema";
?>
<form action="guardar.php?Op=<?php echo $Guardar;?>" method="post" enctype="multipart/form-data" name="FormModulos">
<table border="0" celpading="1" width="300">
    <tr class="Titulo">
        <td>M&oacute;dulos&nbsp;: <?php echo $rowP[1];?></td>
    </tr>
    <tr>
        <td><div style="height:270px;overflow:auto;">
            <?php
                $Cont = 0;
                $C = CargarModulos($Conn,0,$IdSistema);
                while($row1 = $Conn->FetchArray($C)){
                    $Cont += 1;
            ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="4%"><label>
                    <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($Conn,$IdSistema,$row1[0],$IdPerfil);?> />
                    </label></td>
                  <td colspan="3"><?php echo $row1[2];?>
                  <input type="hidden" name="Padre<?php echo $Cont;?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row1[0];?>" /></td>
                </tr>
                    <?php
                        $D = CargarModulos($Conn,$row1[0],$IdSistema);
                        while($row2 = $Conn->FetchArray($D)){
                                $Cont += 1;
                    ?>
                    <tr>
                      <td>&nbsp;</td>
                      <td width="5%"><label>
                        <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($Conn,$IdSistema,$row2[0],$IdPerfil);?> />
                      </label></td>
                      <td colspan="2"><?php echo $row2[2];?>
                      <input type="hidden" name="Padre<?php echo $Cont;?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row2[0];?>" />
                      </td>
                    </tr>
                    <?php
                        $E = CargarModulos($Conn,$row2[0],$IdSistema);
                        while($row3 = $Conn->FetchArray($E)){
                            $Cont += 1;
                    ?>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td width="5%"><label>
                            <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($Conn,$IdSistema,$row3[0],$IdPerfil);?> />
                          </label></td>
                          <td width="86%"><?php echo $row3[2];?>
                          <input type="hidden" name="Padre<?php echo $Cont;?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row3[0];?>" />
                          </td>
                        </tr>
                <?php } } ?>
              </table>
        <?php } ?>
        </div>
        </td>
    </tr>
    <tr>
        <td height="40"><input type="hidden" name="Cont" id="Cont" value="<?php echo $Cont;?>" />
            <table width="300" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100" align="center"><img id="BtnAceptar" src="../../images/btnGuardar.png" width="85" style="cursor:pointer" onclick="ValidarModulos();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
                    <td>&nbsp;</td>
                    <td width="100" align="center"><img id="BtnCancelar" src="../../images/btnCancelar.png" width="85" style="cursor:pointer" onclick="CancelarModulos();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
                </tr>
          </table>
        </td>
    </tr>
</table>
</form>
<?php
function CargarModulos($Conn, $Where=0, $IdSistema){
    $Q = "select * from modulos where idpadre='$Where' and idsistema='$IdSistema' and estado=1 order by orden asc";
    return $Conn->Query($Q);
}
function Check($Conn,$IdSistema, $IdModulo, $IdPerfil){
    $Q = "select count(*) from modulos_perfil where idsistema='$IdSistema' and idmodulo=".$IdModulo." and idperfil='$IdPerfil'";
    $C = $Conn->Query($Q);
    $row = $Conn->FetchArray($C);
    $d="";
    if($row[0]>0){
        $d="checked='checked'";
    }
    return $d;
}
?>