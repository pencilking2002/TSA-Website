<?php
    class _register{
        public static function panelMenu(){
            if( is_array( _panel::$menu ) && !empty( _panel::$menu ) ){
                if( isset( $_GET['page'] ) ){
                    $prefixes = explode( '__' , $_GET['page'] );
                }else{
                    $prefixes = array();
                }
                
                if( empty( $prefixes ) ){
                    /* theme tab */
                    $func = _DEV_ . '__main';
                    if( function_exists( $func ) ){
                        add_menu_page( get_current_theme() , get_current_theme() , 'administrator' , $func  , $func, get_template_directory_uri() . '/lib/core/images/icon.png' );
                    }else{
                        if( class_exists( '_core' ) ){
                            /* clase _core - dynamic add functions for options main page */
                            _core::addFunction( '_panel' , 'main_builder' , $func );
                            add_menu_page( get_current_theme() , get_current_theme() , 'administrator' , $func  ,  $func, get_template_directory_uri() . '/lib/core/images/icon.png' );
                        }
                    }
                }else{    
                    /* theme tab */
                    $func = _DEV_ . '__main';
                    if( function_exists( $func ) ){
                        add_menu_page( get_current_theme() , get_current_theme() , 'administrator' , $func  , $func, get_template_directory_uri() . '/lib/core/images/icon.png' );
                    }else{
                        if( class_exists( '_core' ) ){
                            /* clase _core - dynamic add functions for options main page */
                            _core::addFunction( '_panel' , 'main_builder' , $func );
                            add_menu_page( get_current_theme() , get_current_theme() , 'administrator' , $func  ,  $func, get_template_directory_uri() . '/lib/core/images/icon.png' );
                        }
                    }
                    
                    foreach( _panel::$menu as $page => $tabs ){
                        foreach( $tabs as $tab => $groups ){
                            foreach( $groups as $group => $item ){

                                $func = $page . '__' . $tab . '__' . $group;

                                if( isset( $item['type'] ) &&  $item['type'] == 'main'  ){

                                    if( function_exists( $func ) ){
                                        add_menu_page( $item['main_label'] , $item['menu_label'] , 'administrator' , $func  , $func , $item['icon'] );
                                    }else{
                                        if( class_exists( '_core' ) ){
                                            /* clase _core - dynamic add functions for options main page */
                                            _core::addFunction( '_panel' , 'builder' , $func );
                                            add_menu_page( $item['main_label'] , $item['menu_label'] , 'administrator' , $func  ,  $func  , $item['icon'] );
                                        }
                                    }
                                    $main_func = $func;
                                }else{
                                    if( isset( $item['main_label'] ) ){
                                        if( function_exists( $func ) ){
                                            add_submenu_page( $main_func , $item['label'] , $item['label'] , 'administrator' , $func , $func );
                                        }else{
                                            if( class_exists( '_core' ) ){
                                                /* clase _core - dynamic add functions for options subpage */
                                                _core::addFunction( '_panel' , 'builder' , $func );
                                                add_submenu_page( $main_func , $item['label'] , $item['label'] , 'administrator' , $func , $func );
                                            }
                                        }

                                        $main_label = $item['label'] ;
                                    }else{
                                        if( function_exists( $func ) ){
                                            add_submenu_page( $main_func , $item['label'] , ' - ' . $item['label'] , 'administrator' , $func , $func );
                                        }else{
                                            if( class_exists( '_core' ) ){
                                                /* clase deb - dynamic add functions for options subpage */
                                                _core::addFunction( '_panel', 'builder' , $func );
                                                add_submenu_page( $main_func , $item['label'] , ' - ' . $item['label'] , 'administrator' , $func , $func );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        public static function settings(){
            
            $register_tab = array();
            if( is_array( _settings::$register ) && !empty( _settings::$register ) ){
                foreach( _settings::$register as $page => $tabs ){
                    foreach( $tabs as $tab => $groups ){
                        foreach( $groups as $group => $sets ){
                            register_setting( $page . '__' . $tab . '__' . $group  ,  $page . '__' . $tab . '__' . $group );
                        }
                    }
                }
            }
        }
        
        public static function resources(){
            $resources = _core::method( '_resources' , 'get' );
            
            foreach( $resources as $index => $resource ){
                
                if( isset( $resource['noregister'] ) && $resource['noregister'] ){
                }else{
                    $labels = array(
                        'name' => _x( $resource['ptitle'] , 'post type general name' ),
                        'singular_name' => _x( $resource['stitle'] , 'post type singular name' ),
                        'add_new' => _x( 'Add new ' . strtolower( $resource['stitle'] ) , strtolower( $resource['stitle'] ) ),
                        'add_new_item' => __( 'Add new ' , _DEV_ ) . __($resource['stitle'] , _DEV_ ),
                        'edit_item' => __( 'Edit ', _DEV_ ) . __(strtolower( $resource['stitle'] ) , _DEV_ ),
                        'new_item' => __( 'New ', _DEV_ ) . __(strtolower( $resource['stitle'] ) , _DEV_ ),
                        'view_item' => __( 'View ', _DEV_ ) . __(strtolower( $resource['stitle'] ) , _DEV_ ),
                        'search_items' =>  __( 'Search ', _DEV_ ) . __(strtolower( $resource['ptitle'] ) , _DEV_ ),
                        'not_found' =>  __( 'Nothing found' , _DEV_ ),
                        'not_found_in_trash' => __( 'Nothing found in Trash' , _DEV_ )
                    );

                    if( isset( $resource['fields'] ) && is_array( $resource['fields'] ) ){
                        $fields = _resources::getFields( $resource['fields'] );
                    }else{
                        $fields = _resources::getFields( _resources::fields() ); ;
                    }

                    $args = array(
                        'public' => true,
                        'hierarchical' => false,
                        'menu_position' => 3,
                        'supports' => $fields,
                        'labels' => $labels
												
                    );

                    register_post_type( $resource['slug'] , $args );
                }
                
                self::taxonomy( $resource );
                add_action( 'admin_init'    , array( '_register' , 'relationBox' ) , 1 );
                add_action( 'admin_init'    , array( '_register' , 'customBox' ) , 1 );
                    
                /* set values */
                if( isset( $_POST['post_ID'] ) ){
                    
                    $resources = _core::method( '_resources' , 'get' );
                    $customID = _attachment::getCustomIDByPostID( (int)$_POST['post_ID'] );
                                        
                    if( isset( $resources[ $customID ]['boxes'] ) && !empty( $resources[ $customID ]['boxes'] ) ){
                        foreach( $resources[ $customID ]['boxes'] as $key => $box ){
                            if( isset( $_POST[ $key ] ) && is_array( $box ) && !empty( $box ) ){
                                
                                $metavalue = array();
                                
                                foreach( $box as $set => $field ){
                                    if( isset( $_POST[ $key][ $set ] ) ){
                                        $metavalue[ $set ] = $_POST[ $key][ $set ];
                                        
                                        /* upload id */
                                        if( isset( $_POST[ $key][ $set . '-id' ] ) ){
                                            $metavalue[ $set . '-id' ] = $_POST[ $key][ $set . '-id' ];
                                        }
                                    }
								}

								if(  $key == 'format' ){
										$metavalue=self::write_attachments_metadata();
									}                                

                                _meta::set( (int)$_POST['post_ID'] ,  $key , $metavalue );
                            }
                        }
                    }
                    
                    if( isset( $resources[ $customID ]['boxes'] ) && !empty( $resources[ $customID ]['boxes'] ) ){
                        foreach( $resources[ $customID ]['boxes'] as $key => $box ){
                            if( isset( $_POST[ $key ] ) ){
                                if( isset( _box::$boxes[ $resources[ $customID ][ 'type' ] ][ $key ][ 'fields' ] ) ){
                                    $metavalue = array();
                                    foreach( _box::$boxes[ $resources[ $customID ][ 'type' ] ][ $key ][ 'fields' ] as  $set => $field ){
                                        
                                        if( !is_numeric( $set ) ){
                                            $field[ 'set' ] = $set;
                                        }
                                    
                                        if( isset( $field[ 'set' ] ) && isset( $_POST[ $key][ $field[ 'set' ] ] ) ){
                                            $metavalue[ $field[ 'set' ] ] = $_POST[ $key][ $field[ 'set' ] ];
                                        }
                                    }

									if(  $key == 'format' ){
											$metavalue=self::write_attachments_metadata();
										}

                                    _meta::set( (int)$_POST['post_ID'] , $key ,  $metavalue );
                                }
                            }
                        }
                    }
                }
            }
        }

		public static function write_attachments_metadata(){
				if( isset( $_POST[ 'attachments_type' ] ) ){
						$metadata = Array();
						if(isset($_POST['attachments']))
							{
								foreach($_POST['attachments'] as $attach_id)
									{
										$attachment_post=get_post($attach_id);
										$attachment_post->post_parent=$_POST['post_ID'];
										wp_update_post($attachment_post);
									}
								switch($_POST['attachments_type'])
									{
										case 'image':
											$metadata=array("type" => 'image', 'images'=>$_POST['attachments']);	
										break;
										case 'video':
											foreach($_POST['attachments'] as $index=>$attach_id)
												{
													if($attach_id==$_POST['featured_video'])
													  {
														$_POST['featured_video_id']=$attach_id;
														unset($_POST['attachments'][$index]);
														if(isset($_POST['attached_urls'][$attach_id]))
														  {	
															set_post_thumbnail($_POST['post_ID'],$attach_id);
															$_POST['featured_video_url']=$_POST['attached_urls'][$attach_id];
															unset($_POST['attached_urls'][$attach_id]);
														  }
													  }
												  }
												$metadata=array("type"=>"video", "video_ids"=>$_POST['attachments'], "feat_id"=>$_POST['featured_video_id'], "feat_url"=>$_POST['featured_video_url']);
											  
												if(isset($_POST['attached_urls']))
												  $metadata["video_urls"]=$_POST["attached_urls"];
										break;
										case 'audio':
											$metadata = array("audio"=>  $_POST['attachments'], "type" => 'audio');
										break;
										case 'link':
											$metadata = array("link"=>  $_POST['file'], "type" => 'link', 'link_id' => $_POST['attachments']);
										break;
									}
							}
					}
				else
					{
						$metadata=array("type"=>$_POST['attachments_type']);
					}

				if( isset( $_POST['format']['type'] ) ){
					set_post_format( $_POST[ 'post_ID' ] , $_POST['format']['type'] );
					$_POST['post_format'] = $_POST['format']['type'];
                }
				return $metadata;
			}
        
        public static function taxonomy( $resource ){
            $taxonomy = array();
            if( isset( $resource[ 'taxonomy' ] ) && is_array( $resource[ 'taxonomy' ] ) && !empty( $resource[ 'taxonomy' ] ) ){
                foreach( $resource[ 'taxonomy' ] as $k => $tax  ){

                    $labels = array(
                        'name' => _x( $tax['ptitle'] , 'taxonomy general name' ),
                        'singular_name' => _x( $tax['stitle'] , 'taxonomy singular name' ),
                        'search_items' => __( 'Search ' , _DEV_ ).  __(strtolower( $tax['ptitle'] ) , _DEV_ ),
                        'all_items' => __( 'All ' , _DEV_ ) . __(  strtolower( $tax['ptitle'] ) , _DEV_ ),
                        'parent_item' =>  __( 'Parent ' , _DEV_ ) . __( strtolower( $tax['stitle'] ) , _DEV_ ),
                        'parent_item_colon' => __( 'Parent ' , _DEV_ ) . __( strtolower( $tax['stitle'] ) , _DEV_ ),
                        'edit_item' => __( 'Edit ' , _DEV_ ) . __(strtolower( $tax['stitle'] ) , _DEV_ ),
                        'update_item' => __( 'Update ' , _DEV_ ) . __( strtolower( $tax['stitle'] ) , _DEV_ ),
                        'add_new_item' => __( 'Add New ' , _DEV_ ) . __( strtolower( $tax['stitle'] ) , _DEV_ ),
                        'new_item_name' => __( 'New ', _DEV_ ) . __( strtolower( $tax['stitle'] ) , _DEV_ ) . __( ' name' , _DEV_ ),
                    );

                    if( $tax['hierarchical'] == 'hierarchical' ){
                        $taxonomy = array(
                            'hierarchical' => true,
                            'rewrite' => array(
                                'slug' => $tax['slug'],
                                'hierarchical' => true,
                            ),
                            'labels' => $labels
                        );
                    }else{
                        $taxonomy = array(
                            'rewrite' => array(
                                'slug' => $tax['slug']
                                ),
                            'labels' => $labels
                        );
                    }
                    
                    register_taxonomy( $tax['slug'] , array( $resource['slug'] ) , $taxonomy );
                }
            }
        }
        
        public static function relationBox(){
            
            if( ( isset( $_GET['post'] ) && strlen( $_GET['post'] ) > 0 ) || ( isset( $_GET[ 'post_type' ] ) && !empty( $_GET[ 'post_type' ] ) ) ){
                
                if( isset( $_GET['post'] ) ){
                    $post = get_post( $_GET['post'] );
                    $post_type = $post -> post_type;
                }
                
                if( isset( $_GET['post_type'] ) ){
                    $post_type = $_GET['post_type'];
                }
            }else{
                $post_type = 'post';
            }
                
            if( is_admin() ){
                
                $resources = _core::method( '_resources' , 'get' );
                foreach( $resources as $index => $resource ){
                    if( $resource['slug'] == $post_type ){
                        $res = _core::method( '_resources' , 'get' );
                        foreach( $res as $id => $r ){
                            if( $r['parent'] == $index ){
                                add_meta_box(
                                    str_replace( array( '_' , ' ' ) , '-' ,  $resource['slug'] . '-' . $r['slug'] ) , 
                                    __( 'Add' , _DEV_ ) . ' ' . $r['ptitle'] ,
                                    array( '_attachment' , 'childrenAttachmentsBox' ) ,
                                    $resource['slug'] ,
                                    'side' ,
                                    'default' ,
                                    array( $index , $id ) 
                                );
                            }
                        }
                        
                        if( $resource['parent'] != -1 ){
                            add_meta_box(
                                str_replace( array( '_' , ' ' ) , '-' ,  $post_type . '-' . $resource['slug'] ) ,
                                __( 'Attach' , _DEV_ ) . ' ' . $resource['ptitle'] ,
                                array( '_attachment' , 'parentAttachmentsBox' ) , 
                                $resource['slug'] ,
                                'side' , 
                                'default'
                            );
                        }
                    }
                }
            }
        }
        
        public static function customBox(){
            if( ( isset( $_GET['post'] ) && strlen( $_GET['post'] ) > 0 ) || ( isset( $_GET[ 'post_type' ] ) && !empty( $_GET[ 'post_type' ] ) ) ){
                if( isset( $_GET['post'] ) ){
                    $post = get_post( $_GET['post'] );
                    $post_type = $post -> post_type;
                }
                
                if( isset( $_GET['post_type'] ) ){
                    $post_type = $_GET['post_type'];
                }
            }else{
                $post_type = 'post';
            }
                  
            if( is_admin() ){
                /* short code include panel */
                $cusotomID = _resources::getCustomIdByPostType( $post_type );
                $resources = _core::method( '_resources' , 'get' );
                
                if( !isset( $resources[ $cusotomID ][ 'noshcode' ] ) ){
                    add_meta_box( 'shcode-' . $post_type , __( 'Short Codes' , _DEV_ ) , array( '_register' , 'shcode' ) , $post_type , 'normal' , 'high' );
                }
                
                foreach( $resources as $index => $resource ){
                    if( $resource['slug'] == $post_type && isset( $resource[ 'boxes' ] ) && !empty( $resource[ 'boxes' ] ) && is_array( $resource[ 'boxes' ] ) ){
                        foreach( $resource[ 'boxes' ] as $boxSlug => $box ){
                            
                            if( isset( _box::$boxes[ 'general' ][ $boxSlug ]['type']['args'] ) ){
                                foreach( _box::$boxes[ 'general' ][ $boxSlug ]['type']['args']  as $arg ){
                                    $boxArgs[] = $$arg; 
                                }
                                
                                add_meta_box(
                                    str_replace( array( '_' , ' ' ) , '-' , $resource['slug'] . '-' . $boxSlug ) , 
                                    _box::$boxes[ 'general' ][ $boxSlug ]['title'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['function'] ,
                                    $resource['slug'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['context'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['priority'] ,
                                    $boxArgs
                                );
                            }else{
                                add_meta_box(
                                    str_replace( array( '_' , ' ' ) , '-' , $resource['slug'] . '-' . $boxSlug ) , 
                                    _box::$boxes[ 'general' ][ $boxSlug ]['title'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['instance'] ,
                                    $resource['slug'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['context'] ,
                                    _box::$boxes[ 'general' ][ $boxSlug ]['type']['priority']
                                );
                            }
                        }
                    }
                }
            }
        }
        
        public static function shcode(){
            include 'shcode/main.php';
        }
    }
?>