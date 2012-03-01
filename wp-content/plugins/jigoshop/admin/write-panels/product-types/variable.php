<?php
/**
 * Variable Product Type
 *
 * Functions specific to variable products (for the write panels)
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package		Jigoshop
 * @category	Admin
 * @author		Jigowatt
 * @copyright	Copyright (c) 2011-2012 Jigowatt Ltd.
 * @license		http://jigoshop.com/license/commercial-edition
 */

// Temporary fix for selectbox triggering the click event.
// For some reason enqueing the script inside a class causes the event unbind()
// to not work. Would prefer this to be part of the class but perhaps its better to enqueue
// everything all at once.
add_action( 'admin_enqueue_scripts', 'jigoshop_product_meta_variable_script' );
function jigoshop_product_meta_variable_script( $hook ) {
	global $post;

	// Don't enqueue script if not on product edit screen
	if ( $hook != 'post.php' || $post->post_type != 'product' )
		return false;

	wp_enqueue_script('jigoshop-variable-js', jigoshop::assets_url() . '/assets/js/variable.js' , array('jquery'),1,true);
}

class jigoshop_product_meta_variable extends jigoshop_product_meta
{
	public function __construct() {
		add_action( 'jigoshop_product_write_panel_tabs',       array(&$this, 'register_tab') );
		add_action( 'jigoshop_process_product_meta_variable',  array(&$this, 'save'), 1 );
		add_action( 'jigoshop_product_write_panels',	           array(&$this, 'display') );
		add_action( 'admin_enqueue_scripts',                   array(&$this, 'admin_enqueue_scripts') );

		add_action( 'wp_ajax_jigoshop_remove_variation',       array(&$this, 'remove') );
	}

	/**
	 * Registers tab for use in the product meta box
	 *
	 * @return  void
	 */
	public function register_tab() {
		echo '<li class="variable_tab">
				<a href="#variable_product_options">Variations</a>
			</li>';
	}

	/**
	 * Registers scripts for use in the admin
	 * Also localizes variables for use in the javascript, essential for variation addition
	 *
	 * @return  void
	 */
	public function admin_enqueue_scripts( $hook ) {
		global $post;

		// Don't enqueue script if not on product edit screen
		if ( $hook != 'post.php' || $post->post_type != 'product' )
			return false;

		// wp_enqueue_script('jigoshop-variable-js', jigoshop::assets_url() . '/assets/js/variable.js', array('postbox', 'jquery'), true);

		// Shouldn't we namespace? -Rob
		wp_localize_script( 'jigoshop-variable-js', 'varmeta', array(
			'assets_url'  => jigoshop::assets_url(),
			'ajax_url'    => admin_url('admin-ajax.php'),
			'i18n'        => array(
				'variations_required' => __('You need to add some variations first', 'jigoshop'),
				'remove_all'          => __('Are you sure you want to delete all variations', 'jigoshop'),
				'set_regular_price'   => __('Enter a regular price', 'jigoshop'),
				'set_sale_price'      => __('Enter a sale price', 'jigoshop'),
				'set_stock'           => __('Enter a stock value', 'jigoshop'),
				'set_weight'          => __('Enter a weight value', 'jigoshop'),
				'set_width'           => __('Enter a width value', 'jigoshop'),
				'set_length'          => __('Enter a length value', 'jigoshop'),
				'set_height'          => __('Enter a height value', 'jigoshop'),
			),
			'actions'     => array(
				'remove'      => array(
					'action'        => 'jigoshop_remove_variation',
					'nonce'         => wp_create_nonce("delete-variation"),
					'confirm'       => __('Are you sure you want to remove this variation?', 'jigoshop'),
				),
				'create'      => array(
					'action'        => 'jigoshop_create_variation',
					'panel'         => $this->generate_panel(maybe_unserialize( get_post_meta($post->ID, 'product_attributes', true) ))
				)
			)
		));
	}

	/**
	 * Echos a variable type option for the product type selector
	 *
	 * @return  void
	 */
	public function register( $type ) {
		echo '<option value="variable" ' . selected($type, 'variable', false) . '>' . __('Variable', 'jigoshop') . '</option>';
	}

