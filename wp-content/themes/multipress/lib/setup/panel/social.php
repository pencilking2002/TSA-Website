<?php
    /* SOCIAL */
    _panel::$fields[ 'settings' ][ 'social' ][ 'facebook' ][ 'app_id' ] = array(
        'type' => 'st--text',
        'label' => __( 'Facebook Application ID' , _DEV_ ),
        'hint' => __( 'You can create a FB Application from' , _DEV_ ) . ' <a href="https://developers.facebook.com/apps">' . __( 'here' , _DEV_ ) . '</a>',
    );

	_panel::$fields[ 'settings' ][ 'social' ][ 'facebook' ][ 'secret' ] = array(
        'type' => 'st--text',
        'label' => __( 'Facebook Secret key' , _DEV_ ),
        'hint' => __( 'It is needed for Facebook connect.' , _DEV_ )
    );