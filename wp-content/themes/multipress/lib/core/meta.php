<?php
    class _meta{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;

            $object = new _meta();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_meta' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_meta' , $method ), $args );
                }
            }

            exit;
        }
        
        public static function set( $post_id , $metakey , $value ){
            update_post_meta( $post_id, $metakey , $value );
        }
        
        public static function get( $post_id , $metakey , $index = -1 ){
            $meta = get_post_meta( $post_id , $metakey , true );
            
            /* ( ? ) */
            if( $index  != -1 ){
                if( isset( $meta[ $index ] ) ){
                    $meta = $meta[ $index ];
                }else{
					$meta = null;
				}
            }
            
            return $meta;
        }
        
        public static function add( $post_id , $metakey , $value ){
            $meta = get_post_meta( $post_id , $metakey , true );
            
            if( is_array( $meta ) ){
                $meta[] = $value;
                update_post_meta( $post_id, $metakey , $meta );
            }else{
                $meta = array();
                $meta[] = $value;
                update_post_meta( $post_id, $metakey , $meta );
            }
        }
        
        public static function edit( $post_id , $metakey , $value , $index = -1 ){
            $meta = get_post_meta( $post_id , $metakey , true );
            
            if( is_array( $meta ) && $index != -1 && isset( $meta[ $index ] ) ){
                $meta[ $index ] = $value;
                update_post_meta( $post_id, $metakey , $meta );
            }
        }

		public static function edit2( $post_id, $metakey, $index, $value ){
			$meta = get_post_meta( $post_id, $metakey, true );
			if(!is_array($meta) ){
				$meta=array();
			}

			$meta[ $index ] = $value;
            update_post_meta( $post_id, $metakey , $meta );
		}
        
        public static function remove( $post_id , $metakey , $index = -1 ){
            $meta = get_post_meta( $post_id , $metakey , true );
            
            if( is_array( $meta ) && $index != -1 && isset( $meta[ $index ] ) ){
                unset( $meta[ $index ] );
                update_post_meta( $post_id, $metakey , $meta );
            }else{
                delete_post_meta($post_id, $metakey );
            }
        }
        
        public static function logic( $post_id , $metakey , $index = -1 ){
            $meta = get_post_meta( $post_id , $metakey , true );
            
            if( is_array( $meta ) && $index != -1 && isset( $meta[ $index ] ) ){
                if( $meta[ $index ]  == 'yes' ){
                    return true;
                }else{
                    if( isset( $meta[ $index ] ) && !empty( $meta[ $index ] ) && ( $meta[ $index ] == 'yes' ) ){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                if( $meta  == 'yes' ){
                    return true;
                }else{
                    if( !empty( $meta ) && ( $meta == 'yes' ) ){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
?>