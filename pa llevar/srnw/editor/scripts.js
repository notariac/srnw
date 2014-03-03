var currentML = 0;
var currentMR = 0;
$(document).ready(function(){
	$("#margin").dialog({
		title:'Configuracion de Pagina - Margenes',
		modal:true,
		autoOpen: false,
		buttons :{'Grabar':function()
		        {
		            setMargenes();
		        },
		         'Cerrar': function(){$(this).dialog('close');}}
	});
	$("#config-page").click(function(){
        loadMargenes();
        $("#margin").dialog('open');
    });
});
function loadMargenes()
{
    var ml = $(".page",self.content_ifr.document).css("paddingLeft"),
        mr = $(".page",self.content_ifr.document).css("paddingRight");
        ml = parseFloat(ml);
        ml = toCm(ml);
        mr = parseFloat(mr);
        mr = toCm(mr);
        currentMR = mr.toFixed(1);
        currentML = ml.toFixed(1);
    $("#margen-left").val(ml.toFixed(1));
    $("#margen-right").val(mr.toFixed(1));
}
function setMargenes()
{
    var ml = $("#margen-left").val(),  //En cm.
        mr = $("#margen-right").val(); //En cm.
    var wpage = $(".page",self.content_ifr.document).css("width");
    wpage = parseFloat(wpage);    
    if(ml>currentML)
    {
        var dif = ml - currentML;
        dif = toPixel(dif);
        wpage = wpage - dif;                
    }
    else 
    {
        if(ml<currentML)
        {
            var dif =  currentML - ml;
            dif = toPixel(dif);        
            wpage = wpage + dif;        
        }        
    }
    currentML = ml;
    if(mr>currentMR)
    {
        var dif = mr - currentMR;
        dif = toPixel(dif);
        wpage = wpage - dif;        
    }
    else 
    {
        if(ml<currentMR)
        {
            var dif =  currentMR - mr;
            dif = toPixel(dif);
            wpage = wpage + dif;
        }        
    }  
    currentMR = mr;  
    ml = parseFloat(ml);
    ml = toPixel(ml);
    mr = parseFloat(mr);
    mr = toPixel(mr);
    $(".page",self.content_ifr.document).css("paddingLeft",ml+"px");
    $(".page",self.content_ifr.document).css("paddingRight",mr+"px");
    $(".page",self.content_ifr.document).css("width",wpage+"px");
    return false;
}

function toCm(pixel)
{
    var cm = 0.0264583333333334*pixel;
    return cm;
}
function toPixel(cent)
{
    var pix = cent*1/0.0264583333333334;
    return pix;
}