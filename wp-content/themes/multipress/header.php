<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
    <title><?php bloginfo('name'); ?> ›› <?php bloginfo('description'); ?><?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?></title>
    
    <meta name="robots"  content="index, follow"/>
    
	<?php
		if(is_single()){
			$post = $wp_query -> post;
			if( trim( $post -> post_excerpt ) != '' ){
				$descrip = strip_tags( $post -> post_excerpt );
			}else{
				$descrip = strip_tags( $post -> post_content );
			}	
			$descrip_more = '';
			if ( strlen($descrip) > 155 ) {
				$descrip = substr( $descrip , 0 , 155);
				$descrip_more = ' ...';
			}
			$descrip = str_replace( '"' , '' , $descrip );
			$descrip = str_replace( "'" , '' , $descrip);
			$descripwords = preg_split( '/[\n\r\t ]+/' , $descrip , -1 , PREG_SPLIT_NO_EMPTY );
			array_pop( $descripwords );
			$descrip = implode( ' ' , $descripwords ) . $descrip_more;
			echo '<meta name="description" content="' . $descrip . '">';
			echo '<meta property="og:description" content="' . $descrip . '"/>';
		}else{
			echo '<meta name="description" content="' . get_bloginfo( 'description' ) . '">';
			echo '<meta property="og:description" content="' . get_bloginfo( 'description' ) . '"/>';
		}	
	?>
		
	<?php if(is_single() || is_page()){ ?>
		<meta property="og:title" content="<?php the_title() ?>"/>
		<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
		<meta property="og:url" content="<?php the_permalink() ?>"/>
		<meta property="og:type" content="article"/>
		<meta property="og:locale" content="en_EN"/>
		
		<?php 
			global $post;
			$src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , array( 50 , 50 ) );
			echo '<meta property="og:image" content="'.$src[0].'"/>'; 
			echo ' <link rel="image_src" href="'.$src[0].'"/>'; 			
			wp_reset_query();	
		}else{ ?>
			<meta property="og:title" content="<?php echo get_bloginfo('name'); ?>"/>
			<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
			<meta property="og:url" content="<?php echo home_url() ?>/"/>
			<meta property="og:type" content="blog"/>
			<meta property="og:locale" content="en_EN"/>
			<meta property="og:image" content="<?php echo get_template_directory_uri()?>/screenshot.fb.png"/> 
	<?php
		}
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all"/>
	
	<?php 
		wp_enqueue_script( "jquery" );	
		if ( is_singular() ){ wp_enqueue_script( "comment-reply" ); } 
		wp_head();
	?>
    <?php
        if( is_single() ){
    ?>
            <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/all.css.php?post=<?php echo $post -> ID; ?>" type="text/css" media="all"/>
            <?php
                if( _core::method( '_map' , 'markerExists' , $post -> ID  ) ){
                    ?> <script src="http://maps.googleapis.com/maps/api/js?sensor=true" type="text/javascript"></script> <?php
                }
            ?>
            
            <script src="<?php echo get_template_directory_uri() ?>/js/all.js.php?post=<?php echo $post -> ID; ?>" type="text/javascript"></script>
						
    <?php    
        }else{
    ?>
            <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/all.css.php" type="text/css" media="all"/>
            
            <script src="<?php echo get_template_directory_uri() ?>/js/all.js.php" åtype="text/javascript"></script>
						<!-- events page resources -->
						<script src="/wp-content/themes/multipress/js/jquery.tools.min.js" type="text/javascript"></script>
						<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/tab-styles.css" type="text/css" media="all"/>	
						<!-- End events page resources -->
						
    <?php        
        }
        
        $favico=_core::method( "_settings" , "get" , "settings" , "style" , "general" , "favicon" );
        if(strlen($favico)){?>
            <link rel="shortcut icon" href="<?php echo $favico ?>" />
            <?php
        }else{
            ?>
                <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"/>
            <?php
        }
    ?>

	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
	
    <!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
</head>

    <?php   
        if( is_home() && is_front_page() ){
            $classes = 'home';
        }else{
            $classes = '';
        }
        $body_classes = get_body_class($classes);
        
        if( is_array( $body_classes ) ){
            $body_classes = implode( " " , $body_classes );
            str_replace( "fixed-width" , "" , $body_classes );
        }
        
        if(_core::method( '_settings' , 'logic' , 'settings' , 'style' , 'general' , 'fixed-width-layout' ) ){
            $body_classes .= " fixed-width";
						$body_classes .= " page";
        }
        
        if( isset( $_GET[ 'fp_type' ] ) && !empty( $_GET[ 'fp_type' ] ) ){
            $body_classes .= ' custom_posts';
        }
    ?>

