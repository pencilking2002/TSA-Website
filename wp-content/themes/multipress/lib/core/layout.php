<?php
	class _layout{
        static $size = array(
            'primary' => array(
                'fullwidth' => 930,
                'sidebar' => 640
            ),
            'content' => array(
                'fullwidth' => 930,
                'sidebar' => 610
            ),
            'entry' => array(
                'fullwidth' => 450,
                'sidebar' => 610
            ),
            'image' => array(
                930 => 'single_big',
                640 => 'list'
            )
        );
        
        public static function aside_type( $postID = 0 , $template = null ){
            if( $postID > 0 ){
                $layout = _core::method( '_meta' , 'get' , $postID , 'layout' );

                if( isset( $layout[ 'style' ] ) && !empty( $layout[ 'style' ] ) ){
                    $result = $layout[ 'style' ];
                }else{

                    if( strlen( $template ) ){
                        $result = _core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , $template );
                    }else{
                        $result = '';
                    }
                }
            }else{
                if( strlen( $template ) ){
                    $result = _core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , $template );
                }else{
                    $result = '';
                }
            }
            
            return $result;
        }
        
        public static function aside( $side = 'right' , $postID = 0 , $template = null ){
            if( strlen( $side ) ){
                if( $postID > 0 ){
                    $layout = _core::method( '_meta' , 'get' , $postID , 'layout' );

                    if( isset( $layout[ 'style' ] ) && !empty( $layout[ 'style' ] ) ){
                        $result = $layout[ 'style' ];
                    }else{

                        if( strlen( $template ) ){
                            $result = _core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , $template );
                        }else{
                            $result = '';
                        }
                    }
                }else{
                    if( strlen( $template ) ){
                        $result = _core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , $template );
                    }else{
                        $result = '';
                    }
                }

                if( $side == 'right' ){
                    $classes = 'fr';
                }else{
                    $classes = 'fl';
                }

                if( $result == $side ){
                    echo '<div id="secondary" class="widget-area w_280 ' . $classes . '" role="complementary">';
                    
                    if( is_single() || is_author() || is_page() ){
                        get_template_part('/templates/author-box');
                    }
                    
                    if( isset( $layout['sidebar'] ) && !empty( $layout['sidebar'] ) ){                        
                        if( dynamic_sidebar ( $layout['sidebar'] ) ){

                        }
                    }else{
                        $sidebar = _core::method( '_settings' , 'get' , 'settings' , 'layout' , 'style' , $template . '_sidebar' );
                        if( !empty( $layout ) ){
                            if( dynamic_sidebar ( $layout ) ){

                            }
                        }else{
                            get_sidebar( );
                        }
                        
                    }
                    echo '</div>';
                }
            }
        }

        public static function length( $postID = 0 , $template = null , $type = 'primary'){
            $layout = _core::method( '_meta' , 'get' , $postID , 'layout' );
            if( isset( $layout['style'] ) && !empty( $layout['style'] ) ) {
                if( $layout['style'] == 'full'  ){
                    $ln = self::$size[ $type ][ 'fullwidth' ];
                }else{
                    $ln = self::$size[ $type ][ 'sidebar' ];
                }
            }else{
                if( strlen( $template ) ){
                    $layout = _settings::get( 'settings' , 'layout' , 'style' ,  $template );
                    if( $layout == 'full' ){
                        $ln = self::$size[ $type ][ 'fullwidth' ];
                    }else{
                        $ln = self::$size[ $type ][ 'sidebar' ];
                    }
                }
            }

            return $ln;
        }
        
        public static function primary_class( $postID , $template , $flag = true ){
            $type = _core::method( '_layout' , 'aside_type' , $postID , $template );
            if( self::length( $postID , $template ) == self::$size[ 'primary' ][ 'fullwidth' ] ){
                $classes = 'fullwidth';
            }else{
                if( $type == 'right' ){
                    $classes = 'fl';
                }else{
                    $classes = 'fr';
                }
            }
            
            if( $flag ){
                echo ' class="w_' . self::length( $postID , $template ) . ' ' . $classes . '" ';
            }else{
                return ' class="w_' . self::length( $postID , $template ) . ' ' . $classes . '" ';
            }
        }
        
        public static function content_class( $postID , $template , $flag = true , $classes = ''){
            $grid = self::is_grid( $template );

            if( $template == 'single' || $template == 'page'){
                $c = 'single';
            }else{
                $c = '';
            }
            
            if( $flag ){
                echo 'class="w_' . self::length( $postID , $template , 'content' ) . ' ';
                if( empty( $c ) ){
                    if( $grid ){
                        echo 'grid-view';
                    }else{
                        echo 'list-view';
                    }
                }
                echo ' ' . $c . ' ' . $classes . '"';
            }else{
                $reuslt  = 'class="w_' . self::length( $postID , $template , 'content' ) . ' ';
                if( empty( $c ) ){
                    if( $grid ){
                        $reuslt .= 'grid-view';
                    }else{
                        $reuslt .= 'list-view';
                    }
                }
                $reuslt .= ' ' . $c . ' ' . $classes . '"';
                
                return $result;
            }
        }
        
        public static function entry_class( $postID , $template , $flag = true ){
            if( $flag ){
                echo 'w_' . self::length( $postID , $template , 'entry' );
            }else{
                return 'w_' . self::length( $postID , $template , 'entry' );
            }
        }
        
        public static function is_nsfw( $post_id ){
            $post = get_post( $post_id );
            $meta = _meta::get( $post -> ID  , 'settings' );
            if( isset( $meta['safe'] ) ){
                if( _meta::logic( $post , 'settings' , 'safe' ) ){
                    $result = true;
                }else{
                    $result = false;
                }
            }else{
                $result = false;
            }

            return $result;
        }

        public static function is_grid( $template ){
            
            if( _core::method( '_settings' , 'logic' , 'settings' , 'layout' , 'style' , $template . '_view'  ) ){
                $grid = false;
            }else{
                $grid = true;
            }
            return $grid;
        }
        
        public static function view( $template ){
            if( self::is_grid( $template ) ){
                $result = '';
            }else{
                $result = 'list';
            }
            
            echo $result;
        }

	}
?>