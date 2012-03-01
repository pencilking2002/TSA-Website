<div class="entry-meta">
    
    <div class="entry-author">
        <a href="<?php echo get_author_posts_url( $post -> post_author ) ?>" class="profile-pic">
            <?php echo cosmo_avatar( $post -> post_author , 50 , DEFAULT_AVATAR ); ?>
        </a>
        <?php _e( 'By' , _DEV_ ) ?> 
        <a href="<?php echo get_author_posts_url( $post -> post_author ) ?>">
            <?php
                echo get_the_author_meta( 'display_name' , $post -> post_author );
                
                $user = (array)get_userdata( $post -> post_author );
                
                if( !isset( $user['roles'][0] ) ){
                    $user['roles'][0] = __( 'Subscriber' , _DEV_ );
                }
            ?>
            <span><?php echo $user['roles'][0]; ?></span>
        </a>
    </div>
    
    <ul>
        <?php
            if( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'upload' , 'enb_edit_delete' ) && 
                is_user_logged_in() && $post -> post_author == get_current_user_id() && 
                is_numeric( _core::method( '_settings' , 'get' , 'settings' , 'general' , 'upload' , 'post_item_page' ) ) ){
				$edit_link=get_page_link( _core::method( '_settings' , 'get' , 'settings' , 'general' , 'upload' , 'post_item_page' ) );
				if(strpos($edit_link,"?"))
					$edit_link.="&post=". $post -> ID;
				else $edit_link.="?post=". $post -> ID;
        ?> 
                <li class="edit_post" title="<?php _e('Edit post', _DEV_ ) ?>">
                    <a href="<?php  echo $edit_link;  ?>">
                        <?php echo _e( 'Edit' , _DEV_ ); ?>
                    </a>
                </li>
                <li class="delete_post" title="<?php _e( 'Remove post' , _DEV_ ); ?>">
                    <a href="javascript:void(0)" onclick="if( confirm( '<?php _e( 'Confirm to delete this post.' , _DEV_ ); ?>') ){ removePost( <?php echo $post -> ID ?> , '<?php echo home_url(); ?>' ); }">
                        <?php _e( 'Delete' , _DEV_ ); ?>
                    </a>
                </li>
        <?php
            }
            
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
        <li class="time">
            <a href="<?php echo get_permalink( $post -> ID ) ?>">
                <time datetime="2011-10-25">
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
        
        <?php
            if ( comments_open( $post -> ID ) ) {
                if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'fb_comments' ) ) {
                    ?>
                        <li class="cosmo-comments" title="">
                            <a href="<?php echo get_comments_link( $post -> ID ); ?>">
                                <span class="fb_comments_count">
                                    <fb:comments-count href="<?php echo get_permalink( $post -> ID ) ?>"></fb:comments-count>
                                </span> <?php _e( 'comments' , _DEV_ ); ?>
                            </a>
                        </li>
                    <?php
                }else{
                    ?>
                        <li class="cosmo-comments" title="">
                            <a href="<?php echo get_comments_link( $post -> ID ); ?>">
                                <?php
                                    if( get_comments_number( $post -> ID ) == 1 ){
                                        ?><span class="fb_comments_count"><?php echo get_comments_number( $post -> ID ); ?></span> <?php _e( 'comment' , _DEV_ );
                                    }else{
                                        ?><span class="fb_comments_count"><?php echo get_comments_number( $post -> ID ); ?></span> <?php _e( 'comments' , _DEV_ );
                                    }
                                ?>
                            </a>
                        </li>
                    <?php
                }
            }
        ?>
		<?php
			if($use_likes)
				{
					echo _core::method( '_likes' , 'contentLike' , $post -> ID , false);
					echo _core::method( '_likes' , 'contentHate' , $post -> ID , false);
				}
		?>
    </ul>
    <?php
        $result = '';
        $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $post -> ID );
        $resources = _core::method( '_resources' , 'get' );
        
        if(  $resources[ $customID ][ 'slug' ] == 'post' ){
            $resources[ $customID ][ 'taxonomy' ] = array(
                array(
                    'hierarchical' => 'hierarchical',
                    'slug' => 'category'
                ),
                array(
                    'hierarchical' => '',
                    'slug' => 'post_tag'
                )
            );
        }
        
        if( isset( $resources[ $customID ][ 'taxonomy' ] ) && !empty( $resources[ $customID ][ 'taxonomy' ] ) ){

            foreach( $resources[ $customID ][ 'taxonomy' ] as $taxonomy ){
                $terms = wp_get_post_terms( $post -> ID , $taxonomy[ 'slug' ] , array("fields" => "all") );
                
                if( $taxonomy[ 'hierarchical' ] == 'hierarchical'){
                    $classes = 'category';
                }else{
                    $classes = 'b_tag';
                }

                if( !empty( $terms ) ){
                    $result .=  '<ul class="' . $classes . '">';
                    
                    foreach( $terms as $term ){
                        if( isset( $term -> slug ) && isset( $term -> name  ) ){
                            $result .= '<li><a href="' . get_term_link( $term -> slug , $taxonomy[ 'slug' ] ) . '">' . $term -> slug . '</a></li>';
                        }
                    }
                    $result .= '</ul>';
                }
            }
        }
        
        echo $result;
        
        _core::hook( 'single-meta' );
    ?>
</div>