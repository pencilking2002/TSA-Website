<div class ="upcoming-get">
<!--	<h3><?php echo "Hello Gallery Systems"; ?></h3> -->

<?php query_posts('event-type=upcoming&event-category=tour'); ?><!--Only get posts in the 'upcoming' taxonomy-->

<?php while ( have_posts() ) : the_post(); ?>
<div class ="post"><!--Begin Post -->
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

<div class ="past-get">
	<?php query_posts('event-type=upcoming&event-category=tour'); ?><!--Only get posts in the 'upcoming' taxonomy-->

	<?php while ( have_posts() ) : the_post(); ?>
	<div class ="post"><!--Begin Post -->
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