	/**
	 * Removes a product variation via XHR
	 *
	 * @return  void
	 */
	public function remove() {
		check_ajax_referer( 'delete-variation', 'security' );

		$ID = intval( $_POST['variation_id'] );
		wp_set_object_terms( $ID, null, 'product_type'); // Remove object terms
		wp_delete_post( $ID );

		exit;
	}

	/**
	 * Process the product variable meta
	 *
	 * @param   int   Product ID
	 * @return  void
	 */
	public function save( $parent_id ) {
		global $wpdb;

		// Do not run if there are no variations
		if ( ! isset($_POST['variations']) )
			return false;

		// Get the attributes to be used later
		$attributes = (array) maybe_unserialize( get_post_meta($parent_id, 'product_attributes', true) );

		foreach( $_POST['variations'] as $ID => $meta ) {

			// Update post data or Add post if new
			if ( strpos($ID, '_new') ) {
				$ID = wp_insert_post( array(
					'post_title'  => "#{$parent_id}: Child Variation",
					'post_status' => isset($meta['enabled']) ? 'publish' : 'draft',
					'post_parent' => $parent_id,
					'post_type'   => 'product_variation'
				));
			}
			else {
				$wpdb->update( $wpdb->posts, array(
					'post_title'  => "#{$parent_id}: Child Variation",
					'post_status' => isset($meta['enabled']) ? 'publish' : 'draft'
				), array( 'ID'    => $ID ) );
			}

			// Set the product type
			// NOTE: I think this will work, not sure -Rob
			wp_set_object_terms( $ID, sanitize_title($meta['product-type']), 'product_type');

			// Set variation meta data
			update_post_meta( $ID, 'sku',           $meta['sku'] );
			update_post_meta( $ID, 'regular_price', $meta['regular_price'] );
			update_post_meta( $ID, 'sale_price',    $meta['sale_price'] );

			update_post_meta( $ID, 'weight',        $meta['weight'] );
			update_post_meta( $ID, 'length',        $meta['length'] );
			update_post_meta( $ID, 'height',        $meta['height'] );
			update_post_meta( $ID, 'width',         $meta['width'] );

			update_post_meta( $ID, 'stock',         $meta['stock'] );
			update_post_meta( $ID, '_thumbnail_id', $meta['_thumbnail_id'] );

			// Downloadable Only
			if( $meta['product-type'] == 'downloadable' ) {
				update_post_meta( $ID, 'file_path',      $meta['file_path']);
				update_post_meta( $ID, 'download_limit', $meta['download_limit']);
			}

			// Refresh taxonomy attributes
			$current_meta = get_post_custom( $ID );

			// Remove the current data
			delete_post_meta( $ID, 'variation_data' );

			// Update taxonomies
			$variation_data = array();
			foreach ( $attributes as $attribute ) {

				// Skip if attribute is not for variation
				if ( ! $attribute['variation'] )
					continue;

				// Configure the data
				$key = 'tax_' . sanitize_title($attribute['name']);
				$variation_data[$key] = $meta[$key];
			}

			update_post_meta( $ID, 'variation_data', $variation_data );
		}
	}

