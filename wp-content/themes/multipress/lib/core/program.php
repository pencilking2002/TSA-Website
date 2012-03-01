<?php
    class _program{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _program();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_program' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_program' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function panel( $postID ){
            $result  = '';
            $result .= '<div class="panel-program mrecords-panel">';
            $result .= self::items( $postID );
            $result .= '</div>';
            $result .= '<div class="panel-program-addform mrecords-addform">';
            $result .= self::addForm( $postID );
            $result .= '</div>';
            return $result;
        }
        
        public static function items( $postID ){
        $program = _meta::get( $postID , 'program' );
            $result = '';
            if( !empty( $program ) ){
                $result .= '<ul>';
                foreach( $program as $index => $event ){
                    $result .= self::item( $postID , $index , $event );
                }
                $result .= '</ul>';
            }else{
                $result .= '<ul>';
                $result .= '<li><p>' . __( 'Program is empty' , _DEV_ ) . '</p></li>';
                $result .= '</ul>';
            }
            return $result;    
        }
        
        public static function item( $postID , $index , $event ){
            $result  = '<li>';
            $result .= '<div class="event-item record-item">';
            $result .= $event['event-title'];
            if( !empty( $event['event-description'] ) ){
                $result .= '<span class="item-info"><br />';
                $result .= $event['event-description'];
                $result .= '</span>';
            }
            $result .= '<span class="item-info item-action">';
            $result .= '<a class="edit" href="javascript:field.load(  program , \'editForm\' , [ \'' . $postID . '\' , ' .  $index .  ' ] , \'.panel-program-addform\' , \'.program-editbox\' );">' . __( 'Edit' , _DEV_ ) . '</a> | ';
            $result .= '<a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete?' , _DEV_ ) . '\')) { mbox.r( \'mbox_load\' , \'delete\' , [ ' . $postID . ' , \'program\' , ' . $index . ' ] , \'.panel-program\'  ) }})();">' . __( 'Delete' , _DEV_ ) . '</a>';
            $result .= '</span>';
            $result .= '</div>';
            $result .= '</li>';
            return $result;
        }
        
        public static function addForm( $postID ){
            
            /* start - start event */
            $result  = _fields::layout( array( 
                    'type' => 'cd--submit', 
                    'content' => '<div class="standard-generic-field">
                        <span class="generic-label">
                            <label>' . __( 'Starts at' , _DEV_ ) . '</label>
                        </span>'
                )
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::months(),
                    'set' => "event-start-month"
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( 31 , 1 , true ),
                    'set' => "event-start-day"
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( date('Y') + 10 , date('Y') ),
                    'set' => "event-start-year"
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' @ ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 23 , 0 , true ),
                    'set' => "event-start-hour"
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' : ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 59 , 0 , true ),
                    'set' => "event-start-min"
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit', 'content' => '</div>' ) );
            /* end - start event */
            
            /* start - end event */
            $result .= _fields::layout( array( 
                    'type' => 'cd--submit', 
                    'content' => '<div class="standard-generic-field">
                        <span class="generic-label">
                            <label>' . __( 'Ends at' , _DEV_ ) . '</label>
                        </span>'
                )
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::months(),
                    'set' => "event-end-month"
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( 31 , 1 , true ),
                    'set' => "event-end-day"
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( date('Y') + 10 , date('Y') ),
                    'set' => "event-end-year"
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' @ ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 23 , 0 , true ),
                    'set' => "event-end-hour"
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' : ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 59 , 0 , true ),
                    'set' => "event-end-min"
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit', 'content' => '</div>' ) );
            /* end - end event */
            
            $result .= _fields::layout( array(
                    'type' => 'st--text',
                    'label' => __( 'Title' , _DEV_ ),
                    'set' => 'event-title',
                )
            );
            $result  .= _fields::layout( array(
                    'type' => 'st--textarea',
                    'label' => __( 'Description' , _DEV_ ),
                    'set' => 'event-description',
                )
            );

            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="addmbox-submit-action">' ) );
            $result .= _fields::layout( array(
                    'type' => 'ln--button',
                    'value' => __( 'Add to Program' , _DEV_ ),
                    'btnType' => 'primary fl',
                    'action' => "mbox.r( 
                        'mbox_load' , 
                        'add' , 
                        [
                            tools.v('#post_ID') , 
                            'program' ,
                            {
                                'event-start-month' : tools.v('#event-start-month'),
                                'event-start-day' : tools.v('#event-start-day'),
                                'event-start-year' : tools.v('#event-start-year'),
                                'event-start-hour' : tools.v('#event-start-hour'),
                                'event-start-min' : tools.v('#event-start-min'),
                                
                                'event-end-month' : tools.v('#event-end-month'),
                                'event-end-day' : tools.v('#event-end-day'),
                                'event-end-year' : tools.v('#event-end-year'),
                                'event-end-hour' : tools.v('#event-end-hour'),
                                'event-end-min' : tools.v('#event-end-min'),
                                
                                'event-title' : tools.v('#event-title'),
                                'event-description' : tools.v('#event-description'),
                            }
                        ] , 
                        '.panel-program' , 
                        '.panel-program-addform'
                    )"
                )
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--mssg',
                    'value' => __( 'Successfully added' , _DEV_ ),
                    'classes' => 'result-mssg nf',
                    'iclasses' => 'success hidden'
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--mssg',
                    'value' => __( 'Error, try again' , _DEV_ ),
                    'classes' => 'result-mssg nf',
                    'iclasses' => 'error hidden'
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' ) );
            
            
            return $result;
        }
        
        public static function editForm( $postID , $index ){
            $program = _core::method( '_meta' , 'get' , $postID , 'program' , $index );
            $result  = _fields::layout( array( 
                    'type' => 'cd--submit', 
                    'content' => '<div class="standard-box-generic-field ">
                        <div class="generic-label">
                            <label>' . __( 'Start at' , _DEV_ ) . '</label>
                        </div><div class="generic-input">'
                )
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::months(),
                    'set' => "event-start-month" . $index,
                    'value' =>  $program[ 'event-start-month' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( 31 , 1 , true ),
                    'set' => "event-start-day" . $index,
                    'value' =>  $program[ 'event-start-day' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( date('Y') + 10 , date('Y') ),
                    'set' => "event-start-year" . $index,
                    'value' =>  $program[ 'event-start-year' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' @ ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 23 , 0 , true ),
                    'set' => "event-start-hour" . $index,
                    'value' =>  $program[ 'event-start-hour' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' : ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 59 , 0 , true ),
                    'set' => "event-start-min" . $index,
                    'value' =>  $program[ 'event-start-min' ]
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit', 'content' => '</div></div>' ) );
            /* end - start event */
            
            /* start - end event */
            $result .= _fields::layout( array( 
                    'type' => 'cd--submit', 
                    'content' => '<div class="standard-box-generic-field ">
                        <div class="generic-label">
                            <label>' . __( 'Ends at' , _DEV_ ) . '</label>
                        </div><div class="generic-input">'
                )
            );
            
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::months(),
                    'set' => "event-end-month" . $index,
                    'value' =>  $program[ 'event-end-month' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( 31 , 1 , true ),
                    'set' => "event-end-day" . $index,
                    'value' =>  $program[ 'event-end-day' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'type' => 'ln--select',
                    'values' => _tools::digit( date('Y') + 10 , date('Y') ),
                    'set' => "event-end-year" . $index,
                    'value' =>  $program[ 'event-end-year' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' @ ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 23 , 0 , true ),
                    'set' => "event-end-hour" . $index,
                    'value' =>  $program[ 'event-end-hour' ]
                ) 
            );
            $result .= _fields::layout( array(
                    'label' => ' : ',
                    'type' => 'ln--select',
                    'values' => _tools::digit( 59 , 0 , true ),
                    'set' => "event-end-min" . $index,
                    'value' =>  $program[ 'event-end-min' ]
                ) 
            );
            $result .= _fields::layout( array( 'type' => 'cd--submit', 'content' => '</div></div>' ) );
            
            
            
            $edit = array(
                'type' => 'box--edit',
                'classes' => 'program-editbox',
                'title' => __( 'Modify event' , _DEV_ ),
                'content' => array(
                    __( 'Program event' , _DEV_ ) => array(
                        array( 
                            'type' => 'cd--intervals',
                            'content' => $result
                        ),
                        array(
                            'type' => 'stbox--text',
                            'label' => __( 'Title'  , _DEV_ ),
                            'id' => 'event-title' . $index,
                            'value' => $program[ 'event-title' ]
                        ),
                        array(
                            'type' => 'stbox--textarea',
                            'label' => __( 'Description'  , _DEV_ ),
                            'id' => 'event-description' . $index,
                            'value' => $program[ 'event-description' ]
                        ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ),
                            'action' => "mbox.r( 'mbox_load' , 'edit' , [ " . 
                                $postID . " , 
                                'program' , 
                                { 
                                    'event-start-month' : tools.v('#event-start-month" . $index . "'),
                                    'event-start-day' : tools.v('#event-start-day" . $index . "'),
                                    'event-start-year' : tools.v('#event-start-year" . $index . "'),
                                    'event-start-min' : tools.v('#event-start-min" . $index . "'),
                                    'event-end-month' : tools.v('#event-end-month" . $index . "'),
                                    'event-end-day' : tools.v('#event-end-day" . $index . "'),
                                    'event-end-year' : tools.v('#event-end-year" . $index . "'),
                                    'event-end-hour' : tools.v('#event-end-hour" . $index . "'),
                                    'event-end-min' : tools.v('#event-end-min" . $index . "'),
                                    'event-title' : tools.v('#event-title" . $index . "'),
                                    'event-description' : tools.v('#event-description" . $index . "')
                                },
                                " . $index . "],
                                '.panel-program', 
                                '.panel-program-addform');field.h('.program-editbox');"
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.program-editbox');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully modify' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    )
                )
            );
            
            return _fields::layout( $edit );
        }
		
		public static function getPrgrammDate($post_id){
		/*this method will return the programm starrt and end date, as well as start hour*/
		
			$events = _meta::get( $post_id , 'program' );  
			//var_dump($events);
			$programm_period = array();
			if( count( $events ) && !empty( $events ) ){
				foreach( $events as $index => $event ){
					$mktm1 = mktime( 0 , 0 , 0 , (int)$event['event-start-month'] , (int)$event['event-start-day'], (int)$event['event-start-year'] );
					$mktm2 = mktime( (int)$event['event-start-hour'] , (int)$event['event-start-min'] , 0 , (int)$event['event-start-month'] , (int)$event['event-start-day'], (int)$event['event-start-year'] );
					$new_event[ $mktm1 ][ $mktm2 ] = $event;
				}
				
				ksort( $new_event ); //var_dump($new_event);
				if( isset( $day1 ) ){ unset( $day1 ); }
				if( isset( $day ) ){ unset( $day ); }
				if( isset( $day2 ) ){ unset( $day2 ); }
				if( isset( $d2 ) ){ unset( $d2 ); }
				if( isset( $m2 ) ){ unset( $m2 ); }
				if( isset( $y2 ) ){ unset( $y2 ); }
				if( isset( $f2 ) ){ unset( $f2 ); }
				if( isset( $j2 ) ){ unset( $j2 ); }

				$counter = 1;
				foreach( $new_event as $date => $events ){ 
					if($counter == 1){
						foreach($events as $event){ 
							$programm_period['start_hour'] = $event['event-start-hour'].':'.$event['event-start-min'];
							$start_date = mktime( 0 , 0 , 0 , (int)$event['event-start-month'] , (int)$event['event-start-day'], (int)$event['event-start-year'] );	
							$programm_period['start_date'] = date_i18n( get_option( 'date_format' ) , $start_date );
							
						}
					}
					
					if( !isset( $day1 ) ){
						switch( get_option( 'date_format' ) ){
							case 'j F, Y' : {
								$day1   = date_i18n( 'j F, ' , $date );
								$format = get_option( 'date_format' );
								break;
							}
							case 'Y/m/d' : {
								$day1   = date_i18n( '/m/d' , $date );
								$format = get_option( 'date_format' );
								break;
							}
							case 'm/d/Y' : {
								$day1   = date_i18n( 'm/d/' , $date );
								$format = get_option( 'date_format' );
								break;
							}
							case 'd/m/Y' : {
								$day1   = date_i18n( 'd/m/' , $date );
								$format = get_option( 'date_format' );
								break;
							}
							default : {
								$day1   = date_i18n( 'F j, ' , $date );
								$format = 'F j, Y';
								break;
							}
						}

						$day    = date_i18n( $format , $date );
						$j1     = date_i18n( 'j' , $date );
						$d1     = date_i18n( 'd' , $date );
						$m1     = date_i18n( 'm' , $date );
						$f1     = date_i18n( 'F' , $date );
						$y1     = date_i18n( 'Y' , $date );
					}else{
						$day2   = date_i18n( $format , $date );
						$j2     = date_i18n( 'j' , $date );
						$d2     = date_i18n( 'd' , $date );
						$m2     = date_i18n( 'm' , $date );
						$f2     = date_i18n( 'F' , $date );
						$y2     = date_i18n( 'Y' , $date );
					}
					$counter ++;
				}

				$format = 'F j, Y';
				if( isset( $day2  ) ){
					if( $d2 == $d1 && $m2 == $m1 && $y2 == $y1){
						$date_result = $day2;
					}elseif( $d2 != $d1 && $m2 == $m1 && $y2 == $y1 ){
						if( $format == 'j F, Y' ){
							$date_result =  $j1 . ' -' . $j2 . ' ' . $f1 . ', ' . $y1;
						}
						if( $format == 'F j, Y' ){
							$date_result =  $f1 . ' ' . $j1 . ' - ' . $j2 . ', ' . $y1;
						}
						if( $format == 'd/m/Y' ){
							$date_result =  $d1 . ' - ' . $d2 . ' /' . $m1 . '/' . $y1;
						}
						if( $format == 'm/d/Y' ){
							$date_result =  $m1 . '/ ' . $d1 . ' - ' . $d2 . ' /' . $y1;
						}
						if( $format == 'Y/m/d' ){
							$date_result = $y1 . '/' . $m1 . '/ ' . $d1 . '-' . $d2;
						}

					}elseif( $m2 != $m1 && $y2 == $y1 ){
						if( $format == 'j F, Y' ){
							$date_result =  $j1 . ' ' . $f1 . ' - ' . $j2 . ' ' . $f2 . ', ' . $y1;
						}
						if( $format == 'F j, Y' ){
							$date_result =  $f1 . ' ' . $j1 . ' - ' . $f2 . ' ' . $j2 . ', ' . $y1;
						}
						if( $format == 'd/m/Y' ){
							$date_result =  $d1 . '/' . $m1 . ' - ' . $d2 . '/' . $m2 . ' /' . $y1;
						}
						if( $format == 'm/d/Y' ){
							$date_result =  $m1 . '/' . $d1 . ' - ' . $m2 . '/' . $d2 . ' /' . $y1;
						}
						if( $format == 'Y/m/d' ){
							$date_result = $y1 . '/ ' . $m1 . '/' . $d1 . ' - ' . $m2 . '/' . $d2;
						}

					}else{
						$date_result =  $day . ' - ' .  $day2;
					}
				}else{
					$date_result =  $day;
				}

				$programm_period['start_end_date'] = $date_result;
			}
			
			return $programm_period;
		}
		
		public static function getPrgramm($post_id){
			$events = _meta::get( $post_id , 'program' );
			
			if( count( $events ) && !empty( $events ) ){
				$result = '';
                $show_more = false;

                $mn = _tools::months( );
                if( count( $events ) ){
                    foreach( $events as $index => $event ){ //var_dump($event);
                        $index = 0;
                        $mktm1 = mktime( 0 , 0 , 0 , (int)$event['event-start-month'] , (int)$event['event-start-day'], (int)$event['event-start-year'] );
                        $mktm2 = mktime( (int)$event['event-start-hour'] , (int)$event['event-start-min'] , $index , (int)$event['event-start-month'] , (int)$event['event-start-day'], (int)$event['event-start-year'] );
                        $new_event[ $mktm1 ][ $mktm2 ][] = $event;
                        
						$index++;
                    }
                    /* asc sort by d/m/y */
                    ksort( $new_event );
                    $result .= '<div class="program">';
                    $i = 0; 
                    foreach( $new_event as $date => $events ){
                        $day = __(date_i18n( get_option('date_format') , $date ));
                        $result .= '<div><span class="date">' . $day . '</span>' ; 
												$result .= '<ul>';
                        /* asc sort by h:m on this day */
                        ksort( $events );
                        $li_class = 'odd';
						$prev_iteration_time = '';
                        foreach( $events as $index => $e ){
                            foreach( $e as $event ){
                                $i++;
                                $result .= '<li class="'.$li_class.'">';
                                $start_time = $event['event-start-hour'] . ':' . $event['event-start-min']; 
                                $end_time = $event['event-end-hour'] . ':' . $event['event-end-min'];

                                $result .= '<span class="time">';
                                $result .= date(get_option( 'time_format' ),strtotime($start_time) ).' - '. date(get_option( 'time_format' ),strtotime($end_time) );
                                $prev_iteration_time = date(get_option( 'time_format' ),strtotime($start_time) ).' - '. date(get_option( 'time_format' ),strtotime($end_time) );
                                $result .= '</span>';

                                $result .= '<span class="event">' . $event['event-title'] . '</span>';
                                $result .= '<span class="event-desc">' . $event['event-description'] . '</span>';
                                $result .= '</li>';
                                if($li_class == 'odd'){
                                    $li_class = 'even';
                                }else{
                                    $li_class = 'odd';
                                }
                            }
                        }
                        $result .= '</ul>';
                        $result .= '</div>';
                    }
                    $result .= '</div>';
                }
				
				return $result;
			}
		}
    }
?>