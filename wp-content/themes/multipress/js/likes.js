
var likes = new Object();

likes.vote = function( item , postID , type ){
	if(likes.registration_required){
		if(likes.login_url){
			document.location.href=likes.login_url;
		}else{
			alert("Registration is required to vote a post, but registration is disabled");
		}
	}else{
		jQuery(function(){
			jQuery( '#ajax-indicator' ).css( 'display' , 'block' );
			jQuery.post( ajaxurl , {
				'action' : 'set_like',
				'postID' : postID,
				'meta_type' : type
			} , function( result ){
				jQuery( '#ajax-indicator' ).css( 'display' , 'none' );
				result = eval('(' + result + ')');
				jQuery( item ).parent().parent().parent().parent().find( 'li.cosmo-love span.ilove').removeClass("voted");
				jQuery( item ).parent(".ilove").addClass("voted");
				jQuery( item ).parent().parent().parent().parent().find( 'li.cosmo-love span.hate em strong' ).html( result.hates );
				jQuery( item ).parent().parent().parent().parent().find( 'li.cosmo-love span.like em strong' ).html( result.likes );
			});    
		});
	}
}

likes.my = function( postID , data , customID ){
	jQuery(function(){
		jQuery(".post_type_selectors").removeClass("current");
		jQuery("#post_type_selected"+customID).addClass("current");
		if(data.length<1){
			jQuery( '#content .element' ).remove();
			jQuery( '#content .entry-footer' ).remove();
			jQuery( '#ajax-indicator').css('display','block');
		}
		if(  !(jQuery( 'div#content div.front-page img#loading' ).length) ){
			jQuery( 'div#content div.front-page' ).append( '<img id="loading" src="'+ themeurl +'/images/loading.gif" style="background:none; float:none; text-align:center; width:auto; height:auto; margin:0px auto !important; clear:both; display:block;" />' );
		}
		jQuery.post( ajaxurl , {'action' : 'my_likes' , 'postID' : postID , 'data' : data , 'customID' : customID } , function( result ){
			if( result.substr( 0 , 1 ) == '{' ){
				var opt = eval("(" + result + ')');
				likes.my( opt.postID , opt.data , customID );
			}else{
				jQuery( 'div#content div.front-page img#loading' ).remove();
				jQuery( 'div.clearfix.get-more' ).remove();
				jQuery( 'div.loop-container-view div.last').removeClass('last');
				jQuery( '#ajax-indicator').css('display','none');
				jQuery( '#content>div:first-child' ).append( result );
				
				jQuery('.readmore').mosaic();
				
				return 0; 
			} 
		}); 
	});
}