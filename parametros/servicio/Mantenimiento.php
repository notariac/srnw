<?php	
    if(!session_id()){ session_start(); }	
    include('../../config.php');	
    include_once '../../libs/funciones.php';
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';	
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";	
    $Especial	= "0";
    $Precio	= "0.00";
    $Legal 	= "0";
    $Correlativo= "0";
    $Reinicio	= "0";
    $Folios	= "0";
    $Estado 	= "1";
    $Actual 	= "1";
    if($Op==2 || $Op==3){
        $Enabled = "readonly";
        $Guardar = "Op=$Op";
    }else{
        if($Op==0 || $Op==1){
                $Guardar = "Op=$Op";
        }
    }
    $Enabled2 = "readonly";
    if($Id!=''){
        $Select 	= "SELECT * FROM servicio WHERE idservicio = '$Id'";
        $Consulta 	= $Conn->Query($Select);
        $row 		= $Conn->FetchArray($Consulta);
        $Precio		= $row[2];
        $Legal 		= $row[3];
        $Especial       = $row[4];
        $Correlativo    = $row[6];
        $Reinicio       = $row[7];
        $Folios		= $row[8];
        $Estado         = $row[9];
        $Guardar        = "$Guardar&Id2=$Id";
        $SQL2 = " SELECT asigna_pdt.idacto_juridico, servicio.idservicio, asigna_pdt.iddocumento_notarial 
                  FROM asigna_pdt INNER JOIN 
                      servicio ON servicio.idservicio=asigna_pdt.idservicio 
                  WHERE servicio.idservicio= '$Id' ";        
        $Consulta2 = $Conn->Query($SQL2);
        $cantidad = $Conn->NroRegistros($Consulta2);
        if($cantidad>=1){            
            $ConsultaB = $Conn->Query($SQL2." LIMIT 1 OFFSET 0");
            $rowB = $Conn->FetchArray($ConsultaB);
            $ConsultaActoJuridico="
                SELECT pdt.documento_notarial.iddocumento_notarial, pdt.documento_notarial.descripcion 
                FROM pdt.documento_notarial 
                INNER JOIN pdt.acto_documento ON pdt.acto_documento.iddocumento_notarial=pdt.documento_notarial.iddocumento_notarial 
                WHERE pdt.acto_documento.idacto_juridico='".$rowB[0]."'";
            $ConsultaC = $Conn->Query($ConsultaActoJuridico);        
            $rowC = $Conn->FetchArray($ConsultaC);   
        }
    }
$ArrayP = array(NULL);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/jquery.autocomplete.js"></script>
<style type="text/css">
    *{font-size:12px;}
</style>
<script>
    var Id='<?php echo $Id;?>';
    var Op='<?php echo $Op;?>';
