<?php
class maxUpload{
    var $uploadLocation;    
    function maxUpload(){
        $this->uploadLocation = '../imagenes/';
    }
    function setUploadLocation($dir){
        $this->uploadLocation = $dir;
    }    
    function showUploadForm($msg='', $error=''){
?>
       <div id="container">
            <div id="header"><div id="header_left"></div>
            <div id="header_main">Ingrese la imagen correspondiente</div>
         	<div id="header_right"></div></div>
            <div id="content">
<?php
if ($msg != ''){
        echo '<p class="msg">$msg</p>';
} else if ($error != ''){
        echo '<p class="emsg">$error</p>';		
}
?>
                <form action="" method="post" enctype="multipart/form-data" id="form1" name="form1" >
                     <center>
                       <label>
                         <input name="myfile" id="myfile" type="file" size="30" onchange="MostrarImagen(this)" />
                         <br />
                         <br />
</label>
     <label>
     <input type="submit" name="submitBtn" class="sbtn" value="Cargar" />
                         </label>
                     </center>
                 </form>
             </div>
          </div>
<?php
    }
    function uploadFile($IdKardex='', $IdDependencia=''){
        include("../../../config.php");		
        if (!isset($_POST['submitBtn'])){
            $this->showUploadForm();
        }else{
            $msg = '';
            $error = '';            
            //Check destination directory
            if (!file_exists($this->uploadLocation)){
                $error = "El Directorio de Destino no Existe";
            }else if (!is_writeable($this->uploadLocation)){
                $error = "El Directorio de Destino no Tiene Permiso de Escritura";
            }else{
                $target_path = $this->uploadLocation.basename($_FILES['myfile']['name']);
                if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
                    $msg = basename($_FILES['myfile']['name'])." El Archivo se ha Cargado Correctamente";
                    $Nombre = $_FILES['myfile']['name'];					
                    $Conn->NuevaTransaccion();
                    $Sql = "UPDATE kardex_derivacion SET imagen='".$Nombre."' WHERE idkardex=".$IdKardex." AND iddependencia=".$IdDependencia;
                    $Consulta = $Conn->Query($Sql);
                    if (!$Consulta){
                        $Conn->TerminarTransaccion("ROLLBACK");
                    }else{		
                        $Conn->TerminarTransaccion("COMMIT");
                    }
                }else{
                    $error = "Hubo un Error al Tratar de Cargar el Archivo";
                }
            }
            $this->showUploadForm($msg, $error);
        }
    }
}
?>