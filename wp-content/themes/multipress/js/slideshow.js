/*
* Author:      Marco Kuiper (http://www.marcofolio.net/)
*/
jQuery(document).ready(function() {
		
	// Backwards navigation
	jQuery("#back").click(function() {
		stopAnimation();
		navigate("back");
	});
	
	// Forward navigation
	jQuery("#next").click(function() {
		stopAnimation();
		navigate("next");
	});
	
	var interval;
	jQuery("#control").toggle(function(){
		stopAnimation();
	}, function() {
		// Change the background image to "pause"
		jQuery(this).css({ "background-image" : "url(images/btn_pause.png)" });
		
		// Show the next image
		navigate("next");
		
		// Start playing the animation
		interval = setInterval(function() {
			navigate("next");
		}, slideshowSpeed);
	});
	
	
	var activeContainer = 1;	
	var currentImg = 0;
	var animating = false;
	var navigate = function(direction) {
		// Check if no animation is running. If it is, prevent the action
		if(animating) {
			return;
		}
		
		// Check which current image we need to show
		if(direction == "next") {
			currentImg++;
			if(currentImg == photos.length + 1) {
				currentImg = 1;
			}
		} else {
			currentImg--;
			if(currentImg == 0) {
				currentImg = photos.length;
			}
		}
		
		// Check which container we need to use
		var currentContainer = activeContainer;
		if(activeContainer == 1) {
			activeContainer = 2;
		} else {
			activeContainer = 1;
		}
		
		showImage(photos[currentImg - 1], currentContainer, activeContainer);
		
	};
	
	var currentZindex = -1;
	var showImage = function(photoObject, currentContainer, activeContainer) {
		animating = true;
		
		// Make sure the new container is always on the background
		currentZindex--;
		
		// Set the background image of the new active container
		jQuery("#headerimg" + activeContainer).css({
			"background-image" : "url(" + photoObject.image + ")",
			"display" : "block",
			"z-index" : currentZindex
		});
		
		// Hide the header text
		jQuery("#headertxt").css({"display" : "none"});
		//$("#headertxt").fadeOut('200');
		
		// Set the new header text
		jQuery("#firstline")
			.attr("href", photoObject.url)
			.html(photoObject.firstline);
		jQuery("#secondline")
			.html(photoObject.secondline);
		jQuery("#pictureduri")
			.attr("href", photoObject.url)
			.html(photoObject.title);
		
		// Fade out the current container
		// and display the header text when animation is complete
		jQuery("#headerimg" + currentContainer).fadeOut(function() {
			setTimeout(function() {
				//$("#headertxt").css({"display" : "block"});
				jQuery("#headertxt").fadeIn(400);
				animating = false;
			}, 500);
		});
	};
	
	var stopAnimation = function() {
		// Change the background image to "play"
		jQuery("#control").css({ "background-image" : "url(images/btn_play.png)" });
		
		// Clear the interval
		clearInterval(interval);
	};
	
	// We should statically set the first image
	navigate("next");
	
	// Start playing the animation
	interval = setInterval(function() {
		navigate("next");
	}, slideshowSpeed);
});