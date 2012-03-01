<?php
    class _likes{
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
        
        public static function antiLoop(){
            $id = md5( md5( $_SERVER['HTTP_USER_AGENT'] ) );
            
            $time = mktime();

            $user = get_option('set_user_like');

            if( is_array( $user ) && array_key_exists( $id , $user ) ){
                if( (int) $user[ $id ] + 2  < (int) $time  ){
                    $user[ $id ]  = (int) $time;
                    update_option( 'set_user_like' , $user );
                    return false;
                }else{
                    $user[ $id ]  = (int) $time;
                    update_option( 'set_user_like' , $user );
                    return true;
                }
            }else{
                $user[ $id ]  = (int) $time;
                update_option( 'set_user_like' , $user );
                return true;
            }
        }
        
        public static function set( $post_id = 0 , $meta_type = 'like' ){
            
            if( $post_id == 0 ){
                $meta_type = isset( $_POST[ 'meta_type' ] ) ? $_POST[ 'meta_type' ] : 'like';
                $post_id = isset( $_POST[ 'postID' ] ) ? $_POST[ 'postID' ] : 0;
                $ajax = true;
            }
            
			if($meta_type == 'like'){
				$anti_meta_type = 'hate';
			}else{
				$anti_meta_type = 'like';
			}	
			/*actually it may be like or hate*/
			/*get the likes/hates for the current post*/
            $likes = _meta::get( $post_id , $meta_type );
			/*get the oposite values of the current action, for example if 'like'  is clicked, we count here the 'hates',
			and vice-versa	
			*/
			$anti_likes = _meta::get( $post_id , $anti_meta_type );
            
            if( empty( $anti_likes ) || !is_array( $anti_likes  ) ){
                $anti_likes = array();
            }
            
            if( empty( $likes ) || !is_array( $likes  ) ){
                $likes = array();
            }
			
            if( self::antiLoop() ){
				
				if($meta_type == 'like'){
					$response = array(
						'likes' => (int)count( $likes ),
						'hates' => (int)count( $anti_likes ),
						'like_percentage' => self::percentage( (int)count( $likes ), (int)count( $anti_likes ) ),
					); 
				}else{
					$response = array(
						'likes' => (int)count( $anti_likes  ),
						'hates' => (int)count( $likes ),
						'like_percentage' => self::percentage( (int)count( $anti_likes ), (int)count( $likes ) ),
					); 
				}	
				
                return json_encode($response);
            }

            $user       = true;
            $user_ip    = true;
			$anti_user   = '';
            $anti_user_ip = '';
						
            $ip     = $_SERVER['REMOTE_ADDR'];

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                /* likes/hates by user */
                foreach( $likes as  $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){  
						/* if this user already clicked this button for this post*/
                        $user   = false;
                        $user_ip = false;
                    }
                }
				
				foreach( $anti_likes as  $anti_like ){
                    if( isset( $anti_like['user_id'] ) && $anti_like['user_id'] == $user_id ){  
						/* if this user already clicked this button for this post*/
                        $anti_user   = $user_id;
                        $anti_user_ip = $ip;
                    }
                }
				
            }else{
                if( self::useLikes( $post_id ) ){
                    return '';
                }
                foreach( $likes as  $like ){
                    if( isset( $like['ip'] ) && ( $like['ip'] == $ip ) ){
						/* if a user from the same IP already clicked this button for this post*/
                        $user = false;
                        $user_ip = false;
                    }
                }
				
				foreach( $anti_likes as  $anti_like ){
                    if( isset( $anti_like['ip'] ) && ( $anti_like['ip'] == $ip ) ){
						/* if a user from the same IP already clicked this button for this post*/
                        $anti_user = $user_id;
                        $anti_user_ip = $ip;
                    }
                }
				
            }

            if( $user && $user_ip ){   
                /* add like */ 
                $likes[] = array( 'user_id' => $user_id , 'ip' => $ip );
                _meta::set( $post_id , 'nr_'.$meta_type , count( $likes ) );
                _meta::set( $post_id , $meta_type ,  $likes );
                $date = _meta::get( $post_id , 'hot_date' );
                if( empty( $date ) ){
                    if( ( count( $likes ) >= (int)self::minLikes( $post_id ) ) ){
                        _meta::set( $post_id , 'hot_date' , mktime() );
                    }
                }else{
                    if( ( count( $likes ) < (int)self::minLikes( $post_id ) ) ){
                        _meta::remove( $post_id , 'hot_date' );
                    }
                }
				
				/*now we have to check if this user previously  clicked on the other button,
					if he did, then we will decrease that value 
				*/ 
				if( strlen($anti_user)  && strlen($anti_user_ip)){ 
					/* delete the oposite value of the current action */
					if( $user_id > 0 ){
						foreach( $anti_likes as $index => $like ){
							if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
								unset( $anti_likes[ $index ] );
							}
						}
					}else{
						if( self::useLikes( $post_id ) ){
                            return '';
						}
						foreach( $anti_likes as $index => $like ){
							if( isset( $like['ip'] ) && isset( $like['user_id'] ) && ( $like['ip'] == $ip ) && ( $like['user_id'] == 0 ) ){
								unset( $anti_likes[ $index ] );
							}
						}
					}
					
					_meta::set( $post_id , $meta_type ,  $likes );
					_meta::set( $post_id , 'nr_' . $meta_type ,  count( $likes ) );
					
					_meta::set( $post_id , $anti_meta_type ,  $anti_likes );
					_meta::set( $post_id , 'nr_' . $anti_meta_type ,  count( $anti_likes ) );
					
					if( count( $likes ) < (int)self::minLikes( $post_id ) ){
                        _meta::remove( $post_id , 'hot_date' );
					}
				}
            }

            if( $ajax ){
				if($meta_type == 'like'){
					$response = array(
						'likes' => (int)count( $likes ),
						'hates' => (int)count( $anti_likes ),
						'like_percentage' => self::percentage( (int)count( $likes ), (int)count( $anti_likes ) ),
					); 
				}else{
					$response = array(
						'likes' => (int)count( $anti_likes  ),
						'hates' => (int)count( $likes ),
						'like_percentage' => self::percentage( (int)count( $anti_likes ), (int)count( $likes ) ),
					); 
				}	
                echo json_encode($response);
                exit();
            }
        }
        
        public static function percentage( $nr_likes = 0 , $nr_hates = 0 ){
            $total_nr_votes = $nr_likes + $nr_hates;
			if($total_nr_votes == 0){
				return 100;
			}else{
				$like_percentage = round((100 * $nr_likes ) / $total_nr_votes );
				return $like_percentage;
			}
        }
        
        public static function generate(){
            global $wp_query;
            
            $customID = isset( $_POST[ 'customID' ] ) ? $_POST[ 'customID' ] : exit;
            $paged = isset( $_POST[ 'page' ] ) ? $_POST[ 'page' ] : 1;
            
            $resources  = _core::method( '_resources' , '_get' );
            if( $paged == 1 ){
                if( isset( $resources[ $customID ] ) ){
                    $resources[ $customID ][ 'boxes' ][ 'posts-settings' ]['likes-use'] = 'yes';
                    update_option( _RES_ , $resources );
                }else{
                    if( _core::method( '_resources' , 'getCustomIdByPostType' , 'post' ) == $customID ){
                        _settings::edit( '_settings' , 'get' , 'settings' , 'blogging' , 'likes' , 'use' , 'yes' );
                    }
                }
            }
            
            $resources  = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $customID ] ) ){
                $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => $resources[ $customID ][ 'slug' ] , 'paged' => $paged ) );

                foreach( $wp_query -> posts as $post ){
                    $likes = array();
                    $ips = array();
                    $nr = rand( 60 , 200 );
                    while( count( $likes ) < $nr ){
                        $ip = rand( -255 , -100 ) .  '100'  . rand( -255 , -100 ) . rand( -255 , -100 );

                        $ips[ $ip ] = $ip;

                        if( count( $ips )  > count( $likes ) ){
                            $likes[] = array( 'user_id' => 0 , 'ip' => $ip );
                        }
                    }

                    _meta::set( $post -> ID , 'nr_like' , count( $likes ) );
                    _meta::set( $post -> ID , 'like' ,  $likes );
                    _meta::set( $post -> ID , 'hot_date' , mktime() );
                    
                    /*$hates = array();
                    $ips = array();
                    $nr = rand( 10 , 60 );
                    while( count( $hates ) < $nr ){
                        $ip = rand( -255 , -100 ) .  '200'  . rand( -255 , -100 ) . rand( -255 , -100 );

                        $ips[ $ip ] = $ip;

                        if( count( $ips )  > count( $hates ) ){
                            $hates[] = array( 'user_id' => 0 , 'ip' => $ip );
                        }
                    }
                    _meta::set( $post -> ID , 'nr_hate' , count( $hates ) );
                    _meta::set( $post -> ID , 'hate' ,  $hates );*/
                }

                if( $wp_query -> max_num_pages >= $paged ){
                    if( $wp_query -> max_num_pages == $paged ){
                        echo 0;
                    }else{
                        echo $paged + 1;
                    }
                }
            }
            
            exit();
        }
        
        public static function reset( ){
            global $wp_query;
            $customID = isset( $_POST[ 'customID' ] ) ? $_POST[ 'customID' ] : exit;
            $newLimit = isset( $_POST[ 'newLimit' ] ) ? $_POST[ 'newLimit' ] : exit;
            $paged = isset( $_POST[ 'page' ] ) ? $_POST[ 'page' ] : 1;
            
            $resources  = _core::method( '_resources' , '_get' );
            if( $paged == 1 ){
                if( isset( $resources[ $customID ] ) ){
                    $resources[ $customID ][ 'boxes' ][ 'posts-settings' ]['likes-use'] = 'yes';
                    $resources[ $customID ][ 'boxes' ][ 'posts-settings' ]['likes-limit'] = $newLimit;
                    update_option( _RES_ , $resources );
                }else{
                    if( _core::method( '_resources' , 'getCustomIdByPostType' , 'post' ) == $customID ){
                        _settings::edit( '_settings' , 'get' , 'settings' , 'blogging' , 'likes' , 'use' , 'yes' );
                        _settings::edit( '_settings' , 'get' , 'settings' , 'blogging' , 'likes' , 'limit' , $newLimit );
                    }
                }
            }
            
            
            
            $resources  = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $customID ] ) ){
                $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => $resources[ $customID ][ 'slug' ] , 'paged' => $paged ) );
                foreach( $wp_query -> posts as $post ){
                    $likes = _meta::get( $post -> ID , 'like' );
                    _meta::set( $post -> ID , 'nr_like' , count( $likes ) );
                    if( count( $likes ) < (int)$newLimit ){
                        delete_post_meta( $post -> ID, 'hot_date' );
                    }else{
                        if( (int)_meta::get( $post -> ID , 'hot_date' ) > 0 ){

                        }else{
                            _meta::set( $post -> ID , 'hot_date' , mktime() );
                        }
                    }
                }
                if( $wp_query -> max_num_pages >= $paged ){
                    if( $wp_query -> max_num_pages == $paged ){
                        echo 0;
                    }else{
                        echo $paged + 1;
                    }
                }
            }

            exit();
        }
        
        public static function remove(){
            global $wp_query;
            
            $customID = isset( $_POST[ 'customID' ] ) ? $_POST[ 'customID' ] : exit;
            $paged = isset( $_POST[ 'page' ] ) ? $_POST[ 'page' ] : 1;
            
            $resources  = _core::method( '_resources' , '_get' );
            
            if( $paged == 1 ){
                if( isset( $resources[ $customID ] ) ){
                    $resources[ $customID ][ 'boxes' ][ 'posts-settings' ]['likes-use'] = 'yes';
                    update_option( _RES_ , $resources );
                }else{
                    if( _core::method( '_resources' , 'getCustomIdByPostType' , 'post' ) == $customID ){
                        _settings::edit( '_settings' , 'get' , 'settings' , 'blogging' , 'likes' , 'use' , 'yes' );
                    }
                }
            }
            
            $resources  = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $customID ] ) ){
                $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => $resources[ $customID ][ 'slug' ] , 'paged' => $paged ) );
                foreach( $wp_query -> posts as $post ){
                    _meta::remove( $post -> ID , 'nr_like' );
                    _meta::remove( $post -> ID , 'like' );
                    _meta::remove( $post -> ID , 'hot_date' );
                    _meta::remove( $post -> ID , 'nr_hate' );
                    _meta::remove( $post -> ID , 'hate' );
                }
                if( $wp_query -> max_num_pages >= $paged ){
                    if( $wp_query -> max_num_pages == $paged ){
                        echo 0;
                    }else{
                        echo $paged + 1;
                    }
                }
            }

            exit();
        }
        
        public static function count( $post_id, $like_type = 'like' ){
            $result = _meta::get( $post_id , $like_type );
            return count( $result );
        }
        
        public static function contentHate( $postID , $short = true ){
            if( $short ){
                return '<li class="cosmo-love"><span class="ilove hate"><em onclick="javascript:likes.vote( jQuery( this ) , ' . $postID . ' , \'hate\' );" ><strong>' . (int)_meta::get( $postID , 'nr_hate' ) . '</strong></em></span></li>';
            }else{
                return '<li class="cosmo-love"><span class="ilove hate"><em onclick="javascript:likes.vote( jQuery( this ) , ' . $postID . ' , \'hate\' );"><strong>' . (int)_meta::get( $postID , 'nr_hate' ) . '</strong>&nbsp;&nbsp;' . __( 'dislike this' , _DEV_ ) . '</em></span></li>';
            }
        }
        
        public static function contentLike( $postID , $short = true ){
            if( $short ){
                return '<li class="cosmo-love"><span class="ilove like"><em onclick="javascript:likes.vote( jQuery( this ) , ' . $postID . ' , \'like\' );"><strong>' . (int)_meta::get( $postID , 'nr_like' ) . '</strong></em></span></li>';
            }else{
                return '<li class="cosmo-love"><span class="ilove like"><em onclick="javascript:likes.vote( jQuery( this ) , ' . $postID . ' , \'like\' );"><strong>' . (int)_meta::get( $postID , 'nr_like' ) . '</strong>&nbsp;&nbsp;' . __( 'like this' , _DEV_ ) . ' </em></span></li>';
            }
        }
        
        
        
        public static function isVoted( $post_id , $like_type ){
            $ip  = $_SERVER['REMOTE_ADDR'];

            //$likes = meta::get_meta( $post_id , 'like' );
			$likes = meta::get_meta( $post_id , $like_type );
            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                foreach( $likes as $like ){
                    if( isset( $like[ 'user_id' ] ) && $like[ 'user_id' ] == $user_id ){
                        return true;
                    }
                }
            }else{
                foreach( $likes as $like ){
                    if( isset( $like[ 'ip' ] ) && $like[ 'ip' ] == $ip ){
                        return true;
                    }
                }
            }

            return false;
        }
        
        public static function useLikes( $postID ){
            $customID = _attachment::getCustomIDByPostID( $postID );
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'use-likes' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'use-likes' ] == 'yes' ){
                return true;
            }else{
                return false;
            }
        }
        
        public static function minLikes( $postID ){
            $customID = _attachment::getCustomIDByPostID( $postID );
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'featured-limit' ] ) && (int)$resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'featured-limit' ] > 0 ){
                return $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'featured-limit' ];
            }else{
                return 0;
            }
        }
        
        public static function canVote( $post_id , $like_type ){
            $ip     = $_SERVER['REMOTE_ADDR'];

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( options::logic( 'general' , 'like_register' ) && $user_id == 0 ){
                return false;
            }

            if( $user_id == 0 ){
				$likes = _meta::get( $post_id , $like_type );
                foreach( $likes as $like ){
                    if( isset( $like[ 'user_id' ] ) && $like[ 'user_id' ] > 0  && $like[ 'ip' ] == $ip ){
                        return false;
                    }
                }
            }

            return true;
        }
    }
?>