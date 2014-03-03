(function( $ ){
  $.fn.required = function() {
    if ( $(this).val() == '' ) {
        if($(this).attr("title"))
            {
                var msg = $(this).attr("title"),   
                w = $(this).css('width'),
                xy = $(this).offset(),
                div = '';                
                if(msg!="")
                    {   
                        div = '<div id="pop-alert-ui" class="ui-state-highlight ui-corner-all" style="top:'+(xy.top-1)+'px; left:'+(parseInt(w)+xy.left + 15)+'px ;position:absolute;z-index:9999; display:none;  padding: 0em 0.3em; width:200px ">';
                        div += '<p style="margin:3px"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>';
                        div += '<strong>Hey! </strong>';
                        div += msg;
                        div += '</p></div>';
                        
                        $('body').append(div);
                        $("#pop-alert-ui").fadeTo('slow', 0.95).delay(4000).fadeOut(500,function(){
                            $("#pop-alert-ui").remove();
                        });

                    }
                 
            }        
        $(this).addClass('ui-state-error');
        $(this).focus();
        return false;
    }else {
        $(this).removeClass('ui-state-error')
        return true;
    }
  };
})( jQuery );