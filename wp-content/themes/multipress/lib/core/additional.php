<?php
    class _additional{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _additional();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_additional' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_additional' , $method ), $args );
                }
            }

            exit;
        }
        
        public static function panel( $resource ){
            $result  = '<div class="panel-fields mrecords-panel">';
            $result .= self::items( $resource );
            $result .= '</div>';
            return $result;
        }
        
        public static function items( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            if( isset( $resources[ $resource ][ 'boxes' ][ 'additional' ] ) && !empty( $resources[ $resource ][ 'boxes' ][ 'additional' ] ) ){
                $result .= '<ul>';
                foreach( $resources[ $resource ][ 'boxes' ][ 'additional' ] as $index => $item ){
                    $result .= self::item( $resource , $index , $item );
                }
                $result .= '</ul>';
            }else{
                $result .= '<ul>';
                $result .= '<li><p>' . __( 'No additional fields found' , _DEV_ ) . '</p></li>';
                $result .= '</ul>';
            }
            
            return $result;
        }
        
        public static function item( $resource , $index , $item ){
            $result  = '<li>';
            $result .= '<div class="event-item record-item">';
            $result .= $item[ 'label' ];
            $result .= '<span class="item-info item-action">';
            $result .= '<a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete this additional field?' , _DEV_ ) . '\')) { additional.r( \'remove\' , [ ' . $resource . ' , \''.$index.'\'  ]); }})();">' . __( 'Delete' , _DEV_ ) . '</a>';
            $result .= '</span>';
            $result .= '</div>';
            $result .= '</li>';
            return $result;
        }
        
        public static function remove( $resource , $itemID ){
            $resources = _core::method( '_resources' , '_get' );
            
            if( !empty( $resources[ $resource ] ) ){
                unset( $resources[ $resource ][ 'boxes' ][ 'additional' ][ $itemID ] );
                update_option( _RES_ , $resources );
            }
            
            return self::items( $resource );
        }
        
        public static function add( $resource , $field_label , $field_type ){
            $resources = _core::method( '_resources' , '_get' );
            
            if( !empty( $field_label )  ){
                if( !empty( $resources[ $resource ] ) ){
                    $set = 'field_' . mktime() . rand( 100 , 999 );
                    $resources[ $resource ][ 'boxes' ][ 'additional' ][ $set ] = array( 
                        'type' =>  'st--' . $field_type ,
                        'set' => $set,
                        'label' => $field_label
                    );
                    update_option( _RES_ , $resources );
                }
            }else{
                return 'error';
            }
            
            return self::items( $resource );
        }
    }
?>