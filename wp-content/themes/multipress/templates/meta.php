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
?>
<div class="entry-meta vertical">
    <ul>
        <li class="time">
			<a href="<?php echo get_permalink($post->ID); ?>">
				<time>
					<?php 	if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'time' ) ) {
								echo human_time_diff( get_the_time( 'U' , $post -> ID ) , current_time( 'timestamp' ) ) . ' ' . __( 'ago' , _DEV_ );
							} else {
								echo date_i18n( get_option( 'date_format' ) , get_the_time( 'U' , $post -> ID ) );
							}?>
				</time>
			</a>
		</li>
        <li class="author"><a href="<?php echo get_author_posts_url( $post->post_author ); ?>"><?php echo get_the_author_meta( 'display_name' , $post->post_author ); ?></a></li>
        <?php
            if( !$use_likes ){
                if ( comments_open( $post->ID ) ) {
                    if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'fb_comments' ) ) {
                        ?>
                            <li class="cosmo-comments" title="">
                                <a href="<?php echo get_comments_link( $post->ID ); ?>">
                                    <fb:comments-count href="<?php echo get_permalink( $post->ID ) ?>"></fb:comments-count>
                                </a>
                            </li>
                        <?php
                    }else{
                        ?>
                            <li class="cosmo-comments" title="">
                                <a href="<?php echo get_comments_link( $post->ID ); ?>">
                                    <?php
                                        if( get_comments_number( $post->ID ) == 1 ){
                                            echo get_comments_number( $post->ID );
                                        }else{
                                            echo get_comments_number( $post->ID );
                                        }
                                    ?>
                                </a>
                            </li>
                        <?php
                    }
                }
            }else{
				echo _core::method( '_likes' , 'contentLike' , $post -> ID );
                echo _core::method( '_likes' , 'contentHate' , $post -> ID );
            }
            _core::hook( 'inmeta' );
        ?>
    </ul>
    <?php _core::hook( 'meta' ); ?>	
</div>
