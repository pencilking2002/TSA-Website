/* Copyright (c) 2010 WordImpressed.com jFlow Plus derived from Kean Loong Tan's orgininal jFlow http://www.wordimpressed.com
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * jFlow 1.2 (Plus)
 * Version: jFlow Plus
 * Requires: jQuery 1.2+
 * 
 * modified by StudioPress to add loop; scroll- up, down, left; cover- up, down, left, right; fade; wipe
 */

(function($) {
	$.fn.jFlow = function(options) {
		var opts = $.extend({}, $.fn.jFlow.defaults, options);
		var randNum = Math.floor(Math.random()*11);
		var jFC = opts.controller;
		var jFS =  opts.slideWrapper;
		var jSel = opts.selectedWrapper;
		var maxi = $(jFC).length;
		opts.reverse = (opts.effect == 'down' || opts.effect == 'right') ? 1 : 0;
		var cur = opts.reverse ? maxi - 1 : 0;
		var timer;
/*
main animation routine
*/
		var gsanimate = function (dur,cur) {
			opts.isanimated = 1;
			var cov = opts.effect.split('-');
			if (cov[0] == 'cover')
				cover(cov[1],cur);
			else if (opts.effect == 'fade')
				fade(cur);
			else if (opts.effect == 'wipe')
				wipe(cur);
			else
				slide(dur,cur);
		}
/*
individual animations
*/
		var slide = function (dur, i) {
			$(opts.slides).children().css({
				overflow:"hidden"
			});
			$(opts.slides + " iframe").hide().addClass("temp_hide");
			if (opts.vertical)
				animation = { marginTop: "-" + (i * $(opts.slides).find(":first-child").height()) + "px" };
			else
				animation = { marginLeft: "-" + (i * $(opts.slides).find(":first-child").width()) + "px" };
			
			$(opts.slides).animate(
				animation,
				opts.duration*(dur),
				opts.easing,

				function(){
					$(opts.slides).children().css({
						overflow:"hidden"
					});
					$(".temp_hide").show();

					opts.isanimated = 0;
				}
			);
		}
		var fade = function (i) {
			$(opts.slides+' > .jFlowSlideContainer:eq(' + opts.slideprevious + ')').fadeOut(opts.duration,'linear',function(){
				$(opts.slides+' > .jFlowSlideContainer').hide();
				if (i < maxi)
					$(opts.slides+' > .jFlowSlideContainer:eq(' + i + ')').fadeIn(opts.duration / 2,opts.easing);
				else
					$(opts.slides+' > .jFlowSlideContainer:eq(0)').fadeIn(opts.duration / 2,opts.easing);

				opts.isanimated = 0;
			});
		}
		var wipe = function (i) {
			var slide = $(opts.slides+' > .jFlowSlideContainer:eq(' + i + ')');
			gseffect((i == 0 ? -1 : -2),-1,slide,i);
		}
		var cover = function (dir,i) {
			var slide = $(opts.slides+' > .jFlowSlideContainer:eq(' + i + ')');
			var tstart,tleft=0;

			switch(dir) {
				case 'up':
					tstart = (i == 0 ? 1 : 0);
					break;
				case 'down':
					tstart = (i == 0 ? -1 : -2);
					break;
				case 'left':
					tstart = (i == 0 ? 0 : -1);
					tleft = 1;
					break;
				case 'right':
					tstart = (i == 0 ? 0 : -1);
					tleft = -1;
					break;
			}
			gseffect(tstart,tleft,slide,i);
		}
/*
animation routines for fade, cover & wipe
*/
		var gseffect = function (margintop,marginleft,slide,i) {
			opts.isanimated = 1;
			if (i == 0)
				$(opts.slides+' > .jFlowSlideContainer:eq(' + opts.slideprevious + ')').css({ position: 'absolute' });
			slide.css({ zIndex: 10 });
			slide.css({
				marginTop: (slide.height() * margintop) + 'px',
				marginLeft: (slide.width() * marginleft) + 'px'
			});
			slide.show();
			slide.animate({
					marginTop: (slide.height() * (i == 0 ? 0 : -1)) + 'px',
					marginLeft: '0px'
				},
				opts.duration,
				opts.easing,
				function() {
					finisheffect(slide);
				}
			);					 
		}
		var finisheffect = function(slide) {
			$(opts.slides+' > .jFlowSlideContainer:eq(' + opts.slideprevious + ')').hide().css({ position: 'relative' });
			slide.css({
				zIndex: 0,
				marginTop: '0px',
				marginLeft: '0px'
			});
			opts.isanimated = 0;
		}
/*
keep in sync with slider
*/
		var doanimation = function (dur,cur,skip) {
			if (!skip) {
				$(jFC).removeClass(jSel);
				gsanimate(dur,cur);
			}
			$(jFC).eq(cur).addClass(jSel);			
		}
/*
window resize
*/
		var resize = function (x){
			$(jFS).css({
				position:"relative",
				width: opts.width,
				height: opts.height,
				overflow: "hidden"
			});
			//opts.slides or #mySlides container
			$('.genesis-slider-scroll '+opts.slides).css({
				position:"relative",
				width: $(jFS).width()*(opts.vertical?1:$(jFC).length)+"px",
				height: $(jFS).height()*(opts.vertical?$(jFC).length:1)+"px",
				overflow: "hidden"

			});
			// jFlowSlideContainer
			$(opts.slides).children().css({
				position:"relative",
				width: $(jFS).width()+"px",
				height: $(jFS).height()+"px",
				"float":"left",
				overflow:"hidden"
			});
			if (opts.vertical) {
				$('.genesis-slider-scroll '+opts.slides).css({
					marginTop: "-" + (cur * $(opts.slides).find(":eq(0)").height() + "px")
				});
			} else {
				$('.genesis-slider-scroll '+opts.slides).css({
					marginLeft: "-" + (cur * $(opts.slides).find(":eq(0)").width() + "px")
				});
			} 
		}
/*
event handlers
*/
		var doprev = function (x){
			if (opts.isanimated)
				return;

			var dur = 1;
			opts.slideprevious = cur;
			if (cur > 0)
				cur--;
			else if (maxi > 1 && opts.loop) {
				if (opts.vertical) {
					$(opts.slides).css({
						marginTop: "-" + $(opts.slides+' > .jFlowSlideContainer:first').height() + "px"
					});
				} else {
					$(opts.slides).css({
						marginLeft: "-" + $(opts.slides+' > .jFlowSlideContainer:first').width() + "px"
					});
				}
				$(opts.slides+' > .jFlowSlideContainer').last().clone(true).insertBefore(opts.slides+' > .jFlowSlideContainer:first');
				$(opts.slides+' > .jFlowSlideContainer').last().remove();
			} else {
				cur = maxi -1;
				dur = cur;
			}
			doanimation(dur,cur,false);
		}
		var donext = function (x){
			if (opts.isanimated)
				return;

			var dur = 1;
			opts.slideprevious = cur;
			if (cur < maxi - 1)
				cur++;
			else if (maxi > 1 && opts.loop) {
				first = $(opts.slides+' > .jFlowSlideContainer:first').clone(true);
				$(opts.slides).append(first);
				$(opts.slides+' > .jFlowSlideContainer:first').remove();
				if (opts.vertical) {
					$(opts.slides).css({
						marginTop: "-" + ((maxi - 2) * $(opts.slides+' > .jFlowSlideContainer:first').height()) + "px"
					});
				} else {
					$(opts.slides).css({
						marginLeft: "-" + ((maxi - 2) * $(opts.slides+' > .jFlowSlideContainer:first').width()) + "px"
					});
				}
			} else {
				cur = 0;
				dur = maxi -1;
			}
			doanimation(dur,cur,false);
		}
		var dotimer = function (x){
			if((opts.auto) == true) {
				if(timer != null) 
					clearInterval(timer);

				timer = setInterval(function() {
					if (opts.isanimated)
						return;

					dotimer();
					if (opts.reverse){
						doprev();
					}else
						donext();
				}, opts.timer);
			}
		}
		$(this).find(jFC).each(function(i){
			$(this).click(function(){
				if (opts.isanimated)
					return;

				clearInterval(timer);
				opts.isanimated = 1;
				$(jFC).removeClass(jSel);
				$(this).addClass(jSel);
				var dur = Math.abs(cur-i);
				gsanimate(dur,i);
				cur = i;
				dotimer();
			});
		});
/*
initialize the controller
*/
		$(opts.slides).before('<div id="'+jFS.substring(1, jFS.length)+'"></div>').appendTo(jFS);
		$(opts.slides).find("div").each(function(){
			$(this).before('<div class="jFlowSlideContainer"></div>').appendTo($(this).prev());
		});
		opts.vertical=(opts.effect=='up'||opts.effect=='down') ? 1 : 0;
		cov = opts.effect.split('-');
		if (cov[0] == 'cover' || opts.effect == 'fade' || opts.effect == 'wipe') {
			$(opts.slides+' > .jFlowSlideContainer').hide();
			$(opts.slides+' > .jFlowSlideContainer:eq(' + cur + ')').show();
		}
		doanimation(0,cur,true);

		// sets initial size
		resize();
		// resets size
		$(window).resize(resize);

		$(opts.prev).click(function(){
			if (opts.isanimated)
				return;

			if (opts.loop && opts.reverse && cur < (maxi - 1)) {
				for (i = cur + 1; i < maxi; i++) {
					$(opts.slides+' > .jFlowSlideContainer').last().clone(true).insertBefore(opts.slides+' > .jFlowSlideContainer:first');
					$(opts.slides+' > .jFlowSlideContainer').last().remove();
				}
				cur = maxi - 1;
			} else if (!opts.reverse && cur > 0 && (opts.loop || $.inArray(opts.effect, [ '', 'right', 'left', 'up', 'down' ]) < 0)) {
				for (i = 0; i < cur; i++) {
					first = $(opts.slides+' > .jFlowSlideContainer:first').clone(true);
					$(opts.slides).append(first);
					$(opts.slides+' > .jFlowSlideContainer:first').remove();
				}
				cur = 0;
			}
			dotimer();
			if (opts.reverse)
				donext();
			else
				doprev();
		});

		$(opts.next).click(function(){
			if (opts.isanimated)
				return;

			dotimer();
			if (opts.reverse)
				doprev();
			else
				donext();
		});
//Pause/Resume function fires at hover
		dotimer();
		$(opts.slides).hover(
			function() {
			clearInterval(timer);
			},
		function() {
			dotimer();
			}
		);
	};
	$.fn.jFlow.defaults = {
		controller: ".myController", // must be class, use . sign
		slideWrapper : "#slides", // must be id, use # sign
		selectedWrapper: "jFlowSelected",  // just pure text, no sign
		easing: "swing",
		width: "100%",
		loop: 0,
		effect: "right",
		prev: ".slider-previous", // must be class, use . sign
		next: ".slider-next" // must be class, use . sign
	};
})(jQuery);