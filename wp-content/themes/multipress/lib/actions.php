<?php
    add_action( 'admin_menu'                                , array( '_register' , 'panelMenu' ) );
    add_action( 'admin_init'                                , array( '_register' , 'settings' ) );
    
    /* register resource */
    add_action( 'init'                                      , array( '_register' , 'resources' ) , 1 );
    
    add_action( 'init'                                      , array( '_load' , 'js' ) , 2 );
    add_action( 'init'                                      , array( '_load' , 'css' ) , 3 );
    
    /* ajax actions */
    add_action( 'wp_ajax_search'                            , array( '_resources' , 'search' ) );
    add_action( 'wp_ajax_text_preview'                      , array( '_text' , 'preview' ) );
    add_action( 'wp_ajax_resources_load'                    , array( '_resources' , 'load' ) );
    add_action( 'wp_ajax_taxonomy_load'                     , array( '_taxonomy' , 'load' ) );
    add_action( 'wp_ajax_meta_load'                         , array( '_meta' , 'load' ) );
    add_action( 'wp_ajax_box_load'                          , array( '_box' , 'load' ) );
    add_action( 'wp_ajax_mbox_load'                         , array( '_mbox' , 'load' ) );
    add_action( 'wp_ajax_attachment_load'                   , array( '_attachment' , 'load' ) );
    add_action( 'wp_ajax_sidebar_load'                      , array( '_sidebar' , 'load' ) );
    add_action( 'wp_ajax_additional_load'                   , array( '_additional' , 'load' ) );
    add_action( 'wp_ajax_attachdocs_load'                   , array( '_attachdocs' , 'load' ) );
    add_action( 'wp_ajax_program_load'                      , array( '_program' , 'load' ) );
    add_action( 'wp_ajax_reset_likes'                       , array( '_likes' , 'reset' ) );
    add_action( 'wp_ajax_generate_likes'                    , array( '_likes' , 'generate' ) );
    add_action( 'wp_ajax_remove_likes'                      , array( '_likes' , 'remove' ) );
    add_action( 'wp_ajax_nopriv_set_like'                   , array( '_likes' , 'set' ) );
    add_action( 'wp_ajax_set_like'                          , array( '_likes' , 'set' ) );
    add_action( 'wp_ajax_map_load'                          , array( '_map' , 'load' ) );

	if( class_exists( '_js_translator') ){
		add_action( 'wp_ajax_generate_js_translation' , array( '_js_translator' , 'generate' ) );
		add_action( 'wp_ajax_generate_backend_js_translation' , array( '_js_translator' , 'generate_backend' ) );
	}
    
    if( !( _core::method( '_settings' , 'get' , 'settings' , 'social' , 'facebook' , 'secret' ) == '' || _core::method( '_settings' , 'get' , 'settings' , 'social' , 'facebook' , 'app_id' ) == '' ) ){
        if( is_user_logged_in () ){
            add_action('wp_ajax_fb_user'                    , array( '_facebook' , 'user') );
        }else{
            add_action('wp_ajax_nopriv_fb_user'             , array( '_facebook' , 'user') );
        }
    }
    
    if(is_user_logged_in () ){
        add_action('wp_ajax_contact'                        , array('_contact' , 'mail') );
    }else{
        add_action('wp_ajax_nopriv_contact'                 , array('_contact' , 'mail') );
    }
    
	add_action( 'wp_ajax_my_likes'                          , array( 'post' , 'my_likes') );
	add_action( 'wp_ajax_nopriv_my_likes'                   , array( 'post' , 'my_likes') );
    
    add_action( 'wp_ajax_widget_load'                       , array( 'widget_children_posts' , 'load' ) );
    add_action( 'wp_ajax_slideshow_load'                    , array( '_slideshow' , 'load' ) );
    add_action( 'wp_ajax_remove_post'                       , array( 'post' , 'remove_post' ) );
    
    /* add add_image_post action */
    add_action( 'wp_ajax_add_image_post'                    , array( 'post' , 'add_image_post' ) );
    add_action( 'wp_ajax_nopriv_add_image_post'             , array( 'post' , 'add_image_post' ) );
    
    /* add add_video_post action */
    add_action( 'wp_ajax_add_video_post'                    , array( 'post' , 'add_video_post' ) );
    add_action( 'wp_ajax_nopriv_add_video_post'             , array( 'post' , 'add_video_post' ) );
    
    /* add add_text_post action */
    add_action( 'wp_ajax_add_text_post'                     , array( 'post' , 'add_text_post' ) );
    add_action( 'wp_ajax_nopriv_add_text_post'              , array( 'post' , 'add_text_post' ) );
    
	/* add add_file_post action */
    add_action( 'wp_ajax_add_file_post'                     , array( 'post' , 'add_file_post' ) );
    add_action( 'wp_ajax_nopriv_add_file_post'              , array( 'post' , 'add_file_post' ) );

	/* add add_audio_post action */
    add_action( 'wp_ajax_add_audio_post'                    , array( 'post' , 'add_audio_post' ) );
    add_action( 'wp_ajax_nopriv_add_audio_post'             , array( 'post' , 'add_audio_post' ) );

	/**/
	add_action( 'wp_ajax_redirect_shopping_cart'            , array( '_cart' , 'redirect_shopping_cart') );
    add_action( 'wp_ajax_nopriv_redirect_shopping_cart'     , array( '_cart' , 'redirect_shopping_cart') );
	
	add_action( 'wp_ajax_add_to_cart'                       , array( '_cart' , 'addtocart') );
    add_action( 'wp_ajax_nopriv_add_to_cart'                , array( '_cart' , 'addtocart') );
	
	add_action( 'wp_ajax_get_cart_total'                    , array( '_cart' , 'show_cart_total') );
    add_action( 'wp_ajax_nopriv_get_cart_total'             , array( '_cart' , 'show_cart_total') );
	
	add_action( 'wp_ajax_get_cart_details_updated'          , array( '_cart' , 'get_cart_details_updated') );
    add_action( 'wp_ajax_nopriv_get_cart_details_updated'   , array( '_cart' , 'get_cart_details_updated') );
	
	add_action( 'wp_ajax_remove_cart_item'                  , array( '_cart' , 'remove_product') );
    add_action( 'wp_ajax_nopriv_remove_cart_item'           , array( '_cart' , 'remove_product') );
	
	add_action( 'wp_ajax_confirm_payment'                   , array( '_cart' , 'confirm_payment') );
    add_action( 'wp_ajax_nopriv_confirm_payment'            , array( '_cart' , 'confirm_payment') );
	
	add_action( 'wp_ajax_save_transaction'                  , array( '_cart' , 'save_transaction') );
    add_action( 'wp_ajax_nopriv_save_transaction'           , array( '_cart' , 'save_transaction') );
    add_action( 'wp_ajax_list_posts'                        , array( 'widget_custom_post' , 'list_posts' ) );
    add_action( 'wp_ajax_nopriv_list_posts'                 , array( 'widget_custom_post' , 'list_posts' ) );
	add_action( 'wp_ajax_get_single_posts'                  , array( 'widget_custom_post' , 'get_single_posts' ) );
    add_action( 'wp_ajax_nopriv_get_single_posts'           , array( 'widget_custom_post' , 'get_single_posts' ) );
	
	/* add add_text_post action */
    add_action( 'wp_ajax_play_video'                        , array( 'post' , 'play_video' ) );
    add_action( 'wp_ajax_nopriv_play_video'                 , array( 'post' , 'play_video' ) );
    
    /* columns shortcodes */
    add_shortcode( 'twocol_one'                             , array( '_shcode' , 'de_twocol_one' ) );
    add_shortcode( 'twocol_one_first'                       , array( '_shcode' , 'de_twocol_one_first' ) );
    add_shortcode( 'twocol_one_last'                        , array( '_shcode' , 'de_twocol_one_last' ) );
    add_shortcode( 'threecol_one'                           , array( '_shcode' , 'de_threecol_one' ) );
    add_shortcode( 'threecol_one_first'                     , array( '_shcode' , 'de_threecol_one_first' ) );
    add_shortcode( 'threecol_one_last'                      , array( '_shcode' , 'de_threecol_one_last' ) );
    add_shortcode( 'threecol_two'                           , array( '_shcode' , 'de_threecol_two' ) );
    add_shortcode( 'threecol_two_first'                     , array( '_shcode' , 'de_threecol_two_first' ) );
    add_shortcode( 'threecol_two_last'                      , array( '_shcode' , 'de_threecol_two_last' ) );
    add_shortcode( 'fourcol_one'                            , array( '_shcode' , 'de_fourcol_one' ) );
    add_shortcode( 'fourcol_one_first'                      , array( '_shcode' , 'de_fourcol_one_first' ) );
    add_shortcode( 'fourcol_one_last'                       , array( '_shcode' , 'de_fourcol_one_last' ) );
    add_shortcode( 'fourcol_two'                            , array( '_shcode' , 'de_fourcol_two' ) );
    add_shortcode( 'fourcol_two_first'                      , array( '_shcode' , 'de_fourcol_two_first' ) );
    add_shortcode( 'fourcol_two_last'                       , array( '_shcode' , 'de_fourcol_two_last' ) );
    add_shortcode( 'fourcol_three'                          , array( '_shcode' , 'de_fourcol_three' ) );
    add_shortcode( 'fourcol_three_first'                    , array( '_shcode' , 'de_fourcol_three_first' ) );
    add_shortcode( 'fourcol_three_last'                     , array( '_shcode' , 'de_fourcol_three_last' ) );
    add_shortcode( 'fivecol_one'                            , array( '_shcode' , 'de_fivecol_one' ) );
    add_shortcode( 'fivecol_one_first'                      , array( '_shcode' , 'de_fivecol_one_first' ) );
    add_shortcode( 'fivecol_one_last'                       , array( '_shcode' , 'de_fivecol_one_last' ) );
    add_shortcode( 'fivecol_two'                            , array( '_shcode' , 'de_fivecol_two' ) );
    add_shortcode( 'fivecol_two_first'                      , array( '_shcode' , 'de_fivecol_two_first' ) );
    add_shortcode( 'fivecol_two_last'                       , array( '_shcode' , 'de_fivecol_two_last' ) );
    add_shortcode( 'fivecol_three'                          , array( '_shcode' , 'de_fivecol_three' ) );
    add_shortcode( 'fivecol_three_first'                    , array( '_shcode' , 'de_fivecol_three_first' ) );
    add_shortcode( 'fivecol_three_last'                     , array( '_shcode' , 'de_fivecol_three_last' ) );
    add_shortcode( 'fivecol_four'                           , array( '_shcode' , 'de_fivecol_four' ) );
    add_shortcode( 'fivecol_four_first'                     , array( '_shcode' , 'de_fivecol_four_first' ) );
    add_shortcode( 'fivecol_four_last'                      , array( '_shcode' , 'de_fivecol_four_last' ) );

    /* extra shortcode */
    add_shortcode( 'price_table'                            , array( '_shcode' , 'price_table' ) );
    add_shortcode( 'price_table_col'                        , array( '_shcode' , 'price_table_col' ) );
    
    add_shortcode( 'button'                                 , array( '_shcode' , 'add_button' ) );
    add_shortcode( 'hr'                                     , array( '_shcode' , 'add_hr' ) );
    add_shortcode( 'divider'                                , array( '_shcode' , 'add_divider' ) );
    add_shortcode( 'quote'                                  , array( '_shcode' , 'add_quote' ) );
    add_shortcode( 'box'                                    , array( '_shcode' , 'add_box' ) );
    add_shortcode( 'unordered_list'                         , array( '_shcode' , 'add_unordered_list' ) );
    add_shortcode( 'ordered_list'                           , array( '_shcode' , 'add_ordered_list' ) );
    add_shortcode( 'highlight'                              , array( '_shcode' , 'add_highlight' ) );
    add_shortcode( 'dropcap'                                , array( '_shcode' , 'add_dropcap' ) );
    add_shortcode( 'toggle'                                 , array( '_shcode' , 'add_toggle' ) );

    add_shortcode( 'demo'                                   , array( '_shcode' , 'de_demo' ) );
    add_shortcode( 'tabs'                                   , array( '_shcode' , 'add_tabs' ) );
    add_shortcode( 'accordion'                              , array( '_shcode' , 'add_accordion' ) );

    add_filter( 'the_content'                               , 'do_shortcode' );
    add_filter( 'widget_text'                               , 'do_shortcode' );
    
    if( _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'type' ) == 'widgets'){
        register_widget( "widget_custom_post" ); 
    }
	
	register_widget( "widget_flickr" );
	register_widget( "widget_tweets" );
	register_widget( "widget_contact" );
    register_widget( "widget_children_posts" );
	register_widget( "widget_social_media" );
	register_widget( "widget_sponsors" );
	register_widget( "widget_category_icons" );
	register_widget( "widget_comments" );
	register_widget( "widget_featured_posts" );
	register_widget( "widget_latest_posts" );
	register_widget( "widget_tabber" );
	register_widget( "widget_tags" );
	register_widget( "widget_top_authors" );
	
	/* register sidebars */
    if ( function_exists('register_sidebar') ) {
        register_sidebar(array(
			'name' => __( 'Main Sidebar', _DEV_ ),
			'id' => 'main',
			'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s"><div class="w_260">',
			'after_widget' => '</div></div></aside>',
			'before_title' => '<h4 class="widget-title dynamic-settings-style-sidebars-widget_title">',
			'after_title' => '</h4><p class="delimiter">&nbsp;</p>',
		));
        
        
        /* register mainpage sidebars */
        
        register_sidebar(array(
            'name' => __( 'Mainpage top side', _DEV_ ),
            'id' => 'whitey',
            'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s"><div class="qwe">',
            'after_widget' => '</div></div></aside>',
            'before_title' => '<h5 class="widget-title">',
            'after_title' => '</h5><p class="delimiter">&nbsp;</p>',
        ));
        
        if( _core::method( '_settings' , 'get' , 'settings' , 'front_page' , 'resource' , 'type' ) == 'widgets'){
            register_sidebar(array(
                'name' => __( 'Mainpage content', _DEV_ ),
                'id' => 'front',
                'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s">',
                'after_widget' => '</div></aside>',
                'before_title' => '<h1 class="dynamic-settings-style-front_page_widgets-widget_title entry-title fl"><a href="javascript:void(0);">',
                'after_title' => '</a></h1>',
            ));
        }
        
        register_sidebar(array(
            'name' => __( 'Mainpage bottom side', _DEV_ ),
            'id' => 'mainpage-footer',
            'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h5 class="widget-title">',
            'after_title' => '</h5><p class="delimiter">&nbsp;</p>',
        ));
        
		register_sidebar(array(
			'name' => __( 'Footer area', _DEV_ ),
			'id' => 'footer-first',
			'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s"><div class="w_290  margin-left">',
			'after_widget' => '</div></div></aside>',
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4><p class="delimiter">&nbsp;</p>',
		));

        $sidebars = get_option( _SBAR_ );
        
        if( is_array( $sidebars ) && !empty( $sidebars ) ){
            foreach( $sidebars as $index => $sidebar ){ 
                register_sidebar(array(
                    'name' => $sidebar ,
                    'id' => $index,
                    'before_widget' => '<aside id="%1$s" class="widget"><div class="%2$s"><div class="w_260">',
                    'after_widget' => '</div></div></aside>',
                    'before_title' => '<h4 class="widget-title">',
                    'after_title' => '</h4><p class="delimiter">&nbsp;</p>',
                ));
            }
        } 
    }
?>