<?php 
if(!session_id()){ session_start(); }
	include("../../libs/masterpage.php");	
	include("../../config.php");
	include('../../config_seguridad.php');	
	$NroKardex = isset($_GET["Valor"])?strtoupper($_GET["Valor"]):'';	
	$Valor 	= $NroKardex;
	$Campo 	= (isset($_GET['Campo']))?$_GET['Campo']:'';
	$anio 	= (isset($_GET['Anio']))?$_GET['Anio']:date('Y');	
	if ($Campo=='kardex_derivacion_situacion.titulo_numero'){
            $SQLtit         = "SELECT kardex.correlativo, kardex_derivacion_situacion.titulo_numero FROM kardex_derivacion_situacion INNER JOIN kardex ON (kardex_derivacion_situacion.idkardex = kardex.idkardex) WHERE kardex.anio = '$anio' AND CAST($Campo as text) = '".$NroKardex."' AND kardex.idnotaria='".$_SESSION['notaria']."'";		
            $Consultatit    = $Conn->Query($SQLtit);
            $rowtit         = $Conn->FetchArray($Consultatit);
            $NroKardex      = $rowtit[0];
	}	
	$Fecha 	= date('d/m/Y');
	$Estado = "";
	if ($NroKardex != ''){		
		$SQL 		= "SELECT kardex.fecha, servicio.descripcion, kardex.escritura, kardex.minuta, kardex.fojainicio, kardex.fojafin, kardex.serieinicio, kardex.seriefin, kardex.idkardex, kardex.archivo, (kardex.correlativo || 'Parte.doc'), kardex.estado, kardex.idusuario, kardex.firmado, kardex.firmadofecha, kardex.correlativo FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) WHERE kardex.correlativo ='$NroKardex' AND kardex.anio='$anio' AND kardex.idnotaria='".$_SESSION['notaria']."'";
		$Consulta	= $Conn->Query($SQL);
		$row		= $Conn->FetchArray($Consulta);		
		if(isset($row[13])){
                    if($row[13]==1){
                        $FechaFirma = $Conn->DecFecha($row[14]);				
                        $Firmado = "El Tr&aacute;mite se encuentra firmado, fecha de la firma: $FechaFirma";
                    }else{
                        $Firmado = "El Tr&aacute;mite se encuentra pendiente de firma";
                    }
		}else{
                    $Firmado="El Tr&aacute;mite se encuentra pendiente de llenado de datos";
		}			
		$SQLDerivacion 		= "SELECT kardex_derivacion.idkardex, kardex_derivacion.iddependencia FROM kardex_derivacion INNER JOIN kardex ON (kardex_derivacion.idkardex = kardex.idkardex) WHERE kardex.correlativo='$NroKardex' AND kardex.idnotaria='".$_SESSION['notaria']."'";
		$ConsDerivacion		= $Conn->Query($SQLDerivacion);
		$rowderivacion		= $Conn->FetchArray($ConsDerivacion);				
		if(isset($row[12])){	
			$Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[12]."'";
			$ConsultaS = $ConnS->Query($Sql);
			$rowS = $ConnS->FetchArray($ConsultaS);			
			$Digitado = "Digitado por ".$rowS[0];
		}		
		$Fecha = $Conn->DecFecha($row[0]);		
		$SQLAtencion 	= "SELECT atencion_detalle.idatencion FROM atencion_detalle INNER JOIN atencion ON atencion.idatencion = atencion_detalle.idatencion WHERE atencion_detalle.anio='$anio' AND atencion_detalle.correlativo='$NroKardex' AND atencion.idnotaria='".$_SESSION['notaria']."'";
		$ConsAtencion	= $Conn->Query($SQLAtencion);
		$rowatencion	= $Conn->FetchArray($ConsAtencion);		
	}
	CuerpoSuperior("Consulta de Kardex")
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/Funciones.js" type="text/javascript"></script>
<script>
function BuscarKardex(Obj, evt){
    Id = Obj.value;		
    var keyPressed = (evt.which) ? evt.which : evt.keyCode
    if (keyPressed == 13){
        var Campo = $('#Campo').val();
        var Valor = $('#NroKardex').val();
        var Anio = $('#Anio').val();			
        location.href = 'index.php?Valor=' + Valor + '&Campo=' + Campo + '&Anio=' + Anio;
        event.returnValue = false;	
    }		
}
function Impresion(NroGeneracion, IdParticipante){
    var url='<?php echo $UrlDir;?>servicios/tomafirmas/impresion.php?IdKardex=' + NroGeneracion + '&IdParticipante=' + IdParticipante;
    var ventana = window.open(url, 'Buscar', 'width=500, height=500, resizable=yes, scrollbars=yes'); ventana.focus();
}
function AbrirWord(Archivo){
    if(Archivo == ""){
        alert('No se ha encontrado ning&uacute;n archivo adjuntado');
        return;
    }
    var url='<?php echo $UrlDir;?>servicios/kardex/archivos/' + Archivo;
    var ventana=window.open(url, 'Imprimir', 'width=500, height=500, resizable=yes, scrollbars=yes'); ventana.focus();
}
function AbrirParte(Archivo){
    var url='<?php echo $UrlDir;?>servicios/tomafirmas/partes/' + Archivo;
    if(!existe(url)){
        url='../../libs/pregunta.php';
    }		
    var ventana=window.open(url, 'Imprimir', 'width=500, height=500, resizable=yes, scrollbars=yes'); ventana.focus();
}
function Seguimiento(IdDerivacion){
    if(IdDerivacion==""){
        alert('El archivo a&uacute;n no fue ingresado a registro p&uacute;blicos')
        return;
    }
    var url='busqueda_derivacion.php?NroKardex=' + IdDerivacion + '&Anio=' + document.getElementById('Anio').value;
    var ventana=window.open(url, 'Imprimir', 'width=800, height=600, resizable=no, scrollbars=yes'); ventana.focus();
}	
function AbrirEsquela(Imagen){
    if(Imagen == ""){
        alert('No se ha encontrado ning&uacute;n imagen adjuntado');
        return;
    }
    var url='<?php echo $UrlDir;?>generacion/derivacion/impresion.php?IdDerivacion=' + Imagen;
    var ventana=window.open(url, 'Imprimir', 'width=800, height=600, resizable=yes, scrollbars=yes'); ventana.focus();
}	
function ImagenEsquela(IdDependencia,NroKardex,Imagen){
    var Url="imagen.php?IdDependencia=" + IdDependencia + "&NroKardex=" + NroKardex
    var ventana=window.open(Url, 'Imprimir', 'width=800, height=600, resizable=no, scrollbars=yes'); ventana.focus();
}
function VerFacturacion(NroAtencion){
    if(NroAtencion != ''){
        var Url = "facturacion.php?NroAtencion=" + NroAtencion
        var ventana=window.open(Url, 'Imprimir', 'width=650, height=600, resizable=yes, scrollbars=yes'); ventana.focus();
    }
}
</script>
<div align="center">
<form id="form1" name="form1" method="post" action="">
  <table width="1152" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="7" align="center" class="Titulo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="ListaMenu">
            <thead>
            <tr>
              <td style="font-size:18px" align="center" height="30">Consulta de Kardex</td>
            </tr>
            </thead>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr align="center">
      <td colspan="7">
      <fieldset>
      	<table width="900" border="0" cellspacing="0">
          <tr align="center">
            <td width="149"><input type="button" name="cmdadjuntar" id="cmdadjuntar" value="Archivo Adjuntado" class="Boton" onclick="AbrirWord('<?php echo $row[9];?>')" /></td>
            <td width="128"><input type="button" name="cmdparte" id="cmdparte" value="Archivo de Parte" class="boton" onclick="AbrirParte('<?php echo $row[10];?>')" /></td>
            <td width="159"><input type="button" name="cmdseguimiento" id="cmdseguimiento" value="Seguimiento de Envios" class="boton" onclick="Seguimiento('<?php echo $NroKardex;?>');" /></td>
            <td width="100"><input type="button" name="cmdfacturacion" id="cmdfacturacion" value="Facturacion" class="boton" onclick="VerFacturacion('<?php echo isset($rowatencion[0])?$rowatencion[0]:'';?>')"/></td>
            <td width="282">&nbsp;</td>
            <td width="70"><input type="button" name="cmdregresar" id="cmdregresar" value="Regresar" class="boton" onclick="javascript:location.href='../../index.php'" /></td>
          </tr>
        </table>
      </fieldset>      
      </td>
    </tr>
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
   
    <tr>
      <td width="167" class="cabecera" align="right" style=" padding-right:5px">Buscar Por:</td>
      <td>
          <select id="Campo" name="Campo" class="select" style="width:150px">
              <option value="kardex.correlativo" <?php if(isset($_GET["Campo"])){if($_GET['Campo']=='kardex.correlativo'){ echo 'selected="selected"';}}?> >N&ordm;   K a r d e x</option>
              <option value="kardex_derivacion_situacion.titulo_numero" <?php if(isset($_GET["Campo"])){if($_GET['Campo']=='kardex_derivacion_situacion.titulo_numero'){ echo 'selected="selected"';}}?> >N&ordm;   T &iacute; t u l o</option>
          </select>
      </td>
       <td>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="NroKardex" id="NroKardex" class="inputtex" style="text-transform:uppercase" size="10" onkeypress="BuscarKardex(this, event);" value="<?php echo $Valor;?>" />      </td>
       <td align="right" class="cabecera">A&ntilde;o :         
        <?php 
          $cy = date('Y');
          $fy = 2006;
        ?>
         <select name="Anio" id="Anio" onchange="document.getElementById('NroKardex').focus();">
           <?php 
            for($i=$fy;$i<=$cy;$i++)
            {
              ?>
                <option value="<?php echo $i; ?>" <?php if ($anio==$i) { echo "selected='selected'";}?>><?php echo $i; ?></option>
              <?php   
            }
           ?>           
         </select>       
       </td>
       <td align="right" class="cabecera" style=" padding-right:5px">N&ordm; Ticket:</td>
      <td><input type="text" name="NroTicket" id="NroTicket" readonly="readonly" class="inputtext" style="text-transform:uppercase; width:50px" value="<?php echo (isset($rowatencion[0]))?$rowatencion[0]:'';?>" /></td>     
      <td>&nbsp;</td>
    </tr>    
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td class="cabecera" align="right" style=" padding-right:5px">Fecha:</td>
      <td colspan="6"><input type="text" name="Fecha" id="Fecha" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo $Fecha;?>" /></td>
    </tr>
    <tr>
      <td class="cabecera" align="right" style=" padding-right:5px">Servicio:</td>
      <td colspan="6"><input name="Servicio" type="text" class="inputtext" id="Servicio" style="text-transform:uppercase; width:350px" readonly="readonly" value="<?php echo (isset($row[1]))?$row[1]:'';?>" /></td>
    </tr>
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td class="cabecera" align="right" style=" padding-right:5px">N&ordm; de Escritura:</td>
      <td width="150"><input type="text" name="NroEscritura" id="NroEscritura" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[2]))?$row[2]:'';?>" /></td>
      <td width="176" class="cabecera" align="right">Foja de Inicio:</td>
      <td width="113">&nbsp;</td>
      <td width="149"><input type="text" name="FojaIncio" id="FojaIncio" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[4]))?$row[4]:'';?>" /></td>
      <td width="144" align="right" class="cabecera" style=" padding-right:5px">Serie de Inicio:</td>
      <td width="253"><input type="text" name="SerieInicio" id="SerieInicio" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[6]))?$row[6]:'';?>" /></td>
    </tr>
    <tr>
      <td class="cabecera" align="right" style=" padding-right:5px">N&ordm; Minuta:</td>
      <td><input type="text" name="NroMinuta" id="NroMinuta" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[3]))?$row[3]:'';?>" /></td>
      <td class="cabecera" align="right">Foja Final:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="FojaFinal" id="FojaFinal" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[5]))?$row[5]:'';?>" /></td>
      <td align="right" class="cabecera" style=" padding-right:5px">Serie Final:</td>
      <td><input type="text" name="SerieFinal" id="SerieFinal" class="inputtext" style="text-transform:uppercase; width:100px" readonly="readonly" value="<?php echo (isset($row[7]))?$row[7]:'';?>" /></td>
    </tr>
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7">
	  <table width="800" border="0" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu">
        <thead>
          <th width="100" align="center">Documento</th>
          <th width="110" align="center">N&uacute;mero</th>
          <th align="center">Participante</th>
          <th width="200" align="center">Participacion</th>
          <th width="22" align="center">&nbsp;</th>
        </thead>
