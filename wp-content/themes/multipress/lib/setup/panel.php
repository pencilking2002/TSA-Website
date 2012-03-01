<?php
    include 'help.php';
    $theme_name = get_current_theme();
        
    /* RESOURCES INCLUDE 
     *  - custom posts settings
     *  - custom posts taxonomy
     *  - custom posts meta boxes
     *  - custom sidebars 
     */
    
    _panel::$menu[ 'resources' ]['custom'] = array(
        'post' => array(
            'label' => __( 'Custom posts' , _DEV_ ),
            'title' => $theme_name . ' ' . __( 'custom post builder' , _DEV_ ),
            'description' => __( 'Create different types of custom posts: payments, locations, papers, people, general, etc.' , _DEV_ ),
            'type' => 'main' , 
            'menu_label' => __( 'Custom posts' , _DEV_ ), 
            'main_label' => __( 'Resources' , _DEV_ ),
            'main_title' => $theme_name . ' ' . __( 'custom resources builder' , _DEV_ ),
            'update' => false,
            'classes' => 'resource-builder',
			'icon' => get_template_directory_uri().'/lib/core/images/icon-1.png'
        ),
        'sidebar' => array(
            'label' => __( 'Sidebars' , _DEV_ ),
            'title' => $theme_name . ' ' . __( 'custom sidebars builder' , _DEV_ ),
            'description' => __( 'General page description for custom sidebars.' , _DEV_ ),
            'menu_label' => __( 'Sidebar' , _DEV_ ), 
            'main_label' => __( 'Sidebar' , _DEV_ ),
            'update' => false
        ),
    );
    
    /* SETTINGS INCLUDE 
     *  - all theme settings 
     */
    _panel::$menu[ 'settings' ]['general'] = array(
		'theme' => array(
            'label' => __( 'Theme general settings' , _DEV_ ) ,
            'title' => $theme_name . ' ' . __( 'General theme settings' , _DEV_ ),
            'description' => __( 'Theme general settings: styles, layout, slideshows, etc.' , _DEV_ ),
            'type' => 'main',
            'menu_label' => __( 'Theme settings' , _DEV_ ),
            'main_label' => __( 'Theme settings' , _DEV_ ),
            'classes' => 'theme-settings',
			'icon' => get_template_directory_uri().'/lib/core/images/icon-2.png'
        ),
        'upload' => array(
            'label' => __( 'Front-end submitting' , _DEV_ ),
            'title' => __( 'Front-end submitting settings' , _DEV_ ),
            'description' => __( 'Front-end submitting' , _DEV_ ),
            'menu_label' => __( 'Front-end submitting' , _DEV_ ),
        )
    );
    
    _panel::$menu[ 'settings' ]['front_page'] = array(
        'resource' => array(
            'label' => __( 'Mainpage' , _DEV_ ) ,
            'title' => __( 'Mainpage resource type' , _DEV_ ) ,
            'description' => __( 'General page description.' , _DEV_ ) ,
            'main_label' => __( 'Mainpage' , _DEV_ )
        )
    );
    _panel::$menu[ 'settings' ]['layout'] = array(
        'style' => array(
            'label' => __( 'Layout' , _DEV_ ),
            'title' => __( 'Layout settings' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ ),
            'main_label' => __( 'Layout' , _DEV_ )
        )
    );

	_panel::$menu[ 'settings' ]['menus'] = array(
        'menus' => array(
            'label' => __( 'Menus' , _DEV_ ),
            'title' => __( 'Menu settings' , _DEV_ ),
            'description' => __( 'Menu settings.' , _DEV_ ),
            'main_label' => __( 'Menus' , _DEV_ )
        )
    );
    
    _panel::$menu[ 'settings' ][ 'blogging' ] = array(
        'posts' => array(
            'label' => __( 'Posts' , _DEV_ ),
            'title' => __( 'General posts settings' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ ),
            'main_label' => __( 'Blogging' , _DEV_ ) 
        ),
        'pages' => array(
            'label' => __( 'Pages' , _DEV_ ),
            'title' => __( 'General pages settings' , _DEV_ ),
            'description' => __( 'General pages description.' , _DEV_ )
        ),
        'likes' => array(
            'label' => __( 'Likes' , _DEV_ ),
            'title' => __( 'General likes settings' , _DEV_ ),
            'description' => __( 'General likes description.' , _DEV_ )
        ),
		
		'archive' => array(
			'label' => __( 'Archive' , _DEV_ ),
			'title' => __( 'General archive settings' , _DEV_ ),
			'description' => __( 'General archive description' , _DEV_ )
		)
    );
    _panel::$menu[ 'settings' ][ 'social' ] = array(
        'facebook' => array(
            'label' => __( 'Social' , _DEV_ ),
            'title' => __( 'Social settings' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ ),
            'main_label' => __( 'Social' , _DEV_ ) 
        )
    );
    _panel::$menu[ 'settings' ]['style'] = array(
        'general' => array(
            'label' => __( 'Style' , _DEV_ ),
            'title' => __( 'General theme style' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ ),
            'main_label' => __( 'Style' , _DEV_ ) 
        ),
        'single' => array(
            'label' => __( 'Post' , _DEV_ ) ,
            'title' => __( 'Post text style' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ )
        ),
        'page' => array(
            'label' => __( 'Page' , _DEV_ ),
            'title' => __( 'Pages text style' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ )
        ),
        'archive' => array(
            'label' => __( 'Archive' , _DEV_ ),
            'title' => __( 'Archive text style' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ )
        ),
        'menu' => array(
            'label' => __( 'Menu' , _DEV_ ),
            'title' => __( 'Menu labels text style' , _DEV_ ),
            'description' => __( 'General page description.' , _DEV_ )
        ),
        'sidebars' => array(
            'label' => __( 'Sidebar widgets' , _DEV_ ),
            'title' => __( 'Sidebars widgets text style' , _DEV_ ),
            'description' => __( 'From here you can adjust the styles for text on sidebar widgets' , _DEV_ )
        ),
		'front_page_widgets' => array(
            'label' => __( 'Mainpage widgets' , _DEV_ ),
            'title' => __( 'Mainpage widgets text style' , _DEV_ ),
            'description' => __( 'From here you can adjust the styles for text on mainpage widgets' , _DEV_ )
        ),

		'slideshow' => array(
			'label' => __( 'Slideshow style' , _DEV_ ),
			'title' => __( 'Slideshow style' , _DEV_ ),
			'description' => __( 'From here you can adjust the styles for the slideshow' , _DEV_ )
		)
    );
	
	_panel::$menu[ 'settings' ]['payment'] = array(
        'paypal' => array(
            'label' => __( 'PayPal Settings' , _DEV_ ),
            'title' => __( 'PayPal Settings' , _DEV_ ),
            'description' => __( 'PayPal Settings.' , _DEV_ ),
            'main_label' => __( 'Payments' , _DEV_ )
        ),
		'check_transactions' => array(
            'label' => __( 'Transactions Statstics Settings' , _DEV_ ),
            'title' => __( 'Transactions Statstics Settings' , _DEV_ ),
            'description' => __( 'Settings for for transactions Statstics.' , _DEV_ )
        )
    );
    
    _panel::$menu[ 'settings' ]['slideshow'] = array(
        'general' => array(
            'label' => __( 'Slideshow' , _DEV_ ),
            'title' => __( 'Slideshow settings' , _DEV_ ),
            'description' => __( 'Slideshow general settings.' , _DEV_ ),
            'main_label' => __( 'Slideshow' , _DEV_ )
        )
    );

    /* EXTRA INCLUDE
     *  - notification settings
     *  - export resource structure
     *  - export sidebars
     *  - export tooltips
     */
    _panel::$menu[ 'extra' ]['settings'] = array(
        'css' => array(
            'label' => __( 'Custom CSS' , _DEV_ ),
            'title' => $theme_name . ' ' . __( 'extra options' , _DEV_ ),
            'description' => __( 'Extra options: Import / Export data, custom CSS, notifications, etc' , _DEV_ ),
            'type' => 'main',
            'menu_label' => __( 'Extra options' , _DEV_ ),
            'main_label' => __( 'Custom CSS' , _DEV_ ),
            'main_title' => $theme_name . ' ' . __( 'extra options' , _DEV_ ),
            'classes' => 'extra-settings',
			'icon' => get_template_directory_uri().'/lib/core/images/icon-3.png'
        ),
        'io' => array(
            'label' => __( 'Import / Export' , _DEV_ ),
            'title' => __( 'Import / Export Structure' , _DEV_ ),
            'description' => __( 'Import / Export' , _DEV_ ),
            'main_label' => __( 'Import / Export' , _DEV_ ),
            'update' => false
        ),
        'notifications' => array(
            'label' => __( 'Notifications' , _DEV_ ),
            'title' => __( 'CosmoThemes notifications' , _DEV_ ),
            'description' => __( 'Notifications' , _DEV_ ),
            'main_label' => __( 'Notifications' , _DEV_ )
        ),
    );
    
    /* PANEL SETTINGS */
    /* REGISTER GROUP AND PROPRIETES FOR PAGES */
    
    /* SETTINGS GENERAL THEME */
    include 'panel/general.php';
    
    /* SETTINGS FRONT-PAGE */
    include 'panel/front-page.php';
    
    /* SETTINGS GENERAL THEME */
    include 'panel/upload.php';
    
    /* SETTINGS STYLE */
    include 'panel/style.php';
    
    /* SETTINGS LAYOUT STYLE */
    include 'panel/layout.php';
    
	/* SETTINGS MENUS */
	include 'panel/menu.php';

    /* SETTINGS BLOGGING */
    include 'panel/blogging.php';
    
    /* SETTINGS SOCIAL */
    include 'panel/social.php';
    
    /* SETTINGS PAYMENT */
	include 'panel/payment.php';
	/* SETTINGS FOR PAYMENT STATISTCS*/
    include 'panel/payment_statstics.php';
	
    /* SETTINGS SLIDESHOW */
    include 'panel/slideshow.php';
    
    /* PANEL EXTRA */
    /* CUSTOM CSS EXPORT CUSTOM POSTS STRUCTURE */
    include 'panel/extra.php';
    
    /* TEST EXAMPLE */
    _panel::$fields['settings']['general']['style']['help_test1'] = array( 'type' => 'st--text' , 'label' => __( 'Test help icon' , _DEV_ ) , 'help' => $help[ '123' ] , 'hint' => __( 'Input with short hint' , _DEV_ ) );
    _panel::$fields['settings']['general']['style']['help_test2'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Test help icon' , _DEV_ ) , 'help' => $help[ '124' ] , 'hint' => __( 'Tesing input with.' , _DEV_ ));
    _panel::$fields['settings']['general']['style']['help_test3'] = array( 'type' => 'st--upload' , 'label' => __( 'Test upload' , _DEV_ ) , 'help' => $help[ '124' ] , 'hint' => __( 'Tesing input with upload.' , _DEV_ ));
    _panel::$fields['settings']['general']['style']['help_test4'] = array( 'type' => 'st--upload-id' , 'label' => __( 'Test upload ID' , _DEV_ ) , 'help' => $help[ '124' ] , 'hint' => __( 'Tesing input with upload ID.' , _DEV_ ));
    _panel::$fields['settings']['general']['style']['help_test5'] = array( 'type' => 'st--search' , 'label' => __( 'Test autocomplete' , _DEV_ ) , 'help' => $help[ '124' ] , 'hint' => __( 'Tesing input with upload ID.' , _DEV_ ) , 'query' => array( 'post_type' => 'any' , 'post_status' => 'publish' ) );
    _panel::$fields['settings']['general']['style']['help_test6'] = array( 'type' => 'st--color-picker' , 'label' => __( 'Test color piker' , _DEV_ ) , 'help' => $help[ '124' ] , 'hint' => __( 'Tesing input with color piker.' , _DEV_ ));
    $pattern_path = 'pattern/s.pattern.';
    $pattern = array(
        "flowers"=>"flowers.png" , 
        "flowers_2"=>"flowers_2.png" , 
        "flowers_3"=>"flowers_3.png" , 
        "flowers_4"=>"flowers_4.png" ,
        "circles"=>"circles.png",
        "dots"=>"dots.png",
        "grid"=>"grid.png",
        "noise"=>"noise.png",
        "paper"=>"paper.png",
        "rectangle"=>"rectangle.png",
        "squares_1"=>"squares_1.png",
        "squares_2"=>"squares_2.png",
        "thicklines"=>"thicklines.png",
        "thinlines"=>"thinlines.png" , 
        "none"=>"none.png"
    );
    
    _panel::$fields['settings']['general']['style']['help_test7'] = array( 'type' => 'ni--radio-icon' ,  'value' => $pattern , 'path' => $pattern_path , 'coll' => 6 );
    
    
    /* REGISTER FIELDS */
    _settings::$register = _panel::$fields;
    
    /* NO NEED REGISTER FIELDS */
    /* PANEL CUSTOM RESOURCE */
    _panel::$fields['resources']['custom']['post']['panel'] = array( 'type' => 'ni--resources-custom-posts' );
    _panel::$fields['resources']['custom']['sidebar']['panel'] = array( 'type' => 'ni--resource-custom-sidebars' );
?>