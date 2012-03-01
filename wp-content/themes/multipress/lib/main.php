<?php
    function cosmo__autoload( $class_name ){
	
        /* load widget class */
        if( substr( $class_name , 0 , 6 ) == 'widget'){
            /* widget_[ class-name ] > class-name > class_name */
            $class_name = str_replace( '-' , '_' , str_replace( 'widget_' , '' ,  $class_name ) );
			
            if( is_file( get_template_directory() . '/lib/widget/' . $class_name . '.php' ) ){
                include_once get_template_directory() . '/lib/widget/' . $class_name . '.php';
            }
        }
        /* load core class */
        if( substr( $class_name , 0 , 1 ) == '_'){
            /* core_[ class-name ] > class-name > class_name */
            $class_name = str_replace( '_' , '-' , substr(  $class_name , 1 , strlen( $class_name ) ) );
            if( is_file( get_template_directory() . '/lib/core/' . $class_name . '.php' ) ){

                include_once get_template_directory() . '/lib/core/' . $class_name . '.php';

                /* load additional functions */
                if( is_file( get_template_directory() . '/lib/load/' . $class_name . '.php' ) ){
                    include_once get_template_directory() . '/lib/load/' . $class_name . '.php';
                }

                if( is_file( get_template_directory() . '/lib/setup/' . $class_name . '.php' ) ){
                    include_once get_template_directory() . '/lib/setup/' . $class_name . '.php';
                }
            } 
        }
        
        /* load classes class */
        $class_name = str_replace( '_' , '-' , $class_name );
		if( is_file( get_template_directory() . '/lib/classes/' . $class_name . '.php' ) ){
			include_once get_template_directory() . '/lib/classes/' . $class_name . '.php';
            /* load setup */
            if( is_file( get_template_directory() . '/lib/setup/' . $class_name . '.php' ) ){
				include_once get_template_directory() . '/lib/setup/' . $class_name . '.php';
			}
		}
    }

    spl_autoload_register( "cosmo__autoload" ); 

	
	include_once get_template_directory() . '/lib/actions.php';
    include_once get_template_directory() . '/lib/setup/menu.php';

	include_once get_template_directory(). '/lib/core/audio-player.php';
	
	/*Session*/ 
	if (session_id() == ""){ 
		session_start(); 
	}	
	//session_start();
	if(!isset($_SESSION["Payment_Amount"])){
		$_SESSION["Payment_Amount"] = 0;
	}
	if(!isset($_SESSION["cart"])){
		$_SESSION["cart"] = array();
	}
	
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) { 	
	    /* last request was more than 60 minates ago*/
		$_SESSION['cart']=array();
		$_SESSION["Payment_Amount"] = 0;
	    //session_destroy();   /* destroy session data in storage */
	    //session_unset();     /* unset $_SESSION variable for the runtime */
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	
	function menu( $id ,  $args = array() ){

        $menu = new _menu( $args );

        $vargs = array(
            'menu'            => '',
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => isset( $args['class'] ) ? $args['class'] : '',
            'menu_id'         => '',
            'echo'            => false,
            'fallback_cb'     => '',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'depth'           => 0,
            'walker'          => $menu,
            'theme_location'  => $id ,
        );

        $result = wp_nav_menu( $vargs );
        
        if( empty( $result ) ){
            $result = $menu -> get_terms_menu();
            if( $menu -> need_more ){
                $result  = substr( trim( $result ) , 0 , -( 15 + strlen( $menu -> aftersubm ) ) );
                $result .= $menu -> custom_post_menu( $menu -> subclass )  . "</ul></li></ul>" . $menu -> aftersubm ;
            }else{
                $result  = substr( trim( $result ) , 0 , -( 5 + strlen( $menu -> aftersubm ) ) );
                $result .= $menu -> custom_post_menu( $menu -> subclass )  . "</ul>" . $menu -> aftersubm;
            }
        }else{
            if( $menu -> need_more ){
                $result  = substr( trim( $result ) , 0 , -( 5 + strlen( $menu -> aftersubm ) ) );
                $result .= $menu -> custom_post_menu( $menu -> subclass )  .  "</li></ul>" . $menu -> aftersubm ;
            }else{
                $result  = substr( trim( $result ) , 0 , -( 5 + strlen( $menu -> aftersubm ) ) );
                $result .= $menu -> custom_post_menu( $menu -> subclass )  . "</ul>" . $menu -> aftersubm;
            }
        }

        

        return $result;
    }
	
	function de_excerpt( $excerpt , $content , $length = 0 ){

        if( strlen( $excerpt) ){
			$result  = $excerpt ;
            
        }else{
            $content = trim( strip_shortcodes( $content ) );

            if( strlen( $content ) > strlen( strip_shortcodes( $content ) ) ){
                $length = ( $length == 0 ) ? strlen( $content ) : $length;
                $content = de_excerpt('', $content , $length );
            }

            $content = strip_tags( $content );
            $length = ( $length == 0 ) ? strlen( $content ) : $length;
            
            if( strlen( $content ) > $length ){
                $result  = mb_substr( $content , 0 , $length , 'UTF-8');
                $result .= '[...]';
            }else{
                $result  = $content;
            }
        }

        return $result;
    }
	
	function get__pages( $first_label = 'Select item' ){
        $pages = get_pages();
        $result = array();
        if( is_array( $first_label ) ){
            $result = $first_label;
        }else{
            if( strlen( $first_label ) ){
                $result[] = $first_label;
            }
        }
        foreach($pages as $page){
            $result[ $page -> ID ] = $page -> post_title;
        }

        return $result;
    }
    
    function cosmo_avatar( $user_info, $size, $default = DEFAULT_AVATAR ) {
		
		$avatar = '';
        if( is_numeric( $user_info ) ){
            if( get_user_meta( $user_info , 'custom_avatar' , true ) == -1 ){
                $avatar = '<img src="' . $default . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
            }else{
                if(  get_user_meta( $user_info , 'custom_avatar' , true ) > 0 ){
                    $cusom_avatar = wp_get_attachment_image_src( get_user_meta( $user_info , 'custom_avatar' , true ) , array( $size , $size ) );
                    $avatar = '<img src="' . $cusom_avatar[0] . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                }else{
                    $avatar = get_avatar( $user_info , $size , $default );
                }
            }
            
        }else{
            if( is_object( $user_info ) ){
                if( isset( $user_info -> user_id ) && is_numeric( $user_info -> user_id ) && $user_info -> user_id > 0 ){
                    if( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) == -1 ){
                        $avatar = '<img src="' . $default . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                    }else{
                        if( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) > 0 ){
                            $cusom_avatar = wp_get_attachment_image_src( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) , array( $size , $size ) );
                            if( isset( $cusom_avatar[0] ) ){
                                $avatar = '<img src="' . $cusom_avatar[0] . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                            }else{
                                $avatar = get_avatar( $user_info , $size , $default );
                            }
                        }else{
                            $avatar = get_avatar( $user_info , $size , $default );
                        }
                    }
                }else{
                    $avatar = get_avatar( $user_info , $size , $default );
                }
            }else{
                $avatar = get_avatar( $user_info , $size , $default );
            }
        }
		
        return $avatar;
	}
    
    function de_remove_wpautop( $content ) {
        $content = do_shortcode( shortcode_unautop( $content ) );
        $content = preg_replace('#^<\/p>|^<br \/>|^<br>|<p>$#', '', $content);
        return $content;
    }
?>