<?php
			if(isset($row[8])){
                            $SQLParticipante = "SELECT kardex_participantes.idparticipante, cliente.nombres||' '||coalesce(cliente.ape_paterno,'')||' '||coalesce(cliente.ap_materno,''), cliente.dni_ruc, documento.descripcion, participacion.descripcion FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) INNER JOIN kardex ON (kardex.idkardex= kardex_participantes.idkardex) WHERE kardex_participantes.idkardex ='$row[8]' AND kardex.idnotaria='".$_SESSION['notaria']."'";
                            $Consulta1	 = $Conn->Query($SQLParticipante);
                            while($rowparticipante	= $Conn->FetchArray($Consulta1)){
                                if(strpos($rowparticipante[1],"!")){
                                    $Nombres = explode("!", $rowparticipante[1]);
                                }else{
                                    $Nombres = array('',$rowparticipante[1]);
                                }
                                    
?>
        <tr class="cabecera" bgcolor="#FFFFFF">
          <td align="center"><?php echo $rowparticipante[3];?></td>
          <td align="center"><?php echo $rowparticipante[2];?></td>
          <td align="left"><?php echo utf8_decode($Nombres[1]." ".$Nombres[0]);?></td>
          <td align="center"><?php echo $rowparticipante[4];?></td>
          <td align="center"><img src="<?php echo $UrlDir;?>imagenes/iconos/imprimir.png" width="19" height="19" style="cursor:pointer" onclick="Impresion('<?php echo $row[8];?>','<?php echo $rowparticipante[0];?>')" /></td>
        </tr>
<?php
				}
			}
