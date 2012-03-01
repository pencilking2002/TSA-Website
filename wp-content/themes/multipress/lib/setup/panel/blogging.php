<?php
    /* POSTS */
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'enb-featured' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Display featured image inside post' , _DEV_ ),
        'hint' => __( 'If enabled featured images will be displayed both on category and post page' , _DEV_ ),
        'action' => "tools.sh.check( this , { 'yes' : '.enb-lightbox-options' } )"
    );

	_panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'enb-featured-border' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Display border around the featured image' , _DEV_ ),
    );
    
    if( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'posts' , 'enb-featured' ) ){
        $classes = 'enb-lightbox-options';
    }else{
        $classes = 'enb-lightbox-options hidden';
    }
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'enb-lightbox' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable pretty-photo ligthbox' , _DEV_ ),
        'hint' => __( 'Images inside posts will open inside a fancy lightbox' , _DEV_ ),
        'classes' => $classes
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable similar posts' , _DEV_ ),
        'action' => "tools.sh.check( this , { 'yes' : '.similar-fields' })"
    );
    
    if( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'posts' , 'similar' ) ){
        $similar_cl = 'similar-fields';
    }else{
        $similar_cl = 'similar-fields hidden';
    }
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-full' ] = array(
        'type' => 'st--select',
        'values' => _tools::digit( 20 ,  1 ),
        'label' => __( 'Number of similar posts (full width)' , _DEV_ ),
        'classes' => $similar_cl
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-side' ] = array(
        'type' => 'st--select',
        'values' => _tools::digit( 20 ,  1 ),
        'label' => __( 'Number of similar posts (with sidebar)' , _DEV_ ),
        'classes' => $similar_cl
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'similar-criteria' ] = array(
        'type' => 'st--select',
        'values' => array(
            'post_tag' => __( 'Same tags' , _DEV_ ),
            'category' => __( 'Same Category' , _DEV_ ) 
        ),
        'label' => __( 'Similar posts criteria' , _DEV_ ),
        'classes' => $similar_cl
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'social' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable social sharing' , _DEV_ ),
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'author-box' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable author box' , _DEV_ ),
        'hint' => __( 'This will enable the author box on posts. Edit user description in <strong>Users</strong> > <strong>Your Profile</strong>' , _DEV_ )
    );
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'source' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable posts source' , _DEV_ ),
    );

//     _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'archive' ] = array(
//         'type' => 'st--logic-radio',
//         'label' => __( 'Enable posts archiving' , _DEV_ ),
//     );

    _panel::$fields[ 'settings' ][ 'blogging' ][ 'posts' ][ 'meta' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable meta for posts' , _DEV_ ),
    );
    
    /* PAGES */
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'enb-featured' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Display featured image inside page' , _DEV_ ),
        'hint' => __( 'If enabled featured images will be inside page' , _DEV_ ),
        'action' => "tools.sh.check( this , { 'yes' : '.enb-lightbox-options' } )"
    );
    
    if( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'pages' , 'enb-featured' ) && _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'pages' , 'enb-next-prev' )  ){
        $classes = 'enb-lightbox-options';
    }else{
        $classes = 'enb-lightbox-options hidden';
    }
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'enb-lightbox' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable pretty-photo ligthbox' , _DEV_ ),
        'hint' => __( 'Images inside posts will open inside a fancy lightbox' , _DEV_ ),
        'classes' => $classes
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'enb-next-prev' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Display next and previous butttons' , _DEV_ ),
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'social' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable social sharing' , _DEV_ ),
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'author-box' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable author box for pages' , _DEV_ ),
        'hint' => __( 'This will enable the author box on pages. Edit user description in <strong>Users</strong> > <strong>Your Profile</strong>' , _DEV_ )
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'source' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable page source' , _DEV_ ),
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'pages' ][ 'meta' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable meta' , _DEV_ ),
    );
    
    /* LIKES */
    if( _core::method( '_settings' , 'logic' , 'settings' , 'blogging' , 'likes' , 'use' ) ){
        $classes = 'likes-options';
    }else{
        $classes = 'likes-options hidden';
    }
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'likes' ][ 'use' ] = array(
        'type' => 'st--logic-radio', 
        'label' => __( 'Enable Likes for standard posts' , _DEV_ ),
        'action' => "tools.sh.check( this , { 'yes' : '.likes-options' } )"
    );

    _panel::$fields[ 'settings' ][ 'blogging' ][ 'likes' ][ 'limit' ] = array(
        'type' => 'st--digit-like',
        'label' => __( 'Minimum number of Likes to set Featured' , _DEV_ ),
        'hint' => __( 'Set minimum number of Likes to change post into Featured' , _DEV_ ),
        'action' => "likes.reset( " . _resources::getCustomIdByPostType( 'post' ) . " , tools.v( '#reset-likes') , 1 );",
        'id' => 'reset-likes',
        'rClass' => 'reset',
        'classes' => $classes
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'likes' ][ 'generate' ] = array(
        'type' => 'st--button',
        'value' => __( 'Generate' , _DEV_ ),
        'label' => __( 'Generate random number of Likes for posts' , _DEV_ ),
        'action' => "likes.generate( " . _resources::getCustomIdByPostType( 'post' ) . " , 1 );",
        'hint' => __( 'WARNING! This will reset all current Likes' , _DEV_ ),
        'additional' => true,
        'rClass' => 'generate',
        'classes' => $classes
    );
    
    _panel::$fields[ 'settings' ][ 'blogging' ][ 'likes' ][ 'req-registr' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Registration is required to Like a post' , _DEV_ ),
        'classes' => $classes
    );
    
	/* ARCHIVE */
     _panel::$fields[ 'settings' ][ 'blogging' ][ 'archive' ][ 'grid-excerpt' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Excerpt length (grid view)' , _DEV_ ),
		'hint' => __( 'Set number of words that will be displayed on archive pages for each post', _DEV_ )
    );

	_panel::$fields[ 'settings' ][ 'blogging' ][ 'archive' ][ 'list-excerpt' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Excerpt length (list view)' , _DEV_ ),
		'hint' => __( 'Set number of words that will be displayed on archive pages for each post' , _DEV_ )
    );
?>