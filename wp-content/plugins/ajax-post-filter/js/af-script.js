(function($){
	var isRunning = false;
	// Return an array of selected navigation classes.
	function loopSelected(_node) {
		var _arr = [];
		_node.each(function(){
			var _class = $(this).attr('data-tax');
			_arr.push(_class);
		});
		return _arr;
	};
	
	// Animate the progress bar based on Ajax step completion.
	function increaseProgressBar(percent){
		$('div#progbar').animate({
			width: percent + '%'
		},30);
	};
	
	// Join the array with an & so we can break it later.
	function returnSelected(){
		var selected = loopSelected($('li.filter-selected'));
			return selected.join('&');
	};
	
	// When the navigation is clicked run the ajax function.
	$('a.ajax-filter-label, a.paginationNav, a.pagelink').live('click', function(e) {
		if(isRunning == false){
			isRunning = true;
			e.preventDefault();
			var relation = $(this).attr('rel');
			if($(this).parent('li').length > 0) {
				$(this).parent('li').toggleClass('filter-selected');
				thisPage = 1;
			}
			if(relation === 'next'){
				thisPage++;
			} else if(relation === 'prev') {
				thisPage--;
			} else if($(this).hasClass('pagelink')){
				thisPage = relation;
			}
			filterAjaxify(returnSelected());
		}
	});
	
	// Do all the ajax functions.
	function filterAjaxify(selected){
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				"action":"affilterposts",
				"filters": selected,
				"posttypes": posttypes,
				"qo": qo,
				"paged": thisPage,
				"_ajax_nonce": nonce
			},
			beforeSend: function(){
				$('div#ajax-loader').fadeIn();
				$('section#ajax-filtered-section').fadeTo('slow',0.4);
				increaseProgressBar(33);
			},
			success: function(html){
				increaseProgressBar(80);
				$('section#ajax-filtered-section').html(html);
			},
			complete: function(){
				$('section#ajax-filtered-section').fadeTo('slow',1);
				increaseProgressBar(100);
				$('div#ajax-loader').fadeOut();
				isRunning = false;
			},
			error: function(){}
		});
	};
})(jQuery);