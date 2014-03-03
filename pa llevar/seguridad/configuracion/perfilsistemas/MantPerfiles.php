<?php
if(!session_id()){ session_start(); }
    include("../../config.php");
    include("../../clases/main.php");
    CuerpoSuperior("P&eacute;rfil por Sistema");
    $Guardar            = (isset($_GET["Op"]))?$_GET["Op"]:'';
    $Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';
    if ($Id != ''){
        $SQL 		= "SELECT * FROM sistemas WHERE idsistema = '$Id'";
        $Consulta	= $Conn->Query($SQL);
        $row            = $Conn->FetchArray($Consulta);
        $Guardar        = "$Guardar&Id2=$Id";
    }
?>
<script>
var cont 	= 0; 
var nDest 	= 0;
var nDestC 	= 0;
var val		= 0;	
function Cancelar(){
    location.href = 'index.php';
}
function AgregarPerfil(){
    var Dest = 1;
    var x ;
    var x1;
    var IdModulo 	= document.getElementById('IdPerfil').value;
    var Modulos 	= document.getElementById("IdPerfil").options[document.getElementById("IdPerfil").selectedIndex].text;
    val = 0;
    for (x = 1; x <= document.getElementById('Cont').value; x++){
        try{
            if((eval("document.getElementById('Codigo" + x + "').value")== IdModulo)){
                alert("El Perfil " + Modulos + " se Encuentra Agregado para Este Sistema");
                val=1;
            }
        }catch(exp){}
    }		
    if (val == 0){
        if (document.getElementById('IdPerfil').value != 0){
            nDest 	= nDest + 1;
            nDestC	= nDestC + 1;				
            var miTabla = document.getElementById('ListaMenu').insertRow(nDest);
            var celda1=miTabla.insertCell(0);
            var celda2=miTabla.insertCell(1);
            var celda3=miTabla.insertCell(2);							
            celda1.innerHTML = "<input type='hidden' name='2formd" + nDestC + "_idsistema' id='IdSistema" + nDestC + "' value='<?=$Id?>'/><input name='2formd" + nDestC + "_idperfil' id='Codigo" + nDestC + "' type='hidden' value='" + IdModulo + "' /><div align = 'center' style='background:#FFF'>" + IdModulo + "</div>";
            celda2.innerHTML = "<input name='Descripcion" + nDestC + "' type='hidden' value='" + Modulos + "' /><div style='background:#FFF'>" + Modulos + "</div>";
            celda3.innerHTML = "<img src='<?php echo $UrlDir;?>images/quitar.png' width='16' height='16' onclick='QuitaFilaD(this, 1);' style='cursor:pointer'/>";					
            document.getElementById('Cont').value = nDestC;
        }else{
            alert("Seleccione el Perfil a Registrar");
        }
    }
}        
function QuitaFilaD(x, det){	
    while (x.tagName.toLowerCase() !='tr'){
        if(x.parentElement)
            x=x.parentElement;
        else if(x.parentNode)
            x=x.parentNode;
        else
            return;
    }		
    var rowNum=x.rowIndex;
    while (x.tagName.toLowerCase() !='table'){
        if(x.parentElement)
            x=x.parentElement;
        else if(x.parentNode)
            x=x.parentNode;
        else
            return;
    }
    x.deleteRow(rowNum);
    if (det==1){
        nDest = nDest - 1;
    }
}
function ValidarForm(){
    document.form1.submit();
}
function Sobre(obj){
    obj.style.width=90;
}
function Fuera(obj){
    obj.style.width=85;
}
</script>
<form action="guardar.php?Op=<?php echo $Guardar;?>" method="post" enctype="multipart/form-data" name="form1">
  <table width="350" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="Titulo">Mantenimiento de Pérfil del Sistema</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="75" class="MantTitulo">Código :</td>
      <td class="MantItem">
        <input type="text" name="Codigo" id="Codigo" class="inputtext" size="4" style="text-transform:uppercase" readonly="readonly" value="<?=(isset($Id))?$Id:''?>"/>
      </td>
    </tr>
    <tr>
      <td class="MantTitulo">Sistema :</td>
      <td class="MantItem">
        <input name="Descripcion" readonly="readonly" type="text" class="inputtext" id="Descripcion" style="text-transform:uppercase" size="50" maxlength="60" value="<?=(isset($row[1]))?$row[1]:''?>"/>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="MantTitulo">Perfiles :</td>
      <td class="MantItem">
        <select name="IdPerfil" id="IdPerfil" class="select">
        <option value="0">--Seleccione el Perfil--</option>
        <?php
            $SQL1 		= "SELECT * FROM perfiles WHERE estado=1";
            $Consulta1	= $Conn->Query($SQL1);
            while($row1 = $Conn->FetchArray($Consulta1)){
        ?>
            <option value="<?php echo $row1[0]?>"><?php echo $row1[1]?></option>
        <?php
            }
        ?>
        </select>
     <img src="<?php echo $UrlDir?>images/agregar.png" width="16" height="16" style="cursor:pointer" onclick="AgregarPerfil();" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
    </tr><input type="hidden" name="Cont" id="Cont"/>
    <tr>
      <td colspan="2" align="center">
          <table width="300" cellspacing="1" border="0" align="center" id="ListaMenu">
              <thead>
        <tr class="cabecera">
          <td width="81" align="center">Codigo</td>
          <td width="374" align="center">Perfil</td>
          <td width="31">&nbsp;</td>
        </tr>
              </thead>
        <?php
                    $Contador = 0;
                    $SQL2  = "SELECT sistema_perfiles.idsistema,sistema_perfiles.idperfil,perfiles.descripcion FROM sistema_perfiles ";
                    $SQL2 .= "INNER JOIN perfiles ON (sistema_perfiles.idperfil = perfiles.idperfil) WHERE sistema_perfiles.idsistema='$Id'";
                    $Consulta2 = $Conn->Query($SQL2);
                    while($row2 = $Conn->FetchArray($Consulta2)){
                            $Contador = $Contador + 1;
        ?>
        <tr>
          <td align="center">
              <input type="hidden" name="2formd<?php echo $Contador;?>_idsistema" id="IdSistema" value="<?php echo $row2[0];?>"/>
              <input name="2formd<?php echo $Contador?>_idperfil" id="Codigo<?php echo $Contador;?>" type="hidden" value="<?php echo $row2[1];?>" /><?php echo $row2[1];?></td>
          <td><?php echo $row2[2];?></td>
          <td align="center"><img src='<?php echo $UrlDir;?>images/quitar.png' width='16' height='16' onclick='QuitaFilaD(this, 1);' style='cursor:pointer;'/></td>
        </tr>
        <?php } ?>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="Pie">
      <td colspan="2" align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="200" align="center"><img id="BtnAceptar" src="../../images/btnGuardar.png" width="85" style="cursor:pointer" onclick="ValidarForm();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        <td>&nbsp;</td>
        <td width="200" align="center"><img id="BtnCancelar" src="../../images/btnCancelar.png" width="85" style="cursor:pointer" onclick="Cancelar();" onmousemove="Sobre(this);" onmouseout="Fuera(this);" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<script>
	document.getElementById("Cont").value=<?php echo $Contador;?>;
	cont 	= <?php echo $Contador;?>; 
	nDest 	= <?php echo $Contador;?>; 
	nDestC 	= <?php echo $Contador;?>; 
</script>
<?php CuerpoInferior();?>