</script>
<script type="text/javascript" src="servicio.js"></script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">C&oacute;digo :</td>
    <td><input type="text" class="inputtext" style="text-align:center; width:50px" name="1form1_idservicio" id="Id" maxlength="2" value="<?php echo $row[0];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Descripcion');"/>    </td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Descripci&oacute;n&nbsp;:</td>
    <td><input type="text" class="inputtext" style="width:350px; text-transform:uppercase;" name="0form1_descripcion" id="Descripcion"  maxlength="100" value="<?php echo $row[1];?>" <?php echo $Enabled;?> onkeypress="return permite(event, 'car'); CambiarFoco(event, 'Precio');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Precio : </td>
    <td><input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="0form1_precio" id="Precio" value="<?php echo $Precio;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'num');"/></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Legal : </td>
    <td><input type="checkbox" name="Legal2" id="Legal2" <?php if ($Legal==1) echo "checked='checked'";?> onclick="CambiaLegal();" /><input type="hidden" name="0form1_legal" id="Legal" value="<?php echo $Legal;?>" /></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">N&ordm; Especial : </td>
    <td><input type="checkbox" name="Especial2" id="Especial2" <?php if ($Especial==1) echo "checked='checked'";?> onclick="CambiaEspecial();" /><input type="hidden" name="0form1_especial" id="Especial" value="<?php echo $Especial;?>" /></td>
  </tr>
  <tr id="TrKardexTipo">
    <td width="130" class="TituloMant">Tipo de Kardex  : </td>
    <td>
    <select name="0form1_idkardex_tipo" id="KardexTipo" class="select" onchange="Tab('DniRuc');" >
        <option value="0" >-- Seleccione el Tipo de Kardex --</option>   
        <?php
        $SelectKT 	= "SELECT * FROM kardex_tipo WHERE estado = 1 ORDER BY descripcion ASC";
        $ConsultaKT = $Conn->Query($SelectKT);
        while($rowKT=$Conn->FetchArray($ConsultaKT)){
            $Select = '';
            if ($row[5]==$rowKT[0])
                $Select = 'selected="selected"';
        ?>
        <option value="<?php echo $rowKT[0];?>" <?php echo $Select;?>><?php echo "(".$rowKT[2].") - ".$rowKT[1];?></option>
        <?php
        }
        ?>
    </select>
    </td>
  </tr>
  <tr id="TrCorrelativo">
    <td class="TituloMant">Lleva correlativo : </td>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input type="checkbox" name="Correlativo2" id="Correlativo2" <?php if ($Correlativo==1) echo "checked='checked'";?> onclick="CambiaCorrelativo();" /><input type="hidden" name="0form1_correlativo" id="Correlativo" value="<?php echo $Correlativo;?>" /></td>
          <td>
            <table width="99%" border="0" cellspacing="0" cellpadding="0" id="TrCorrelativo2">
            <tr>
              <td width="37%"><span class="TituloMant">N&ordm; Correlativo : </span></td>
              <td>
                <?php
                    $Corr = 0;
                    if ($Op!=0){
                        $SelectN 	= "SELECT correlativo FROM servicio_notaria WHERE idnotaria='".$_SESSION["notaria"]."' AND idservicio = $Id";
                        $ConsultaN 	= $Conn->Query($SelectN);
                        $rowN 		= $Conn->FetchArray($ConsultaN);
                        $Corr		= $rowN[0];
                        if ($Corr==''){
                            $Corr = 0;
                        }
                    }
                ?>		  
                  <input type="text" class="inputtext" style="width:80px; text-transform:uppercase; text-align:right" name="CorrelativoNro" id="CorrelativoNro" value="<?php echo $Corr;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'num');"/>
              </td>
              <td>
                  <input type="checkbox" name="Reinicio2" id="Reinicio2" <?php if ($Reinicio==1) echo "checked='checked'";?> onclick="CambiaReinicio();" /><input type="hidden" name="0form1_reinicio" id="Reinicio" value="<?php echo $Reinicio;?>" />Reinicio Anual
              </td>
            </tr>
          </table>            
          </td>
        </tr>
      </table></td>
  </tr>  
  <tr id="TrCorrelativo">
    <td class="TituloMant">Documento Notarial : </td>
    <td>              
        <input type="text" class="inputtext" style="width:310px; text-transform:uppercase;" name="DocumentoNotarial" id="DocumentoNotarial" value="<?php echo $rowC[1];?>" <?php if($cantidad!=0){ echo "disabled";} ?> onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'car');"/>
        <input type="hidden" class="inputtext" style="width:80px; text-transform:uppercase;" name="iddocumento_notarial" id="IdDocumentoNotarial" value="<?php echo $rowC[0];?>"  onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'car');"/>
    </td>
  </tr>
  <?php if($row[0]!=""){ ?>
  <tr>
    <td class="TituloMant">Plantilla : </td>
    <td>              
        <a target="_blank" href="../../editor/servicio.php?tipo=p&idservicio=<?php echo $row[0];?>">Ver Plantilla</a>
    </td>
  </tr>
  <?php } ?>
  <tr id="TrCorrelativo">
    <td class="TituloMant">Acto Jur&iacute;dico : </td>
    <td>	  
        <input type="text" class="inputtext" style="width:310px; text-transform:uppercase;" name="ActoJuridico" id="ActoJuridico" onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'car');" <?php if($cantidad==0){ echo "disabled";} ?> />        
        <input type="hidden" class="inputtext" style="width:80px; text-transform:uppercase;" name="idacto_juridica" id="IdActoJuridico" onkeypress="CambiarFoco(event, 'Legal2'); return permite(event, 'car');"/>        
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="TrCorrelativo">
    <td class="TituloMant" colspan="2">        
        <table width="90px" border="0" cellspacing="0" cellpadding="0" class="Boton" onclick="AgregaPDT_Notario(this);" style="cursor: pointer;" title="Agregar Norma PDT Notario" >
            <tr>
              <td width="16" height="24" align="center" valign="middle"><img src="../../imagenes/iconos/add.png" width="16" height="16" /></td>
              <td width="74" align="center" valign="middle" style="font-size:12px;">Agregar Item</td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <table width="300" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu4">
      <tr>
        <th height="20" title="Cabecera">Acto Jur&iacute;dico</th>
        <th title="Cabecera" width="20">&nbsp;</th>
      </tr>
      <tbody>
