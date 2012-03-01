jQuery(document).ready(function(){

	//widgets scroller
	jQuery(function()
	{
		jQuery('.scroll-pane').jScrollPane();
	});

	//footer widgets
	jQuery('div.footer-area div.w_290').each(function(index) {
		
		if(((index+1) % 3) == 1){  
			jQuery(this).addClass('first');
		}
		//alert(index + ': ' + $(this).text());
	});
	jQuery('div.footer-area aside').each(function(index) {
		
		if(((index+1) % 3) == 0){  
			//jQuery(this).addClass('first');
			jQuery('<div class="clearfix"></div>').insertAfter(this);
		}
		//alert(index + ': ' + $(this).text());
	});
	
	//whitey widgets
	jQuery('div.whitey div.qwe').each(function(index) {
		
		if(index == 0){  
			jQuery(this).addClass('w_610');
		}else{
			jQuery(this).addClass('w_290');
		}
		//alert(index + ': ' + $(this).text());
	});
	
	/* Lightbox single post on mainpage */
	/*var $slidePanel;
	(function($) {
		$slidePanel = jQuery('#slidePanel')
			.bind('fly', function(e, state) {
				var $this = jQuery(this).stop();
				if (state === true) {
					$this
						.show()
						.animate({ top: '165px' }, 300);
				} else {
					$this
						.animate({ top: '700px' }, 300, function() {
							$this.hide();
						});
				}
			})
			.bind('click', function() {
				jQuery(this).trigger('fly', false);
			});

		jQuery('.openFly')
			.bind('click', function(e) {
				e.preventDefault();
				var pos = jQuery(this).offset();
				jQuery('html, body').animate({ scrollTop: pos.top }, 300, function() {
					$slidePanel.trigger('fly', true);
				});					
			});
	})(jQuery);*/
	
	//Scroll to top
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},300);
	});

	
	/* Superfish menu */
	jQuery("ul.sf-menu").supersubs({
			minWidth:    12,
			maxWidth:    32,
			extraWidth:  1
		}).superfish({
			delay: 200,
			speed: 250
		});

	/* Mosaic fade */
	jQuery('.readmore, .full-screen').mosaic();
	jQuery('.circle, .gallery-icon').mosaic({
		opacity:	0.5
	});
	jQuery('.fade').mosaic({
		animation:	'slide'
	});
	
	/* Dynamic twitter widget */
	if (jQuery().slides) {
		jQuery(".dynamic .cosmo_twitter").slides({
			play: 5000,
			effect: 'fade',
			generatePagination: false,
			autoHeight: true
		});
	}
	
	/* Hide title from menu items */
	jQuery(function(){
		jQuery("li.menu-item > a").hover(function(){
			jQuery(this).stop().attr('title', '');},
			function(){jQuery(this).stop().attr();
		});
	});
	
	/* Fixed social media sharing */
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.share_buttons_single_page').length != 0) {
			var top = jQuery('#share_buttons_single_page').offset().top - parseFloat(jQuery('#share_buttons_single_page').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-90) {
					// if so, ad the fixed class
					jQuery('#share_buttons_single_page').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#share_buttons_single_page').removeClass('fixed');
				}
			});
		}
	});

	/* Fixed cart */
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.cosmo-cart').length != 0) {
			var top = jQuery('#cosmo-cart').offset().top - parseFloat(jQuery('#cosmo-cart').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-30) {
					// if so, ad the fixed class
					jQuery('#cosmo-cart').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#cosmo-cart').removeClass('fixed');
				}
			});
		}
	});
	
	/* Sponsors images annimation */
	/*$("#nav-shadow p").append('<img class="shadow" src="images/icons-shadow.png" width="100%" height="27" alt="" />');*/
	jQuery("#nav-shadow p").hover(function() {
		var e = this;
		jQuery(e).find("a").stop().animate({ marginTop: "-20px" }, 250, function() {
			jQuery(e).find("a").animate({ marginTop: "-15px" }, 250);
		});
	jQuery(e).find("img.shadow").stop().animate({ width: "80%", height: "20px", marginLeft: "8px", opacity: 0.25 }, 250);
	},function(){
		var e = this;
		jQuery(e).find("a").stop().animate({ marginTop: "0px" }, 250, function() {
			jQuery(e).find("a").animate({ marginTop: "0px" }, 250);
		});
	jQuery(e).find("img.shadow").stop().animate({ width: "100%", height: "27px", marginLeft: "0", opacity: 1 }, 250);
	});
	
	/* Social-media icons + hotkeys annimation */
	jQuery(".hotkeys-meta span, .social-media li").hover(function() {
		var e = this;
		jQuery(e).find("a").stop().animate({ marginTop: "-8px" }, 250, function() {
			jQuery(e).find("a").animate({ marginTop: "-8px" }, 250);
		});
	},function(){
		var e = this;
		jQuery(e).find("a").stop().animate({ marginTop: "0px" }, 250, function() {
			jQuery(e).find("a").animate({ marginTop: "0px" }, 250);
		});
	});
	/* flickr settings */
    jQuery('.w_260 .flickr_badge_image').each(function(index){
		var x = index % 3;
		if(index !=1 && x == 2){
			jQuery(this).addClass('last');
		}
	});

    jQuery('.w_290 .flickr_badge_image').each(function(index){
		var x = index % 4;
		if(index !=1 && x == 3){
			jQuery(this).addClass('last');
		}
	});

	/* digit input */
	jQuery('input[type="text"].digit').bind('keyup', function(){
		act.accept_digits( this );
	});
	
	/* Widget tabber */
    jQuery( 'ul.widget_tabber li a' ).click(function(){
        jQuery(this).parent('li').parent('ul').find('li').removeClass('active');
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').fadeTo( 200 , 0 );
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').hide();
        jQuery( jQuery( this ).attr('href') + '_panel' ).fadeTo( 200 , 1 );
        jQuery( this ).parent('li').addClass('active');
    });

	 /* Initialize tabs */
	(typeof(jQuery.fn.tabs) === 'function') && jQuery(function() { 
		jQuery('.cosmo-tabs').tabs({ fxFade: true, fxSpeed: 'fast' }); 
	});
	


	/*Case when by default the toggle is closed */
	jQuery(".open_title").toggle(function(){ 
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
		}, function () {
		
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
			
	});
	
	/*Case when by default the toggle is oppened */		
	jQuery(".close_title").toggle(function(){ 
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
		}, function () {
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
			
	});	
	
	/*Accordion*/
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	}); 
	
	/* Flying Header */
	var $window = jQuery(window),
		$header = jQuery('#header-wrapper');

	jQuery.fn.flyingHeader = function() {
		var $instance = jQuery(this),
			offset = $instance.outerHeight() - $instance.find('a').outerHeight();

		if ($window.scrollTop() >= offset) {
			if (!$instance.hasClass('fixed')) {
				setTimeout(function() {
					$instance
						.stop()
						.css({ opacity: 0, top: String(-offset) + 'px' })
						.addClass('fixed')
						.animate({ top: 0, opacity: 1 }, 500, 'easeInOutExpo');
				}, 0);
			}
		} else if ($window.scrollTop() <= offset ) {
			$instance.removeClass('fixed');
		}
		
		return this;
	};

	if ($header.length && $header.hasClass('can-fly')) {
		$window.bind('scroll', function() {
			$header.flyingHeader();
		});	
	}

	/* Quick News */
	var $document = jQuery(document),
		$qnWrapper = jQuery('.cosmo-qnews-wrapper'),
		$qnContent = jQuery('.cosmo-qnews-content'),
		$qnLable = jQuery('.cosmo-qnews-label'),
		$qnClose = jQuery('.cosmo-qnews-close');

	$qnWrapper.data('orig-width', $qnWrapper.width());
	
	$document.delegate('.cosmo-qnews-label a', 'click', function(event) {
		event.preventDefault();

		if ($qnWrapper.hasClass('cosmo-qnews-opened')) return;
		if ($qnWrapper.is(':animated'))	return;

		$qnWrapper
			.css({ width: 0, display: 'block' })
			.animate({ width: $qnWrapper.data('orig-width') +'px' }, 500, 'easeInOutExpo')
			.animate({ height: $qnContent.outerHeight(true) +'px' }, 500, 'easeInOutExpo', function() {
				$qnWrapper.addClass('cosmo-qnews-opened');
				$qnContent.fadeIn(300, function() {
					$qnClose.show();
					$document.trigger('qnews-opened');
				});
			});
	});

	$document.delegate('.cosmo-qnews-close', 'click', function(event) {
		event.preventDefault();
		$qnClose.hide();
		$qnContent.fadeOut(200, function() {
			$qnWrapper
				.animate({ height: $qnLable.outerHeight(true) +'px' }, 500, 'easeInOutExpo')	
				.animate({ width: 0 }, 500, 'easeInOutExpo', function() {
					$qnWrapper.hide();
					$qnWrapper.removeClass('cosmo-qnews-opened');
					$document.trigger('qnews-closed');
				})	
		});
	});

	/* Determine screen resolution */
	var $body = jQuery('body'),
		wSizes = [1600, 1440, 1280, 1024, 800],
		wSizesClasses = ['w1600', 'w1440', 'w1280', 'w1024', 'w800'];
		
	jQuery(window).bind('resize', function() {
		$body.removeClass(wSizesClasses.join(' '));
		var size = jQuery(this).width();
		for (var i=0; i<wSizes.length; i++) {
			if (size >= wSizes[i]) {
				$body.addClass(wSizesClasses[i]);
				break;
			}
		}
	}).trigger('resize');
	
	jQuery(".menu-item").each(function(){
	  jQuery(this).addClass("dropdown");
	});
	jQuery("#header-wrapper .dropdown").hover(function(){//sets the coordinates for the dropdown menu
	  jQuery("#header-wrapper .dropdown").each(function(){
		jQuery(this).find("ul").css("top",jQuery(this).find("a:first-child").height()+"px");
		jQuery(this).find("ul").css("padding-top",(jQuery("#header-wrapper").height()-jQuery(this).find("a:first-child").height())/2+2+"px");
	  });
	});
});

