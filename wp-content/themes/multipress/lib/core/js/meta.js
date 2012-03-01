
var meta = new Object();
meta.r = function( action , method1 , method2 , args ,  selector ){
    jQuery(function(){
        jQuery.post( ajaxurl , {
            "action" : action,
            "method" : method1,
            "args" : args
        } , 
        function( result ) {
            jQuery.post( ajaxurl , {
                "action" : "attachment_load",
                "method" : method2,
                "args"   : [ args[0] , args[1] ]
            } ,
            function( result ){
                jQuery( selector ).html( result );
            });
        });
    });
}