<?php	
$NumRegs = 0;
if ($Op!=0){
        $Consulta2 = $Conn->Query($SQL2);			
        while($row2 = $Conn->FetchArray($Consulta2)){
            $ConsultaA = $Conn->Query(" SELECT * FROM pdt.acto_juridico WHERE idacto_juridico='".$row2[0]."' ");			
            $rowA = $Conn->FetchArray($ConsultaA);			
            $NumRegs = $NumRegs + 1;				
?>
        <tr>
            <td style="padding-right:5px">
                <input type="hidden" name="0formF<?php echo $NumRegs;?>_idservicio" id="IdServicioP<?php echo $NumRegs;?>" value="<?php echo $row2[1];?>" />
                <input type="hidden" name="0formF<?php echo $NumRegs;?>_idacto_juridico" id="IdActoJuridicoP<?php echo $NumRegs;?>" value="<?php echo $row2[0];?>" />
                <input type="hidden" name="0formF<?php echo $NumRegs;?>_iddocumento_notarial" id="IDocumentoNotarialP<?php echo $NumRegs;?>" value="<?php echo $row2[2];?>" />
                <?php echo $rowA[1];?>
            </td>
            <td align="center">
                <img src="../../imagenes/iconos/eliminar.png" alt="" width="16" height="16" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar Participacion" onclick="QuitaPDT_Notario(this);" />
            </td>
        </tr>
<?php
        }
}
	echo "<script> var nDestF = $NumRegs; var nDestFC = $NumRegs; </script>";
?>
    </tbody>
    </table>
    <input type="hidden" name="ConPDT_Notario" id="ConPDT_Notario" value="<?php echo $NumRegs;?>"/>
    </td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="TrFolio">
    <td class="TituloMant">Uso de Folio : </td>
    <td><input type="checkbox" name="Folios2" id="Folios2" <?php if ($Folios==1) echo "checked='checked'";?> onclick="CambiaFolio();" /><input type="hidden" name="0form1_folios" id="Folios" value="<?php echo $Folios;?>" /></td>
  </tr>
  <tr>
    <td width="130" class="TituloMant">Estado :</td>
    <td><input type="checkbox" name="Estado2" id="Estado2" <?php if ($Estado==1) echo "checked='checked'";?> onclick="CambiaEstado();" /><input type="hidden" name="0form1_estado" id="Estado" value="<?php echo $Estado;?>" /> Activo</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <table width="300" border="0" cellspacing="1">
      <tr>
        <td>
            <select name="Participacion" id="Participacion" class="select">
            <?php echo opt_combo("SELECT * FROM participacion WHERE estado = 1 ORDER BY descripcion ASC", $row[5], $Conn);?>
            </select>
            <button id="agregarboton">Agregar</button>
        </td>
        
      
      </tr>
    </table>
    </td>
    </tr>
  <tr>
    <td colspan="2" align="center">
    <table width="300" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu3">
      <tr>
        <th height="20" title="Cabecera">Participaci&oacute;n</th>
        <th title="Cabecera" width="20">&nbsp;</th>
      </tr>
      <tbody>
      <?php
        $NumRegs = 0;
        if ($Op!=0){
        $SQL2 = "SELECT 
                 servicio_participacion.idservicio, 
                 servicio_participacion.idparticipacion, 
                 participacion.descripcion 
                 FROM participacion 
                 INNER JOIN servicio_participacion ON (participacion.idparticipacion = servicio_participacion.idparticipacion) 
                 WHERE servicio_participacion.idservicio = '$Id'";
        $Consulta2 = $Conn->Query($SQL2);			
        while($row2 = $Conn->FetchArray($Consulta2)){
            $NumRegs = $NumRegs + 1;				
        ?>
        <tr>
            <td style="padding-right:5px">
                <input type="hidden" 
                       name="0formD<?php echo $NumRegs;?>_idservicio" 
                       id="IdServicioD<?php echo $NumRegs;?>" 
                       value="<?php echo $row2[0];?>" />
                <input type="hidden" 
                       name="0formD<?php echo $NumRegs;?>_idparticipacion" 
                       id="IdParticipacionD<?php echo $NumRegs;?>" 
                       value="<?php echo $row2[1];?>" />
               <!-- Valor -->
                <?php echo $row2[2];?>
            </td>
            <td align="center">
                <img src="../../imagenes/iconos/eliminar.png" alt="" width="16" height="16" style="cursor:pointer; <?php if ($Op==2 || $Op==3 || $Op==4){ echo "display:none";}?>" title="Quitar Participacion" onclick="QuitaParticipacion(this);" />
            </td>
        </tr>
<?php
        }
}
	echo "<script> var nDestP = $NumRegs; var nDestPC = $NumRegs; </script>";
?>
    </tbody>
    </table>
      <input type="hidden" name="ConParticipacion" id="ConParticipacion" value="<?php echo $NumRegs;?>"/>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</div>
<script>
    VerificaCT();
    CambiaEspecial();
    CambiaCorrelativo();
</script>