<?php	
if(!session_id()){ session_start(); }
    include('../../config.php');
    include('../../config_seguridad.php');	
    include_once '../../libs/funciones.php';
    $Op                 = $_POST["Op"];
    $Id                 = isset($_POST["Id"])?$_POST["Id"]:'';	
    $Enabled            = "";
    $Enabled2           = "";
    $Guardar            = "";	
    $Usuario            = $_SESSION["Usuario"];	
    $Fecha              = date('d/m/Y');
    $FechaP             = date('d/m/Y');
    $FechaEscritura	    = date('d/m/Y');
    $FechaMinuta	    = date('d/m/Y');
    $Plazoi             = date('d/m/Y');
    $Plazof             = date('d/m/Y');
    $FecFirmaE          = date('d/m/Y');
    $FecPago            = "//";
    $Estado             = "<label style='color:#FF6600'>PENDIENTE</label>";
    $Anio               = date("Y");
    $Guardar            = "Op=$Op";
    if($Op==2 || $Op==4)
    {
        $Enabled = "readonly";
    }	
    $Enabled2 = "readonly";	
    if($Id!='')
    {
        $Select = "SELECT kardex.*, kardex_tipo.abreviatura, asigna_pdt.idacto_juridico 
                FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio)
                INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
                LEFT OUTER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
                WHERE kardex.idkardex='$Id' AND kardex.idnotaria = '".$_SESSION['notaria']."'";        
        $Consulta       = $Conn->Query($Select);
        $row = $Conn->FetchArray($Consulta);	

        $s = "SELECT idkardex from kardex where correlativo = '".$row['correlativo']."' AND idnotaria = '".$_SESSION['notaria']."'
                 ORDER by idkardex asc limit 1";
       
        $q = $Conn->Query($s);
        $r = $Conn->FetchArray($q);
        if($r[0]!=$row['idkardex'])
        {
            $Select         = "SELECT kardex.*, kardex_tipo.abreviatura, asigna_pdt.idacto_juridico 
                            FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio)
                            INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) 
                            LEFT OUTER JOIN asigna_pdt ON (asigna_pdt.idservicio = servicio.idservicio) 
                            WHERE kardex.idkardex='".$row[0]."' AND kardex.idnotaria='".$_SESSION['notaria']."'";            
            $Consulta  = $Conn->Query($Select);
            $row  = $Conn->FetchArray($Consulta); 
        }
        $descripcion = $row['descripcion'];
        $Usuario        = $_SESSION["Usuario"];
        $Fecha		= $Conn->DecFecha($row[2]);		
        $NumEscritura 	= $row[5];
        $NumMinuta      = $row[6];
        $Abre		= $row[26];
        $abreviatura = $row['abreviatura'];

        if ($row[5]=='' || $row[15]==0)
        {
            $SqlCo = "SELECT COUNT(kardex.correlativo) FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) WHERE kardex_tipo.abreviatura = '$Abre' AND kardex.idnotaria='".$_SESSION['notaria']."'";
            $ConsultaCo = $Conn->Query($SqlCo);
            $rowCo 	= $Conn->FetchArray($ConsultaCo);
            $NumEscritura   = (int)substr($row[3], strlen($Abre), 7);
            if ($rowCo[0]!=''){
                $NumEscritura = $rowCo[0];
            }
            $SqlCo2 = "SELECT COUNT(kardex.correlativo) FROM servicio INNER JOIN kardex ON (servicio.idservicio = kardex.idservicio) INNER JOIN kardex_tipo ON (servicio.idkardex_tipo = kardex_tipo.idkardex_tipo) WHERE kardex_tipo.abreviatura = '$Abre' AND kardex.idnotaria='".$_SESSION['notaria']."'";
            $ConsultaCo2 = $Conn->Query($SqlCo2);
            $rowCo2      = $Conn->FetchArray($ConsultaCo2);
            $NumMinuta   = 1;
            if ($rowCo2[0]!=''){
                $NumMinuta = $rowCo2[0];
            }
        }
        if ($row[20]!='')
        {
            $FechaEscritura = $Conn->DecFecha($row[20]);
        }
        if ($row[21]!=''){
            $FechaMinuta = $Conn->DecFecha($row[21]);
        }
        $Firmado	= $row[13];
        if ($row[15]==1){
            $Estado = "<label style='color:#003366'>GENERADO</label>";
        }
        if ($row[15]==2){
            $Estado = "<label style='color:#003366'>TERMINADO</label>";
        }
        if ($row[15]==3){
            $Estado = "<label style='color:#FF00000'>ANULADO</label>";
        }
        if ($row[27]!=''){
            $Plazoi = $Conn->DecFecha($row[27]);
        }
        if ($row[28]!=''){
            $Plazof = $Conn->DecFecha($row[28]);
        }
        if ($row[29]!=''){
            $FecFirmaE = $Conn->DecFecha($row[29]);
        }
        $Anio = $row[18];		
        $Sql = "SELECT nombres FROM usuario WHERE idusuario='".$row[16]."'";
        $ConsultaS  = $ConnS->Query($Sql);
        $rowS = $ConnS->FetchArray($ConsultaS);
        $Usuario    = $rowS[0];
        $SqlSe = "SELECT descripcion FROM servicio WHERE idservicio='".$row[4]."'";
        $ConsultaSe = $Conn->Query($SqlSe);
        $rowSe = $Conn->FetchArray($ConsultaSe);
        $Servicio   = $rowSe[0];
        
    }else die("No existe");
?>
<script type="text/javascript">
	var CantidadSgt     = 'Precio';
    var formatoMinDate  = '-<?php echo date("Y")-1968;?>y';
    var idKardex        = '<?php echo $Id;?>';
	var id_user         =  <?php echo $_SESSION["id_user"];?>;
	var FechaP          =  <?php echo $FechaP;?>;
	var Op              =  <?php echo $Op;?>;	
