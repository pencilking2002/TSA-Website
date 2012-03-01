jQuery(document).ready(function() {
	
	/*reset inputs when page is reloaded*/
	resetBoxSettings();
	
	jQuery('#box_type').change(function() {
		jQuery('#box_sample').attr('class','cosmo-box '+ jQuery(this).val());
	});
	
	
	jQuery('#box_text_size').change(function() {
		jQuery('#box_sample').css('font-size',jQuery(this).val());
	});

    jQuery('#box_color , #box_style').change(function() {
        jQuery( 'option' , this ).each(function(){
            if( jQuery('#box_sample div.fr').hasClass( jQuery( this ) . attr('value') ) ){
                jQuery('#box_sample div.fr').removeClass( jQuery( this ) . attr('value') );
            }
        });

		jQuery('#box_sample div.fr').addClass( jQuery( this ) . val() );
	});



    var title;
    var content;
    var rtitle;
    var rcontent;
    var url;
    var color;
    var style;

    var result;
    
    jQuery('#box_style').change(function(){
        var val = jQuery( this ).val();
        if( val == 'default' ){
            result  = '<div class="fl">';
            result += '<span class="cosmo-ico"></span>';
            result += '<h5>' + title + '</h5>';
            result += '<p>' + content + '</p>';
            result += '</div>';

            jQuery('#box_sample').html( result );
        }
    });

	jQuery('.box-text').keyup(function() {
        result = '';
        if( jQuery('#box_title').val() == '' ){
            title = 'Box title';
        }else{
            title = jQuery('#box_title').val();
        }

		if( jQuery('#box_content').val() == '' ){
            content = 'Box content';
		}else{
			content = jQuery('#box_content').val();
		}

        if( jQuery('#box_right_title').val() == '' ){
            rtitle = '';
		}else{
			rtitle = jQuery('#box_right_title').val();
		}

        if( jQuery('#box_right_description').val() == '' ){
            rdescription = '';
		}else{
			rdescription = jQuery('#box_right_description').val();
		}

        if( jQuery('#box_url').val() == '' ){
            url = '';
		}else{
			url = jQuery('#box_url').val();
		}

        if( jQuery('#box_color').val() == '' ){
            color = '';
		}else{
			color = jQuery('#box_color').val();
		}

        if( jQuery('#box_style').val() == '' ){
            style = '';
		}else{
			style = jQuery('#box_style').val();
		}

        
        result += '<div class="fl">';
        result += '<span class="cosmo-ico"></span>';
        result += '<h5>' + title + '</h5>';
        result += '<p>' + content + '</p>';
        result += '</div>';
        if( ( rdescription.length > 0 || rtitle.length > 0 ) && style != 'default' ){
            result += '<div class="fr ' + color + ' ' + style + '">';
            result += '<a href="'+ url +'">'+ rtitle +'<span>' + rdescription + '</span><a>'
            result += '</div>';
        }

        jQuery('#box_sample').html( result );
	});
	
});

function insertBox(){
	
	result = '[box type="'+jQuery('#box_type').val()+'" size="'+jQuery('#box_text_size').val()+'"';

    if( jQuery('#box_title').val() == '' ){
        title = 'Box title';
    }else{
        title = jQuery('#box_title').val();
    }

    if( jQuery('#box_content').val() == '' ){
        content = 'Box content';
    }else{
        content = jQuery('#box_content').val();
    }

    if( jQuery('#box_url').val() == '' ){
        url = '';
    }else{
        url = jQuery('#box_url').val();
    }

    if( jQuery('#box_style').val() == '' ){
        style = '';
    }else{
        style = jQuery('#box_style').val();
    }
    
    
    if( jQuery('#box_right_title').val() == '' ){
        rtitle = '';
    }else{
        if( style != 'default' ){
            rtitle = jQuery('#box_right_title').val();
        }else{
            rtitle = '';
        }
    }

    if( jQuery('#box_right_description').val() == '' ){
        rdescription = '';
    }else{
        if( style != 'default' ){
            rdescription = jQuery('#box_right_description').val();
        }else{
            rdescription = '';
        }
    }

    if( jQuery('#box_color').val() == '' ){
        color = '';
    }else{
        color = jQuery('#box_color').val();
    }

    result += ' title="' + title + '" right_title="' + rtitle + '" right_description="' + rdescription + '" url="' + url + '" style="' + style + ' ' + color + '" ]' + content + '[/box]';
	
	Editor.AddText( "content" , "\n" + result + "\n");
	showNotify();
}

function resetBoxSettings(){
	jQuery('#box_content').val('');
	jQuery('#box_type option:first').attr('selected','selected');
	jQuery('#box_size option:first').attr('selected','selected');
	jQuery('#box_sample').attr('class','cosmo-box default');
	jQuery('#box_sample').html( '<span class="cosmo-ico"></span>'+__('Box content.') );
}