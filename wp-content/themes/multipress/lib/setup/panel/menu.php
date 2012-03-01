<?php
    /* MENUS */
	_panel::$fields[ 'settings' ][ 'menus' ][ 'menus' ][ 'menu-limit' ] = array(
        'type' => 'st--select',
		'values' => _tools::digit( 20 ),
        'label' => __( 'Set limit for main menu' , _DEV_ ),
        'hint' => __( 'Set the number of visible menu items. Remaining menu<br />items will be shown in the drop down menu item "More"' , _DEV_ )
    );  

	_panel::$fields[ 'settings' ][ 'menus' ][ 'menus' ][ 'footer-menu-limit' ] = array(
        'type' => 'st--select',
		'values' => _tools::digit( 20 ),
        'label' => __( 'Set limit for footer menu' , _DEV_ ),
        'hint' => __( 'Set the number of visible menu items. Remaining menu<br />items will be hidden' , _DEV_ )
    );  
?>