	public function display() {
		global $post;

		// Get the attributes
		$attributes = (array) maybe_unserialize( get_post_meta($post->ID, 'product_attributes', true) );

		// Get all variations of the product
		$variations = get_posts(array(
			'post_type'   => 'product_variation',
			'post_status' => array('draft', 'publish'),
			'numberposts' => -1,
			'orderby'     => 'id',
			'order'       => 'desc',
			'post_parent' => $post->ID
		));

		?>

		<div id='variable_product_options' class='panel'>
		<?php if($this->has_variable_attributes($attributes)): ?>
			<div class="toolbar">
				<select name="variation_actions">
					<option value="default"><?php _e('Bulk Actions', 'jigoshop') ?></option>
					<option value="remove_all"><?php _e('Remove All Variations', 'jigoshop') ?></option>
					<optgroup label="<?php _e('Set All', 'jigoshop') ?>:">
						<option value="set_all_regular_prices"><?php _e('Regular Prices', 'jigoshop') ?></option>
						<option value="set_all_sale_prices"><?php _e('Sale Prices', 'jigoshop') ?></option>
						<option value="set_all_stock"><?php _e('Stock', 'jigoshop') ?></option>
						<option value="set_all_weight"><?php _e('Weight', 'jigoshop') ?></option>
						<option value="set_all_width"><?php _e('Width', 'jigoshop') ?></option>
						<option value="set_all_length"><?php _e('Length', 'jigoshop') ?></option>
						<option value="set_all_height"><?php _e('Height', 'jigoshop') ?></option>
					</optgroup>
				</select>
				<input id="do_actions" type="submit" class="button-secondary" value="Apply">
					<button type='button' class='button button-seconday add_variation'><?php _e('Add Variation', 'jigoshop') ?></button>
			</div>
		<?php endif; ?>
			<div class='jigoshop_variations'>

				<?php if ( ! $variations ): ?>


				<div class="demo variation ">
					<a href="http://forum.jigoshop.com/kb/creating-products/variable-products" target="_blank" class="overlay"><span><?php _e('Learn how to make a Variation', 'jigoshop'); ?></span></a>
					<div class="inside">
						<div class="jigoshop_variation postbox">
							<button type="button" class="remove_variation button">Remove</button>
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="handle">
								<select>
										<option value="Medium">Medium</option>
								</select>
							</h3>

							<div class="inside">
								<table cellspacing="0" cellpadding="0" class="jigoshop_variable_attributes">
									<tr>
										<td class="upload_image" rowspan="2">
											<a href="#" class="upload_image_button " rel="0_new">
												<img src="<?php echo jigoshop::assets_url().'/assets/images/placeholder.png' ?>" width="93px">
												<input type="hidden" class="upload_image_id" value="">
												<!-- TODO: APPEND THIS IN JS <span class="overlay"></span> -->
											</a>
										</td>

										<td>
											<label class="clearlabel">Type</label>
											<select class="product_type">
												<option value="simple">Simple</option>
											</select>
										</td>

										<td>
											<label>SKU
												<input type="text" value="SKU1" />
											</label>
										</td>

										<td>
											<label>Stock Qty
												<input type="text" value="12">
											</label>
										</td>

										<td>
											<label>Price
												<input type="text" value="19.99">
											</label>
										</td>

										<td>
											<label>Sale Price
												<input type="text" value="13.99">
											</label>
										</td>

										<td>
											<label>Enabled
												<input type="checkbox" class="checkbox" checked="checked">
											</label>
										</td>
									</tr>
									<tr class="simple options">
										<td>
											<label>Weight
												<input type="text" value="22.5">
											</label>
										</td>
											<td colspan="4" class="dimensions">
												<label>Dimensions <?php echo '('.get_option('jigoshop_dimension_unit'). ')' ?></label>
												<input type="text" placeholder="Length" value="">
												<input type="text" placeholder="Width" value="">
												<input type="text" placeholder="Height" value="">
											</td>
											<td colspan="3">&nbsp;</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if ( $this->has_variable_attributes( $attributes ) ): ?>
					<?php
						if( $variations ) {
							foreach( $variations as $variation ) {
								echo $this->generate_panel($attributes, $variation);
							}
						}
					?>
				<?php endif; ?>
			</div>
		</div>
	<?php
	}

	/**
	 * Returns a specially formatted field name for variations
	 *
	 * @param   string   Field Name
	 * @param   object   Variation Post Object
	 * @return  string
	 */
	private function field_name( $name, $variation = null ) {
		return "variations[{$variation->ID}][{$name}]";
	}

