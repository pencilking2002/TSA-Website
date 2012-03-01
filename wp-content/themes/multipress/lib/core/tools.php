<?php
    class _tools{
        public static function digit( $to , $from = 0 , $twodigit = false ){
            $result = array();
            for( $i = $from; $i < $to + 1; $i ++ ){
                if( $twodigit ){
                    $i = (string)$i;
                    if( strlen( $i ) == 1 ){
                        $i = '0' . $i;
                    }
                    $result[$i] = $i;
                }else{
                    $result[$i] = $i;
                }
            }

            return $result;
        }


        public static function months( ){
            $result = array(
                '01' =>  __( 'January' , _DEV_ ),
                '02' =>  __( 'February', _DEV_ ),
                '03' =>  __( 'March' , _DEV_ ),
                '04' =>  __( 'April', _DEV_ ),
                '05' =>  __( 'May', _DEV_ ),
                '06' =>  __( 'June', _DEV_ ),
                '07' =>  __( 'July', _DEV_ ),
                '08' =>  __( 'August', _DEV_ ),
                '09' =>  __( 'September', _DEV_ ),
                '10' =>  __( 'October', _DEV_ ),
                '11' =>  __( 'November', _DEV_ ),
                '12' =>  __( 'December', _DEV_ )
            );

            return $result;
        }

        public static function months_days( $month , $year  ){
            $days = date( 't' , mktime( 0 , 0 , 0 , $month, 0 , $year, 0 ) );
            return self::digit( $days , 1 , true );
        }

        public static function item_label( $item ){
            $item = basename( $item );
            $item = str_replace( '-' , ' ' , $item );
            return $item;
        }
        
        public static function exists_posts( $args ){
            $posts = get_posts( $args );
            if( count( $posts ) > 0 ){
                return true;
            }else{
                return false;
            }
        }
        
        public static function clean_array( array $array ){
            $result = array();
            if( !empty( $array ) ){
                foreach( (array) $array as $index => $value ){
                    array_push( $result , $value );
                }
            }
            
            return $result;
        }
        
        public static function currencies(){
            $result = array(
                'AUD' => __( 'Australian Dollar' , _DEV_ ) . ' (A $)',
                'CAD' => __( 'Canadian Dollar' , _DEV_ ) . ' (C $)',
                'EUR' => __( 'Euro' , _DEV_ ) . ' (€)',
                'GBP' => __( 'British Pound' , _DEV_ ) . ' (£)',
                'JPY' => __( 'Japanese' , _DEV_ ) . ' Yen (¥)',
                'USD' => __( 'U.S. Dollar' , _DEV_ ) . '  ($)',
                'NZD' => __( 'New Zealand Dollar' , _DEV_ ) . ' ($)',
                'CHF' => __( 'Swiss Franc' , _DEV_ ),
                'HKD' => __( 'Hong Kong Dollar' , _DEV_ ) . ' ($)',
                'SGD' => __( 'Singapore Dollar' , _DEV_ ) . ' ($)',
                'SEK' => __( 'Swedish Krona' , _DEV_ ),
                'DKK' => __( 'Danish Krone' , _DEV_ ),
                'PLN' => __( 'Polish Zloty' , _DEV_ ),
                'NOK' => __( 'Norwegian Krone' , _DEV_ ),
                'HUF' => __( 'Hungarian Forint' , _DEV_ ),
                'CZK' => __( 'Czech Koruna' , _DEV_ ),
                'ILS' => __( 'Israeli New Shekel' , _DEV_ ),
                'MXN' => __( 'Mexican Peso' , _DEV_ ),
                'BRL' => __( 'Brazilian Real (only for Brazilian members)' , _DEV_ ),
                'MYR' => __( 'Malaysian Ringgit (only for Malaysian members)' , _DEV_ ) ,
                'PHP' => __( 'Philippine Peso' , _DEV_ ),
                'TWD' => __( 'New Taiwan Dollar' , _DEV_ ),
                'THB' => __( 'Thai Baht' , _DEV_ ),
                'TRY' => __( 'Turkish Lira (only for Turkish members)' , _DEV_)
            );
        		
			return $result;
        }
        
        public static function role(){
            return array(
                10 => __( 'Administrator' , _DEV_ ) ,
                7 => __( 'Editor' , _DEV_ ) , 
                2 => __( 'Author' , _DEV_ ) , 
                1 => __( 'Contributor' , _DEV_  ) , 
                0 => __( 'Subscriber' , _DEV_ ), 
                '' => __( 'Subscriber' , _DEV_ )
            );
        }
    }
?>