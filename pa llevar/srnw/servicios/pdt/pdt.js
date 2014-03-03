/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
   $("#generar").button({icons:{primary:'ui-icon-search'}}).click(function(e){
       e.preventDefault();
       $("#mostra").html("<center><img src='../../imagenes/avance.gif' width=20 /></center>");
       $.post("allGenerate.php",{
           anio:$("#anio").val(),
           tipo_generar:$("#tipo_generar").val()
       },function(response){
        if($.trim(response)=='.'){
            $("#hola").attr({
                 href:"files/3520"+$("#anio").val()+"10011638126.zip"
            });
            $("#mostra").html(""); 
            $("#boton_descarga").slideUp(true,function(){
               $("#boton_descarga").slideDown(true);
               $("#texto_tipo").html("Descargar Fichero de "+$("#tipo_generar option:selected").html());
            });
            
        }else{
            $("#boton_descarga").slideUp(true,function(){
                $("#mostra").html(response);
            });
        }
       })
   }); 
});


