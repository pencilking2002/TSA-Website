
var res = new Object();
res.vr = {
    "cnt" : ".resources-container",   
    "res_id" : "resources_id",
    "res_ac" : "resources_ac",
    "action" : "resources_load",
    "init"   : function(){ 
        jQuery(function(){
            /* custom post action box */
            jQuery('div.resources-container div.resource').each(function(){
                
                jQuery( this ).hover(function(){
                    jQuery( this ).children( 'div.resource-actionbox' ).show();
                    jQuery( this ).find('div.resource-actionbox ul li a').click(function(){
                        jQuery( this ).parent().parent().parent().hide( 'fast' );
                    });
                });
                jQuery( this ).mouseleave( "hover" , function(){
                    jQuery( this ).children( 'div.resource-actionbox' ).hide( 'fast' );
                });
            });

            /* box tabber menu action */
            jQuery( 'ul.box-panel-menu li' ).click(function(){

                var parent = jQuery( this ).parent().parent();
                jQuery( this ).parent().find('li').removeClass('current');
                jQuery('div.box-panel' , parent ).addClass('hidden');
                jQuery('div.box-panel.box-panel-' + jQuery( this ).attr('class') , parent ).removeClass('hidden');
                
                jQuery( this ).addClass( 'current' );
            });
            
            jQuery( 'div.box-panel div.standard-box-generic-field.hidden' ).hide();
        });
    }
};
res.r = function( method , args ){
    tools.init = function( result ){
        if( jQuery.trim( result ).substr( 0 , 5 ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( 'span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg('span.result-mssg');
            
            if( method == 'add' ){
                field.clean( 'div.resource-addbox' );
                field.h('.resource-addbox');
            }
            
            if( method == 'import' ){
                jQuery( 'textarea.extra__settings__io.export-area' ).html( result );
                document.location.href=window.location.href;
                return 0;
            }
        }
        jQuery( res.vr.cnt ).html( result );
        res.vr.init();
    };
    tools.r( res.vr.action , method , args );
}
res.copy = function( resurce ){
    jQuery(function(){
        jQuery.cookie( res.vr.res_id , resurce , {expires: 365, path: '/'} );
        jQuery.cookie( res.vr.res_ac , 'copy' , {expires: 365, path: '/'} );
    });
}
res.move = function( resurce ){
    jQuery(function(){
        jQuery.cookie( res.vr.res_id , resurce , {expires: 365, path: '/'} );
        jQuery.cookie( res.vr.res_ac , 'move' , {expires: 365, path: '/'} );
    });
}
res.paste = function( parent , page ){
    jQuery(function(){
        if( jQuery.cookie( res.vr.res_id ).length > 0 && jQuery.cookie( res.vr.res_ac ).length > 0 ){
            
            if( jQuery.cookie( res.vr.res_ac ) == 'move' ){
                res.r( 'move' , [ jQuery.cookie( res.vr.res_id ) , parent , page ] );
            }
            
            if( jQuery.cookie( res.vr.res_ac ) == 'copy' ){
                res.r( 'copy' , [ jQuery.cookie( res.vr.res_id ) , parent , page , -1 ] );
            }
            
            jQuery.cookie( res.vr.res_id , '' , {expires: 365, path: '/'} );
            jQuery.cookie( res.vr.res_ac , '' , {expires: 365, path: '/'} );
            jQuery('div.resources-action a.paste').remove();
        }
    });
}

res.tax = new Object();
res.tax.vr = {
    "cnt": ".taxonomy-container",
    "action": "taxonomy_load",
    "init"  : function(){ 
        jQuery(function(){
            /* custom post action box */
            jQuery('div.taxonomy-container div.taxonomy').each(function(){
                jQuery( this ).hover(function(){
                    jQuery( this ).children( 'div.taxonomy-actionbox' ).show();
                    jQuery( this ).find('div.taxonomy-actionbox ul li a').click(function(){
                        jQuery( this ).parent().parent().parent().hide( 'fast' );
                    });
                });
                jQuery( this ).mouseleave( "hover" , function(){
                    jQuery( this ).children( 'div.taxonomy-actionbox' ).hide( 'fast' );
                });
            });
        });
    }
};
res.tax.r = function( method , args ){
    tools.init = function( result ){
        if( jQuery.trim( result ) == 'error' ){
            field.vr.mssg = false;
            field.mssg( 'span.result-mssg' );
            return 0;
        }else{
            field.vr.mssg = true;
            field.mssg('span.result-mssg');
            
            if( method == 'add' ){
                field.clean( 'div.taxonomy-addbox' );
            }
        }
        
        if( method == 'getCustomTaxonomy' ){
            jQuery( 'div.custom-taxonomy.latest-custom-post' ).html( result );
            return 0;
        }
        
        if( method == 'getTaxonomyTerms' ){
            jQuery( 'div.taxonomy-terms.latest-custom-post' ).html( result );
            return 0;
        }
        jQuery( res.tax.vr.cnt ).html( result );
        res.tax.vr.init();
    };
    tools.r( res.tax.vr.action , method , args );
}
