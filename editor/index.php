<?php 
    session_start();
    include('../config.php');
    include('func.php');
    include("num2letraK.php");  

    $dia = str_pad(date('d'),2,'0',0);
    $mes = str_pad(date('m'),2,'0',0);
    $anio = date('Y');

    if(isset($_GET['idkardex']))    
      $Id = $_GET['idkardex'];    

    $plantilla_template="";

    if(isset($_GET['template'])&&$_GET['template']!="")
    {
        //En caso que se quiere usar la copia de otra documento ya elaborado
        $sql = "SELECT digital as plantilla from kardex              
                 where correlativo = '".$_GET['template']."' and anio = '".$_GET['anio']."'";
        
        $q = $Conn->Query($sql);
        $r = $Conn->FetchArray($q);

        $plantilla_template = stripSlash(html_entity_decode($r[0])); 
        $plantilla_template = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla_template);
        $plantilla_template = str_replace('"times new roman"',"'Times New Roman'",$plantilla_template);
        $plantilla_template = utf8_decode($plantilla_template);        
    }
    if($plantilla_template=="")
    {
      //Si la plantilla_template sigue vacia, se obtiene la plantilla definida en el servicio
      $sql = "SELECT k.*,
                     s.descripcion as servicio,
                     s.digital as plantilla,
                     coalesce(kaj.monto,0) as monto,
                     m.descripcion as moneda,
                     dn.descripcion as documento_notarial
                from servicio as s INNER JOIN kardex as k ON (s.idservicio = k.idservicio) 
                                  left outer JOIN kardex_aj as kaj on kaj.idkardex = k.idkardex 
                                  left outer join moneda as m on m.idmoneda = kaj.idmoneda                                            
                                  left outer join asigna_pdt as apdt on apdt.idservicio = s.idservicio
                                  left outer join pdt.documento_notarial as dn on dn.iddocumento_notarial = apdt.iddocumento_notarial
               where k.idkardex = ".$Id;
      $q = $Conn->Query($sql);
      $r = $Conn->FetchArray($q);

      $Escritura      = $r['escritura'];
      $EscrituraFecha = $Conn->DecFecha($r['escritura_fecha']);
      $fecha_escritura = $r['escritura_fecha'];
      $documento_notarial = $r['documento_notarial'];
      $servicio = $r['servicio'];
      $DiaL           = CantidadEnLetra((int)substr($r['escritura_fecha'], 8, 2)); //Dia en letras
      $MesL           = $meses[(int)substr($r['escritura_fecha'], 5, 2)-1];        //Mes en letras
      $AnioL          = CantidadEnLetra((int)substr($r['escritura_fecha'], 0, 4)); //Anio en letras

      $Dia = substr($r['escritura_fecha'], 8, 2);   //Dia en numero
      $Mes = substr($r['escritura_fecha'], 5, 2);   //Dia en numero
      $Anio = substr($r['escritura_fecha'], 0, 4);  //Anio en numero

      $IdServicio     = $r['idservicio'];
      $Anio = $r['anio'];
      $FojaI    = $r['fojainicio'];
      $SerieI   = $r['serieinicio'];
      $FojaF    = $r['fojafin'];
      $SerieF   = $r['seriefin'];      
      $Monto    = $r['monto'];
      $Descripcion = $r['descripcion'];

      //Variables exclusivas para autorizaciones de viaje
      $Motivo   = $r['motivo'];
      $via = $r['via'];
      $fecha_salida = $Conn->DecFecha($r['fecha_salida']);
      $fecha_retorno = $Conn->DecFecha($r['fecha_retorno']);
      $ruta = $r['ruta'];       
      //--

      $monto_total = $r['monto'];
      $moneda = $r['moneda']; 


      //Verificamos y obtenemos si exibieron medio de pago
      $query = "SELECT fp.descripcion,
                    ef.descripcion,
                    m.descripcion,
                    dfp.montopagado,
                    dfp.fechapago,
                    dfp.nromediopago
                  FROM detalle_forma_pago as dfp inner join forma_pago as fp on fp.idforma_pago = dfp.idforma_pago
                    inner join moneda as m on m.idmoneda = dfp.idmoneda
                    inner join pdt.entidadfinanciera as ef on ef.identidad_financiera = dfp.identidad_financiera
                  WHERE dfp.idkardex = ".$Id;
      $stmt       = $Conn->Query($query);
      $data_pay = array();
      while($reg = $Conn->FetchArray($stmt))
      {
         $data_pay[] = array($reg[0],$reg[1],$reg[2],$reg[3],$reg[4],$reg[5]);
      }
      //--

      $flag = false;
      $finalizado = $r['finalizado'];

      $msg = "";
      if($finalizado==1) $msg = "Esta escritura ya no es editable ya que fue marcada como finalizada";

      if($r['digital']=="")
      {
          //Si la escritura aun no tiene nada grabado se obtiene la plantilla del servicio
          $plantilla = stripSlash(html_entity_decode($r['plantilla']));       
          $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
  	      $plantilla = utf8_decode($plantilla);
          $flag = true;
      }
      else
      {
          //Si existe ya el documento grabado se procesa,
          $r['digital'] = str_replace('“', '"', $r['digital']);
          $r['digital'] = str_replace('”', '"', $r['digital']);
          $r['digital'] = str_replace('–', '-', $r['digital']);

          //$r['digital'] = str_replace('?', '"', $r['digital']);
          
          $plantilla = stripSlash(html_entity_decode($r['digital']));
          $plantilla = str_replace('"Times New Roman"',"'Times New Roman'",$plantilla);
          $plantilla = str_replace('"times new roman"',"'Times New Roman'",$plantilla);
  	      $plantilla = utf8_decode($plantilla);
      }

      //Si no existe el documento digital en el kardex y tampoco tiene plantilla en su servicio
      //Se opta por una plantilla por defecto.
      if($plantilla!="")
      {
          //Plantilla Limpia
    
          $plantilla = '<div id="contenedor">
                          <div id="box-contenedor">                        
                          <div class="page">
                            <div class="write-page" >
                              <div>&nbsp;</div>
                            </div>
                          </div>
                        </div>
                      </div>';
          $plantilla = stripSlash($plantilla);
      }

      //Recuperamos los participantes en la escritura
      $s = "SELECT 
                kardex_participantes.idkardex, 
                documento.descripcion as documento, 
                kardex_participantes.idparticipante, 
                cliente.dni_ruc, 
                cliente.nombres||' '||coalesce(cliente.ape_paterno,' ')||' '||coalesce(cliente.ap_materno,' ') as nombres, 
                kardex_participantes.idparticipacion, 
                participacion.descripcion as participacion,
                kardex_participantes.porcentage,
                coalesce(kardex_participantes.idrepresentado,0) as idrepresentado, 
                kardex_participantes.tipo,
                kardex_participantes.conyuge,
                kardex_participantes.porcentage,
                kardex_participantes.partida,
                kardex_participantes.idzona,
                zr.zona as zona,
                cliente.direccion,
                cliente.fecha_nac,
                case cliente.idubigeo when '000000' then 'Distrito' else distrito.descripcion end as distrito,
                case cliente.idubigeo when '000000' then 'Provincia' else  provincia.descripcion end as provincia,
                case cliente.idubigeo when '000000' then 'Departamento' else departamento.descripcion end as departamento,
                ec.descripcion as estado_civil,
                cliente.sexo,
                cliente.nacionalidad,
                cliente.pais,                
                case cliente.idprofesion when 999 then cliente.otra_profesion else pro.descripcion end as ocupacion,
                cliente.idcliente_tipo
                FROM cliente INNER JOIN kardex_participantes ON (cliente.idcliente = kardex_participantes.idparticipante) 
                INNER JOIN participacion ON (kardex_participantes.idparticipacion = participacion.idparticipacion) 
                INNER JOIN documento ON (cliente.iddocumento = documento.iddocumento) 
                inner join ubigeo as distrito on distrito.idubigeo = cliente.idubigeo
                inner join ubigeo as provincia on provincia.idubigeo = substr(cliente.idubigeo,1,4)||'00'
                inner join ubigeo as departamento on departamento.idubigeo = substr(cliente.idubigeo,1,2)||'0000'
                inner join estado_civil as ec on ec.idestado_civil = cliente.idestado_civil
                inner join ro.profesion as pro on pro.idprofesion = cliente.idprofesion
                left outer join ro.zona_registral as zr on zr.idzona = kardex_participantes.idzona 
                where kardex_participantes.idkardex = ".$Id." order by tipo";     
    
    $qp = $Conn->Query($s);    
    $par = array();
    $participacion = "";
    $cont = 1;    
    $lastp = "";
    $data = array();
    while($p = $Conn->FetchArray($qp))
    {
          $pp = strtolower(trim(str_replace(" ","", $p['participacion'])));
          if($pp==$participacion)
          {
              $cont = $cont+1;            
              $participacion = $pp;
          }
          $pp = $pp.$cont;        
          $par[] = array(
                          'nombres'=>$p['nombres'],   //Nombre 
                          'participacion'=>$pp,                   //Participacion
                          'd'.$pp=>$p['dni_ruc'],                         //Nro de Documento
                          'td'.$pp=>$p['documento'],                        //Tipo de Documento
                          'dir'.$pp=>$p['direccion'],                       //Direccion
                          'edad'.$pp=>calcular_edad($p['fecha_nac']),       //Edad
                          'fecha_nac'.$pp=>$p['fecha_nac'],                 //Fecha de Nacimiento
                          'distrito'.$pp=>$p['distrito'],
                          'provincia'.$pp=>$p['provincia'],
                          'departamento'.$pp=>$p['departamento'],
                          'estado_civil'.$pp=>$p['estado_civil']
                         );
          $data[] = array('idparticipante'=>$p['idparticipante'],
                          'participante'=>$p['nombres'],
                          'documento'=>$p['documento'],
                          'nrodocumento'=>$p['dni_ruc'],
                          'idparticipacion'=>$p['idparticipacion'],
                          'participacion'=>$p['participacion'],
                          'tipo'=> $p['tipo'],
                          'idrepresentado'=>$p['idrepresentado'],
                          'conyuge'=>values($p['conyuge']),
                          'porcentage'=>$p['porcentage'],
                          'partida'=>$p['partida'],
                          'idzona'=>$p['idzona'],
                          'zona'=>$p['zona'],
                          'distrito'=>$p['distrito'],
                          'provincia'=>$p['provincia'],
                          'departamento'=>$p['departamento'],
                          'estado_civil'=>$p['estado_civil'],
                          'dir'=>$p['direccion'],                       //Direccion
                          'edad'=>calcular_edad($p['fecha_nac']),       //Edad
                          'fecha_nac'=>$p['fecha_nac'],
                          'sexo'=>$p['sexo'],
                          'nacionalidad'=>$p['nacionalidad'],
                          'pais'=>$p['pais'],
                          'ocupacion'=>$p['ocupacion'],
                          'idcliente_tipo'=>$p['idcliente_tipo'],
                          'es_conyuge'=>0,
                          'fecha_nac'=>$p['fecha_nac']
          );
            if($p['conyuge']!="")
            {
              $s = "SELECT cliente.idcliente,
                           cliente.dni_ruc, 
                           cliente.nombres||' '||coalesce(cliente.ape_paterno,' ')||' '||coalesce(cliente.ap_materno,' ') as nombres, 
                           documento.descripcion as documento,
                           cliente.direccion,
                           cliente.fecha_nac,
                           case cliente.idubigeo when '000000' then 'Distrito' else distrito.descripcion end as distrito,
                           case cliente.idubigeo when '000000' then 'Provincia' else  provincia.descripcion end as provincia,
                           case cliente.idubigeo when '000000' then 'Departamento' else departamento.descripcion end as departamento,
                           'CASADA' as estado_civil,
                           cliente.sexo,
                           cliente.nacionalidad,
                           cliente.pais,
                           case cliente.idprofesion when 999 then cliente.otra_profesion else pro.descripcion end as ocupacion
                     from cliente 
                        INNER JOIN documento ON cliente.iddocumento = documento.iddocumento 
                        inner join ubigeo as distrito on distrito.idubigeo = cliente.idubigeo
                        inner join ubigeo as provincia on provincia.idubigeo = substr(cliente.idubigeo,1,4)||'00'
                        inner join ubigeo as departamento on departamento.idubigeo = substr(cliente.idubigeo,1,2)||'0000'
                        inner join estado_civil as ec on ec.idestado_civil = cliente.idestado_civil
                        inner join ro.profesion as pro on pro.idprofesion = cliente.idprofesion
                     where idcliente = ".$p['conyuge'];
                  $q = $Conn->Query($s);
                  while($rrr = $Conn->FetchArray($q))
                  {
                    $data[] = array('idparticipante'=>$rrr['idcliente'],
                        'participante'=>$rrr['nombres'],
                        'documento'=>$rrr['documento'],
                        'nrodocumento'=>$rrr['dni_ruc'],
                        'idparticipacion'=>$p['idparticipacion'],
                        'participacion'=>$p['participacion'],
                        'tipo'=> $p['tipo'],
                        'idrepresentado'=>'NULL',
                        'conyuge'=>'NULL',
                        'porcentage'=>$p['porcentage'],
                        'partida'=>'',
                        'idzona'=>'',
                        'zona'=>'',
                        'distrito'=>$rrr['distrito'],
                        'provincia'=>$rrr['provincia'],
                        'departamento'=>$rrr['departamento'],
                        'estado_civil'=>$rrr['estado_civil'],
                        'dir'=>$rrr['direccion'],                       //Direccion
                        'edad'=>calcular_edad($rrr['fecha_nac']),       //Edad
                        'fecha_nac'=>$rrr['fecha_nac'],
                        'sexo'=>$rrr['sexo'],
                        'nacionalidad'=>$p['nacionalidad'],
                        'pais'=>$p['pais'],
                        'ocupacion'=>$rrr['ocupacion'],
                        'idcliente_tipo'=>1,
                        'es_conyuge'=>1,
                        'fecha_nac'=>$p['fecha_nac']
                        );
                  }
            }
          
      }
    }
    
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
<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="scripts.js"></script>
<link href="stylos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var hhh = $(window).height()-145;
$(document).ready(function()
{ 
    tinymce.init({
    selector: "textarea",     
    theme: "modern",
    height : hhh,
    language : "es",   
    browser_spellcheck : true,   
    convert_fonts_to_spans: true, 
    content_css : "estilos.css",  
    fontsize_formats: "10pt=13px 11pt=15px 12pt=16px 13pt=17px 13.5pt=18px 14pt=19px",
    style_formats: [                
                {title: '16', inline: 'span', styles: {fontSize: '22px'}},                                
                {title: '20', inline: 'span', styles: {fontSize: '26px'}},
                {title: '22', inline: 'span', styles: {fontSize: '29px'}},
                {title: '24', inline: 'span', styles: {fontSize: '32px'}},
                {title: '26', inline: 'span', styles: {fontSize: '35px'}},
                {title: '28', inline: 'span', styles: {fontSize: '37px'}},
                {title: '32', inline: 'span', styles: {fontSize: '42px'}},
        ],
    
    plugins: [
        "advlist autolink lists link image charmap hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save autosave table contextmenu directionality",
        "emoticons template paste moxiemanager print tabfocus textcolor colorpicker textpattern fullpage "
    ],
    menubar: "file tools table format view insert edit",
    toolbar1: "save | fontselect styleselect fontsizeselect | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image | forecolor backcolor | print | searchreplace |",    
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ],
    init_instance_callback : "call_back_function"
});

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
              $("#templat_anio").val(ui.item.anio);
              return false;
          }
      }).data( "autocomplete" )._renderItem = function( ul, item ) 
      {
          return $( "<li></li>" )
              .data( "item.autocomplete", item )
              .append( "<a>"+ item.correlativo + " ("+item.anio+")</a>" )
              .appendTo( ul );
      };
    $("#reload").click(function()
    {
       var t = $("#templat").val(),
          a = $("#templat_anio").val();
       if(t!="")
       {
          if(confirm('Realmente deseas cargar la plantilla?'))
          {
              var idk = $("#idkardex").val();              
              window.location = "index.php?idkardex="+idk+"&template="+t+"&anio="+a;
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
if(hhh<0) hhh = 500
function saveKardex()
{   
    var idkardex = $("#idkardex").val(),
            cont = $("#content_ifr").contents().find("#tinymce").html(),
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
function call_back_function()
{
  $("#content_ifr").contents().find("body").attr("contenteditable","false");  
  $("#content_ifr").contents().find("body").css("margin","0");  
  $("#content_ifr").contents().find(".page").attr("contenteditable","true");
  $("#content_ifr").contents().find(".page").select();
}

</script>
<body style="font-size:65%;background:#FAFAFA; margin:0; padding:0px">
<form method="post" action="index.php" name="frm" id="frm">
    <div style="width:100%; padding:5px 0; background:url('../png/green.png') repeat-x">
        <div style="padding:0 6px;">
        <span style="width:300px; color:#FFFFFF; font-size:12px; margin: 0px 10px 0 0; ">ESCRITURA: <b><?php echo $r['correlativo']; ?></b>  <?php if($_GET['template']!=""){ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Usando el Modelo de ".$_GET['template']." del a&ntilde;o ".$_GET['anio']."]"; } ?></span>
        <span id="msg" style="font-size:11px; color:#fff; display:none">Guardando cambios...</span>        
        <a class="btn close" href="javascript:window.close();" style="float:right;">CERRAR</a>
        <a class="btn config" href="javascript:" id="config-page" style="float:right;">CONFIGURAR PAGINA</a>  
        <span style="float:right; padding:0 20px">
          <label style="color:#FFF">CARGAR PLANTILLA: </label>
          <input type="text" name="templat" id="templat" value="<?php echo $_GET['template'] ?>" style="width:80px" class="ui-widget-content ui-corner-all text" maxlength="7" />
          <input type="hidden" name="templat_anio" id="templat_anio" value="<?php echo $_GET['anio'] ?>" class="ui-widget-content ui-corner-all text"  />
          <input type="button" name="reload" id="reload" value="Cargar" />
        </span>        
        <input type="hidden" name="idkardex" id="idkardex" value="<?php echo $Id; ?>" />
        </div>
        <div style="clear:both"></div>
    </div>   
    <textarea name="content" style="width:100%;">      
    <?php   
        if($plantilla_template=="")      
        {
         if($flag)
         {
             $plantilla = str_replace("%kardex%", $r['correlativo'], $plantilla);
             $plantilla = str_replace("%escritura%", $r['escritura'], $plantilla);
             $plantilla = str_replace("%minuta%", $r['minuta'], $plantilla);
             $plantilla = str_replace("%servicio%", validValur($r['servicio']), $plantilla);

             $plantilla = str_replace("%ruta%", validValur($r['ruta']), $plantilla);
             $plantilla = str_replace("%via%", validValur($r['via']), $plantilla);
             $plantilla = str_replace("%motivo%", validValur($r['motivo']), $plantilla);
             $plantilla = str_replace("%dia%", $dia, $plantilla);
             $plantilla = str_replace("%name_mes%", $meses[(int)$mes-1], $plantilla);
             $plantilla = str_replace("%mes%", $mes, $plantilla);
             $plantilla = str_replace("%anio%", $anio, $plantilla);
             
             $plantilla = str_replace("%dial%", validValur(trim($DiaL)), $plantilla);
             $plantilla = str_replace("%mesl%", validValur(trim($MesL)), $plantilla);
             $plantilla = str_replace("%aniol%", validValur(trim($AnioL)), $plantilla);
             $plantilla = str_replace("%diam%", validValur(trim($DiaM)), $plantilla);
             $plantilla = str_replace("%mesm%", validValur(trim($MesM)), $plantilla);
             $plantilla = str_replace("%aniom%", validValur(trim($AnioM)), $plantilla);

             $plantilla = str_replace("%fojai%", validValur($FojaI), $plantilla);
             $plantilla = str_replace("%fojaf%", validValur($FojaF), $plantilla);
             $plantilla = str_replace("%seriei%", validValur($SerieI), $plantilla);
             $plantilla = str_replace("%serief%", validValur($SerieF), $plantilla);  
             $plantilla = str_replace("%desc_bien%", validValur($descripcion), $plantilla);

             $monto_letra = CantidadEnLetraP($monto);
             $plantilla = str_replace("%monto_letra%", $monto_letra, $plantilla);
             $plantilla = str_replace("%monto%", number_format($monto,2), $plantilla);

             $part =  participantes($data,$IdServicio);
             $plantilla = str_replace("%participantes%", $part,$plantilla);
             $part =  verOtorgantes($data);
             $plantilla = str_replace("%otorgantes%", $part,$plantilla);
             $part =  verFavorecidos($data);
             $plantilla = str_replace("%favorecidos%", $part,$plantilla);
             $part =  verIntervinientes($data);
             $plantilla = str_replace("%intervinientes%", $part,$plantilla);
             $part =  participantes_firma($data);
             $plantilla = str_replace("%participantes_firma%", $part,$plantilla);
             $cuerpoVehiculo = cuerpoVehiculo(array($r['clasev'], $r['marcav'], $r['aniofabv'], $r['modelov'], $r['colorv'], $r['motorv'], $r['cilindrosv'], $r['seriev'], $r['ruedasv'], $r['combustiblev'], $r['fechaincripcionv'], $r['carroceriav'], $r['placa']));        

             $pago = constancia_pago($data,$data_pay,$monto_total,$moneda,$fecha_escritura,$servicio,$documento_notarial);             
             $plantilla = str_replace("%medio_pago%", $pago,$plantilla);

              //********************
              //**Casos especiales**
              //********************
              //Para plantillas vehiculares
                $plantilla = str_replace("%cuerpovehiculo%", $cuerpoVehiculo, $plantilla);          

              //Participantes para autorizaciones de viajes 
                $txt = participantes_v($data,$IdServicio);
                $plantilla = str_replace("%participantes_v%", $txt, $plantilla);   

              //Datos del menor, para autorizaciones de viajes
                $txt = datos_menor($data);
                $plantilla = str_replace("%datos_menor%", $txt, $plantilla); 

              //
              $part_firma = participantes_firma_v($data);
              $plantilla = str_replace("%participantes_firma_v%", $part_firma, $plantilla);

              //Variables autogeneradas
              $plantilla = str_replace("%denominacion_1%", $_SESSION['denominacion_1'], $plantilla); 
              $plantilla = str_replace("%denominacion_2%", $_SESSION['denominacion_2'], $plantilla); 




             //Fecha de Salida (Viaje) 
             $f = explode("-",$r['fecha_salida']);
             $fechas = $f[2]." de ".$meses[(int)$f[1]-1]." del ".$f[0];
	           //Fecha de Regreso (Viaje)
             $f = explode("-",$r['fecha_retorno']);
             $fechar = $f[2]." de ".$meses[(int)$f[1]-1]." del ".$f[0];

	           $plantilla = str_replace("%fecha_salida%",$fechas,$plantilla);
             $plantilla = str_replace("%fecha_retorno%",$fechar,$plantilla);
            // print_r($par);
             /*
             foreach ($par as $key => $value) 
             {   
	              $participacion = $value['participacion'];
                if($value['participacion']=="madre1"||$value['participacion']=="tutor1")
                {                          
                   $bval = seachp("padre1",$par);
                   if(!$bval)                   
                      $participacion = "padre1";                                                               
                }
                $plantilla = str_replace("%".$participacion."%", fupper(utf8_decode($value['nombres'])), $plantilla);
                $plantilla = str_replace("%d".$participacion."%", fupper($value['d'.$value['participacion']]), $plantilla);     
                $plantilla = str_replace("%td".$participacion."%", fupper($value['td'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%dir".$participacion."%", fupper(utf8_decode($value['dir'.$value['participacion']])), $plantilla);                   
                $plantilla = str_replace("%edad_text".$participacion."%", fupper(CantidadEnLetra($value['edad'.$value['participacion']])), $plantilla);
                $plantilla = str_replace("%edad".$participacion."%", fupper($value['edad'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%distrito".$participacion."%", fupper($value['distrito'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%provincia".$participacion."%", fupper($value['provincia'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%departamento".$participacion."%", fupper($value['departamento'.$value['participacion']]), $plantilla);
                $plantilla = str_replace("%estado_civil".$participacion."%", fupper($value['estado_civil'.$value['participacion']]), $plantilla);
              }
              */
         }
         //echo $plantilla;
       }
       else 
       {
          $plantilla = $plantilla_template;
       }
       echo goNewVersion($plantilla);
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
<div id="templates"></div>
</body>
</html>