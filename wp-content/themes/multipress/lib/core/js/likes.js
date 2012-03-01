
var likes = new Object();

likes.reset = function( customID , limit , page ){
    jQuery(function(){
        if( page == 1 ){
            jQuery( 'span.btn.result.reset' ).html( __('update ..') );
        }
        jQuery.post( ajaxurl , {'action' : 'reset_likes' , 'customID' : customID , 'newLimit' : limit , 'page' : page } , function( result ){
           if( result > 0 ){
               var n = (( parseInt( result ) - 1 ) * 150 );
               jQuery( 'span.btn.result.reset' ).html( n + __(' posts updated .. ') );
               likes.reset( customID , limit , result );
           }else{
               jQuery( 'span.btn.result.reset' ).html( '' );
               return 0; 
           } 
       } ); 
    });
}

likes.generate = function( customID , page ){
    jQuery(function(){
        if( page == 1 ){
            jQuery( 'span.btn.result.generate' ).html( 'update ..' );
        }
        jQuery.post( ajaxurl , { 'action' : 'generate_likes' , 'customID' : customID , 'page' : page } , function( result ){
           if( parseInt( result ) > 0 ){ 
               var n = (( parseInt( result ) - 1 ) * 150 );
               jQuery( 'span.btn.result.generate' ).html( n + __(' posts updated .. ') );
               likes.generate( customID , parseInt( result ) ); 
           }else{
               jQuery( 'span.btn.result.generate' ).html( '' );
               return 0; 
           } 
       }); 
    });
}

likes.remove = function( customID , page ){
    jQuery(function(){
        if( page == 1 ){
            jQuery( 'span.btn.result.remove' ).html( __( 'remove ..' ) );
        }
        jQuery.post( ajaxurl , { 'action' : 'remove_likes' , 'customID' : customID , 'page' : page } , function( result ){
           if( result > 0 ){ 
               var n = (( parseInt( result ) - 1 ) * 150 );
               jQuery( 'span.btn.result.remove' ).html( n + __(' remove from posts .. ') );
               likes.remove( customID , result ); 
           }else{
               jQuery( 'span.btn.result.remove' ).html( '' );
               return 0; 
           } 
       }); 
    });
}