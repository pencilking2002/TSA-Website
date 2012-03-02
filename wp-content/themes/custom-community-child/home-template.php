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
                <span class ="home-news-content">
                  <h4><?php the_title(); ?></h4> - <?php echo content(10); ?><a href ="<?the_permalink(); ?>" alt="Latest Textile Society News">Read more</a>
                </span>
                
             </div>
			
			<div class ="home-module post">yo</div>
            <div class ="home-module post">yo</div>
            <div class ="home-module post">yo</div>

			</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_footer() ?>
