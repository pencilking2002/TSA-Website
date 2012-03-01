<?php 

/*

Template Name: textile-events

*/

?>
<?php get_header(); ?>

<div class="b_content clearfix" id="main">
	<div class="b_page events">

<!-- the tabs -->
		<ul class="tabs">
				<li><a href="#">Upcoming Events</a></li>
				<li><a href="#">Past EVents</a></li>
		</ul>

<!-- tab "panes" -->
		<div class="panes">
			<div class ="upcoming-pane">
				
				<?php query_posts('event_type=upcoming'); ?><!--Only get posts in the 'upcoming' taxonomy-->

				<?php while ( have_posts() ) : the_post(); ?>
				<div <?php post_class('post upcoming'); ?> ><!--Begin Post -->
					<div class ="img-cap">
				  	<p><?php the_post_thumbnail(); ?></p>
				    <div>
				    	<?php /* the_field('event_type'); */ ?>
				      	</span><?php the_terms( $post->ID, 'event_type', '<span class="event-cat">','</span ><span class="event-cat">', '</span>' ); ?></span>
				        </div>
				     </div>

				<div class ="event-body">
				     <h2><a href ="<?php the_permalink(); ?>" rel="<?php the_id(); ?>"><?php the_title(); ?></a></h2>
				     <span><?php the_field('event_dates'); ?></span><br />
				     </span><?php the_field('event_location'); ?></span><br />
				     <p><?php the_content(); ?></p>
				     </span><?php the_field('image_caption'); ?></span>
				</div>    
				</div><!--End Post -->
			
				     <?php endwhile; ?>
					
			</div>
			
			<div class="past-pane">
			
				<?php query_posts('event_type=past'); ?><!--Only get posts in the 'upcoming' taxonomy-->

				<?php while ( have_posts() ) : the_post(); ?>
				<div <?php post_class('post past'); ?> ><!--Begin Post -->
					<div class ="img-cap">
				  	<p><?php the_post_thumbnail(); ?></p>
				    <div>
				    	<?php /* the_field('event_type'); */ ?>
				      	</span><?php the_terms( $post->ID, 'event-category', '<span class="event-cat">','</span ><span class="event-cat">', '</span>' ); ?></span>
				        </div>
				     </div>

				<div class ="event-body">
				     <h2><a href ="<?php the_permalink(); ?>" rel="<?php the_id(); ?>"><?php the_title(); ?></a></h2>
				     <span><?php the_field('event_dates'); ?></span><br />
				     </span><?php the_field('event_location'); ?></span><br />
				     <p><?php the_content(); ?></p>
				     </span><?php the_field('image_caption'); ?></span>
				</div>    
				</div><!--End Post -->
			
				     <?php endwhile; ?>	
				
			</div>
			
			
			</div>

		
	
		</div>
	
	</div>
</div>

<script type ="text/javascript">


jQuery( function() {

	//code for tabs
	jQuery('ul.tabs').tabs('div.panes > div');

	//Generate Event category linkls based on custom unique post classes
	function sort_and_unique( my_array ) {
		my_array.sort();
		for ( var i = 1; i < my_array.length; i++ ) {
			if ( my_array[i] === my_array[ i - 1 ] ) {
				my_array.splice( i--, 1 );
			}
		}
		return my_array;
	};
	//parse through the classes to get one with '-filter' it it
	var str=/(\S+-filter)/;

	var temparr=[];
	jQuery(".post").each(function(){
		if (this.getAttribute('class').match(str)) {
			var tempstr=this.getAttribute('class').match(str);
			jQuery.each(tempstr, function(index, value){
				temparr.push((value.substring(0, value.length-7)).replace('-',' '));
			});		
		}	
	});		

	jQuery(".b_page.events").append("<ul class ='gen event-categories'><li class='event-cat-title'>Event Categories</li><li><a href ='#' class='all-events'>All events</a></li></ul>");
	jQuery.each(sort_and_unique(temparr), function(i, val) {
		jQuery(".b_page.events ul.gen.event-categories").append("<li> <a href='javascript:void(0);'>"+ val + "</a> </li>");
	});

	//append the current class to the clicked event category link
	jQuery("ul.event-categories").on('click', 'a', function() {
			jQuery("ul.event-categories a").removeClass("current");
			jQuery(this).addClass("current");
	
	});

});


</script>

<?php get_footer(); ?>


