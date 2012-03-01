<?php
    $slideshow = _core::method( '_meta' , 'get' , _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'item' ) , 'slideshow' );
    if( _core::method( '_slideshow' , 'exists_slideshow' ) && !empty( $slideshow ) && is_array( $slideshow ) && !isset( $_GET[ 'fp_type' ] ) ){
?>
        <div class="cosmo-slider-wrapper">
            <div class="cosmo-slider">

                <div id="headerimgs">
                    <div id="headerimg1" class="headerimg"></div>
                    <div id="headerimg2" class="headerimg"></div>
                </div>
                <?php
                    if( _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'news-type' ) == 'latest-post' ){
                        $query = new WP_Query( array( 'post_status' => 'publish' , 'post_type' => _resources::getOnlySlugs( true , array( 'slideshow' ) ) , 'posts_per_page' => 1 ) );
                    }else{
                        $custom_post = _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'custom-post' );
                        if(  strlen( $custom_post ) ){
                            $taxonomy = _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'taxonomy' );
                            if( strlen( $taxonomy ) ){
                                $term = _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'terms' );
                                if( strlen( $term ) ){
                                    $query = new WP_Query( array(
                                        'post_status' => 'publish',
                                        'post_type' => $custom_post,
                                        'posts_per_page' => 1,
                                        'tax_query' => array( array(
                                            'taxonomy' => $taxonomy,
                                            'field' => 'slug',
                                            'terms' => $term
                                        ) ) 
                                    ));
                                }else{
                                    $query = new WP_Query( array( 'post_status' => 'publish' , 'post_type' => $custom_post , 'posts_per_page' => 1 ) );
                                }
                            }else{
                                $query = new WP_Query( array( 'post_status' => 'publish' , 'post_type' => $custom_post , 'posts_per_page' => 1 ) );
                            }
                        }else{
                            $query = new WP_Query( array( 'post_status' => 'publish' , 'post_type' => _resources::getOnlySlugs( true , array( 'slideshow' ) ) , 'posts_per_page' => 1 ) );
                        }
                    }
                    
                    $result = '';
                    
                    if( count( $query -> posts ) > 0 ){
                        foreach( $query -> posts as $post ){
                            
                            if( has_post_thumbnail( $post -> ID )  ){
                                $result .= '<div class="entry-header b w_450">';
                                $result .= '<div class="readmore">';
                                $result .= '<a href="'. get_permalink( $post -> ID ) . '" class="mosaic-overlay">';
                                $result .= '<div class="details">' . __( 'Read more' , _DEV_ ) . '</div>';
                                $result .= '</a>';
                                $result .= _image::thumbnail( $post -> ID , 'list' );
                                $result .= '<div class="format">&nbsp;</div><!--Ads format bg-->';
                                $result .= '<div class="stripes">&nbsp;</div><!--Ads stripes bg-->';
                                $result .= '</div>';
                                $result .= '</div>';
                            }
                            
                            if( strlen( $post -> post_excerpt ) ){
                                $excerpt = strip_tags( strip_shortcodes( $post -> post_excerpt ) );
                            }else{
                                $limit = _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'news-limit' );
                                if( strlen( $post -> post_content ) >  $limit ){
                                    $excerpt = mb_substr( strip_shortcodes( strip_tags( trim( $post -> post_content ) ) ) , 0 , $limit );
                                }else{
                                    $excerpt = mb_substr( strip_shortcodes( strip_tags( trim( $post -> post_content ) ) ) , 0 );
                                }
                            }
                            
                            $result .= '<div class="entry-footer b w_450">';
                            $result .= '<h4>';
                            $result .= '<a href="' . get_permalink( $post -> ID ) . '">' . $post -> post_title . '</a>';
                            $result .= '</h4>';
                            $result .= '<div class="excerpt">';
                            $result .= $excerpt;
							$result .= '</div>';
                            $result .= '<p class="button blue">';
                            $result .= '<a href="' . get_permalink( $post -> ID ) . '">' . __( 'continue reading' , _DEV_ ) . '</a>';
                            $result .= '</p>';
                            $result .= '</div>';

							if($format=get_post_format( $post -> ID )){
								$format_class="format-$format";
							}else{
								$format_class="format-text";
							}
                        }
                    }else{
                        $result = '';
						$format_class='';
                    }
                ?>
                <?php
                    if( strlen( $result ) > 0 ){
                    ?>
                        <div class="cosmo-qnews">
                            <div class="cosmo-qnews-label">
                                <a href="javascript:void(0);"><?php _e( 'Quick news' , _DEV_ ); ?></a>
                            </div>
                            <div class="cosmo-qnews-wrapper">
                                <a class="cosmo-qnews-close" href="#" title="Close"><?php _e( 'close' , _DEV_ ); ?></a>
                                <div class="cosmo-qnews-content">
                                    <div class="w_930 list-view">
                                        <article class="post <?php echo $format_class;?>">		
                                            <?php echo $result; ?>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                ?>

                <div id="headernav-outer">
                    <div id="headernav">
                        <div id="back" class="btn"></div>
                        <div id="next" class="btn"></div>
                    </div>
                </div>

                <div id="headertxt">
                    <div class="caption right">
                        <div class="caption-text">
                            <a href="#" id="firstline"></a>
                            <span id="secondline"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
<?php
    }
?>