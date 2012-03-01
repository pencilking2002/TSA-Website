<?php
    //delete_option( 'settings__blogging__posts' );

    /* SETTINGS GENERAL */
	_settings::$default[ 'settings' ][ 'general' ][ 'theme' ][ 'time' ]                         = 'yes';
	_settings::$default[ 'settings' ][ 'general' ][ 'theme' ][ 'fb_comments' ]                  = 'no';
	_settings::$default[ 'settings' ][ 'general' ][ 'theme' ][ 'copyright' ]                    = 'Copyright &copy; %year% MultiPress by <a href="http://cosmothemes.com" target="_blank">CosmoThemes</a>. All rights reserved';
    
    _settings::$default[ 'settings' ][ 'general' ][ 'upload' ][ 'enb_edit_delete' ]             = 'yes';
    _settings::$default[ 'settings' ][ 'general' ][ 'upload' ][ 'post_item_page' ]              = 'yes';
    
    /* FRONT PAGE */
    _settings::$default[ 'settings' ][ 'front_page' ][ 'resource' ][ 'type' ]                   = 'latest-post';
    
    /* BLOGGING */
    /* POST */
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'enb-featured' ]                = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'enb-lightbox' ]                = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar' ]                     = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-full' ]                = 3;
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-side' ]                = 2;
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-criteria' ]            = 'post_tag';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'meta' ]                        = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'social' ]                      = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'author-box' ]                  = 'no';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'posts' ][ 'source' ]                      = 'yes';
    
    /* PAGE */
    _settings::$default[ 'settings' ][ 'blogging' ][ 'pages' ][ 'enb-featured' ]                = 'no';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'pages' ][ 'enb-lightbox' ]                = 'no';
    
    /* ATTACHMENT */
    
    /* LIKES */
    _settings::$default[ 'settings' ][ 'blogging' ][ 'likes' ][ 'use' ]                         = 'yes';
    _settings::$default[ 'settings' ][ 'blogging' ][ 'likes' ][ 'limit' ]                       =  50;
    
    
    /* SETTINGS STYLE GENERAL */
    _settings::$default[ 'settings' ][ 'style' ][ 'general' ][ 'logo_type' ]                    = 'text';
    _settings::$default[ 'settings' ][ 'style' ][ 'general' ][ 'background' ]                   =  get_template_directory_uri() . '/lib/core/images/pattern/s.pattern.noise.png';
    _settings::$default[ 'settings' ][ 'style' ][ 'general' ][ 'background_color' ]             = '#ffffff';
    _settings::$default[ 'settings' ][ 'style' ][ 'general' ][ 'footer_bg_color' ]              = '#ffffff';
    
    
    /* SLIDESHOW */
    _settings::$default[ 'settings' ][ 'style' ][ 'slideshow' ][ 'opacity' ]                    = 70;
    _settings::$default[ 'settings' ][ 'style' ][ 'slideshow' ][ 'background' ]                 = '#000000';
    _settings::$default[ 'settings' ][ 'style' ][ 'slideshow' ][ 'color' ]                      = '#ffffff';
    
    
    /* SETTINGS LAYOUT STYLE */
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page' ]                    = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'front_page_view' ]               = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'l404' ]                          = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'author' ]                        = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'author_view' ]                   = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'page' ]                          = 'full';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'single' ]                        = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page' ]                     = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'blog_page_view' ]                = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch' ]                       = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'lsearch_view' ]                  = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'archive' ]                       = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'archive_view' ]                  = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'category' ]                      = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'category_view' ]                 = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'tag' ]                           = 'right';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'tag_view' ]                      = 'yes';
    _settings::$default[ 'settings' ][ 'layout' ][ 'style' ][ 'attachment' ]                    = 'right';

	/* MENUS SETTINGS */
	_settings::$default[ 'settings' ][ 'menus' ][ 'menus' ][ 'menu-limit' ]						= 5;
	_settings::$default[ 'settings' ][ 'menus' ][ 'menus' ][ 'footer-menu-limit' ]				= 5;
	
	/* PAYMENT SETTINGS */
	_settings::$default[ 'settings' ][ 'payment' ][ 'paypal' ][ 'currency' ]                    = 'USD';
    
    /* SETTINGS SLIDESHOW GENERAL */
	$slideshows_created=get_posts( array(
            'post_type' => 'slideshow',
            'post_status' => 'publish'
        ) ); 
		
	if(count($slideshows_created)>=1){	
		_settings::$default[ 'settings' ][ 'slideshow' ][ 'general' ][ 'item' ]					= $slideshows_created[0]->ID; 
	}
	_settings::$default[ 'settings' ][ 'slideshow' ][ 'general' ][ 'speed' ]                    = 6000;
    _settings::$default[ 'settings' ][ 'slideshow' ][ 'general' ][ 'news-limit' ]               = 180;
    _settings::$default[ 'settings' ][ 'slideshow' ][ 'general' ][ 'news-type' ]                = 'latest-post';
    
    
    /* EXTRA */
    _settings::$default[ 'extra' ][ 'settings' ][ 'notifications' ][ 'version' ]                = 'yes';
	_settings::$default[ 'extra' ][ 'settings' ][ 'notifications' ][ 'news' ]                   = 'yes';
?>