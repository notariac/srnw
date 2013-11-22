<?php 
    include('../config.php');
    function addSlash($str)
    {
       $str = str_replace("'",'\"',$str);
       return $str;
    }

    function stripSlash($str)
    {
       $str = str_replace('\"',"'",$str);
       return $str;
    }
    $Id = $_GET['idarchivo'];
    $sql = "SELECT contenido from editor.archivos
             where idarchivo = ".$Id;
    
    $q = $Conn->Query($sql);
    $r = $Conn->FetchArray($q);
    $plantilla = html_entity_decode(stripSlash($r[0]));
    $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
    //echo utf8_decode($plantilla);
?>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    window.print();
});
</script>
<style>
	.page { box-shadow: 0 0 0 #FFFFFF !important;}
	#idmargen_top {display:none;}
    p {     margin-top: 3px !important;
	      margin-bottom:3px !important;       
		}
    span,p {word-wrap: break-word;}
    ul,ol { margin-top:3px !important;
	  margin-bottom: 3px !important; }
</style>
<html>
<head>
	<style id="mceDefaultStyles" type="text/css">.mce-content-body div.mce-resizehandle {position: absolute;border: 1px solid black;background: #FFF;width: 5px;height: 5px;z-index: 10000}.mce-content-body .mce-resizehandle:hover {background: #000}.mce-content-body img[data-mce-selected], hr[data-mce-selected] {outline: 1px solid black;resize: none}.mce-content-body .mce-clonedresizable {position: absolute;outline: 1px dashed black;opacity: .5;filter: alpha(opacity=50);z-index: 10000}
	</style>
	
	<link type="text/css" rel="stylesheet" href="http://localhost/srnw/js/tinymce/skins/lightgray/content.min.css">
	<link type="text/css" rel="stylesheet" href="http://localhost/srnw/editor/estilos.css">
</head>
<body id="tinymce" class="mce-content-body " onload="window.parent.tinymce.get('content').fire('load');" contenteditable="true" spellcheck="false" dir="ltr" style="">
<?php echo utf8_decode($plantilla); ?>
</body>
</html>