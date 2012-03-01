<div id ="slideshow-container">
		
            <div id ="homeslider" class="pics">
            	<?php query_posts('category_name=events'); ?>
                <?php global $more;
					  $more = 0; // set $more to 0 in order to only get the first part of the post ?>
				<?php while ( have_posts() ) : the_post(); ?>
                <div class ="slider-posts">
                	
                    <div class ="slider-post-image">
						<?php if ( has_post_thumbnail() ) {
							 the_post_thumbnail( 'homepage-thumb' ); } ?>
                    </div>
                	<div class = "slider-post-text">
                    <h3><?php the_title(); ?></h3>
						<?php the_content(); ?>
                        <?php echo get_post_meta($post->ID, 'register', true); ?>
                        <?php the_field('register_button'); ?>
                    </div>
                    
                </div>
                  <?php endwhile; ?>
                  
            </div>
            
            <div class ="slider-controls">
            	<div class="nav"></div>
            	<div id="prev">prev</div>
            	<div id="next">next</div>
			</div>
		</div>
</html>
