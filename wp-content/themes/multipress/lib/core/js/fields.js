
/* field box ( show / hide ) */
field = new Object();
field.vr = {
    "mssg" : '',
    "init" : function(){
        /*  autocomplete input with post title */
        var autocomplete = function(){
            jQuery(function(){
                jQuery('input.generic-search').each(function(){
                    var self = this;
                    jQuery( self ).autocomplete({ 
                        serviceUrl: ajaxurl + '?action=search&params=' + jQuery( self ).parent().children('input.generic-params').val(), 
                        minChars:2,
                        delimiter: /(,|;)\s*/, 
                        maxHeight:400, 
                        width:300, 
                        zIndex: 9999, 
                        deferRequestBy: 0, 
                        noCache: false, 
                        onSelect: function( value , data){
                            jQuery(function(){
                                jQuery( self ).parent().children('input.generic-value').val( data );
                                /* action */
                                if( jQuery( self ).parent().parent('div.short-generic-field.parent-attachments').length ){
                                    jQuery.post( ajaxurl , {
                                        "action" : 'attachment_load', 
                                        "method" : 'getNextPostsChildren', 
                                        "args" : [ data , jQuery( self ).parent().parent().parent().parent().find( 'h3.title' ).attr('index') ] } , 
                                        function( result ){
                                            if( jQuery( self ).parent().parent('div.short-generic-field.parent-attachments').parent().children('div.panel-custom-post').length ){
                                                jQuery( self ).parent().parent('div.short-generic-field.parent-attachments').parent().children('div.panel-custom-post').html( result );
                                                autocomplete();
                                            }else{
                                                jQuery( self ).parent().parent('div.short-generic-field.parent-attachments').parent().append('<div class="panel-custom-post"></div>');
                                                jQuery( self ).parent().parent('div.short-generic-field.parent-attachments').parent().children('div.panel-custom-post').html( result );
                                                autocomplete();
                                            }
                                        }
                                    );
                                }
                            });
                        }
                    });
                });
            });
        };

        autocomplete();

        /* digit text field */
        jQuery('input[type="text"].digit').bind( 'keyup' , function(){
            var value = jQuery( this ).val()
            jQuery( this ).val( value.replace( /[^\d|\.|\,]/g , '' ) );
        });

        /* color piker */
        jQuery('.generic-input input[id^="pick-"]').each(function(index) {
            var farbtastic;
            var $obj = this;
            (function(jQuery){
                var pickColor = function( a ) {
                    farbtastic.setColor( a );
                    jQuery( '#pick-' + jQuery( $obj ).attr( 'op_name' ) ).val( a );
                    jQuery( '#link-pick-' + jQuery( $obj ).attr( 'op_name' ) ).css( 'background-color' , a );
                };

                jQuery(document).ready( function() {

                    farbtastic = jQuery.farbtastic( '#color-panel-'  + jQuery( $obj ).attr( 'op_name' ) , pickColor );

                    pickColor( jQuery( '#pick-' + jQuery( $obj ).attr( 'op_name' ) ).val() );

                    jQuery( '#link-pick-' + jQuery( $obj ).attr( 'op_name' ) ).click( function( e ) {
                        jQuery( '#color-panel-'  + jQuery( $obj ).attr( 'op_name' ) ).show();
                        e.preventDefault();
                    });

                    jQuery( '#pick-' + jQuery( $obj ).attr( 'op_name' ) ).keyup( function() {
                        var a = jQuery( '#pick-' + jQuery($obj).attr('op_name') ).val(),
                            b = a;

                        a = a.replace( /[^a-fA-F0-9]/ , '');
                        if ( '#' + a !== b )
                            jQuery( '#pick-' + jQuery($obj).attr( 'op_name' ) ).val( a );
                        if ( a.length === 3 || a.length === 6 )
                            pickColor( '#' + a );
                    });

                    jQuery(document).mousedown( function() {
                        jQuery('#color-panel-'  + jQuery( $obj ).attr( 'op_name' ) ).hide();
                    });
                });
            })(jQuery);
        });
    }
};
field.s = function( selector ){
    jQuery(function(){
        jQuery( 'body' ).append('<div style="background:#111; opacity:0.7; width:100%; height:100%; position:fixed;top:0px; left:0px; display:block; z-index:100;" id="admin-shadow"></div>');
        jQuery( selector ).show();
        /* var h = (parseInt( parseInt( screen.height ) /2 ) - parseInt( parseInt( jQuery( selector ).height() ) /2 ) ) - 200 ; */
        var h = 50;
        var w = parseInt( parseInt( screen.width ) /2 ) - parseInt( parseInt( jQuery( selector ).width() ) /2 );
        jQuery( selector ).css( { 'left' :  w + 'px' , 'top' : h + 'px' } );
        jQuery('body #admin-shadow').click(function(){
            jQuery( selector ).hide();
            jQuery( this ).remove();
        });
    });
}
field.h = function( selector ){
    jQuery(function(){
       jQuery( 'body #admin-shadow' ).remove();
       jQuery( selector ).hide();
    });
}
field.load = function( resources , method , args , selector1 , selector2  ){
    jQuery(function(){
        jQuery.post( ajaxurl , {
            "action" : resources.vr.action,
            "method" : method,
            "args" : args
        } ,
        function( result ){
            if( jQuery( selector2 ).length ){
                jQuery( selector2 ).remove();
            }
            jQuery( selector1 ).append( result );
            field.s( selector2 );
            resources.vr.init();
        });
    });
}
field.mssg = function( selector ){
    if( field.vr.mssg ){
        tools.ah( selector + ' .success' );
    }else{
        tools.ah( selector + ' .error' );
    }
}
field.radio_icon = function( selector , index ){
	jQuery(function(){
        jQuery('.generic-radio-icon input.' + selector ).removeAttr("checked");
        jQuery('img.pattern-texture.' + selector ).removeClass( 'selected' );

        jQuery('.generic-input-radio-icon.' + index + ' input.' + selector + '.' + index ).attr('checked' , 'checked');
        jQuery('img.pattern-texture.' + selector + '.' + index ).addClass( 'selected' );
    });
}
field.clean = function( selector ){
    jQuery(function(){
        jQuery( selector + ' input[type="text"]' ).each(function(){
            jQuery( this ).val('');
        });
        jQuery( selector + ' input[type="hidden"]' ).each(function(){
            jQuery( this ).val('');
        });
        jQuery( selector + ' input[type="checkbox"]' ).each(function(){
            jQuery( this ).removeAttr('checked');
        });
        jQuery( selector + ' select' ).each(function(){
            jQuery( this ).removeAttr('selected');
        });
        jQuery( selector + ' textarea' ).each(function(){
            jQuery( this ).val('');
        });
    });
}
field.preview = function( page , tab , group , prefix , args , selector ){
    jQuery(function(){
        jQuery.post( ajaxurl , {
            "action" : "text_preview",
            "page" : page,
            "tab" : tab,
            "group" : group,
            "prefix" : prefix,
            "args" : args 
        },
        function( result ){
            jQuery( selector ).html( result );
        });
    });
}
field.upload = function( selector ){
    deleteUserSetting('uploader');
    setUserSetting('uploader', '1');

    jQuery(document).ready(function() {
        (function(){
            var tb_show_temp = window.tb_show;
            window.tb_show = function(){
                tb_show_temp.apply( null , arguments);
                jQuery('#TB_iframeContent').load(function(){
                    jQuery( this ).contents().find('p.upload-html-bypass').remove();
                    jQuery( this ).contents().find('div#html-upload-ui').show();
                    $container = jQuery( this ).contents().find('tr.submit td.savesend');
                    var sid = '';
                    $container.find('div.del-attachment').each(function(){
                        var $div = jQuery(this);
                        sid = $div.attr('id').toString();
                        if( typeof sid != "undefined" ){
                            sid = sid.replace(/del_attachment_/gi , "" );
                            jQuery(this).parent('td.savesend').html('<input type="submit" name="send[' + sid + ']" id="send[' + sid + ']" class="button" value="'+__('Use this Attachment')+'">');
                        }else{
                            var $submit = $container.find('input[type="submit"]');
                            sid = $submit.attr('name');
                            if( typeof sid != "undefined" ){
                                sid = sid.replace(/send\[/gi , "" );
                                sid = sid.replace(/\]/gi , "" );
                                $container.html('<input type="submit" name="send[' + sid + ']" id="send[' + sid + ']" class="button" value="'+__('Use this Attachment')+'">');
                            }
                        }
                    });

                    $container.find('input[type="submit"]').click(function(){
                        $my_submit = jQuery( this );
                        sid = $my_submit.attr('name');
                        if( typeof sid != "undefined" ){
                            sid = sid.replace(/send\[/gi , "" );
                            sid = sid.replace(/\]/gi , "" );
                        }else{
                            sid = 0;
                        }
                        var html = $my_submit.parent('td').parent('tr').parent('tbody').parent('table').html();
                        
                        window.send_to_editor = function() {
                            var attach_url = jQuery( 'input[name="attachments['+sid+'][url]"]' , html ).val();
                            jQuery( selector ).val( attach_url );
                            tb_remove();
                        }
                    });
                });

            }})()

            formfield = jQuery( selector ).attr('name');
            tb_show('', 'media-upload.php?post_id=-1&amp;type=image&amp;TB_iframe=true');
            return false;
    });
}

