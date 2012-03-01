<?php
	class _text{
		function preview(  $page , $tab , $group , $prefix , $text ){
			$classes    = 'dinamic-text-preview-' . mktime() . '-' . rand( 1000 , 9999 ) ;
            $result     = '';
			if( empty( $page ) ){
				$family = isset( $_POST['family'] ) ? $_POST['family'] : exit;
				$size 	= isset( $_POST['size'] ) ? $_POST['size'] : exit;
				$weight = isset( $_POST['weight'] ) ? $_POST['weight'] : exit;
                //$color  = isset( '' ) ? '' : exit;
                //$islink = isset( '' ) ? '' : exit;
                
				$text 	= isset( $_POST['text'] ) ? $_POST['text'] : exit;
				$ajax	= true;
                
                
			}else{
				$ajax	= false;
			
            
                $result  = '<style>';
                if( strlen( self::family( $page , $tab , $group , $prefix ) ) > 0 ){
                    if( strlen( _load::$fonts[ str_replace( ' ' , '+' , self::family( $page , $tab , $group , $prefix ) ) ] ) ){
                        $result .= '@import url("' . _load::$fonts[ str_replace( ' ' , '+' , self::family( $page , $tab , $group , $prefix ) ) ] . '");';
                    }
                }

                $result .= "\n\t/* font style */\n";
                $result .= "\th3." . $classes . "{\n";
                
                if( strlen( self::family( $page , $tab , $group , $prefix ) ) ){
                    $result .= "\tfont-family : '" . self::family( $page , $tab , $group , $prefix ) . "', Helvetica,Arial,sans-serif !important;\n";
                }
                
                if( (int)self::size( $page , $tab , $group , $prefix ) > 0 ){
                    $result .= "\tfont-size : " . self::size( $page , $tab , $group , $prefix ) . "px !important;\n";
                }
                
                $result .= "\tfont-weight : " . self::weight( $page , $tab , $group , $prefix ) . " !important;\n";
                $result .= "\tcolor : " . self::color( $page , $tab , $group , $prefix ) . " !important;\n";
                if( !_settings::get( $page , $tab , $group , $prefix . '_font_link' ) ){
                    $result .= "\ttext-decoration : " . self::decoration( $page , $tab , $group , $prefix ) . " !important;\n";
                }
                if( (int)self::align( $page , $tab , $group , $prefix ) != 'left' ){
                    $result .= "\ttext-align : " . self::align( $page , $tab , $group , $prefix ) . " !important;\n";
                }
                
                if( (int)self::height( $page , $tab , $group , $prefix ) > 0 ){
                    $result .= "\tline-height : " . self::height( $page , $tab , $group , $prefix ) . "px !important;\n";
                }
                
                if( (int)self::margin_top( $page , $tab , $group , $prefix ) + 
                    (int)self::margin_right( $page , $tab , $group , $prefix ) + 
                    (int)self::margin_bottom( $page , $tab , $group , $prefix ) +
                    (int)self::margin_left( $page , $tab , $group , $prefix ) != 0 ){
                    
                    $result .= "\n\t/* spacing ( margin & padding ) */\n";
                    if( (int)self::margin_top( $page , $tab , $group , $prefix ) != 0 ){
                        $result .= "\tmargin-top: " . self::margin_top( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::margin_right( $page , $tab , $group , $prefix ) != 0 ){
                        $result .= "\tmargin-right: " . self::margin_right( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::margin_bottom( $page , $tab , $group , $prefix ) != 0 ){
                        $result .= "\tmargin-bottom: " . self::margin_bottom( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::margin_left( $page , $tab , $group , $prefix ) != 0 ){
                        $result .= "\tmargin-left: " . self::margin_left( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                }
                
                if( (int)self::padding_top( $page , $tab , $group , $prefix ) + 
                    (int)self::padding_right( $page , $tab , $group , $prefix ) + 
                    (int)self::padding_bottom( $page , $tab , $group , $prefix ) +
                    (int)self::padding_left( $page , $tab , $group , $prefix ) != 0 ){
                    
                    if( (int)self::padding_top( $page , $tab , $group , $prefix ) > 0 ){
                        $result .= "\tpadding-top: " . self::padding_top( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::padding_right( $page , $tab , $group , $prefix ) > 0 ){
                        $result .= "\tpadding-right: " . self::padding_right( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::padding_bottom( $page , $tab , $group , $prefix ) > 0 ){
                        $result .= "\tpadding-bottom: " . self::padding_bottom( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    if( (int)self::padding_left( $page , $tab , $group , $prefix ) > 0 ){
                        $result .= "\tpadding-left: " . self::padding_left( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                }

                $result .= "}\n";

                if( _settings::get( $page , $tab , $group , $prefix . '_font_link' ) ){
                    $result .= "\n/* link  */\n";
                    $result .= "\th3." . $classes . " a{\n";
                    $result .= "\n\t/* font style */\n";
                    if( strlen( self::family( $page , $tab , $group , $prefix ) ) ){
                        $result .= "\tfont-family : '" . self::family( $page , $tab , $group , $prefix ) . "',Helvetica,Arial,sans-serif !important;\n";
                    }
                    $result .= "\tfont-weight : " . self::weight( $page , $tab , $group , $prefix ) . " !important;\n";
                    $result .= "\tcolor : " . self::color( $page , $tab , $group , $prefix ) . " !important;\n";
                    $result .= "\ttext-decoration : " . self::decoration( $page , $tab , $group , $prefix ) . " !important;\n";
                    if( self::align( $page , $tab , $group , $prefix ) != 'left' ){
                        $result .= "\ttext-align : " . self::align( $page , $tab , $group , $prefix ) . " !important;\n";
                    }
                    if( self::height( $page , $tab , $group , $prefix ) > 0 ){
                        $result .= "\tline-height : " . self::height( $page , $tab , $group , $prefix ) . "px !important;\n";
                    }
                    $result .= "}\n";

                    $result .= "\n/* hover  */\n";
                    $result .= "\th3." . $classes . " a:hover{\n";
                    $result .= "\tcolor : " . self::hover_color( $page , $tab , $group , $prefix ) . " !important;\n";
                    $result .= "\ttext-decoration : " . self::hover_decoration( $page , $tab , $group , $prefix ) . " important;\n";
                    $result .= "}\n";
                }

                $result .= '</style>';

                if( _settings::get( $page , $tab , $group , $prefix . '_font_link' ) ){
                    $result .= '<h3 class="' . $classes . '"><a href="#">' . $text . '</a></h3>';
                }else{
                    $result .= '<h3 class="' . $classes . '">' . $text . '</h3>';
                }
            }
            
			if( $ajax ){
				echo $result;
				exit;
			}else{
				return $result;
			}
		}
		
		function fields( $page , $tab , $group , $prefix , $link , $classes = '' , $text = 'Test text for preview' , $default = array( 'Francois One' , 24 , 'normal' ) , $hover = array() ){

            /* from code */
            $default[0] = str_replace( ' ' , '+' , $default[0] );
			$family = strlen( _settings::get( $page , $tab , $group , $prefix . '_font_family' ) . '' ) ? _settings::get( $page , $tab , $group , $prefix . '_font_family' ) : $default[0];
			$size   = (int) _settings::get( $page , $tab , $group , $prefix . '_font_size' ) > 0  ? _settings::get( $page , $tab , $group , $prefix . '_font_size' ) : $default[1];
			$weight = strlen( _settings::get( $page , $tab , $group , $prefix . '_font_weight' ) . '' ) ? _settings::get( $page , $tab , $group , $prefix . '_font_weight' ) : $default[2];
			
            /* default value */
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_link']                 = $link;
			_settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_family']               = $default[0];
			_settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_size']                 = $default[1];
			_settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_weight']               = $default[2];
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_color']                = isset( $default[3] ) ? $default[3] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_decoration']           = isset( $default[4] ) ? $default[4] : 'none';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_align']                = isset( $default[5] ) ? $default[5] : 'none';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_height']               = isset( $default[6] ) ? $default[6] : $default[1];

            /* hover */
            if( $link ){
                _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_hover_color']          = isset( $hover[0] ) ? $hover[0] : ( isset( $default[3] ) ? $default[3] : '' );
                _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_hover_decoration']     = isset( $hover[1] ) ? $hover[1] : 'none';
            }

            /* margin */
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_top']           = isset( $default[7][0] ) ? $default[7][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_right']         = isset( $default[7][1] ) ? $default[7][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_bottom']        = isset( $default[7][2] ) ? $default[7][2] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_left']          = isset( $default[7][3] ) ? $default[7][3] : '';

            /* padding */
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_top']          = isset( $default[8][0] ) ? $default[8][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_right']        = isset( $default[8][1] ) ? $default[8][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_bottom']       = isset( $default[8][2] ) ? $default[8][2] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_left']         = isset( $default[8][3] ) ? $default[8][3] : '';

            /* border */
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_top_size']      = isset( $default[9][0][0] ) ? $default[9][0][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_top_style']     = isset( $default[9][0][1] ) ? $default[9][0][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_top_color']     = isset( $default[9][0][2] ) ? $default[9][0][2] : '';

            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_right_size']    = isset( $default[9][1][0] ) ? $default[9][1][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_right_style']   = isset( $default[9][1][1] ) ? $default[9][1][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_right_color']   = isset( $default[9][1][2] ) ? $default[9][1][2] : '';

            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_bottom_size']   = isset( $default[9][2][0] ) ? $default[9][2][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_bottom_style']  = isset( $default[9][2][1] ) ? $default[9][2][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_bottom_color']  = isset( $default[9][2][2] ) ? $default[9][2][2] : '';

            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_left_size']     = isset( $default[9][3][0] ) ? $default[9][3][0] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_left_style']    = isset( $default[9][3][1] ) ? $default[9][3][1] : '';
            _settings::$default[ $page ][ $tab ][ $group ][ $prefix . '_font_border_left_color']    = isset( $default[9][3][2] ) ? $default[9][3][2] : '';

            /* preview interface */
			$action = "act.preview( extra.val('#" . $prefix . "_font_family') , extra.val('#" . $prefix . "_font_size') , extra.val('#" . $prefix . "_font_weight') , '" . $text . "' ,'#" . $prefix . '_font_preview' . "' );";
            
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_text_preview']                  = array('type' => 'st--preview'  , 'label' => __( 'Preview Text' , _DEV_ ) , 'content' => self::preview( $page , $tab , $group , $prefix , $text ) , 'classes' => $classes , 'id' => $prefix . '_font_preview' );
            
			_panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_family']                   = array('type' => 'st--select' , 'label' => __( 'Font Family' , _DEV_ ) , 'values' => _load::fonts() , 'action' => $action  , 'id' => $prefix . '_font_family' , 'classes' => $classes );
			_panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_size']                     = array('type' => 'st--digit' , 'label' => __( 'Font Size (px)' , _DEV_ ) , 'action' => $action  , 'id' => $prefix . '_font_size' ,  'classes' => $classes );
			_panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_weight']                   = array('type' => 'st--select' , 'label' => __( 'Font Weight' , _DEV_ ) , 'values' => array( 'normal' => 'Normal' , 'bold' => 'Bold' ) , 'action' => $action  , 'id' => $prefix . '_font_weight' ,  'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_color']                    = array('type' => 'st--color-picker' , 'label' => __( 'Text color' , _DEV_ ) , 'classes' => $classes );
            
            if( $link && !empty( $hover ) ){
                _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_hover_color']          = array('type' => 'st--color-picker' , 'label' => __( 'Hover text color' , _DEV_ ) , 'classes' => $classes );
            }
            
            $decoration = array( 
                'none' => __( 'None decoration' , _DEV_ ),
                'underline' => __( 'Underline' , _DEV_ ),
                'overline' => __( 'Overline' , _DEV_ ),
                'line-through' => __( 'Strike' , _DEV_ ),
                'blink' => __( 'Blink' , _DEV_ )
            );
            
            $align = array(
                'none' => __( 'Align none' , _DEV_ ),
                'left' => __( 'Align left' , _DEV_ ),
                'right' => __( 'Align right' , _DEV_ ),
                'justify' => __( 'Align justify' , _DEV_ ),
                'center' => __( 'Align center' , _DEV_ ),
            );
            
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_decoration']               = array('type' => 'st--select' , 'label' => __( 'Text decoration' , _DEV_ ) , 'values' => $decoration , 'classes' => $classes );
            
            if( $link && !empty( $hover ) ){
                _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_hover_decoration']     = array('type' => 'st--select' , 'label' => __( 'Hover text decoration' , _DEV_ ) , 'values' => $decoration , 'classes' => $classes );
            }
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_align']                    = array('type' => 'st--select' , 'label' => __( 'Text align' , _DEV_ ) , 'values' => $align , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_height']                   = array('type' => 'st--digit' , 'label' => __( 'Text line height ( px )' , _DEV_ ) , 'classes' => $classes );
            
            /* margin */
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_top']               = array('type' => 'st--digit' , 'label' => __( 'Top margin ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_right']             = array('type' => 'st--digit' , 'label' => __( 'Right margin ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_bottom']            = array('type' => 'st--digit' , 'label' => __( 'Bottom margin ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_margin_left']              = array('type' => 'st--digit' , 'label' => __( 'Left margin ( px )' , _DEV_ ) , 'classes' => $classes );
            
            /* padding */
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_top']              = array('type' => 'st--digit' , 'label' => __( 'Top padding ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_right']            = array('type' => 'st--digit' , 'label' => __( 'Right padding ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_bottom']           = array('type' => 'st--digit' , 'label' => __( 'Bottom padding ( px )' , _DEV_ ) , 'classes' => $classes );
            _panel::$fields[ $page ][ $tab ][ $group ][ $prefix . '_font_padding_left']             = array('type' => 'st--digit' , 'label' => __( 'Left padding ( px )' , _DEV_ ) , 'classes' => $classes );
		}
		
		function family( $page , $tab , $group , $prefix ){
			return str_replace( '+' , ' ' , _settings::get( $page , $tab , $group , $prefix . '_font_family' ) );
		}

        function gfamily( $page , $tab , $group , $prefix ){
			return _settings::get( $page , $tab , $group , $prefix . '_font_family' );
		}

		function size( $page , $tab , $group , $prefix ){
			return _settings::get( $page , $tab , $group , $prefix . '_font_size' );
		}
		
		function weight( $page , $tab , $group , $prefix ){
			return _settings::get( $page , $tab , $group , $prefix . '_font_weight' );
		}

        function height( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_height' );
        }

        /* new */
        /* margin style */
        function margin_top( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_margin_top' );
        }

        function margin_right( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_margin_right' );
        }

        function margin_bottom( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_margin_bottom' );
        }

        function margin_left( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_margin_left' );
        }

        /* border style */
        function border_top( $page , $tab , $group , $prefix ){
            return    ( (int) _settings::get( $page , $tab , $group , $prefix . '_font_border_top_size' ) ) . 'px '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_top_style' ) . ' '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_top_color' );
        }

        function border_right( $page , $tab , $group , $prefix ){
            return    ( (int) _settings::get( $page , $tab , $group , $prefix . '_font_border_right_size' ) ) . 'px '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_right_style' ) . ' '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_right_color' );
        }

        function border_bottom( $page , $tab , $group , $prefix ){
            return    ( (int) _settings::get( $page , $tab , $group , $prefix . '_font_border_bottom_size' ) ) . 'px '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_bottom_style' ) . ' '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_bottom_color' );
        }

        function border_left( $page , $tab , $group , $prefix ){
            return    ( (int) _settings::get( $page , $tab , $group , $prefix . '_font_border_left_size' ) ) . 'px '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_left_style' ) . ' '
                            . _settings::get( $page , $tab , $group , $prefix . '_font_border_left_color' );
        }

        /* padding style */
        function padding_top( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_padding_top' );
        }

        function padding_right( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_padding_right' );
        }

        function padding_bottom( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_padding_bottom' );
        }

        function padding_left( $page , $tab , $group , $prefix ){
            return (int)_settings::get( $page , $tab , $group , $prefix . '_font_padding_left' );
        }

        function color( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_color' );
        }

        function hover_color( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_hover_color' );
        }

        function decoration( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_decoration' );
        }

        function hover_decoration( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_hover_decoration' );
        }

        function style( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_style' );
        }

        function align( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_align' );
        }

        function sensitivity( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_sensitivity' );
        }

        function letter_spacing ( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_letter_spacing' );
        }

        function first_letter( $page , $tab , $group , $prefix ){
            return _settings::get( $page , $tab , $group , $prefix . '_font_first_letter' );
        }
        
        function font_classes( $page , $tab , $group , $prefix ){
            return '.dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix;
        }

        function load_fonts( ){
            $fonts = array();
            echo "/*";
            echo "
         ________                                 _________________
        |   _____|  CosmoThemes Generic CSS      |_______    ______| 
        |  |                                             |  |
        |  |         _____    _____    ______    _____   |  |    _   _    _____    ______    _____    _____
        |  |        |     |  |  ___|  |      |  |     |  |  |   | |_| |  |  ___|  |      |  |  ___|  |  ___|
        |  |_____   |  |  |  |___  |  | |  | |  |  |  |  |  |   |  _  |  |  ___|  | |  | |  |  ___|  |___  |
        |________|  |_____|  |_____|  |_|__|_|  |_____|  |__|   |_| |_|  |_____|  |_|__|_|  |_____|  |_____|
                    
";
            echo "*/\n";
            echo "\n";
            foreach( _panel::$fields as $page => $tabs ){
                foreach( $tabs as $tab => $groups ){
                    foreach( $groups  as $group => $prefixes ){
                        foreach( $prefixes as $prefix => $field ){
                            if( (int)strlen( str_replace( '_font_family' , '' , $prefix ) ) < (int)strlen( $prefix ) ){
                                if( isset( _load::$fonts[ _settings::get( $page , $tab , $group , $prefix ) ] ) && !in_array( _settings::get( $page , $tab , $group , $prefix ) , $fonts ) ){
                                    $fonts[] = _settings::get( $page , $tab , $group , $prefix );
                                    echo "@import url('" . _load::$fonts[ _settings::get( $page , $tab , $group , $prefix ) ] . "');\n";
                                }
                            }
                        }
                    }
                }
            }
        }

        function generic_style(){
            echo "\n\n";
            foreach( _panel::$fields as $page => $tabs ){
                foreach( $tabs as $tab => $groups ){
                    foreach( $groups as $group => $prefixes ){
                        foreach( $prefixes as $prefix => $field ){
                            if( (int)strlen( str_replace( '_font_family' , '' , $prefix ) ) < (int)strlen( $prefix ) ){

                                echo "\n/* style for ." . $page  . "-" . $tab . '-' . $group . "-" . str_replace( '_font_family' , '' , $prefix ) . "*/\n";
                                $prefix = str_replace( '_font_family' , '' , $prefix );
                                echo self::font_classes( $page , $tab , $group , $prefix ) . "{\n";
                                echo "\n\t/* font style */\n";
                                
                                if( strlen( self::family( $page , $tab , $group , $prefix ) ) ){
                                    echo "\tfont-family : '" . self::family( $page , $tab , $group , $prefix ) . "',Helvetica, Arial ,sans-serif !important;\n";
                                }
                                
                                if( (int)self::size( $page , $tab , $group , $prefix ) > 0 ){
                                    echo "\tfont-size : " . self::size( $page , $tab , $group , $prefix ) . "px !important;\n";
                                }
                                
                                echo "\tfont-weight : " . self::weight( $page , $tab , $group , $prefix ) . " !important;\n";
                                echo "\tcolor : " . self::color( $page , $tab , $group , $prefix ) . " !important;\n";
                                if( !_settings::get( $page , $tab , $group , $prefix . '_font_link' ) ){
                                    echo "\ttext-decoration : " . self::decoration( $page , $tab , $group , $prefix ) . " !important;\n";
                                }
                                if( self::align( $page , $tab , $group , $prefix ) != 'left' ){
                                    echo "\ttext-align : " . self::align( $page , $tab , $group , $prefix ) . " !important;\n";
                                }
                                if( self::height( $page , $tab , $group , $prefix ) > 0 ){
                                    echo "\tline-height : " . self::height( $page , $tab , $group , $prefix ) . "px !important;\n";
                                }
                                
                                if( (int)self::margin_top( $page , $tab , $group , $prefix ) + 
                                    (int)self::margin_right( $page , $tab , $group , $prefix ) + 
                                    (int)self::margin_bottom( $page , $tab , $group , $prefix ) +
                                    (int)self::margin_left( $page , $tab , $group , $prefix ) != 0 ){

                                    echo "\n\t/* spacing ( margin & padding ) */\n";
                                    if( (int)self::margin_top( $page , $tab , $group , $prefix ) != 0 ){
                                        echo "\tmargin-top: " . self::margin_top( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::margin_right( $page , $tab , $group , $prefix ) != 0 ){
                                        echo "\tmargin-right: " . self::margin_right( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::margin_bottom( $page , $tab , $group , $prefix ) != 0 ){
                                        echo "\tmargin-bottom: " . self::margin_bottom( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::margin_left( $page , $tab , $group , $prefix ) != 0 ){
                                        echo "\tmargin-left: " . self::margin_left( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                }

                                if( (int)self::padding_top( $page , $tab , $group , $prefix ) + 
                                    (int)self::padding_right( $page , $tab , $group , $prefix ) + 
                                    (int)self::padding_bottom( $page , $tab , $group , $prefix ) +
                                    (int)self::padding_left( $page , $tab , $group , $prefix ) != 0 ){

                                    if( (int)self::padding_top( $page , $tab , $group , $prefix ) > 0 ){
                                        echo "\tpadding-top: " . self::padding_top( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::padding_right( $page , $tab , $group , $prefix ) > 0 ){
                                        echo "\tpadding-right: " . self::padding_right( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::padding_bottom( $page , $tab , $group , $prefix ) > 0 ){
                                        echo "\tpadding-bottom: " . self::padding_bottom( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                    if( (int)self::padding_left( $page , $tab , $group , $prefix ) > 0 ){
                                        echo "\tpadding-left: " . self::padding_left( $page , $tab , $group , $prefix ) . "px !important;\n";
                                    }
                                }
                                /*echo "\n\t/* border \n";
                                echo "\tborder-top: " . self::border_top( $page , $tab , $group , $prefix ) . ";\n";
                                echo "\tborder-right: " . self::border_right( $page , $tab , $group , $prefix ) . ";\n";
                                echo "\tborder-bottom: " . self::border_bottom( $page , $tab , $group , $prefix ) . ";\n";
                                echo "\tborder-left: " . self::border_left( $page , $tab , $group , $prefix ) . ";\n"; */

                                echo "}\n";

                                if( _settings::get( $page , $tab , $group , $prefix . '_font_link' ) ){
                                    echo "\n/* link  */\n";
                                    $prefix = str_replace( '_font_family' , '' , $prefix );
                                    echo self::font_classes( $page , $tab , $group , $prefix ) . " a{\n";
                                    echo "\n\t/* font style */\n";
                                    if( strlen( self::family( $page , $tab , $group , $prefix ) ) ){
                                        echo "\tfont-family : '" . self::family( $page , $tab , $group , $prefix ) . "',Helvetica,Arial,sans-serif  !important;\n";
                                    }
                                    echo "\tfont-weight : " . self::weight( $page , $tab , $group , $prefix ) . "  !important;\n";
                                    echo "\tcolor : " . self::color( $page , $tab , $group , $prefix ) . "  !important;\n";
                                    echo "\ttext-decoration : " . self::decoration( $page , $tab , $group , $prefix ) . "  !important;\n";
                                    
                                    if( self::align( $page , $tab , $group , $prefix ) != 'left' ){
                                        echo "\ttext-align : " . self::align( $page , $tab , $group , $prefix ) . "  !important;\n";
                                    }
                                    if( self::height( $page , $tab , $group , $prefix ) > 0 ){
                                        echo "\tline-height : " . self::height( $page , $tab , $group , $prefix ) . "px  !important;\n";
                                    }
                                    echo "}\n";

                                    echo "\n/* hover  */\n";
                                    $prefix = str_replace( '_font_family' , '' , $prefix );
                                    echo self::font_classes( $page , $tab , $group , $prefix ) . " a:hover{\n";
                                    echo "\tcolor : " . self::hover_color( $page , $tab , $group , $prefix ) . " !important;\n";
                                    echo "\ttext-decoration : " . self::hover_decoration( $page , $tab , $group , $prefix ) . " !important;\n";
                                    echo "}\n";
                                }
                            }
                        }
                    }
                }
            }
        }

        public static function content( $page , $tab , $group , $prefix , $element , $post , $tag , $classes = '' ){
            $result = '';
            if( $element == 'link_post_title' ){
                $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                $result .= '<a href="' . get_permalink( $post -> ID ) . '" title="' . __( 'Permalink to ' , _DEV_ ) . get_the_title( $post -> ID ) . '">' . get_the_title( $post -> ID ) . '</a>';
                $result .= '</'.$tag.'>';
            }

            if( $element == 'post_title' ){
                $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' .  $group . '-' . $prefix . ' ' . $classes . '">';
                $result .= get_the_title( $post -> ID );
                $result .= '</'.$tag.'>';
            }

            if( $element == 'content' ){
                if( !empty( $tag ) ){
                    $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                }else{
                    $result .= '<div class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                }
                ob_start();
                the_content();
                $result .= ob_get_clean();
                
                if( !empty( $tag ) ){
                    $result .= '</'.$tag.'>';
                }else{
                    $result .= '</div>';
                }
            }

            if( $element == 'excerpt' ){
                if( !empty( $tag ) ){
                    $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                }else{
                    $result .= '<div class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                }
                ob_start();
                the_excerpt();
                $result .= ob_get_clean();
                if( !empty( $tag ) ){
                    $result .= '</'.$tag.'>';
                }else{
                    $result .= '</div>';
                }
            }

            if( $element == 'text' ){
                $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                $result .= $post;
                $result .= '</'.$tag.'>';
            }

            if( $element == 'link_text' ){
                $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                $result .= '<a href="' . $post[0] . '" title="' . __( 'Permalink to ' , _DEV_ ) . $post[1] . '" >' . $post[1] . '</a>';
                $result .= '</'.$tag.'>';
            }

			if( $element == 'link_text_no_permalink' ){
                $result .= '<'.$tag.' class="dynamic-' . $page . '-' . $tab . '-' . $group . '-' . $prefix . ' ' . $classes . '">';
                $result .= '<a href="' . $post[0] . '"  >' . $post[1] . '</a>';
                $result .= '</'.$tag.'>';
            }
			
            return $result;
        }
	}
?>