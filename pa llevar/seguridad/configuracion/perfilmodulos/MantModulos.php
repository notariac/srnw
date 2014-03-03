<?php 
if(!session_id()){ session_start(); }
    include("../../conexion.php");	
    include("../../clases/main.php");
    CuerpoSuperior("Mantenimiento de Acceso por Perfil");	
    $Guardar 	= (isset($_GET["Op"]))?$_GET["Op"]:'';
    $Id 		= (isset($_GET["Id"]))?$_GET["Id"]:'';
    $IdSistema1 = (isset($_GET["IdSistema"]))?$_GET["IdSistema"]:'';	
    if ($Id != ''){
            $SQL 		= "SELECT idperfil,descripcion FROM perfiles WHERE idperfil = '$Id'";
            $Consulta	= pg_query($conectar,$SQL);
            $row		= pg_fetch_array($Consulta);		
            $Guardar = "$Guardar&Id2=$Id&IdSistema=$IdSistema1";
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
function ValidarForm(){
    return true;
}
</script>
<form action="guardar.php?Op=<?php echo $Guardar;?>" method="post" enctype="multipart/form-data" name="form1">
  <table width="509" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#5398FF" class="Titulo">Acceso por Pérfil</td>
    </tr>
    <tr>
      <td align="right" class="cabecera">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="75" align="right" class="cabecera">Código:</td>
      <td width="424"><label>
        <input type="text" name="Codigo" id="Codigo" class="inputtext_1" style="text-transform:uppercase" readonly="readonly" value="<?php echo (isset($Id))?$Id:''?>"/>
      </label></td>
    </tr>
    <tr>
      <td class="cabecera" align="right">Sistema:</td>
      <td><label>
        <input name="Descripcion" readonly="readonly" type="text" class="inputtext_1" id="Descripcion" style="text-transform:uppercase" size="60" maxlength="60" value="<?php echo (isset($row[1]))?$row[1]:''?>"/>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="cabecera">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="cabecera">Módulo del Sistema</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="cabecera">&nbsp;</td>	
    </tr>
    <input type="hidden" name="Cont" id="Cont"  />
    <tr>
      <td colspan="2">
      <?php
                    $Cont = 0;			
                    $C = CargarModulos($conectar,0,$IdSistema1);
                    while($row1 = pg_fetch_array($C)){
                            $Cont += 1; 
      ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="4%"><label>
                    <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($conectar,$IdSistema1,$row1[0],$Id);?> />
                    </label></td>
                  <td colspan="3"><?php echo $row1[2]?>
                  <input type="hidden" name="Padre<?php echo $Cont?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row1[0];?>" /></td>
                </tr>
                <?php
                        $D = CargarModulos($conectar,$row1[0],$IdSistema1);
                        while($row2 = pg_fetch_array($D)){
                                $Cont += 1; 
                ?>
                <tr>
                      <td>&nbsp;</td>
                      <td width="5%"><label>
                        <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($conectar,$IdSistema1,$row2[0],$Id);?> />
                      </label></td>
                      <td colspan="2"><?php echo $row2[2];?>
                      <input type="hidden" name="Padre<?php echo $Cont;?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row2[0];?>" />
                      </td>
                </tr>
                <?php
                    $E = CargarModulos($conectar,$row2[0],$IdSistema1);
                    while($row3 = pg_fetch_array($E)){
                            $Cont += 1; 
                ?>
                <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td width="5%"><label>
                            <input type="checkbox" name="ChkPadre<?php echo $Cont;?>" id="ChkPadre<?php echo $Cont;?>" <?php echo Check($conectar,$IdSistema1,$row3[0],$Id);?> />
                          </label></td>
                          <td width="86%"><?php echo $row3[2]?>
                          <input type="hidden" name="Padre<?php echo $Cont;?>" id="Padre<?php echo $Cont;?>" value="<?php echo $row3[0];?>" />
                          </td>
                </tr>
                <?php } } ?>
              </table>
        <?php } ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="cabecera"><input type="hidden" name="Cont" id="Cont" value="<?php echo $Cont;?>" /></td>
    </tr>
    <tr bgcolor="#0066FF">
      <td colspan="2"><table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#0066FF"><label>
            <input type="submit" name="Aceptar" id="Aceptar" value="Aceptar" onclick="return ValidarForm();" />
          </label></td>
          <td bgcolor="#0066FF" align="right"><label>
            <input type="button" name="Cerrar" id="Cerrar" value="Cerrar" onclick="Cancelar();" />
          </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<?php
function CargarModulos($Conectar,$Where=0,$IdSistema){
    $Q = "select * from modulos where idpadre=$Where and idsistema=$IdSistema and estado=1 order by orden asc";
    return pg_query($Conectar,$Q);
}
function Check($Conectar,$IdSistema,$IdModulo,$IdPerfil){
    $Q = "select count(*) from modulos_perfil where idsistema=$IdSistema and idmodulo=$IdModulo and idperfil=$IdPerfil";
    $C = pg_query($Conectar,$Q);
    $row = pg_fetch_array($C);
    $d="";
    if($row[0]>0){
        $d="checked='checked'";
    }
    return $d;
}
CuerpoInferior();	
?>