<body <?php body_class( $body_classes ) ?>>
	<script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript" id="fb_script"></script>
	<div style="display:none" id="ajax-indicator">
        <div style="position:absolute; margin-left:-77px; margin-top:-39px; top:50%; left:50%;">
            <object width="150" height="150" type="application/x-shockwave-flash" data="<?php echo get_template_directory_uri() ?>/images/preloader.swf" id="ajax-indicator-swf" style="visibility: visible;">
                <param name="quality" value="high" />
                <param name="allowscriptaccess" value="always" />
                <param name="wmode" value="transparent" />
                <param name="scale" value="noborder" />
            </object>
        </div>
    </div>
	
	<?php $header_classes = "b_head  absolute clearfix ";
		
		/*if slider is defined add "cosmo-slider-inside" */ ?>
    <div class="b_body" id="wrapper" >
		<div class="b_body_c">
            <?php 
                /*  header  ( ned class absolute ) */ 
				$slideshow = _core::method( '_meta' , 'get' , _core::method( '_settings' , 'get' , 'settings' , 'slideshow' , 'general' , 'item' ) , 'slideshow' );
                if( _core::method( '_slideshow' , 'exists_slideshow' )  && !empty( $slideshow ) && is_array( $slideshow ) && !isset( $_GET[ 'fp_type' ] ) ){
                    $b_head_classes = 'b_head cosmo-slider-inside clearfix';
					if(_core::method( '_settings' , 'logic' , 'settings' , 'style' , 'slideshow' , 'under_menu' ) ){
                        $b_head_classes.=" absolute";
                    }
                }else{
                    $b_head_classes = 'b_head clearfix';
                }

				$floating_header=_core::method('_settings','logic','settings','style','general','floating-header');
            ?>
            <header class="<?php echo $b_head_classes; ?>" id="header">
				<div class="header-wrapper <?php echo $floating_header?"can-fly":"";?>" id="header-wrapper">
					<div class="b_page clearfix">
						<div class="branding">
                            
                            <?php /* logo */ ?>
							<?php get_template_part( 'templates/logo' ); ?>
                            
                            <?php /* menu */ ?>
							<div class="cosmo-menu b w_590">
								<nav id="access" role="navigation">
									<?php /* 
									if ( is_user_logged_in() ) {
									    wp_nav_menu(array('theme_location' => 'header', 'menu_id' => 'logged_in_tsa_menu'));
									} else {
									    wp_nav_menu(array('theme_location' => 'header', 'menu_id' => 'Main Menu'));
									};
								*/	?>
									
									<?php /* login form */ ?>
										<div class="utilities-container">
											</div>
		<?php get_template_part( 'templates/login' ); ?>
	
			<div class="login-form donate"><a href="<?php echo esc_url( get_permalink( get_page_by_title('donate') ) ); ?>">Donate</a></div>
			<div class="login-form contact">Contact</div>
			<div class="login-form about">About</div>
			
		<div class="cosmo-icons">
										<?php  $limit=_core::method( '_settings' , 'get' , 'settings' , 'menus' , 'menus' , 'menu-limit');?>
                                        <?php echo menu( 'header' , array( 'class' => 'sf-menu fl dynamic-settings-style-menu-top_menu' , 'current-class' => 'active' , 'number-items' => $limit ) ); ?>
									</div>
								</nav>
							</div>
                            
                            
                            
						</div>
					</div>
				</div>

                <?php /* slideshow */ ?>
                <?php get_template_part( 'templates/slideshow' ); ?>
			</header>
                <?php /* breadcrumbs */ ?>
				<?php 
                    if( _core::method("_settings","logic","settings","general","theme","show-breadcrumbs") && ( !is_front_page() || isset( $_GET[ 'fp_type' ] ) ) ){
                        echo '<div class="b w_930 breadcrumbs">';
                        echo '<ul>';
                        _core::method("post","dimox_breadcrumbs");
                        echo '</ul>';
                        echo '</div>';
                    }
				?>