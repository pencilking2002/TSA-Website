<?php
    class _box{
        public static $boxes;
        public static $default;
        
        public static function get( $type = null ){
            if( !empty( $type ) ){
                return self::$boxes[ $type ];
            }else{
                return self::$boxes;
            }
        }
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _box();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_box' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_box' , $method ), $args );
                }
            }

            exit;
        }
        
        public static function getBoxFields( $type , $box , $args = array() ){
            $result = '';
            
            $meta = _meta::get( self::postID() , $box  );
            if( isset( self::$boxes[ $type ][ $box ][ 'fields' ] ) && !empty( self::$boxes[ $type ][ $box ][ 'fields' ] ) ){
                foreach( self::$boxes[ $type ][ $box ][ 'fields' ] as $set => $field ){
                    
                    $field['group'] = $box;
                    $field['set'] = $set;
                    
                    if( isset( $field[ 'set' ] ) && isset( $meta[ $field[ 'set' ] ] ) ){
                        if( isset( $meta[ $field[ 'set' ] ] ) && !empty( $meta[ $field[ 'set' ] ] ) ){
                            $field['value'] = $meta[ $field[ 'set' ] ];
                            if( isset( $meta[ $field[ 'set' ] . '-id' ] ) ){
                                $field['valueID'] = $meta[ $field[ 'set' ] . '-id' ];
                            }
                            
                        }
                    }
                    $result .= _fields::layout( $field );
                }
            }
            
            return $result;
        }
        public static function register( ){
            
            $meta = _meta::get( self::postID() , 'register' );
            $resources = _core::method( '_resources' , 'get' );    
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            if( !isset( $meta['enable'] ) ){
                if( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'enable' ][ 'cvalue' ] == 'no' ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'use' ][ 'classes' ]             = 'payment-side hidden';
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'value' ][ 'classes' ]           = 'payment-value hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity' ][ 'classes' ]        = 'quantity hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity-sold' ][ 'classes' ]   = 'quantity-sold hidden';
					
                }
            }else{
                 if( !_meta::logic( self::postID() , 'register' , 'enable' ) ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'use' ][ 'classes' ]             = 'payment-side hidden';
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'value' ][ 'classes' ]           = 'payment-value hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity' ][ 'classes' ]        = 'quantity hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity-sold' ][ 'classes' ]   = 'quantity-sold hidden';
                }
            }
            
            if( !isset( $meta['use'] ) ){
                if( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'use' ][ 'cvalue' ] == 'no' ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'value' ][ 'classes' ]           = 'payment-value hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity' ][ 'classes' ]        = 'quantity hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity-sold' ][ 'classes' ]   = 'quantity-sold hidden';
                }
            }else{
                 if( !_meta::logic( self::postID() , 'register' , 'use' ) ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'value' ][ 'classes' ]           = 'payment-value hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity' ][ 'classes' ]        = 'quantity hidden';
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'register' ][ 'fields' ][ 'quantity-sold' ][ 'classes' ]   = 'quantity-sold hidden';
                }
            }
			
            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'register' );
        }
        
        public static function layout( ){
            
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            $meta = $meta = _meta::get( self::postID() , 'layout' );
            
            $resources = _core::method( '_resources' , 'get' );    
            
            if( !isset( $meta['style'] ) ){
                if( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'style' ][ 'cvalue' ] == 'no' ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'sidebar' ][ 'classes' ] = 'layout-sidebar hidden';
                }else{
                    if( isset( $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'style' ] ) && $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'style' ] == 'full' ){
                        self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'sidebar' ][ 'classes' ] = 'layout-sidebar hidden';
                        self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'style'][ 'cvalue' ] = 'full';
                    }else{
                        if( isset( $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'style' ] ) && $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'style' ] != 'full' ){
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'style'][ 'cvalue' ] = $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'style' ];
                            if( isset( $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'sidebar' ] ) ){
                                self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'sidebar'][ 'cvalue' ] = $resources[ $customID ][ 'boxes' ][ 'layout' ][ 'sidebar' ];
                            }
                        }
                    }
                }
            }else{
                if( !empty( $meta ) && _meta::get( self::postID() , 'layout' , 'style' ) == 'full' ){
                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'layout' ][ 'fields' ][ 'sidebar' ][ 'classes' ] = 'layout-sidebar hidden';
                }
            }
            
            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'layout' );
        }

		 public static function format( ){
            
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            $format = $meta = _meta::get( self::postID() , 'format' , 'type' );
            
            $resources = _core::method( '_resources' , 'get' );    
            
			self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'image-uploader' ][ 'classes' ] = 'image-uploader hidden';
			self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'video-uploader' ][ 'classes' ] = 'video-uploader hidden';
			self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'audio-uploader' ][ 'classes' ] = 'audio-uploader hidden';
			self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'file-uploader' ][ 'classes' ] = 'file-uploader hidden';


			switch( $format ){
				case 'image' : {
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'image-uploader' ][ 'classes' ] = 'image-uploader';
					break;
				}
				case 'video' : {
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'video-uploader' ][ 'classes' ] = 'video-uploader';
					break;
				}
				case 'audio' : {
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'audio-uploader' ][ 'classes' ] = 'audio-uploader';
					break;
				}
				case 'link' : {
					self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'format' ][ 'fields' ][ 'file-uploader' ][ 'classes' ] = 'file-uploader';
					break;
				}
				default : {
					break;
				}
			}

            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'format' );
        }
        
        public static function attachdocs(){
            echo _attachdocs::panel( self::postID() );
        }
        
        public static function program(){
            echo _program::panel( self::postID() );
        }
        
        public static function slideshow(){
            echo _slideshow::panel( self::postID() );
        }
        
        public static function posts_settings(){
            $resources = _core::method( '_resources' , 'get' );    
            
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ] ) ){
                
                /* SIMILAR */
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] ) ||
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] ) &&
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'similar' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'similar' ] );
                    }
                }
                
                /* LIKES */
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] ) ||
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] ) &&
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'likes' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'likes' ] );
                    }
                }
                
                /* SOCIAL */
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] ) || 
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] ) && 
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social' ] );
                    }
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ] );
                    }       
                }else{
                    if( self::postID() > 0 ){    
                        $social = _meta::get( self::postID() , 'posts-settings' , 'social' );
                        
                        if( $social == 'yes' ){
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options';
                        }else{
                            if( empty( $social ) ){
                                if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] == 'yes' ){
                                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options';
                                }else{
                                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options hidden';
                                }
                            }else{
                                self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options hidden';
                            }
                        }
                    }else{
                        if(  self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social' ][ 'cvalue' ] == 'yes' ){                            
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options';
                        }else{
                            
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'social-position' ][ 'classes' ] = 'social-position-options hidden';
                        }
                    }
                }
                
                /* OTHERS */
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] ) || 
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] ) && 
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'author-box' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'author-box' ] );
                    }
                }
                
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'archive-use' ] ) || 
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'archive-use' ] ) &&
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'archive-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'archive' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'archive' ] );
                    }
                }
                
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] ) ||
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] ) &&
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'source-use' ] == 'no' ) ){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'source' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'source' ] );
                    }
                }
                
                /* META */
                if( !isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) || 
                    (
                        isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) && 
                        $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'no' )){
                    
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta' ] );
                    }
                    if( isset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ] ) ){
                        unset( self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ] );
                    }
                }else{
                    if( self::postID() > 0 ){
                        $meta = _meta::get( self::postID() , 'posts-settings' , 'meta' );
                        if( $meta == 'yes' ){
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options';
                        }else{
                            if( empty( $meta ) ){
                                if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'yes' ){
                                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options';
                                }else{
                                    self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options hidden';
                                }
                            }else{
                                self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options hidden';
                            }
                        }
                    }else{
                        if(  self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta' ][ 'cvalue' ] == 'yes' ){                            
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options';
                        }else{           
                            self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'posts-settings' ][ 'fields' ][ 'meta-type' ][ 'classes' ] = 'meta-type-options hidden';
                        }
                    }
                }
            }
            
            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'posts-settings' );
        }
        
        public static function additional(){
            $resources = _core::method( '_resources' , 'get' );
            
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            if( isset( $resources[ $customID ][ 'boxes' ][ 'additional' ] ) ){
                if( !empty( $resources[ $customID ][ 'boxes' ][ 'additional' ] ) ){
                    foreach( $resources[ $customID ][ 'boxes' ][ 'additional' ] as $index => $additional ){
                        self::$boxes[ $resources[ $customID ][ 'type' ] ][ 'additional' ][ 'fields' ][ $additional[ 'set' ] ] = $additional;
                    }
                }
            }
            
            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'additional' );
        }
        
        public static function map(){
            $resources = _core::method( '_resources' , 'get' );    
            
            if( self::postID() > 0 ){
                $customID = _attachment::getCustomIDByPostID( self::postID() );
            }else{
                $customID = _resources::getCustomIDByPostType( self::postType() );
            }
            
            echo self::getBoxFields( $resources[ $customID ][ 'type' ] , 'map' );
            
            $map = _meta::get( self::postID() , 'map' );
            ?>
                <script type="text/javascript">
                    
                    var map_args = {
                        
                         'map' : {
                            'mapTypeId' : google.maps.MapTypeId.ROADMAP
                            <?php
                                if( !empty( $map ) && is_array( $map ) && isset( $map[ 'map' ][ 'zoom' ] ) && (int)$map[ 'map' ][ 'zoom' ] > 0 ){
                            ?>
                                    , 'zoom' : <?php echo (int)$map[ 'map' ][ 'zoom' ]; ?>
                            <?php
                                }
                            ?>
                        },
                        'pos' : {
                            <?php
                                if( !empty( $map ) && is_array( $map ) && isset( $map['map']['pos']['lat'] ) && 
                                        isset( $map[ 'map' ][ 'pos' ][ 'lng' ] ) ){
                            ?>
                                    'lat' : <?php echo $map[ 'map' ][ 'pos' ][ 'lat' ] ?>,
                                    'lng' : <?php echo $map[ 'map' ][ 'pos' ][ 'lng' ] ?>
                            <?php
                                }
                            ?>
                        },
                        'markers' : [
                            <?php
                                $i = 0;
                                
                                if( !empty( $map ) && is_array( $map ) && isset( $map[ 'markers' ] ) && !empty( $map[ 'markers' ] ) ){
                                    foreach( $map[ 'markers' ] as $index => $marker ){
                                        if( !empty( $marker ) && isset( $marker[ 'type' ] ) && isset( $marker[ 'icon' ] ) && isset( $marker[ 'shadow' ] ) && isset( $marker[ 'lat' ] ) && isset( $marker[ 'lng' ] ) ){
                                            if( $i >  0 ){
                                                echo " , { lat:" . $marker[ 'lat' ] . " , lng:" . $marker[ 'lng' ] . " , icon: '" . $marker[ 'type'] . "' , src: '" . $marker[ 'icon' ] . "' , shadow: '" . $marker[ 'shadow' ] . "' , id:" . $index . " }";
                                            }else{
                                                echo " { lat:" . $marker[ 'lat' ] . " , lng:" . $marker[ 'lng' ] . " , icon: '" . $marker[ 'type' ] . "' , src: '" . $marker[ 'icon' ] . "' , shadow: '" . $marker[ 'shadow' ] . "' , id:" . $index . " }";
                                                $i = 1;
                                            }
                                        }
                                    }
                                }
                            ?>
                        ],
                        'selectors' : {
                            'markers' : '.select-markers'
                        },
                        'postID' : <?php echo self::postID(); ?>
                    };
                    
                    jQuery(function(){
                        jQuery( map.vr.canvas ).gmap( map_args );
                    });
                    
                </script>
            <?php
        }
        
        public static function mapFrontEnd( $postID ){
            $resources = _core::method( '_resources' , 'get' );    
            
            if( $postID > 0 ){
                $customID = _attachment::getCustomIDByPostID( $postID );
            }else{
                $customID = -1;
            }
            
            if( !isset( $resources[ $customID ][ 'boxes' ][ 'map' ] ) ){
                return null;
            }
            
            $map = _meta::get( $postID , 'map' );
            ?>
                <script type="text/javascript">
                    
                    var map_args = {
                        
                         'map' : {
                            'mapTypeId' : google.maps.MapTypeId.ROADMAP
                            <?php
                                if( !empty( $map ) && is_array( $map ) && isset( $map[ 'map' ][ 'zoom' ] ) && (int)$map[ 'map' ][ 'zoom' ] > 0 ){
                            ?>
                                    , 'zoom' : <?php echo (int)$map[ 'map' ][ 'zoom' ]; ?>
                            <?php
                                }
                            ?>
                        },
                        'pos' : {
                            <?php
                                if( !empty( $map ) && is_array( $map ) && isset( $map['map']['pos']['lat'] ) && 
                                        isset( $map[ 'map' ][ 'pos' ][ 'lng' ] ) ){
                            ?>
                                    'lat' : <?php echo $map[ 'map' ][ 'pos' ][ 'lat' ] ?>,
                                    'lng' : <?php echo $map[ 'map' ][ 'pos' ][ 'lng' ] ?>
                            <?php
                                }
                            ?>
                        },
                        'markers' : [
                            <?php
                                $i = 0;
                                
                                if( !empty( $map ) && is_array( $map ) && isset( $map[ 'markers' ] ) && !empty( $map[ 'markers' ] ) ){
                                    foreach( $map[ 'markers' ] as $index => $marker ){
                                        $html = '';
                                        
                                        if( isset( $marker[ 'title' ] ) && !empty( $marker[ 'title' ] ) ){
                                            $html .= '<h4>' . addslashes( $marker[ 'title' ] ) . '</h4>';
                                        }
                                        
                                        if( isset( $marker[ 'telephone' ] ) && !empty( $marker[ 'telephone' ] ) ){
                                            $html .= '<strong>' . __( 'Tel' , _DEV_ ) . '</strong> : ' . addslashes( $marker[ 'telephone' ] ) . '<br />';
                                        }
                                        
                                        if( isset( $marker[ 'address' ] ) && !empty( $marker[ 'address' ] ) ){
                                            $html .= '<strong>' . __( 'Address' , _DEV_ ) . '</strong> : ' . addslashes(  $marker[ 'address' ] ) . '<br />' ;
                                        }
                                        
                                        if( isset( $marker[ 'description' ] ) && !empty( $marker[ 'description' ] ) ){
                                            $html .= '<p>' . addslashes(  $marker[ 'description' ] ) . '</p>';
                                        }
                                        
                                        if( !empty( $html ) ){
                                            $jhtml = " , html : '<div class=\"marker-info\">" . $html . "</div>'";                                        
                                        }else{
                                            $jhtml = " , html : null ";
                                        }
                                        
                                        if( !empty( $marker ) && isset( $marker[ 'type' ] ) && isset( $marker[ 'icon' ] ) && isset( $marker[ 'shadow' ] ) && isset( $marker[ 'lat' ] ) && isset( $marker[ 'lng' ] ) ){
                                            if( $i >  0 ){
                                                echo " , { lat:" . $marker[ 'lat' ] . " , lng:" . $marker[ 'lng' ] . " , icon: '" . $marker[ 'type'] . "' , src: '" . $marker[ 'icon' ] . "' , shadow: '" . $marker[ 'shadow' ] . "' , id:" . $index . "" . $jhtml . " }";
                                            }else{
                                                echo " { lat:" . $marker[ 'lat' ] . " , lng:" . $marker[ 'lng' ] . " , icon: '" . $marker[ 'type' ] . "' , src: '" . $marker[ 'icon' ] . "' , shadow: '" . $marker[ 'shadow' ] . "' , id:" . $index . "" . $jhtml . " }";
                                                $i = 1;
                                            }
                                        }
                                    }
                                }
                            ?>
                        ],
                        'selectors' : {
                            'markers' : '.select-markers'
                        },
                        'postID' : <?php echo self::postID(); ?>
                    };
                    
                    jQuery(function(){
                        jQuery( map.vr.canvas ).gmapFrontEnd( map_args );
                    });
                </script>
            <?php
        }
        
        /* end box init */
        
        public static function panel( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result  = '';
            if( isset( $resources[ $resource ] ) && $resource  > -1 ){
                $result .= '<h3>' . __( 'Custom meta box for : ' , _DEV_ ) . $resources[ $resource ]['stitle'] . '</h3>';
                $result .= '<div class="box-panel-list">';
                $result .= self::items( $resource );
                $result .= '</div>';
                $result .= '<div class="box-panel-action">';
                $result .= _fields::layout( array( 'type' => 'ln--button' , 'set' => '' , 'value' => __( 'Set active boxes' , _DEV_ ) , 'action' => self::action( $resource ) ) );
                $result .= '</div>';
            }

            return $result;
        }
        
        public static function items( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result  = '';
            foreach( _core::method( '_box' , 'get' , $resources[ $resource ]['type'] ) as $key => $box ){
                if( isset( $box[ 'nopanel' ] ) &&  $box[ 'nopanel' ] ){
                    continue;
                }
                $result .= self::item( $resource , $box , $key );
            }
            
            return $result;
        }
        
        public static function item( $resource , $box , $key ){
            $resources = _core::method( '_resources' , 'get' );
            
            $result = '';
            if( isset( $resources[ $resource ][ 'slug' ] ) && !empty( $resources[ $resource ][ 'slug' ] ) ){
                $result .= '<div class="resource">';
                if( isset( $resources[ $resource ]['boxes'][ $key ] ) ){
                    $checked = 'checked="checked"';
                }else{
                    $checked = '';
                }
                
                $result .= '<p><input type="checkbox" value="' . $key . '" id="' . $resources[ $resource ][ 'slug' ] . '-' . $key . '" ' . $checked . ' />';
                if( isset( self::$boxes[ $resources[ $resource ][ 'type' ] ][ $key ][ 'settings' ] ) ){
                    $result .= ' <a href="javascript:field.load(  box , \'editForm\' , [ \''.$resource.'\' , \'' .  $key .  '\' ] , \'.box-panel-action\' , \'.box-' . $key . '\' );">' . $box[ 'title' ] . '</a>';
                }else{
                    $result .= ' <label for="' . $resources[ $resource ][ 'slug' ] . '-' . $key . '">' . $box[ 'title' ];
                }
                $result .= '</p>';
                $result .= '</div>';
            }
            
            return $result;
        }
        
        public static function action( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result  = "box.r(\n";
            $result .= "\t'set',\n";
            $result .= "\t[\n";
            $result .= "\t\t" . $resource ." , \n";
            $result .= "\t\t[\n";
            $i = 0;
            foreach( _core::method( '_box' , 'get' , $resources[ $resource ][ 'type' ] ) as $key => $box ){
                if( $i == 0 ){
                    $result .= "\t\t\t" . "tools.v('input#" . $resources[ $resource ][ 'slug' ] . '-' . $key . "')";
                    $i = 1;
                }else{
                    $result .= ",\n" . "\t\t\t" . "tools.v('input#" . $resources[ $resource ][ 'slug' ] . '-' . $key . "')";
                }
                
            }
            $result .= "\n\t\t]\n";
            $result .= "\t]);";
            return $result;
        }
        
        public static function set( $resource , $keys ){
            $resources = _core::method( '_resources' , '_get' );
            
            if( !isset( $resources[ $resource ][ 'boxes' ] ) ){
                $resources[ $resource ][ 'boxes' ] = array();
            }
            
            foreach( _core::method( '_box' , 'get' , $resources[ $resource ]['type'] ) as $key => $box ){
                if( is_array( $keys ) && in_array( $key , $keys ) ){
                    if( !isset( $resources[ $resource ][ 'boxes' ][ $key ] ) ){
                        if( isset( self::$default[ $key ] )  ){
                            $resources[ $resource ][ 'boxes' ][ $key ] = self::$default[ $key ];
                        }else{
                            $resources[ $resource ][ 'boxes' ][ $key ] = array();
                        }
                    }
                }else{
                    unset( $resources[ $resource ][ 'boxes' ][ $key ] );
                }
            }
            
            update_option( _RES_ , $resources );
            
            return self::items( $resource );
        }
        
        /* edit Box posts-settings  */
        public static function edit( $resource , $box , $similar_use , $similar_default , $similar_number_full , $similar_number_sidebar , $similar_criteria , $likes_use , $social_use , $social_list_view , $social_single_default , $social_single_position , $author_box_use , $author_box , $source_use , $archive_use , $archive , $meta_use , $meta ){
            $resources = _core::method( '_resources' , '_get' );
            if( isset( $resources[ $resource ][ 'boxes' ][ $box ] ) ){
                $resources[ $resource ][ 'boxes' ][ $box ][ 'similar-use' ]                 = $similar_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'similar-default' ]             = $similar_default;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'similar-number-full' ]         = $similar_number_full;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'similar-number-sidebar' ]      = $similar_number_sidebar;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'similar-criteria' ]            = $similar_criteria;
                
                $resources[ $resource ][ 'boxes' ][ $box ][ 'likes-use' ]                   = $likes_use;
                
                $resources[ $resource ][ 'boxes' ][ $box ][ 'social-use' ]                  = $social_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'social-single-default' ]       = $social_single_default;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'social-single-position' ]      = $social_single_position;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'social-list-view' ]            = $social_list_view;
                
                $resources[ $resource ][ 'boxes' ][ $box ][ 'author-box-use' ]              = $author_box_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'author-box' ]                  = $author_box;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'source-use' ]                  = $source_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'archive-use' ]                 = $archive_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'archive' ]                     = $archive;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'meta-use' ]                    = $meta_use;
                $resources[ $resource ][ 'boxes' ][ $box ][ 'meta' ]                        = $meta;
                
                update_option( _RES_ , $resources );
            }else{
                $resources[ $resource ][ 'boxes' ][ $box ] = array(
                    'similar-use'               => $similar_use,
                    'similar-default'           => $similar_default,
                    'similar-number-full'       => $similar_number_full,
                    'similar-number-sidebar'    => $similar_number_sidebar,
                    'similar-criteria'          => $similar_criteria,

                    'likes-use'                 => _box::$default[ 'posts-settings' ][ 'likes-use' ],
                    'likes-limit'               => _box::$default[ 'posts-settings' ][ 'likes-limit' ],

                    'social-use'                => $social_use,
                    'social-single-default'     => $social_single_default,
                    'social-single-position'    => $social_single_position,
                    'social-list-view'          => $social_list_view,

                    'author-box-use'            => $author_box_use,
                    'author-box'                => $author_box,
                    'source-use'                => $source_use,
                    'archive-use'               => $archive_use,
                    'archive'                   => $archive,
                    'meta-use'                  => $meta_use,
                    'meta'                      => $meta
                );
                
                update_option( _RES_ , $resources );
            }
            
            return self::items( $resource );
        }
        
        /* edit Box posts-settings  */
        public static function editForm( $resource , $box ){
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            
            if( isset( self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'] ) ){
                /* special settings */
                /* post settings */
                if( $box == 'posts-settings' ){
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['likes-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'use-likes-settings';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 3 ][ 'classes' ] = 'use-likes-settings';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 5 ][ 'classes' ] = 'use-likes-settings';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 7 ][ 'classes' ] = 'use-likes-settings';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'use-likes-settings hidden'; 
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 3 ][ 'classes' ] = 'use-likes-settings hidden'; 
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 5 ][ 'classes' ] = 'use-likes-settings hidden'; 
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Likes Settings' , _DEV_ ) ][ 7 ][ 'classes' ] = 'use-likes-settings hidden'; 
                    }
                    
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['similar-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'similar-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 1 ][ 'classes' ] = 'similar-posts';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 2 ][ 'classes' ] = 'similar-posts';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 3 ][ 'classes' ] = 'similar-posts';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 4 ][ 'classes' ] = 'similar-posts';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 1 ][ 'classes' ] = 'similar-posts hidden'; 
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 2 ][ 'classes' ] = 'similar-posts hidden'; 
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 3 ][ 'classes' ] = 'similar-posts hidden';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ][ 4 ][ 'classes' ] = 'similar-posts hidden';
                    }
                    
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['archive-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'social-options';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 2 ][ 'classes' ] = 'social-options';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 3 ][ 'classes' ] = 'social-options';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'social-options hidden';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 2 ][ 'classes' ] = 'social-options hidden';
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Social Settings' , _DEV_ ) ][ 3 ][ 'classes' ] = 'social-options hidden';
                    }
                    
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['archive-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'author-box-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'author-box-options';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 1 ][ 'classes' ] = 'author-box-options hidden';
                    }
                    
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['archive-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'archive-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 4 ][ 'classes' ] = 'archive-options';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 4 ][ 'classes' ] = 'archive-options hidden';
                    }
                    
                    if( isset( $resources[ $resource ][ 'boxes' ][ 'posts-settings' ]['archive-use'] ) && $resources[ $resource ][ 'boxes' ][ 'posts-settings' ][ 'meta-use' ] == 'yes' ){
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 6 ][ 'classes' ] = 'meta-options';
                    }else{
                        self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Other Settings' , _DEV_ ) ][ 6 ][ 'classes' ] = 'meta-options hidden';
                    }
                    
                    if( !isset( $resources[ $resource ][ 'taxonomy' ] ) || empty( $resources[ $resource ][ 'taxonomy' ] ) ){
                        unset( self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'][ 'content' ][ __( 'Related Posts' , _DEV_ ) ] );
                    }
                }
                
                $result = _fields::layout( self::$boxes[ $resources[ $resource ][ 'type' ] ][ $box ]['settings'] );
            }
            return $result;
        }
        
        public static function postID(){
            if( isset( $_GET[ 'post' ] ) && (int) $_GET[ 'post' ] > 0 ){
                $result = (int)$_GET[ 'post' ];
            }else{
                $result = 0;
            }
            
            return $result;
        }
        
        public static function postType(){
            if( isset( $_GET[ 'post' ] ) && (int) $_GET[ 'post' ] > 0 ){
                $result = $_GET[ 'post' ];
                $post = get_post( $_GET[ 'post' ] );
                $result = $post -> post_type;
            }
            
            if( isset( $_GET[ 'post_type' ] ) && !empty( $_GET[ 'post_type' ] ) ){
                $result = $_GET[ 'post_type' ];
            }
            
            if( !isset( $result ) ){
                $result = 'post';
            }
            
            return $result;
        }
    }
?>