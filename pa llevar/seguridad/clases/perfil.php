<?php
    include("../config.php");
    $IdSistema = $_POST['IdSistema']
?>
<select name="IdPerfil" id="IdPerfil" class="select">
<?php
    $SQL1 = "SELECT sistema_perfiles.idperfil, perfiles.descripcion FROM sistema_perfiles INNER JOIN perfiles ON perfiles.idperfil = sistema_perfiles.idperfil WHERE sistema_perfiles.idsistema='$IdSistema'";
    $Consulta1	= $Conn->Query($SQL1);
    while($row1 = $Conn->FetchArray($Consulta1)){
?>
    <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option>
<?php
    }
?>
</select>