<?php
    class _mbox{
        public static function load( ){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _mbox();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_mbox' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_mbox' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function add( $postID , $metakey , $value ){
            _meta::add( $postID , $metakey , $value );
            return _core::method( '_' . $metakey  , 'items' , $postID );
        }
        
        public static function delete( $postID , $metakey , $index ){
            _meta::remove( $postID , $metakey, $index );
            return _core::method( '_' . $metakey  , 'items' , $postID );
        }
        
        public static function edit( $postID , $metakey , $value , $index ){
            _meta::edit( $postID , $metakey , $value , $index );
            return _core::method( '_' . $metakey  , 'items' , $postID );
        }
    }
?>