<?php
    /* blog on front page */
    if( ( _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'type' ) == 'selected-page' ) &&  ( (int)_core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'page' ) === (int)get_option( 'page_for_posts' ) ) ){
        global $wp_query;
        $wp_query = new WP_Query( array( 'post_type' => 'post' , 'post_status' => 'publish' ) );
        get_template_part( 'index' );
        exit();
    }
    
    /* list custom posts type */
    if( isset( $_GET[ 'fp_type' ] ) ){
        global $wp_query;
        $wp_query = new WP_Query( array( 'post_type' => $_GET[ 'fp_type' ] , 'post_status' => 'publish' ) );
        get_template_part( 'index' );
        exit();
    }
    
    global $wp_query;
    
    get_header();
?>

<div class="b_content clearfix" id="main">
    <?php
        $slideshow = _core::method( '_meta' , 'get' , _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'item' ) , 'slideshow' );
        if( _core::method( '_slideshow' , 'exists_slideshow' )  && !empty( $slideshow ) && is_array( $slideshow ) ){
            /* mainpage top sidebar */
            ob_start();
            ob_clean();
            get_sidebar( 'whitey' );
            $top = ob_get_clean();
            if( strlen( $top ) ){
                ?>	
                    <div class="whitey">
                        <div class="b_page">
                            <?php echo $top; ?>
                        </div>
                    </div>
                <?php
            }
        }
	?>
	<div class="slide-panel" id="slidePanel">
	</div>
    
    <div class="b_page clearfix">
        <?php
            /* front page type resource */
            $type = _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'type' );
            
            if( $type != 'widgets' ){
                /* no widgets type front-page */
                
                switch( $type ){
                    case 'latest-post' : {
                        $query = new WP_Query( 
                            array(
                                'posts_per_page' => 1,
                                'orderby' => 'post_date',
                                'post_type' => 'post',
                                'post_status' => 'publish'
                            )
                        );
                        break;
                    }
                    case 'selected-post' : {
                        $postID = _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'post' );
                        $resources = _core::method( '_resources' , 'get' );
                        $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $postID );
                        if( isset( $resources[ $customID ] ) ){
                            $query = new WP_Query(
                                array(
                                    'p' => $postID , 'post_type' => $resources[ $customID ][ 'slug' ]
                                )
                            );
                        }
                        break;
                    }
                    case 'selected-page' : {
						$query = new WP_Query(
                            array(
                                'page_id' =>  _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'page' )
                            )
                        );
                        break;
                    }
                }
                
                if(  isset( $query ) && count( $query -> posts ) > 0 ){
                    foreach( $query -> posts as $post ){
                        $query -> the_post();
                        
                        /* latest post title */
                        ?>
                            <div class="content-title">
                                <div class="title">
                                    <h1 class="entry-title">
                                        <span><?php the_title(); ?></span>
                                    </h1>
                                    
                                    <?php
                                        if( $type != 'selected-page' ){
                                            /* hotkeys-meta */
                                            get_template_part( '/templates/single/meta/hotkeys' );
                                        }
                                    ?>
                                </div>
                            </div> 
                        <?php

                            /* left-sidebar */
                            _core::method( '_layout' , 'aside' , 'left' , 0 , 'front_page' );
                        ?>
                            <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'front_page' ); ?> >
                                <div id="content" role="main">
                                    <div <?php _core::method( '_layout' , 'content_class' ,0 , 'front_page', true, 'single' )?>>
                                        <article id="post-<?php echo $post -> ID; ?>" <?php post_class(); ?>>
                                            <?php
                                                if( $type != 'selected-page' ){
                                                    /* for posts */
                                                    if( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'posts' , 'enb-featured' ) && ( has_post_thumbnail() || ( get_post_format( $post -> ID ) == 'video' ) ) ){
                                            ?>
                                                        <header class="entry-header">
                                                            <div class="featimg">
                                                                <?php $border = _core::method( "_settings" , "logic" , "settings" , "blogging" , "posts" , "enb-featured-border" ); ?>
                                                                <div class="img <?php if( !$border ) echo "noborder"; ?>">
                                                                    <?php 
                                                                        if( get_post_format( $post -> ID ) == 'video' ){

                                                                            $video_format = _core::method( '_meta' , 'get' , $post -> ID , 'format' );

                                                                            if( strlen( $video_format[ "feat_url" ] ) > 1 ){
                                                                                $video_url = $video_format[ "feat_url" ];
                                                                                $youtube_id = _core::method( 'post' , 'get_youtube_video_id' , $video_url );
                                                                                $vimeo_id= _core::method( 'post' , 'get_vimeo_video_id' , $video_url );
                                                                                if(  strlen( $youtube_id ) ){
                                                                                    echo _core::method( 'post' , 'get_embeded_video' , $youtube_id , "youtube" );
                                                                                }else if( strlen( $vimeo_id ) ){
                                                                                    echo _core::method( 'post' , 'get_embeded_video' , $vimeo_id , "vimeo" );
                                                                                }
                                                                            }else if( is_numeric( $video_format[ "feat_id" ] ) ){
                                                                                echo _core::method( 'post' , 'get_local_video' , urlencode( wp_get_attachment_url( $video_format[ "feat_id" ] ) ) );
                                                                            }
                                                                        }else if( has_post_thumbnail() ){
                                                                            if( $border ){
                                                                                echo _core::method( '_image' , 'thumbnail' , $post -> ID , _layout::$size[ 'image' ][ _layout::length( 0 , 'front_page' ) ] ); 
                                                                            }elseif(_core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , 'front_page' ) == "full" ){
                                                                                echo wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , array( 930 , 9999 ) );
                                                                            }else{
                                                                                echo wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , array( 610 , 9999 ) );
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </header>
                                            <?php
                                                    }
                                                }else{
                                                    /* selected page */
                                                    if( _core::method(  '_settings' , 'logic' , 'settings' , 'blogging' , 'pages' , 'enb-featured' ) ){
                                                        if ( has_post_thumbnail( $post -> ID ) ) {
                                                            $src 		= _core::method( '_image' , 'thumbnail' , $post -> ID , 'page' , _layout::$size[ 'image' ][ _core::method( '_layout' , 'length' , $postID , 'page' ) ] );
                                                            $src_       = _core::method( '_image' , 'thumbnail' , $post -> ID , 'page' , 'full' );
                                                            $caption    = _core::method( '_image' , 'caption' , $post -> ID );
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
                                                    }
                                                }
                                                
                                                $position = _core::method( '_meta' , 'logic' , $post -> ID , 'posts-settings' , 'meta-type' );

                                                if( $position ){
                                                    $classes = 'horizontal';
                                                }else{
                                                    $classes = 'vertical';
                                                }
                                            ?>

                                            <div class="entry-content <?php echo $classes; ?>">
                                                <?php
                                                    /* single meta */
                                                    $resources = _core::method( '_resources' , 'get' );
                                                    $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $post -> ID ) ;
                                                    if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) && 
                                                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'yes' ){

                                                        if( _core::method( '_meta' , 'logic' , $post -> ID , 'posts-settings' , 'meta' ) ){
                                                            if( $position ){
                                                                get_template_part( '/templates/single/meta/horizontal' );
                                                            }else{
                                                                get_template_part( 'templates/single/meta/vertical' );
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <div class="b_text">
                                                    <?php
                                                        if( $type != 'selected-page' ){
                                                            if( get_post_format( $post -> ID ) == 'link' ){
                                                                echo _core::method( 'post' , 'get_attached_files' , $post -> ID );
                                                            }else if( get_post_format( $post -> ID ) == 'audio' ){
                                                                $audio = new AudioPlayer();	
                                                                echo $audio -> processContent( _core::method( 'post' , 'get_audio_files' , $post -> ID ) );
                                                            }else if( get_post_format( $post -> ID ) == 'video' ){
                                                                if( isset( $video_format[ 'video_ids' ] ) && !empty( $video_format[ 'video_ids' ] ) ){
                                                                    foreach( $video_format[ "video_ids" ] as $videoid ){
                                                                    if( isset( $video_format[ "video_urls" ] ) && is_array( $video_format[ "video_urls" ] ) && isset( $video_format[ "video_urls" ][ $videoid ] ) ){
                                                                            $video_url = $video_format[ "video_urls" ][ $videoid ];
                                                                            $youtube_id = _core::method( 'post' , 'get_youtube_video_id' , $video_url );
                                                                            $vimeo_id= _core::method( 'post' , 'get_vimeo_video_id' , $video_url );
                                                                            if(  strlen( $youtube_id ) ){
                                                                                echo _core::method( 'post' , 'get_embeded_video' , $youtube_id , "youtube" );
                                                                            }else if( strlen( $vimeo_id ) ){
                                                                                echo _core::method( 'post' , 'get_embeded_video' , $vimeo_id , "vimeo" );
                                                                            }
                                                                        }else{
                                                                            echo _core::method( 'post' , 'get_local_video' , urlencode( wp_get_attachment_url( $videoid ) ) );
                                                                        }
                                                                    }
                                                                }
                                                            }else if( get_post_format( $post -> ID ) == "image" ){
                                                                $image_format = _core::method( '_meta' , 'get' , $post -> ID , 'format' );
                                                                echo '<div class="attached_imgs_gallery">';
                                                                if( isset( $image_format[ 'images' ] ) && is_array( $image_format[ 'images' ] ) ){
                                                                    foreach( $image_format[ 'images' ] as $index => $img_id ){
                                                                        $thumbnail = wp_get_attachment_image_src( $img_id , 'thumbnail' );
                                                                        $full_image = wp_get_attachment_url( $img_id );
                                                                        $url = $thumbnail[ 0 ];
                                                                        $width = $thumbnail[ 1 ];
                                                                        $height = $thumbnail[ 2 ];
                                                                        echo '<div class="attached_imgs_gallery-element">';
                                                                        echo '<a title="" rel="prettyPhoto-' . $post -> ID . '" href="' . $full_image . '">';

                                                                        if( $height < 150 ){
                                                                            $vertical_align_style = 'style="margin-top:' . ( ( 150 - $height ) / 2 ) . 'px;"';
                                                                        }else{
                                                                            $vertical_align_style = "";
                                                                        }

                                                                        echo '<img alt="" src="' . $url . '" width="' . $width . '" height="' . $height . '" ' . $vertical_align_style . '>';
                                                                        echo '</a>';
                                                                        echo '</div>';
                                                                    }
                                                                    echo '</div>';
                                                                }
                                                            }
                                                        }
                                                        
                                                        /* content */
                                                        the_content();

                                                        /* additional info */
                                                        $additional = _core::method( '_meta' , 'get' , $post -> ID , 'additional' );
                                                        $resources  = _core::method( '_resources' , 'get' );
                                                        $resource = $resources[ _attachment::getCustomIDByPostID( $post -> ID ) ];

                                                        if(  !empty( $additional ) && is_array( $additional ) && !empty( $resource[ 'boxes' ][ 'additional' ] ) ){
                                                            ?>
                                                                <table class="additional-info">
                                                                    <tbody>
                                                                        <?php
                                                                            $i = 0;
                                                                            foreach( $resource[ 'boxes' ][ 'additional' ] as $set => $field ){
                                                                                $i++;
                                                                                ?>
                                                                                    <tr class="row_<?php echo $i; ?>">
                                                                                        <td class="td_1_<?php echo $i; ?>"><?php echo $field[ 'label' ]; ?></td>
                                                                                        <td class="td_2_<?php echo $i; ?>"><?php echo $additional[ $set ]; ?></td>
                                                                                    </tr>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                            <?php
                                                if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] ) &&  $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] == 'yes' ){
                                                    $source = _core::method( '_meta' , 'get' , $post -> ID , 'posts-settings' , 'source' );

                                                    if( !empty( $source ) ){
                                                        $source_ = '<div class="source no_source"><p>' . __( 'Source' , _DEV_ ) . ' : <a href="' . $source . '" target="_blank">' . $source . '</a></p></div>';
                                                    }else{
                                                        $source_ = '<div class="source no_source"><p>' . __( 'Unknown source' , _DEV_ ) . '</p></div>';
                                                    }
                                                }

                                                if( isset( $resources[ $customID ][ 'boxes' ][ 'attachdocs' ] ) ){
                                                    $attachdocs = _core::method( '_meta' , 'get' , $post -> ID , 'attachdocs' );

                                                    if( !empty( $attachdocs ) ){
                                                        $attachdocs_  = '<table class="demo-download">';
                                                        $attachdocs_ .= '<tbody>';
                                                        foreach( $attachdocs as $doc ){
                                                            $attachdocs_ .= '<tr>';
                                                            $attachdocs_ .= '<td class="demo-link">';
                                                            $attachdocs_ .= '<p class="demo-link-title"><a href="' . $doc[ 'demo' ] .  '">' . __( 'Demo' , _DEV_ ) .  '</a></p>';
                                                            $attachdocs_ .= '</td>';

                                                            $attachdocs_ .= '<td class="attach">';
                                                            $attachdocs_ .= '<p class="attach-title"><a href="' . $doc[ 'url' ] .  '">' . __( 'Download' , _DEV_ ) .  '</a></p>';
                                                            $attachdocs_ .= '</td>';

                                                            $attachdocs_ .= '</tr>';
                                                        }
                                                        $attachdocs_ .= '</tbody>';
                                                        $attachdocs_ .= '</table>';
                                                    }
                                                }

                                                if( isset( $source_ ) || isset( $attachdocs_ ) ){
                                                    ?>
                                                        <footer class="entry-footer">
                                                            <?php
                                                                if( isset( $attachdocs_ ) ){
                                                                    echo $attachdocs_;
                                                                }

                                                                if( isset( $source_ ) ){
                                                                    echo $source_;
                                                                }
                                                            ?>
                                                        </footer>
                                                    <?php    
                                                }
                                            ?>
                                            
                                        </article>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                }else{
                    ?>
                        <div class="content-title">
                            <div class="title">
                                <h1 class="entry-title">
                                    <span><?php _e( 'Error 404, page, post or resource can not be found' , _DEV_ ); ?></span>
                                </h1>

                                <?php
                                    if( $type != 'selected-page' ){
                                        /* hotkeys-meta */
                                        get_template_part( '/templates/single/meta/hotkeys' );
                                    }
                                ?>
                            </div>
                        </div> 
                    <?php
                        /* left-sidebar */
                        _core::method( '_layout' , 'aside' , 'left' , 0 , 'front_page' );
                    ?>          
                        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'front_page' ); ?>>
                            <div id="content" role="main">
                                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'front_page' ); ?>>
                                    <div class="loop-container-view list">
                                        <?php /*  posts loop articles */ ?>
                                        <div class="element last">
                                            <p>
                                                <?php get_template_part( 'loop' , '404' ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            }else{
                /* widgets type front-page ( mainpage content sidebars ) */
                ?>
                    <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'front_page' ); ?> >
                        <div id="content" role="main">
                            <?php get_sidebar( 'front' ); ?>
                        </div>
                    </div>
                <?php
            }
            
            /* right-sidebar */
            _core::method( '_layout' , 'aside' , 'right' , 0 , 'front_page' );
        ?>
    </div>
    <?php
    
        /* mainpage bottom sidebar */
        ob_start();
        ob_clean();
        get_sidebar( 'mainpage-footer' );
        $bottom = ob_get_clean();

        if( strlen( $bottom ) ){
            ?>
                <div class="mainpage-footer-widget">
                    <div class="b_page">
                        <?php echo $bottom; ?>
                    </div>
                </div>
            <?php
        }
    ?>
</div>
<?php get_footer(); ?>