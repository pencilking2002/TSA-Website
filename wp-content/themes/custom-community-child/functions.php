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
<?php /* Adding a homepage thumnail for the slideshow*/?>
<?php if ( function_exists( 'add_image_size'  ) ) {
	 add_image_size( 'homepage-thumb', 960, 360, true ); 
} ?>
<?php /* Register a new sidebar for use in the news page */ ?>
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
<?php function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}
?>