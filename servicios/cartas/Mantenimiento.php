<?php	
if(!session_id()){ session_start(); }	
    include('../../config.php');
    include('../../config_seguridad.php');	
    $Op = $_POST["Op"];
    $Id = isset($_POST["Id"])?$_POST["Id"]:'';
    $Enabled	= "";
    $Enabled2	= "";
    $Guardar	= "";
    $Usuario = $_SESSION["Usuario"];
    $Fecha	= date('d/m/Y');
    $OcurrenciaFecha = date('d/m/Y');
    $EntregaFecha = date('d/m/Y');
    $Estado = "<label style='color:#FF6600'>PENDIENTE</label>";
    $Anio = $_SESSION["Anio"];
    $FolioI		= 1;
    $FolioF		= 1;
    $Guardar = "Op=$Op";
    if($Op==2 or $Op==4){
        $Enabled = "readonly";
    }	
    $Enabled2 = "readonly";
    if($Id!=''){
        $Select 	= "SELECT * FROM carta WHERE idcarta = '$Id'";

        $Consulta 	= $Conn->Query($Select);
        $row 		= $Conn->FetchArray($Consulta);		
        $Usuario 	= $_SESSION["Usuario"];

          $Fecha    = $Conn->DecFecha($row[2]);  

        $OcurrenciaFecha = $Conn->DecFecha($row[12]);

        $EntregaFecha = $Conn->DecFecha($row[16]);

        if ($row[17]==1){
                $Estado = "<label style='color:#003366'>POR IMPRIMIR</label>";
        }
        if ($row[17]==2){
                $Estado = "<label style='color:#003366'>CANCELADO</label>";
        }
        if ($row[17]==3){
                $Estado = "<label style='color:#FF00000'>ANULADO</label>";
        }
        $Anio = $row[20];
        $Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[18]."'";
        $ConsultaS = $ConnS->Query($Sql);
        $rowS = $ConnS->FetchArray($ConsultaS);
        $Usuario	= $rowS[0];
        $SqlSe = "SELECT descripcion FROM servicio WHERE idservicio=".$row[4];
        $ConsultaSe = $Conn->Query($SqlSe);
        $rowSe = $Conn->FetchArray($ConsultaSe);
        $CartaTipo	= $rowSe[0];
    }
$ArrayP = array(NULL);
?>
<script>
$(document).ready(function(){
        var idub = $("#idubigeo").val();
        if(idub==""){idub=="220901"}
        TraerDepartamento(idub);
        $("#IdDepartamento").change(function(){
            TraerProvincia($(this).val());
            $("#IdProvincia").focus();
        });
        $("#IdProvincia").change(function(){
            TraerDistrito($(this).val());
            $("#IdDistrito").focus();
        });
        $("#IdDistrito").change(function(){
           $("#idubigeo").val($(this).val());
        })
	
        $("#OcurrenciaFecha").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
        });
        $("#EntregaFecha").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
        });
});
function TraerDepartamento(IdUbigeo)
    {
        $("#load-departamento").css("display","inline-block");            
        $.post('../../libs/departamento.php','IdUbigeo=' + IdUbigeo,function(data){                
            $("#load-departamento").css("display","none");   
            $("#IdDepartamento").empty().append(data);
            TraerProvincia(IdUbigeo);
        });
    }
    function TraerProvincia(IdUbigeo)
    {  
        IdDep = $('#IdDepartamento').val();
        $("#load-provincia").css("display","inline-block");   
        $.post('../../libs/provincia.php','IdUbigeo=' + IdUbigeo + '&IdDep=' + IdDep,function(data){                
            $("#load-provincia").css("display","none");
            $("#IdProvincia").empty().append(data);
            TraerDistrito(IdUbigeo);
        });
    }
    function TraerDistrito(IdUbigeo)
    {
        IdProv = $('#IdProvincia').val();
        $("#load-distrito").css("display","inline-block");
        $.post('../../libs/distrito.php?Prefi=formP','IdUbigeo=' + IdUbigeo + '&IdProv=' + IdProv,function(data){
            $("#load-distrito").css("display","none");
            $("#IdDistrito").empty().append(data);
            $("#idubigeo").val($("#IdDistrito").val());
        });            
    }   
