<?php
    header( 'Content-type:text/javascript' );
    include '../../../../wp-load.php';
    
    $initScript  = '';

	/* ajax url */
    $siteurl = get_option( 'siteurl' );
    if( !empty($siteurl) ){
        $siteurl = rtrim( $siteurl , '/') . '/wp-admin/admin-ajax.php' ;
    }else{
        $siteurl = home_url('/wp-admin/admin-ajax.php');
    }
?>
<?php if( false ){ ?><script><?php }?>
    
    var mail = new Object();
    mail = {
        'name' : "<?php _e( 'Error, fill all required fields ( name )'  , _DEV_ );?>",
        'email' : "<?php _e( 'Error, fill all required fields ( email )' , _DEV_ ) ?>",
        'message' : "<?php _e( 'Error, fill all required fields ( message )' , _DEV_ )?>"
    };
    
<?php

    if( isset( $_GET[ 'post' ] ) &&  (int)$_GET[ 'post' ] > 0 ){
        $postID = (int)$_GET[ 'post' ];
        $slideshowID = _core::method( '_meta' , 'get' , (int)$_GET[ 'post' ] , 'posts-settings' , 'slideshow' );
        
        if(  $slideshowID > 0 ){
            $slideshow = _core::method( '_meta' , 'get' , $slideshowID , 'slideshow' );
        }else{
            $slideshow = array();
        }
    }else{
        $postID = 0;
        $slideshow = _core::method( '_meta' , 'get' , _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'item' ) , 'slideshow' );
    }
    
    if ( !empty( $slideshow ) && is_array( $slideshow ) ) {
?>
/* ================================================================================================================================================ */
/* SLIDESHOW                                                                                                                                        */
/* ================================================================================================================================================ */

<?php        
        include 'slideshow.js';
    }    
?>
    
/* ================================================================================================================================================ */
/* SUPERFISH , SUPERSUBS , MOSAIC                                                                                                                   */
/* ================================================================================================================================================ */
    
<?php    
	include 'jquery.superfish.js';
	include 'jquery.supersubs.js';
	include 'jquery.mosaic.1.0.1.min.js';
    include 'jquery.cookie.js';
?> 

/* ================================================================================================================================================ */
/* SHOPING CARD                                                                                                                                     */
/* ================================================================================================================================================ */
    
<?php 	
	include 'cart.js';
?>
    
<?php	
	if( (int)_widgets::count_widget('widget_custom_post') > 0){ 
		$slideshow_ = _core::method( '_meta' , 'get' , _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'item' ) , 'slideshow' );
		
        if( !empty( $slideshow_ ) && is_array( $slideshow_ ) ){
			$fixed_width = _core::method( '_settings' , 'logic' , 'settings' , 'style' , 'general' , 'fixed-width-layout' );
			/*for fixed width slider*/
			if($fixed_width){
?>			
				var top_possition = 330;
<?php
			}else{ /*for full width slider*/
?>			
				var top_possition = 640;
<?php		
			}
		}else{ /*no slideshow */
?>		
			var top_possition = 100;
<?php	
		}
		include '../lib/core/js/custom_post.js';
	}
?>

/* ================================================================================================================================================ */
/* PRETTY PHOTO                                                                                                                                     */
/* ================================================================================================================================================ */
    
<?php    
	include 'jquery.prettyPhoto.js';
	include 'prettyPhoto.settings.js';
?>

/* ================================================================================================================================================ */
/* JSCROLL PANEL                                                                                                                                    */
/* ================================================================================================================================================ */
<?php
    include 'jquery.jscrollpane.min.js';
    include 'jquery.mousewheel.js';
    include 'jquery.easing.min.js';
    include 'functions.js';
    include 'likes.js';
?>
    
    /* ================================================================================================================================================ */
    /* GENERAL VARS                                                                                                                                     */
    /* ================================================================================================================================================ */
    
    var ajaxurl = "<?php echo $siteurl; ?>";
    var cookies_prefix = "<?php //echo ZIP_NAME; ?>";  
    var themeurl = "<?php echo get_template_directory_uri(); ?>";

	/* ================================================================================================================================================ */
    /* LIKES VARS                                                                                                                                     */
    /* ================================================================================================================================================ */

	likes.registration_required=<?php echo (_core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'likes' , 'req-registr' ) && !is_user_logged_in() )? 'true' : 'false'; ?>;

	<?php 	$login_page=_core::method( '_settings' , 'get' , 'settings' , 'general' , 'theme' , 'login-page' );
			if(is_numeric($login_page)){?>
				likes.login_url="<?php echo get_permalink($login_page); ?>";
	<?php	} ?>

	/* ================================================================================================================================================ */
    /* UPLOADER                                                                                                                                     */
    /* ================================================================================================================================================ */

<?php include "uploader.js"; ?>
    
	/* ================================================================================================================================================ */
    /* TABS                                                                                                                                     */
    /* ================================================================================================================================================ */

<?php include "jquery.tabs.pack.js"; ?>

	/* ================================================================================================================================================ */
    /* FRONTEND                                                                                                                                     */
    /* ================================================================================================================================================ */

<?php 

    include "frontend.js"; 
    
    if( _core::method( '_map' , 'markerExists' , $postID  ) ){
        include "../lib/core/js/map.js";
    }
?>    

	/* ================================================================================================================================================ */
    /* TRANSLATIONS                                                                                                                                     */
    /* ================================================================================================================================================ */