?>
      </table></td>
    </tr>
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="7" align="left" class="cabecera" style="font-family:'Arial'; color:#009966; font-size:12px; font-weight:bold">Derivado a:</td>
    </tr>
<?php
        $row[15]=isset($row[15])?$row[15]:'';
		$SQLDerivaciones 	= "SELECT 
                                dependencia.descripcion, 
                                kardex_derivacion.iddependencia, 
                                kardex_derivacion.imagen 
                                FROM kardex_derivacion 
                                INNER JOIN dependencia ON (kardex_derivacion.iddependencia = dependencia.iddependencia) 
                                INNER JOIN kardex ON (kardex_derivacion.idkardex = kardex.idkardex) 
                                WHERE kardex.correlativo='".$row[15]."' 
                                    AND kardex.idnotaria='".$_SESSION['notaria']."'";
		$ConsDerivacion	 	= $Conn->Query($SQLDerivaciones);
		while ($rowderivacion   = $Conn->FetchArray($ConsDerivacion)){
?>    
        <tr>
          <td class="cabecera" align="right">&nbsp;</td>
          <td colspan="2" style="font-family:'Arial'; color:#009966; font-size:10px; font-weight:bold"><?php echo $rowderivacion[0];?></td>
          <td style="font-family:'Arial'; color:#009966; font-size:10px; font-weight:bold" align="center">&nbsp;</td>
          <td style="font-family:'Arial'; color:#009966; font-size:10px; font-weight:bold" align="center"><label onclick="ImagenEsquela('<?php echo $rowderivacion[1];?>','<?php echo $NroKardex;?>','<?php echo $rowderivacion[2];?>')" style="cursor:pointer"><img src="<?php echo $UrlDir;?>imagenes/buscar.png" width="19" height="19" /></label></td>
          <td style="font-family:'Arial'; color:#009966; font-size:10px; font-weight:bold">&nbsp;</td>
          <td style="font-family:'Arial'; color:#009966; font-size:10px; font-weight:bold">&nbsp;</td>
        </tr>
<?php 
		}
?>
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" style="color:#FF0000; font-weight:bold"><?php echo isset($Digitado)?$Digitado:'';?></td>
      <td colspan="4" style="color:#FF0000; font-weight:bold"><?php echo isset($Firmado)?$Firmado:'';?></td>
    </tr>
    <tr>
      <td class="cabecera" align="right">&nbsp;</td>
      <td colspan="6">&nbsp;</td>
    </tr>
  </table>
</form>
</div>
<script>
	document.form1.NroKardex.focus();
</script>
<?php
    CuerpoInferior();
?>
