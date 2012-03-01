<?php
    class _widgets{
        /*
         *  exista eroare la copierea modului din virf in radacina
         *  ex de jos : timpul de copiere a nodului [F] --> [I] va tinde la infinit
         */
        /*
         *  [A]( 1 , 2 , 3 , 4 , 5 )
         *   |--[B]
         *   |   |--[D]( 1 )
         *   |       |--[G]( 1 , 5 )
         *   |--[C]
         *       |--[E] ( 2 )
         *       |--[F] ( 3 )
         *           |--[H] ( 3 )
         *               |--[I] ( 3 )
         *               |--[J] ( 4 )
         * 
         *  array(
         *      'A' => array(
         *          'D' => array( 
         *              'G' 
         *          ),
         *          'E',
         *          'F' => array(
         *              'H' => array(
         *                  'I' ,
         *                  'J'
         *              )
         *          )
         *      )
         *  )
         *  A,B,C,D,E,F,G,H,I si J - vor fi slugurile posturilor.
         */
        public static function get(){
            $result = get_option( _WG_ ); /* widgets relation */
            
            if( is_array( $result )  && !empty( $result  ) ){
                return $result;
            }else{
                return array();
            }
        }
        
        public static function getParents( $resource , $result = array() ){
            $resources = _core::method( '_resources' , 'get' );
            if( isset( $resources[ $resource ] )  && is_array( $resources[ $resource ] )  && !empty( $resources[ $resource ] ) && isset( $resources[ $resource ]['parent'] ) &&  $resources[ $resource ]['parent'] > -1 ){
                $result[] = $resources[ $resource ]['parent'];
                return self::getParents( $resources[ $resource ]['parent'] , $result );
            }else{
                return $result;
            }
        }
        
        public static function add( $data ){
            $resources = _core::method( '_resources' , 'get' );
            foreach( $data as $index => $resource ){
                $resources[ $rtesource ]['widget'] = true;
            }
        }
		
		function count_widget($widget_name){
			/* this function will count how many times a widget is used (how many instances of a given widget we have) */
			$widgets = wp_get_sidebars_widgets();
			
			$widget_instances = 0;
			if(sizeof($widgets)){
				foreach($widgets as $key => $sidebar){ 
					if($key != 'wp_inactive_widgets' && is_array($sidebar) && sizeof($sidebar)){ 
						foreach($sidebar as $widg ){ 
							$sidebar_id = explode('-',$widg);
							if($sidebar_id[0] == $widget_name){
								$widget_instances ++;
							}
						}
					}
				}
			}
			
			return $widget_instances;
		} 
    }
?>