	/**
	 * Returns all the possible variable attributes in select form
	 *
	 * @param   array    Attributes array
	 * @param   object   Variation Post Object
	 * @return  HTML
	 */
	private function attribute_selector( $attributes, $variation = null ) {
		global $post;
		$html = null;

		if ( ! is_ajax() ) {
			$variation_data = get_post_meta( $variation->ID, 'variation_data' );
		}

		// Attribute Variation Selector
		foreach ( $attributes as $attr ) {

			// If not variable attribute then skip
			if ( ! $attr['variation'] )
				continue;

			// Get current value for variation (if set)
			if ( ! is_ajax() ) {
				$selected = $variation_data[0][ 'tax_' . sanitize_title($attr['name']) ];
			}

			// Open the select & set a default value
			$html .= '<select name="' . $this->field_name('tax_' . sanitize_title($attr['name']), $variation) . '" >
						<option value="">'.__('Any', 'jigoshop') . ' ' .jigoshop_product::attribute_label('pa_'.$attr['name']).'&hellip;</option>';

			// Get terms for attribute taxonomy or value if its a custom attribute
			if ( $attr['is_taxonomy'] ) {

				$options = get_the_terms( $post->ID, 'pa_'.sanitize_title($attr['name']));
				foreach( $options as $option ) {
					$html .= '<option value="'.esc_attr($option->slug).'" '.selected($selected, $option->slug, false).'>'.$option->name.'</option>';
				}

			}
			else {

				$options = explode(',', $attr['value']);
				foreach( $options as $option ) {
					$option = trim($option);
					$html .= '<option '.selected($selected, $option, false).' value="'.esc_attr($option).'">'.$option.'</option>';
				}

			}

			// Close the select
			$html .= '</select>';
		}

		return $html;
	}

	/**
	 * Checks all the product attributes for variation defined attributes
	 *
	 * @param   mixed   Attributes
	 * @return  bool
	 */
	private function has_variable_attributes( $attributes ) {
		if ( ! $attributes )
			return false;

		foreach ( $attributes as $attribute ) {
			if ( isset($attribute['variation']) && $attribute['variation'] )
				return true;
		}

		return false;
	}

	/**
	 * Generates a variation panel
	 *
	 * @param   array    attributes
	 * @param   object   variation
	 * @return  HTML
	 */
	private function generate_panel($attributes, $variation = null) {

		if ( ! $this->has_variable_attributes($attributes) )
			return false;

		// Set the default image as the placeholder
		$image = jigoshop::assets_url().'/assets/images/placeholder.png';

		if ( ! $variation ) {

			// Create a blank variation object with a unique id
			$variation = new stdClass;
			$variation->ID = '__ID__';
			$variation->post_status = 'publish';
		}
		else {

			// Get the variation meta
			$meta = get_post_custom( $variation->ID );

			// If variation has a thumbnail display that
			if ( $image_id = $meta['_thumbnail_id'][0] )
				$image = wp_get_attachment_url( $image_id );
		}

		// Start buffering the output
		ob_start();
		?>
		<div class="jigoshop_variation postbox closed" rel="<?php echo $variation->ID; ?>">
			<button type="button" class="remove_variation button"><?php _e('Remove', 'jigoshop'); ?></button>
			<div class="handlediv" title="Click to toggle"><br></div>
			<h3 class="handle"><?php echo $this->attribute_selector($attributes, $variation); ?></h3>

			<div class="inside">
				<table cellpadding="0" cellspacing="0" class="jigoshop_variable_attributes">
					<tbody>
						<tr>
							<td class="upload_image" rowspan="2">
								<a href="#" class="upload_image_button <?php if (isset($image_id)) echo 'remove'; ?>" rel="<?php echo $variation->ID; ?>">
									<img src="<?php echo $image ?>" width="93px" />
									<input type="hidden" name="<?php echo esc_attr( $this->field_name('_thumbnail_id', $variation) ); ?>" class="upload_image_id" value="<?php if ( isset($image_id)) echo esc_attr( $image_id ); ?>" />
									<!-- TODO: APPEND THIS IN JS <span class="overlay"></span> -->
								</a>
							</td>

							<td>
								<?php
									$terms = get_the_terms( $variation->ID, 'product_type' );
									$product_type = ($terms) ? current($terms)->slug : 'simple';
								?>
								<label class="clearlabel"><?php _e('Type', 'jigoshop') ?></label>
								<select class="product_type" name="<?php echo esc_attr( $this->field_name('product-type', $variation) ); ?>">
									<option value="simple" <?php selected('simple', $product_type) ?>>Simple</option>
									<option value="downloadable" <?php selected('downloadable', $product_type) ?>>Downloadable</option>
									<option value="virtual" <?php selected('virtual', $product_type) ?>>Virtual</option>
								</select>
							</td>

							<td>
								<label><?php _e('SKU', 'jigoshop'); ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('sku', $variation) ); ?>" placeholder="<?php echo esc_attr( $variation->ID ); ?>" value="<?php echo esc_attr( isset($meta['sku'][0]) ? $meta['sku'][0] : null ); ?>" />
								</label>
							</td>