</script>
<script type="text/javascript" src="../../js/functions.js"></script>
<script src="jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify.css">
<script type="text/javascript" src="kardex.js"></script>
<script type="text/javascript">
<?php $timestamp = time();?>
$(function() {
        $('#file_upload').uploadify({
                'formData' : {
                        'timestamp' : '<?php echo $timestamp;?>',
                        'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
                        'correlativo': 'K001232'
                },
                'swf'      : 'uploadify.swf',
                'uploader' : 'uploadify.php',
                'buttonText': 'Escritura',                
                onUploadSuccess : function(file, data, response) {
                        if(response)
                        {
                            r = data.split("###");
                            if(r[0]==1)
                            {
                                alert('El archivo fue subido correctamente');
                                $("#archivo").val(r[1]);
                                $("#VerImagennn").attr("href","archivos/"+r[1]);
                                $("#VerImagennn").css("display","inline");
                            }
                            else 
                            {
                                alert(r[1]+' '+data);
                            }                            
                        }
                        else 
                        {
                            alert("Ha ocurrido un error al intentar subir el archivo "+file.name);
                        }
                        
                    },
                onUploadError : function(file, errorCode, errorMsg, errorString) {
                        alert('El archivo ' + file.name + ' no pudo ser subido: ' + errorString);
                    }
        });
        
        $('#file_uploadm').uploadify({
                'formData' : {
                        'timestamp' : '<?php echo $timestamp;?>',
                        'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
                        'correlativo': 'K001232'
                },
                'swf'      : 'uploadify.swf',
                'uploader' : 'uploadifym.php',
                'buttonText': 'Minuta',                
                onUploadSuccess : function(file, data, response) {
                        if(response)
                        {
                            r = data.split("###");
                            if(r[0]==1)
                            {
                                alert('El archivo fue subido correctamente');
                                $("#archivom").val(r[1]);
                                $("#VerImagennnm").attr("href","archivos/"+r[1]);
                                $("#VerImagennnm").css("display","inline");
                            }
                            else 
                            {
                                alert(r[1]+' '+data);
                            }                            
                        }
                        else 
                        {
                            alert("Ha ocurrido un error al intentar subir el archivo "+file.name);
                        }
                        
                    },
                onUploadError : function(file, errorCode, errorMsg, errorString) {
                        alert('El archivo ' + file.name + ' no pudo ser subido: ' + errorString);
                    }
        });
        
});
</script>
<form id="form1" name="form1" method="post" action="guardar.php?<?php echo $Guardar;?>" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">N&ordm; Kardex :</td>
    <td width="222">
    <?php $idkardex = $row[0]; ?>
      <input type="text" class="inputtext" style="text-align:center; font-size:12px; width:70px" name="0form1_correlativo" id="Id" maxlength="10" value="<?php echo $row[3];?>" onkeypress="CambiarFoco(this, 'Cliente');" readonly/>
      <input type="hidden" name="1form1_idkardex" value="<?php echo $row[0];?>" id="iddkardex" />
      <input type="hidden" name="0form1_archivo" id="Archivo" value="<?php echo $row[12];?>" />
    </td>
    <td width="160" align="right">
        <table width="160" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
                <td align="right"><?php echo $Estado;?></td>
            </tr>
        </table>	
    </td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Fecha : </td>
    <td colspan="2"><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha" id="Fecha" value="<?php echo $Fecha;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'Servicio');"/></td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Servicio : </td>
    <td colspan="2"><input type="text" class="inputtext" style="font-size:12px; width:350px;" name="Servicio" id="Servicio"  maxlength="100" value="<?php echo $Servicio;?>" <?php echo $Enabled2;?> onkeypress="CambiarFoco(event, 'NroEscritura');"/>
      <input type="hidden" name="0form1_idservicio" value="<?php echo $row[4];?>" />
    </td>
  </tr>
  <?php 
        $stylo = "display:none;";        
        $idop = "099";
        $value_p = "-";
        if(substr($row[3], 0, 1)=='N' || substr($row[3], 0, 1)=='K' || substr($row[3], 0, 1)=='V')
        {
            $array_verify=array(1,2,3,4,8,9,10,11,12,13,14,15,16,19,20,21,22,23);
            if(in_array($row['idacto_juridico'],$array_verify))
            {
                $stylo = "";
                $value_p = "100";                        
                $idop = "099";
            }
        }
        if($row['idoportunidad_pago']!="")
        {
            $idop = $row['idoportunidad_pago'];
        }
  ?>
  <tr style="<?php echo $stylo; ?>">
    <td width="100" class="TituloMant">Oportunidad de Pago :</td>
    <td colspan="2">
        <select name="0form1_idoportunidad_pago" id="idoportunidad_pago" class="inputtext" title="Oportunidad de Pago">
            <option value="">-Seleccione-</option>
            <?php 
                $s = "SELECT * from ro.oportunidad_pago order by idoportunidad_pago";
                $q = $Conn->Query($s);
                while($r = $Conn->FetchArray($q))
                {
                    $s = "";
                    if($r[0]==$idop)
                        $s = "selected";
                    ?>
                    <option value="<?php echo $r[0] ?>"  <?php echo $s; ?> ><?php echo $r[1]; ?></option>
                    <?php
                }
            ?>
        </select> 
    </td>
  </tr>
  <tr>
    <td width="100" class="TituloMant">Origen de Fondos :</td>
    <td colspan="2">
        <input type="text" class="inputtext" style="font-size:12px; width:450px; text-transform:uppercase;" name="0form1_origen_fondos" id="origen_fondos" value="<?php echo $row['origen_fondos'];?>" />
    </td>
  </tr>
  <tr>
    <td class="TituloMant">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="TituloMant">        
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Datos del Kardex</a></li>
            <li><a href="#tabs-2">Datos de Participantes</a></li>
            <!-- Inicializacion de Pestañas -->
            <?php
            if ((substr($row[3],0,1)=='A' && substr($row[3],0,2)!='AP') || (substr($row[3],0,1)=='E'))        
                echo '<li><a href="#tabs-3" onclick="$(\'#Ruta\').focus();">Datos de Viaje</a></li>';
            
            if ((substr($row[3],0,1)=='V'))
                echo '<li><a href="#tabs-3">Datos de Vehiculo</a></li>';
          
            if (substr($row[3],0,1)=='P')    
                echo  '<li><a href="#tabs-3">Datos de Poder</a></li>';
            
            if (substr($row[3],0,2)=='AP')
                echo '<li><a href="#tabs-3">Datos de Protestos</a></li>';        
            
            if(substr($row[3], 0, 1)=='N' || substr($row[3], 0, 1)=='K' || substr($row[3], 0, 1)=='V')
            {    
                $array_verify=array(1,2,3,4,8,9,10,11,12,13,14,15,16,19,20,21,22,23);
                if(in_array($row['idacto_juridico'],$array_verify))
                echo '<li><a href="#tabs-4">PDT Notario</a></li>';
            }
            ?>
            <li><a href="#tabs-5">Descripcion del Bien</a></li>
        </ul>
            <div id="tabs-1">
                <label class="TituloMant labels">N&ordm; Escritura :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_escritura" id="NroEscritura" value="<?php echo $NumEscritura;?>" <?php echo $Enabled;?> onkeypress="return permite(event, 'num'); CambiarFoco(event, 'EscrituraFecha');"/>
                <label class="TituloMant labels">Fecha Escritura : </label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_escritura_fecha" id="EscrituraFecha" value="<?php echo $FechaEscritura;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'NroMinuta');"/>
                <label class="TituloMant labels">N&ordm; Minuta :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_minuta"  value="<?php echo $row[6];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FecFirmaE');"/>
                <br/>
                <label class="TituloMant labels">Fecha Minuta :</label>
                <input type="text"  align="right" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_minuta_fecha" id="Minuta_Fecha" value="<?php echo $FechaMinuta;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FormaPago');"/>                
                <label class="TituloMant labels">Fec. Firma Esc.: </label>
                <input type="text"  align="right" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecfirmae" id="FecFirmaE" value="<?php echo $FecFirmaE;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FormaPago');"/>
                <div style="border-top:1px dotted #CCC; margin-top:10px; padding-top:10px;">
                <label class="TituloMant labels">Foja de Inicio :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_fojainicio" id="FojaInicio" value="<?php echo $row[8];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FojaFin');"/>
                <label class="TituloMant labels">Serie Inicio :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_serieinicio" id="SerieInicio" value="<?php echo $row[10];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'SerieFin');"/>
                <br/>
                <label class="TituloMant labels">Foja Final :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_fojafin" id="FojaFin" value="<?php echo $row[9];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'SerieInicio');"/>
                <label class="TituloMant labels">Serie Final :</label>
                <input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_seriefin" id="SerieFin" value="<?php echo $row[11];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'SerieFin');"/>
                <label class="TituloMant labels">Estado :</label>
                <input type="checkbox" name="Firmado2" id="Firmado2" <?php if ($Firmado==1) echo "checked='checked'"; ?> onclick="CambiaFirmado();" /><input type="hidden" name="0form1_firmado" id="Firmado" value="<?php echo $Firmado;?>" /> Firmado
                <br/>
                </div>
                <div style="border-top:1px dotted #CCC; margin-top:10px; padding-top:10px;">                
                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:150px; border:1px solid #dadada;" align="center">
                                <div style="display:inline-block; ">
                                <div id="queue" style="display:inline-block"></div>
                                <input id="file_uploadm" name="file_uploadm" type="file" multiple="true">
                                <input type="hidden" name="0form1_archivom" id="archivom" value="<?php echo $row['archivom'] ?>" />
                                <?php 
                                    if($row['archivom']!="")                    
                                        $d = "inline";                    
                                    else                     
                                        $d = "none";                    
                                ?>                                
                                </div>                               
                            </td>
                            <td style="border:1px solid #dadada; border-left:0"><a target="_blank" href="minutas/<?php echo $row['archivom'] ?>" style="display:<?php echo $d; ?>;cursor:pointer; font-size: 11px;" id="VerImagennnm"><img src="../../imagenes/iconos/word2.png" width="20" />Abrir Minuta</a></td>
                            <td style="width:150px;border:1px solid #dadada;border-left:0" align="center">
                                <div style="display:inline-block; ">
                                <div id="queue" style="display:inline-block"></div>
                                <input id="file_upload" name="file_upload" type="file" multiple="true">
                                <input type="hidden" name="0form1_archivo" id="archivo" value="<?php echo $row['archivo'] ?>" />
                                <?php 
                                    if($row['archivo']!="")                    
                                        $d = "inline";                    
                                    else                     
                                        $d = "none";                    
                                ?>                                
                                </div>
                            </td>
                            <td style="border:1px solid #dadada;border-left:0">
                                <a target="_blank" href="archivos/<?php echo $row['archivo'] ?>" style="display:<?php echo $d; ?>;cursor:pointer; font-size: 11px;" id="VerImagennn"><img src="../../imagenes/iconos/word2.png" width="20" />Abrir Escritura</a>
                            </td>
                        </tr>
                    </table>                
                </div>
            </div>
            <div id="tabs-2">                
                <div id="tabs-participantes">
                      <ul>
                        <li><a href="#tabs-otorgante">Otorgantes (OT)</a></li>
                        <li><a href="#tabs-afavor">A favor (FA)</a></li>
                        <li><a href="#tabs-interviniente">Intervinientes (IN)</a></li>
                      </ul>
                      <div id="tabs-otorgante">  
                        <div id="box-contenido">   
                        <label class="TituloMant" id="text_tipo">Otorgante: </label>                  
                        <input type="hidden" id="IdParticipante" />
                        <input type="text" class="inputtext" style="width:100px; text-transform:uppercase; font-size:12px" name="DocParticipante" id="DocParticipante" value="" onkeypress="CambiarFoco(event, 'Participante');"/>
                        <input type="hidden" id="Documento" name="Documento" />
                        <input type="text" class="inputtext" style="width:280px; text-transform:uppercase; font-size:12px" name="Participante" id="Participante" value="" onkeypress="CambiarFoco(event, 'Cantidad');"/>&nbsp;
                        <img src="../../imagenes/adduser.png" width="20" style="cursor:pointer;" onclick="NuevoParticipante('');" title="Agregar nuevo cliente"/>                                                                        
                        <span style="background:#FFFDC5; padding:2px 2px; border:1px dotted #666">
                            <label class="TituloMant" for="conyuge">Conyuge</label>
                            <input type="checkbox" name="conyuge" id="conyuge" value="1" title="Participa el conyuge"/>
                        </span>
                        <span id="box-porcentage" style="<?php echo $stylo; ?>">
                            <label class="TituloMant">%: </label>
                            <input type="text" class="inputtext" style="width:40px; text-transform:uppercase; font-size:12px" name="Porcentage" id="Porcentage" value="<?php echo $value_p; ?>" onkeypress=""/>
                        </span>
                        <select name="TipoParticipacion" id="TipoParticipacion" class="select" style="width:100px" >
                        <?php
                            $SelectLT   = "SELECT DISTINCT servicio_participacion.idparticipacion, 
                                                participacion.descripcion FROM participacion 
                                                INNER JOIN servicio_participacion ON 
                                                (participacion.idparticipacion = servicio_participacion.idparticipacion) 
                                                WHERE estado = 1 AND servicio_participacion.idservicio = '".$row[4]."' and tipo=1 ";
                            $ConsultaLT = $Conn->Query($SelectLT);
                            while($rowLT=$Conn->FetchArray($ConsultaLT)){
                                $Select = '';
                                if ($row[8]==$rowLT[0])
                                {
                                    $Select = 'selected="selected"';
                                }
                                echo '<option value="'.$rowLT[0].'" '.$Select.'>'.$rowLT[1].'</option>';
                               }
                        ?>
                        </select>                        
                        <div id="box-conyuge" style="display:none">
                            <label class="TituloMant">&nbsp;&nbsp;Conyuge: </label>      
                            <input type="hidden" id="IdParticipante_c" name="IdParticipante_c" value="" /> 
                            <input type="hidden" id="Documento_c" name="Documento_c" value="" />
                            <input type="text" class="inputtext" name="DocParticipante_c" id="DocParticipante_c" value="" style="width:100px; text-transform:uppercase; font-size:12px" />                            
                            <input type="text" class="inputtext" name="Participante_c" id="Participante_c" value=""  style="width:280px; text-transform:uppercase; font-size:12px" />&nbsp;
                            <img src="../../imagenes/adduser.png" width="20" style="cursor:pointer;" onclick="NuevoParticipante('_c');" title="Agregar nuevo cliente"/>                                                
                        </div>
                        <button id="addParticipante" style="float:right">Agregar</button>
                        <div style="clear:both"></div>
                        </div>                        
                      </div>
                      <div id="tabs-afavor"></div>
                      <div id="tabs-interviniente">
                        <label class="TituloMant">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Intervinientes: </label>      
                        <input type="hidden" id="IdParticipante_i" name="IdParticipante_i" value="" /> 
                        <input type="hidden" id="Documento_i" name="Documento_i" value="" />
                        <input type="text" class="inputtext" name="DocParticipante_i" id="DocParticipante_i" value="" style="width:100px; text-transform:uppercase; font-size:12px" placeholder="DNI/RUC" />                            
                        <input type="text" class="inputtext" name="Participante_i" id="Participante_i" value=""  style="width:280px; text-transform:uppercase; font-size:12px" placeholder="Nombre / Razon Social"/>&nbsp;
                        <img src="../../imagenes/adduser.png" width="20" style="cursor:pointer;" onclick="NuevoParticipante();" title="Agregar nuevo cliente"/>                                                
                        <select name="TipoParticipacion_i" id="TipoParticipacion_i" class="select" style="width:120px" >
                        <?php
                            $SelectLT   = "SELECT DISTINCT servicio_participacion.idparticipacion, 
                                                participacion.descripcion FROM participacion 
                                                INNER JOIN servicio_participacion ON 
                                                (participacion.idparticipacion = servicio_participacion.idparticipacion) 
                                                WHERE estado = 1 AND servicio_participacion.idservicio = '".$row[4]."' and tipo=3 ";
                            $ConsultaLT = $Conn->Query($SelectLT);
                            while($rowLT=$Conn->FetchArray($ConsultaLT))
                            {
                                $Select = '';
                                if ($row[8]==$rowLT[0])
                                {
                                    $Select = 'selected="selected"';
                                }
                                echo '<option value="'.$rowLT[0].'" '.$Select.'>'.$rowLT[1].'</option>';
                            }
                        ?>
                        </select> <br/>
                        <label class="TituloMant">&nbsp;&nbsp;Representando a: </label>  
                        <select name="list_participantes" id="list_participantes" class="select"></select>                        
                        <input type="text" name="nro_partida" id="nro_partida" value="" maxlenght="9" class="inputtext" style="width:80px" placeholder="Nro Partida"/>
                        <select name="idzonar" id="idzonar">
                            <option value="">-Zonal Registral-</option>
                            <?php 
                                $sql = "select * from ro.zona_registral order by idzona";
                                $q = $Conn->Query($sql);
                                while($r = $Conn->FetchArray($q))
                                {
                                    echo "<option value='".$r[0]."'>".$r[1]."</option>";
                                }
                            ?>
                        </select>
                        <button id="addParticipantei">Agregar</button>
                      </div>
                    </div>
                <?php
                    $stylo = "display:none;";
                    $value_p = "100";
                ?>
                <div style="">
                <table id="ListaMenu2" width="100%" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" >
                    <thead>
                        <tr>
                            <th title="Cabecera">Tipo</th>
                            <th title="Cabecera">Doc</th>
                            <th title="Cabecera" width="80">N&uacute;mero</th>
                            <th title="Cabecera">Participante</th>
                            <th title="Cabecera" width="100">Participaci&oacute;n</th>                            
                            <th title="Cabecera" >Representa a</th>
                            <th title="Cabecera" width="20">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            $NumRegs = 0;
                            $SQL2 = "SELECT 
                                    kardex_participantes.idkardex, 
                                    documento.descripcion, 
                                    kardex_participantes.idparticipante, 
                                    cliente.dni_ruc, 
                                    cliente.nombres, 
                                    kardex_participantes.idparticipacion, 
                                    participacion.descripcion,
                                    kardex_participantes.porcentage 
                                    FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) 
                                    INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) 
                                    INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) 
                                    WHERE kardex_participantes.idkardex = $Id";
                            $Consulta2 = $Conn->Query($SQL2);           
                            while($row2 = $Conn->FetchArray($Consulta2))
                            {
                                    $NumRegs = $NumRegs + 1;                
                                    $EnabledF = $Enabled;
                                    $row2[9]=isset($row2[9])?$row2[9]:'';
                                    if ($row2[9]==0){
                                        $EnabledF = 'readonly';
                                    }
                                    $EnabledC = $Enabled;
                                    $row2[8]=isset($row2[8])?$row2[8]:'';
                                    if ($row2[8]!=''){
                                        $EnabledC = 'readonly';
                                    }
                                    $NombresD = explode("!", $row2[4]);
                            }
                            echo "<script> var nDest = $NumRegs; var nDestC = $NumRegs; </script>";
                            ?>
                            </tbody>
                        </table>
                        <input type="hidden" name="ConParticipantes" id="ConParticipantes" value="<?php echo $NumRegs;?>"/>
                </div>
        </div>
