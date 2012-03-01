<?php 

/*

	Template Name: home-template

*/ 

?>

<?php get_header() ?>

	<div id="content">
		<div class="padder">
       
			<div class ="home-news">
            	<h4>News:</h4>
                <?php query_posts("post_type=news&posts_per_page=1"); the_post(); ?>
                <span class ="home-news-content"><?php the_title(); ?> - "Put truncated news here"</span>
                
             </div>
			
			<div class ="home-module post">yo</div>
            <div class ="home-module post">yo</div>
            <div class ="home-module post">yo</div>

			
			

		</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_footer() ?>
