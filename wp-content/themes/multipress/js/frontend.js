function add_image_post(){
	//if(jQuery('#image_content-tmce').hasClass('active')){
		jQuery('#image_content-html').click();
		jQuery('#image_content-tmce').click();
	//}	
	/*disable the button to not submit the post twice*/
	
	jQuery("#submit_img_btn").attr("disabled", "disabled");
	
	jQuery("#ajax-indicator").show();
	jQuery('#not_logged_msg').hide();
	jQuery('#success_msg').hide();
	jQuery('#img_post_title_warning').hide();
	jQuery('#img_warning').hide();
	
	jQuery('#img_post_title').removeClass('invalid');
	jQuery('#image_url').removeClass('invalid');
	jQuery('#img_upload').removeClass('invalid');
	var data = jQuery('#form_post_image').serialize();
		
	jQuery.ajax({
		url: ajaxurl,
		data: data+'&action=add_image_post&category_id='+jQuery('#img_post_cat').val()+window.image_uploader.serialize(),
		type: 'POST',
		cache: false,
		success: function (data) {
			json = eval("(" + data + ")");
    		if(json['error_msg'] && json['error_msg'] != ''){
    			if(json['image_error'] != ''){ /*if image OR image link is invalid*/
    				
    				jQuery('#image_url').addClass('invalid');
    				jQuery('#img_upload').addClass('invalid');
    				
    				window.image_uploader.show_error(json['image_error']);
    			}
    			
    			if(json['title_error'] != ''){ /*If title is not set*/
    				jQuery('#img_post_title_warning').html(json['title_error']);
    				jQuery('#img_post_title_warning').show();
    				jQuery('#img_post_title').addClass('invalid');
    			}

				if(json['auth_error'] != ''){ /*is user is not logged in*/
					jQuery('#not_logged_msg').show();
				}
				
				
				var h3_position = jQuery('#pic_upload h3').offset().top ;
				jQuery.scrollTo( h3_position, 400); /* scroll to in .4 of a second */
				
    		}else{
    			jQuery('#success_msg').html(json['success_msg']);
    			jQuery('#success_msg').show();
    			
    			/*clear inputs*/
    			jQuery('.front_post_input').each(function(index) {
    			    jQuery(this).val('');
    			});
    			
    			jQuery('#image_content').val('');
    			jQuery('#image_content_ifr').contents().find(".mceContentBody").html('');
    			
    			var button_position = jQuery('#submit_img_btn').offset().top ;
    			jQuery.scrollTo( button_position - 200, 400); /* scroll to in .4 of a second */
    		}
    		jQuery("#ajax-indicator").hide();
    		jQuery("#submit_img_btn").removeAttr("disabled");
    		
    		
		},
		error: function (xhr) {
			jQuery("#ajax-indicator").hide();
			alert(xhr);
		}
	});	
}
	
function add_video_post(){
	//if(jQuery('#video_content-tmce').hasClass('active')){
		jQuery('#video_content-html').click();  
		jQuery('#video_content-tmce').click();
	//}	
	/*disable the button to not submit the post twice*/
	
	jQuery("#submit_video_btn").attr("disabled", "disabled");
	
	jQuery("#ajax-indicator").show();
	jQuery('#not_logged_msg').hide();
	
	jQuery('#video_url_warning').hide();
	jQuery('#video_post_title_warning').hide();
	
	jQuery('#success_msg').hide();
	jQuery('#video_post_title').removeClass('invalid');
	jQuery('#video_url').removeClass('invalid');
	
	var data = jQuery('#form_post_video').serialize();
	
	jQuery.ajax({
		url: ajaxurl,
		data: data+'&action=add_video_post&category_id='+jQuery('#video_post_cat').val()+window.video_uploader.serialize(),
		type: 'POST',
		cache: false,
		success: function (data) {
			json = eval("(" + data + ")");
    		if(json['error_msg'] && json['error_msg'] != ''){
    			if(json['video_error'] != ''){ /*if image OR image link is invalid*/
    				
    				jQuery('#video_url').addClass('invalid');
    				window.video_uploader.show_error(json['video_error']);
    			}
    			
    			if(json['title_error'] != ''){ /*If title is not set*/
    				jQuery('#video_post_title').addClass('invalid');
    				jQuery('#video_post_title_warning').html(json['title_error']);
    				jQuery('#video_post_title_warning').show();
    				
    			}

				if(json['auth_error'] != ''){ /*is user is not logged in*/
					jQuery('#not_logged_msg').show();
				}
				
				var h3_position = jQuery('#video_upload h3').offset().top ;
				jQuery.scrollTo( h3_position, 400); /* scroll to in .4 of a second */
    		}else{
    			jQuery('#success_msg').html(json['success_msg']);
    			jQuery('#success_msg').show();
    			
    			/*clear inputs*/
    			jQuery('.front_post_input').each(function(index) {
    			    jQuery(this).val('');
    			});
    			
    			jQuery('#video_content').val('');
    			jQuery('#video_content_ifr').contents().find(".mceContentBody").html('');
    			
    			var button_position = jQuery('#submit_video_btn').offset().top ;
    			jQuery.scrollTo( button_position - 200, 400); /* scroll to in .4 of a second */
    			
    		}
    		
    		jQuery("#ajax-indicator").hide();
    		jQuery("#submit_video_btn").removeAttr("disabled");
			
		},
		error: function (xhr) {
			jQuery("#ajax-indicator").hide();
			alert(xhr);
			
		}
	});
}

