<?php
/**
 * Price Filter Widget
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package		Jigoshop
 * @category	Widgets
 * @author		Jigowatt
 * @since		1.0
 * @copyright	Copyright (c) 2011-2012 Jigowatt Ltd.
 * @license		http://jigoshop.com/license/commercial-edition
 */
class Jigoshop_Widget_Price_Filter extends WP_Widget {

	/**
	 * Constructor
	 *
	 * Setup the widget with the available options
	 * Add actions to clear the cache whenever a post is saved|deleted or a theme is switched
	 */
	public function __construct() {
		$options = array(
			'classname'		=> 'jigoshop_price_filter',
			'description'	=> __( 'Outputs a price filter slider', 'jigoshop' )
		);

		// Create the widget
		parent::__construct( 'jigoshop_price_filter', __( 'Jigoshop: Price Filter', 'jigoshop' ), $options );

		// Add price filter init to init hook
		add_action('init', array( &$this, 'jigoshop_price_filter_init') );
	}

	/**
	 * Widget
	 *
	 * Display the widget in the sidebar
	 * Save output to the cache if empty
	 *
	 * @param	array	sidebar arguments
	 * @param	array	instance
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		if ( ! is_tax( 'product_cat' ) && ! is_post_type_archive( 'product' ) && ! is_tax( 'product_tag' ) )
			return false;

		global $_chosen_attributes, $wpdb, $jigoshop_all_post_ids_in_view;

		// Set the widget title
		$title = apply_filters(
			'widget_title',
			( $instance['title'] ) ? $instance['title'] : __( 'Filter by Price', 'jigoshop' ),
			$instance,
			$this->id_base
		);

		// Print the widget wrapper & title
		echo $before_widget;
		echo $before_title . $title . $after_title;

		// Remember current filters/search
		$fields = '';

		if ( get_search_query() ) {
			$fields .= '<input type="hidden" name="s" value="' . get_search_query() . '" />';
		}

		if ( isset( $_GET['post_type'] ) ) {
			$fields .= '<input type="hidden" name="post_type" value="' . esc_attr( $_GET['post_type'] ) . '" />';
		}

		if ( $_chosen_attributes ) foreach ( $_chosen_atributes as $attr => $val ) {
			$fields .= '<input type="hidden" name="'.str_replace('pa_', 'filter_', $attribute).'" value="'.implode(',', $value).'" />';
		}

		// Get maximum price
		$max = ceil($wpdb->get_var("SELECT max(meta_value + 0)
		FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
		WHERE meta_key = 'price' AND (
			$wpdb->posts.ID IN (".implode( ',', $jigoshop_all_post_ids_in_view ).")
			OR (
				$wpdb->posts.post_parent IN (".implode( ',', $jigoshop_all_post_ids_in_view ).")
				AND $wpdb->posts.post_parent != 0
			)
		)"));

		echo '<form method="get" action="' . esc_attr( $_SERVER['REQUEST_URI'] ) . '">
			<div class="price_slider_wrapper">
				<div class="price_slider"></div>
				<div class="price_slider_amount">
					<button type="submit" class="button">Filter</button>' . __( 'Price: ', 'jigoshop' ) . '<span></span>
					<input type="hidden" id="max_price" name="max_price" value="' . esc_attr( $max ) . '" />
					<input type="hidden" id="min_price" name="min_price" value="0" />
					' . $fields . '
				</div>
			</div>
		</form>';

		// Print closing widget wrapper
		echo $after_widget;
	}

	/**
	 * Update
	 *
	 * Handles the processing of information entered in the wordpress admin
	 * Flushes the cache & removes entry from options array
	 *
	 * @param	array	new instance
	 * @param	array	old instance
	 * @return	array	instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Save the new values
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function jigoshop_price_filter_init() {

		unset(jigoshop_session::instance()->min_price);
		unset(jigoshop_session::instance()->max_price);

		if ( isset( $_GET['min_price'] ) ) {
			jigoshop_session::instance()->min_price = $_GET['min_price'];
		}

		if ( isset( $_GET['max_price'] ) ) {
			jigoshop_session::instance()->max_price = $_GET['max_price'];
		}
	}

	/**
	 * Form
	 *
	 * Displays the form for the wordpress admin
	 *
	 * @param	array	instance
	 */
	public function form( $instance ) {

		// Get instance data
		$title 	= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : null;

		// Widget Title
		echo "
		<p>
			<label for='{$this->get_field_id( 'title' )}'>" . __( 'Title:', 'jigoshop' ) . "</label>
			<input class='widefat' id='{$this->get_field_id( 'title' )}' name='{$this->get_field_name( 'title' )}' type='text' value='{$title}' />
		</p>";
	}
} // class Jigoshop_Widget_Price_Filter

function jigoshop_price_filter( $filtered_posts ) {

	if (isset($_GET['max_price']) && isset($_GET['min_price'])) :

		$matched_products = array( 0 );

		$matched_products_query = get_posts(array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'price',
					'value' => array( $_GET['min_price'], $_GET['max_price'] ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field' => 'slug',
					'terms' => 'grouped',
					'operator' => 'NOT IN'
				)
			)
		));

		if ($matched_products_query) :

			foreach ($matched_products_query as $product) :
				$matched_products[] = $product->ID;
			endforeach;

		endif;

		// Get grouped product ids
		$grouped_products = get_objects_in_term( get_term_by('slug', 'grouped', 'product_type')->term_id, 'product_type' );

		if ($grouped_products) foreach ($grouped_products as $grouped_product) :

			$children = get_children( 'post_parent='.$grouped_product.'&post_type=product' );

			if ($children) foreach ($children as $product) :
				$price = get_post_meta( $product->ID, 'price', true);

				if ($price<=$_GET['max_price'] && $price>=$_GET['min_price']) :

					$matched_products[] = $grouped_product;

					break;

				endif;
			endforeach;

		endforeach;

		$filtered_posts = array_intersect($matched_products, $filtered_posts);

	endif;

	return $filtered_posts;
}
add_filter( 'loop-shop-posts-in', 'jigoshop_price_filter' );