<?php
	if ((substr($row[3],0,1)=='A' && substr($row[3],0,2)!='AP') || (substr($row[3],0,1)=='E')){
?>
   <div id="tabs-3">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr style="display:none">
            <td width="150" class="TituloMant">Nombre Hijo :</td>
            <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_hijo" id="Hijo" value="<?php echo $row[22];?>" <?php echo $Enabled;?> /></td>
          </tr>
          <tr>
            <td class="TituloMant">Ruta :</td>
            <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_ruta" id="Ruta" value="<?php echo $row[23];?>" <?php echo $Enabled;?> /></td>
          </tr>
          <tr>
            <td class="TituloMant">Motivo :</td>
            <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_motivo" id="Motivo" value="<?php echo $row[24];?>" <?php echo $Enabled;?> /></td>
          </tr>
          <tr>
            <td class="TituloMant">V&iacute;a :</td>
            <td><select name="0form1_via" id="Via">
              <option value="AEREA" <?php if ($row[25]=='AEREA') { echo "selected='selected'";}?>>AEREA</option>
              <option value="TERRESTRE" <?php if ($row[25]=='TERRESTRE') { echo "selected='selected'";}?>>TERRESTRE</option>
              <option value="TERRESTRE/AEREA" <?php if ($row[25]=='TERRESTRE/AEREA') { echo "selected='selected'";}?>>TERRESTRE/AEREA</option>
            </select></td>
          </tr>
          <tr>
            <td class="TituloMant">Fecha Salida :</td>
            <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha_salida" id="fsalida" value="<?php if($row['fecha_salida']!="") echo $Conn->DecFecha($row['fecha_salida']); else echo date('d/m/Y');?>" <?php echo $Enabled;?> /></td>
          </tr>
          <tr>
            <td class="TituloMant">Fecha Retorno :</td>
            <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_fecha_retorno" id="fretorno" value="<?php if($row['fecha_retorno']!="") echo $Conn->DecFecha($row['fecha_retorno']); else echo date('d/m/Y');?>" <?php echo $Enabled;?> /></td>
          </tr>
          <tr>
            <td class="TituloMant">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
    </div>        
<?php
	}
        
	if ((substr($row[3],0,1)=='V')){
        ?>        
        <div id="tabs-3">
            <div>
                <label class="TituloMant labels">Placa :</label>
                <input type="text"  align="left" name="0form1_placa" id="Placa" value="<?php echo $row['placa'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Clase :</label>
                <input type="text"  align="left" name="0form1_clasev" id="Clasev" value="<?php echo $row['clasev'];?>" <?php echo $Enabled;?>  class="inputtext" style="font-size:12px; width:130px; text-transform:uppercase;" />
                <label class="TituloMant labels" style="width:100px">Marca :</label>
                <input type="text"  align="left" name="0form1_marcav" id="Marcav" value="<?php echo $row['marcav'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;"  />
                <br/>
                <label class="TituloMant labels">Año Fabric. :</label>
                <input type="text"  align="left" name="0form1_aniofabv" id="Aniofabv" value="<?php echo $row['aniofabv'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Modelo :</label>
                <input type="text"  align="left" name="0form1_modelov" id="Modelov" value="<?php echo $row['modelov'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Color :</label>
                <input type="text"  align="left" name="0form1_colorv" id="Colorv" value="<?php echo $row['colorv'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <br/>
                <label class="TituloMant labels">Motor. :</label>
                <input type="text"  align="left" name="0form1_motorv" id="Motorv" value="<?php echo $row['motorv'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:130px; text-transform:uppercase;" />
                <label class="TituloMant labels" style="width:100px">Cilindros :</label>
                <input type="text"  align="left" name="0form1_cilindrosv" id="Cilindros" value="<?php echo $row['cilindrosv'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Serie Nro :</label>
                <input type="text"  align="left" name="0form1_seriev" id="Seriev" value="<?php echo $row['seriev'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:130px; text-transform:uppercase;" />
                <br/>
                <label class="TituloMant labels">Ruedas. :</label>
                <input type="text"  align="left" name="0form1_ruedasv" id="Ruedasv" value="<?php echo $row['ruedasv'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Combustible :</label>
                <input type="text"  align="left" name="0form1_combustiblev" id="Combustiblev" value="<?php echo $row['combustiblev'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
                <label class="TituloMant labels">Carroceria :</label>
                <input type="text"  align="left" name="0form1_carroceriav" id="Carroceriav" value="<?php echo $row['carroceriav'];?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:130px; text-transform:uppercase;" />
                <br/>
                <label class="TituloMant labels">Fecha Incripcion :</label>
                <input type="text"  align="left" name="3form1_fechaincripcionv" id="Fechainscripcionv" value="<?php if($row['fechaincripcionv']!=""){ echo $Conn->DecFecha($row['fechaincripcionv']);} else { echo date('d/m/Y'); }?>" <?php echo $Enabled;?> class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" />
            </div>
        </div>
        <?php }
        
	if (substr($row[3],0,1)=='P'){
?>
            <div id="tabs-3">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="150" class="TituloMant">Motivo :</td>
                    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_motivo" id="Motivo" value="<?php echo $row[24];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'MinutaFecha');"/></td>
                  </tr>
                  <tr>
                    <td class="TituloMant">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
            </div>
