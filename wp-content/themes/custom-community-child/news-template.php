<?php 

/*

	Template Name: news

*/ 

?>

<?php get_header() ?>

	<div id="content">
		<div class="padder">
       		<h3>Textile Society News<h3>
	   			<?php query_posts('post_type=news') ?>
				<?php if ( have_posts() ) : ?>
					<?php while (have_posts()) : the_post(); ?>
						
                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="post-content">
							<span class="marker"></span>
							<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'cc' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							
							<p class="date"><?php the_time('F j, Y') ?> <em><?php _e( 'in', 'cc' ) ?> <?php the_category(', ') ?><?php if(defined('BP_VERSION')){  printf( __( ' by %s', 'cc' ), bp_core_get_userlink( $post->post_author ) );}?></em></p>

							<div class="entry">
								<?php do_action('blog_post_entry')?>
							</div>
							<?php $tags = get_the_tags();  ?>
							<p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'cc' ), ', ', '<br />'); ?></span></p>
							
						</div>
						
					</div>
                    
	<?php endwhile; ?>
 <?php endif; ?>
			
			

		</div><!-- .padder -->
       <div class="v_line v_line_right"></div>
        <?php dynamic_sidebar('roman'); ?>
	</div><!-- #content -->
	
<?php get_footer(); ?>
