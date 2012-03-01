<?php
    class _resources {
        static $resources;
        public function search(){
            $query = isset( $_GET['params'] ) ? (array)json_decode( stripslashes( $_GET['params'] ) ) : exit;
            $query['s'] = isset( $_GET['query'] ) ? $_GET['query'] : exit;

            global $wp_query;
            $result = array();
            $result['query'] = $query['s'];

            $wp_query = new WP_Query( $query );

            if( $wp_query -> have_posts() ){
                foreach( $wp_query -> posts as $post ){
                    $result['suggestions'][] = $post -> post_title;
                    $result['data'][] =  $post -> ID;
                }
            }

            echo json_encode( $result );
            exit();
        }
        
        /* manager resources */
        public static function type(){
            $type = array(
                'general' => __( 'General' , _DEV_ ) , 
                'event' => __( 'Event' , _DEV_ ) , 
                'paper' => __( 'Paper' , _DEV_ ) , 
                'people' => __( 'People' , _DEV_ ) , 
                'location' => __( 'Location' , _DEV_ ) , 
            );
            
            return $type;
        }
        
        public static function fields(){
            $fields = array(
                'title' => 'title',
                'editor' => 'editor',
                'excerpt' => 'excerpt',
                'comments' => '',
                'thumbnail' => 'thumbnail',
                'menu' => 'menu'
            );
            
            return $fields;
        }
        
        public static function getFields( $fields ){
            $result = array();
            foreach( $fields as $key => $value ){
                if( strlen( $value ) ){
                    array_push( $result , $value );
                }
            }
            
            return $result;
        }
        
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _resources();         
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_resources' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_resources' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function breadcrumbs( $parent = -1 , $current = true ){
            $result = '';
            if( $parent == -1 ){
                if( $current ){
                    $result = '<li>' . __( "Custom Posts" , _DEV_ ) . '</li>';
                }else{
                    $result = '<li><a href="admin.php?page=' . $_GET['page'] . '&cpost=' . $parent . '">' . __( "Custom Posts" , _DEV_ ) . '</a></li>';
                }
            }else{
                $resources = _core::method( '_resources' , 'get' );
                $title = isset( $resources[ $parent ]['stitle'] ) ? $resources[ $parent ]['stitle'] : '&nbsp;';
                if( $current ){
                    $result = '<li>' . $title . '</li>';
                }else{
                    $result = '<li><a href="admin.php?page=' . $_GET['page'] . '&resource=' . $parent . '">' . $title . '</a></li>';
                    
                }
                
                $result = self::breadcrumbs( $resources[ $parent ]['parent']  , false ) . $result;
            }
            
            return $result;
        }
        
        public static function panel( $parent = -1){
            $resources  = _core::method( '_resources' , 'get' );
            $result  = '';
            $result .= '<ul class="resources-breadcrumbs">';
            $result .= self::breadcrumbs( $parent , true );
            $result .= '</ul>';
            $result .= '<div class="resources-container">';
            $result .= self::items( $parent , $_GET['page'] );
            $result .= '</div>';
            
            $result .= '<div class="resources-action">';
            
            $result .= self::addForm( $parent , $_GET['page'] );
            if( $parent > -1 ){
                $result .= '<a class="add" href="javascript:field.s(\'.resource-addbox\');">' . __( 'Add new child' , _DEV_ ) . '</a> ';
            }else{
                $result .= '<a class="add" href="javascript:field.s(\'.resource-addbox\');">' . __( 'Add new' , _DEV_ ) . '</a> ';
            }
			
            if( isset( $_COOKIE[ "resources_id" ] ) && isset( $_COOKIE[ "resources_ac" ] ) && ( $_COOKIE[ "resources_ac" ] == 'copy' || $_COOKIE[ "resources_ac" ] == 'move' ) && isset($resources[ $_COOKIE[ "resources_id" ] ]['parent']) && $resources[ $_COOKIE[ "resources_id" ] ]['parent'] != $parent ){
                if( isset( $_COOKIE[ "resources_ac" ] ) && $_COOKIE[ "resources_ac" ] == 'move' ){
                    if( !self::is_root( $_COOKIE[ "resources_id" ] , $parent ) ){
                        $result .= ' <a class="paste" href="javascript:res.paste( ' . $parent. ' , \'' . $_GET['page'] . '\');">' . __( 'Paste' , _DEV_ ) . '</a>';
                    }
                }else{
                    $result .= ' <a class="paste" href="javascript:res.paste( ' . $parent. ' , \'' . $_GET['page'] . '\');">' . __( 'Paste' , _DEV_ ) . '</a>';
                }
            }
            
            $result .= '</div>';
            
            return $result;
        }
        
        public static function items( $parent , $page ){
            $resources  = _core::method( '_resources' , 'get' );
            $result = '';
            foreach( $resources as $index => $resource ){
                if( isset( $resource[ 'nopanel' ] ) && $resource[ 'nopanel' ] ){
                    continue;
                }
                $result .= _core::method( '_resources' , 'item' , $parent  , $page , $resource , $index );
            }
            
            return $result;
        }
        
        public static function item( $parent , $page , $resource , $index ){
            $result = '';
            if( $resource['parent'] == $parent ){
                $result .= '<div class="resource">';
                $result .= self::actionbox( $index , $page );
                $title = isset( $resource['stitle'] ) ? $resource['stitle'] : '&nbsp;';
                $result .= '<p><a href="admin.php?page=' . $page . '&resource=' . $index . '">' . $title . '</a></p>';
                $result .= '</div>';
            }
            
            return $result;
        }
        
        public static function addForm( $parent , $page ){
            
            $add = array(
                'type' => 'box--add',
                'classes' => 'resource-addbox hidden',
                'title' => __( 'Add new custom post ' , _DEV_ ),
                'content' => array(
                    __('Add' , _DEV_ ) => array(
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (singular)' , _DEV_ ) , 'id' => 'resource_stitle'  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (plural)' , _DEV_ ) , 'id' => 'resource_ptitle'  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Slug' , _DEV_ ) , 'id' => 'resource_slug' , 'hint' => __( 'Slug is an unique identifier of this type of post, it is used in url to view single.php template.' , _DEV_ )  ),
                        array( 'type' => 'stbox--select' , 'label' => __( 'Type' , _DEV_ ) , 'values' => _core::method( '_resources' , 'type' )  , 'id' => 'resource_type'  ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="addbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "res.r( 'add' , [ " . $parent . " , '" . $page . "' , tools.v( '#resource_stitle' ) , tools.v( '#resource_ptitle' ) , tools.v( '#resource_slug' ) , tools.v( '#resource_type' ) ] );" 
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.resource-addbox');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    ),
                )
            );
            
            return _fields::layout( $add );
        }
        
        public static function editForm( $page , $resource ){
            $resources  = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $resource ]['fields' ] ) ){
                $fields = $resources[ $resource ]['fields' ];
            }else{
                $fields = _core::method( '_resources' , 'fields' );
            }
            
            $parent = $resources[ $resource ]['parent' ];
            
            $edit = array(
                'type' => 'box--edit',
                'classes' => 'resource-editbox' . $resource,
                'title' => __( 'Properties ' , _DEV_ ),
                'content' => array(
                    __('General' , _DEV_ ) => array(
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (singular)' , _DEV_ ) , 'value' => $resources[ $resource ]['stitle'] , 'id' => 'resource_stitle' . $resource  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (plural)' , _DEV_ ) , 'value' => $resources[ $resource ]['ptitle'] , 'id' => 'resource_ptitle' . $resource ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Slug' , _DEV_ ) , 'value' => $resources[ $resource ]['slug'] , 'id' => 'resource_slug' . $resource  , 'hint' => __( 'Slug is an unique identifier of this type of post, it is used in url to view single.php template.' , _DEV_ ) ),
                        array( 
                            'type' => 'stbox--select' , 'label' => __( 'Type' , _DEV_ ) , 'value' => $resources[ $resource ]['type'] , 'values' => _core::method( '_resources' , 'type' )  , 
                            'id' => 'resource_type' .$resource  
                        ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                        
                        /* este necesar de construit in mod automat numarul de parametrii trimisi */
                        /* trebuieste de adaugat un mesaj la salvarea */
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "res.r( 'edit' , [ " . 
                                $parent . " , '" . 
                                $page . "' , " . 
                                $resource . " , " . 
                                " tools.v( 'input#resource_stitle" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_ptitle" . $resource ."' ) , " .
                                " tools.v( 'input#resource_slug" . $resource ."' ) , " .
                                " tools.v( 'select#resource_type" . $resource ."' ) , " .
                                " tools.v( 'input#resource_title" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_editor" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_excerpt" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_comments" . $resource ."' ) , " .
                                " tools.v( 'input#resource_thumbnail" . $resource ."' ) , ".
                                " tools.v( 'input#resource_menu" . $resource ."' )]);"
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.resource-editbox" . $resource . "');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    ),
                    
                    __( 'Advanced' , _DEV_ ) => array(
                        /* sunt necesare si niste hinturi si o iconitsa cu help pentru a fi mai clar despre ce este vorba */
                        array( 'type' => 'nii--checkbox' , 'set' => 'title' , 'label' => __( 'Use title for this custom post' , _DEV_ ) , 'id' => 'resource_title' . $resource , 'cvalue' => $fields['title'] ), 
                        array( 'type' => 'nii--checkbox' , 'set' => 'editor' , 'label' => __( 'Use editor for this custom post' , _DEV_ ) , 'id' => 'resource_editor' . $resource , 'cvalue' => $fields['editor'] ), 
                        array( 'type' => 'nii--checkbox' , 'set' => 'excerpt' , 'label' => __( 'Use excerpt for this custom post' , _DEV_ ) , 'id' => 'resource_excerpt' . $resource , 'cvalue' => $fields['excerpt'] ), 
                        array( 'type' => 'nii--checkbox' , 'set' => 'comments' , 'label' => __( 'Use comments for this custom post' , _DEV_ ) , 'id' => 'resource_comments' . $resource , 'cvalue' => $fields['comments'] ),
                        array( 'type' => 'nii--checkbox' , 'set' => 'thumbnail' , 'label' => __( 'Use featured image for this custom post' , _DEV_ ) , 'id' => 'resource_thumbnail' . $resource , 'cvalue' => $fields['thumbnail'] , 'hint' => __( 'You can also enable lightbox effects for featured image' , _DEV_ )   ),
                        array( 'type' => 'nii--checkbox' , 'set' => 'menu' , 'label' => __( 'Use this item in header menu' , _DEV_ ) , 'id' => 'resource_menu' . $resource , 'cvalue' => $fields['menu'] ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "res.r( 'edit' , [ " . 
                                $parent . " , '" . 
                                $page . "' , " . 
                                $resource . " , " . 
                                " tools.v( 'input#resource_stitle" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_ptitle" . $resource ."' ) , " .
                                " tools.v( 'input#resource_slug" . $resource ."' ) , " .
                                " tools.v( 'select#resource_type" . $resource ."' ) , " .
                                " tools.v( 'input#resource_title" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_editor" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_excerpt" . $resource ."' ) , " . 
                                " tools.v( 'input#resource_comments" . $resource ."' ) , " .
                                " tools.v( 'input#resource_thumbnail" . $resource ."' ), ".
                                " tools.v( 'input#resource_menu" . $resource ."' ) ] );field.h('.resource-editbox" . $resource . "');"
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.resource-editbox" . $resource . "');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    )
                )
            );
            
            return _fields::layout( $edit );
        }
        
        public static function actionbox( $resource , $page ){
            $result  = '';
            $result .= '<div class="resource-actionbox">';
            $result .= '<ul>';
            $result .= '<li><a href="javascript:res.move( ' . $resource . ' )"> ' . __( 'Move' , _DEV_ ) . ' </a></li>';
            $result .= '<li><a href="javascript:res.copy( ' . $resource . ' );"> ' . __( 'Copy' , _DEV_ ) . ' </a></li>';
            $result .= '<li><a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete?' , _DEV_ ) . '\')) { res.r( \'remove\' , [ ' . $resource . ' , \'' . $page . '\' ]); } })();">' . __( 'Delete' , _DEV_ ) . '</a></li>';
            $result .= '<li><a href="javascript:field.load(  res , \'editForm\' , [ \''.$page.'\' , ' .  $resource .  ' ] , \'.resources-action\' , \'.resource-editbox' . $resource .  '\' );"> ' . __( 'Properties' , _DEV_ ) . ' </a></li>';
            $result .= '</ul>';
            $result .= '</div>';
            
            return $result;
        }
        
        /* Custom Post Actions */
        public static function get(){
            
            $resources = get_option( _RES_ );
            if( empty( $resources ) || !is_array( $resources ) ){
                $resources = array();
            }
            
            if( !empty( self::$resources ) && is_array( self::$resources ) ){
                foreach( self::$resources as $resource ){
                    if( !self::exists_slug( $resource[ 'slug' ] , $resources ) ){
                        $resources[] = $resource;
                    }
                }
            }
            return $resources;
        }
        
        public static function _get(){
            $resources = get_option( _RES_ );
            if( empty( $resources ) || !is_array( $resources ) ){
                $resources = array();
            }
            return $resources;
        }
        
        public static function exists_slug( $slug , $resources ){
            foreach( $resources as $resource ){
                if( isset( $resource[ 'slug' ] ) && $resource[ 'slug' ] == strtolower( str_replace( array( '   ' , '  ' , ' ' ) , '-' , $slug ) ) ){
                    return true;
                }
            }
            
            return false;
        }
        
        public static function import( $res ){
            $theme = unserialize( base64_decode( $res ) );
            
            if( is_array( $theme ) && !empty( $theme ) ){
                
                if( !empty( $theme[ 'res' ] ) && is_array( $theme[ 'res' ] ) ){
                    update_option( _RES_ , $theme[ 'res' ] );
                }
                
                if( !empty( $theme[ 'settings' ] ) && is_array( $theme[ 'settings' ] ) ){
                    foreach( $theme[ 'settings' ] as $ptg => $option ){
                        update_option( $ptg , $option );
                    }
                }
            }
            return $res;
        }
        
        public static function getSlugs( $withpost = false , $exclude = array() ){
            $resources_     = _core::method( '_resources' , 'get' );
            $resources      = array();
            $resources[]    = __( 'Select custom post type' , _DEV_ );
            if( $withpost ){
                $resources[ 'post' ] = __( 'Simple post' , _DEV_ );
            }
            
            foreach( $resources_ as $index => $resource ){
                if( !in_array( $resource[ 'slug'  ] , $exclude ) ){
                    $resources[ $resource[ 'slug'  ] ] = $resource[ 'stitle'  ];
                }
            }
            
            return $resources;
        }
        
        public static function getOnlySlugs( $withpost = false , $exclude = array() ){
            $resources_     = _core::method( '_resources' , 'get' );
            $resources      = array();
            if( $withpost ){
                $resources[] = 'post';
            }
            
            foreach( $resources_ as $index => $resource ){
                if( !in_array( $resource[ 'slug'  ] , $exclude ) ){
                    $resources[] = $resource[ 'slug'  ];
                }
            }
            
            return $resources;
        }
        
        public static function getResourceBySlug( $slug ){
            $resources  = _core::method( '_resources' , 'get' );
            $resource_  = -1;
            
            foreach( $resources as $index => $resource ){
                if( $resource[ 'slug'  ] == $slug ){
                    $resource_ = $index;
                }
            }
            
            return $resource_;
        }
        
        public static function add( $parent , $page , $stitle , $ptitle , $slug , $type ){
            $resources = _core::method( '_resources' , '_get' );
            $types = _core::method( '_resources' , 'type' );
            
            if( ( strlen( $stitle ) == 0 ) || ( strlen( $ptitle ) == 0 ) || (strlen( $slug ) == 0 ) || !isset( $types[ $type ] ) ){
                return 'error';
            }
            
            if( self::exists_slug( $slug , $resources ) ){
                return 'error';
            }
            _box::$default[ 'posts-settings' ];
            $resources[] = array(  
                'stitle' => $stitle,
                'ptitle' => $ptitle,
                'slug' => strtolower( str_replace( array( '   ' , '  ' , ' ' ) , '-' , $slug ) ),
                'type' => $type,
                'parent' => $parent,
                'fields' => _core::method( '_resources' , 'fields' ),
                'taxonomy' => array(),
                'boxes' => array(
                    'posts-settings' => _box::$default[ 'posts-settings' ],
                    'layout' => array(
                    )
                )
            );
            
            update_option( _RES_ ,  $resources );
            
            return self::items( $parent , $page );
        }
        
        public static function edit( $parent , $page , $resource , $stitle , $ptitle , $slug , $type , $title , $editor , $excerpt , $comments , $thumbnail , $menu ){
            $resources = _core::method( '_resources' , '_get' );
            $types = _core::method( '_resources' , 'type' );
            
            $old_slug = $resources[ $resource ][ 'slug' ];
            unset( $resources[ $resource ][ 'slug' ] );
            if( self::exists_slug( $slug , $resources ) ){
                return 'error';
            }
            
            if( isset( $resources[ $resource ][ 'fields' ] ) ){
                $fields = array(
                    'title' => $title,
                    'editor' => $editor,
                    'excerpt' => $excerpt,
                    'comments' => $comments,
                    'thumbnail' => $thumbnail,
                    'menu' => $menu,
                );
            }else{
                $fields = _core::method( '_resources' , 'fields' );
            }
            
            if( isset( $resources[ $resource ][ 'taxonomy' ] ) ){
                $tax = $resources[ $resource ][ 'taxonomy' ];
            }else{
                $tax = array();
            }
            
            if( ( strlen( $stitle ) == 0 ) || ( strlen( $ptitle ) == 0 ) || (strlen( $slug ) == 0 ) || !isset( $types[ $type ] ) || !isset( $resources[ $resource ] ) ){
                return 'error';
            }
            
            $resources[ $resource ][ 'stitle' ] = $stitle;
            $resources[ $resource ][ 'ptitle' ] = $ptitle;
            $resources[ $resource ][ 'slug' ] = strtolower( str_replace( array( '   ' , '  ' , ' ' ) , '-' , $slug ) );
            $resources[ $resource ][ 'type' ] = $type;
            $resources[ $resource ][ 'parent' ] = $resources[ $resource ]['parent'];
            $resources[ $resource ][ 'fields' ] = $fields;
            $resources[ $resource ][ 'taxonomy' ] = $tax;
            
            update_option( _RES_ ,  $resources );
            
            return self::items( $parent , $page );
        }
        
        public static function copy( $children , $parent , $page , $count ){

            $resources = _core::method( '_resources' , '_get' );
            
            if( $count == -1 ){
                $count = self::count( $children );
                if( $count == 0 ){
                    $count = 1;
                }
            }
            if( $count > 0 ){
                $count--;
                $resources = _core::method( '_resources' , '_get' );

                $resources[] = $resources[ $children ];
                $keys = array_keys($resources );

                $resources[ $keys[ count( $keys ) - 1 ] ][ 'parent' ] = $parent;
                $slug_side = explode( '_' ,  $resources[ $keys[ count( $keys ) - 1 ] ][ 'slug' ] );
                
                if( (int)$slug_side[ count($slug_side) - 1 ] > 0 ){
                    $nr = $slug_side[ count($slug_side) - 1 ] + 1;
                    unset( $slug_side[ count($slug_side) - 1 ] );
                    $slug = implode( '_' , $slug_side );
                    $resources[ $keys[ count( $keys ) - 1 ] ]['slug'] = self::getSlugName( $slug , $nr );
                }else{
                    $resources[ $keys[ count( $keys ) - 1 ] ]['slug'] .= '_1';
                }
                
                $last = $keys[ count( $keys ) - 1 ];

                update_option( _RES_ , $resources );

                foreach( $resources as $index => $resource ){
                    if( $resource['parent'] == $children ){
                        self::copy( $index , $last , $page , $count );
                    }
                }
            }
            
            return self::items( $parent , $page );
        }
        public static function getSlugName( $slug , $nr ){
            $resources = _core::method( '_resources' , 'get' );
            
            $result = '';
            
            foreach( $resources as $resource ){
                if( $resource[ 'slug' ] == $slug . '_' . $nr  ){
                    return self::getSlugName( $slug , $nr + 1 );
                    break;
                }
            }
            
            return $slug . '_' . $nr;
        }
        
        public static function count( $children ){
            $count = 0;
            $c = 0;
            $resources = _core::method( '_resources' , '_get' );
            foreach( $resources as $index => $resource ){
                if( $resource['parent'] == $children ){
                    $count++;
                    $c += self::count( $index );
                }
            }
            
            return $c + $count;
        }
        
        public static function is_root( $children , $parent ){
            $result = false;
            $resources = _core::method( '_resources' , 'get' );
            
            foreach( $resources as $index => $resource ){
                if( $resource['parent'] == $children || $children == $parent ){
                    if( $index == $parent ){
                        return true;
                        
                    }else{
                        $result = $result || self::is_root( $index , $parent );
                    }
                }
            }
            
            return $result;
        }
        
        public static function move( $children , $parent , $page ){
            $resources = _core::method( '_resources' , '_get' );
            
            $resources[ $children ]['parent'] = $parent;
            
            if( isset( $resources[ $children ]['widget'] ) ){
                foreach( $resources[ $children ]['widget'] as $index ){
                    foreach( $resources as $key => $res ){
                        if( isset( $res['widget'] ) && !empty( $res['widget'] ) &&  is_array( $res['widget'] ) && in_array(  $index , $res['widget'] ) ){
                            $k = $keys = array_search( $index , $res['widget']  );
                            if( is_array( $keys ) && !empty( $keys ) ){
                                foreach( $keys as $k ){
                                    unset($resources[ $key ]['widget'][ $k ] );
                                }
                            }else{
                                unset($resources[ $key ]['widget'][ $k ] );
                            }
                        }
                    }
                }
            }
            
            update_option( _RES_ , $resources );
            
            return self::items( $parent , $page );
        }
        
        public static function remove( $resource , $page ){
            $resources = _core::method( '_resources' , '_get' );
            $parent = $resources[ $resource ]['parent'];
            
            unset( $resources[ $resource ] );
            update_option( _RES_ ,  $resources );
            
            foreach( $resources as $index => $cp ){
                if( $cp['parent'] == $resource ){
                    self::remove( $index , $page );
                }
            }
            
            return self::items( $parent , $page );
        }
        
        public static function getCustomIdByPostType( $post_type ){
            $resources = _core::method( '_resources' , 'get' );
            
            $result = -1;
            
            if( !empty( $resources ) ){
                foreach( $resources as $index => $resource ){
                    if( isset( $resource[ 'slug' ] ) && $resource[ 'slug' ] == $post_type ){
                        $result = (int) $index;
                        break;
                    }
                }
            }
            
            return $result;
        }
    }
?>