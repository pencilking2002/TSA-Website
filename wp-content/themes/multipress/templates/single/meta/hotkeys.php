<?php
    $zoom = false;
    
    if( $post -> post_type == 'page' ){
        $template = 'page';
        $option   = 'pages';
    }else{
        $template = 'single';
        $option   = 'posts';
    }
    
    $hotkeys  = true;
    
    if( _core::method(  '_settings' , 'logic' , 'settings' , 'blogging' , $option , 'enb-featured' ) && _core::method(  '_settings' , 'logic' , 'settings' , 'blogging' , $option , 'enb-lightbox' ) ){
        if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
            $src        = _core::method( '_image' , 'thumbnail_src' , $post -> ID , $template , 'full' );
            $caption    = _core::method( '_image' , 'caption' , $post -> ID );
            $zoom       = true;
        }
    }
    
    if( $zoom ){
        $classes = '';
    }else{
        $classes = 'no-zoom';
    }
    
    if( is_page() ){
        if( !_core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'pages' , 'enb-next-prev' ) ){
            $hotkeys  = false; 
        }
    }
    
    if( $hotkeys || $zoom ){
?>
        <nav class="hotkeys-meta">
            <?php 
                if( $hotkeys ) {
                    ?><span class="nav-previous <?php echo $classes; ?>"><?php previous_post_link( '%link', 'Previous' ); ?></span><?php
                }
                if( $zoom  ){
                    ?><span class="nav-zoom"><a href="<?php echo $src[0]; ?>" title="<?php echo $caption;  ?>" rel="prettyPhoto-<?php echo $post -> ID; ?>"><?php _e( 'Full size' , _DEV_ ); ?></a></span><?php
                }
                
                if( $hotkeys ) {
                    ?><span class="nav-next"><?php next_post_link( '%link', 'Next' ); ?></span><?php
                }
            ?>        
            <?php _core::hook( 'hotkeys-meta' ); ?>
        </nav>
<?php
    }
?>