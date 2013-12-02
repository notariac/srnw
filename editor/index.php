<?php 
    include('../config.php');
    include('func.php');
    include('../libs/num2letra.php');

    $dia = str_pad(date('d'),2,'0',0);
    $mes = str_pad(date('m'),2,'0',0);
    $anio = date('Y');

    if(isset($_GET['idkardex']))    
      $Id = $_GET['idkardex'];    

    $plantilla_template="";

    if(isset($_GET['template'])&&$_GET['template']!="")
    {
        $sql = "SELECT digital as plantilla from kardex              
                 where correlativo = '".$_GET['template']."'";
        
        $q = $Conn->Query($sql);
        $r = $Conn->FetchArray($q);

        $plantilla_template = stripSlash(html_entity_decode($r[0])); 
        $plantilla_template = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla_template);
        $plantilla_template = str_replace('"times new roman"',"'Times New Roman'",$plantilla_template);
        $plantilla_template = utf8_decode($plantilla_template);        
    }
    if($plantilla_template=="")
    {
      $sql = "SELECT k.*,s.descripcion as servicio,s.digital as plantilla 
                from kardex as k inner join servicio as s 
               on k.idservicio = s.idservicio
               where k.idkardex = ".$Id;
      $q = $Conn->Query($sql);
      $r = $Conn->FetchArray($q);
      $flag = false;
      $finalizado = $r['finalizado'];
      $msg = "";
      if($finalizado==1) $msg = "Esta escritura ya no es editable ya que fue marcada como finalizada";
      if($r['digital']=="")
      {
          $plantilla = stripSlash(html_entity_decode($r['plantilla']));       
          $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
  	      $plantilla = utf8_decode($plantilla);
          $flag = true;
      }
      else
      {
          $plantilla = stripSlash(html_entity_decode($r['digital']));	
          $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
          $plantilla = str_replace('"times new roman"',"'Times New Roman'",$plantilla);
  	      $plantilla = utf8_decode($plantilla);        
      }
      if($plantilla=="")
      {
         //Plantilla Limpia
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

    $s = "SELECT  c.nombres||' '||coalesce(c.ape_paterno,'')||' '||coalesce(c.ap_materno,'') as nombres,
                  p.descripcion,
                  c.dni_ruc,
                  doc.descripcion as documento,
                  c.direccion,
                  c.fecha_nac,
                  case c.idubigeo when '000000' then 'Distrito' else distrito.descripcion end as distrito,
                  case c.idubigeo when '000000' then 'Provincia' else  provincia.descripcion end as provincia,
                  case c.idubigeo when '000000' then 'Departamento' else departamento.descripcion end as departamento
                FROM kardex_participantes  as kp 
                  inner join cliente as c on c.idcliente = kp.idparticipante 
                  inner join participacion as p on p.idparticipacion = kp.idparticipacion 
                  inner join documento as doc on doc.iddocumento = c.iddocumento
                  inner join ubigeo as distrito on distrito.idubigeo = c.idubigeo
                  inner join ubigeo as provincia on provincia.idubigeo = substr(c.idubigeo,1,4)||'00'
                  inner join ubigeo as departamento on departamento.idubigeo = substr(c.idubigeo,1,2)||'0000'
          where kp.idkardex = ".$Id;    
    
    $qp = $Conn->Query($s);    
    $par = array();
    $participacion = "";
    $cont = 1;    
    $lastp = "";
    while($p = $Conn->FetchArray($qp))
    {                
          $pp = strtolower(trim(str_replace(" ","", $p[1])));
          if($pp==$participacion)
          {
              $cont = $cont+1;            
              $participacion = $pp;
          }
          $pp = $pp.$cont;        
          $par[] = array(
                          'nombres'=>fupper(strtolower($p[0])),   //Nombre 
                          'participacion'=>$pp,                   //Participacion
                          'd'.$pp=>$p[2],                         //Nro de Documento
			                    'td'.$pp=>$p[3],                        //Tipo de Documento
			                    'dir'.$pp=>$p[4],                       //Direccion
			                    'edad'.$pp=>calcular_edad($p[5]),       //Edad
                          'fecha_nac'.$pp=>$p[5],                 //Fecha de Nacimiento
                          'distrito'.$pp=>$p['distrito'],
                          'provincia'.$pp=>$p['provincia'],
                          'departamento'.$pp=>$p['departamento']
                         );
      }
    }
    //print_r($par);
    function seachp($name,$par)
    {
        $flag = false;
        foreach($par as $key => $value)
          {
             if($value['participacion']==$name)
              {
                 $flag = true;
              }
          }
      return $flag;
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
var hhh = $(window).height()-160;
$(document).ready(function(){  
    $("#print").click(function(){
        myPrint();
    });
    $( "#templat" ).autocomplete({
          minLength: 0,
          source: '../libs/autocompletar/kardex_template.php',
          focus: function( event, ui ) 
          {
              $("#templat").val(ui.item.correlativo);
              return false;
          },
          select: function( event, ui ) 
          {
              $("#templat").val(ui.item.correlativo);
              return false;
          }
      }).data( "autocomplete" )._renderItem = function( ul, item ) 
      {
          return $( "<li></li>" )
              .data( "item.autocomplete", item )
              .append( "<a>"+ item.correlativo + "</a>" )
              .appendTo( ul );
      };
    $("#reload").click(function()
    {
       var t = $("#templat").val();
       if(t!="")
       {
          if(confirm('Realmente deseas cargar la plantilla?'))
          {
              var idk = $("#idkardex").val();              
              window.location = "index.php?idkardex="+idk+"&template="+t;
          }
       }
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
        $.post('save_index.php',str,function(data){            
            $("#msg").css("display","none");
            if(data!='1')
            {
                alert("HA OCURRIDO UN ERROR: "+data+"; S.O.S Sistemas");
            }
        });
}
function myPrint()
{
    var kard = $("#idkardex").val();
    window.open('../editor/print.php?idkardex='+kard,'width=600,height=300');
}
if(hhh<0) hhh = 500;
tinymce.init({
    selector: "textarea",     
    theme: "modern",
    height : hhh,
    language : "es",         
    content_css : "estilos.css",    
    browser_spellcheck : true,
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
        <span style="width:300px; color:#FFFFFF; font-size:12px; margin: 0px 10px 0 0; ">ESCRITURA: <b><?php echo $r['correlativo']; ?></b>  <?php if($_GET['template']!=""){ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Usando el Modelo de ".$_GET['template']."]"; } ?></span>
        <span id="msg" style="font-size:11px; color:#fff; display:none">Guardando cambios...</span>        
        <a class="btn close" href="javascript:window.close();" style="float:right;">CERRAR</a>
        <a class="btn config" href="javascript:" id="config-page" style="float:right;">CONFIGURAR</a>  
        <span style="float:right; padding:0 20px">
          <label style="color:#FFF">CARGAR PLANTILLA: </label>
          <input type="text" name="templat" id="templat" value="<?php echo $_GET['template'] ?>" style="width:80px" class="ui-widget-content ui-corner-all text" maxlength="7" />
          <input type="button" name="reload" id="reload" value="Cargar" />
        </span>        
        <input type="hidden" name="idkardex" id="idkardex" value="<?php echo $Id; ?>" />
        </div>
        <div style="clear:both"></div>
    </div>

    <textarea name="content" style="width:100%; background:#dadada;">
        <?php     

        if($plantilla_template=="")      
        {
         if($flag)
         {
             $plantilla = str_replace("%kardex%", $r['correlativo'], $plantilla);
             $plantilla = str_replace("%escritura%", $r['escritura'], $plantilla);
             $plantilla = str_replace("%minuta%", $r['minuta'], $plantilla);
             $plantilla = str_replace("%ruta%", $r['ruta'], $plantilla);
             $plantilla = str_replace("%via%", fupper($r['via']), $plantilla);
             $plantilla = str_replace("%motivo%", fupper($r['motivo']), $plantilla);
             $plantilla = str_replace("%dia%", $dia, $plantilla);
             $plantilla = str_replace("%name_mes%", $meses[(int)$mes-1], $plantilla);
             $plantilla = str_replace("%mes%", $mes, $plantilla);
             $plantilla = str_replace("%anio%", $anio, $plantilla);

             //Fecha de Salida (Viaje) 
             $f = explode("-",$r['fecha_salida']);
             $fechas = $f[2]." de ".$meses[(int)$f[1]-1]." del ".$f[0];
	           //Fecha de Regreso (Viaje)
             $f = explode("-",$r['fecha_retorno']);
             $fechar = $f[2]." de ".$meses[(int)$f[1]-1]." del ".$f[0];

	           $plantilla = str_replace("%fecha_salida%",$fechas,$plantilla);
             $plantilla = str_replace("%fecha_retorno%",$fechar,$plantilla);

             foreach ($par as $key => $value) 
             {   
	        $participacion = $value['participacion'];
                if($value['participacion']=="madre1"||$value['participacion']=="tutor1")
                {
                   $bval = seachp("padre1",$par);
                   if(!$bval)                   
                      $participacion = "padre1";                      			                      		   
		   //die($participacion);
                }
                $plantilla = str_replace("%".$participacion."%", fupper(utf8_decode($value['nombres'])), $plantilla);
                $plantilla = str_replace("%d".$participacion."%", $value['d'.$value['participacion']], $plantilla);		  
 		$plantilla = str_replace("%td".$participacion."%", fupper($value['td'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%dir".$participacion."%", fupper(utf8_decode($value['dir'.$value['participacion']])), $plantilla);                   
                $plantilla = str_replace("%edad_text".$participacion."%", fupper(num2letra($value['edad'.$value['participacion']])), $plantilla);
                $plantilla = str_replace("%edad".$participacion."%", $value['edad'.$value['participacion']], $plantilla);
                $plantilla = str_replace("%distrito".$participacion."%", fupper($value['distrito'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%provincia".$participacion."%", fupper($value['provincia'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%departamento".$participacion."%", fupper($value['departamento'.$value['participacion']]), $plantilla);
              }
         }
         echo $plantilla;
       }
       else 
       {
          echo $plantilla_template;
       }
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
<div id="templates">
    
</div>
</body>
</html>