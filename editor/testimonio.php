<?php 
    include('../config.php');
    include('func.php');
    if(isset($_GET['idkardex']))
    {
        $Id = $_GET['idkardex'];    
    }    
    $sql = "SELECT  k.*,
                    s.descripcion as servicio,
                    s.digital as plantilla
            from kardex as k inner join servicio as s 
             on k.idservicio = s.idservicio
             where k.idkardex = ".$Id;
    $q = $Conn->Query($sql);
    $r = $Conn->FetchArray($q);
    if($r['testimonio']=="")
    {
        if($r['digital']!="")
        {
            $r['digital'] = str_replace('“', '"', $r['digital']);
            $r['digital'] = str_replace('”', '"', $r['digital']);
            $r['digital'] = str_replace('–', '-', $r['digital']);

            $r['digital'] = str_replace('?', '"', $r['digital']);

            $plantilla = stripSlash(html_entity_decode($r['digital']));       
            $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
	        $plantilla = utf8_decode($plantilla);
        }
        else 
        {
            echo "<script>alert('Para registrar la testimonio, primero se tiene que tener elaborado el documento');window.close();</script>";
        }
    }
    else 
    {

        $plantilla = stripSlash(html_entity_decode($r['testimonio']));       
        $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
	    $plantilla = utf8_decode($plantilla);
    }    
    if($plantilla=="")
    {
            $plantilla = '';
        
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
$(document).ready(function(){  
    $("#print").click(function(){
        myPrint();        
    });
    $("#grabar").click(function(){
        saveKardex();
    });
});
function saveKardex()
{
    var idkardex = $("#idkardex").val(),
            cont = $("#tinymce",self.content_ifr.document).html(),
            params = { 
                        'idkardex':idkardex,
                        'cont':cont
                     },
            str = jQuery.param(params);
        $("#msg").css("display","inline");
        $.post('save_test.php',str,function(data){            
            $("#msg").css("display","none");
            if(data!='1')
            {
                alert("HA OCURRIDO UN ERROR: "+data);
            }
        });
}
function myPrint()
{
   var kard = $("#idkardex").val();
   window.open('../editor/print_test.php?idkardex='+kard,'width=600,height=300');
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
        <span style="width:300px; color:#FFFFFF; font-size:12px; margin: 0 10px 0 0">EDIDCION DIGITAL (Testimonio): <b><?php echo $r['correlativo']; ?></b></span>
        <!-- <span style="font-size:15px; color:#fff;"><b>NOTA: Ahora el Boton de "Grabar" esta en la barra de opciones, Tambien puedes usar Ctrl+S para grabar.</b></span> -->
        <span id="msg" style="font-size:11px; color:#fff; display:none">Guardando cambios...</span>
        <a class="btn close" href="javascript:window.close();" style="float:right;">CERRAR</a>        
        <a class="btn config" href="javascript:" id="config-page" style="float:right;">CONFIGURAR</a>        
	    <!-- <input type="button" name="config-page" id="config-page" value="Configurar Pagina"  style="float:right" title="Configurar Pagina" /> -->
        <!-- <input type="button" name="print" id="print" value="Imprimir"  style="float:right" title="Imprimir" /> -->
        <input type="hidden" name="idkardex" id="idkardex" value="<?php echo $Id; ?>" />
        </div>
        <div style="clear:both"></div>
    </div>
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
</div>
</body>
</html>