							<td>
								<label><?php _e('Stock Qty', 'jigoshop'); ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('stock', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['stock'][0]) ? $meta['stock'][0] : null ); ?>" />
								</label>
							</td>

							<td>
								<label><?php _e('Price', 'jigoshop'); ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('regular_price', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['regular_price'][0]) ? $meta['regular_price'][0] : null ); ?>" />
								</label>
							</td>

							<td>
								<label><?php _e('Sale Price', 'jigoshop'); ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('sale_price', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['sale_price'][0]) ? $meta['sale_price'][0] : null ); ?>" />
								</label>
							</td>

							<td>
								<label><?php _e('Enabled', 'jigoshop'); ?>
									<input type="checkbox" class="checkbox" name="<?php echo esc_attr( $this->field_name('enabled', $variation) ); ?>" <?php checked($variation->post_status, 'publish'); ?> />
								</label>
							</td>
						</tr>
						<tr class="simple options" <?php echo ( ('simple' == $product_type) || ('variable' == $product_type)) ? 'style="display: table-row;"' : 'style="display: none;"';?>>
							<td>
								<label><?php _e('Weight', 'jigoshop') ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('weight', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['weight'][0]) ? $meta['weight'][0] : null ); ?>" />
								</label>
							</td>
							<td colspan="4" class="dimensions">
								<label><?php _e('Dimensions', 'jigoshop') ?> <?php echo '('.get_option('jigoshop_dimension_unit'). ')' ?></label>
								<input type="text" name="<?php echo esc_attr( $this->field_name('length', $variation) ); ?>" placeholder="Length" value="<?php echo esc_attr( isset($meta['length'][0]) ? $meta['length'][0] : null ); ?>" />
								<input type="text" name="<?php echo esc_attr( $this->field_name('width', $variation) ); ?>" placeholder="Width" value="<?php echo esc_attr( isset($meta['width'][0]) ? $meta['width'][0] : null ); ?>" />
								<input type="text" name="<?php echo esc_attr( $this->field_name('height', $variation) ); ?>" placeholder="Height" value="<?php echo esc_attr( isset($meta['height'][0]) ? $meta['height'][0] : null ); ?>" />
								<td colspan="3"></td>
							</td>
						</tr>
						<tr class="downloadable options" <?php echo ('downloadable' == $product_type) ? 'style="display: table-row;"' : 'style="display: none;"';?>>
							<td colspan="4" class="download_file">
								<label class="clearlabel"><?php _e('File Location', 'jigoshop') ?></label>
								<input type="text" name="<?php echo esc_attr( $this->field_name('file_path', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['file_path'][0]) ? $meta['file_path'][0] : null ); ?>" />
								<input type="submit" class="upload_file_button button-secondary" value="Upload">
							</td>
							<td colspan="2">
								<label><?php _e('Re-downloads Limit', 'jigoshop') ?>
									<input type="text" name="<?php echo esc_attr( $this->field_name('download_limit', $variation) ); ?>" value="<?php echo esc_attr( isset($meta['file_path'][0]) ? $meta['download_limit'][0] : null ); ?>" />
								</label>
							</td>
						</tr>
						<tr class="virtual options" <?php echo ('virtual' == $product_type ? 'style="display: table-row;"' : 'style="display: none;"');?>>
							<td colspan="6">
								&nbsp;
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	<?php
	// Flush & return the buffer
	return ob_get_clean();
	}
} new jigoshop_product_meta_variable();
