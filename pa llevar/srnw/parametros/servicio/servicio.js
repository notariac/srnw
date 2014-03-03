$(document).ready(function(){	
        function formatItemC(row){
            return "<table width='100%' border='0'><tr style='font-size:12px;'><td width='300px'>" + row[0] + "</td></tr></table>";
        }
        function formatResult(row){
            return row[0];
        }
        $("#agregarboton").button({icons:{primary:'ui-icon-plus'}}).click(function(e){
            e.preventDefault();
            AgregaParticipacion();
        });
        $('#DocumentoNotarial').autocomplete('../../libs/autocompletar/documentonotarial.php', {
                autoFill: true,
                width: 310,
                selectFirst: false,
                formatItem: formatItemC, 
                formatResult: formatResult,
                mustMatch : false
        }).result(function(event, item){
                $("#DocumentoNotarial").val(item[0]);
                $("#IdDocumentoNotarial").val(item[1]);
                $("#ActoJuridico").attr("disabled", false);
                Tab("ActoJuridico");
        });	
        $('#ActoJuridico').autocomplete('../../libs/autocompletar/actojuridico.php', {
                autoFill: true,
                width: 310,
                selectFirst: false,
                formatItem: formatItemC, 
                formatResult: formatResult,
                cache:false,
                mustMatch : false,
                extraParams : {id : function(){ return $("#IdDocumentoNotarial").val(); }}
        }).result(function(event, item){
                $("#ActoJuridico").val(item[0]);
                $("#IdActoJuridico").val(item[1]);
                Tab("Servicio");
        });	
});
function CambiaLegal(){
    if (document.getElementById('Legal2').checked){
        $('#Legal').val(1);
    }else{
        $('#Legal').val(0);
    }
}	
function CambiaEspecial(){
        if (document.getElementById('Especial2').checked){
            $('#Especial').val(1);
            $('#TrKardexTipo').css("display", "");
            $('#TrCorrelativo').css("display", "none");
            $('#TrFolio').css("display", "none");
            $('#Correlativo2').attr("checked", false);
            $('#Folios2').attr("checked", false);
            CambiaCorrelativo();
            CambiaFolio();
        }else{
            $('#Especial').val(0);
            $('#TrKardexTipo').css("display", "none");
            $('#TrCorrelativo').css("display", "");
            $('#TrFolio').css("display", "");
            $('#KardexTipo').val(0);
        }
}	
function CambiaCorrelativo(){
    if (document.getElementById('Correlativo2').checked){
        $('#Correlativo').val(1);
        $('#TrCorrelativo2').css("display", "");
    }else{
        $('#Correlativo').val(0);
        $('#TrCorrelativo2').css("display", "none");
        $('#CorrelativoNro').val(0);
        $('#Reinicio2').attr("checked", false);
        $('#Reinicio').val(0);
    }
}
function CambiaEstado(){
    if (document.getElementById('Estado2').checked){
        $('#Estado').val(1);
    }else{
        $('#Estado').val(0);
    }
}	
function CambiaFolio(){
    if (document.getElementById('Folio2').checked){
        $('#Folios').val(1);
    }else{
        $('#Folios').val(0);
    }
}	
function VerificaCT(){
    if ($('#ClienteTipo').val()==1){
        $('#ruc_dni').html('D.N.I.');
        $('#razon_nombre').html('Nombre Completo :');
        $('#DniRuc').attr("maxlength", 8);			
    }else{
        $('#ruc_dni').html('R.U.C.');
        $('#razon_nombre').html('Raz&oacute;n Social :');
        $('#DniRuc').attr("maxlength", 11);
    }
}	
function CambiaReinicio(){
    if (document.getElementById('Reinicio2').checked){
        $('#Reinicio').val(1);
    }else{
        $('#Reinicio').val(0);
    }
}
function AgregaParticipacion(){
    var IdParticipacion	= $("#Participacion").val();
    var Participacion	= document.getElementById("Participacion").options[document.getElementById("Participacion").selectedIndex].text;			
    nDestP = nDestP + 1;
    nDestPC = nDestPC + 1;
    var miTabla = document.getElementById('ListaMenu3').insertRow(nDestP);
    var celda1	= miTabla.insertCell(0);
    var celda2	= miTabla.insertCell(1);		
    celda1.innerHTML = "<input type='hidden' name='0formD"  + nDestPC + "_idservicio' id='IdServicioD"  + nDestPC + "' value='"+Id+"' /><input type='hidden' name='0formD"  + nDestPC + "_idparticipacion' value='"  + IdParticipacion + "' />" + Participacion;
    celda2.innerHTML = "<img src='../../imagenes/iconos/eliminar.png' width='16' height='16' onclick='QuitaParticipacion(" + nDestPC + ");' style='cursor:pointer'/>";						
    $('#ConParticipacion').val(nDestPC);		
    var cssString = 'text-align:center;';
    miTabla.style.cssText = cssString;
    miTabla.setAttribute('style',cssString);		
    var cssString = 'padding-left:5;text-align:left;';
    celda1.style.cssText = cssString;
    celda1.setAttribute('style',cssString);	
    $('#Participacion').focus();
}
function QuitaParticipacion(x){	
    var current = window.event.srcElement;   
    while ( (current = current.parentElement) && current.tagName !="TR");{
        current.parentElement.removeChild(current);
        nDestP = nDestP - 1;
    }
}
function AgregaPDT_Notario(){
    var IdActoJuridico	= $("#IdActoJuridico").val();
    var IdDocumentoNotarial	= $("#IdDocumentoNotarial").val();
    var ActoJuridico	= $("#ActoJuridico").val();			
    var IdServicio	= $("#Id").val();
    if(IdActoJuridico.length==0){
        alert('Es necesario seleccionar un Acto Jurídico');
        return false;
    }
    nDestF = nDestF + 1;
    nDestFC = nDestFC + 1;
    var miTabla = document.getElementById('ListaMenu4').insertRow(nDestF);
    var celda1	= miTabla.insertCell(0);
    var celda2	= miTabla.insertCell(1);		
    celda1.innerHTML = "<input type='hidden' name='0formF"  + nDestFC + "_idservicio' id='IdServicioF"  + nDestFC + "' value='"+Id+"' /><input type='hidden' name='0formF"  + nDestFC + "_idacto_juridico' value='"  + IdActoJuridico + "' />" + ActoJuridico+"<input type='hidden' name='0formF"  + nDestFC + "_iddocumento_notarial' value='"  + IdDocumentoNotarial + "' />";
    celda2.innerHTML = "<img src='../../imagenes/iconos/eliminar.png' width='16' height='16' onclick='QuitaPDT_Notario(" + nDestFC + ");' style='cursor:pointer'/>";						
    $('#ConPDT_Notario').val(nDestFC);		
    var cssString = 'text-align:center;';
    miTabla.style.cssText = cssString;
    miTabla.setAttribute('style',cssString);		
    var cssString = 'padding-left:5;text-align:left;';
    celda1.style.cssText = cssString;
    celda1.setAttribute('style',cssString);
    $("#DocumentoNotarial").attr('disabled', true);	
    $("#ActoJuridico").val('');	
    $("#IdActoJuridico").val('');	
    $('#ActoJuridico').focus();
}
function QuitaPDT_Notario(x){	
    var current = window.event.srcElement;   
    while ( (current = current.parentElement) && current.tagName !="TR");{
        current.parentElement.removeChild(current);
        nDestF = nDestF - 1;
    }
}
function Cancelar(){
    window.location.href='index.php';
}	
function ValidarFormEnt(evt){
    var keyPressed 	= (evt.which) ? evt.which : event.keyCode;
    if (keyPressed == 13 ){
        Guardar(Op);
    }
}
