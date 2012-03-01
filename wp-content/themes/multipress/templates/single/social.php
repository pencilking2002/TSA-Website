<div class="cosmo-sharing">
    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=box_count&amp;show_faces=true&amp;&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" height="70" width="45"></iframe>
    <p><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink( $post -> ID ); ?>" data-text="<?php echo $post -> post_title; ?>" data-count="vertical"><?php _e( 'Tweet' , _DEV_ ); ?></a></p>
    <g:plusone size="tall"  href="<?php echo get_permalink( $post -> ID ); ?>"></g:plusone>
    <?php _core::hook( 'single_social' ); ?>
</div>