function Cancelar(){
    window.location.href='index.php';
}
function ValidarFormEnt(evt){
    var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
    if (keyPressed == 13 ){
        Guardar(<?php echo $Op;?>);
    }
}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>">
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="110" class="TituloMant">Nº Carta :</td>
    <td width="280"><input type="text" class="inputtext" style="text-align:center; font-size:10px; width:50px" name="0form1_correlativo" id="Id" maxlength="2" value="<?php echo $row[3];?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(this, 'Fecha');"/>
      <input type="hidden" name="1form1_idcarta" value="<?php echo $row[0];?>" /><label style="font-size:12px; color:#336600"><?php echo $CartaTipo;?></label></td>
    <td width="130" align="right">
        <table width="130" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
                <td align="right"><?php echo $Estado;?></td>
            </tr>
        </table>	
    </td>
  </tr>
  <tr>
    <td class="TituloMant">Fecha : </td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha" id="Fecha" value="<?php echo $Fecha;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'Remitente');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Remitente  :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_remitente" id="Remitente"  maxlength="100" value="<?php echo $row[5];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Remitente');"/></td>
  </tr>
  <tr style="display:none">
    <td class="TituloMant">Empresa Remitente  :</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant"><p> Destinataria :</p></td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_destinatario" id="Remitente"  maxlength="100" value="<?php echo $row[7];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'RemitenteEmpresa');"/></td>
  </tr>
  <tr style="display:none">
    <td class="TituloMant">Empresa Destinataria  :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_destinatario_empresa" id="RemitenteEmpresa"  maxlength="100" value="<?php echo $row[8];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Direccion');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Direcci&oacute;n :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_direccion" id="Direccion"  maxlength="100" value="<?php echo $row[9];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Ubigeo');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Departamento :</td>
    <td colspan="2">
    	<div id="DivDepartamento">
          <input type="hidden" name="0form1_idubigeo" id="idubigeo" value="<?php echo $row[10]; ?>" />
        	<select name="IdDepartamento" id="IdDepartamento">
            <option value=''>Departamento</option>
          </select>
		</div>
  	</td>
  </tr>
  <tr>
    <td class="TituloMant">Provincia : </td>
    <td colspan="2">
    	<div id="DivProvincia">
            <select name="IdProvincia" id="IdProvincia">
                <option value=''>Provincia</option>
            </select>
      	</div>
    </td>
  </tr>
  <tr>
    <td class="TituloMant">Distrito :</td>
    <td colspan="2">
    	<div id="DivDistrito">
        	<select name="IdDistrito" id="IdDistrito">
              <option value=''>Distrito</option>
          </select>
    	</div>
    </td>
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="TituloMant">Ocurrencia : </td>
    <td colspan="2"><select name="0form1_idocurrencia" id="Ocurrencia" class="select" style="font-size:12px;" onchange="Tab('FolioInicial');" >
    	<option value="">-- Selecciones una ocurrencia</option>
<?php
    $SelectLT 	= "SELECT * FROM ocurrencia WHERE estado = 1";
    $ConsultaLT = $Conn->Query($SelectLT);
    while($rowLT=$Conn->FetchArray($ConsultaLT)){
        $Select = '';
        if ($row[11]==$rowLT[0]){
            $Select = 'selected="selected"';
        }
?>
<option value="<?php echo $rowLT[0];?>" <?php echo $Select;?>><?php echo $rowLT[1];?></option>
<?php
    }
?>
    </select></td>
  </tr>
  <tr>
    <td valign="top" class="TituloMant">Observaci&oacute;n : </td>
    <td colspan="2"><textarea name="0form1_observaciones" rows="2" class="inputtext" id="Observacion" style="font-size:12px; width:350px; text-transform:uppercase;" <?php echo $Enabled;?> ><?php echo $row[13];?></textarea></td>
  </tr>
  <tr>
    <td class="TituloMant">Fecha Ocurrencia :</td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_ocurrencia_fecha" id="OcurrenciaFecha" maxlength="10" value="<?php echo $OcurrenciaFecha;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Mensajero');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Mensajero :</td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_mensajero" id="Mensajero"  maxlength="100" value="<?php echo $row[14];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Recepciono');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Repcionado por : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_recepciono" id="Recepciono"  maxlength="100" value="<?php echo $row[15];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'EntregaFecha');"/></td>
  </tr>
  <tr>
    <td class="TituloMant">Fecha de Recepci&oacute;n :</td>
    <td colspan="2">
      <input type="text" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_entrega_fecha" id="EntregaFecha"  maxlength="10" value="<?php echo $EntregaFecha;?>" <?php echo $Enabled;?> />
      <input type="text" class="inputtext" style="font-size:12px; width:50px; text-transform:uppercase;" name="0form1_remitente_empresa" id="Remitente"  maxlength="10" value="<?php echo $row[6];?>" <?php echo $Enabled;?>/>
      <input type="hidden" class="inputtext" style="font-size:12px; width:50px; text-transform:uppercase;" name="2form1_anio" id="0form1_anio"  maxlength="10" value="<?php echo $row[20];?>" <?php echo $Enabled;?>/>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2" align="left" valign="middle" style="font-size:12px">Generado por : <?php echo $Usuario;?></td>
  </tr>
</table>
</form>
</div>