field.upload_id = function( selector , upload_url ){
    	if( upload_url == ""){
        tb_show_url = 'media-upload.php?post_id=-1&amp;type=image&amp;TB_iframe=true&amp;flash=0';
	}else{
        tb_show_url = upload_url;
	}

    deleteUserSetting('uploader');
    setUserSetting('uploader', '1');
	
    jQuery(document).ready(function() {
        (function(){
            var tb_show_temp = window.tb_show;
            window.tb_show = function(){
                tb_show_temp.apply(null, arguments);
                jQuery('#TB_iframeContent').load(function(){
                    
                    if( jQuery( this ).contents().find('p.upload-html-bypass').length ){
                        jQuery( this ).contents().find('p.upload-html-bypass').remove();
                    }
                    
                    jQuery( this ).contents().find('div#html-upload-ui').show();

                    $container = jQuery( this ).contents().find('tr.submit td.savesend');
                    var sid = '';
                    $container.find('div.del-attachment').each(function(){
                        var $div = jQuery(this);
                        sid = $div.attr('id').toString();
                        if( typeof sid != "undefined" ){
                            sid = sid.replace(/del_attachment_/gi , "" );
                            jQuery(this).parent('td.savesend').html('<input type="submit" name="send[' + sid + ']" id="send[' + sid + ']" class="button" value="'+__('Use this Attachment')+'">');
                        }else{
                            var $submit = $container.find('input[type="submit"]');
                            sid = $submit.attr('name');
                            if( typeof sid != "undefined" ){
                                sid = sid.replace(/send\[/gi , "" );
                                sid = sid.replace(/\]/gi , "" );
                                $container.html('<input type="submit" name="send[' + sid + ']" id="send[' + sid + ']" class="button" value="'+__('Use this Attachment')+'">');
                            }
                        }
                    });

                    $container.find('input[type="submit"]').click(function(){
                        $my_submit = jQuery( this );
                        sid = $my_submit.attr('name');
                        if( typeof sid != "undefined" ){
                                sid = sid.replace(/send\[/gi , "" );
                                sid = sid.replace(/\]/gi , "" );
                        }else{
                            sid = 0;
                        }
                        var html = $my_submit.parent('td').parent('tr').parent('tbody').parent('table').html();
                        window.send_to_editor = function() {
                            var attach_url = jQuery('input[name="attachments['+sid+'][url]"]',html).val();
                            jQuery( selector ).val(  attach_url  );
                            jQuery( selector + '-id' ).val( sid );

                            if( jQuery( 'img' + selector ).lengt > 0 ){
                                jQuery( 'img' + selector ).attr( "src" ,  attach_url  );
                            }

                            tb_remove();
                        }
                    });
                });

            }})()

        formfield = jQuery( selector ).attr('name');
        tb_show('', tb_show_url);
        return false;
    });
}