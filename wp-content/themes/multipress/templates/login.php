
<?php if( _core::method( "_settings" , "logic" , "settings" , "general" , "theme" , "enb-login" ) ){?>
	<div class="login-form b w_130">
		<div class="fr">
            <?php
                if( is_user_logged_in() ){
                    $u_id = get_current_user_id();
                    $picture = _core::method( '_facebook' , 'picture' );
                    $cusom_avatar = wp_get_attachment_image_src( get_user_meta( $u_id , 'custom_avatar' , true ) , array( 24 , 24 ) );
                            
                    if( strlen( $picture ) && ( !isset( $cusom_avatar[0] ) || ( isset( $cusom_avatar[0] ) && empty(  $cusom_avatar[0] ) ) ) ){
                        ?><a href="http://facebook.com/profile.php?id=<?php echo _core::method( '_facebook' , 'id' ); ?>" class="profile-pic simplemodal-login simplemodal-none"><img src="<?php echo $picture; ?>" width="24" width="24" /></a><?php
                    }else{
                        ?><a href="#" class="profile-pic simplemodal-login simplemodal-none"><?php echo cosmo_avatar( get_current_user_id() , 24 , DEFAULT_AVATAR );?></a><?php
                    }
                }else{
                    ?><a href="#" class="profile-pic simplemodal-login simplemodal-none"><?php echo cosmo_avatar( get_current_user_id() , 24 , DEFAULT_AVATAR );?></a><?php
                }
            ?>
			<div class="cosmo-icons">
				<ul class="sf-menu">
					<li class="signin dropdown">
						<?php if(is_user_logged_in()){
								$user=wp_get_current_user();?>
							<a class="dynamic-settings-style-menu-top_menu" href=""><?php echo $user->user_login;?></a>
							<ul>
								<?php $cart_page=_core::method("_settings","get","settings", "payment", "paypal", "cart_page");
									if(is_numeric($cart_page)){?>
										<li class="my-cart"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($cart_page)?>">My cart</a></li>
									<?php } ?>

								<?php $my_payments=_core::method("_settings","get","settings", "payment", "check_transactions", "my_payments");
									if(is_numeric($my_payments)){?>
										<li class="my-payments"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($my_payments)?>">My payments</a></li>
									<?php } ?>

								<?php $sold_items=_core::method("_settings","get","settings", "payment", "check_transactions", "sold_items");
									if(is_numeric($sold_items)){?>
										<li class="my-sold-items"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($sold_items)?>">Sold items</a></li>
									<?php } ?>

								<?php $settings_page=_core::method("_settings","get","settings","general","theme","settings-page");
									if(is_numeric($settings_page)){?>
										<li class="my-settings"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($settings_page)?>">My settings</a></li>
									<?php } ?>

								<li class="my-profile"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_author_posts_url( get_current_user_id() ); ?>">My profile</a></li>

								<?php $my_posts=_core::method("_settings","get","settings","general","theme","post-page");
									if(is_numeric($my_posts)){?>
										<li class="my-posts"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($my_posts)?>">My added posts</a></li>
									<?php } ?>

								<?php $my_likes=_core::method("_settings","get","settings","general","theme","my-likes-page");
									if(is_numeric($my_likes)){?>
										<li class="my-likes"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($my_likes)?>">My voted posts</a></li>
									<?php } ?>

								<?php $post_item_page=_core::method("_settings","get","settings","general","upload","post_item_page");
									if(is_numeric($post_item_page)){?>
										<li class="my-add"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($post_item_page)?>">Add post</a></li>
									<?php } ?>
								
									<li class="my-logout"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo wp_logout_url( home_url() ); ?>">Log out</a></li>
							</ul>
						<?php }else{ 
								$login_page=_core::method('_settings','get','settings','general','theme','login-page');?>
							<li class="my-logout"><a class="dynamic-settings-style-menu-top_menu" href="<?php echo get_permalink($login_page);?>">Sign in</a></li>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php } ?>