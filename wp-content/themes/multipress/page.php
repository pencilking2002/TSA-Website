<?php
if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "payment" , "paypal" , "cart_page" ) && isset( $_GET[ 'eco' ] ) ) {		
	/* express check out */ 
	get_template_part( '/lib/core/paypal/expresscheckout' ); 
	}elseif( isset( $_GET['tr_id'] ) ){
		/* if is set transaction id */
		/* for PDF docs, we need it before any output!!! */
		/* add here PDF generation page */
		get_template_part( '/lib/core/ticket' ); 
	}else{
		if( $post->ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "settings-page" ) ){
			get_template_part( 'user_profile_update' );
		}
		get_header();
		?>
		<div class="b_content clearfix" id="main">
			<div class="b_page">
				<?php
			while( have_posts () ){
				the_post();	
				$postID = $post -> ID;

				$img = false;

				if( _core::method(  '_settings' , 'logic' , 'settings' , 'blogging' , 'pages' , 'enb-featured' ) ){
					if ( has_post_thumbnail( $post -> ID ) ) {
						$src 		= _core::method( '_image' , 'thumbnail' , $post -> ID , 'page' , _layout::$size[ 'image' ][ _core::method( '_layout' , 'length' , $postID , 'page' ) ] );
						$src_       = _core::method( '_image' , 'thumbnail' , $post -> ID , 'page' , 'full' );
						$caption    = _core::method( '_image' , 'caption' , $post -> ID );
						$img        = true;
					}
				} 	
				?>		
				<div class="content-title">
					<div class="title">
						<h1 class="entry-title">
							<?php 	if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "login-page" ) ){
							if(isset($_GET['action']) && $_GET['action']=='register'){
								echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'page' , 'post_title' , 'text' , __( 'Registration' , _DEV_ ), 'span' );
								}elseif( isset($_GET['action']) && $_GET['action']=='recover' ){
									echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'page' , 'post_title' , 'text' , __( 'Recover password' , _DEV_ ), 'span' );
								}else{
									echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'page' , 'post_title' , 'text' , __( 'Login' , _DEV_ ), 'span' );
								}

							}else{
								echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'page' , 'post_title' , 'post_title' , $post , 'span'  ); 
							}
							?>
						</h1>
						<?php get_template_part( '/templates/single/meta/hotkeys' ); ?>
					</div>
				</div>

				<?php /* social sharing */ ?>
				<?php get_template_part( '/templates/page/social' ); ?>

				<?php /* left-sidebar */ ?>
				<?php 	if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "my-likes-page" ) ){
					_core::method( '_layout' , 'aside' , 'left' , 0 , 'author' ); 
			}else{
				_core::method( '_layout' , 'aside' , 'left' , $postID , 'page' ); 
				}?>
				<?php 
			$primary_template='page';
			$primary_post_ID=$postID;
			if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "my-likes-page" ) ){
				$primary_template='author';
				$primary_post_ID=0;
			}
			?>
			<div id="primary" <?php _core::method( '_layout' , 'primary_class' , $primary_post_ID , $primary_template ); ?>>
				<div id="content" role="main">
					<?php     
				if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "post-page" ) ){
					?>
					<div <?php _core::method( '_layout' , 'content_class' , $post -> ID , 'page', true, "list-view" );?>>
						<?php _core::method( "post" , "my_posts" , get_current_user_id() ); ?>
					</div>
					<?php 
				}else if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "my-likes-page" ) ){
					?>
					<div <?php _core::method( '_layout' , 'content_class' , 0 , 'author' );?>>
						<?php _core::method( "post" , "likes" ); ?>
					</div>
					<?php
				}elseif( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "upload" , "post_item_page" ) ){
					get_template_part( 'post_item' );
					}elseif($post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "login-page")){
						get_template_part( 'login' );
						}elseif($post->ID == _core::method("_settings","get","settings","general","theme","settings-page")){
							?>
							<div <?php _core::method( '_layout' , 'content_class' , $post -> ID , 'author' );?>>
								<?php get_template_part( 'user_profile' );?>
							</div>
							<?php
					}else{
						?>
						<div <?php _core::method( '_layout' , 'content_class' , $post -> ID , 'page' )?> >
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' , $postID ); ?>>
								<?php 
								if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "payment" , "paypal" , "cart_page" ) ) {
									get_template_part( 'shopping_cart' );
									}elseif( $post -> ID == _core::method( "_settings" , "get" , "settings" , "payment" , "check_transactions" , "my_payments" ) ){
										get_template_part( 'templates/my_payments' );
										}elseif( $post -> ID == _core::method( "_settings" , "get" , "settings" , "payment" , "check_transactions" , "sold_items" ) ){
											if(isset($_GET['tr_d'])){ /*if is set transaction details*/
												get_template_part( 'templates/transactions_details' ); 
											}else{	
												get_template_part( 'templates/sold_items' );	
											}	
											}elseif( $post -> ID == _core::method( "_settings" , "get" , "settings" , "payment" , "paypal" , "return_url") ){
												?><div id="shopping_cart_details"><?php
												echo _core::method( '_cart' , 'get_shopping_cart_details' );
												?></div><?php
												get_template_part( '/lib/core/paypal/order_review' );
											}else{
												if ( $img ) {
													?>
													<header class="entry-header">
														<div class="featimg">
															<div class="img">
																<?php echo $src; ?>
															</div>
														</div>
													</header>
													<?php
											}

											$position = _core::method( '_meta' , 'logic' , $postID , 'posts-settings' , 'meta-type' );

											if( $position ){
												$classes = 'horizontal';
											}else{
												$classes = 'vertical';
											}
											?>

											<div class="entry-content <?php echo $classes; ?>"><!--horizontal or vertical. In case of horizontal - delete div entry-author -->
												<?php
												$resources = _core::method( '_resources' , 'get' );
												$customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $postID );

												if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) && 
												$resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'yes' ){

													if( _core::method( '_meta' , 'logic' , $postID , 'posts-settings' , 'meta' ) ){
														if( $position ){
															get_template_part( '/templates/page/meta/horizontal' );
														}else{
															get_template_part( 'templates/page/meta/vertical' );
														}
													}
												}
												?>

												<div class="b_text">
													<?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'page' , 'post_text' , 'content' , $post, ''   ); ?>
												</div>
											</div>    
											<?php
										if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] ) && 
										$resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] == 'yes' ){
											$source = _core::method( '_meta' , 'get' , $postID , 'posts-settings' , 'source' );    
											if( !empty( $source ) ){
												?>
												<footer class="entry-footer">
													<div class="source no_source">
														<p><?php _e( 'Source' , _DEV_ )?> : <a href="<?php echo $source; ?>" target="_blank"><?php echo $source; ?></a></p>
													</div>
												</footer>
												<?php    
										}else{
											?>
											<footer class="entry-footer">
												<div class="source no_source">
													<p><?php _e( 'Unknown source' , _DEV_ ); ?></p>
												</div>
											</footer>
											<?php
									}
								}
								?>
								<?php
						} /*EOF if post_id == cart_page_id */ 
						?>
					</article>
					<p class="delimiter blank">&nbsp;</p>
					<?php
				/* Roman took away comments 
				if( comments_open() ){
					if( _core::method( "_settings" , "logic" , "settings" , "general" , "general_settings" , "fb_comments" ) ) {
						?>
						<div id="comments">
							<h3 id="reply-title"><?php _e( 'Leave a reply' , _DEV_ ); ?></h3>
							<p class="delimiter">&nbsp;</p>
							<fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="620" height="120" reverse="true"></fb:comments>
						</div>
						<?php
				}else{
					comments_template( '', true );
				}
			}
		*/	?>
		</div>
		<?php
}
?>	
</div>
</div>
<?php 
/* right-sidebar */ 
if( $post -> ID == _core::method( "_settings" , "get" , "settings" , "general" , "theme" , "my-likes-page" ) ){
	_core::method( '_layout' , 'aside' , 'right' , 0 , 'author' );
}else{
	_core::method( '_layout' , 'aside' , 'right' , $postID , 'page' );
}
}
?>
</div>
</div>
<?php 
get_footer();
}   /* EOF if(isset($_GET['doc'])) */ 
?>