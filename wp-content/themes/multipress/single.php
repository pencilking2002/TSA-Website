<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page">
        
        <?php
            if( have_posts() ){
                while( have_posts() ){
                    the_post();
                    $postID = $post -> ID; 
		?>
                    <div class="content-title">
                        <div class="title">
                            <h1 class="entry-title">
								<?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'single' , 'post_title' , 'text' , get_the_title() , 'span' );?>
                            </h1>
                            
                            <?php /* hotkeys-meta */ ?>
                            <?php get_template_part( '/templates/single/meta/hotkeys' )?>
                        </div>
                    </div>    

                    <?php /* social sharing */ ?>
                    <?php get_template_part( '/templates/single/panel' ); ?>
        
                    <?php /* right-sidebar */ ?>
                    <?php _core::method( '_layout' , 'aside' , 'left' , $post -> ID , 'single' ); wp_reset_query(); ?>
                    
                    <div id="primary" <?php _core::method( '_layout' , 'primary_class' , $post -> ID , 'single' )?>>                        
                        <div id="content" role="main">
                            <div <?php _core::method( '_layout' , 'content_class' , $post -> ID , 'single' )?>>

                                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                    <?php
                                        if( _core::method( '_map' , 'markerExists' , $post -> ID  ) || ( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'posts' , 'enb-featured' ) && ( has_post_thumbnail() || ( get_post_format( $post -> ID ) == 'video' ) ) ) ){
                                    ?>
                                            <header class="entry-header">
                                                <div class="featimg">
													<?php $border = _core::method( "_settings" , "logic" , "settings" , "blogging" , "posts" , "enb-featured-border" ); ?>
                                                    <?php
                                                        $map = _meta::get( $postID , 'map' );
                                                        
                                                        $map_id = '';

                                                        if( _core::method( '_map' , 'markerExists' , $post -> ID  ) ){
                                                            $map_id = 'id="map_canvas"';
                                                            _core::method( '_box' , 'mapFrontEnd' , $post -> ID );
                                                        }
                                                    ?>
                                                    <div class="img <?php if( !$border ) echo "noborder"; ?>" <?php echo $map_id; ?>><!--Add noborder to remove 10px border-->
                                                        <?php 
                                                            if( strlen( $map_id ) == 0 ){
                                                                if( get_post_format( $post -> ID ) == 'video' ){

                                                                    $video_format = _core::method( '_meta' , 'get' , $post -> ID , 'format' );

                                                                    if( strlen( $video_format[ "feat_url" ] ) > 1 ){
                                                                        $video_url = $video_format[ "feat_url" ];
                                                                        $youtube_id = _core::method( 'post' , 'get_youtube_video_id' , $video_url );
                                                                        $vimeo_id= _core::method( 'post' , 'get_vimeo_video_id' , $video_url );
                                                                        if( $youtube_id != '0'  ){
                                                                            echo _core::method( 'post' , 'get_embeded_video' , $youtube_id , "youtube" );
                                                                        }else if( $vimeo_id != '0' ){
                                                                            echo _core::method( 'post' , 'get_embeded_video' , $vimeo_id , "vimeo" );
                                                                        }
                                                                    }else if( is_numeric( $video_format[ "feat_id" ] ) ){
                                                                        echo _core::method( 'post' , 'get_local_video' , urlencode( wp_get_attachment_url( $video_format[ "feat_id" ] ) ) );
                                                                    }
                                                                }else if( has_post_thumbnail() ){
                                                                    if($border){
                                                                        echo _core::method( '_image' , 'thumbnail' , $post -> ID , _layout::$size[ 'image' ][ _layout::length( $post -> ID ,  'single' ) ] ); 
                                                                    }else{
                                                                        echo wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , array( 610 , 9999 ) );
                                                                    }
                                                                }
                                                            }
														?>
                                                    </div>
                                                </div>
                                            </header>
                                    <?php
                                        }
                                        
                                        $position = _core::method( '_meta' , 'logic' , $postID , 'posts-settings' , 'meta-type' );
                                        
                                        if( $position ){
                                            $classes = 'horizontal';
                                        }else{
                                            $classes = 'vertical';
                                        }
                                    ?>

                                    <div class="entry-content <?php echo $classes; ?>">
                                        
                                        <?php /* single meta */ ?>
                                        <?php
                                                $resources = _core::method( '_resources' , 'get' );
                                                $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $postID ) ;
                                                if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) && 
                                                    $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'yes' ){
                                                    
                                                    if( _core::method( '_meta' , 'logic' , $postID , 'posts-settings' , 'meta' ) ){
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
                                            	if( get_post_format( $post -> ID ) == 'link' ){
													echo _core::method( 'post' , 'get_attached_files' , $post -> ID );
												}else if( get_post_format( $post -> ID ) == 'audio' ){
													$audio = new AudioPlayer();	
													echo $audio -> processContent( _core::method( 'post' , 'get_audio_files' , $post -> ID ) );
												}else if( get_post_format( $post -> ID ) == 'video' ){
													if(isset( $video_format[ 'video_ids' ] ) && !empty( $video_format[ 'video_ids' ] ) ){
														foreach( $video_format[ "video_ids" ] as $videoid ){
														if(isset($video_format["video_urls"]) && is_array($video_format["video_urls"]) && isset($video_format["video_urls"][$videoid])){
																$video_url=$video_format["video_urls"][$videoid];
											 					$youtube_id = _core::method( 'post' , 'get_youtube_video_id' , $video_url );
																$vimeo_id= _core::method( 'post' , 'get_vimeo_video_id' , $video_url );
                                                                if(  strlen( $youtube_id ) ){
                                                                    echo _core::method( 'post' , 'get_embeded_video' , $youtube_id , "youtube" );
                                                                }else if( strlen($vimeo_id) ){
                                                                    echo _core::method( 'post' , 'get_embeded_video' , $vimeo_id , "vimeo" );
																}
                                                            }else{
                                                                echo _core::method('post','get_local_video', urlencode(wp_get_attachment_url($videoid)));
                                                            }
                                                        }
                                                    }
                                                }else if( get_post_format( $post->ID ) == "image" ){
													$image_format = _core::method( '_meta' , 'get' , $post -> ID , 'format' );
													echo "<div class=\"attached_imgs_gallery\">";
													if( isset( $image_format[ 'images' ] ) && is_array( $image_format[ 'images' ] ) ){
														foreach($image_format['images'] as $index=>$img_id){
															$thumbnail= wp_get_attachment_image_src( $img_id, 'thumbnail');
															$full_image=wp_get_attachment_url($img_id);
															$url=$thumbnail[0];
															$width=$thumbnail[1];
															$height=$thumbnail[2];
															echo "<div class=\"attached_imgs_gallery-element\">";
															echo "<a title=\"\" rel=\"prettyPhoto-".get_the_ID()."\" href=\"".$full_image."\">";

															if( $height < 150 ){
																$vertical_align_style="style=\"margin-top:".((150-$height)/2)."px;\"";
															}else{
																$vertical_align_style="";
															}
		
															echo "<img alt=\"\" src=\"$url\" width=\"$width\" height=\"$height\" $vertical_align_style>";
															echo "</a>";
															echo "</div>";
                                                        }
														echo "</div>";
                                                    }
                                                }
                                                
                                                /* content */
												echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'single' , 'post_text' , 'content' , $post , 'span' );
                                                
                                                /* additional info */
                                                $additional = _core::method( '_meta' , 'get' , $post -> ID , 'additional' );
                                                $resources  = _core::method( '_resources' , 'get' );
                                                $resource = $resources[ _attachment::getCustomIDByPostID( $post -> ID ) ];
                                                
                                                $is_empty = true;
                                                
                                                if( is_array( $additional ) && !empty( $additional ) ){
                                                    foreach( $additional as $key => $value ){
                                                        if( !empty( $value ) ){
                                                            $is_empty = false;
                                                        }
                                                    }
                                                }
                                                
                                                
                                                if(  !$is_empty && !empty( $additional ) && is_array( $additional ) && !empty( $resource[ 'boxes' ][ 'additional' ] ) ){
                                                ?>
                                                    <table class="additional-info">
                                                    <tbody>
                                                <?php
                                                    $i = 0;
                                                    foreach( $resource[ 'boxes' ][ 'additional' ] as $set => $field ){
                                                        if( !empty( $additional[ $set ] ) ){
                                                            $i++;
                                                            ?>
                                                                <tr class="row_<?php echo $i; ?>">
                                                                    <td class="td_1_<?php echo $i; ?>"><?php echo $field[ 'label' ]; ?></td>
                                                                    <td class="td_2_<?php echo $i; ?>"><?php echo $additional[ $set ]; ?></td>
                                                                </tr>
                                                            <?php
                                                        }
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
										$events = _core::method('_meta','get',$postID , 'program');
										//var_dump($events);
										
										if( count( $events ) && !empty( $events ) ){
											echo _core::method('_program','getPrgramm',$postID);
										}
										
                                        if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] ) && 
                                            $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] == 'yes' ){
                                            
                                            $source = _core::method( '_meta' , 'get' , $postID , 'posts-settings' , 'source' );
                                            
                                            if( !empty( $source ) ){
                                                $source_ = '<div class="source no_source"><p>' . __( 'Source' , _DEV_ ) . ' : <a href="' . $source . '" target="_blank">' . $source . '</a></p></div>';
                                            }else{
                                                $source_ = '<div class="source no_source"><p>' . __( 'Unknown source' , _DEV_ ) . '</p></div>';
                                            }
                                        }
                                        
                                        if( isset( $resources[ $customID ][ 'boxes' ][ 'attachdocs' ] ) ){
                                            $attachdocs = _core::method( '_meta' , 'get' , $postID , 'attachdocs' );
                                            
                                            if( !empty( $attachdocs ) ){
                                                $attachdocs_  = '<table class="demo-download">';
                                                $attachdocs_ .= '<tbody>';
                                                foreach( $attachdocs as $doc ){
                                                    if( !empty( $doc[ 'demo' ] ) || !empty( $doc[ 'url' ] ) ){
                                                        $attachdocs_ .= '<tr>';
                                                        
                                                        if( empty( $doc[ 'url' ] ) ){
                                                            $attrdemo = 'colspan="2"';
                                                        }else{
                                                            $attrdemo = '';
                                                        }
                                                        
                                                        if( empty( $doc[ 'demo' ] ) ){
                                                            $attrurl = 'colspan="2"';
                                                        }else{
                                                            $attrurl = '';
                                                        }
                                                        if( !empty( $doc[ 'demo' ] ) ){
                                                            $attachdocs_ .= '<td class="demo-link" ' .  $attrdemo . '>';
                                                            $attachdocs_ .= '<p class="demo-link-title"><a href="' . $doc[ 'demo' ] .  '">' . __( 'Demo' , _DEV_ ) .  '</a></p>';
                                                            $attachdocs_ .= '</td>';
                                                        }

                                                        if( !empty( $doc[ 'url' ] ) ){
                                                            $attachdocs_ .= '<td class="attach" ' .  $attrurl . '>';
                                                            $attachdocs_ .= '<p class="attach-title"><a href="' . $doc[ 'url' ] .  '">' . __( 'Download' , _DEV_ ) .  '</a></p>';
                                                            $attachdocs_ .= '</td>';
                                                        }

                                                        $attachdocs_ .= '</tr>';
                                                    }
                                                }
                                                $attachdocs_ .= '</tbody>';
                                                $attachdocs_ .= '</table>';
                                            }
                                        }
                                            
                                        if( isset( $source_) || isset( $attachdocs_ ) ){
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
							
                                <p class="delimiter blank">&nbsp;</p>
                                <?php
                                    /* comments */
                                    if( comments_open() ){
                                        if( _core::method( "_settings" , "logic" , "settings" , "general" , "general_settings" , "fb_comments" ) ) {
                                    ?>
                                            <div id="comments">
                                                <h3 id="reply-title"><?php _e( 'Leave a reply' , _DEV_ ); ?></h3>
                                                <p class="delimiter">&nbsp;</p>
                                                <fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="620" height="120" reverse="true"></fb:comments>
                                            </div>
                                    <?php
                                        }else{
                                            comments_template( '', true );
                                        }
                                    }

                                    /* related posts */
                                    get_template_part( '/templates/single/related' );
                                ?>
                            </div>
                        </div>
                    </div>
        
                    <?php /* right-sidebar */ ?>
                    <?php _core::method( '_layout' , 'aside' , 'right' , $postID , 'single' );  wp_reset_query(); ?>
        <?php
                }
            }
        ?>
    </div>
</div>
<?php get_footer(); ?>