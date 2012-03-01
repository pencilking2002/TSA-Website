<?php
    class _attachdocs{
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _attachdocs();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_attachdocs' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_attachdocs' , $method ), $args );
                }
            }
            exit;
        }
        public static function panel( $postID ){
            $result  = '';
            $result .= '<div class="panel-attachdocs mrecords-panel">';
            $result .= self::items( $postID );
            $result .= '</div>';
            $result .= '<div class="panel-attachdocs-addform mrecords-addform">';
            $result .= self::addForm( $postID );
            $result .= '</div>';
            return $result;
        }
        
        public static function addForm( $postID ){
            $result  = _fields::layout( array(
                    'type' => 'st--text',
                    'label' => __( 'URL for demo' , _DEV_ ),
                    'set' => 'attachdocs-demo',
                )
            );
            
            $result .= _fields::layout( array(
                    'type' => 'st--upload',
                    'label' => __( 'Upload or select file from media library' , _DEV_ ),
                    'btnType' => 'secondary',
                    "classes" => 'attachdocs-action',
                    'set' => 'attachdocs',
                )
            );

            $result .= _fields::layout( array( 'type' => 'cd--submit' , 'content' => '<div class="addmbox-submit-action">' ) );
            
            $result .= _fields::layout( array(
                    'type' => 'ln--button',
                    'value' => __( 'Attach Document' , _DEV_ ),
                    'btnType' => 'primary fl',
                    'action' => "mbox.r( 
                        'mbox_load' , 
                        'add' , 
                        [
                            tools.v('#post_ID') , 
                            'attachdocs' ,
                            {
                                'demo' : tools.v('#attachdocs-demo'),
                                'url' : tools.v('#attachdocs')
                            }
                        ] , 
                        '.panel-attachdocs' , 
                        '.panel-attachdocs-addform'
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
            
            $attachdoc = _core::method( '_meta' , 'get' , $postID , 'attachdocs' , $index );
            
            $edit = array(
                'type' => 'box--edit',
                'classes' => 'attachdocs-editbox',
                'title' => __( 'Modify attached document' , _DEV_ ),
                'width' => 490,
                'content' => array(
                    __( 'Attached document' , _DEV_ ) => array(
                        array( 
                            'type' => 'stbox--text',
                            'label' => __( 'URL for demo' , _DEV_ ),
                            'value' => $attachdoc[ 'demo' ],
                            'id' => 'attachdoc_demo' . $index
                        ),
                        array(
                            'type' => 'stbox--upload',
                            'label' => __( 'Upload document' , _DEV_ ),
                            'value' => $attachdoc[ 'url' ],
                            'id' => 'attachdoc_url' . $index ,
                            'hint' => __( 'You can download document or select it from media library' , _DEV_ ) 
                        ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="editbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ),
                            'action' => "mbox.r( 'mbox_load' , 'edit' , [ " . 
                                $postID . " , 
                                'attachdocs' , 
                                { 'demo' : tools.v('#attachdoc_demo" . $index . "') , 'url' : tools.v('#attachdoc_url" . $index . "') },
                                " . $index . "],
                                '.panel-attachdocs', 
                                '.panel-attachdocs-addform');field.h('.attachdocs-editbox');"
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.attachdocs-editbox');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully modify' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    )
                )
            );
            
            return _fields::layout( $edit );
        }
        
        public static function items( $postID ){
            $attachdocs = _meta::get( $postID , 'attachdocs' );
            $result = '';
            if( !empty( $attachdocs ) ){
                $result .= '<ul>';
                foreach( $attachdocs as $index => $attachdoc ){
                    $result .= self::item( $postID , $index , $attachdoc );
                }
                $result .= '</ul>';
            }else{
                $result .= '<ul>';
                $result .= '<li><p>' . __( 'Not found attched documents' , _DEV_ ) . '</p></li>';
                $result .= '</ul>';
            }
            return $result;
        }
        
        public static function item( $postID , $index , $attachdoc ){
            $result  = '<li>';
            $result .= '<div class="attchdocs-item record-item">';
            $file = pathinfo( $attachdoc['url'] );
            $result .= __( 'File' , _DEV_ ) . '&nbsp; - ' . $file['basename'];
            if( !empty( $attachdoc['demo'] ) ){
                $result .= '<span class="item-info"><br />';
                $result .= __( 'Demo' , _DEV_ ) . ' - <a href="' . $attachdoc['demo'] . '" target="_blank">' . $attachdoc['demo'] . '</a>';
                $result .= '</span>';
            }
            $result .= '<span class="item-info item-action">';
            $result .= '<a class="edit" href="javascript:field.load(  attachdocs , \'editForm\' , [ \'' . $postID . '\' , ' .  $index .  ' ] , \'.panel-attachdocs-addform\' , \'.attachdocs-editbox\' );">' . __( 'Edit' , _DEV_ ) . '</a> | ';
            $result .= '<a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete?' , _DEV_ ) . '\')) { mbox.r( \'mbox_load\' , \'delete\' , [ ' . $postID . ' , \'attachdocs\' , ' . $index . ' ] , \'.panel-attachdocs\'  ); }})();">' . __( 'Delete' , _DEV_ ) . '</a>';
            $result .= '</span>';
            $result .= '</div>';
            $result .= '</li>';
            return $result;
        }
    }
?>