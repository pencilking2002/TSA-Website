<?php
    class _taxonomy{
        /* accepted methods for ajax request */
        
        public static function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new _taxonomy();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( '_taxonomy' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( '_taxonomy' , $method ), $args );
                }
            }

            exit;
        }
        
        public static function get(){
            $result = array();
            if( isset( $_POST[ 'args' ][0] ) ){
                $resources = _core::method( '_resources' , 'get' );
                if( isset( $resources[ $_POST[ 'args' ][0] ] ) ){
                    if( isset( $resources[ $_POST[ 'args' ][0] ][ 'taxonomy' ] ) && !empty( $resources[ $_POST[ 'args' ][0] ][ 'taxonomy' ] ) ){
                        foreach( $resources[ $_POST[ 'args' ][0] ][ 'taxonomy' ] as $taxonomy ){
                            $result[ $taxonomy[ 'slug' ] ] = $taxonomy[ 'stitle' ];
                        }
                    }
                }
            }
            
            return $result;
        }
        
        public static function getByResourceID( $resourceID ){
            $result     = array();
            $resources  = _core::method( '_resources' , 'get' );
            $result[]   = __( 'Select taxonomy' , _DEV_ );
            if( isset( $resources[ $resourceID ][ 'taxonomy'] ) && !empty( $resources[ $resourceID ][ 'taxonomy' ] ) ){
                foreach( $resources[ $resourceID ][ 'taxonomy' ] as $taxonomy ){
                    $result[ $taxonomy[ 'slug' ] ] = $taxonomy[ 'stitle' ];
                }
            }
            
            return $result;
        }

        public static function panel( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result  = '';

            if( isset( $resources[ $resource ] ) ){
                $result .= '<h3>' . __( 'Taxonomy for : ' , _DEV_ ) . $resources[ $resource ]['stitle'] . '</h3>';
                $result .= '<div class="taxonomy-container">';

                $result .= self::items( $resource );

                $result .= '</div>';
                $result .= '<div class="taxonomy-action">';
                $result .= self::addForm( $resource );
                $result .= '<a  class="add" href="javascript:field.s(\'.taxonomy-addbox\');">' . __( 'Add new' , _DEV_ ) . '</a>';
                $result .= '</div>';
            }

            return $result;
        }

        public static function items( $resource ){
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            if( isset( $resources[ $resource ]['taxonomy'] ) && !empty( $resources[ $resource ]['taxonomy'] ) && is_array( $resources[ $resource ]['taxonomy'] ) ){
                foreach( $resources[ $resource ]['taxonomy'] as $index => $taxonomy ){
                    $result .= self::editForm( $resource , $index );
                    $result .= '<div class="taxonomy">';
                    $result .= self::actionbox( $resource , $index );
                    $result .= '<p>' . $taxonomy['stitle'] . '</p>';
                    $result .= '</div>';
                }
            }
            return $result;
        }
    
        public static function actionbox( $resource , $tax ){
            $result  = '';
            $result .= '<div class="taxonomy-actionbox">';
            $result .= '<ul>';
            $result .= '<li><a href="javascript:javascript:(function(){ if (confirm(\'' . __( 'Are you sure you want to delete?' , _DEV_ ) . '\')) { res.tax.r(\'remove\' , [ ' . $resource . ' , ' . $tax . ' ]); }})();">' . __( 'Delete' , _DEV_ ) . '</a></li>';
            $result .= '<li><a href="javascript:field.load( res.tax , \'editForm\' , [ ' . $resource . ' , ' . $tax . ' ]  , \'.taxonomy-action\' ,  \'.taxonomy-editbox' . $tax . '\')"> ' . __( 'Properties' , _DEV_ ) . ' </a></li>';
            $result .= '</ul>';
            $result .= '</div>';
            
            return $result;
        }
    
        public static function add( $resource , $stitle , $ptitle , $slug , $hierarchical ){
            $resources = _core::method( '_resources' , '_get' );
            if( isset( $resources[ $resource ] ) ){
                if( ( strlen( $stitle ) == 0 ) || ( strlen( $ptitle ) == 0 ) || ( strlen( $slug ) == 0 ) ){
                    return 'error';
                }
                $resources[ $resource ]['taxonomy'][] = array(
                    'stitle' => $stitle,
                    'ptitle' => $ptitle,
                    'slug' => strtolower( str_replace( array( '   ' , '  ' , ' ' ) , '-' , $slug ) ),
                    'hierarchical' => $hierarchical,
                );
                update_option( _RES_ , $resources );
            }else{
                return 'error';
            }

            return self::items( $resource );
        }

        public static function edit( $resource , $taxonomy , $stitle , $ptitle , $slug , $hierarchical ){
            $resources = _core::method( '_resources' , '_get' );
            if( isset( $resources[ $resource ]['taxonomy'][$taxonomy] ) ){
                if( ( strlen( $stitle ) == 0 ) || ( strlen( $ptitle ) == 0 ) || ( strlen( $slug ) == 0 ) ){
                    return 'error';
                }
                $resources[ $resource ]['taxonomy'][$taxonomy] = array(
                    'stitle' => $stitle,
                    'ptitle' => $ptitle,
                    'slug' => strtolower( str_replace( array( '   ' , '  ' , ' ' ) , '-' , $slug ) ),
                    'hierarchical' => $hierarchical,
                );
                update_option( _RES_ , $resources );
            }else{
                return 'error';
            }

            return self::items( $resource );
        }

        public static function remove( $resource , $taxonomy ){
            $resources = _core::method( '_resources' , '_get' );

            if( isset( $resources[ $resource ]['taxonomy'][ $taxonomy ] ) ){
                unset( $resources[ $resource ]['taxonomy'][ $taxonomy ] );
                update_option( _RES_ , $resources );
            }

            return self::items( $resource );
        }

        /* posibil este necesar de generalizat cinpurile pentru add - forme si edit ( settings ) forme  */
        public static function addForm( $resource ){

            $add = array(
                'type' => 'box--addtaxonomy',
                'classes' => 'taxonomy-addbox hidden',
                'title' => __( 'Add new taxonomy' , _DEV_ ),
                'content' => array(
                    __('Add' , _DEV_ ) => array(
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (singular)' , _DEV_ ) , 'id' => 'tax_stitle'  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Title (plural)' , _DEV_ ) , 'id' => 'tax_ptitle'  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Slug' , _DEV_ ) , 'id' => 'tax_slug' , 'hint' => __( 'Slug is unique identifier of this type of taxonomy, it is used in url to view archive.php template.' , _DEV_ ) ),
                        array( 'type' => 'nii--checkbox' , 'set' => 'hierarchical' , 'label' => ' ' . __( 'Hierarchical taxonomy' , _DEV_ ) , 'id' => 'tax_type' , 'invers' => true , 'cvalue' => 'yes' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="addbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "res.tax.r( 'add' , [ " . $resource . " , tools.v( '#tax_stitle' ) , tools.v( '#tax_ptitle' ) , tools.v( '#tax_slug' ) , tools.v( '#tax_type' ) ]);field.h('.taxonomy-addbox');" 
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.taxonomy-addbox');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully added' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    ),
                )
            );

            return _fields::layout( $add );
        }

        public static function editForm( $resource , $taxonomy ){

            $resources = _core::method( '_resources' , 'get' );
            
            $edit = array(
                'type' => 'box--edittaxonomy',
                'classes' => 'taxonomy-editbox' . $taxonomy . ' hidden',
                'title' => __( 'Properties' , _DEV_ ),
                'content' => array(
                    __('General' , _DEV_ ) => array(
                        array( 'type' => 'stbox--text' , 'label' => __( 'Single Title' , _DEV_ ) , 'id' => 'tax_stitle' . $taxonomy , 'value' => $resources[ $resource ]['taxonomy'][ $taxonomy ]['stitle']  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Multiple Titles' , _DEV_ ) , 'id' => 'tax_ptitle' . $taxonomy , 'value' => $resources[ $resource ]['taxonomy'][ $taxonomy ]['ptitle']  ),
                        array( 'type' => 'stbox--text' , 'label' => __( 'Slug' , _DEV_ ) , 'id' => 'tax_slug' . $taxonomy , 'value' => $resources[ $resource ]['taxonomy'][ $taxonomy ]['slug'] , 'hint' => __( 'Slug is unique identifier of this type of taxonomy, it is used in url to view archive.php template.' , _DEV_ ) ),
                        array( 'type' => 'nii--checkbox' , 'set' => 'hierarchical' , 'label' => ' ' . __( 'Hierarchical taxonomy' , _DEV_ ) , 'id' => 'tax_type' . $taxonomy , 'invers' => true , 'cvalue' => $resources[ $resource ]['taxonomy'][ $taxonomy ]['hierarchical'] ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="addbox-submit-action">' ),
                        array( 
                            'type' => 'ln--button' ,  'value' => __( 'Ok' , _DEV_ ) , 
                            'action' => "res.tax.r( 'edit' , [ " . $resource . " , " . $taxonomy .  " , tools.v( '#tax_stitle" . $taxonomy . "' ) , tools.v( '#tax_ptitle" . $taxonomy . "' ) , tools.v( '#tax_slug" . $taxonomy . "' ) , tools.v( '#tax_type" . $taxonomy . "' ) ]);field.h('.taxonomy-editbox" . $taxonomy . "');" 
                        ),
                        array( 'type' => 'ln--button' , 'value' => __( 'Cancel' , _DEV_ ) , 'action' => "field.h('.taxonomy-editbox" . $taxonomy . "');" , 'btnType' => 'secondary' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Successfully modified' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'success hidden' ),
                        array( 'type' => 'ln--mssg' , 'value' => __( 'Error, try again' , _DEV_ ) , 'classes' => 'result-mssg' , 'iclasses' => 'error hidden' ),
                        array( 'type' => 'cd--submit' , 'content' => '<div class="clear"></div></div>' )
                    ),
                )
            );
            
            return _fields::layout( $edit );
        }
        
        public static function getCustomTaxonomy(  $custom_post_type ){
            $result = '';
            
            if( strlen( $custom_post_type ) ){
                if( $custom_post_type != 'post' ){
                    $resourceID = _resources::getResourceBySlug( $custom_post_type );
                    $taxonomy = self::getByResourceID( $resourceID );
                }else{
                    $taxonomy = array(
                        'post_tag' => __( 'Tags' , _DEV_ ),
                        'category' => __( 'Category' , _DEV_ )
                    );
                }

                if( !empty( $taxonomy ) && count( $taxonomy ) > 1  ){
                    
                    $result .= _fields::layout( array(
                        'type' => 'st--select',
                        'label' => __( 'Select taxonomy for latest quick news'  , _DEV_ ),
                        'values' => $taxonomy,
                        'group' => 'settings__slideshow__general',
                        'set' => 'taxonomy',
                        'action' => "res.tax.r( 'getTaxonomyTerms' , [ this.value ] )"
                    ));
                    
                    $result .= _fields::layout( array(
                        'type' => 'cd--code-start',
                        'content' => '<div class="taxonomy-terms latest-custom-post">'
                    ));
                    
                    $result .= _fields::layout( array(
                        'type' => 'cd--code-start',
                        'content' => '</div>'
                    ));
                }
            }
            
            return $result;
        }
        
        public static function getTerms( $taxonomy ){
            $terms = get_terms( $taxonomy  , array(
                'orderby'    => 'count  ',
                'hide_empty' => 1
            ) );
            
            $result = array();
            $result[] = __( 'Select item from list' , _DEV_ );
            foreach( $terms as $term ){
                if( is_object( $term ) ){
                    $result[ $term -> slug ] = $term -> name;
                }
            }
            
            return $result;
        }
        
        public static function getTaxonomyTerms( $taxonomy ){
            $terms = self::getTerms( $taxonomy );
            $result = '';
            if( !empty( $terms ) && count( $terms ) > 1 ){
                $result = _fields::layout( array(
                    'type' => 'st--select',
                    'label' => __( 'Select termen for latest quick news' , _DEV_ ),
                    'values' => $terms,
                    'group' => 'settings__slideshow__general',
                    'set' => 'terms'
                ));
            }
            
            return $result;
        }
    }
?>