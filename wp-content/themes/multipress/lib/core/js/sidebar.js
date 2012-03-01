
var sidebar = new Object();

sidebar.vr = {
    'action' : 'sidebar_load',
    'cnt' : '.sidebars-container',
    'init': function(){
        jQuery(function(){
            /* custom post action box */
            jQuery('div.sidebars-container div.sidebar').each(function(){
                jQuery( this ).hover(function(){
                    jQuery( this ).children( 'div.sidebar-actionbox' ).show();
                    jQuery( this ).find('div.sidebar-actionbox ul li a').click(function(){
                        jQuery( this ).parent().parent().parent().hide( 'fast' );
                    });
                });
                jQuery( this ).mouseleave( "hover" , function(){
                    jQuery( this ).children( 'div.sidebar-actionbox' ).hide( 'fast' );
                });
            });
        });
    }
};

sidebar.r = function( method , args ){
    tools.init = function( result ){
        if( jQuery.trim( result ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( 'span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg('span.result-mssg');
            
            if( method == 'add' ){
                field.clean( 'div.sidebar-addbox' );
            }
        }
        jQuery( sidebar.vr.cnt ).html( result );
        field.vr.init();
        sidebar.vr.init();
        
    };
    tools.r( sidebar.vr.action , method , args );
}