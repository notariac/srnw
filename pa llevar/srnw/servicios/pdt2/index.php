<?php
if(!session_id()){ session_start(); }
    include('../../config.php');
    include("../../libs/masterpage.php");
    include("../../libs/claseindex.php");	
    include_once '../../libs/funciones.php';
    $TituloVentana = "Generacion de Archivos Planos para el PDT NOTARIO";
    CuerpoSuperior($TituloVentana);
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/evb.css" rel="stylesheet" type="text/css" />
<link href="../../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script language="JavaScript" src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

<script type="text/javascript" src="pdt.js"></script>
<style type="text/css">
    *{font-size:12px;}
    a:visited{text-decoration: none;color:#009900;}
    a:link{text-decoration: none;color:#009900;}
    a:hover{text-decoration: none;color:#336600;}
</style>
<div align="center" class="divCentrado">
    <h2 class="ui-widget-header ui-corner-top">Generaci&oacute;n de Archivos Planos para el PDT NOTARIO</h2>  
    <div id="cuerpoForm">
        <div>
            <fieldset class="ui-widget-content ui-corner-all">
                <legend class="ui-widget-header ui-corner-all ">Configuracion del Generador</legend>
                <label class="label">A&ntilde;o</label>
                <select name="anio" id="anio" class="ui-widget-content">
                    <?php 
                    echo opt_combo("select anio_id,anio_id from anio", 0, $Conn);
                    ?>
                </select>
                <span class="label">Seleccione el Archivo a Generar:</span>
                <select class="ui-widget-content" name="tipo_generar" id="tipo_generar">
                    <option value="A">Actos Juridicos</option>
                    <option value="B">Bienes</option>
                    <option value="O">Otorgantes</option>
                </select>
            <br/>
            <button id="generar">Generar Fichero Plano</button>
            <div id="boton_descarga" style="display: none;clear: both;">
                <a target="_BLANK" href="#" id="hola">
                    <img src="img/download.png" align="center"/><br/>
                    <span id="texto_tipo" style="text-align: center;display: block;width: 100px;" class="ui-button-text">&nbsp;</span>
                </a>
                
            </div>
            </fieldset>
            
            <div id="mostra"></div>
        </div>
    </div>
</div>
<?php
    CuerpoInferior();
?>