<?php
	}
	if (substr($row[3],0,2)=='AP'){
?>
            <div id="tabs-3">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="150" class="TituloMant">Solicitante :</td>
                    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:350px; text-transform:uppercase;" name="0form1_hijo" id="Hijo" value="<?php echo $row[22];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'MinutaFecha');"/></td>
                  </tr>
                  <tr>
                    <td class="TituloMant">Monto :</td>
                    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:100px; text-transform:uppercase; text-align:right" name="0form1_ruta" id="Ruta" value="<?php echo number_format($row[23], 2);?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'MinutaFecha');"/></td>
                  </tr>
                  <tr>
                    <td class="TituloMant">Fecha Notificaci&oacute;n :</td>
                    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_motivo" id="Motivo" value="<?php echo $row[24];?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Placa');"/></td>
                  </tr>
                  <tr>
                    <td class="TituloMant">T&iacute;tulo Valor :</td>
                    <td><select name="0form1_via" id="Via">
                      <option value="LETRA" <?php if ($row[25]=='LETRA' || $row[4]==275) { echo  "selected='selected'";}?>>LETRA</option>
                      <option value="PAGARE" <?php if ($row[25]=='PAGARE' || $row[4]==276) { echo  "selected='selected'";}?>>PAGARE</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="TituloMant">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
            </div>
            