function removePost( postID , homeUrl ){
    jQuery(function(){
        jQuery.post( ajaxurl , {
            'action': 'remove_post',
            'post_id': postID
        } , function (data) {
            document.location = homeUrl; 
        });
    });
}

/* Social sharing */
(function() {
  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
  po.src = 'https://apis.google.com/js/plusone.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})(); 

function playVideo(video_id,video_type,obj,width, height){
  
		
	jQuery.ajax({
		url: ajaxurl,
		data: '&action=play_video&video_id='+video_id+'&video_type='+video_type+'&width='+width+'&height='+height,
		type: 'POST',
		cache: false,
		success: function (data) { 
			//json = eval("(" + data + ")");
    		if(data != ''){
    			obj.html(data);
				if(width == '430'){
					obj.css('margin-left','10px');
				}	
				obj.removeAttr('onclick');
    		}
			
		},
		error: function (xhr) {
			//jQuery('#loading_').hide();
			alert(xhr);
			
		}
	});
}

function contact( action , form , container ){
    jQuery( document ).ready(function(){

        var name  = jQuery( form ).find("input[name=\"name\"]").val();
        var email = jQuery( form ).find("input[name=\"email\"]").val();
		var contact_email = jQuery( form ).find("input[name=\"contact_email\"]").val();
        var phone  = jQuery( form ).find("input[name=\"phone\"]").val();
        var mssg  = jQuery( form ).find("textarea[name=\"message\"]").val();


        jQuery.post( ajaxurl ,
                {
                    "action" : action ,
                    "name" : name,
                    "email" : email,
					"contact_email" : contact_email, 
                    "phone" : phone,
                    "message" : mssg,
                    "btn_send" : "btn_send"
                } ,
                function( data ){
                    var result = '';
                    var array  = data.split( '","' );
                    if( array.constructor.toString().indexOf("Array") == -1 ){
                        for(var i = 0; i < array.length; i++ ){
                            if( jQuery.trim( array[i] ) == mail.email ){
                                result = result + array[i] + '<br />';
                                jQuery( form ).find( "input[name=\"email\"]" ).addClass( 'send-error' );
                            }

                            if( jQuery.trim( array[i] ) == mail.name ){
                                result = result + array[i] + '<br />';
                                jQuery( form ).find( "input[name=\"name\"]" ).addClass( 'send-error' );
                            }

                            if( jQuery.trim( array[i] ) == mail.message ){
                                result = result + array[i] + '<br />';
                                jQuery( form ).find( "textarea[name=\"message\"]" ).addClass( 'send-error' );
                            }
                        }
                        if( result.toString().length > 0 ){
                            jQuery( container ).html( result );
                        }else{
                            jQuery( container ).html( data );

                                jQuery('#name').val('');
                                jQuery('#email').val('');
                                jQuery('#comment').val('');
                                jQuery('#contact_name').val('');
                                jQuery('#contact_email').val('');
                                jQuery('#contact_phone').val('');
                                jQuery('#contact_message').val('');
                        }
                    }else{
                        jQuery( container ).html( data );
                    }
        });
    });

}
