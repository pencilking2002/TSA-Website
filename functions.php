<?php

//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args');

// Unleash the power of Thematic's dynamic classes
// 
// define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
// define('THEMATIC_COMPATIBLE_POST_CLASS', true);

// Unleash the power of Thematic's comment form
//
// define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);

// Unleash the power of Thematic's feed link functions
//
// define('THEMATIC_COMPATIBLE_FEEDLINKS', true);

//this will insert links to 1st level pages on your site 
function wicked_footer_pagelinks() {
	echo '<ul id="simplepages">';
	wp_list_pages('depth=1&sort_column=menu_order&title_li=');
	echo '</ul>';
}
//add a favicon, not sure what the extra quote is for. 

//function wicked_favicon() {
//	echo '<link rel="shortcut icon" href="' . get_bloginfo('stylesheet_directory') . '/images/favicon.ico" />';
//}
////add the function to to a hook. list the hook and then a function, don't forget the quotes
//add_action('wp_head', 'wicked_favicon');

/*
*******************************************
 Function to allow multi-line photo captions.
 This function will split captions onto multiple lines if it detects
 a "|" (pipe) symbol.
********************************************
*/
/* Override existing caption shortcode handlers with our own */
add_shortcode('wp_caption', 'multiline_caption');
add_shortcode('caption', 'multiline_caption');

/* Our new function */
function multiline_caption($attr, $content = null) {
       extract(shortcode_atts(array(
               'id'        => '',
               'align'        => 'alignnone',
               'width'        => '',
               'caption' => ''
       ), $attr));

       if ( 1 > (int) $width || empty($caption) )
               return $content;

       if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

        $new_caption = str_replace("|", "<br />", $caption);

       return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
       . do_shortcode( $content ) . '<p class="wp-caption-text">' . $new_caption . '</p></div>';
?> 
