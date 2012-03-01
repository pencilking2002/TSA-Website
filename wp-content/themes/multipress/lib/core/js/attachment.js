
attachment = new Object();    
attachment.vr = {
    "action" : "attachment_load",
    'init' : function(){
        return null;
    }
};

attachment.r = function( method , args , selector ){
    tools.init = function( result ){
        if( jQuery.trim( result ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( 'span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg('span.result-mssg');
        }
        
        if( selector != '-' ){
            jQuery( selector ).html( result );
            field.vr.init(); 
        }
    };
    if( method == 'addAttachment' ){
        if( parseInt( args[0] ) > 0 && parseInt( args[1] ) > 0 ){
            tools.r( attachment.vr.action , method , args );
        }else{
            alert( "Please complete all required fields" );
        }
    }else{
        tools.r( attachment.vr.action , method , args );
    }
}