function add_file_post(){
	
	//if(jQuery('#file_content-tmce').hasClass('active')){
		jQuery('#file_content-html').click();
		jQuery('#file_content-tmce').click();
	//}	
	/*disable the button to not submit the post twice*/
	
	jQuery("#submit_file_btn").attr("disabled", "disabled");
	
	jQuery("#ajax-indicator").show();
	jQuery('#not_logged_msg').hide();
	jQuery('#success_msg').hide();
	jQuery('#file_img_post_title_warning').hide();
	jQuery('#file_img_warning').hide();
	jQuery('#file_warning').hide();
	
	jQuery('#file_post_title').removeClass('invalid');
	jQuery('#file_image_url').removeClass('invalid');
	jQuery('#file_img_upload').removeClass('invalid');
	jQuery('#file_upload').removeClass('invalid');
	var data = jQuery('#form_post_file').serialize();

	jQuery.ajax({
		url: ajaxurl,
		data: data+'&action=add_file_post&category_id='+jQuery('#file_post_cat').val()+window.link_uploader.serialize()+window.link_feat_img_uploader.serialize(),
		type: 'POST',
		cache: false,
		success: function (data) {
			json = eval("(" + data + ")");
    		if(json['error_msg'] && json['error_msg'] != ''){
    			if(json['image_error'] != ''){ /*if image OR image link is invalid*/
    				
    				jQuery('#file_image_url').addClass('invalid');
    				jQuery('#file_img_upload').addClass('invalid');
    				
				  window.link_feat_img_uploader.show_error(json['image_error']);
    			}
    			
    			if(json['title_error'] != ''){ /*If title is not set*/
    				jQuery('#file_img_post_title_warning').html(json['title_error']);
    				jQuery('#file_img_post_title_warning').show();
    				jQuery('#file_post_title').addClass('invalid');
    			}

				if( json['file_error'] != ''){
					window.link_uploader.show_error(json['file_error']);
					jQuery('#file_upload').addClass('invalid');
					
				}
				
				if(json['auth_error'] != ''){ /*is user is not logged in*/
					jQuery('#not_logged_msg').show();
				}
				
				
				var h3_position = jQuery('#file_post h3').offset().top ;
				jQuery.scrollTo( h3_position, 400); /* scroll to in .4 of a second */
				
    		}else{
    			jQuery('#success_msg').html(json['success_msg']);
    			jQuery('#success_msg').show();
    			
    			/*clear inputs*/
    			jQuery('.front_post_input').each(function(index) {
    			    jQuery(this).val('');
    			});
    			
    			jQuery('#file_content').val('');
    			jQuery('#file_content_ifr').contents().find(".mceContentBody").html('');
    			
    			var button_position = jQuery('#submit_file_btn').offset().top ;
    			jQuery.scrollTo( button_position - 200, 400); /* scroll to in .4 of a second */
    		}
    		jQuery("#ajax-indicator").hide();
    		jQuery("#submit_file_btn").removeAttr("disabled");
    		
    		
		},
		error: function (xhr) {
			jQuery("#ajax-indicator").hide();
			alert(xhr);
		}
	});
}

