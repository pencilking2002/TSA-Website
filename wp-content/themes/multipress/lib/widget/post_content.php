<div class="content" >
<div class="b_page">

	<div class="content-title">
		<div class="title">
			<h1 class="entry-title">
				<span><?php the_title(); ?></span>
			</h1>
			<nav class="hotkeys-meta">
				<span class="nav-close"><a class="cosmo-qnews-close" href="javascript:void(0)" onclick="close_post();" title="Close" style="display: inline; ">close</a></span>
			</nav>
		</div>
	</div>				

	<div id="primary" class="w_930 fullwidth">
		<div id="content" role="main">
			<div class="w_930 single">
				<article id="post-<?php echo $_POST['post_id']; ?>" class="post-26 post type-post status-publish format-image hentry category-images tag-slideshow post">
					<header class="entry-header">
						<?php
							if(_core::method( '_image' , 'thumbnail_url' , $_POST['post_id'] , 'single_big'  )){
								$f_img = _core::method( '_image' , 'thumbnail_url' , $_POST['post_id'] , 'single_big',false  );
								$img_width = $f_img[1];
								$img_height = $f_img[2];
								
									/* for front page w/ sidebar */
									if($f_img[1] >= _image::$img_size['single_big'][0] && $f_img[2] >= _image::$img_size['single_big'][1]){
										$img_width = _image::$img_size['single_big'][0];
										$img_height = _image::$img_size['single_big'][1];
									}
									
								
								$img_src = _core::method( '_image' , 'thumbnail_url' , $_POST['post_id'] , 'single_big'  );
								$feat_img = '<img src="'.$img_src.'" alt="" width="'.$img_width.'" height="'.$img_height.'" />';
								
								
							
						?>
						<div class="featimg">
							<div class="img <?php if(!_core::method("_settings","logic","settings","blogging","posts","enb-featured-border")) echo "noborder";?>">
								<?php echo $feat_img; ?>
							</div>
						</div>
						<?php
						}	
						?>
					</header>
					<div class="entry-content vertical">
						<?php _core::method('_post','meta', $post)  ?>
						<div class="b_text">
							<?php the_content(); ?>
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>
</div>
</div>