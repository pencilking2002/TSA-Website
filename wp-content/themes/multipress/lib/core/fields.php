<?php
    class _fields {

        public static $hints = array( 'hl' , 'hr' , 'hd' , 'hs' , 'h' );
        
        public static function hint( $content , $type , $classes = '' ){
            $result = '';
            if( in_array( $type , self::$hints ) ){
                switch( $type ){
                    case 'hl' : {
                        $result = '<div class="generic-hint fl ' . $classes . '">' . $content . '</div>';
                        break;
                    }
                    case 'hr' : {
                        $result = '<div class="generic-hint fr ' . $classes . '">' . $content . '</div>';
                        break;
                    }
                    case 'hd' : {
                        $result = '<div class="generic-hint ' . $classes . '">' . $content . '</div>';
                        break;
                    }
                    case 'hs' : {
                        $result = '<span class="generic-hint ' . $classes . '">' . $content . '</span>';
                        break;
                    }
                    case 'h' : {
                        $result =  $content;
                        break;
                    }
                }
            }
            return $result;
        }
        
        public static function help( $help ){
            $result  = '';
            $result .= '<div class="generic-help-box ' . $help[ 'class' ] . ' hidden">';
            $result .= '<div class="help-content">';
            
            if( isset( $help[ 'media' ] ) ){
                $result .= '<div class="generic-help-media">';
                if( $help['media']['type'] == 'image' ){
                    $result .= '<img src="' . $help['media']['src'] . '" width="300" />';
                }
                $result .= '</div>';
            }
            
            if( isset( $help[ 'title' ] ) && isset( $help[ 'desc' ] ) ){
                $result .= '<div class="generic-help-content">';

                if( isset( $help[ 'title' ] ) ){
                    $result .= '<h4>' . $help[ 'title' ] . '</h4>';
                }

                if( isset( $help[ 'desc' ] ) ){
                    $result .= '<p>' . $help[ 'desc' ] . '</p>';
                }

                $result .= '</div>';
            }
            
            $result .= '<div class="clear"></div>';
            $result .= '</div>';
            $result .= '<div class="generic-help-footer">';
            $result .= '<span class="generic-help-close">';
            $result .= '<a href="javascript:tools.h(\'.generic-help-box.' . $help[ 'class' ] . '\');">' .__( 'Close' , _DEV_ ) . '</a>';
            $result .= '</span>';
            $result .= '</div>';
            $result .= '</div>';
            $result .= '<span class="generic-help-icon">';
            $result .= '<a href="javascript:tools.h(\'.generic-help-box\');tools.s(\'.generic-help-box.' . $help[ 'class' ] . '\');"></a>';
            $result .= '</span>';
            
            return $result;
        }
        
        public static function layout( $field ){
            /* return field attributes */
            if( !is_array( $field ) || empty( $field ) ){
                return '';
            }

            foreach( $field as $attribut => $attribut_value ){
                $$attribut = $attribut_value;
            }

            /* if no specified type */
            if( !isset( $type ) ){
                return '';
            }
            
            /* field identity */
            $group          = isset( $group ) ? $group : '';
            $set            = isset( $set ) ? $set : '';
            $index          = isset( $index ) ? $index : '';

            /* return layout type from field type */
            $objects        = explode( '--' ,  $type );
            $layout         = $objects[ 0 ];
            
            if( count( $objects ) == 3 ){
                $hintType       = $objects[ 1 ];
                $objects[ 1 ]   = $objects[ 2 ];
            }else{
                $hintType       = 'hd';
            }

            /* generate attr - ids, label, hint , help */
            $inputID        = isset( $id ) && strlen( $id ) ? 'id="' . $id . '"' : '';
            $labelID        = isset( $id ) && strlen( $id ) ? 'for="' . $id . '"' : '';
            $fieldID        = isset( $fid ) && strlen( $fid ) ? 'id="' . $fid . '"' : '';
            
            $hintClass      = isset( $hclasses ) ? $hclasses : ''; 
            $fieldClass     = isset( $classes ) ? $classes : '' ;
            
            $label          = isset( $label ) ? '<label ' . $labelID . '>' . $label . '</label>' : '';            
            $hint           = isset( $hint ) ? self::hint( $hint , $hintType , $hintClass ) : '' ;
            $help           = isset( $help ) ? self::help( $help ) : '' ;

            
            
            
            /* reset field type */
            $field['type']  = $objects[ 1 ];
            $object         = str_replace( 'm-' , '' , $objects[ 1 ] );
            $result = '';

            switch( $layout ){
                /* code type layout */
                case 'cd' : {
                    $result .= $content;
                    break;
                }
                /* without layout  */
                case 'no'  :{
                    $result .= self::field( $field );
                    break;
                }
                /* not input type  */
                case 'ni' : {
                    $result .= '<div class="not-input-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-input generic-' . $object . '">' . $label . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                case 'nii' : {
                    $result .= '<div class="not-input-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-input generic-' . $object . '">' . self::field( $field ) . $label . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                /* standard layout  */
                case 'st' : {
                    $result .= '<div class="standard-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="generic-input generic-' . $object . '">' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    
                    break;
                }
                
                case 'sti' :{
                    $result .= '<div class="standard-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-input generic-' . $object . '">' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                
                case 'stbox' : {
                    $result .= '<div class="standard-box-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="generic-input generic-' . $object . '">' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                
                /* short layout */
                case 'sh' : {
                    $result .= '<div class="short-generic-field ' . $fieldClass . '">';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="generic-input generic-' . $object . '">' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '</div>';
                    break;
                }
                
                /* in line layout */
                case 'ln' : {
                    $result .= '<span class="inline-generic-field ' . $fieldClass . '">';
                    $result .= '<span class="generic-label">'. $label .'</span>';
                    $result .= '<span class="generic-input generic-' . $object . '">' . self::field( $field ) . $help . $hint . '</span>';
                    $result .= '</span>';
                    break;
                }
                
                /* box layout */
                case 'box' : {
                    
                    if( isset( $width ) ){
                        $style = 'style="width:'.$width.'px;"';
                    }else{
                        $style = '';
                    }
                    
                    $result .= '<div class="box-generic-field ' . $fieldClass . '" ' . $style . '>';
                    $result .= '<div class="box-action">';
                    if( isset( $title ) ){
                        $result .= '<span class="title">' . $title . '</span>';
                    }
                    $result .= '<span class="action"><a href="javascript:field.h(\'.'.str_replace( array( '   ' , '  ' , ' ' ) , '.' , str_replace( array( '   ' , '  ' , ' ' ) , '.' , trim( $fieldClass ) ) ).'\' );">x</a></span>';
                    $result .= '</div>';

                    if( isset( $content ) && !empty( $content ) && is_array( $content ) ){
                        if( count( $content ) > 1 ){
                            $i = 0;
                            $result .= '<ul class="box-panel-menu">';
                            foreach( $content as $tab => $fields ){
                                if( $i == 0 ){
                                    $result .= '<li class="tab'.$i.' current"><a href="javascript:void(0);">' . $tab . '</a></li>';
                                }else{
                                    $result .= '<li class="tab'.$i.' "><a href="javascript:void(0);">' . $tab . '</a></li>';
                                }
                                $i++;
                            }
                            $result .= '</ul>';
                        }else{

                        }
                        $i = 0;
                        foreach( $content as $tab => $fields ){
                            if( $i == 0 ){
                                $result .= '<div class="box-panel box-panel-tab'.$i.'">';
                            }else{
                                $result .= '<div class="box-panel box-panel-tab'.$i.' hidden">';
                            }
                            foreach( $fields as $field ){
                                $field['group']     = isset( $field['group'] ) ? $field['group'] : '';
                                $field['set']       = isset( $field['set'] ) ? $field['set'] : '';
                                $field['index']     = isset( $field['index'] ) ? $field['index'] : '';
                                $field['value']     = isset( $field['value'] ) ? $field['value'] : '';
                                $field['cvalue']    = isset( $field['cvalue'] ) ? $field['cvalue'] : '';
                                $result .= self::layout( $field );
                            }
                            $result .= '</div>';
                            $i++;
                        }
                        $result .= '</div>';
                        break;
                    }
                }
            }

            return $result;
        }

        public static function field( $field ){
            /* return field attributes */
            foreach( $field as $attribut => $attribut_value ){
                $$attribut  = $attribut_value;
            }
            
            $iclasses       = isset( $iclasses ) ? $iclasses  : '';
            $action         = isset( $action ) ? $action : '';
            
            $inputName      = '';
            $inputMName     = '';
            $inputNameID    = '';
            $inputClass     = 'class="' . $iclasses . '"';
            $uploadID       = '';
            $inputID        = '';
            $checkValue     = '';
            $ID             = '';
            $colorPiker     = '';
            $checkName      = '';
            $radioiconClass = '';
            $searchClass    = 'class="generic-value"';
            $buttonType     = isset( $btnType ) ? 'button-' . $btnType : 'button-primary';
            $buttonTypeUp   = isset( $btnType ) ? 'button-' . $btnType . ' button-upload' : 'button-primary button-upload';
            
            $buttonClass    = 'class="' . $buttonType . '"';
            $buttonClassUp  = 'class="' . $buttonTypeUp . '"';
            $containerClass = 'class="generic-' . $type . '"';
            $resultClass    = '';
            $resultAClass   = '';
            
            if( isset( $set ) && strlen( $set ) ){
                if( isset( $group ) && strlen( $group ) ){
                    $inputName      = 'name= "' . $group . '[' . $set . ']"';
                    $inputMName     = 'name= "' . $group . '[' . $set . '][]"';
                    $inputNameID    = 'name= "' . $group . '[' . $set . '-id]"';
                    $inputClass     = 'class="' . $group . ' ' . $set . ' ' . $iclasses . '"';
                    $digitClass     = 'class="digit ' . $group . ' ' . $set . ' ' . $iclasses . '"';
                    $buttonClass    = 'class="' . $buttonType . ' ' . $group . ' ' . $set . ' ' . $iclasses . '"';
                    $buttonClassUp  = 'class="' . $buttonTypeUp . ' ' . $group . ' ' . $set . ' ' . $iclasses . '"';
                    $searchClass    = 'class="generic-value ' . $group . ' ' . $set . ' ' . $iclasses . '"';
                    $containerClass = 'class="generic-' . $type . ' ' . $group . ' ' . $set . '"';
                    $radioiconClass = $group . ' ' . $iclasses . ' ' . $set;

                    if( isset( $id ) && strlen( $id ) ){
                        $inputID    = 'id="' . $id . '"';
                        $uploadID   = 'id="' . $id . '-id"';
                        $ID         = $id;
                    }else{
                        $inputID    = 'id="' . $group . '-' . $set . '"';
                        $uploadID   = 'id="' . $group . '-' . $set . '-id"';
                        $ID         = $group . '-' . $set;
                    }
                }else{
                    if( isset( $index ) && $index > 0 ){
                        if( isset( $id ) && strlen( $id ) ){
                            $inputID    = 'id="' . $id . $index . '"';
                            $uploadID   = 'id="' . $id . $index . '-id"';
                            $ID         = $id . $index;
                        }else{
                            $inputID    = 'id="' . $set . $index . '"';
                            $uploadID   = 'id="' . $set . $index . '-id"';
                            $ID         = $set . $index;
                        }
                    }else{
                        $inputName      = 'name= "' . $set . '"';
                        $inputMName     = 'name= "' . $set . '[]"';
                        $inputNameID    = 'name= "' . $set . '-id"';
                        $inputClass     = 'class="' . $set . ' ' . $iclasses . '"';
                        $digitClass     = 'class="digit ' . $set . ' ' . $iclasses . '"';
                        $buttonClass    = 'class="' . $buttonType . ' ' . $set . ' ' . $iclasses . '"';
                        $buttonClassUp  = 'class="' . $buttonTypeUp . ' ' . $set . ' ' . $iclasses . '"';
                        $searchClass    = 'class="generic-value ' . $set . ' ' . $iclasses . '"';
                        $containerClass = 'class="generic-' . $type . ' ' . $set . '"';
                        $radioiconClass = $iclasses . ' ' . $set;

                        if( isset( $id ) && strlen( $id ) ){
                            $inputID    = 'id="' . $id . '"';
                            $uploadID   = 'id="' . $id . '-id"';
                            $ID         = $id;
                        }else{
                            $inputID    = 'id="' . $set . '"';
                            $uploadID   = 'id="' . $set . '-id"';
                            $ID         = $set;
                        }
                    }
                }
                $colorPiker = $set;
                $checkValue = 'value="' . $set . '"';
                $checkName  = $set; 
            }else{
                if( isset( $index ) && $index > 0 ){
                    if( isset( $id ) && strlen( $id ) ){
                        $inputID    = 'id="' . $id . $index . '"';
                        $uploadID   = 'id="' . $id . $index . '-id"';
                        $ID         = $id . $index;
                    }else{
                        $id         = 'generic-' . rand( 100 , 999 );
                        $inputID    = 'id="' . $id . $index . '"';
                        $uploadID   = 'id="' . $id . $index . '-id"';
                        $ID         = $id . $index;
                    }
                }else{
                    if( isset( $id ) && strlen( $id ) ){
                        $inputID    = 'id="' . $id . '"';
                        $uploadID   = 'id="' . $id . '-id"';
                        $ID         = $id;
                    }else{
                        $id         = 'generic-' . rand( 100 , 999 );
                        $inputID    = 'id="' . $id . '"';
                        $uploadID   = 'id="' . $id . '-id"';
                        $ID         = $id;
                    }
                }
            }
            
            if( isset( $rClass ) ){
                $resultClass        = 'class="btn result ' . $rClass . '"';
                $resultAClass       = 'class="btn result a ' . $rClass . '"';
            }else{
                $resultClass        = 'class="btn result"';
                $resultAClass       = 'class="btn result a"';
            }

            $result = '';
            
            switch( $type  ){
                /* no input type */
                case 'mssg' : {
                    $result .= '<p ' . $inputClass . ' ' . $inputID . '><span></span>' . $value . '</p>';
                    break;
                }
                case 'title' : {
                    $result .= '<h3 ' . $inputClass . ' ' . $inputID . '>' . $title . '</h3>';
                    break;
                }
				case 'label' : {
					if(!isset($value)){
						$value = $cvalue;
					}
                    $result .= '<span ' . $inputClass . ' ' . $inputID . '>' . $value . '</span>';
					$result .= '<input type="hidden" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' value="' . $value . '" />';
                    break;
                }
                case 'hint' : {
                    $result .= $value;
                    break;
                }
                case 'preview' : {
                    $result .= $content;
                    break;
                }
                case 'icon' : {
                    $width  .= isset( $width ) ? ' width="' . $width . '" ' : '';
                    $heigt  .= isset( $heigt ) ? ' height="' . $height . '" ' : '';
                    $result .= '<div ' . $containerClass . ' ' . $inputID . ' ><img src="' . $src  . '" ' . $width . $height . ' ' . $inputClass . '/></div>';
                    break;
                }
                case 'color-picker' : {
                    
                    if( !isset( $value ) ){
                        if( isset( $cvalue ) ){
                            $value = $cvalue;
                        }else{
                            $value = '';
                        }
                    }
                    
                    $result .= '<input type="text" ' . $inputName . ' ' . $inputClass . ' id="pick-' . $colorPiker . '" op_name="' . $colorPiker . '" value="' .  $value . '"/>';
					$result .= '<a href="#" class="pickcolor hide-if-no-js" id="link-pick-' . $colorPiker . '"></a>';
					$result .= '<div id="color-panel-' . $colorPiker . '" class="generic-color-panel">a</div>';
                    break;
                }
                
                case 'resources-custom-posts' : {
                    if( isset( $_GET['resource'] ) && strlen( $_GET['resource'] ) > 0 ){
                        $parent = (int)$_GET['resource'];
                    }else{
                        $parent = -1;
                    }
                    $result .= _resources::panel( $parent );
                    $result .= _taxonomy::panel( $parent );
                    $result .= _box::panel( $parent );
                    break;
                }
                
                case 'resource-custom-sidebars' : {
                    $result .= _sidebar::panel();
                    break;
                }

                case 'post-upload' : {
                    $result .= '<a class="thickbox" href="media-upload.php?post_id=' . $post_id  . '&type=image&TB_iframe=1&width=640&height=381">' . $title . '</a>';
                    break;
                }

                case 'link' : {
                    $result .= '<a href="' . $url  . '">' . $title . '</a>';
                    break;
                }

                case 'callback' : {
                    $result .= '<span ' . $inputID . '> -- </span>';
                    break;
                }

                case 'radio-icon' : {
                    if( is_array( $value ) && !empty( $value ) ){
                        $path   = isset( $path ) ? $path : '';
                        $coll   = isset( $coll ) ? $coll : 5;
                        $i      = 0;
                        foreach( $value  as $k => $icon ){
                            if( $i == 0 ){
                                $result .= '<div>';
                            }
                            
                            if( isset( $ivalue ) &&  $ivalue == get_template_directory_uri() . '/lib/core/images/' . $path . $icon ){
                                $status = 'checked="checked"';
                                $sclasses = 'selected';
                            }else{
                                $status = '';
                                $sclasses = '';
                            }

                            $result .= '<div class="generic-input-radio-icon ' . $k . '  hidden">';
                            $result .= '<input type="radio" value="' . get_template_directory_uri() . '/lib/core/images/' . $path . $icon . '" class="' . $radioiconClass . ' ' . $k . '" ' . $inputName . ' ' . $inputID . ' ' . $status . '>';
                            $result .= '</div>';
                            
                            $action['selector'] = str_replace( array( '   ' , '  ' , ' ' ) , '.' , $radioiconClass );
                            $action['index'] = $k;
                            
                            $result .= '<img ' . self::action(  $action , 'radio-icon' ) . ' title="' . $icon . '" class="pattern-texture ' . $sclasses . ' ' . $radioiconClass . ' ' . $k . '" alt="' . $icon . '" src="' . get_template_directory_uri() . '/lib/core/images/' . $path . $icon . '" />';
                            
                            $i++;
                            if( $i % $coll == 0 ){
                                $i = 0;
                                $result .='<div class="clear"></div></div>';
                            }
                        }

                        if( $i % $coll != 0){
                            $result .='<div class="clear"></div></div>';
                        }
                    }
                    break;
                }
                
                case 'logic-radio' : {
                    if( isset( $value ) && $value == 'yes' ){
                        $status1 = 'checked="checked"';
                        $status2 = '';
                    }else{
                        if( isset( $value ) &&  $value == 'no' ){
                            $status1 = '';
                            $status2 = 'checked="checked"';
                        }else{
                            if( isset( $cvalue ) ){
                                if( $cvalue == 'yes' ){
                                    $status1 = 'checked="checked"';
                                    $status2 = '';
                                }else{
                                    $status1 = '';
                                    $status2 = 'checked="checked"';
                                }
                            }else{
                                $status1 = '';
                                $status2 = 'checked="checked"';
                            }
                        }
                    }

                    $result  = '<input type="radio" value="yes" ' . $inputName . ' ' . $inputClass . '  ' . $status1 . ' ' . self::action( $action , 'logic-radio' ) . ' /> ' . __( 'Yes' , _DEV_ ) . '&nbsp;&nbsp;&nbsp;';
                    $result .= '<input type="radio" value="no" ' . $inputName . ' ' . $inputClass . '  ' . $status2 . ' ' . self::action( $action , 'logic-radio' ) . ' /> ' . __( 'No' , _DEV_ );
                    break;
                }
                
                /* single type records */
                case 'hidden' : {
                    $result .= '<input type="hidden" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' value="' . $value . '"/>';
                    break;
                }
                case 'text' : {
                    if( !isset( $value ) ){
                        $value = '';
                    }
                    $result .= '<input type="text" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' value="' . $value . '" ' . self::action( $action , 'text' ) . '/>';
                    break;
                }
                case 'digit' : {
                    if( isset( $value ) ){
                        $value = $value; 
                    }else{
                        if( isset( $cvalue ) ){
                            $value = $cvalue;
                        }else{
                            $value = '';
                        }
                    }
                    $result .= '<input type="text" ' . $inputName . ' ' . $digitClass . ' ' . $inputID . ' value="' . $value . '" ' . self::action( $action , 'text' ) . '/>';
                    break;
                }
                
                case 'digit-like' : {
                    $result .= '<input type="text" ' . $inputName . ' ' . $digitClass . ' ' . $inputID . ' value="' . $value . '"/>';
                    $result .= '<input type="button" value="' . __( 'Reset Value' , _DEV_ ) . '" ' . $buttonClassUp . ' ' . self::action( $action , 'digit-like' ) . ' /> <span ' . $resultClass  .'></span>';
                    break;
                }
                case 'textarea' : {
                    if( !isset( $value ) ){
                        $value = '';
                    }
                    $result .= '<textarea ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' ' . self::action( $action , 'textarea' ) . '>' . $value . '</textarea>';
                    break;
                }

                case 'search' : {
                    if( !empty( $value ) && (int)$value > 0 ){
                        $p = get_post( $value );
						if(!is_wp_error($p) && is_object($p))
							{
								$title = $p -> post_title;
								$post_id = $p -> ID;
							}else{
								$title = '';
								$post_id = '';
							}
                    }else{
                        $title = '';
                        $post_id = '';
                    }
                    
                    $result .= '<input type="text" class="generic-search" value="' . $title . '" ' . self::action( $action , 'search' ) . '>';
                    $result .= '<input type="hidden" ' . $inputName . ' ' . $searchClass . ' ' . $inputID . ' value="' . $post_id . '" />';
                    $result .= '<input type="hidden" class="generic-params" value="' . urlencode( json_encode( $query ) ) . '" />';
					$result .= '<a href="#" onclick="javascript:jQuery(this).parent().find(\'input\').val(\'\');">Remove</a>';
                    break;
                }
                
                /* not used */
                case 'radio' : {
                    if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $name = isset( $single ) ? $iname : $side . '[' . $iname . ']';

                    $result .= '<input type="radio" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' value="' . $value . '"  ' . $status . ' ' . self::action( $action , 'radio' ) . ' />';
                    break;
                }

                case 'select' : {
                    $result .= '<select  ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' ' . self::action( $action , 'select' ) . ' >';
                    foreach( $values as $index => $etichet ){
                        if( isset( $value ) ){
                            if( $value == $index ){
                                $status = ' selected="selected" ' ;
                            }else{
                                $status = '' ;
                            }
                        }else{
                            
                            if( isset( $cvalue ) && $cvalue == $index ){
                                $status = ' selected="selected" ' ;
                            }else{
                                $status = '' ;
                            }
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'checkbox' : {
                    if( isset( $checkName ) && $checkName == $cvalue ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $result .= '<input type="checkbox" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' ' . $checkValue . '  ' . $status . ' ' . self::action( $action , 'checkbox' ) . ' />';
                    break;
                }

                case 'button' : {
                    $result .= '<input type="button" ' . $inputName . ' ' . $buttonClass . ' ' . $inputID . ' value="' . $value . '" ' . self::action( $action , 'button' ) . ' />';
                    if( isset( $additional ) && $additional ){
                        $result .= '<span ' . $resultAClass . '></span>';
                    }
                    break;
                }

                /* ~~~ not used ~~~ */
                case 'attach' : {
                    $action['res'] = $side;
                    $action['group'] = $res;
                    $action['post_id']  = $post_id;
                    $action['attach_selector'] = $attach_selector;
                    if( !isset( $selector ) ){
                        $selector = 'div#' . $res . '_' . $side . ' div.inside div#box_' . $res . '_' . $side;
                    }
                    $action['selector'] = $selector;
                    $result .= '<input type="button" ' . $inputName . ' ' . $buttonClass . ' ' . $inputID . ' value="' . $value . '" ' . self::action( $action , 'attach' ) . ' /> <p id="attach_' . $res . '_' . $side . '" class="attach_alert hidden">'.__( ' Attached ' , _DEV_ ).'</sp>';
                    break;
                }

                /* ~~~ revenim ~~~ */
                case 'meta-save' : {
                    $action['res']      = $res;
                    $action['group']    = $side;
                    $action['post_id']  = $post_id;
                    $action['selector'] = $selector;
                    $result .= '<input type="button" ' . $inputName . ' ' . $buttonClass . ' ' . $inputID . ' value="' . $value . '" ' . self::action( $action , 'meta-save' ) . ' />';
                    break;
                }

                case 'upload' : {
                    if( !isset( $value ) ){
                        $value = '';
                    }
                    
                    $result .= '<input type="text" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . '  value="' . $value  . '" /><input type="button" ' . $buttonClassUp . ' value="' . __( 'Choose File' , _DEV_ ) . '" ' . self::action( $ID  , 'upload' ) . ' />';
                    break;
                }

                case 'upload-id' :{
                    if( !isset( $value ) ){
                        $value = '';
                    }
                    if( !isset( $valueID ) ){
                        $valueID = '';
                    }
                    $result .= '<input type="text" ' . $inputName . ' ' . $inputClass . ' ' . $inputID . ' value="' . $value . '"/><input type="button" ' . $buttonClassUp . ' value="' . __('Choose File' , _DEV_ ) . '" ' . self::action( $ID , 'upload-id' ) . ' />';
                    $result .= '<input type="hidden" ' . $inputNameID . ' ' . $inputClass . ' ' . $uploadID . ' value="' . $valueID . '"/>';
                    break;
                }

				case "form-upload-init":
				  $result.='<div id="hidden_inputs_container" class="">';
				  $result.='</div>';
				  $result.='<script type="text/javascript">';
				  $result.='window.update_hidden_inputs=function(ids,type,urls,feat_vid)';
				  $result.="{";
					$result.='jQuery("#hidden_inputs_container").html("");';
					$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attachments_type\\" value=\\""+type+"\\">");';
					$result.='var i;';
					$result.='for(i=0;i<ids.length;i++)';
					  $result.="{";
						$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attachments[]\\" value=\\""+ids[i]+"\\">");';
						$result.="if(urls){";
						  $result.="if(urls[ids[i]]){";
							$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attached_urls["+ids[i]+"]\\" value=\\""+urls[ids[i]]+"\\">");';
						  $result.="}";
						$result.="}";
					  $result.="}";
					$result.="if(feat_vid){";
					  $result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"featured_video\\" value=\\""+feat_vid+"\\">");';
					$result.="}";
				  $result.="}";
				  $result.='</script>';
				  break;

				case "form-upload" :
					$result.='<iframe id="'.$format.'_upload_iframe"  class="upload_iframe" src="'.get_template_directory_uri().'/upload_iframe.php?type='.$format.(is_numeric($post_id)?('&post='.$post_id):"").'"></iframe>';
				break;

                /* ~~~ revenim ~~~  */
                /* multiple type records */
                case 'm-hidden' : {
                    $result .= '<input type="hidden" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . '  />';
                    break;
                }
                case 'm-text' : {
                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'm-digit' : {
                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record digit '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'm-textarea' : {
                    $result .= '<textarea name="' . $name . '[]" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'textarea' ) . '>' . $value . '</textarea>';
                    break;
                }

                case 'm-radio' : {
                    if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $name = isset( $single ) ? $iname : $side . '[' . $iname . ']';

                    $result .= '<input type="radio" name="' . $name . '[]" value="' . $value . '"  ' . $status . ' class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'radio' ) . ' />';
                    break;
                }

                case 'm-select' : {
                    $result .= '<select  name="' . $name . '[]" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && $ivalue == $index ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'm-multiple-select' : {
                    $result = '<select  name="' . $name . '[]" multiple="multiple" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'multiple-select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && is_array( $ivalue ) && $in_array( $index , $ivalue) ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }
                
                case 'm-search' : {
                    if( !empty( $value ) && (int)$value > 0 ){
                        $p = get_post( $value );
                        $title = $p -> post_title;
                        $post_id = $p -> ID;
                    }else{
                        $title = '';
                        $post_id = '';
                    }
                    
                    $result .= '<input type="text" class="generic-search" value="' . $title . '" ' . self::action( $action , 'search' ) . '>';
                    $result .= '<input type="text" ' . $inputMName . ' ' . $searchClass . ' ' . $inputID . ' value="' . $post_id . '" />';
                    $result .= '<input type="hidden" class="generic-params" value="' . urlencode( json_encode( $query ) ) . '" />';
                    break;
                }

                case 'm-checkbox' : {
                     if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $result .= '<input type="checkbox" name="' . $name . '[]" value="' . $value . '"  ' . $status . ' class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'checkbox' ) . ' />';
                    break;
                }
                case 'm-upload' : {
                    $result .= '<input type="text" name="' . $name . '[]"  value="' . $value  . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' /><input type="button" class="button-primary" value="Choose File" ' . self::action( $field_id , 'upload' ) . ' />';
                    break;
                }

                case 'm-upload-id' :{

                    $action['group'] = $side;
                    $action['topic'] = $prefix;
                    $action['index'] = $index;

                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . '  /><input type="button" class="button-primary" value="Choose File" ' . self::action( $action , 'upload-id' ) . ' />';
                    $result .= '<input type="hidden" name="' . $name_id . '[]" id="' . $field_id . '_id"  class="generic-record '  . $fclasses .  '" />';
                    break;
                }
            }
            
            return $result;
        }

        function action( $action , $type ){

            if( empty( $action ) ){
                return '';
            }

            $result = '';
            switch( $type ){
                case 'text' : {
                    $result = 'onkeyup="javascript:' . $action . ';"';
                    break;
                }
                case 'radio-icon' : {
                    $result = 'onclick="javascript:field.radio_icon(\'' . $action['selector'] . '\' , \'' . $action['index'] . '\' );"';
                    break;
                }
                case 'textarea' : {
                    $result = 'onkeyup="javascript:' . $action . ';"';
                    break;
                }
                case 'radio' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'checkbox' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'select' : {
                    $result = 'onchange="javascript:' . $action . ';"';
                    break;
                }
                case 'logic-radio' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'digit-like' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'm-select' : {
                    $result = 'onchange="javascript:' . $action . ';"';
                    break;
                }
                case 'button' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'meta-save' : {
                    $result = 'onclick="javascript:meta.save(\'' . $action['res'] . '\' , \'' . $action['group'] . '\' , '.$action['post_id'].' , \''.$action['selector'].'\' );meta.clear(\'.generic-' . $action['group'] . '\');"';
                    break;
                }
                case 'attach' : {
                    $result = 'onclick="javascript:meta.save_data(\'' . $action['res'] . '\' , \'' . $action['group'] . '\' , extra.val(\''.$action['attach_selector'].'\') , [ { \'name\' : \''.$action['group'].'[idrecord][]\' , \'value\' : ' . $action['post_id'] . ' }] , \''.$action['selector'].'\' );"';
                    break;
                }
                case 'upload' : {
                    $result = 'onclick="javascript:field.upload(\'input#' . $action . '\' );"';
                    break;
                }
                case 'upload-id' : {
                    $result = 'onclick="javascript:field.upload_id(\'#' . $action . '\' , \'\' );"';
                    break;
                }
            }

            return $result;
        }
    }
?>