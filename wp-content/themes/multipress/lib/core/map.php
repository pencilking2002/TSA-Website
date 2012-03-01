<?php
    class _map{
        public static function load(){
            $method = isset( $_POST[ 'method' ] ) ? $_POST[ 'method' ] : exit;
            $args   = isset( $_POST[ 'args' ] ) ? $_POST[ 'args' ] : exit;
            $object = new _map();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_map' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_map' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function google_markers( $type , $second = '' ){
            $result = '';
            if( empty( $second ) ){
                $result .= '<div class="marker-items">';
                if( $type != 'marker' ){
                    for($i = ord('A'); $i <= ord('Z'); $i++ ){
                        if( $type == 'brown' && $i == ord('A') ){
                            continue;
                        }
                        if( $type == 'blue' ){
                            if( $i == ord('A') ){
                                continue;
                            }
                            $result  .= '<img class="select-markers" alt="' . $type .  ' '.chr( $i ) .'" src="'.  get_template_directory_uri() . '/lib/core/images/google/' . $type . '_' . strtolower( chr( $i ) ) . '.png" />';
                        }else{
                            $result  .= '<img class="select-markers" alt="' . $type .  ' '.chr( $i ) .'" src="http://maps.google.com/mapfiles/marker_' . $type . chr( $i ) . '.png" />';
                        }
                    }
                }else{
                    for($i = ord('A'); $i <= ord('Z'); $i++ ){
                        $result  .= '<img class="select-markers" alt="' . $type .  ' '.chr( $i ) .'" src="http://maps.google.com/mapfiles/' . $type . chr( $i ) . '.png" />';
                    }
                }
                $result .= '<div class="clear"></div>';
                $result .= '</div>';
            }
            
            return $result;
        }
        
        public static function markers( $postID ){
            $result  = '';
            $result .= '<div class="panel-markers mrecords-panel">';
            $result .= '<input type="hidden" class="select-markers shadow" value="' . get_template_directory_uri() . '/lib/core/images/google/shadow.png">';
            $result .= '<ul class="ln">';
            $result .= '<li><img class="select-markers" alt="green dot" src="http://maps.google.com/mapfiles/marker_green.png" />' . self::google_markers('green') . '</li>';
            $result .= '<li><img class="select-markers" alt="blue A" src="' . get_template_directory_uri() . '/lib/core/images/google/blue_a.png" />' . self::google_markers('blue') . '</li>';
            $result .= '<li><img class="select-markers" alt="purple dot" src="http://maps.google.com/mapfiles/marker_purple.png" />' . self::google_markers('purple') . '</li>';
            $result .= '<li><img class="select-markers" alt="brown A" src="http://maps.google.com/mapfiles/marker_brownA.png" />' . self::google_markers('brown') . '</li>';
            $result .= '<li><img class="select-markers" alt="standard" src="http://maps.google.com/mapfiles/marker.png" />' . self::google_markers('marker') . '</li>';
            $result .= '<li><img class="select-markers" alt="orange dot" src="http://maps.google.com/mapfiles/marker_orange.png" />' . self::google_markers('orange') . '</li>';
            $result .= '<li><img class="select-markers" alt="yellow dot" src="http://maps.google.com/mapfiles/marker_yellow.png" />' . self::google_markers('yellow') . '</li>';
            
            $result .= '<li><img class="select-markers" alt="white dot" src="http://maps.google.com/mapfiles/marker_white.png" />' . self::google_markers('white') . '</li>';
            $result .= '<li><img class="select-markers" alt="grey dot" src="http://maps.google.com/mapfiles/marker_grey.png" />' . self::google_markers('grey') . '</li>';
            $result .= '<li><img class="select-markers" alt="black dot" src="http://maps.google.com/mapfiles/marker_black.png" />' . self::google_markers('black') . '</li>';
            
             
            $result .= '</ul>';
            $result .= '</div>';
            return $result;
        }
        public static function panel( $postID ){
            $result  = '';
            $result .= '<div id="map_canvas"></div>';
            $result .= '<div class="panel-map-action"></div>';
            return $result;
        }
        
        public static function add( $postID , $lat , $lng , $type , $icon , $shadow ){
            $map = _meta::get( $postID , 'map' );
            $map['markers'][] = array(
                'lat' => $lat,
                'lng' => $lng,
                'type' => $type, 
                'icon' => $icon,
                'shadow' => $shadow,
                'title' => '',
                'telephone' => '',
                'address' => '',
                'description' => ''
            );
            
            _meta::set( $postID , 'map' , $map );
            $map_key = array_keys( $map[ 'markers' ] );
            return $map_key[ count( $map_key ) - 1 ];
        }
        
        public static function edit( $postID , $markerID , $args ){
            $map = _meta::get( $postID , 'map' );
            
            foreach( $args as $key => $value ){
                if( $key != 'zoom' && $key != 'pos' ){
                    $map['markers'][ $markerID ][ $key ] = $value;
                }else{
                    if( $key == 'zoom' ){
                        $map['map'][ $key ] = $value;
                    }
                    
                    if( $key == 'pos' ){
                        $map['map'][ $key ]['lat'] = $value['lat'];
                        $map['map'][ $key ]['lng'] = $value['lng'];
                    }
                }
            }
            _meta::set( $postID , 'map' , $map );
        }
        
        public static function remove( $postID , $markerID ){
            $map = _meta::get( $postID , 'map' );
            unset( $map['markers'][ $markerID ] );
            _meta::set( $postID , 'map' , $map );
            
            $resources = _core::method( '_resources' , 'get' );
            $customID = _attachment::getCustomIDByPostID( $postID );
            
            $map = _meta::get( self::postID() , 'map' );
            
            ?>
                map_args = {

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
                                    isset( $map[ 'map' ][ 'pos' ][ 'lng' ] ) && 
                                    (float)$map[ 'map' ][ 'pos' ][ 'lat' ] > 0 && 
                                    (float)$map[ 'map' ][ 'pos' ][ 'lng' ] > 0 ){
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
            <?php
        }
        
        public static function editForm( $postID , $markerID ){
            
            $map = _meta::get( $postID , 'map' );
            $marker  = $map[ 'markers' ][ $markerID ];
            
            $edit = array(
                'type' => 'box--edit-marker',
                'classes' => 'marker-editbox',
                'title' => __( 'Marker properties' , _DEV_ ),
                'content' => array(
                    __( 'Set Marker' , _DEV_ ) => array(
                        array(
                            'type' => 'stbox--text',
                            'label' => __( 'Set marker title' , _DEV_ ),
                            'value' => $marker[ 'title' ],
                            'set' => 'marker-title',
                        ),
                        array(
                            'type' => 'stbox--text',
                            'label' => __( 'Set marker telephone' , _DEV_ ),
                            'value' => $marker[ 'telephone' ],
                            'set' => 'marker-telephone',
                        ),
                        array(
                            'type' => 'stbox--text',
                            'label' => __( 'Set marker address' , _DEV_ ),
                            'value' => $marker[ 'address' ],
                            'set' => 'marker-address',
                        ),
                        array(
                            'type' => 'stbox--textarea',
                            'label' => __( 'Set marker description' , _DEV_ ),
                            'value' => $marker[ 'description' ],
                            'set' => 'marker-description',
                        ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "map.r( 'edit' , [ " . 
                                $postID . " , " . 
                                $markerID . " , " .
                                "{" . 
                                    "'title' : tools.v( 'input#marker-title' ),".
                                    "'telephone' : tools.v( 'input#marker-telephone' ),".
                                    "'address' : tools.v( 'input#marker-address' ),".
                                    "'description' : tools.v( 'textarea#marker-description' )".
                                "}]);field.h('.marker-editbox');"
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Del Marker' , _DEV_ ) , 'action' => "(function(){ if( confirm('" . __( 'Are you sure you want to delete this marker?' , _DEV_ ) . "')){ map.remove();map.r( 'remove' , [ " . $postID . " , " . $markerID . " ] );field.h('.marker-editbox'); } })();" , 'btnType' => 'secondary' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' ),
                    )
                )
            );
            return _fields::layout( $edit );
        }
        
        public function markerExists( $postID ){
            $map = _meta::get( $postID , 'map' );
            $result = false;
            if( !empty( $map ) && is_array( $map ) && isset( $map[ 'markers' ] ) && !empty( $map[ 'markers' ] ) ){
                if( count( $map[ 'markers' ] ) > 0 ){
                    $result = true;
                }
            }
            
            return $result;
        }
    }
?>