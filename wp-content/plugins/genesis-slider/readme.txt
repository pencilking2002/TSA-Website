=== Genesis Slider ===
Contributors: nathanrice, studiopress, wpmuguru
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5553118
Tags: slider, slideshow, genesis, genesiswp, studiopress
Requires at least: 3.2
Tested up to: 3.3
Stable tag: 0.9.6

This plugin allows you to create a simple slider that displays the featured image, along with the title and excerpt from each post.

== Description ==

This plugin allows you to create a simple slider that displays the featured image, along with the title and excerpt from each post.

It includes options for auto-progress and the dimensions of your slideshow. It also allows you to choose to display posts or pages, what category to pull from, and even the specific post IDs of the posts you want to display. Finally, you can place the slider into a widget area.

Note: This plugin only supports Genesis child themes.

== Installation ==

1. Upload the entire `genesis-slider` folder to the `/wp-content/plugins/` directory
1. DO NOT change the name of the `genesis-slider` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the `Genesis > Slider Settings` menu
1. Configure the slider
1. In the "Widgets" screen, drag the "Genesis Slider" widget to the widget area of your choice

== Child Theme Integration ==

To adjust the slider defaults for a child theme use a filter simiar to the following:

`add_filter( 'genesis_slider_settings_defaults', 'my_child_theme_slider_defaults' );

function my_child_theme_slider_defaults( $defaults ) {
	$defaults = array(
		'post_type' => 'post',
		'posts_term' => '',
		'exclude_terms' => '',
		'include_exclude' => '',
		'post_id' => '',
		'posts_num' => 5,
		'posts_offset' => 0,
		'orderby' => 'date',
		'slideshow_timer' => 4000,
		'slideshow_delay' => 800,
		'slideshow_arrows' => 1,
		'slideshow_height' => 400,
		'slideshow_width' => 870,
		'slideshow_excerpt_content' => 'excerpts',
		'slideshow_excerpt_content_limit' => 150,
		'slideshow_more_text' => __( '[Continue Reading]', 'genesis-slider' ),
		'slideshow_excerpt_show' => 1,
		'slideshow_excerpt_width' => 500,
		'location_vertical' => 'bottom',
		'location_horizontal' => 'right'
	);
	return $defaults;
}
`

== Changelog ==

= 0.9.6 =
* jQuery fixes for transitions & suspending animations when tab in background
* add Menu Order to sorting
* add carousel option

= 0.9.5 =
* add transitions
* minor tranlation fixes, add slide id class

= 0.9.4 =
* bug fix - slider scrolling on no arrows
* add title options

= 0.9.3 =
* translation support
* customizable excerpt
* add defaults filter

= 0.9.2 =
* bug fixes for slider timing, mobile theme collision
* change slider defaults

= 0.9.1 =
* bug fixes, add options

= 0.9 =
* Beta Release
