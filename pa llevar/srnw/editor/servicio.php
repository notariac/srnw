<?php 
    include('../config.php');
    include('func.php');
    if(isset($_GET['idservicio']))
    {
        $Id = $_GET['idservicio'];
        $sql = "SELECT digital,descripcion from servicio where idservicio= ".$Id;
        $q = $Conn->Query($sql);
        $r = $Conn->FetchArray($q);    
        
        $plantilla = stripSlash(html_entity_decode($r[0]));       
        $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
	    $plantilla = utf8_decode($plantilla);
    }
    else
    {
        if(isset($_POST['idservicio']))
        {
            $Id = $_POST['idservicio'];
            $sql = "SELECT digital,descripcion from servicio where idservicio= ".$Id;
            $q = $Conn->Query($sql);
            $r = $Conn->FetchArray($q);
                      
            $plantilla = stripSlash(html_entity_decode($r[0]));       
            $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
	    $plantilla = utf8_decode($plantilla);
        }
    }
    if($plantilla=="")
            {
		if($Id==97)
		{
		   $addi = '<div id="viaje_externo"><img src="../imagenes/viaje_externo.png" /></div>';
		}
		if($Id==98)
                {
                   $addi = '<div id="viaje_interno"><img src="../imagenes/viaje_interno.png" /></div>';
                }
                $plantilla = '<div id="contenedor" style="background:#dadada;">
                        <div id="box-contenedor" style="width:793px; margin:0 auto; padding:20px 0 50px 0; ">                        
                        <div class="page" style="margin-bottom: 10px;
                                                 box-shadow: 10px 10px 8px #888888;
                                                 width:548px; 
                                                 min-height: 855px;
                                                 padding:6px 45px 6px 196px; 
                                                 background:#FFFFFF;
                                                 font-size: 14pt;
                                                 font-family:\"Times New Roman\",arial,Times,serif;">
                            <div class="write-page" >
                                 <div>&nbsp;</div>
                            </div>
                        </div>                          
                        </div>     
                        </div>';
                 $plantilla = stripSlash($plantilla);
                 
            }
    
?>
<html>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<link href="../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../js/tinymce/langs/es.js"></script>
<script type="text/javascript" src="scripts.js"></script>
<link href="stylos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){       
    $("#grabar").click(function(){
        var idservicio = $("#idservicio").val(),
            cont = $("#tinymce",self.content_ifr.document).html(),
            params = { 
                        'idservicio':idservicio,
                        'cont':cont
                     },
            str = jQuery.param(params);
        $("#msg").css("display","inline");
        $.post('save_servicio.php',str,function(data){            
            $("#msg").css("display","none");
            if(data!='1')
            {
                alert("HA OCURRIDO UN ERROR: "+data);
            }
        });
    })
});
tinymce.init({
    selector: "textarea",     
    theme: "modern",
    height : "460",
    language : 'es',         
    content_css : "estilos.css",    
    plugins: [
        "advlist autolink lists link image charmap hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking table contextmenu directionality",
        "emoticons template paste moxiemanager print"
    ],
    menubar: "file tools table format view insert edit",    
    toolbar1: "save | undo redo | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | forecolor backcolor ",    
    toolbar2: "",    
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ],
});
</script>

<body style="font-size:65%">
<form method="post" action="index.php" name="frm" id="frm">
    <div style="width:100%; padding:10px 0; background:green">
        <div style="padding:0 6px;">
        <span style="width:300px; color:#FFFFFF; font-size:12px; margin: 0 10px 0 0">PLANTILLA: <b><?php echo $r['descripcion']; ?></b></span>
        <input type="button" name="grabar" id="grabar" value="Guardar" />
        <span id="msg" style="font-size:11px; color:#fff; display:none">Guardando cambios...</span>
        <input type="button" name="cerrar" id="cerrar" value="X" onclick="window.close();" style="float:right" title="Cerrar" />
        <input type="button" name="config-page" id="config-page" value="Configurar Pagina"  style="float:right" title="Configurar Pagina" />

        <input type="hidden" name="idservicio" id="idservicio" value="<?php echo $Id ?>" />
        </div>
    </div> 
    <textarea name="content" style="width:100%; background:#dadada;">       
       <?php echo $plantilla; ?>
    </textarea>
</form>
</div>
<div id="margin">
    <label for="margen-left" style="width:80px; display:inline-block; text-align:right; font-size:12px">Izquierdo: </label><input type="text" name="margen-left" id="margen-left" class="ui-widget-content ui-corner-all" value="" size="3" /> cm.
    <label for="margen-top" style="width:60px; display:inline-block; text-align:right;font-size:12px">Arriba: </label><input type="text" name="margen-top" id="margen-top" class="ui-widget-content ui-corner-all" value="" size="3" readonly="" /> cm.
    <br/>
    <label for="margen-right" style="width:80px; display:inline-block; text-align:right;font-size:12px">Derecho: </label><input type="text" name="margen-right" id="margen-right" class="ui-widget-content ui-corner-all" value="" size="3" /> cm.
    <label for="margen-buttom" style="width:60px; display:inline-block; text-align:right;font-size:12px">Abajo: </label><input type="text" name="margen-buttom" id="margen-buttom" class="ui-widget-content ui-corner-all" value="" size="3" readonly="" /> cm.
</div>
</body>
</html>
