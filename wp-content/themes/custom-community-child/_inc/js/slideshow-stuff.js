/* Settings for home slideshow */
$(function() {
		
		$('#homeslider').before("<div class ='slider-controls'><div class='nav'></div>")
		.cycle({
			fx:		'fade',
			speed:   3000,
			timeout: 3000,
			delay: 3000,
			//next: '#next',
			//prev: '#prev',
			pager: '.nav'
		});
	});

