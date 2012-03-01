<?php
    /* FOR DEBUGING ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*
        include get_template_directory() . '/lib/core/deb.php';
        _deb::inc();
    */

    /* DEFINES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    /*
        _DEV_   - developer name used in translation                            ex : __( 'Title for translate' , _DEV_ ); or _e( 'Title for translate' , _DEV_ );
        _DEVL_  - developer logo url used in admin panel
    */
    if( !defined( '_DEV_' ) ){
        define( '_DEV_' , 'cosmotheme' );
    }
	define( '_CT_' , 'CosmoThemes' );
    define( '_DEVL_' , get_template_directory_uri() . '/lib/core/images/freetotryme.png' );
    define( '_RES_' , 'cosmo_custom_posts' );
    define( '_SBAR_' , 'cosmo_custom_sidebars' );
    define( '_WG_' , 'widgets_relations' );
	define('ZIP_NAME'   , 'MultiPress' );
	define('DEFAULT_AVATAR_100'   , get_template_directory_uri()."/images/default_avatar_100.jpg" );
	define('DEFAULT_AVATAR'   , get_template_directory_uri() . "/images/default_avatar.jpg" );
	define('BLOCK_TITLE_LEN' , 50 );
	
    /* ADMIN SIDE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
    include get_template_directory() . '/lib/main.php';
	
	
	if(is_admin() && ini_get('allow_url_fopen') == '1'){
		/*New version check*/	
		
		if( _core::method( '_settings' , 'logic' , "extra" , "settings" , "notifications" , "version" ) ) {
			function versionNotify(){
				echo _core::method( '_api_call' , 'compareVersions' ); 
			}
		
			/* Add hook for admin <head></head> */
			add_action( 'admin_head' , 'versionNotify' );
		}

		/* Cosmo news */
		if( _core::method( '_settings' , 'logic' , "extra" , "settings" , "notifications" , "news") && !isset( $_GET['post_id'] )  && !isset( $_GET['post'] ) ) {
			function doCosmoNews(){
				echo _core::method( '_api_call' , 'getCosmoNews' ); 
			}
		
			/* Add hook for admin <head></head> */
			add_action( 'admin_head' , 'doCosmoNews' );
		}	
	}
	
	if( function_exists( 'add_theme_support' ) ){
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
    }

    _core::method( '_image' , 'add_size' );
    
    add_custom_background();
    
	/* add_theme_support( 'post-formats' , array( 'image' , 'video' , 'audio' ) ); */
	add_editor_style('editor-style.css');
	
	/* Localization */
    load_theme_textdomain( _DEV_ );
    load_theme_textdomain( _DEV_ , get_template_directory() . '/languages' );
    
    if ( function_exists( 'load_child_theme_textdomain' ) ){
        load_child_theme_textdomain( _DEV_ );
    }

	if( !_core::method( "_settings" , "logic" , "settings" , 'general' , 'theme' , 'show-admin-bar' ) ){
		add_filter( 'show_admin_bar' , '__return_false' );
	}
	
	if (function_exists('register_sidebar')) {
	    	register_sidebar(array(
	    		'name' => 'Sidebar Widgets',
	    		'id'   => 'sidebar-widgets',
	    		'description'   => 'These are widgets for the sidebar.',
	    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    		'after_widget'  => '</div>',
	    		'before_title'  => '<h2>',
	    		'after_title'   => '</h2>'
	    	));
	    }
/*  Custom Breadcrumbs  */

function the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
		echo get_option('home');
		echo '">';
		echo "Home";
		echo "</a> » ";
		if (is_category() || is_single()) {
			the_category('title_li=');
			if (is_single()) {
				echo " » ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}
	}
}

/*  Custom Breadcrumbs  */	

/*  Only load scripts on certain pages  */	

add_action( 'wp_print_styles', 'my_deregister_styles', 100 );

function my_deregister_styles() {

	if ( !is_page('events') ) {

		wp_deregister_style( 'tab-styles' );

	}
	
	if ( is_page() ) {

		wp_deregister_style( 'tabs' );

	}

}
/*  End Only load scripts on certain pages  */	

/* ADD CUSTOM TAXONOMY CLASSES TO POST_CLASS(); FOR CSS TARGETING */

add_filter( 'post_class', 'theme_t_wp_taxonomy_post_class', 10, 3 );
 
function theme_t_wp_taxonomy_post_class( $classes, $class, $ID ) {
    $suffix = "-filter";
		$taxonomy = 'event_type';
    $terms = get_the_terms( (int) $ID, $taxonomy );
    if( !empty( $terms ) ) {
        foreach( (array) $terms as $order => $term ) {
            if( !in_array( $term->slug, $classes ) ) {
                $classes[] = $term->slug . $suffix;
            }
        }
    }
    return $classes;
}


/* END ADD CUSTOM TAXONOMY CLASSES TO POST_CLASS(); FOR CSS TARGETING */

/* list users */


?>