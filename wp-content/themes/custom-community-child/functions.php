<?php
/*disable the buddypress admin bar */
//define( 'BP_DISABLE_ADMIN_BAR', true );
?>
<?php if ( function_exists( 'register_nav_menu' ) ) {
	register_nav_menus( array( 'main_nav' => 'tsa_menu' ) );
} ?>
<?php if ( function_exists( 'register_nav_menu' ) ) {
	register_nav_menus( array( 'main_nav' => 'logged_in_tsa_menu' ) );
} ?>
<?php if ( function_exists( 'add_image_size'  ) ) {
	 add_image_size( 'homepage-thumb', 960, 900, true ); 
} ?>
<?php 
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
	    	'name' => 'Roman',
	    	'id'   => 'roman',
	    	'description'   => 'These are widgets for the sidebar.',
	    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    	'after_widget'  => '</div>',
	    	'before_title'  => '<h2>',
	    	'after_title'   => '</h2>'
	    	));
	    }
?>