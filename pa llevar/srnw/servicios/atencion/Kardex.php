<script type="text/javascript">

$(document).ready(function(){

//   $( "#NroKardex" ).autocomplete({
//                 minLength: 0,
//                 source: '../../libs/autocompletar/kardex.php',
//                 focus: function( event, ui ) 
//                 {
//                     //$( "#DocRepresentante" ).val( ui.item.dni_ruc );      
//                     $("#NroKardex").val(ui.item.correlativo);            
//                     return false;
//                 },
//                 select: function( event, ui ) 
//                 {
//                      $("#NroKardex").val(ui.item.correlativo);
//                      //$("#cargo").focus();
//                     return false;
//                 }
//             }).data( "autocomplete" )._renderItem = function( ul, item ) {                
//                 return $( "<li></li>" )
//                     .data( "item.autocomplete", item )
//                     .append( "<a>"+ item.correlativo + "</a>" )
//                     .appendTo( ul );
//             };   
        




// $('#existe').click(function() 
//  {
//   if($(this).is(':checked'))
//    {
//     $("#NroKardex").attr('readonly',false);
//    } else {$("#NroKardex").attr('readonly',true);}
//  });

});

</script>
<?php
    if(!session_id()){ session_start(); }
	include('../../config.php');
	$Anio = $_POST['Anio'];
	$Tipo = $_POST['Tipo'];
	$Id = $_POST['IdServicio'];	
?>
    <!-- <div id="kexiste">
     <input type="text" name="NroKardex" id="NroKardex" style="width:80px;" class="inputtext" readonly>
     <input type="checkbox" name="existe" id="existe"> Existe Kardex
    </div> -->
<!--<select name="NroKardex" id="NroKardex" class="select" onchange="Tab('Precio');" >
 	<option value="">Ninguno</option>

<?php	
/*	if ($Id==118 || $Id==245){
            $SelectK = "SELECT lpad(CAST(C.correlativo AS varchar), 5, '0') FROM carta C WHERE C.anio='$Anio' AND C.estado=3 AND C.idnotaria='".$_SESSION["notaria"]."' AND NOT EXISTS(SELECT correlativo FROM carta WHERE carta.idnotaria='".$_SESSION["notaria"]."' AND carta.correlativo=C.correlativo AND anio = '$Anio' AND estado <= 1) GROUP BY C.correlativo ";
	}else{
            $SelectK = "SELECT K.correlativo FROM kardex K WHERE K.anio='$Anio' AND K.estado=3 AND K.idnotaria='".$_SESSION["notaria"]."' AND K.correlativo LIKE '$Tipo%' AND NOT EXISTS(SELECT correlativo FROM kardex WHERE idnotaria='".$_SESSION["notaria"]."' AND correlativo=K.correlativo AND anio = '$Anio' AND estado <= 1) GROUP BY K.correlativo ";
	}
	$ConsultaK = $Conn->Query($SelectK);
	while($rowK=$Conn->FetchArray($ConsultaK)){
            if($Id==118 || $Id==245){

    */
?>

	  <option value="<?php //echo $Tipo.$rowK[0];?>"><?php //echo $Tipo.$rowK[0];?></option>
<?php
      //      }else{
?>
          <option value="<?php //echo $rowK[0];?>"><?php //echo $rowK[0];?></option>
<?php                
    //        }
	//}
?>
</select>
-->