<?php

    $theme[ 'settings' ] = _settings::getAll();
    $theme[ 'res' ] = get_option( _RES_ );
    
    _panel::$fields[ 'extra' ][ 'settings' ][ 'io' ][ 'export-area' ] = array(
        'type' => 'stbox--textarea',
        'label' => __( 'Export custom posts structure' , _DEV_ ),
        'hint' => __( '' , _DEV_ ),
        'value' => base64_encode( serialize( $theme ) )
    );
    _panel::$fields[ 'extra' ][ 'settings' ][ 'io' ][ 'import-area' ] = array(
        'type' => 'stbox--textarea',
        'label' => __( 'Import custom posts structure' , _DEV_ ),
        'hint' => __( '' , _DEV_ ),
    );
    _panel::$fields[ 'extra' ][ 'settings' ][ 'io' ][ 'import-button' ] = array(
        'type' => 'stbox--button',
        'value' => __( 'Import Structure' , _DEV_ ),
        'action' => "res.r( 'import' , [ tools.v( 'textarea.extra__settings__io.import-area' ) ] )"
    );
    
    
    _panel::$fields[ 'extra' ][ 'settings' ][ 'css' ][ 'header-css' ] = array(
        'type' => 'stbox--textarea',
        'label' => __( 'Edit header custom css' , _DEV_ )
    );
    _panel::$fields[ 'extra' ][ 'settings' ][ 'css' ][ 'general-css' ] = array(
        'type' => 'stbox--textarea',
        'label' => __( 'Edit general custom css' , _DEV_ )
    );
    
    
    _panel::$fields[ 'extra' ][ 'settings' ][ 'notifications' ][ 'version' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable notification about new theme version' , _DEV_ )
    );
	_panel::$fields[ 'extra' ][ 'settings' ][ 'notifications' ][ 'news' ]  = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Enable CosmoThemes news notification' , _DEV_ )
    );
?>