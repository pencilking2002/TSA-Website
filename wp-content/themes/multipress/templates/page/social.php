<?php
    $resources = _core::method( '_resources' , 'get' );
    $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $post -> ID );
    
    $social = false;
    
    if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] == 'yes' ){
        if( _core::method( "_meta" , "logic" , $post -> ID , 'posts-settings' , 'social' ) ){
            $social = true;
        }
    }
    
    if( $social ){
        if( !_core::method( "_meta" , "logic" , $post -> ID , 'posts-settings' , 'social-position' ) ){
            $align = 'left';
        }else{
            $align = 'right';
        }
?>
        <div id="share_buttons_wrapper" class="<?php echo $align; ?>">
            <div id="share_buttons_single_page" class="share_buttons_single_page">
                <div class="cosmo-sharing">
                    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=box_count&amp;show_faces=true&amp;&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" height="70" width="45"></iframe>
                    <p><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink( $post -> ID ); ?>" data-text="<?php echo $post -> post_title; ?>" data-count="vertical"><?php _e( 'Tweet' , _DEV_ ); ?></a></p>
                    <g:plusone size="tall"  href="<?php echo get_permalink( $post -> ID ); ?>"></g:plusone>
                    <?php _core::hook( 'single-social' ); ?>
                </div>
            </div>
        </div>
<?php
    }
?>