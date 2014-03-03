<?php
    $NombreG = $_GET['Nombre'];
    $ruta = "partes/".$NombreG."Parte.doc";
    $Adjunto = $_FILES['Adjunto']['name'];
    if($Adjunto != ''){
        if(@move_uploaded_file($_FILES['Adjunto']['tmp_name'], $ruta)){}
    }
?>
<style>
    body { margin-top:0px; margin-left:0px}
</style>
<script>
function comprueba_extension(){ 
    formulario = document.getElementById('FormA');
    archivo = document.getElementById('Adjunto').value;		
    extensiones_permitidas = new Array(".doc"); 
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
        for (var i = 0; i < extensiones_permitidas.length; i++) { 
            if (extensiones_permitidas[i] == extension){
                permitida = true; 
                break;
            }
        } 
        if (!permitida){ 
                mierror = "Comprueba la extensi&oacute;n de los archivos a subir. \nS&oacute;lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join(); 
        }else{ 
            //submito! 
            alert ("Todo correcto. El Archivo ser&acute; cargado."); 
            formulario.submit();
            return 1; 
        }
    } 
    //si estoy aqui es que no se ha podido submitir 
    alert (mierror); 
    return 0; 
} 
</script>
<form action="subirarchivo.php?Nombre=<?php echo $NombreG;?>" method="post" name="FormA" id="FormA" enctype="multipart/form-data">
<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <input type="file" name="Adjunto" id="Adjunto">
    </td>
    <td>
        <input type="button" name="Submit" value="Subir" onclick="comprueba_extension();">
    </td>
  </tr>
</table>
</form>