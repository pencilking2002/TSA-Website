<?php
    class _panel{
		static $menu;
		static $fields;

        public static function main_builder( $slug ){
            ?>
            <div class="admin-page">
                <?php self::header(); ?>
                <div class="admin-content main-page">                    
                <?php
                    foreach( self::$menu as $page => $tabs ){
                        foreach( $tabs as $tab => $groups ){
                            foreach( $groups as $group => $item ){
                                if( isset( $item['type'] ) &&  $item['type'] == 'main'  ){
                                    ?>
                                        <div class="info-panel <?php echo $item['classes'] ?>">
                                            <h3>
                                                <a href="<?php echo self::path( $page . '__' . $tab . '__' . $group ); ?>">
                                                    <?php
                                                        if( isset( $item['main_title'] ) ){
                                                            echo $item['main_title'];
                                                        }else{
                                                            echo $item['title'];
                                                        }
                                                    ?>
                                                </a>
                                            </h3>
                                            <p>
                                                <?php
                                                    if( isset( $item[ 'main_description' ] ) ){
                                                        echo $item['main_description'];
                                                    }else{
                                                        echo $item['description'];
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    <?php
                                    break;
                                }
                            }
                            break;
                        }
                    }
                ?>
                </div>
                </div>
            <?php
        }
        
        public static function builder( $slug ) {

            $items = explode( '__' , $slug );

            if( !isset( $items[1] ) ){
                exit();
            }else{
                $page   = $items[0];
                $tab    = $items[1];
                $group  = $items[2];
            }

            $label          = isset( self::$menu[ $page ][ $tab ][ $group ][ 'label' ] ) ? self::$menu[ $page ][ $tab ][ $group ][ 'label' ] : '';
            $title          = isset( self::$menu[ $page ][ $tab ][ $group ][ 'title' ] ) ? self::$menu[ $page ][ $tab ][ $group ][ 'title' ] : '';
            $description    = isset( self::$menu[ $page ][ $tab ][ $group ][ 'desctiption' ] ) ? self::$menu[ $page ][ $tab ][ $group ][ 'desctiption' ] : '';
            $update         = isset( self::$menu[ $page ][ $tab ][ $group ][ 'update' ] ) ? self::$menu[ $page ][ $tab ][ $group ][ 'update' ] : true ;

            $tabs  = isset( self::$menu[ $page ] ) ? self::$menu[ $page ] : array() ;
            
            if( count( $tabs ) > 1 ){
                echo '<div class="admin-page">';
            }else{
                echo '<div class="admin-page nomenu">';
            }
            
            self::header( $page , $tab );
            self::page( $title , $page , $tab , $group , $description , $update );
            echo '</div>';
        }

        /* registerul mutat in classa register */
        public static function register( ){
            $register_tab = array();
            if( is_array( self::$register ) && !empty( self::$register ) ){
                foreach( self::$register as $pages => $tabs ){
                    if( is_array( $tabs ) && !empty( $tabs ) ){
                        foreach( $tabs as $tab => $groups ){
                            if( substr( $tab , 0 , 1 ) != '_'){
                                if( !in_array( $tab , $register_tab ) ){
                                    register_setting( $page . '__' . $tab , $tab );
                                }
                            }
                        }
                    }
                }
            }
        }
        
        public static function hook( ){
            global $pl_menu , $pl_fields , $pl_default;
            
            if( is_array( $pl_menu ) && !empty( $pl_menu ) ){
                foreach( $pl_menu[ _DEV_ ] as $item => $value ){
                    self::$menu[ _DEV_ ][ $item ] = $value;
                }
            }
            
            if( is_array( $pl_fields ) && !empty( $pl_fields ) ){
                foreach( $pl_fields as $item => $value ){
                    self::$fields[ $item ] = $value; 
                }
            }
            if( is_array( $pl_default ) && !empty( $pl_default ) ){
                foreach( $pl_default as $item => $value ){
                    self::$default[ $item ] = $value; 
                }
            }
        }
        
		function header( $page = null , $current_tab = null ){
            
			$result = '';
            $ct = current_theme_info();
			
			$result .= '<div class="admin-intro">';
            $result .= '<img src="' . _DEVL_ . '" />';
			$result .= '<span class="theme">' . $ct -> title . ' ' . __( 'Version' , _DEV_ ).': ' . $ct -> version . '</span>';
            $result .= '</div>';
            
            $tabs  = isset( self::$menu[ $page ] ) ? self::$menu[ $page ] : array() ;
            
            if( !empty( $page ) && !empty( $current_tab ) && count( $tabs ) > 1 ){
                if( is_array( $tabs ) ){
                    $result .= '<div class="admin-menu aside">';
                    $result .= '<ul>';
                    foreach( $tabs as $tab => $groups ){
                        foreach( $groups as $group => $info ){
                            if( isset( $info['main_label'] ) ){
                                $result .= '<li ' . self::page_class( $tab , $current_tab ) . '><a href="' . self::path( $page . '__' . $tab . '__' . $group ) . '">' . $info['main_label'] . '</a></li>';
                            }
                        }
                    }
                    $result .= '</ul>';
                    $result .= '</div>';
                }
            }
            
            echo $result;
		}

        function tabber( $page , $tab , $current_group ){
            $result = '';
            $groups  = self::$menu[ $page ][ $tab ];

			if( is_array( $groups )  && count( $groups ) > 1 ){
				$result .= '<div class="admin-menu tabber">';
				$result .= '<ul>';
				foreach( $groups as $group => $info ){
                    $result .= '<li ' . self::page_class( $group , $current_group ) . '>';
                    $result .= '<a href="' . self::path( $page . '__' . $tab . '__' . $group ) . '">' . $info['label'] . '</a>';
                    $result .= '</li>';
				}
				$result .= '</ul>';
				$result .= '</div>';
			}
            echo $result;
        }

        function path( $slug ){
            $path = 'admin.php?page=' . $slug;
            return $path;
        }

        function page_class( $slug , $current ){
            
            if( $current == $slug ){
                if( substr( $slug , 0 , 1 ) == '_' ){
                    $slug = substr( $slug , 1 , strlen( $slug ) );
                }
            
                $slug = str_replace( '_' , '-' , $slug  );
                
                return 'class="current ' . $slug . '"';
            }else{
                if( substr( $slug , 0 , 1 ) == '_' ){
                    $slug = substr( $slug , 1 , strlen( $slug ) );
                }
            
                $slug = str_replace( '_' , '-' , $slug  );
                
                return ' class="' . $slug . '"';
            }

        }

        function page( $title , $page , $tab , $group , $description = '' , $update = true ){
?>
            <div class="admin-content">
                <?php self::tabber( $page , $tab , $group ); ?>
                
                <div class="title">
                    <h2><?php echo $title; ?></h2>
                    <?php
                        if( strlen( $description ) ){
                            ?><p><?php echo $description; ?></p><?php
                        }
                    ?>
                </div>
            <?php
                if( $update ){
                    ?><form action="options.php" method="post"><?php
                }
                        settings_fields( $page . '__' . $tab . '__' . $group );
                        echo self::fields( $page , $tab , $group );

                if( $update ){
            ?>
                        <div class="standard-generic-field submit">
                            <div class="field">
                                <input type="submit" value="<?php _e( 'Update Settings' , _DEV_ ); ?>" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
            <?php
                }else{
                    ?><div class="record submit"></div><?php
                }
            ?>
			</div>
<?php
        }

        function fields( $page , $tab ,  $group ){
            $result = '';
            if( isset( self::$fields[ $page ][ $tab ][ $group ] ) ){
                foreach( self::$fields[ $page ][ $tab ][ $group ] as $set => $field ){
                    $field['group'] = $page . '__' . $tab . '__' . $group;
                    $field['set']   = $set;
                    
                    if( !isset( $field['value'] ) ){
                        $field['value'] = _settings::get( $page , $tab , $group , $set );
                    }

                    $field['ivalue'] = _settings::get( $page , $tab , $group , $set );
                    
                    /* special for upload-id*/
                    if( isset( $field['type'] ) ){
                        $type = explode( '--' , $field['type'] );
                        if( ( isset( $type[2] ) && $type[2] == 'upload-id' ) || ( isset( $type[1] ) && $type[1] == 'upload-id' ) ){
							$settings = _settings::get( $page , $tab , $group );
                            $field['valueID'] = isset( $settings[ $set . '-id' ] ) ? $settings[ $set . '-id' ] : 0;
                        }
                    }

                    $result .= _fields::layout( $field );
                }
            }
			
            return $result;
        }
    }
?>