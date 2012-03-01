
var additional = new Object();
additional.vr = {
    "action" : "additional_load",
    "cnt" : ".panel-fields"
};

additional.r = function( method , args ){
    tools.init = function( result ){
        if( jQuery.trim( result ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( 'span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg('span.result-mssg');
            
            if( method == 'add' ){
                field.clean( 'div.additional-add-form' );
            }   
        }
        jQuery( additional.vr.cnt ).html( result );
        box.r( 'items' , [ args[0] ] );
    };
    tools.r( additional.vr.action , method , args );
}