/* Settings for home slideshow */
$(function() {
		
		$('#homeslider').cycle({
			fx:		'fade',
			speed:   3000,
			timeout: 3000,
			delay: 3000,
			next: '#next',
			prev: '#prev',
			pager: '.nav'
		});
	});

