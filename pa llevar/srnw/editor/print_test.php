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
    $Id = $_GET['idkardex'];
    $sql = "SELECT testimonio from kardex
             where idkardex = ".$Id;
    
    $q = $Conn->Query($sql);
    $r = $Conn->FetchArray($q);
    $plantilla = html_entity_decode(stripSlash($r[0]));
    $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
    $plantilla = str_replace('"times new roman"',"'Times New Roman'",$plantilla);
    //echo utf8_decode($plantilla);
?>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    window.print();
});
</script>
<html>
<head>
  <style id="mceDefaultStyles" type="text/css">.mce-content-body div.mce-resizehandle {position: absolute;border: 1px solid black;background: #FFF;width: 5px;height: 5px;z-index: 10000}.mce-content-body .mce-resizehandle:hover {background: #000}.mce-content-body img[data-mce-selected], hr[data-mce-selected] {outline: 1px solid black;resize: none}.mce-content-body .mce-clonedresizable {position: absolute;outline: 1px dashed black;opacity: .5;filter: alpha(opacity=50);z-index: 10000}
  </style>
  
  <link type="text/css" rel="stylesheet" href="http://localhost/srnw/js/tinymce/skins/lightgray/content.min.css">
  <link type="text/css" rel="stylesheet" href="http://localhost/srnw/editor/estilos.css">
	<style>
	.page { box-shadow: 0 0 0 #FFFFFF !important;}
	#idmargen_top {display:none !important;}
    p { margin-top: 3px !important;
	      margin-bottom:3px !important;       
		}
    span,p {word-wrap: break-word;}
    ul,ol { margin-top:3px !important;
	  margin-bottom: 3px !important; }
#viaje_externo { position:absolute; padding:230px 0 0 40px;}
#viaje_interno {position:absolute; padding:230px 0 0 43px;}
table tr td {border:1px solid #333 !important;
	  padding:0 !important;
 	  margin:0 !important;
          border-collapse:collapse !important;
}
#box-contenedor {padding:0 !important; }
#contenedor { background:#FFF !important;}
body {margin:0 !important;}
.page {padding-top:0 !important;}
</style>
</head>
<body id="tinymce" class="mce-content-body " onload="window.parent.tinymce.get('content').fire('load');" contenteditable="true" spellcheck="false" dir="ltr" style="">
<?php echo utf8_decode($plantilla); ?>
</body>
</html>
