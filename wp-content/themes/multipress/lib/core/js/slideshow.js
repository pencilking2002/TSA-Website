
var slideshow = new Object()

slideshow.vr = {
    'action' : 'slideshow_load',
    'cnt' : '.panel-slideshow',
    'sort' : function( postID ){
        jQuery(function(){
            jQuery( slideshow.vr.cnt + " ul").sortable({ beforeStop : function(){
                    var args = new Array();
                    jQuery( 'li' , this  ).each(function( i ){
                        args[i] = jQuery( this ).find('input.slider-item-id').val();
                    });
                    
                    slideshow.r( 'sort' , [ postID , args ] , slideshow.vr.cnt );
                }
            });
        });
    }
}

slideshow.r = function( method , args , selector ){
    tools.init = function( result ){
        if( method == 'add' ){
            if( jQuery.trim( result ) == 'error' ){
                field.vr.mssg = false;
                field.mssg( 'span.result-mssg' );
                return 0;
            }else{
                field.vr.mssg = true;
                field.mssg('span.result-mssg');
            }
        }
        
        jQuery( selector ).html( result );
        
        if( method == 'getSearch' ){
            field.vr.init();
            jQuery("#slider-image-id").val(0);
            if( args[0] != 'image' ){
                jQuery(function(){
                    jQuery( slideshow.vr.cnt + ' .generic-hint , .panel-slideshow-action .generic-hint' ).show();
                });
            }else{
                jQuery(function(){
                    jQuery( slideshow.vr.cnt + ' .generic-hint , .panel-slideshow-action .generic-hint' ).hide();
                });
            }
        }
    }
    
    tools.r( slideshow.vr.action , method , args );
}