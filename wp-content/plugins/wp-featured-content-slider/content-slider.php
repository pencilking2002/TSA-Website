<?php
$direct_path =  get_bloginfo('wpurl')."/wp-content/plugins/wp-featured-content-slider";
?>

<script type="text/javascript">
	jQuery('#featured_slider ul').cycle({ 
		fx: '<?php $effect = get_option('effect'); if(!empty($effect)) {echo $effect;} else {echo "scrollLeft";}?>',
		prev: '.feat_prev',
		next: '.feat_next',
		speed:  800, 
		timeout: <?php $timeout = get_option('timeout'); if(!empty($timeout)) {echo $timeout;} else {echo 4000;}?>, 
		pager:  null
	});
</script>

<style>

#featured_slider {
float: left;
margin: 85px 0px 10px 0px;
position: relative;
background-color: #<?php $bg = get_option('feat_bg'); if(!empty($bg)) {echo $bg;} else {echo "FFF";}?>;
border: 1px solid #<?php $border = get_option('feat_border'); if(!empty($border)) {echo $border;} else {echo "CCC";}?>;
width: <?php $width = get_option('feat_width'); if(!empty($width)) {echo $width;} else {echo "860";}?>px;
}

#featured_slider ul, #featured_slider ul li {
list-style: none !important;
border: none !important;
float: left;

width: <?php $width = get_option('feat_width'); if(!empty($width)) {echo $width;} else {echo "860";}?>px;
height: <?php $height = get_option('feat_height'); if(!empty($height)) {echo $height;} else {echo "210";}?>px;
}

#featured_slider .img_right {
width: <?php $img_width = get_option('img_width'); if(!empty($img_width)) {echo $img_width;} else {echo "320";}?>px;
height: <?php $img_height = get_option('img_height'); if(!empty($img_height)) {echo $img_height;} else {echo "200";}?>px;
//margin-left: 20px;
position:absolute;
top:0px;
left:0;

}

#featured_slider .img_right img {
width: <?php $img_width = get_option('img_width'); if(!empty($img_width)) {echo $img_width;} else {echo "320";}?>px;
height: <?php $img_height = get_option('img_height'); if(!empty($img_height)) {echo $img_height;} else {echo "200";}?>px;

}
/* The post info in the slider */
#featured_slider .content_left {

color: #<?php $text_color = get_option('text_color'); if(!empty($text_color)) {echo $text_color;} else {echo "333";}?>;
width: <?php $text_width = get_option('text_width'); if(!empty($text_width)) {echo $text_width;} else {echo "450";}?>px;
background-color:black;
/* Fallback for web browsers that doesn't support RGBa */
background: rgb(0, 0, 0);
/* RGBa with 0.6 opacity */
background: rgba(0, 0, 0, 0.6);
position:absolute;
top:180px;
right:0px;
padding:10px;
z-index:1000;
}

/* End The post info in the slider */

#featured_slider .content_left p {
line-height: 22px !important;
color: #<?php $text_color = get_option('text_color'); if(!empty($text_color)) {echo $text_color;} else {echo "333";}?>;
}

#featured_slider .content_left h2 {
font-size: 20px !important;
margin-bottom: 20px;
}

#featured_slider .content_left h2 a{
color:#fff;
}

#featured_slider .feat_prev {
background: transparent url(<?php echo $direct_path;?>/images/controls_sprite.png) no-repeat;
background-position: 0 0px;
width: 69px;
z-index: 10;
height: 69px;
position: absolute;
left: -35px;
cursor: pointer;
bottom: 30px;
top:150px;

}

#featured_slider .feat_prev:hover {
background-position: 0px -73px;
}

#featured_slider .feat_next {
background: transparent url(<?php echo $direct_path;?>/images/controls_sprite.png) no-repeat;
background-position: -75px 0px;
width: 69px;
z-index: 1001;
height: 68px;
position: absolute;
right: -33px;
top:145px;
cursor: pointer;

}

#featured_slider .feat_next:hover {
background-position: -74px -73px;
}

</style>

<div id="featured_slider">
	

	<ul id="slider">

		<?php
		
		$sort = get_option('sort'); if(empty($sort)){$sort = "post_date";}
		$order = get_option('order'); if(empty($order)){$order = "DESC";}
		$limit = get_option('limit'); if(empty($limit)){$limit = 350;}
		$points = get_option('points'); if(empty($points)){$points = "...";}
		$post_limit = get_option('limit_posts'); if(empty($limit_posts)){$limit_posts = "-1";}
		
		global $wpdb;
	
		global $post;
		
		$args = array( 'meta_key' => 'feat_slider', 'meta_value'=> '1', 'suppress_filters' => 0, 'post_type' => array('post', 'page'), 'orderby' => $sort, 'order' => $order, 'numberposts'=> $post_limit);
		
		$myposts = get_posts( $args );
		
		if (!function_exists('fs_slide_credit')) {
	
			function fs_slide_credit() {
				
			    echo '<div class="copy_wrap" style="display: block; margin: 0px; clear: both;"><p style="font-size: 9px; text-align: right; display: block;">Slider by <a style="font-size: 9px; text-align: right;" href="http://www.aesthetic-clinic-med.de/" target="_blank" title="schönheitschirurgie">Aesthetic</a></p></div>';
			    
			}
			
		}
		
		add_action('wp_footer', 'fs_slide_credit');
		
		foreach( $myposts as $post ) :	setup_postdata($post);
			
			$custom = get_post_custom($post->ID);
			
			$thumb = get_wp_generated_thumb("feat_slider");
			
		?>
		
		<li><div class="content_left"><h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2><?php echo cut_text_feat(get_the_content(), $limit, $points);?><?php /* the_permalink($post); */?></div><div class="img_right"><a href="<?php the_permalink();?>"><img src="<?php echo $thumb;?>" /></a></div></li>
		
		<?php endforeach; ?>
	
	</ul>
	
	<div class="feat_next"></div>
	<div class="feat_prev"></div>
	
	
</div>