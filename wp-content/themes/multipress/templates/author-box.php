<?php
    if( is_author() || is_single() || is_page() ){
        if( is_single() || is_page() ){
            $resources = _core::method( '_resources' , 'get' );
            $customID = _attachment::getCustomIDByPostID( $post -> ID );
            if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] == 'yes' ){
                if( _core::method( '_meta' , 'logic' , $post -> ID , 'posts-settings' , 'author-box' ) ){
                    $author_box = true;
                }else{
                    $author_box = false;
                }
            }else{
                $author_box = false;
            }
        }else{
            $author_box = true;
        }
    }else{
        $author_box = false;
    }
    
    if( $author_box ){
?>
        <aside class="widget">
            <h4 class="widget-title"><?php _e( 'By' , _DEV_ ); ?> 
                <a href="<?php echo get_author_posts_url( $post-> post_author ) ?>" title="<?php echo esc_attr( get_the_author_meta( 'display_name' , $post-> post_author ) ); ?>" rel="me">
                    <?php echo get_the_author_meta( 'display_name' , $post-> post_author ); ?>
                </a>
            </h4>
            <p class="delimiter">&nbsp;</p>
            <div class="box-author clearfix">
                <p>
                    <a href="<?php echo get_author_posts_url( $post -> post_author) ?>"><?php echo cosmo_avatar( $post -> post_author , $size = '50', DEFAULT_AVATAR );  ?></a>
                    <?php
                        $author_bio = get_the_author_meta( 'description' , $post -> post_author );

                        if( $author_bio != '' ){
                            echo '<span class="author-page">' . $author_bio . '</span>';
                        }
                    ?>
                </p>
            </div>
        </aside>
<?php
    }
?>