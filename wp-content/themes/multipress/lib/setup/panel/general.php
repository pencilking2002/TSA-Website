<?php
	_panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'settings-page' ] = array(
        'type' => 'st--search',
        'label' => __( 'Select My Settings page' , _DEV_ ),
        'query' => array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ),
        'hint' => __( 'Create a blank page then select it from the list to generate My Settings page' , _DEV_ )
    );

    _panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'post-page' ] = array(
        'type' => 'st--search',
        'label' => __( 'Select My Posts page' , _DEV_ ),
        'query' => array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ),
        'hint' => __( 'Create a blank page then select it from the list to generate My Posts page' , _DEV_ )
    );
        
    _panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'my-likes-page' ] = array(
        'type' => 'st--search',
        'label' => __( 'Select My Voted Posts page' , _DEV_ ),
        'query' => array(
            'post_type' => 'page',
            'post_status' => 'publish'
        ),
        'hint' => __( 'Create a blank page then select it from the list to generate My Voted Posts page' , _DEV_ )
    );
    
	/* LOG IN */

	if( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'enb-login' ) ){
        $classes = 'enb-login-options';
    }else{
        $classes = 'enb-login-options hidden';
    }

	_panel::$fields['settings']['general']['theme']['login-page']= array(
		'type'=>'st--search',
		'label'=>__('Select Log In page', _DEV_ ),
		'query'=>array(
			'post_type'=>'page',
			'post_status'=>'publish'
		),
		'hint'=> __( 'Create a blank page then select it from the list to generate the Login page' , _DEV_ ),
		'classes' => $classes
	);
	

    _panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'enb-login' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable user login' , _DEV_ ),
        'hint' => __( 'Choose Yes to display login link and user menu in header area' , _DEV_ ),
		'action' => "tools.sh.check( this , { 'yes' : '.enb-login-options' } )"
    );
    
    _panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'show-breadcrumbs' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Show breadcrumbs'  , _DEV_ ),
    );
    
    
    
    
    _panel::$fields[ 'settings' ][ 'general' ][ 'theme' ][ 'time' ] = array(
        'type' => 'st--logic-radio', 
        'label' => __( 'Use human time' , _DEV_ ), 
        'hint' => __( 'If set No will use WordPress time format' , _DEV_ ) 
    );
	
// 	_panel::$fields[ 'settings' ]['general']['theme']['fb-comments'] = array(
//         'type' => 'st--logic-radio', 
//         'label' => __( 'Use Facebook comments' , _DEV_ )
//     );

	_panel::$fields['settings']['general']['theme']['show-admin-bar']=array(
		'type'=> 'st--logic-radio',
		'label'=>__( 'Show WordPress admin bar' )
	);
	
	_panel::$fields[ 'settings' ]['general']['theme']['code'] = array(
        'type' => 'st--textarea', 
        'label' => __( 'Tracking code' , _DEV_ ), 
        'hint' => __( 'Paste your Google Analytics or other tracking code here.<br />It will be added into the footer of this theme' , _DEV_ ) 
    );
    
	_panel::$fields[ 'settings' ]['general']['theme']['copyright'] = array(
        'type' => 'st--textarea',
        'label' => __( 'Copyright text' , _DEV_ ),
        'hint' => __( 'Type here the Copyright text that will appear in the footer.<br />To display the current year use "%year%"' , _DEV_ )
    );
?>