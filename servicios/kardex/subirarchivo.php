<?php
session_start();
$ruta=isset($ruta)?$ruta:'';
if(strlen($_SESSION['notaria'])>1){ $digito=1; }else{ $digito=0; }
if(strpos($_GET['Nombre'],".")=='8'){
    $NombreG = substr($_GET['Nombre'], 0, strlen($_GET['Nombre'])-4);
    $ext = substr($_FILES['Adjunto']['name'], strpos($_FILES['Adjunto']['name'],"."), strlen($_FILES['Adjunto']['name']));
    $ruta = "archivos/".$NombreG."_".$_SESSION['notaria']."_".$_SESSION['Anio'].$ext;
}else{
    $value = substr($_GET['Nombre'], strpos($_GET['Nombre'],"_"), (7+$digito));
    if(strlen($value)==(7+$digito) && strpos($_GET['Nombre'],"_")>0){
        $NombreG = $_GET['Nombre'];
        $ruta = "archivos/".$NombreG;
    }else{
        $NombreG = substr($_GET['Nombre'], 0, 7);
        if(isset($_FILES̈́['Adjunto'])){
            $ext = substr($_FILES['Adjunto']['name'], strpos($_FILES['Adjunto']['name'],"."), strlen($_FILES['Adjunto']['name']));
            $ruta = "archivos/".$NombreG."_".$_SESSION['notaria']."_".$_SESSION['Anio'].$ext;
        }        
    }    
}
$Adjunto = isset($_FILES['Adjunto']['name'])?$_FILES['Adjunto']['name']:'';
if($Adjunto != ''){
    if(@move_uploaded_file($_FILES['Adjunto']['tmp_name'], $ruta)){
?>
<script>
    window.parent.CambiarArchivo('<?php echo substr($ruta, 9, strlen($ruta));?>');
</script>
<?php			
    }
}
?>
<style>
    body { margin-top:0px; margin-left:0px}
</style>
<script type="text/javascript" src="../../js/Funciones.js"></script>
<script type="text/javascript" src="../../js/jquery.js"></script>
<script>
function comprueba_extension() { 
    formulario = document.getElementById('FormA');
    archivo = document.getElementById('Adjunto').value;		
    extensiones_permitidas = new Array(".rtf", ".doc"); 
    mierror = ""; 
    if (!archivo) { 
    //Si no tengo archivo, es que no se ha seleccionado un archivo en el formulario 
    mierror = "Archivo sin seleccionar"; 
    }else{ 
    //recupero la extensión de este nombre de archivo 
    extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
    //alert (extension); 
    //compruebo si la extensión está entre las permitidas 
    permitida = false; 
    for (var i = 0; i < extensiones_permitidas.length; i++){ 
        if (extensiones_permitidas[i] == extension){
            permitida = true; 
            break;
        }
    } 
        if (!permitida){ 
            mierror = "Comprueba la extensi<?php echo utf8_decode("ó");?>n de los archivos a subir. \nS<?php echo utf8_decode("ó");?>lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join(); 
        }else{ 
            alert ("Todo correcto. El Archivo ser<?php echo utf8_decode("á");?> cargado."); 
            formulario.submit();
            return 1; 
        }
    } 
    alert (mierror); 
    return 0; 
}	
</script>
<form action="subirarchivo.php?Nombre=<?php echo $NombreG;?>" method="post" name="FormA" id="FormA" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="CargarArchivo">
  <tr>
    <td>
      <input type="file" name="Adjunto" id="Adjunto">    
    </td>
    <td>
        <input type="button" name="Submit" value="Subir" onclick="comprueba_extension();">    
    </td>
    <td>
        <label onclick="VerImagen('<?php echo $ruta;?>')" style="cursor:pointer; display:none;font-size: 11px;" id="VerImagennn"><img src="../../imagenes/iconos/word2.png" width="20" />Abrir Archivo</label>
    </td>
  </tr>
</table>
</form>
<script>

if (existe('<?php echo $ruta?>')){
    $('#VerImagennn').css('display', '');
}	
function VerImagen(Img){
    var ventana = window.open(Img,'Ver Kardex', 'width=400, height=350, resizable=yes, scrollbars=yes');
    ventana.focus();
}
</script>