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
    
    $use_payment = false;
	$price=_core::method( '_meta' , 'get' , $post -> ID , 'register' , 'value' );
    if( _core::method( '_meta' , 'logic' , $post -> ID , 'register' , 'enable' ) && is_numeric($price)){
        $use_payment = _core::method( '_meta' , 'logic' , $post -> ID , 'register' , 'use' );
        $price = _core::method( '_meta' , 'get' , $post -> ID , 'register' , 'value' );
        $currency=_core::method('_settings','get', 'settings' , 'payment' , 'paypal' , 'currency');
	}
?>

<div class="entry-meta">
    <?php
        if( $use_payment ){
    ?>
                <ul>
                    <li class="basket">
                        <?php echo _core::method( '_cart' , 'get_btn' , $post -> ID ); ?>
                    </li>
                </ul>
                <ul class="fr">
                    <li class="basket"><?php echo ( ( $currency == "USD" ) ? "$" : $currency ) . " " . $price ?></li>															
                </ul>
    <?php
        }else{
    ?>
            <ul>
                <li class="time">
                    <a href="<?php echo get_permalink( $post -> ID ) ?>">
                        <time datetime="<?php get_the_time( 'U' , $post -> ID ) ?>">
                            <?php
                                if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'time' ) ) {
                                    echo human_time_diff( get_the_time( 'U' , $post -> ID ) , current_time( 'timestamp' ) ) . ' ' . __( 'ago' , _DEV_ );
                                } else {
                                    echo date_i18n( get_option( 'date_format' ) , get_the_time( 'U' , $post -> ID ) );
                                }
                            ?>
                        </time>
                    </a>
                </li>
            </ul>
            <ul class="fr">	
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
                ?>
            </ul>
    <?php   
        }
    ?>
</div>