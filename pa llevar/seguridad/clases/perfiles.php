<?php
    include('../config.php');
    $IdSistema = substr($_POST['IdSistema'],3);
    $Sql = "SELECT perfiles.idperfil, perfiles.descripcion, sistemas.descripcion FROM sistema_perfiles INNER JOIN perfiles ON perfiles.idperfil = sistema_perfiles.idperfil INNER JOIN sistemas ON sistemas.idsistema = sistema_perfiles.idsistema WHERE sistema_perfiles.idsistema='$IdSistema'";
    $Consulta = $Conn->Query($Sql);
    $Consulta2 = $Conn->Query($Sql);
    $rowT = $Conn->FetchArray($Consulta2);
?>
<input type="hidden" id="Sistema" value="<?php echo $rowT[2];?>"/>
<table width="300">
    <tr>
        <td valign="top">
            <table width="300">
                <tr class="Titulo">
                    <td align="center" width="50">C&oacute;digo</td>
                    <td align="center">Pérfil</td>
                </tr>
<?php
    while($row = $Conn->FetchArray($Consulta)){
?>
    <tr onClick="VermodulosPerfil(<?php echo $IdSistema;?>, <?php echo $row[0];?>)" style=" cursor:pointer;">
        <td align="center"><?php echo $row[0];?></td>
        <td align="left" style=" padding-left:5px"><?php echo $row[1];?></td>
    </tr>
<?php
    }
?>
            </table>
        </td>
        <td valign="top"><div id="DivPerfilesM"></div></td>
    </tr>
</table>