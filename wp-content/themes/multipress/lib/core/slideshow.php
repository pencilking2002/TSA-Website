<?php
    class _slideshow{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _slideshow();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_slideshow' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_slideshow' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function panel( $postID ){
            
            $result  = '';
            $result .= '<div class="panel-slideshow mrecords-panel">';
            $result .= self::items( $postID );
            $result .= '</div>';
            $result .= '<div class="panel-slideshow-action mrecords-addform">';
            $result .= self::addForm( $postID );
            $result .= '</div>';
            ?>
                <script>
                    jQuery(function(){
                        jQuery('input#slider-post-id').each(function(){
                            jQuery( this ).attr( 'style' , 'border:1px solid red' ).css( 'siplay' , 'block;' );
                        });
                    });
                </script>    
            <?php
            return $result;
        }
        
        public static function items( $postID ){
            $result  = '';
            $result .= '<script>';
            $result .= 'slideshow.vr.sort(' . $postID . ');';
            $result .= '</script>';
            $result .= '<ul>';
            $slideshow = _meta::get( $postID , 'slideshow' );
            if( !empty( $slideshow ) ){
                foreach( $slideshow as $index => $slider ){
                    $result .= self::item( $postID , $index , $slider );
                }
            }else{
                $result .= '<li><p>' . __( 'No slideshow items found' , _DEV_ ) . '</p></li>';
            }
            $result .= '</ul>';
            
            return $result;
        }
        
        public static function item( $postID , $index , $slider ){
            $result  = '<li class="slider">';
            $result .= '<div class="record-item">';
            $result .= '<input type="hidden" value="' . $index . '" class="slider-item-id">';
            if( isset( $slider[ 'slider-image-id' ] ) && (int)$slider[ 'slider-image-id' ] > 0 ){
                $icon  = '<a href="javascript:slideshow.r( \'remove_image\' , [ ' . $postID . ' , ' . $index . ' ] , \'.media-icon-'.$index.'\' );">'. __( 'Remove Image' , _DEV_ ).'</a>';
                $icon .= wp_get_attachment_image( $slider[ 'slider-image-id' ] , 'thumbnail' );
                
               
                if( isset( $slider[ 'slider-type' ] ) &&  isset( $slider[ 'slider-post-id' ] ) && $slider[ 'slider-type' ] != 'image' && (int)$slider[ 'slider-post-id' ] > 0 ){
                    $post = get_post( $slider[ 'slider-post-id' ] );
                    
                    if( empty( $slider[ 'slider-title' ] ) && !empty( $post ) ){
                        $title = '<a href="post.php?post=' . $slider[ 'slider-post-id' ] . '&action=edit">' . $post -> post_title . '</a>';
                    }else{
                        $title = $slider[ 'slider-title' ];
                    }
                    
                    if( empty( $slider[ 'slider-description' ] ) && !empty( $post ) ){
                        $description = mb_substr( $post -> post_excerpt , 0 , 180 );
                    }else{
                        $description = $slider[ 'slider-description' ];
                    }
                }else{
                    $title = $slider[ 'slider-title' ];
                    $description = $slider[ 'slider-description' ];
                }
            }else{
                if( isset( $slider[ 'slider-type' ] ) &&  isset( $slider[ 'slider-post-id' ] ) && $slider[ 'slider-type' ] != 'image' && (int)$slider[ 'slider-post-id' ] > 0 ){ 
                    if( has_post_thumbnail( $slider[ 'slider-post-id' ] ) ){
                        $icon  = '<a href="post.php?post=' . $slider[ 'slider-post-id' ] . '&action=edit">'. __( 'Edit post' , _DEV_ ).'</a>';
                        $icon .= wp_get_attachment_image( get_post_thumbnail_id( $slider[ 'slider-post-id' ] ) , 'thumbnail' );
                        
                    }else{
                        $icon = '<img src="' . get_template_directory_uri() . '/lib/core/images/no.image.thumbnail.png"/>';
                    }
                    
                    $post = get_post( $slider[ 'slider-post-id' ] );
                    
                    if( empty( $slider[ 'slider-title' ] ) && !empty( $post ) ){
                        $title = '<a href="post.php?post=' . $slider[ 'slider-post-id' ] . '&action=edit">' . $post -> post_title . '</a>';
                    }else{
                        $title = $slider[ 'slider-title' ];
                    }
                    
                    if( empty( $slider[ 'slider-description' ] ) && !empty( $post ) ){
                        $description = mb_substr( $post -> post_excerpt , 0 , 180 );
                    }else{
                        $description = $slider[ 'slider-description' ];
                    }
                }else{
                    $icon = '<img src="' . get_template_directory_uri() . '/lib/core/images/no.image.thumbnail.png"/>';
                    $title = $slider[ 'slider-title' ];
                    $description = $slider[ 'slider-description' ];
                }
            }
            
            $result .= '<div class="media-icon media-icon-' . $index . '">' . $icon . '</div>';
            $result .= '<div class="panel-preview panel-' . $index . '">';
            $result .= '<strong>' . $title . '</strong>';
            $result .= '<p>' . $description . '</p>';
            $result .= '</div>';
            $result .= '<div class="panel-edit hidden panel-' . $index . '">';
            $result .= self::editForm( $postID , $index , $slider );
            $result .= '</div>';
            $result .= '<div class="clear"></div>';
            $result .= '<span class="item-info item-action">';
            $result .= '<a class="edit" href="javascript:tools.s( \'.panel-edit.hidden.panel-' . $index . '\' );field.vr.init();tools.h( \'.panel-preview.panel-' . $index . '\' );">' . __( 'Edit' , _DEV_ ) . '</a>';
            $result .= ' | ';
            $result .= '<a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete slider?' , _DEV_ ) . '\')) { slideshow.r( \'remove\' , [ ' . $postID . ' , ' . $index . ' ] , slideshow.vr.cnt ); }})();">' . __( 'Delete' , _DEV_ ) . '</a>';
            $result .= '</span>';
            
            $result .= '<div class="clear"></div>';
            $result .= '</div>';
            $result .= '</li>';
            return $result;
        }
        
        public static function sliderType( $postID ){
            $post =  get_post( $postID );
            $resources  = _core::method( '_resources' , 'get' );
            $result     = array();
            $result[ 'image' ] = __( 'Simple image' , _DEV_ );
            
            foreach( $resources as $index => $resource ){
                if( $post -> post_type ==  $resource[ 'slug' ] ){
                    continue;
                }
                $result[ $resource[ 'slug' ] ] = $resource[ 'stitle' ];
            }
            
            return $result;
        }
        
        public static function getSearch( $post_type  , $index = -1){
            $result = '';
            if( $index > -1 ){
                $prefix = $index;
                $type = 'box';
            }else{
                $prefix = '';
                $type = '';
            }
            if( $post_type != 'image' ){
                $result = _fields::layout( array(
                    'type' => 'st' . $type . '--search',
                    'label' => __( 'Select post' , _DEV_ ),
                    'query' => array(
                        'post_type' => $post_type,
                        'post_status' => 'publish'
                    ),
                    'classes' => 'generic-value',
                    'id' => 'slider-post-id' . $prefix,
                    'hint' => __( 'Start typing the ' , _DEV_ ) . ' <strong>' . $post_type .  '</strong> ' . __( 'title' , _DEV_ )
                ));
            }else{
                $result = _fields::layout( array(
                    'type' => 'st' . $type . '--hidden',
                    'id' => 'slider-post-id'  . $prefix ,
                    'value' => 0
                ));
            }
            return $result;
        }   
        
        public static function addForm( $postID ){
            $result  = '';
            $result .= _fields::layout( array(
                'type' => 'st--select',
                'values' => self::sliderType( $postID ),
                'label' => __( 'Select slider type' , _DEV_ ),
                'id' => 'slider-type',
                'action' => "slideshow.r( 'getSearch' , [ this.value ] , 'div.post-autocomplete' )"
            ));
            
            $hclasses = 'hidden';
            
            $result .= _fields::layout( array( 'type' => 'cd--code' , 'content' => '<div class="post-autocomplete">' ) );
            $result .= _fields::layout( array(
                'type' => 'st--hidden',
                'id' => 'slider-post-id',
                'value' => 0
            ));
            $result .= _fields::layout( array( 'type' => 'cd--code' , 'content' => '</div>' ) );
            
            $result .= _fields::layout( array(
                'type' => 'st--text',
                'label' => __( 'Slider title' , _DEV_ ),
                'id' => 'slider-title',
                'hclasses' => $hclasses,
                'hint' => __( 'If not completed will use post title' , _DEV_ )
            ));
            
            $result .= _fields::layout( array(
                'type' => 'st--textarea',
                'label' => __( 'Slider description' , _DEV_ ),
                'id' => 'slider-description',
                'hclasses' => $hclasses,
                'hint' => __( 'If not completed will use post excerpt (first 180 chars) or post content (first 180 chars)' , _DEV_ )
            ));
            
            $result .= _fields::layout( array(
                'type' => 'st--text',
                'label' => __( 'Slider URL' , _DEV_ ),
                'id' => 'slider-link',
                'hclasses' => $hclasses,
                'hint' => __( 'If not completed then Title will link to the selected post' , _DEV_ )
                
            ));
            
            $result .= _fields::layout( array(
                'type' => 'st--upload-id',
                'label' => __( 'Slider image' , _DEV_ ),
                'btnType' => 'secondary',
                'id' => 'slider-image',
                'hclasses' => $hclasses,
                'hint' => __( 'If not uploaded will use post Featured image' , _DEV_ )
            ));
    
            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="addmbox-submit-action">' ) );
            $result .= _fields::layout( array(
                    'type' => 'ln--button',
                    'value' => __( 'Add to Slideshow' , _DEV_ ),
                    'btnType' => 'primary fl',
                    'action' => "slideshow.r(
                        'add' , 
                        [
                            tools.v('input#post_ID'), 
                            tools.v('select#slider-type'),
                            tools.v('input#slider-post-id'),
                            tools.v('input#slider-title'),
                            tools.v('textarea#slider-description'),
                            tools.v('input#slider-link'),
                            tools.v('input#slider-image-id')
                        ] , slideshow.vr.cnt
                    );field.clean('.panel-slideshow-action.mrecords-addform');"
                )
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--mssg',
                    'value' => __( 'Successfully added' , _DEV_ ),
                    'classes' => 'result-mssg nf',
                    'iclasses' => 'success hidden'
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--mssg',
                    'value' => __( 'Error, try again' , _DEV_ ),
                    'classes' => 'result-mssg nf',
                    'iclasses' => 'error hidden'
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' ) );
            
            return $result;
        }
        
        public static function editForm( $postID , $index , $slider ){
            
            $result  = '';
            
            $result .= _fields::layout( array(
                'type' => 'stbox--select',
                'values' => self::sliderType( $postID ),
                'label' => __( 'Select slider type' , _DEV_ ),
                'id' => 'slider-type' . $index ,
                'value' => $slider[ 'slider-type' ],
                'action' => "slideshow.r( 'getSearch' , [ this.value , " . $index . " ] , 'div.post-autocomplete-" . $index . "' )"
            ));
                    
            $result .= _fields::layout( array(  'type' => 'cd--code' , 'content' => '<div class="post-autocomplete-' . $index . '">' ) );
            
            $hclasses = 'hidden';
            
            if( isset( $slider[ 'slider-type' ] ) && !empty( $slider[ 'slider-type' ] ) && isset( $slider[ 'slider-post-id' ] ) && (int)$slider[ 'slider-post-id' ] > 0 ){
                $result .= _fields::layout( array(
                    'type' => 'stbox--search',
                    'label' => __( 'Select post' , _DEV_ ),
                    'query' => array(
                        'post_type' => $slider[ 'slider-type' ],
                        'post_status' => 'publish'
                    ),
                    'hint' => __( 'Start typing the' , _DEV_ ) . '  <strong>' . $slider[ 'slider-type' ] . '</strong> ' . __( 'title' , _DEV_ ),
                    'id' => 'slider-post-id' . $index,
                    'value' => $slider[ 'slider-post-id' ]
                ));
                
                $hclasses = '';
            }else{
                $result .= _fields::layout( array(
                    'type' => 'st--hidden',
                    'id' => 'slider-post-id'.$index,
                    'value' => 0
                ));
            }
            
            
            
            $result .= _fields::layout( array( 'type' => 'cd--code' , 'content' => '</div>' ) );
            
            $result .= _fields::layout( array(
                'type' => 'stbox--text',
                'label' => __( 'Slider title' , _DEV_ ),
                'id' => 'slider-title' . $index,
                'hint' => __( 'If not completed will use post title' , _DEV_ ),
                'hclasses' => $hclasses,
                'value' => $slider[ 'slider-title' ]
            ));
            
            $result .= _fields::layout( array(
                'type' => 'stbox--textarea',
                'label' => __( 'Slider description' , _DEV_ ),
                'id' => 'slider-description' . $index,
                'hint' => __( 'If not completed will use post excerpt (first 180 chars) or post content (first 180 chars)' , _DEV_ ),
                'hclasses' => $hclasses,
                'value' => $slider[ 'slider-description' ]
            ));
            
            $result .= _fields::layout( array(
                'type' => 'stbox--text',
                'label' => __( 'Slider URL' , _DEV_ ),
                'id' => 'slider-link' . $index,
                'hint' => __( 'If not completed then Title will link to the selected post' , _DEV_ ),
                'hclasses' => $hclasses,
                'value' => $slider[ 'slider-link' ]
            ));
            
            $result .= _fields::layout( array(
                'type' => 'stbox--upload-id',
                'label' => __( 'Slider image' , _DEV_ ),
                'btnType' => 'secondary',
                'id' => 'slider-image' . $index,
                'hclasses' => $hclasses,
                'hint' => __( 'If not uploaded will use post Featured image' , _DEV_ )
            ));
            
            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="submit-action">' ) );
            $result .= _fields::layout( array(
                    'type' => 'ln--button',
                    'value' => __( 'Update Slider' , _DEV_ ),
                    'btnType' => 'primary',
                    'classes' => 'fl',
                    'action' => "slideshow.r(
                        'edit' , 
                        [
                            " . $postID . " , 
                            " . $index . " ,
                            tools.v('select#slider-type" . $index . "' ),
                            tools.v('input#slider-post-id" . $index . "'),
                            tools.v('input#slider-title" . $index . "'),
                            tools.v('textarea#slider-description" . $index . "'),
                            tools.v('input#slider-link" . $index . "'),
                            tools.v('input#slider-image" . $index . "-id')
                        ] , slideshow.vr.cnt
                    )"
                )
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' ) );
            
            return $result;
        }
        
        public static function add( $postID , $sliderType , $sliderPostID , $title , $description , $link , $imageID ){
            if( $postID > 0 ){
                $slideshow = array(
                    'slider-type' => $sliderType,
                    'slider-post-id' => $sliderPostID,
                    'slider-title' => $title,
                    'slider-description' => $description,
                    'slider-link' => $link,
                    'slider-image-id' => $imageID
                );
                
                _meta::add( $postID , 'slideshow' , $slideshow );
            }
            
            return self::items( $postID );
        }
        
        public static function sort( $postID , $slideshowIDs ){
            $slideshow = _meta::get( $postID , 'slideshow' );
            $new_slideshow = array();
            
            if( is_array( $slideshowIDs ) && !empty( $slideshowIDs ) && is_array( $slideshow ) && !empty( $slideshow ) ){
                foreach( $slideshowIDs as $key => $index ){
                    if( isset( $slideshow[ $index ] ) ){
                        $new_slideshow[] =  $slideshow[ $index ];
                    }
                }
                
                _meta::set( $postID , 'slideshow' , $new_slideshow );
            }
            
            return self::items( $postID );
        }
        
        public static function edit( $postID , $sliderID , $sliderType , $sliderPostID , $title , $description , $link , $imageID ){
            if( $postID > 0 ){
                $slideshow = _meta::get( $postID , 'slideshow' );
                if( is_array( $slideshow ) && isset( $slideshow[ $sliderID ] ) ){
                    if( (int)$imageID == 0 ){
                        if( isset( $slideshow[ $sliderID ][ 'slider-image-id' ] ) && $slideshow[ $sliderID ][ 'slider-image-id' ] > 0 ){
                            $imageID = $slideshow[ $sliderID ][ 'slider-image-id' ];
                        }
                    }
                    $slider = array(
                        'slider-type' => $sliderType,
                        'slider-post-id' => $sliderPostID,
                        'slider-title' => $title,
                        'slider-description' => $description,
                        'slider-link' => $link,
                        'slider-image-id' => $imageID
                    );
                    _meta::edit( $postID , 'slideshow' , $slider , $sliderID );
                }
            }
            
            return self::items( $postID );
        }
        
        public static function remove( $postID , $slideID ){
            _meta::remove( $postID , 'slideshow' , $slideID );
            return self::items( $postID );
        }
        
        public static function remove_image( $postID , $sliderID ){
            $slideshow = _meta::get( $postID , 'slideshow' );
            $result = '';
            if( !empty( $slideshow ) && is_array( $slideshow ) ){
                $slider = $slideshow[ $sliderID ];
                $slider[ 'slider-image-id' ] = '';
                
                _meta::edit( $postID , 'slideshow' , $slider , $sliderID );
            }
            
            if( $slider[ 'slider-type' ] != 'image' && $slider[ 'slider-post-id' ] > 0 ){
                if( has_post_thumbnail( $slider[ 'slider-post-id' ] ) )
                $result .= '<a href="post.php?post=' . $slider[ 'slider-post-id' ] . '&action=edit">'. __( 'Edit post' , _DEV_ ).'</a>';
                $result .= wp_get_attachment_image( get_post_thumbnail_id(  $slider[ 'slider-post-id' ] ) , 'thumbnail' );
                
                
            }else{
                $result .= '<img src="' . get_template_directory_uri() . '/lib/core/images/no.image.thumbnail.png"/>';
            }
            return $result;
        }
        
       public static function exists_slideshow( ){
            if( is_single() ){
                global $post;
                $slideshowID = _core::method( '_meta' , 'get' , $post -> ID , 'posts-settings' , 'slideshow' );

                if(  $slideshowID > 0 ){
					$slideshow_post=get_post($slideshowID);
					if(!is_wp_error($slideshow_post) && is_object($slideshow_post) && property_exists($slideshow_post,'post_status') && $slideshow_post->post_status=="publish")
						{
							$slideshow = true;
						}
					else
						{
							$slideshow = false;
						}
                }else{
                    $slideshow = false;
                }
            }else{
                $slideshow = false;
            }
            
			$default_slideshow=false;
			$slider_id = _core::method('_settings','get','settings','slideshow','general','item'); //get the slideshow ID from the settings
			if(intval($slider_id) == 0){ // if no slideshow is assigned, then we get the last created slideshow
				$args = array(
				'numberposts'     => 1,
				'post_type'       => 'slideshow',
				);
				$last_slideshow = get_posts( $args );  //var_dump($last_slideshow);
				if(sizeof($last_slideshow)){
					$slider_id = $last_slideshow[0]->ID;
				}	
			}
			
			if(intval($slider_id) != 0){ // if a real slideshow ID exists
				$default_slideshow_post=get_post($slider_id);
				if(!is_wp_error($default_slideshow_post) && is_object($default_slideshow_post) && property_exists($default_slideshow_post,'post_status') && $default_slideshow_post->post_status=="publish")
				{
					$default_slideshow=true;
				}
			}
			//var_dump($default_slideshow);

			$result = (is_home() && $default_slideshow)
					  || (is_front_page() && $default_slideshow)
					  || $slideshow;
            
            return $result;
        }
    }
?>