<?php
    $resources = _core::method( '_resources' , 'get' );
    $customID  = _core::method( '_resources' , 'getCustomIdByPostType' , $post -> post_type );
    
    if( _core::method( '_meta' , 'logic' , $post -> ID , 'posts-settings' , 'similar' ) ){
        if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] ) &&  $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] == 'yes' ){
            $slug = $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-criteria' ];
            $terms = wp_get_post_terms( $post -> ID , $slug , array("fields" => "all") );
            $t = array();
            foreach( $terms as $term ){
                array_push( $t , $term -> slug );
            }
            
            if( !empty( $t ) && is_array( $t ) ){
                
                $length = _core::method( '_layout' , 'length' , $post -> ID , 'single' );
                
                if( (int)$length == _layout::$size[ 'primary' ][ 'fullwidth' ] ){
                    $div = 3;
                    $similar_number = $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-number-full' ];
                }else{
                    $div = 2;
                    $similar_number = $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-number-sidebar' ];
                }
                                    
                $args = array(
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => $slug,
                            'field' => 'slug',
                            'terms' => $t
                        )
                    ),
                    'posts_per_page' => $similar_number,
                    'post_type' => $resources[ $customID ][ 'slug' ],
                    'post_status' => 'publish',
                    'post__not_in' => array( $post -> ID )
                );
                
                $query = new WP_Query( $args );

                if( count( $query -> posts ) > 0 ){
                    ?>
                        <p class="delimiter blank">&nbsp;</p>
                        <div class="box-related clearfix grid-view">
                            <h3 class="related-title"><?php _e( 'Related posts' , _DEV_ ); ?></h3>
                            <p class="delimiter">&nbsp;</p>
                            <div class="loop-container-view">
                                <?php
                                    $nr = count( $query -> posts );
                                    $i  = 1;
                                    $k  = 1;

                                    foreach( $query -> posts as $similar ){
                                        $query -> the_post();
                                        if( $i == 1 ){
                                            if( ( $nr - $k ) < $div  ){
                                                $classes = 'class="element last"';
                                            }else{
                                                $classes = 'class="element"';
                                            }
                                            echo '<div ' . $classes . '>';
                                        }
                                        
                                        post::grid_view( $similar , 'single' );

                                        if( $i % $div == 0 ){
                                            echo '</div>';
                                            $i = 0;
                                        }
                                        $i++;
                                        $k++;

                                    }

                                    /* if div container is open */
                                    if( $i > 1 ){
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    <?php
                }
                
                wp_reset_postdata();
            }
        }
    }
    
    _core::hook( 'related' );
?>