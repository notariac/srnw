<?php
    if(!session_id()){ session_start(); }
    include('../config.php');
    include("../libs/masterpage.php");
    include("../libs/claseindex.php"); 
    include_once '../libs/funciones.php';
    $TituloVentana = "EDITOR WEB DE DOCUMENTOS";
    CuerpoSuperior($TituloVentana);    
    $urlBack = "";
    $folder = $_GET['folder'];
    if(!isset($_GET['folder'])||$_GET['folder']=="")
    {
       $folder = 1;
    }
    $sql = "SELECT idcarpeta,nombre,idpadre FROM editor.carpeta where idpadre = ".$folder;    
    $Consulta = $Conn->Query($sql);
    $father="";
    $n = $Conn->NroRegistros($Consulta);
    if($n>0)
    {
        while($row = $Conn->FetchArray($Consulta))
        {
          $name = $row[1];
          $idcarpeta = $row[0];
          $idpadre = $row[2];   
          $father = $idpadre;       
        } 
    }
    else 
    {
       $sql = "SELECT idcarpeta,nombre,idpadre FROM editor.carpeta where idcarpeta = ".$folder;    
       $Consulta = $Conn->Query($sql);
       while($row = $Conn->FetchArray($Consulta))
        {
          $name = $row[1];
          $idcarpeta = $row[0];
          $idpadre = $row[2];
          $father = $idcarpeta;
          $URL .= '<a href="#" style="padding:0 3px">'.$name.'&</a>';
        } 
        
    }
    if(getNChilds($idpadre,$Conn))    
       $URL .= getURLBack($idpadre,$Conn);    
    $urlBack .= $URL;    
    $urlBack = ".".URLSort($urlBack);
    function getURLBack($id,$Conn)
    {       
       $sql = "SELECT idcarpeta,nombre,idpadre FROM editor.carpeta where idcarpeta = ".$id;
       $Consulta = $Conn->Query($sql);
       while($row = $Conn->FetchArray($Consulta))
       {
          $name = $row[1];
          $idcarpeta = $row[0];
          $idpadre = $row[2];
          $URL .= '<a href="index.php?folder='.$idcarpeta.'" style="padding:0 3px">'.$name.'&</a>';          
       }
       $urlBack .= $URL;
       if(getNChilds($idpadre,$Conn))
       {
          $urlBack .= getURLBack($idpadre,$Conn);
       }               
       return $urlBack;       
    }
   function getNChilds($id,$Conn)
   {
      if($id>0)
      {
        $sql = "SELECT count(*) from editor.carpeta where idpadre = ".$id;      
        $q = $Conn->Query($sql);
        $r = $Conn->FetchArray($q);
        if($r[0][0]>0)      
            return true;
        else 
            return false;
      } 
      else
      {
        return false;
      }     
   }
   function URLSort($url)
   {
     $cad = explode("&", $url);
     $nurl = "";
     foreach($cad as $i => $c)
     {
        if($i==0)
          $nurl .= $c.'<img src="../imagenes/editor/arrow.png" title="directorio"  style="margin:-8px">';
        else 
          $nurl = $c.'<img src="../imagenes/editor/arrow.png" title="directorio"  style="margin:-8px">'.$nurl;
     }
     return $nurl;
   }

?>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<link href="../css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<style>
  .mybuttons { text-decoration: none;color: green !important; font-weight: bold; font-size: 14px; margin-left: 10px; padding: 3px 5px 0px;}  
</style>
<script>  
  $(document).ready(function(){
     $("#folder").dialog({
        title:'Nueva Carpeta',
        modal:true,
        autoOpen: false,
        buttons :{'Crear':function()
                  {
                    saveFolder();
                  },
                'Cerrar': function(){$(this).dialog('close');}}
      });
      $("#archivo").dialog({
        title:'Nuevo Documento',
        modal:true,
        autoOpen: false,
        buttons :{'Crear':function()
                  {
                    saveDocumento();
                  },
                'Cerrar': function(){$(this).dialog('close');}}
      });      
      $("#newFolder").click(function()
      {
        $("#folder").dialog('open');
      });
      $("#newFile").click(function()
      {
        $("#archivo").dialog('open');
      });
   })
  function saveFolder()
  {
    var name = $("#name_folder").val(),
        idpadre = $("#idfolder").val();
    if(name!="")
    {
       $.post('process.php','oper=1&name='+name+'&idpadre='+idpadre,function(data){
            alert(data);
            window.location = 'index.php?folder='+idpadre;
       }) 
    }
  }

 function saveDocumento()
  {
    var name = $("#name_archivo").val(),
        idfolder = $("#idfolder").val();
    if(name!="")
    {
       $.post('process.php','oper=2&name='+name+'&idfolder='+idfolder,function(data)
       {
            alert(data);
            window.location = 'index.php?folder='+idfolder;
       }) 
    }
  }