function add_audio_post(){
	
	//if(jQuery('#audio_content-tmce').hasClass('active')){
		jQuery('#audio_content-html').click();
		jQuery('#audio_content-tmce').click();
	//}	
	/*disable the button to not submit the post twice*/
	
	jQuery("#submit_audio_btn").attr("disabled", "disabled");
	
	jQuery("#ajax-indicator").show();
	jQuery('#not_logged_msg').hide();
	jQuery('#success_msg').hide();
	jQuery('#audio_img_post_title_warning').hide();
	jQuery('#audio_img_warning').hide();
	jQuery('#audio_warning').hide();
	
	jQuery('#audio_post_title').removeClass('invalid');
	jQuery('#audio_image_url').removeClass('invalid');
	jQuery('#audio_img_upload').removeClass('invalid');
	jQuery('#audio_upload').removeClass('invalid');
	var data = jQuery('#form_post_audio').serialize();
	
	jQuery.ajax({
		url: ajaxurl,
		data: data+'&action=add_audio_post&category_id='+jQuery('#audio_post_cat').val()+window.audio_uploader.serialize()+window.audio_feat_img_uploader.serialize(),
		type: 'POST',
		cache: false,
		success: function (data) {
			json = eval("(" + data + ")");
    		if(json['error_msg'] && json['error_msg'] != ''){
    			if(json['image_error'] != ''){ /*if image OR image link is invalid*/
    				
    				jQuery('#audio_image_url').addClass('invalid');
    				jQuery('#audio_img_upload').addClass('invalid');
    				
					window.audio_feat_img_uploader.show_error(json['image_error']);
    			}
    			
    			if(json['title_error'] != ''){ /*If title is not set*/
    				jQuery('#audio_img_post_title_warning').html(json['title_error']);
    				jQuery('#audio_img_post_title_warning').show();
    				jQuery('#audio_post_title').addClass('invalid');
    			}

				if( json['audio_error'] != ''){ 
					window.audio_uploader.show_error(json['audio_error']);
					jQuery('#audio_upload').addClass('invalid');
					
				}
				
				if(json['auth_error'] != ''){ /*is user is not logged in*/
					jQuery('#not_logged_msg').show();
				}
				
				
				var h3_position = jQuery('#audio_post h3').offset().top ;
				jQuery.scrollTo( h3_position, 400); /* scroll to in .4 of a second */
				
    		}else{
    			jQuery('#success_msg').html(json['success_msg']);
    			jQuery('#success_msg').show();
    			
    			/*clear inputs*/
    			jQuery('.front_post_input').each(function(index) {
    			    jQuery(this).val('');
    			});
    			
    			jQuery('#audio_content').val('');
    			jQuery('#audio_content_ifr').contents().find(".mceContentBody").html('');
    			
    			var button_position = jQuery('#submit_audio_btn').offset().top ;
    			jQuery.scrollTo( button_position - 200, 400); /* scroll to in .4 of a second */
    		}
    		jQuery("#ajax-indicator").hide();
    		jQuery("#submit_audio_btn").removeAttr("disabled");
    		
    		
		},
		error: function (xhr) {
			jQuery("#ajax-indicator").hide();
			alert(xhr);
		}
	});
}

function add_text_post(){
	
	//if(jQuery('#text_content-tmce').hasClass('active')){
		jQuery('#text_content-html').click();
		jQuery('#text_content-tmce').click();
	//}	
	/*disable the button to not submit the post twice*/
	
	jQuery("#submit_text_btn").attr("disabled", "disabled");
	
	jQuery("#ajax-indicator").show();
	jQuery('#not_logged_msg').hide();
	jQuery('#success_msg').hide();
	jQuery('#text_post_title_warning').hide();
	jQuery('#text_warning').hide();
	
	jQuery('#text_post_title').removeClass('invalid');
	
	var data = jQuery('#form_post_text').serialize();
	
	jQuery.ajax({
		url: ajaxurl,
		data: data+'&action=add_text_post&category_id='+jQuery('#text_post_cat').val(),
		type: 'POST',
		cache: false,
		success: function (data) {
			json = eval("(" + data + ")");
    		if(json['error_msg'] && json['error_msg'] != ''){
    			
    			if(json['title_error'] != ''){ /*If title is not set*/
    				jQuery('#text_post_title_warning').html(json['title_error']);
    				jQuery('#text_post_title_warning').show();
    				jQuery('#text_post_title').addClass('invalid');
    			}

				if(json['auth_error'] != ''){ /*is user is not logged in*/
					jQuery('#not_logged_msg').show();
				}
				
				var h3_position = jQuery('#form_post_text h3').offset().top ;
				jQuery.scrollTo( h3_position, 400); /* scroll to in .4 of a second */
    		}else{
    			jQuery('#success_msg').html(json['success_msg']);
    			jQuery('#success_msg').show();
    			
    			/*clear inputs*/
    			jQuery('.front_post_input').each(function(index) {
    			    jQuery(this).val('');
    			});
    			
    			jQuery('#text_content').val('');
    			jQuery('#text_content_ifr').contents().find(".mceContentBody").html('');
    			
    			
    			var button_position = jQuery('#submit_text_btn').offset().top ;
    			jQuery.scrollTo( button_position - 200, 400); /* scroll to in .4 of a second */
    		}
    		jQuery("#ajax-indicator").hide();
    		jQuery("#submit_text_btn").removeAttr("disabled");
    		
    		
		},
		error: function (xhr) {
			jQuery("#ajax-indicator").hide();
			alert(xhr);
		}
	});
	
}