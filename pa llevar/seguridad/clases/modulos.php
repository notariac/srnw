<?php
include("../config.php");	
$IdSistema 	= $_POST["IdSistema"];
$IdModulo	= $_POST["IdModulo"];	
function CargarD($IdPadre, $Pref){	
    global $Conn, $IdSistema, $IdModulo;		
    $SQL = "SELECT modulos.idmodulo, modulos.descripcion, modulos.idpadre FROM modulos INNER JOIN modulos Padre ON (Padre.idmodulo = modulos.idpadre) ";
    $SQL = $SQL."WHERE modulos.idsistema='$IdSistema' AND modulos.idpadre='$IdPadre' AND modulos.estado = 1 ORDER BY modulos.idpadre, modulos.orden ";
    $Consulta	= $Conn->Query($SQL);		
    $Cont = '';
    while ($row2=$Conn->FetchArray($Consulta)){
        $Pref2 = $Pref;
        $Cont = $row2[0];			
        $SQL2 = "SELECT count(idmodulo) ";
        $SQL2 = $SQL2." FROM modulos ";
        $SQL2 = $SQL2." WHERE idpadre=".$row2[0]." and estado=1 AND idsistema='$IdSistema'";		
        $Consulta5 = $Conn->Query($SQL2);
        $row3 = $Conn->FetchArray($Consulta5);			
        if ($row3[0]!=0){
            $Selected = "";
            if($row2[0]==$IdModulo){
                $Selected = "selected='Selected'";
            }
            $html2 = $html2."<option value='".$row2[2]."' ".$Selected." >".$Pref2.$row2[1]."</option>";
            $Pref2 = $Pref2."&nbsp;&nbsp;&nbsp;&nbsp;";
            $html2 = $html2.CargarD($row2[0], $Pref2);
        }else{
            if($row2[0]==$IdModulo){
                $Selected = "selected='Selected'";
            }
            $html2 = $html2."<option value='".$row2[0]."' ".$Selected." >".$Pref2.$row2[1]."</option>";
        }
    }		
    if ($Cont!=''){
        $html = $html.$html2;
    }
    return $html;
}
?>
<label>
  <select name="0form1_idpadre" id="IdPadre" style="width:200px" class="select">
  	<option value="0">--Seleccione el Padre--</option>
<?php
$SQL = "SELECT modulos.idmodulo, modulos.descripcion, modulos.idpadre FROM modulos INNER JOIN modulos Padre ON (Padre.idmodulo = modulos.idpadre) WHERE modulos.idsistema='$IdSistema' AND modulos.idpadre=0 AND modulos.estado = 1 ORDER BY modulos.idpadre, modulos.orden";
$Consulta	= $Conn->Query($SQL);
while($row	= $Conn->FetchArray($Consulta)){
    $Pref = "&nbsp;&nbsp;&nbsp;&nbsp;";
    $Selected = "";
    if($row[0]==$IdModulo){
        $Selected = "selected='Selected'";
    }
?>
       	<option value="<?php echo $row[0];?>" <?php echo $Selected;?> ><?php echo $row[1];?></option>
<?php
    echo CargarD($row[0], $Pref);
}
?>
  </select>
</label>