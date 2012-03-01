var $slidePanel;

jQuery.fn.slideToElem = function(delay, callback) {
	delay = delay || 300;
	var pos = jQuery(this).offset();
	jQuery('html, body').animate({ scrollTop: pos.top }, delay, callback);
};

jQuery(function(){
	jQuery('#slidePanel')
		.bind('fly', function(e, state, obj) {
			var $this = jQuery(this).stop();
			if (state === true) {
				
				var h2top = jQuery('.loop-container-view').offset().top;
				var pos = jQuery(obj).offset().top; 
				/* var top_possition is defined in /js/all.js.php*/
				$this.show().animate({ top: parseInt(pos) - top_possition + 'px' }, 300); /* w/ small slider*/
			} else {
				$this
					.animate({ top: '700px' }, 300, function() {
						$this.hide();
					});
			} 
		});
		/*jQuery('#slidePanel').bind('click', function() {
			jQuery(this).trigger('fly', false);
		});*/
	
	/* Lightbox  */
	jQuery('a.openFly').live('click', function(e) {
		e.preventDefault();
		var $this = jQuery(this),
			postId = $this.data('id');
			
		if (!postId) return;
		
		jQuery('#ajax-indicator').show();
		jQuery.ajax({
			url: ajaxurl,
			data: '&action=get_single_posts&post_id='+postId,
			type: 'POST',
			cache: false,
			success: function (data) { 
				jQuery('#ajax-indicator').hide();
				jQuery('#slidePanel').html(data);
				//jQuery('#slidePanel').find(".hotkeys-meta").hide();
				$this.slideToElem(300, function() {
					jQuery('#slidePanel').trigger('fly', [true, $this]);
				});
			},
			error: function (xhr) {
				
			}
		});
	});
	
});	

	function get_c_post(custom_posts,active_post_type,nr_posts,post_view,light_box, container_id){
		jQuery('#ajax-indicator').show();
		jQuery.ajax({
			url: ajaxurl,
			data: '&action=list_posts&active_post_type='+active_post_type+'&custom_posts='+custom_posts+'&nr_posts='+nr_posts+'&post_view='+post_view+'&light_box='+light_box+'&container_id='+container_id,
			type: 'POST',
			cache: false,
			success: function (data) { 
				jQuery('#ajax-indicator').hide();
				
				jQuery('#'+container_id).html(data);
				jQuery(document).ready(function(){
					jQuery('.readmore, .full-screen').mosaic();
				});
				
			},
			error: function (xhr) {
				
			}
		});
		
	}
	
	
	
	function get_post_box(post_id, targetElem){
		jQuery('#ajax-indicator').show();
		
		jQuery.ajax({
			url: ajaxurl,
			data: '&action=get_single_posts&post_id='+post_id,
			type: 'POST',
			cache: false,
			success: function (data) { 
				jQuery('#ajax-indicator').hide();
				//json = eval("(" + data + ")");
				$slidePanel.html(data);
				$(targetElem).slideToElem();
				
			},
			error: function (xhr) {
				
			}
		});
	}

	function close_post(){ 
		jQuery('#slidePanel').animate({ top: '700px' }, 300, function() {  
			jQuery('#slidePanel').hide();
		});
		
	}