<?php
	
	include "translations.js.php";

?>

	/* ================================================================================================================================================ */
    /* SCROLL TO                                                                                                                                     */
    /* ================================================================================================================================================ */

<?php include "jquery.scrollTo-1.4.2-min.js";?>

	/* ================================================================================================================================================ */
    /* Twitter widget                                                                                                                               */
    /* ================================================================================================================================================ */
<?php include "slides.min.jquery.js";?>
	/* twitter widget */
	if (jQuery().slides) {
		jQuery(".dynamic .cosmo_twitter").slides({
			play: 5000,
			effect: 'fade',
			generatePagination: false,
			autoHeight: true
		});
	}
<?php    
    if ( !empty( $slideshow ) && is_array( $slideshow ) ) {
?>
    /* ================================================================================================================================================ */
    /* SLIDESHOW SETTINGS                                                                                                                               */
    /* ================================================================================================================================================ */

    var slideshowSpeed = <?php echo _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'speed' ); ?>;
    var photos = [
        <?php
            $i = 0;
            foreach( $slideshow as $slider ){

                if( isset( $slider[ 'slider-type' ] ) && !empty( $slider[ 'slider-type' ] ) && $slider[ 'slider-type' ] != 'image' && isset( $slider[ 'slider-post-id' ] ) && (int)$slider[ 'slider-post-id' ] > 0 ){
                    $post = get_post( $slider[ 'slider-post-id' ] );
                    if( strlen( $post -> post_excerpt )  ){
                        if( strlen( $post -> post_excerpt ) > 180 ){
                            $post_description = mb_substr( $post -> post_excerpt , 0 , 180 ) . '..';
                        }else{
                            $post_description = $post -> post_excerpt;
                        }
                    }else{
                        if( strlen( $post -> post_content ) > 180 ){
                            $post_description = mb_substr( $post -> post_content , 0 , 180 ) . '...';
                        }else{
                            $post_description = $post -> post_content;
                        }
                    }

                    $post_title_side = explode( ' ' , $post -> post_title );
                    $post_title_side[ count( $post_title_side ) -1  ] = '<span>' . $post_title_side[ count( $post_title_side ) -1  ] . '</span>';

                    $post_title = implode( ' ' , $post_title_side );

                    $post_link = get_permalink( $post -> ID );

                    if( has_post_thumbnail( $post -> ID ) ){
                        $post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'slideshow_big' );
                    }else{
                        $post_image[0] = '';
                    }   
                }else{
                    $post_title = '';
                    $post_description = '';
                    $post_link = '';
                    $post_image[0] = '';
                }

                if( isset( $slider[ 'slider-image-id' ] ) && (int)$slider[ 'slider-image-id' ] > 0 ){
                    $image = wp_get_attachment_image_src( $slider['slider-image-id'] , 'full' );
					$image_id=$slider['slider-image-id'];
                }else{
                    $image = $post_image;
					$image_id=get_post_thumbnail_id( $post -> ID );
                }

                if( isset( $slider[ 'slider-title' ] ) && !empty( $slider[ 'slider-title' ] ) ){
                    $title_side = explode( ' ' , $slider[ 'slider-title' ] );
                    $title_side[ count( $title_side ) -1  ] = '<span>' . $title_side[ count( $title_side ) -1  ] . '</span>';
                    $title = implode( ' ' , $title_side );
                }else{
                    $title = $post_title;
                }

                if( isset( $slider[ 'slider-description' ] ) && !empty( $slider[ 'slider-description' ] ) ){
                    $description = $slider[ 'slider-description' ];
                }else{
                    $description = $post_description;
                }

                if( isset( $slider[ 'slider-link' ] ) && !empty( $slider[ 'slider-link' ] ) ){
                    $link = $slider[ 'slider-link' ];
                }else{
                    $link = $post_link;
                }

				if(_core::method( '_settings' , 'logic' , 'settings' , 'style' , 'general' , 'fixed-width-layout' ) ){
					$image=wp_get_attachment_image_src($image_id,array(990,9999));
				}

                if( $i == 0 ){
                    ?>
                        {
                            "image" : "<?php echo $image[0]; ?>",
                            "url" : "<?php echo $link; ?>",
                            "firstline" : "<?php echo str_replace( PHP_EOL , '<br/>', $title); ?>",
                            "secondline" : "<?php echo str_replace( PHP_EOL , '<br/>', $description); ?>"
                        }
                    <?php
                    $i = 1;
                }else{
                    ?>
                        , {
                            "image" : "<?php echo $image[0]; ?>",
                            "url" : "<?php echo $link; ?>",
                            "firstline" : "<?php echo str_replace( PHP_EOL , '<br/>', $title); ?>",
                            "secondline" : "<?php echo str_replace( PHP_EOL , '<br/>', $description); ?>"
                        }
                    <?php
                }
            }
        ?>
    ];
<?php
    }
?>
    <?php ob_start(); ob_clean(); ?>
        jQuery('.scroll-pane').jScrollPane();
        
    <?php $initScript .= trim( ob_get_clean() ); ?>

    /* ================================================================================================================================================ */
    /*  JQUERY SETTINGS                                                                                                                                 */
    /* ================================================================================================================================================ */
    
    jQuery(function(){
        <?php echo $initScript; ?>

    });
    
    
   

<?php if( false ){?></script><?php }?>