<?php
	}
        
        if(substr($row[3], 0, 1)=='N' || substr($row[3], 0, 1)=='K' || substr($row[3], 0, 1)=='V'){
            
            $array_verify=array(1,2,3,4,8,9,10,11,12,13,14,15,16,19,20,21,22,23);
            if(in_array($row['idacto_juridico'], $array_verify)){
            ?>    
            <div id="tabs-4">
                <?php
                /*
                * Recuperacion de los Actos Juridicos
                */
               $sqlaj="SELECT 
               pdt.acto_juridico.idacto_juridico,
               pdt.acto_juridico.descripcion as acto_juridico,
               pdt.documento_notarial.descripcion as documento_notarial,
               public.asigna_pdt.idservicio
             FROM
               public.asigna_pdt
               INNER JOIN pdt.documento_notarial ON (public.asigna_pdt.iddocumento_notarial = pdt.documento_notarial.iddocumento_notarial)
               INNER JOIN pdt.acto_juridico ON (public.asigna_pdt.idacto_juridico = pdt.acto_juridico.idacto_juridico)
             WHERE
               asigna_pdt.idservicio = {$row['idservicio']}";
               
                ?>
                <div id="pdt_notario">
                     <ul>
                        <li><a href="#acto_juridico">Acto Jur&iacute;dico</a></li>
                        <li><a href="#bienes">Bienes</a></li>
                     </ul>
                    <div id="acto_juridico">
                        <fieldset style="text-align: center;">
                            <label for="idacto_juridico">Acto Juridico</label>
                           <select name="idacto_juridico" id="idacto_juridico">
                               <option></option>
                               <?php 
                               echo opt_combo($sqlaj, null, $Conn);
                               ?>
                           </select>
                        </fieldset>
                        <table width="100%" border="0">                  
                                <tr>
                                    <td><label for="idmoneda_aj">Moneda</label></td>
                                    <td>
                                          <select name="0form1_idmoneda" id="idmoneda_aj">
                                                <?php 
                                                echo opt_combo("select * from moneda", $row['idmoneda'], $Conn);
                                                ?>
                                          </select>
                                      </td>
                                      <td>Precio Operaci&oacute;n :</td>
                                      <td>
                                          <input type="text" align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_monto" id="PrecioOperacion" value="<?php if(isset($row[26])){ echo $row[26]; }else{ echo "0.00"; }?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FormaPago'); return permite(event, 'num');"/>
                                      </td>
                                </tr>
                                <?php
                                    if($row['idacto_juridico']==4 || $row['idacto_juridico']==10){
                                ?>
                                <tr id="mediospago" style="visibility: hidden;">
                                            <td colspan="2">
                                                <label>¿Exhibi&oacute; medio de pago?:</label> 
                                            </td>
                                            <td>
                                                <label><input type="radio"  align="left"  style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_exmedpago" id="ExMedPago" value="1" <?php if($row[30] != 0){echo "Checked";}?> <?php echo $Enabled;?> onclick="ocultarTR();"/>Si</label>
                                         
                                                <label><input type="radio"  align="left"  style="font-size:12px; width:80px; text-transform:uppercase;" name="0form1_exmedpago" id="ExMedPago" value="0" <?php if($row[30] == 0){echo "Checked";}?> <?php echo $Enabled;?> onclick="ocultarTR();"/>No</label>
                                            </td>
                                </tr>  
                                      <?php 
                                    }
                                  ?>
                                 <?php
                                 if($row['idacto_juridico']!=10){
                                 ?>
                                <tr id="plazos" style="visibility: hidden;">
                                    <td class="TituloMant">Plazo Inicial:</td>
                                    <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_plazoinicial" id="Plazoi" value="<?php echo $Plazoi;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Plazof');"/></td>
                                    <td class="TituloMant">Plazo Final:</td>
                                    <td><input type="text"  align="right" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_plazofinal" id="Plazof" value="<?php echo $Plazof;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FojaInicio');"/></td>
                                </tr> 
                                            <?php
                                                }
                                            ?>
                                <tr>
                                    <td colspan="4">
                                        
                                        <fieldset id="forma_pago_tr1" style="display: <?php if($row[30]==1){ echo 'inline;'; }else{ echo 'none;'; } ?>" class="ui-widget-content ui-corner-all">
                                            <legend class="ui-widget-header ui-corner-all">Detalle de Pago</legend>
                                            <table>
                                            <tr>
                                                <td class="TituloMant">Medio de Pago : </td>
                                                <td>
                                                    <select name="idforma_pago" id="FormaPago" class="select" style="font-size: 12px;width: 150px;" >
                                                      <?php
                                                       echo opt_combo2("SELECT * FROM forma_pago", $row[26], $Conn);
              //                                        ?>
                                                    </select>
                                                </td>
                                                <td class="TituloMant">Entidad Financiera:</td>
                                                <td>
                                                    <select name="EntidadFinanciera" id="EntidadFinanciera" class="select" style="font-size: 12px;width: 150px;" >
                                                      <?php
                                                      echo  opt_combo("SELECT * FROM pdt.entidadfinanciera", $row['idacto_juridico'], $Conn);
                                                      ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="TituloMant">Moneda</td>
                                                <td>
                                                    <select name="idmoneda" id="Moneda" class="select" style="font-size: 12px;width: 150px;" >
                                                      <?php
                                                       echo opt_combo("SELECT * FROM moneda", 0, $Conn);
                                                       ?>
                                                    </select>
                                                </td>
                                                <td class="TituloMant">Monto pagado:</td>
                                                <td><input type="text" align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="montopagado" id="MontoPagado" value="<?php echo $MontoPagado;?>" <?php echo $Enabled;?> onkeypress="return permite(event, 'num');"/></td>
                                            </tr>
                                            <tr>
                                                <td class="TituloMant">Fecha de pago:</td>
                                                <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;text-align: center;" name="fechapago" id="FechaPago" value="<?php echo $FecPago;?>" <?php echo $Enabled;?>/></td>
                                                <td class="TituloMant">N&ordm; Documento Pago:</td>
                                                <td><input type="text" align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="nromediopago" id="NroMedioPago" value="<?php echo $DocPago?>" <?php echo $Enabled;?> onkeypress="return permite(event, 'num');"/></td>
                                            </tr>
                                            <tr>
                                                <td class="TituloMant"></td>
                                                <td colspan="2" class="TituloMant">
                                                </td>
                                                <td class="TituloMant">
                                                    
                                                </td>
                                            </tr>
                                        </table>
                                        <button id="addMedioPago" style="float: right;">Agregar Medio Pago</button>
                                        <table width="516" border="1" cellspacing="1" bordercolor="#000000" bgcolor="#ECECEC" id="ListaMenu4" align="center" style="display: <?php if($row['exmedpago']==1){ echo 'inline;'; }else{ echo 'none;'; } ?>">
                                            <tr>
                                                <th title="Cabecera" width="180" height="20">Medio Pago</th>
                                                <th title="Cabecera" width="80">Moneda</th>
                                                <th title="Cabecera" width="70">Monto</th>
                                                <th title="Cabecera" width="70">Fecha</th>
                                                <th title="Cabecera" width="70">Nº Transacción</th>
                                                <th title="Cabecera" width="200">Ent. Fin.</th>
                                                <th title="Cabecera" width="16"></th>
                                            </tr>
                                            <tbody>
                                            <?php 
              //                                print_r($Conn->Execute("SELECT * FROM detalle_forma_pago"));
                                              $NumRegs = 0;
                                              $Select     = " SELECT * FROM detalle_forma_pago WHERE idkardex={$row[0]}";
                                              $Consulta   = $Conn->Query($Select);
                                              while($row=$Conn->FetchArray($Consulta)){
                                                  $NumRegs++;
                                                  $Consulta1 = $Conn->Query(" SELECT * FROM forma_pago WHERE idforma_pago='".$row[1]."'");
                                                  $Consulta2 = $Conn->Query(" SELECT * FROM moneda WHERE idmoneda='".$row[2]."'");
                                                  $Consulta3 = $Conn->Query(" SELECT * FROM pdt.entidadfinanciera WHERE identidad_financiera='".$row[6]."'");
                                                  $row1=$Conn->FetchArray($Consulta1);
                                                  $row2=$Conn->FetchArray($Consulta2);
                                                  $row3=$Conn->FetchArray($Consulta3);
                                            ?>
                                            <tr>
                                                <td width="180" height="20" style="text-align: center;"><input type="hidden" id="0formX<?php echo $NumRegs;?>_idkardex" name="0formX<?php echo $NumRegs;?>_idkardex" value="<?php echo $Id;?>"><input type="hidden" id="0formX<?php echo $NumRegs;?>_idforma_pago" name="0formX<?php echo $NumRegs;?>_idforma_pago" value="<?php echo $row[1];?>"><?php echo $row1[1];?></td>
                                                <td width="80" style="text-align: center;"><input type="hidden" id="0formX<?php echo $NumRegs;?>_idmoneda" name="0formX<?php echo $NumRegs;?>_idmoneda" value="<?php echo $row[2];?>"><?php echo $row2[1];?></td>
                                                <td width="70" style="text-align: center;"><input type="hidden" id="0formX<?php echo $NumRegs;?>_montopagado" name="0formX<?php echo $NumRegs;?>_montopagado" value="<?php echo $row[3];?>"><?php echo $row[3];?></td>
                                                <td width="70" style="text-align: center;"><input type="hidden" id="3formX<?php echo $NumRegs;?>_fechapago" name="0formX<?php echo $NumRegs;?>_fechapago" value="<?php echo $row[4];?>"><?php echo $Conn->DecFecha($row[4]);?></td>
                                                <td width="200" style="text-align: center;"><input type="hidden" id="0formX<?php echo $NumRegs;?>_nromediopago" name="0formX<?php echo $NumRegs;?>_nromediopago" value="<?php echo $row[5];?>"><?php echo $row[5];?></td>
                                                <td width="200" style="text-align: center;"><input type="hidden" id="0formX<?php echo $NumRegs;?>_identidad_financiera" name="0formX<?php echo $NumRegs;?>_identidad_financiera" value="<?php echo $row[6];?>"><?php echo $row3[1];?></td>
                                                <td width="16" style="text-align: center;"><img src="../../imagenes/iconos/eliminar.png" width="16px" class="quit" style="cursor: pointer;" title="Eliminar elemento"></td>
                                            </tr>
                                            <?php 
                                              }
                                              echo "<script> var nDestx = $NumRegs; var nDestCx = $NumRegs; </script>";                                
                                            ?>                              
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="ConMedioPago" id="ConMedioPago" value="<?php echo $NumRegs;?>"/>
                                        </fieldset>
                                        
                                        
                                    </td>
                                </tr>
                              <?php
                                /*
                                 * Verificacion  de Constancia de Plazos
                                 */  
                                $var_for_plazos=array(2,6,7,10,17,18,20);
                                if(in_array($row['idacto_juridico'], $var_for_plazos)){
                                  ?>
                                  <tr>
                                      <td class="TituloMant">Plazo Inicial:</td>
                                      <td><input type="text"  align="left" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_plazoinicial" id="Plazoi" value="<?php echo $Plazoi;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'Plazof');"/></td>
                                      <td class="TituloMant">Plazo Final:</td>
                                      <td><input type="text"  align="right" class="inputtext" style="font-size:12px; width:80px; text-transform:uppercase;" name="3form1_plazofinal" id="Plazof" value="<?php echo $Plazof;?>" <?php echo $Enabled;?> onkeypress="CambiarFoco(event, 'FojaInicio');"/></td>
                                  </tr> 
                                  <?php
                                }                     
                              ?>
                              </table>
                    </div>
                    <div id="bienes">
                        <div id="formBienes" style="display: none;">
                        <fieldset class="ui-widget-content ui-corner-all">
                            <legend class="ui-widget-header ui-corner-all">Formulario de Bienes [PDT]</legend>
                            <fieldset>
                               
                                <div style="width:30%; float:left;">
                                    <label><input type="radio" name="tipo_bien" class="tipo_bien" value="B"/>Bienes</label><br/>
                                    <label><input type="radio" name="tipo_bien" class="tipo_bien" value="A"/>Acciones&nbsp;Y&nbsp;Derechos</label>
                                </div>
                                <div style="width: 40%;float: right;">
                                    <label>Bien del Acto Jur&iacute;dico</label><br/>
                                    <select name="idbien" id="idbien" style="width:80%;">
                                        <option value="0"></option>
                                        <?php 
                                            if($abreviatura=='V'){
                                                echo opt_combo("SELECT * FROM pdt.bien WHERE idbien='09' ORDER BY descripcion", null, $Conn);    
                                            }else
                                                echo opt_combo("SELECT * FROM pdt.bien ORDER BY descripcion", null, $Conn);
                                        ?>
                                    </select>
                                </div>
                                <div style="clear:both; margin-top:5px;">
                                    <label>Nro Partida: </label>
                                    <input type="text" name="nropartida" id="nropartida" value="" maxlength="10" style="width:80px" />
                                    <label style"margin-left:10px">&nbsp;&nbsp;&nbsp;Zonal Registral: </label>
                                    <select name="idzona" id="idzona">
                                        <option value="">-Ninguna-</option>
                                    <?php 
                                        $sql = "select * from ro.zona_registral order by idzona";
                                        $q = $Conn->Query($sql);
                                        while($r = $Conn->FetchArray($q))
                                        {
                                            echo "<option value='".$r[0]."'>".$r[1]."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div style="width: 100%;clear: both;"><hr/></div>
                                <div id="codigosplacas" style="display: none;">
                                    <fieldset class="ui-widget-content ui-corner-all">
                                        <legend class="ui-widget-header ui-corner-all">Datos Selecci&oacute;n:</legend>
                                        <label><input type="radio" name="tipo_codigoplacas" class="tipo_codigoplacas" value="1" />N° de Placa</label>
                                        <label><input type="radio" name="tipo_codigoplacas" class="tipo_codigoplacas" value="2" />N° de Serie</label>
                                        <label><input type="radio" name="tipo_codigoplacas" class="tipo_codigoplacas" value="3" checked="checked"/>N° de Motor</label><br/>
                                        <input type="text" name="numero_codigoplacas" id="numero_codigoplacas" style="margin:0 auto;"/>
                                    </fieldset>
                                </div>
                                <div id="serie" style="display: none;">
                                    <fieldset class="ui-widget-content ui-corner-all">
                                        <legend class="ui-widget-header ui-corner-all">Datos Selecci&oacute;n</legend>
                                        <label>N° de Serie para Maquinarias y Equipos:</label>
                                        <input type="text" name="numserie" id="numserie"/>
                                    </fieldset>
                                </div>
                                <div id="otro" style="display: none;">
                                    <fieldset class="ui-widget-content ui-corner-all">
                                        <legend class="ui-widget-header ui-corner-all">Datos Selecci&oacute;n</legend>
                                        <label>Detalle del Bien materia del acto jur&iacute;dico:</label>
                                        <input type="text" name="descotro" id="descotro"/>
                                    </fieldset>
                                </div>
                                <div id="origen" style="display:none;">
                                    <fieldset class="ui-widget-content ui-corner-all">
                                        <legend class="ui-widget-header ui-corner-all">Datos Selecci&oacute;n</legend>
                                        <div class="box-all">
                                            <div class="box-head">
                                                <label>Origen del Bien:</label>
                                                <label><input type="radio" class="origen" name="origen" value="1"/>Nacional</label>
                                                <label><input type="radio" class="origen" name="origen" value="2"/>Extrangero</label>
                                            </div>
                                            <div>
                                                <div id="divubigeo" style="display:none;width: 65%;float:left;">
                                                    <label>Ubicaci&oacute;n Geogr&aacute;fica:</label>
                                                    <button id="searchUbigeo">Ubigeo</button>
                                                    <input type="text" name="ubigeo" id="ubigeo" class="ui-state-disabled" readonly="readonly" value="9999" style="width: 80px;"/>
                                                </div>
                                                <div id="divpaises" style="display:none;width:50%;float: left;">
                                                    <label for="idpais">Pa&iacute;s</label>
                                                    <select id="idpais" name="idpais" style="width:200px;">
                                                        <option value="0"></option>
                                                        <?php 
                                                            echo opt_combo("SELECT * FROM pdt.pais WHERE idpais<>'9589' ORDER BY nombre", null, $Conn);
                                                        ?>
                                                    </select>
                                                </div>
                                                <fieldset>
                                                    <label>Fecha de Adquisici&oacute;n o Construcci&oacute;n del bien enajenado(Si el Transferente  es persona Natural)</label>
                                                    <input type="text" name="fecha_construccion" id="fecha_construccion" value="//"/>
                                                </fieldset>
                                            </div>
                                        </div>
                                       
                                        
                                    </fieldset>
                                    
                                </div>
                            </fieldset>
                        </fieldset>
                    </div>
                        <fieldset class="ui-widget-content ui-corner-all">
                            <legend class="ui-widget-header ui-corner-all">Listado de Bienes Asociados a la Operacion</legend>
                            <div style="float: right;width: 90px;">
                                <button id="nuevoBien">Nuevo</button>
                                <button id="updateBien">Modificar</button>
                                <button id="deleteBien">Eliminar</button>
                            </div>
                            <div  style="width: 620px;overflow: scroll;height: 200px;float: left;">
                                    <table id="TablaBienes" width="600"  cellspacing="1"   class="TablaData">
                                    <tr class="ui-widget-header ui-corner-all">
                                                <td title="Cabecera" width="80" height="20">Tipo Bien</td>
                                                <td title="Cabecera" width="80">Bienes</td>
                                                <td title="Cabecera">Registro</th>
                                                <td title="Cabecera" width="150">N°Serie/Placa/Motor</td>
                                                <td title="Cabecera" width="150">N°Serie</td>
                                                <td title="Cabecera" width="150">Origen</td>
                                                <td title="Cabecera" width="150">Ubicaci&oacute;n Geogr&aacute;fica</td>
                                                <td title="Cabecera" width="150">Reg. Bien</td>
                                                <td title="Cabecera" width="150">Nro Partida</td>
                                                <td title="Cabecera" width="150">Zona</td>
                                            </tr>  
                            <?php
                                    $NumRegs = 0;
                                    $SQL2 = "SELECT 
                                                  public.kardex_bien.*,
                                                  pdt.bien.descripcion as bien,
                                                  public.ubigeo.descripcion as ubigeo_nombre,
                                                  pdt.pais.nombre as pais                                                  
                                                FROM
                                                  public.kardex_bien
                                                  INNER JOIN pdt.bien ON (public.kardex_bien.idbien = pdt.bien.idbien)
                                                  LEFT OUTER JOIN pdt.pais ON (public.kardex_bien.idpais = pdt.pais.idpais)
                                                  LEFT OUTER JOIN public.ubigeo ON (public.ubigeo.idubigeo = public.kardex_bien.ubigeo)
                                                WHERE public.kardex_bien.idkardex = $Id";
                                    $Sql2 = "SELECT";        
                                    $Consulta2 = $Conn->Query($SQL2);			
                                    $htmlTDS="";
                                    while($row2 = $Conn->FetchArray($Consulta2)){
                                            $NumRegs = $NumRegs + 1;				
                                            $tipo_bien=$row2['tipo_bien'];
                                            $tipo_bien_nombre=($tipo_bien=='B')?'BIENES':'Acciones&nbsp;Y&nbsp;Derechos';
                                            $idbien=$row2['idbien'];

                                            $partida=$row2['nropartida'];
                                            $idzona=$row2['idzona'];

                                            $idbien_nombre=$row2['bien'];
                                            $tipo_codigoplacas=$row2['tipo_codigoplacas'];
                                            $tipo_codigoplacas_nombre="";
                                            $codigosplacas_permitidos=array(1,7,9);
                                            if(in_array($idbien,$codigosplacas_permitidos)){
                                                switch(intval($tipo_codigoplacas)){
                                                    case 1:$tipo_codigoplacas_nombre="Nº de placa";break;
                                                    case 2:$tipo_codigoplacas_nombre="Nº de serie";break;
                                                    case 3:$tipo_codigoplacas_nombre="Nº de motor";break;
                                                    default:break;
                                                }
                                            }            
                                            $numero_codigoplacas=$row2['numero_codigoplacas'];
                                            $numserie=$row2['numserie'];
                                            $descotro=$row2['descotro'];
                                            $origen=$row2['origen'];
                                            $origen_nombre="";
                                            if($origen!=0){
                                                $origen_nombre=($origen==1)?'NACIONAL':'EXTRANGERO';
                                            }
                                            $ubigeo=$row2['ubigeo'];
                                            $ubigeo_nombre="";
                                            if($ubigeo!=""){
                                                $ubigeo_nombre=$row2['ubigeo_nombre'];                                                
                                            }                
                                            $idpais=$row2['idpais'];
                                            $idpais_nombre=$row2['pais'];
                                            $fecha_construccion=($row2['fecha_construccion']!="")?reformatFecha($row2['fecha_construccion']):'';		
                                            $variables_hidden="";	
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_idkardex' value='".$Id."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_idbien' value='".$idbien."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_tipo_bien' value='".$tipo_bien."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_tipo_codigoplacas' value='".$tipo_codigoplacas."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_numero_codigoplacas' value='".$numero_codigoplacas."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_numserie' value='".$numserie."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_descotro' value='".$descotro."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_origen' value='".$origen."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_ubigeo' value='".$ubigeo."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_idpais' value='".$idpais."' />";
                                            $variables_hidden.="<input type='hidden' name='3formB".$NumRegs."_fecha_construccion' value='".$fecha_construccion."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_nropartida' value='".$partida."' />";
                                            $variables_hidden.="<input type='hidden' name='0formB".$NumRegs."_idzona' value='".$idzona."' />";
                                            
                                            $celda1 = $tipo_bien_nombre." ".$variables_hidden;
                                            $celda2 = $idbien_nombre;
                                            $celda3 = $tipo_codigoplacas_nombre;
                                            $celda4 = $numero_codigoplacas;
                                            $celda5 = $numserie;
                                            $celda6 = $origen_nombre;
                                            $celda7 = $ubigeo_nombre;
                                            $celda8 = $descotro;
                                            $celda9 = $partida;
                                            $celda10 = $idzona;
                                            $htmlTDS.="<tr>";
                                            $htmlTDS.="<td>".$celda1."</td>";
                                            $htmlTDS.="<td>".$celda2."</td>";
                                            $htmlTDS.="<td>".$celda3."</td>";
                                            $htmlTDS.="<td>".$celda4."</td>";
                                            $htmlTDS.="<td>".$celda5."</td>";
                                            $htmlTDS.="<td>".$celda6."</td>";
                                            $htmlTDS.="<td>".$celda7."</td>";
                                            $htmlTDS.="<td>".$celda8."</td>";
                                            $htmlTDS.="<td>".$celda9."</td>";
                                            $htmlTDS.="<td>".$celda10."</td>";
                                            $htmlTDS.="</tr>";
                                
                                }
                                 echo $htmlTDS."</table>";
                           
                                echo "<script> var nDestb = $NumRegs; var nDestCb = $NumRegs; </script>";
                                ?>
                                <input type="hidden" name="ConBienes" id="ConBienes" value="<?php echo $NumRegs;?>"/>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        <?php       
                }
            } 
        ?>
        <div id="tabs-5">
            <p>Ingrese la descripcion del bien materia de la operacion.</p>
            <textarea rows="5" cols="114" name="0form1_descripcion" id="descripcion_bien"><?php echo $descripcion; ?></textarea>
        </div>
        </div>
        <div class="ui-widget-content ui-widget-header ui-corner-all" style="margin-top:10px; padding:5px 0; text-align:right;">
            <a target="_blank" style="margin-right:10px;" href="../../editor/index.php?idkardex=<?php echo $idkardex; ?>" id="<?php echo $row['idkardex'] ?>">Documento</a>            
            <a target="_blank" style="margin-right:10px;" href="../../editor/testimonio.php?idkardex=<?php echo $idkardex; ?>" id="<?php echo $row['idkardex'] ?>">Testimonio</a>
            <a target="_blank" style="margin-right:10px;" href="../../editor/parte.php?idkardex=<?php echo $idkardex; ?>" id="<?php echo $row['idkardex'] ?>">Parte</a>
            <label for="edicion_impri" style="margin-left:10px">Vista Impresion: </label>
            <select name="edicion_impri" id="edicion_impri">
                <option value="print">Documento</option>
                <option value="print_test">Testimonio</option>
                <option value="print_parte">Parte</option>
            </select>
            <a href="javascript:" id="print_digitales"><img src="../../imagenes/iconos/imprimir.png" /></a>
        </div>
    </td>
    </tr>
  
</table>
    <input type="hidden" name="0form1_anio" id="0form1_anio" value="<?php echo $Anio;?>" />
</form>
</div>
<div id="listaUbigeo">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
            <td>
                <label for="IdDepartamento">Departamento</label>
            </td>
            <td>
            <div id="DivDepartamento">
                <select name="IdDepartamento2" id="IdDepartamento2"></select>
            </div>			
            </td>
    </tr>
    <tr>
            <td>
                <label for="IdProvincia">Provincia</label>
            </td>
            <td>
            <div id="DivProvincia">
                <select name="IdProvincia2" id="IdProvincia2"></select>
            </div>			
            </td>
    </tr>
        <tr>
             <td>
                <label for="IdDistrito">Distrito</label>
            </td>
            <td>
            <div id="DivDistrito">
                <select name="IdDistrito2" id="IdDistrito2"></select>
            </div>			
            </td>
      </tr>
      
    </table> 

<div id="dnewCliente"></div>
