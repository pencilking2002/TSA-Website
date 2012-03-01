<?php
    class _attachment{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;

            $object = new _attachment();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_attachment' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_attachment' , $method ), $args );
                }
            }

            exit;
        }
        
        /* generate attachments boxes */
        /* parent attachments boxes */
        public static function addAttachment( $parentPostID , $currentPostID ){
            
            $parentCustomID  = self::getCustomIDByPostID( $parentPostID );
            $currentCustomID = self::getCustomIDByPostID( $currentPostID );
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $parentCustomID ][ 'slug' ] ) && isset( $resources[ $currentCustomID ][ 'slug' ] ) ){
                _meta::add( $parentPostID , $resources[ $parentCustomID ][ 'slug' ] . '-' . $resources[ $currentCustomID ][ 'slug' ] , $currentPostID );
                _meta::add( $currentPostID , $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] , $parentPostID );
            }
            
            return self::getAttachments( $currentPostID , $parentCustomID );
        }
        
        public static function delAttachment( $parentPostID , $currentPostID , $currentIndex ){
            $parentCustomID  = self::getCustomIDByPostID( $parentPostID );
            $currentCustomID = self::getCustomIDByPostID( $currentPostID );
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $parentCustomID ][ 'slug' ] ) && isset( $resources[ $currentCustomID ][ 'slug' ] ) ){
                $parentAttachments = _meta::get( $parentPostID ,  $resources[ $parentCustomID ][ 'slug' ] . '-' . $resources[ $currentCustomID ][ 'slug' ] );
                $parentIndex = array_keys( $parentAttachments , $currentPostID );
                _meta::remove( $parentPostID ,  $resources[ $parentCustomID ][ 'slug' ] . '-' . $resources[ $currentCustomID ][ 'slug' ] , $parentIndex[0] );
                _meta::remove( $currentPostID , $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] , $currentIndex );
            }
            
            return self::getAttachments( $currentPostID , $parentCustomID );
        }
        
        public static function getAttachments( $currentPostID , $parentCustomID ){
            $resources = _core::method( '_resources' , 'get' );
            $currentCustomID = self::getCustomIDByPostID( $currentPostID );
            $result = '';
            
            if( isset( $resources[ $parentCustomID ][ 'slug' ] ) && isset( $resources[ $currentCustomID ][ 'slug' ] ) ){
                $parents = _meta::get( $currentPostID , $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] );
                $result .= '<ul>';
                if( !empty( $parents ) ){
                    foreach( $parents as $currentIndex => $parentPostID ){
                        $parentPost = get_post( $parentPostID );
                        $result .= '<li>';
                        $result .= '<p><strong><a href="post.php?post=' . $parentPostID . '&action=edit" target="_blank">' . $parentPost -> post_title . '</a></strong></p>';
                        $result .= '<div class="item-info">';
                        $result .= '<span class="item-status">' . __( 'Status' , _DEV_ ) . ' - <b>' . $parentPost -> post_status . '</b></span>';
                        $result .= '<span class="item-action"><a href="javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete?' , _DEV_ ) . '\')) { attachment.r( \'delAttachment\' , [ ' . $parentPostID . ' , ' . $currentPostID . ' , ' . $currentIndex . ' ] , \'div.attachments-' . $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] . '\' ); } })();">' . __( 'Delete' , _DEV_ ) . '</a></span>';
                        $result .= '<div class="clear"></div>';
                        $result .= '</div>';
                        $result .= '</li>';
                    }
                }else{
                    $result .= '<li>';
                    $result .= '</li>';
                }
                $result .= '</ul>';
            }
            return $result;
        }
        
        public static function parentAttachmentsBox( $currentPost ){
            
            $resources = _core::method( '_resources' , 'get' );
            $currentCustomID = self::getCustomIDByPostID(  $currentPost -> ID );
            
            if( isset( $resources[ $currentCustomID ]['parent'] ) &&  $resources[ $currentCustomID ]['parent'] > -1 ){
                
                $parentCustomID = $resources[ $currentCustomID ]['parent'];
            
                $result  = '';
                $result .= '<div class="panel-attachments">';
                $result .= '<h3 class="title" index="' . $currentPost -> ID . '">' . $resources[ $parentCustomID ]['ptitle'] . '</h3>';
                $result .= '<div class="attachments-items attachments-' . $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] . '">';
                $result .= self::getAttachments( $currentPost -> ID , $parentCustomID );
                $result .= '</div>';
                $result .= '<div class="panel-filter">';
                $result .= self::getPostsByCustomID( self::getCustomParentID( $currentCustomID ) , $currentPost -> ID );
                $result .= '</div>';
                
                $result .= '<div class="panel-action">';
                $result .= _fields::layout( array(

                        'type' => 'sh--button' ,
                        'value'=> __( 'Attach' , _DEV_ ) . ' ' . $resources[ $currentCustomID ]['stitle'] ,
                        'action' => "attachment.r(
                            'addAttachment' ,
                            [
                                tools.v('#parent-post-id'),
                                " . $currentPost -> ID . "
                            ] ,
                            'div.attachments-" . $resources[ $currentCustomID ][ 'slug' ] . '-' . $resources[ $parentCustomID ][ 'slug' ] . "'
                        );" 
                    )
                );
                $result .= '</div>';
                $result .= '</div>';
            }
            
            echo $result;
        }
        
        public static function getCustomParentID( $currentCustomID ){
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $currentCustomID ]['parent'] ) ){
                if( $resources[ $currentCustomID ]['parent'] == "-1" ){
                    return $currentCustomID;
                }else{
                    
                    return self::getCustomParentID( $resources[ $currentCustomID ]['parent'] );
                }
            }else{
                return -1;
            }
        }
        
        public static function getPostsByCustomID( $customPostID , $currentPostID ){
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            if( isset( $resources[ $customPostID ] ) ){
                if( $resources[ self::getCustomIDByPostID( $currentPostID ) ]['parent'] ==  $customPostID ){
                    $set = 'parent-post-id';
                    $classes = '';
                }else{
                    $set = $resources[ $customPostID ][ 'slug' ];
                    $classes = 'parent-attachments';
                }
                
                $result = _fields::layout( array(
                        'label' => __( 'Select' , _DEV_ ) . ' ' . $resources[ $customPostID ][ 'stitle' ] . ' ( ' . __( 'required' , _DEV_ ) . ' ) ',
                        'type' => 'sh--search' ,
                        'set' =>  $set,
                        'query' => array(
                            'post_status' => 'publish' ,
                            'post_type' => $resources[ $customPostID ][ 'slug' ]
                        ),
                        'classes' => $classes,
                        'hint' => '<small>' . __( 'Start typing the ' , _DEV_ ) . "<strong>" . $resources[ $customPostID ][ 'stitle' ] . "</strong>" . __( ' title' , _DEV_ ) . '</small>'
                    )
                );
            }
            
            return $result;
        }
        
        public static function getNextCustomID( $customID , $currentCustomID ){
            $resources = _core::method( '_resources' , 'get' );
            if( isset( $resources[ $currentCustomID ]['parent'] ) ){
                if( (int)$resources[ $currentCustomID ]['parent'] == (int)$customID ){
                    return $currentCustomID;
                }else{
                    return self::getNextCustomID( $customID , (int)$resources[ $currentCustomID ]['parent'] );
                }
            }else{
                return -1;
            }
        }
        
        /* ajax interaction */
        public static function getNextPostsChildren( $postParentID , $currentPostID ){
            $nextCustomID = self::getNextCustomID( self::getCustomIDByPostID( $postParentID ) , self::getCustomIDByPostID( $currentPostID ) );
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            $customParentID = self::getCustomIDByPostID( $postParentID );
            if( isset( $resources[ $customParentID ] ) && isset( $resources[ $nextCustomID ] ) ){
                $postsChildren = _meta::get( $postParentID , $resources[ $customParentID ][ 'slug' ] . '-' . $resources[ $nextCustomID ][ 'slug' ] );
                if( !empty( $postsChildren ) ){
                    if( $resources[ self::getCustomIDByPostID( $currentPostID ) ][ 'parent' ] ==  $nextCustomID ){
                        $set = 'parent-post-id';
                        $classes = '';
                    }else{
                        $set = $resources[ $nextCustomID ][ 'slug' ];
                        $classes = 'parent-attachments';
                    }
                    $postsChildren = _tools::clean_array( $postsChildren );
                    if( !empty( $postsChildren ) ){
                        $result = _fields::layout( array(
                            'label' => __( 'Select' , _DEV_ ) . ' ' . $resources[ $nextCustomID ][ 'stitle' ] . ' ( ' . __( 'required' , _DEV_ ) . ' ) ',
                                'type' => 'sh--search' ,
                                'set' => $set ,
                                'query' => array( 
                                    'post_status' => 'publish' ,
                                    'post_type' => $resources[ $nextCustomID ][ 'slug' ],
                                    'posts_per_page' => -1,
                                    'post__in' => $postsChildren
                                ),
                                'classes' => $classes,
                                'hint' => '<small>' . __( 'Start typing the ' , _DEV_ ) . "<strong>" . $resources[ $nextCustomID ][ 'stitle' ] . "</strong>" . __( ' title' , _DEV_ ) . '</small>'
                            )
                        );
                    }else{
                        $result = _fields::layout( array(
                                'type' => 'sh--preview' ,
                                'content' => '<a href="edit.php?post_type=' . $resources[ $nextCustomID ][ 'slug' ] . '" target="_blank">'.__( 'Attach ' ) . ' ' . $resources[ $nextCustomID ][ 'ptitle' ] . __( ' to this ' , _DEV_ ) . $resources[ self::getCustomIDByPostID( $postParentID ) ][ 'stitle' ] . ' </a>' ,
                            )
                        );
                    }
                }else{
                    $result = _fields::layout( array(
                            'type' => 'sh--preview' ,
                            'content' => '<a href="edit.php?post_type=' . $resources[ $nextCustomID ][ 'slug' ] . '" target="_blank">'.__( 'Attach ' ) . ' ' . $resources[ $nextCustomID ][ 'ptitle' ] . __( ' to this ' , _DEV_ ) . $resources[ self::getCustomIDByPostID( $postParentID ) ][ 'stitle' ] . ' </a>' ,
                        )
                    );
                }
            }
            
            return $result;
        }
        
        public static function getCustomIDByPostID( $postID ){
            $post = get_post( $postID );
            $resources = _core::method( '_resources' , 'get' );
            $customID = -1;
            if( !empty( $resources ) ){
                foreach( $resources as $index => $resource ){
                    if( $resource['slug'] == $post -> post_type ){
                        $customID = (int)$index;
                        break;
                    }
                }
            }
            
            return $customID;
        }
        
        /* children attachments boxes */
        public static function childrenAttachmentsBox( $currentPost , $metabox ){
            $currentCustomID = $metabox['args'][0];
            $childrenCustomID = $metabox['args'][1];
            $resources = _core::method( '_resources' , 'get' );
            $result  = '';
            
            $result .= '<div class="panel-attachments">';
            $result .= '<h3 class="title">' . $resources[ $currentCustomID ]['stitle'] . ' ' . $resources[ $childrenCustomID ]['ptitle'] . '</h3>';
            $result .= '<div class="attachments-items attachments-' . $resources[ $currentCustomID ]['slug'] . '-' . $resources[ $childrenCustomID ]['slug'] . '">';
            $result .= self::getAttachments( $currentPost -> ID , $childrenCustomID );
            $result .= '</div>';
            
            
            
            /* check if exists posts  */
            if( _tools::exists_posts( array( 'post_type' => $resources[ $childrenCustomID ]['slug'] ) ) ){
                $result .= '<div class="panel-filter">';
                $result .= _fields::layout( 
                        array(
                            'label' => __( 'Select' , _DEV_ ) . ' ' . $resources[ $childrenCustomID  ][ 'stitle' ] . ' ( ' . __( 'required' , _DEV_ ) . ' ) ',
                            'type' => 'sh--search' , 
                            'set' => $resources[ $currentCustomID ]['slug'] . '-' . $resources[ $childrenCustomID ]['slug'] , 
                            'query' => array( 
                                'post_status' => 'publish' , 
                                'post_type' => $resources[ $childrenCustomID ]['slug'] 
                            ),
                            'hint' => '<small>' . __( 'Start typing the ' , _DEV_ ) . "<strong>" . $resources[ $childrenCustomID ]['stitle'] . "</strong>" . __( ' title' , _DEV_ ) . '</small>'
                        ) 
                );
                
                $result .= '</div>';
                $result .= '<div class="panel-action">';
                $result .= _fields::layout( 
                        array(
                            'type' => 'sh--button' ,
                            'value'=> __( 'Add' , _DEV_ ) . ' ' . $resources[ $childrenCustomID ]['stitle'] ,
                            'action' => "attachment.r(
                                'addAttachment' , 
                                [ 
                                    tools.v( '.generic-input.generic-search input#" . $resources[ $currentCustomID ]['slug'] . '-' . $resources[ $childrenCustomID ]['slug'] . "'),
                                    " . $currentPost -> ID . "
                                ] , 
                                'div.attachments-" . $resources[ $currentCustomID ]['slug'] . '-' . $resources[ $childrenCustomID ]['slug'] . "'
                            );" 
                        )
                );
                
                $result .= '<p><a href="post-new.php?post_type=' . $resources[ $childrenCustomID ]['slug'] . '">' . __( 'Add new' , _DEV_ ) . ' ' . $resources[ $childrenCustomID ]['stitle'] . ' </a></p>';
                $result .= '</div>';
            }else{
                $result .= '<div class="panel-action">';
                $result .= '<p><a href="post-new.php?post_type=' . $resources[ $childrenCustomID ]['slug'] . '">' . __( 'Add new' , _DEV_ ) . ' ' . $resources[ $childrenCustomID ]['stitle'] . ' </a></p>';
                $result .= '</div>';
            }
            
            $result .= '</div>';
            
            echo $result;
        }
    }
?>