
(function( $ ) {
    $.fn.combo = function() {
        var estado = true;
        var first = true;
        this.each (function() {
            if ( $(this).val() == 0 ||$(this).val() == ''||$(this).val()==null) {
                $(this).addClass('ui-state-error ui-icon-alert');
                if(first) {
                    $(this).focus();
                    first = false;
                }
                estado = estado && false;
            }else {
                $(this).removeClass('ui-state-error ui-icon-alert')
                estado = estado && true;
            }
        });
        return estado;
    };
})( jQuery );
function addFunctionSelectTr(TableID,fpostprocess){  
    $("#"+TableID+"  tr").click(function(){
        $.each($("#"+TableID+" tbody tr"), function(i){
            if($(this).hasClass("ui-state-highlight"))
            $(this).removeClass("ui-state-highlight");
            $(this).find("td").eq(0).html("");
        });
        $(this).addClass("ui-state-highlight");
        $(this).find("td").eq(0).html("<span class='ui-icon ui-icon-arrowthickstop-1-e'>Selecionado<span>");
    });
    $("#"+TableID+"  tr").dblclick(function(){
       if ( typeof(fpostprocess) == 'function' ) fpostprocess();        
    });
}
    