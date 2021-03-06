<?php 
    include('../config.php');
    include('func.php');
    if(isset($_GET['idarchivo']))
    {
        $Id = $_GET['idarchivo'];    
    }
    $sql = "SELECT a.nombre,a.contenido
            from editor.archivos as a              
            where a.idarchivo = ".$Id;
    
    $q = $Conn->Query($sql);
    $r = $Conn->FetchArray($q);
    $flag = false;
    if($r['contenido']!="")
    {       
        $plantilla = stripSlash(html_entity_decode($r['contenido']));
        $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
	    $plantilla = utf8_decode($plantilla);
    }
    else
    {
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
<script type="text/javascript" src="scripts.js"></script>
<link href="stylos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var fp = 'P';
$(document).ready(function(){  
    $("#print").click(function(){
        myPrint();
    });
    $("#grabar").click(function(){
        saveKardex();
    });
    $("#templates").dialog({
        title:'Escrituras Realizadas',
        modal:true,
        autoOpen: false,
        width: 330,
        buttons: {'Cerrar': function(){$(this).dialog('close')}}
    });    
    $("#modelo").click(function(){
        $("#templates").dialog('open');
    });
    $("#format_page").change(function(){
        var myfp = $(this).val();
        formatPage(myfp);
    })
 
});
function saveKardex()
{
    var idarchivo = $("#idarchivo").val(),
        cont = $("#tinymce",self.content_ifr.document).html(),
        params = { 
                    'idarchivo':idarchivo,
                    'cont':cont
                 },
        str = jQuery.param(params);
    $("#msg").css("display","inline");
    $.post('save_editor.php',str,function(data)
    {            
        $("#msg").css("display","none");
        if(data!='1')
        {
            alert("HA OCURRIDO UN ERROR: "+data);
        }
    });
}
function myPrint()
{
    var ida = $("#idarchivo").val();
    window.open('../eweb/print.php?idarchivo='+ida,'width=600,height=300');
}
tinymce.init({
    selector: "textarea",     
    theme: "modern",
    height : "460",
    language : 'es',         
    content_css : "estilos.css",    
    plugins: [
        "advlist autolink lists link image charmap hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save autosave table contextmenu directionality",
        "emoticons template paste moxiemanager print"
    ],
    menubar: "file tools table format view insert edit",    
    toolbar1: "save | undo redo | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | forecolor backcolor | print |",    
    toolbar2: "",    
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ],
});
</script>
<body style="font-size:65%">
<form method="post" action="index.php" name="frm" id="frm">
    <div style="width:100%; padding:5px 0; background:green">
        <div style="padding:0 6px;">
        <span style="width:300px; color:#FFFFFF; font-size:12px; margin: 0px 10px 0 0; ">DOCUMENTO: <b><?php echo $r['nombre']; ?></b></span>        
        <span id="msg" style="font-size:11px; color:#fff; display:none">Guardando cambios...</span>        
        <a class="btn close" href="javascript:window.close();" style="float:right;">CERRAR</a>
        <a class="btn config" href="javascript:" id="config-page" style="float:right;">CONFIGURAR</a>          
        <input type="hidden" name="idarchivo" id="idarchivo" value="<?php echo $Id; ?>" />
        </div>
        <div style="clear:both"></div>
    </div>
    <div id="info"></div>
    <textarea name="content" style="width:100%; background:#dadada;">
        <?php   
            echo $plantilla;
        ?>
    </textarea>
</form>
</div>
<div id="margin">
    <label for="margen-left" style="width:80px; display:inline-block; text-align:right; font-size:12px">Izquierdo: </label><input type="text" name="margen-left" id="margen-left" class="ui-widget-content ui-corner-all" value="" size="3" /> cm.
    <label for="margen-top" style="width:60px; display:inline-block; text-align:right;font-size:12px">Arriba: </label><input type="text" name="margen-top" id="margen-top" class="ui-widget-content ui-corner-all" value="" size="3" readonly="" /> cm.
    <br/>
    <label for="margen-right" style="width:80px; display:inline-block; text-align:right;font-size:12px">Derecho: </label><input type="text" name="margen-right" id="margen-right" class="ui-widget-content ui-corner-all" value="" size="3" /> cm.
    <label for="margen-buttom" style="width:60px; display:inline-block; text-align:right;font-size:12px">Abajo: </label><input type="text" name="margen-buttom" id="margen-buttom" class="ui-widget-content ui-corner-all" value="" size="3" readonly="" /> cm.
    <br/>
    <select name="format_page" id="format_page">
        <option value="P">Vertical</option>
        <option value="L">Horizontal</option>
    </select>
</div>
<div id="templates">
    
</div>
</body>
</html>