<?php
    $use_payment = false;
	$price=_core::method( '_meta' , 'get' , $post -> ID , 'register' , 'value' );
    if( _core::method( '_meta' , 'logic' , $post -> ID , 'register' , 'enable' ) && is_numeric($price)){
		$use_payment = _core::method( '_meta' , 'logic' , $post->ID , 'register' , 'use');
		$price = _core::method( '_meta' , 'get' , $post->ID , 'register' , 'value' );
		$currency = _core::method( '_settings' , 'get' , 'settings' , 'payment' , 'paypal' , 'currency');
    }
?>
<?php if($use_payment){?>
  <div class="share">
	<?php echo _core::method( '_cart' , 'get_btn' , $post -> ID ); ?>
	<ul class="fr">
		<li class="basket"><?php echo ($currency=="USD"?"$":$currency)." ".$price?></li>															
	</ul>
  </div>
<?php }else{ ?>
  <div class="share">
	<p class="button">
	  <a href="<?php echo the_permalink()?>"><?php _e('continue reading',_DEV_) ?></a>
	</p>
  </div>
<?php } ?>

<?php
    /* social sharing  */
    if( is_front_page() && isset( $_GET[ 'fp_type' ] ) ){
        $customID = _core::method( '_resources' , 'getCustomIdByPostType' , $_GET[ 'fp_type' ] );
        $resources = _core::method( '_resources' , 'get' );
        
        if( isset( $resources[ $customID ][ 'boxes' ]['posts-settings'][ 'social-list-view' ] ) && $resources[ $customID ][ 'boxes' ]['posts-settings'][ 'social-list-view' ] == 'yes' ){
?>
            <div class="share">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink( $post -> ID ); ?>" data-text="<?php echo $post -> post_title; ?>" data-count="horizontal"><?php _e( 'Tweet' , _DEV_ ) ; ?></a>
                <g:plusone size="medium"  href="<?php echo get_permalink( $post -> ID ); ?>"></g:plusone>
                <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=button_count&amp;show_faces=false&amp;&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" height="20" width="109"></iframe>
            </div>
<?php
        }
    }else{
        if(!( is_archive() || is_author() || is_category() || is_search() || is_tag() ) && _core::method( "_meta" , "logic" , $post->ID , 'posts-settings' , 'social' ) ){
?>
            <div class="share">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink( $post -> ID ); ?>" data-text="<?php echo $post -> post_title; ?>" data-count="horizontal"><?php _e( 'Tweet' , _DEV_ ) ; ?></a>
                <g:plusone size="medium"  href="<?php echo get_permalink( $post -> ID ); ?>"></g:plusone>
                <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=button_count&amp;show_faces=false&amp;&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" height="20" width="109"></iframe>
            </div>
<?php
        }
    }
?>


<?php _core::hook( 'social' ); ?>

