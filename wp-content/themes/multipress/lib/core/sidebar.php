<?php
    class _sidebar {
        
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _sidebar();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_sidebar' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_sidebar' , $method ), $args );
                }
            }
            exit;
        }
        
        public static function addForm(){
            $add = array(
                'type' => 'box--addsidebar',
                'classes' => 'sidebar-addbox hidden',
                'title' => __( 'Add new sidebar ' , _DEV_ ),
                'content' => array(
                    __('Add' , _DEV_ ) => array(
                        array( 'type' => 'stbox--text' , 'label' => __( 'Sidebar Title' , _DEV_ ) , 'id' => 'sidebar_title'  ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="addbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "sidebar.r( 'add' , [ tools.v( '#sidebar_title' ) ] );field.h('.sidebar-addbox');" 
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.sidebar-addbox');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    ),
                )
            );
            
            return _fields::layout( $add );
        }
        
        public static function editForm( $id ){
            $sidebars = get_option( _SBAR_ );
            
            if( isset( $sidebars[ $id ] ) ){
                $edit = array(
                    'type' => 'box--editsidebar',
                    'classes' => 'sidebar-editbox' . $id ,
                    'title' => __( 'Add new sidebar ' , _DEV_ ),
                    'content' => array(
                        __('Edit' , _DEV_ ) => array(
                            array( 'type' => 'stbox--hidden' , 'id' => 'sidebar_id' . $id , 'value' => $id ),
                            array( 'type' => 'stbox--text' , 'label' => __( 'Sidebar Title' , _DEV_ ) , 'id' => 'sidebar_title' . $id , 'value' => $sidebars[ $id ] ),
                            array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                            array( 
                                'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                                'action' => "sidebar.r( 'edit' , [ tools.v( '#sidebar_id" . $id . "' ) , tools.v( '#sidebar_title" . $id . "' ) ]);field.h('.sidebar-editbox" . $id . "');" 
                            ),
                            array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.sidebar-editbox" . $id . "');" , 'btnType' => 'secondary' ),
                            array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                            array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                            array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                        ),
                    )
                );

                return _fields::layout( $edit );
            }
        }
        
        public static function get( ){
            $sidebars = get_option( _SBAR_ );
            
            if( !is_array( $sidebars ) || empty( $sidebars ) ){
                return array();
            }else{
                return $sidebars;
            }
        }
        
        public static function getList(){
            $sidebars[ 'main' ] = __( 'main' , _DEV_ );
            
            $sb = self::get();
            if( !empty( $sb ) ){
                foreach( $sb as $index => $s ){
                    $sidebars[ $index ] = $s;
                }
            }
            
            return $sidebars;
        }
        
        public static function add( $title ){
            $sidebars = self::get();
            
            if( empty( $title ) ){
                return 'error';
            }
            
            if( isset( $sidebars[str_replace( array( '  ' , '   ' , ' ' , '_' ) , '-' , $title )] ) ){
                return 'error';
            }
            
            $sidebars[str_replace( array( '  ' , '   ' , ' ' , '_' ) , '-' , $title )] = $title;
            update_option( _SBAR_ ,  $sidebars );
            return self::items();
        }
        
        public static function edit( $id , $title ){
            $sidebars = self::get();
            
            if( isset( $sidebars[ $id ] ) ){
                unset( $sidebars[ $id ] );
                $sidebars[str_replace( array( '  ' , '   ' , ' ' , '_' ) , '-' , $title )] = $title;
                update_option( _SBAR_ ,  $sidebars );
            }else{
                return 'error';
            }
            
            return self::items();
        }
        
        public static function delete( $id ){
            $sidebars = self::get();
            if( isset( $sidebars[ $id ] ) ){
                unset( $sidebars[ $id ] );
                update_option( _SBAR_ ,  $sidebars );
            }else{
                return 'error';
            }
            
            return self::items();
        }
        
        public static function panel(){
            
            $result  = '<h3>' . __( 'Custom sidebars' , _DEV_ ) . '</h3>';
            $result .= '<div class="sidebars-container">';
            $result .= self::items();
            $result .= '</div>';
            $result .= self::action();
            return $result;
        }
        
        public static function items(){
            $sidebars = self::get();
            $result = '';
            foreach( $sidebars as $id => $sidebar ){
                $result .= self::item( $id );
            }
            return $result;
        }
        
        public static function item( $id ){
            
            $sidebars = self::get();
            
            $result  = '<div class="sidebar">';
            $result .= self::actionbox( $id );
            $title   = isset( $sidebars[ $id ] ) ? $sidebars[ $id ] : '&nbsp;';
            $result .= '<p>'  . $title . '</p>';
            $result .= '</div>';
            
            return $result;
        }
        
        public static function action(){
            $result  = '<div class="sidebar-action">';
            $result .= self::addForm();
            $result .= '<a class="add" href="javascript:field.s(\'.sidebar-addbox\');">' . __( 'Add new sidebar' , _DEV_ ) . '</a>';
            $result .= '</div>';
            
            return $result;
        }
        
        public static function actionbox( $id ){
            
            $result  = '';
            $result .= '<div class="sidebar-actionbox">';
            $result .= '<ul>';
            $result .= '<li><a href="javascript:field.load( sidebar , \'editForm\' , [  \'' . $id . '\' ] , \'.sidebar-action\' , \'.sidebar-editbox' . $id .  '\' );">' . __( 'Edit' , _DEV_ ) .  '</a></li>';
            $result .= '<li><a href="javascript:sidebar.r( \'delete\' , [  \'' . $id . '\' ]);">'. __( 'Delete' , _DEV_ ) . '</a></li>';
            $result .= '</ul>';
            $result .= '</div>';
            
            return $result;
        }
    }
?>