<?php
	if ( function_exists('register_nav_menu') ) {
		register_nav_menus(
			array(
				'header' => 'Main Menu',
				 'header' => 'logged_in_tsa_menu',
                'footer' => 'Footer Menu'
            )
		);
	}
?>