</script>
<div>
  <h2 class="ui-widget-header ui-corner-all" style="padding:5px 0; text-align:center; margin:0">EDITOR WEB DE DOCUMENTOS</h2>
  <fieldset class="ui-widget-content ui-corner-all">
    <legend>Editor-Web</legend>
    <form name="frm-ro" id="frm-ro">
      <a href="#" class="mybuttons" id="newFolder"><img src="../imagenes/editor/newfolder.png" title="Nueva Carpeta" width="28" height="28"></a>
      <a href="#" class="mybuttons" id="newFile"><img src="../imagenes/editor/newfile.png" title="Nueva Documento" width="28" height="28"></a>
      <input type="hidden" name="idfolder" id="idfolder" value="<?php echo $folder; ?>" />
    </form>
  </fieldset>
</div>
<div>
  <div class="contain" style="width:99.6%;background:#FAFAFA">
   <?php
      echo $urlBack;
   ?>
   <table class="ui-widget-content">
    <thead class="ui-widget-header">
        <tr class="ui-widget-header">
          <th scope="col">&nbsp;</th>
          <th scope="col">Titulo</th>
          <th scope="col">Ultima Modificacion</th>          
        </tr>
    </thead>
    <tbody>
      <?php 
        if($idpadre>0)
          $sql = " SELECT idcarpeta as codigo,nombre,null as fecham,1 as tipo,cast('' as text) as hora FROM editor.carpeta where idpadre = {$father}
                   UNION ALL
                   SELECT idarchivo as codigo,nombre,fecha_modificacion as fecham,2 as tipo,cast(hora_modificacion as text) as hora 
                   FROM editor.archivos WHERE idcarpeta = ".$folder;
        else 
          $sql = " SELECT idcarpeta as codigo,nombre,null as fecham,1 as tipo,cast('' as text) as hora FROM editor.carpeta where idpadre = null
                    UNION ALL
                   SELECT idarchivo as codigo,nombre,fecha_modificacion as fecham,2 as tipo, cast(hora_modificacion as text) as hora
                   FROM editor.archivos WHERE idcarpeta =".$folder;
        $Consulta = $Conn->Query($sql);
        while($row = $Conn->FetchArray($Consulta))
        {
         $tipo='<img src="../imagenes/editor/folder.png" title="Carpeta" width="20" height="20" style="margin:-4px;">';
         $href = "index.php?folder=".$row[0];
         $target = '';
         if($row['tipo']==2)
         {
           $tipo='<img src="../imagenes/editor/document.png" title="Documento" width="15" height="15" style="margin:-2px;">';
           $href = "editor.php?idarchivo=".$row[0];
           $target = 'target="_blank"';
         }
         ?>
         <tr>
          <td><?php echo "&nbsp;" ?></td>
          <td><a style="color:green !important; padding:0 5px;" href="javascript:"><?php echo $tipo; ?></a><a href="<?php echo $href; ?>" <?php echo $target; ?> ><?php echo $row['nombre'] ?></a></td>
          <td align="center"><?php if($row['fecham']!=""){ echo "El ".$row['fecham']." a las ".$row['hora']; } ?></td>
         </tr>
         <?php
        }
      ?>
    </tbody>
  </table>
</div>
<div id="folder">
  <br/>
  <label>Nombre de la Carpeta: </label>
  <input type="text" name="name_folder" id="name_folder" class="ui-widget-content ui-corner-all" style="width:265px"/>
</div>
<div id="archivo">
  <br/>
  <label>Nombre del Archivo: </label>
  <input type="text" name="name_archivo" id="name_archivo" class="ui-widget-content ui-corner-all" style="width:265px"/>
</div>
<?php
  CuerpoInferior();
?>
