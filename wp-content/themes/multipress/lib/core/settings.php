<?php
    class _settings{
        public static $default;
        public static $register;
        
        public static function getAll(){
            
            $result = array();
            if( is_array( _panel::$fields ) && !empty( _panel::$fields ) ){
                foreach( _panel::$fields as $page => $tabs ){
                    foreach( $tabs as $tab => $groups ){
                        foreach( $groups as $group => $sets ){
                            $result[ $page . '__' . $tab . '__' . $group ] = get_option( $page . '__' . $tab . '__' . $group );
                        }
                    }
                }
            }
            
            return $result;
        }
        
        public static function get( $page , $tab  , $group , $prefix = '' , $index = -1 ){
            
            $opt = get_option( $page . "__" . $tab . '__' . $group  );
            if( !empty( $opt ) ){
                if( isset( $opt[ $prefix ] ) && strlen( $prefix ) ){
                    if( isset( $opt[ $prefix ][ $index] ) && $index != -1 ){
                        return $opt[ $prefix ][ $index ];
                    }else{
                        if(  $index != -1 ){
                            if( isset( self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ] ) ){
                                return self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ];
                            }else{
                                return '';
                            }
                        }else{
                            return $opt[ $prefix ];
                        }
                    }
                }else{
                    if( strlen( $prefix ) ){
                        if( isset( self::$default[ $page ][ $tab ][ $group ][ $prefix ] ) ){
                            if( $index != -1 ){
                                if( isset( self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ] ) ){
                                    return self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ];
                                }else{
                                    return '';
                                }
                            }else{
                                return self::$default[ $page ][ $tab ][ $group ][ $prefix ];
                            }
                        }else{
                            return '';
                        }
                    }else{
                        return $opt;
                    }
                }
            }else{
                if( strlen( $prefix ) ){
                    if( isset( self::$default[ $page ][ $tab ][ $group ][ $prefix ] ) ){
                        if( $index != -1 ){
                            if( isset( self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ] ) ){
                                return self::$default[ $page ][ $tab ][ $group ][ $prefix ][ $index ];
                            }else{
                                return '';
                            }
                        }else{
                            return self::$default[ $page ][ $tab ][ $group ][ $prefix ];
                        }
                    }else{
                        return '';
                    }
                }else{
                    return self::$default[ $page ][ $tab ][ $group ];
                }
            }
        }
        
        public static function edit( $page , $tab , $group , $prefix , $value ){
            $opt = get_option( $page . "__" . $tab . '__' . $group  );
            
            if( !is_array( $opt ) ){
                $opt = array();
            }
            
            $opt[ $prefix ] = $value;
            
            update_option( $page . "__" . $tab . '__' . $group , $opt );
        }
        
        public static function logic( $page , $tab , $group , $prefix , $index = -1 ){
            $opt = self::get( $page , $tab , $group , $prefix , $index );
            
            if( $opt == 'yes' ){
                return true;
            }else{
                return false;
            }
        }
    }
?>