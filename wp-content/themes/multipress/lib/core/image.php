<?php
    class _image{
	
		/*sizes for resized thumbnails*/
        static $size = array(
            'sidebar'               => array( 50  , 50   , true ),
            'list'      			=> array( 590  , 295   , true ),
            'slidshow_big' 			=> array( 1920 , 600 , true  ),
			'slidshow_small' 		=> array( 990 , 300 , true  ),
			'single_big' 			=> array( 930 , 9999  ),
			'single_small' 			=> array( 610 , 9999  ),
			'sponsor' 				=> array( 9999 , 40  ), /*sponsor widget*/
        );
		
		
		/*sizes used throughout the theme*/
        static $img_size = array(
            'sidebar'               => array( 50  , 50   ), /*used in sidebar widgets*/
            'list'      			=> array( 590  , 295   ), /*used in list view */
			'list_small'      		=> array( 430  , 215    ), /*used in list view */
			'grid'					=> array( 290  , 145    ), /*used in grid view */
            'slidshow_big' 			=> array( 1920 , 600   ), /*used in full width slideshow */
			'slidshow_small' 		=> array( 990 , 300   ), /*used in boxed slideshow */
			'single_big' 			=> array( 930 , 9999  ), /*used on single post w/o sidebar */
			'single_small' 			=> array( 610 , 9999  ), /*used in single post w/ sidebar*/
			'sponsor' 				=> array( 9999 , 40  ), /*sponsor widget*/
        );

        static function add_size(){
            $size =array();
            if( function_exists( 'add_image_size' ) ){
                foreach( self::$size as $label => $args ){
                    $labels = explode( ',' , $label ); 
                    //if( (int)$args[2] > 1 ){
					if(sizeof($args) == 2){
                        add_image_size( $labels[0]  , $args[0] , $args[1] );
                    }else{
                        add_image_size( $labels[0]  , $args[0] , $args[1] , $args[2] );
                    }
                }
            }
        }

        static function asize( $type ){
            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $type == $label ){
                            if( $args[1] == 9999 ){
                                return array( $args[0] , $args[2] );
                            }else{
                                return array( $args[0] , $args[1] );
                            }
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $type == $label ){
                        if( $args[1] == 9999 ){
                            return array( $args[0] , $args[2] );
                        }else{
                            return array( $args[0] , $args[1] );
                        }
                    }
                }
            }
        }

        static function tsize( $type ){
            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $type == $label ){
                            if( $args[1] == 9999 ){
                                return $args[0] . 'x' . $args[2];
                            }else{
                                return $args[0] . 'x' . $args[1];
                            }
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $type == $label ){
                        if( $args[1] == 9999 ){
                            return $args[0] . 'x' . $args[2];
                        }else{
                            return $args[0] . 'x' . $args[1];
                        }
                    }
                }
            }
        }

        static function size( $post_id , $template , $type = '' ){
            $size = array();

            foreach( self::$size as $label => $args ){
                $labels = explode( ',' , $label );
                if( count( $labels ) > 1 ){
                    foreach( $labels as $label ){
                        $size[ $label ] = $args;
                        if( $template == $label ){
                            return $labels[0];
                        }
                    }
                }else{
                    $size[ $label ] = $args;
                    if( $template == $label ){
                        return $label;
                    }
                }
            }

            if( isset( $size[ $type ]  ) ){
                return self::size( 0 , $type );
            }

            if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                return 'tlarge';
            }

            if( layout::length( $post_id , $template ) == layout::$size['medium'] ){
                return 'tmedium';
            }

            echo 'not defined size or error';
        }

        static function caption( $post_id ){
            $result = '';
            $args = array(
                'numberposts' => -1,
                'post_type' => 'attachment',
                'status' => 'publish',
                'post_mime_type' => 'image',
                'post_parent' => $post_id
            );

            $images = &get_children( $args );

            if( isset( $images[ get_post_thumbnail_id( $post_id ) ] ) ){
                $result = $images[ get_post_thumbnail_id( $post_id ) ] -> post_excerpt;
            }else{
                $args = array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'status' => 'publish',
                    'post_mime_type' => 'image',
                    'post_parent' => 0
                );

                $images = &get_children($args);

                if( isset( $images[  get_post_thumbnail_id( $post_id ) ] ) ){
                    $result = $images[ get_post_thumbnail_id( $post_id ) ] -> post_excerpt;
                }else{
                    $result = '';
                }
            }

            return $result;
        }

		static function thumbnail_url( $post_id , $thumb_size = '', $url = true ){
			if(get_post_thumbnail_id( $post_id ) != ''){
				$img_obj = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , $thumb_size );
				
				if($url){ /*return URL*/
					return  $img_obj[0];
				}else{ /*return array*/
					return  $img_obj;
				}	
			}else{
				return '';
			}	
		}
		
        static function thumbnail_src( $post_id , $template , $size = '' ){
            if( $size == 'full'){
                return wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , 'full' );
            }else{
                return wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , self::size( $post_id , $template , $size ) );
            }
        }
        
        static function thumbnail( $post_id , $template , $size = '' ){
            if( $size == 'full'){
                return wp_get_attachment_image( get_post_thumbnail_id( $post_id ) , 'full' );
            }else{
                return wp_get_attachment_image( get_post_thumbnail_id( $post_id ) , self::size( $post_id , $template , $size ) );
            }
        }

        static function mis( $post_id , $template , $size = '' , $classes = '' , $side = 'no.image' ){
            return '<img src="' . get_template_directory_uri() . '/images/' . $side . '.' . self::tsize( self::size( $post_id , $template , $size ) ) . '.png" class="' . $classes . '" />';
        }
    }
?>