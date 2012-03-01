
<?php get_header(); ?>

	<div class="b_content clearfix" id="main">

		<div class="b_page clearfix">

			<div class="b_text">

				<span class="dynamic-settings-style-single-post_text">

					<!--<h4 class="tabs_title">My Tabs</h4>-->

					<div class="cosmo-tabs default has_title" id="53">

						<ul class="tabs-nav"> 

							<li class="first tabs-selected"><a href="#t1382">
							
								<span>Upcoming Events</span></a>
							
							</li>

							<li class="last"><a href="#t977">
							
								<span>Past Events</span></a>
								
							</li>

						</ul><!--End tabs nav -->

						<div class="tabs-container" id="t1382">

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
							<h3><a href ="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<span><?php the_field('event_dates'); ?></span><br />
							</span><?php the_field('event_location'); ?></span><br />
							<p><?php the_content(); ?></p>
							</span><?php the_field('image_caption'); ?></span>
						</div>	
					</div><!--End Post -->
							<?php endwhile; ?>
						</div><!-- End b_text -->

						<div class="tabs-container" id="t977">
						
							<?php query_posts('event-type=past&event-category=tour'); ?><!--Only get posts in the 'past' taxonomy-->

							<?php while ( have_posts() ) : the_post(); ?>
							<div class ="post"><!--Begin Post -->
								<div class ="img-cap">
									<p><?php the_post_thumbnail(); ?></p>
									<div>
										</span><?php the_terms( $post->ID, 'event-category', '<span class="event-cat">','</span ><span class="event-cat">', '</span>' ); ?></span>
									</div>
								</div>

							<div class ="event-body"> 
								<h3><a href ="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<span><?php the_field('event_dates'); ?></span><br />
								</span><?php the_field('event_location'); ?></span><br />
								<p><?php the_content(); ?></p>
								</span><?php the_field('image_caption'); ?></span>
							</div>	
						</div><!--End Post -->
						
						<?php endwhile; ?>
						
						
							
								
						</div>

					</div>
				
						
						<?php get_sidebar(); ?>
					</span><!-- End dynamic-settings-style-single-post_text -->                                       
					 </div>
			</div>
			
		</div>

	</div><!-- End Main -->
	
	
	<?php get_footer(); ?>