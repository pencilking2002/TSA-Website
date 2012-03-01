
var box = new Object()
box.vr = {
    "action" : "box_load",
    "cnt" : ".box-panel-list",
    "init" : function(){
        res.vr.init();
    }
};

box.r = function( method , args ){
    tools.init = function( result ){        
        if( method != 'edit' && method != 'items' ){
            jQuery( box.vr.cnt ).html( result );
            document.location.href = document.location;
        }else{
            if( jQuery.trim( result ) == 'error' ){
                field.vr.mssg = false;
                field.mssg( 'span.result-mssg' );
                return 0;
            }else{
                field.vr.mssg = true;
                field.mssg('span.result-mssg');
            }
            
            jQuery( box.vr.cnt ).html( result );
        }
    };
    tools.r( box.vr.action , method , args );
}

mbox = new Object();
mbox.r = function( action , method , args , s1 , s2 ){
    tools.init = function( result ){
        if( jQuery.trim( result ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( s2 + ' span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg( s2 + ' span.result-mssg');
            
            if( method == 'add' ){
                field.clean( s2 );
            }
        }
        jQuery( s1 ).html( result );
    };
    tools.r( action , method , args );
}