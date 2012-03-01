<?php
	class _post {
		static $post_id = 0;
		
		function list_terms( $post_id , $taxonomy = 'post_tag'){
            $term_list = array();
            $terms = wp_get_post_terms( $post_id , $taxonomy );

            if ( sizeof( $terms ) ) { 
				foreach ( $terms as $term ) { 
					$term_list[ $term-> term_id ] = $term -> name;
				}
            }
            
            return $term_list;
        }
		
		function meta( $post ) {
            global $wp_query;
		
		?>
		
		<div class="entry-meta ">
			<?php //if(self::show_meta_author_box($post) && self::get_meta_view_style($post) == 'vertical') { 
				$role = array( 
									10 => __( 'Administrator' , _DEV_ ) ,
									7 => __( 'Editor' , _DEV_ ) , 
									2 => __( 'Author' , _DEV_ ) , 
									1 => __( 'Contributor' , _DEV_ ) , 
									0 => __( 'Subscriber' , _DEV_ ), 
									'' => __( 'Subscriber' , _DEV_ )
								); 
							
			?>  
			<div class="entry-author">
				<a href="<?php echo get_author_posts_url($post->post_author) ?>" class="profile-pic" ><?php echo cosmo_avatar( $post -> post_author , $size = '50', $default = DEFAULT_AVATAR );  ?></a>
				<?php _e('By',_DEV_); ?> 
				<a href="<?php echo get_author_posts_url($post->post_author) ?>">
					<?php echo get_the_author_meta('display_name', $post->post_author); ?>
					<span><?php echo $role[ get_the_author_meta( 'user_level' , $post->post_author ) ]; ?></span>
				</a>
			</div>
			<?php //} ?>	
			
			<ul>
				<?php edit_post_link( __( 'Edit', _DEV_ ), '<li class="edit_post" title="' . __( 'Edit' , _DEV_ ) . '">', '</li>' ); ?>
				
				<li class="time">
					<a href="<?php echo get_permalink($post->ID); ?>">
						<time>
							<?php
							if( _core::method("_settings","logic","settings", "general", "theme", "time") ) {
								echo human_time_diff(get_the_time('U', $post->ID), current_time('timestamp')) . ' ' . __('ago', _DEV_);
							} else {
								echo date_i18n(get_option('date_format'), get_the_time('U', $post->ID));
							}
							?>
						</time>
					</a>
				</li>
				<?php
					$resources = _core::method( '_resources' , 'get' );
					$customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $post -> ID );
					$use_likes = false; 

					if(_core::method("_settings","logic","settings","blogging","likes","use"))
						{
							if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] )&& $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] == 'yes' ){
								$use_likes = true;
							}else if( _core::method( '_meta' , 'logic' , $post->ID , 'posts-settings' , 'likes' ) ){
								$use_likes = true;
							}
						}

					if(!$use_likes)
						{
							if (comments_open($post->ID)) {
								$comments_label = __('comments',_DEV_);  
								if( _core::method("_settings","logic","settings", "general", "general_settings", "fb_comments") ) {
								
									?><li class="cosmo-comments" title=""><a href="<?php echo get_comments_link($post->ID); ?>"> <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count> <?php //if(self::get_meta_view_style($post) == 'vertical'){ echo $comments_label; } ?> </a></li><?php
								} else {
									
									if(get_comments_number($post->ID) == 1){
										$comments_label = __('comment',_DEV_);
									}
									?><li class="cosmo-comments" title="<?php echo get_comments_number($post->ID); echo ' '.$comments_label; ?>"><a href="<?php echo get_comments_link($post->ID) ?>"> <?php echo get_comments_number($post->ID) ?> <?php //if(self::get_meta_view_style($post) == 'vertical'){ echo $comments_label; } ?> </a></li><?php
								}
							}
						}else{
							echo _core::method( '_likes' , 'contentLike' , $post -> ID, false );
							echo _core::method( '_likes' , 'contentHate' , $post -> ID, false );
						}
				?>
			</ul>
			<?php
				$post_tags = _core::method( '_post' , 'list_terms' , $post->ID , 'post_tag'  );
				
				if(sizeof($post_tags)){
			?>
				<ul class="b_tag">
			<?php	
					foreach($post_tags as $key => $tag){
						$tag_link = get_tag_link($key);
			?>
						<li><a href="<?php echo $tag_link; ?>" rel="tags"><?php echo $tag; ?></a></li>			
			<?php	
					}
			?>
				</ul>
			<?php	
				}
			?>
			
			<?php if(sizeof(_core::method( '_post' , 'list_terms' , $post->ID , 'category'  ))){ ?>
			<ul class="category">
				<?php
					$post_categories = _core::method( '_post' , 'list_terms' , $post->ID , 'category'  );
					foreach($post_categories as $key => $categ){
						$categ_link = get_category_link($key);
				?>
						<li><a href="<?php echo $categ_link; ?>" ><?php echo $categ; ?></a></li>			
				<?php	
					}
				?>
			</ul>
			<?php } ?>
			
		</div>
		<?php
		}
	}
	
	

?>