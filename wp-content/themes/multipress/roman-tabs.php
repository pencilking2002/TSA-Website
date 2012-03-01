<?php 

/*

Template Name: roman-tabs

*/

?>

<?php get_header(); ?>

<div class="b_content clearfix" id="main">
	<div class="b_page">

		<ul>
			<li><a href ="#" class="all-events">All events</a></li>
			<li><a href="" class="">Exhibitions</a></li>
			<li><a href="" class="">Conferences</a></li>
			<li><a href="" class="">Lectures</a></li>
			<li><a href="" class="">Study Tours</a></li>
			<li><a href="" class="">TSA events</a></li>  
			<li><a href="" class="">Workshops</a></li>
			 
		</ul>

<!-- the tabs -->
		<ul class="tabs">
				<li class="current"><a href="#">Upcoming Events</a></li>
				<li><a href="#">Past EVents</a></li>
		</ul>

<!-- tab "panes" -->
		<div class="panes">
			<div class ="upcoming-pane">
				
				hhh

			</div>    
		</div>

		<div class="past-pane">Past Events content</div>
	
		</div>
	
	</div>
</div>

<script type ="text/javascript">

jQuery( function() {

	jQuery('ul.tabs').tabs('div.panes > div');

/*	jQuery(".all-events").click( function() {
	event.preventDefault();
	jQuery('.upcoming-pane').load('http://localhost:8888/wp-content/themes/multipress/tabs-test/event-post-loops.php', '.upcoming-get');
	jQuery('.past-pane').load('event-post-loops.php', '.past-get');
	});
	
	jQuery.ajaxSetup({cache:false});
	jQuery(".all-events").click( function() {
		var post_id = $(this).attr("rel");
		jQuery(".upcoming-pane").html("loading...");
		jQuery(".upcoming-page").load("http://<?php echo $_SERVER[HTTP_HOST]; ?>/triqui-ajax" ,{id:post_id});
		return false;

	});	*/
	
});

</script>

<?php get_footer(); ?>

