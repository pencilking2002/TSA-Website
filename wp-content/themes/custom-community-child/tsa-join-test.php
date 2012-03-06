<?php 

/*

	Template Name: tsa-join-test

*/ 

?>

<?php get_header() ?>

	<div id="content">
		<div class="padder">
       		<h3>Textile Society News<h3>
	   			<?php query_posts('page_id=39') ?>
				<?php if ( have_posts() ) : ?>
					<?php while (have_posts()) : the_post(); ?>
					<h2><?php the_title(); ?></h2>
					<p><?php the_field('publications'); ?></p>
					<?php echo do_shortcode('[s2Member-PayPal-Button level="1" ccaps="" desc="Bronze Member / description and pricing details here." 
					ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="localhost:8888" ta="2.00" tp="1" tt="D" ra="0.01" rp="1" rt="D" rr="0" rrt="" 
					rra="1" image="default" output="button" /]'); ?>
          


          
                        
                    
	<?php endwhile; ?>
 <?php endif; ?>
			
			

		</div><!-- .padder -->
       <div class="v_line v_line_right"></div>
        <?php dynamic_sidebar('roman'); ?>
	</div><!-- #content -->
	
<?php get_footer(); ?>
