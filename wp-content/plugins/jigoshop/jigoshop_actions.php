<?php
/**
 * Various hooks Jigoshop core uses
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package		Jigoshop
 * @category	Core
 * @author		Jigowatt
 * @copyright	Copyright (c) 2011-2012 Jigowatt Ltd.
 * @license		http://jigoshop.com/license/commercial-edition
 */

/**
 * Various hooks Jigoshop uses to do stuff. index:
 *
 *		- Add order item
 *		- When default permalinks are enabled, redirect shop page to post type archive url
 *		- Add to Cart
 *		- Clear cart
 *		- Restore an order via a link
 *		- Cancel a pending order
 *		- Download a file
 *		- Order Status completed - GIVE DOWNLOADABLE PRODUCT ACCESS TO CUSTOMER
 *
 **/

/**
 * Add order item
 *
 * Add order item via ajax
 *
 * @since 		1.0
 */
add_action('wp_ajax_jigoshop_add_order_item', 'jigoshop_add_order_item');

function jigoshop_add_order_item() {

	check_ajax_referer( 'add-order-item', 'security' );

	global $wpdb;

	$item_to_add = trim(stripslashes($_POST['item_to_add']));

	$post = '';

	// Find the item
	if (is_numeric($item_to_add)) :
		$post = get_post( $item_to_add );
	endif;

	if (!$post || ($post->post_type!=='product' && $post->post_type!=='product_variation')) :
		$post_id = $wpdb->get_var($wpdb->prepare("
			SELECT post_id
			FROM $wpdb->posts
			LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
			WHERE $wpdb->postmeta.meta_key = 'SKU'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'shop_product'
			AND $wpdb->postmeta.meta_value = '".$item_to_add."'
			LIMIT 1
		"));
		$post = get_post( $post_id );
	endif;

	if (!$post || ($post->post_type!=='product' && $post->post_type!=='product_variation')) :
		die();
	endif;

	if ($post->post_type=="product") :
		$_product = new jigoshop_product( $post->ID );
	else :
		$_product = new jigoshop_product_variation( $post->ID );
	endif;

	$loop = 0;
	?>
	<tr class="item">
		<td class="product-id">#<?php echo $_product->id; ?></td>
		<td class="variation-id"><?php if (isset($_product->variation_id)) echo $_product->variation_id; else echo '-'; ?></td>
		<td class="product-sku"><?php if ($_product->sku) echo $_product->sku; ?></td>
		<td class="name"><a href="<?php echo esc_url( admin_url('post.php?post='. $_product->id .'&action=edit') ); ?>"><?php echo $_product->get_title(); ?></a></td>
		<td class="variation"><?php
			if (isset($_product->variation_data)) :
				echo jigoshop_get_formatted_variation( $_product->variation_data, true );
			else :
				echo '-';
			endif;
		?></td>
		<!--<td>
			<table class="meta" cellspacing="0">
				<tfoot>
					<tr>
						<td colspan="3"><button class="add_meta button"><?php _e('Add meta', 'jigoshop'); ?></button></td>
					</tr>
				</tfoot>
				<tbody></tbody>
			</table>
		</td>-->
		<?php do_action('jigoshop_admin_order_item_values', $_product); ?>
		<td class="quantity"><input type="text" name="item_quantity[]" placeholder="<?php _e('Quantity e.g. 2', 'jigoshop'); ?>" value="1" /></td>
        <td class="cost"><input type="text" name="item_cost[]" placeholder="<?php _e('Cost per unit ex. tax e.g. 2.99', 'jigoshop'); ?>" value="<?php echo esc_attr( get_option('jigoshop_prices_include_tax') == 'yes' ? $_product->get_price_excluding_tax() : $_product->get_price() ); ?>" /></td>
        <td class="tax"><input type="text" name="item_tax_rate[]" placeholder="<?php _e('Tax Rate e.g. 20.0000', 'jigoshop'); ?>" value="<?php echo esc_attr( jigoshop_tax::calculate_total_tax_rate($_product->get_tax_base_rate()) ); ?>" /></td>
		<td class="center">
			<input type="hidden" name="item_id[]" value="<?php echo esc_attr( $_product->id ); ?>" />
			<input type="hidden" name="item_name[]" value="<?php echo esc_attr( $_product->get_title() ); ?>" />
            <input type="hidden" name="item_variation_id[]" value="<?php if ($_product instanceof jigoshop_product_variation) echo esc_attr( $_product->variation_id ); else echo ''; ?>" />
			<button type="button" class="remove_row button">&times;</button>
		</td>
	</tr>
	<?php

	// Quit out
	die();
}


/**
 * When default permalinks are enabled, redirect shop page to post type archive url
 **/
if (get_option( 'permalink_structure' )=="") add_action( 'init', 'jigoshop_shop_page_archive_redirect' );

function jigoshop_shop_page_archive_redirect() {

	if ( isset($_GET['page_id']) && $_GET['page_id'] == jigoshop_get_page_id('shop') ) :
		wp_safe_redirect( get_post_type_archive_link('product') );
		exit;
	endif;

}

/**
 * Remove from cart/update
 **/
add_action( 'init', 'jigoshop_update_cart_action' );

function jigoshop_update_cart_action() {

	// Remove from cart
	if ( isset($_GET['remove_item']) && is_numeric($_GET['remove_item'])  && jigoshop::verify_nonce('cart', '_GET')) :

		jigoshop_cart::set_quantity( $_GET['remove_item'], 0 );

		// Re-calc price
		//jigoshop_cart::calculate_totals();

		jigoshop::add_message( __('Cart updated.', 'jigoshop') );

		if ( isset($_SERVER['HTTP_REFERER'])) :
			wp_safe_redirect($_SERVER['HTTP_REFERER']);
			exit;
		endif;

	// Update Cart
	elseif (isset($_POST['update_cart']) && $_POST['update_cart']  && jigoshop::verify_nonce('cart')) :

		$cart_totals = $_POST['cart'];

		if (sizeof(jigoshop_cart::$cart_contents)>0) :
			foreach (jigoshop_cart::$cart_contents as $cart_item_key => $values) :

				if (isset($cart_totals[$cart_item_key]['qty'])) jigoshop_cart::set_quantity( $cart_item_key, $cart_totals[$cart_item_key]['qty'] );

			endforeach;
		endif;

		jigoshop::add_message( __('Cart updated.', 'jigoshop') );

	endif;

}

/**
 * Add to cart
 **/
add_action( 'init', 'jigoshop_add_to_cart_action' );

function jigoshop_add_to_cart_action($url = false)
{
    //if required param is not set or nonce is invalid then just ignore whole function
    if (empty($_GET['add-to-cart']) || !jigoshop::verify_nonce('add_to_cart', '_GET')) {
        return;
    }

    $product_added = false;

    //single product
    if (is_numeric($_GET['add-to-cart'])) {
        $product_id = apply_filters('jigoshop_product_id_add_to_cart_filter', (int) $_GET['add-to-cart']);
        $quantity = 1;
        if (isset($_POST['quantity'])) {
            $quantity = (int) $_POST['quantity'];
        }

        jigoshop_cart::add_to_cart($product_id, $quantity);

        $product_added = true;
    } else if ($_GET['add-to-cart'] == 'variation') { //variable product variation

        //variaton wasn't selected but user managed to submit a form
        if (empty($_POST['variation_id']) || !is_numeric($_POST['variation_id'])) {
            /* Link on product pages */
            jigoshop::add_error(__('Please choose product options&hellip;', 'jigoshop'));
            wp_redirect(get_permalink($_GET['product']));
            exit;
        } else {
            $product_id = apply_filters('jigoshop_product_id_add_to_cart_filter', (int) $_POST['product_id']);
            $variation_id = (int) $_POST['variation_id'];
            $quantity = 1;
            if (isset($_POST['quantity'])) {
                $quantity = (int) $_POST['quantity'];
            }

            $attributes = (array) maybe_unserialize(get_post_meta($product_id, 'product_attributes', true));
            $variations = array();
            $all_variations_set = true;

            foreach ($attributes as $attribute) {

                if ( ! $attribute['variation']) {
                    continue;
                }

                $attr_name = 'tax_' . sanitize_title($attribute['name']);
                if (!empty($_POST[$attr_name])) {
                    $variations[$attr_name] = $_POST[$attr_name];
                } else {
                    $all_variations_set = false;
                }
            }

            if ($all_variations_set && $variation_id > 0) { //all variation options are set
                jigoshop_cart::add_to_cart($product_id, $quantity, $variation_id, $variations);

                $product_added = true;
            } else {
                /* Link on product pages */
                jigoshop::add_error(__('Please choose product options&hellip;', 'jigoshop'));
                wp_redirect(apply_filters('jigoshop_product_id_add_to_cart_filter', get_permalink($_GET['product'])));
                exit;
            }
        }
    } else if ($_GET['add-to-cart'] == 'group') { //grouped product
        // Group add to cart
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {

            $total_quantity = 0;

            foreach ($_POST['quantity'] as $item => $quantity) {
                $quantity = (int)$quantity;

                if ($quantity > 0) {
                    jigoshop_cart::add_to_cart($item, $quantity);

                    $total_quantity = $total_quantity + $quantity;
                }
            }

            if ($total_quantity == 0) {
                jigoshop::add_error(__('Please choose a quantity&hellip;', 'jigoshop'));
            } else {
                $product_added = true;
            }
        } else if ($_GET['product']) {
            /* Link on product pages */
            jigoshop::add_error(__('Please choose a product&hellip;', 'jigoshop'));
            wp_redirect(get_permalink($_GET['product']));
            exit;
        }
    }

    //if product was successfully added to the cart
    if ($product_added) {

    	switch ( get_option('jigoshop_redirect_add_to_cart', 'same_page') ) {
    		case 'same_page':
    			jigoshop::add_message(sprintf(__('<a href="%s" class="button">View Cart &rarr;</a> Product successfully added to your cart.', 'jigoshop'), jigoshop_cart::get_cart_url()));
    			break;

    		case 'to_checkout':
    				// Do nothing
    			break;

    		default:
    			jigoshop::add_message(__('Product successfully added to your cart.', 'jigoshop'));
    			break;
    	}

    }

    $url = apply_filters('add_to_cart_redirect', $url);

    // If has custom URL redirect there
    if ($url) {
        wp_safe_redirect($url);
    }
    // Redirect directly to checkout if no error messages
    else if (get_option('jigoshop_redirect_add_to_cart', 'same_page') == 'to_checkout' && jigoshop::error_count() == 0) {
        wp_safe_redirect(jigoshop_cart::get_checkout_url());
    }
    // Redirect directly to cart if no error messages
    else if (get_option('jigoshop_redirect_add_to_cart', 'to_cart') == 'to_cart' && jigoshop::error_count() == 0) {
        wp_safe_redirect(jigoshop_cart::get_cart_url());
    }
    // Otherwise redirect to where they came
    else if (isset($_SERVER['HTTP_REFERER'])) {
        wp_safe_redirect($_SERVER['HTTP_REFERER']);
    }
    // If all else fails redirect to root
    else {
        wp_redirect(home_url());
    }

    exit;
}

function jigoshop_ajax_update_order_review() {

	check_ajax_referer( 'update-order-review', 'security' );

	if (!defined('JIGOSHOP_CHECKOUT')) define('JIGOSHOP_CHECKOUT', true);

	if (sizeof(jigoshop_cart::$cart_contents)==0) :
		echo '<p class="error">'.__('Sorry, your session has expired.', 'jigoshop').' <a href="'.home_url().'">'.__('Return to homepage &rarr;', 'jigoshop').'</a></p>';
		exit;
	endif;

	do_action('jigoshop_checkout_update_order_review', $_POST['post_data']);

        if (isset($_POST['shipping_method'])) :

		$shipping_method = explode(":", $_POST['shipping_method']);
	 	jigoshop_session::instance()->chosen_shipping_method_id = $shipping_method[0];

                if (is_numeric($shipping_method[2])) :
                    jigoshop_session::instance()->selected_rate_id = $shipping_method[2];
                endif;

	endif;

	if (isset($_POST['country'])) jigoshop_customer::set_country( $_POST['country'] );
	if (isset($_POST['state'])) jigoshop_customer::set_state( $_POST['state'] );
	if (isset($_POST['postcode'])) jigoshop_customer::set_postcode( $_POST['postcode'] );
	if (isset($_POST['s_country'])) jigoshop_customer::set_shipping_country( $_POST['s_country'] );
	if (isset($_POST['s_state'])) jigoshop_customer::set_shipping_state( $_POST['s_state'] );
	if (isset($_POST['s_postcode'])) jigoshop_customer::set_shipping_postcode( $_POST['s_postcode'] );

	jigoshop_cart::calculate_totals();

	do_action('jigoshop_checkout_order_review');

	die();
}
add_action('wp_ajax_jigoshop_update_order_review', 'jigoshop_ajax_update_order_review');
add_action('wp_ajax_nopriv_jigoshop_update_order_review', 'jigoshop_ajax_update_order_review');

/**
 * Clear cart
 **/
add_action( 'wp_header', 'jigoshop_clear_cart_on_return' );

function jigoshop_clear_cart_on_return() {

	if (is_page(jigoshop_get_page_id('thanks'))) :

		if (isset($_GET['order'])) $order_id = $_GET['order']; else $order_id = 0;
		if (isset($_GET['key'])) $order_key = $_GET['key']; else $order_key = '';
		if ($order_id > 0) :
			$order = new jigoshop_order( $order_id );
			if ($order->order_key == $order_key) :
				jigoshop_cart::empty_cart();
			endif;
		endif;

	endif;

}

/**
 * Clear the cart after payment - order will be processing or complete
 **/
add_action( 'init', 'jigoshop_clear_cart_after_payment' );

function jigoshop_clear_cart_after_payment( $url = false ) {

	if (isset( jigoshop_session::instance()->order_awaiting_payment ) && jigoshop_session::instance()->order_awaiting_payment > 0) :

		$order = new jigoshop_order( jigoshop_session::instance()->order_awaiting_payment );

		if ($order->id > 0 && ($order->status=='completed' || $order->status=='processing')) :

			jigoshop_cart::empty_cart();

			unset( jigoshop_session::instance()->order_awaiting_payment );

		endif;

	endif;

}


/**
 * Process the login form
 **/
add_action('init', 'jigoshop_process_login');

function jigoshop_process_login() {

	if (isset($_POST['login']) && $_POST['login']) :

		jigoshop::verify_nonce('login');

		if ( !isset($_POST['username']) || empty($_POST['username']) ) jigoshop::add_error( __('Username is required.', 'jigoshop') );
		if ( !isset($_POST['password']) || empty($_POST['password']) ) jigoshop::add_error( __('Password is required.', 'jigoshop') );

		if (jigoshop::error_count()==0) :

			$creds = array();
			$creds['user_login'] = $_POST['username'];
			$creds['user_password'] = $_POST['password'];
			$creds['remember'] = true;
			$secure_cookie = is_ssl() ? true : false;
			$user = wp_signon( $creds, $secure_cookie );
			if ( is_wp_error($user) ) :
				jigoshop::add_error( $user->get_error_message() );
			else :
				if ( isset($_SERVER['HTTP_REFERER'])) {
					wp_safe_redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				wp_redirect(apply_filters('jigoshop_get_myaccount_page_id', get_permalink(jigoshop_get_page_id('myaccount'))));
				exit;
			endif;

		endif;

	endif;
}

/**
 * Process ajax checkout form
 */
add_action('wp_ajax_jigoshop-checkout', 'jigoshop_process_checkout');
add_action('wp_ajax_nopriv_jigoshop-checkout', 'jigoshop_process_checkout');

function jigoshop_process_checkout () {
	include_once jigoshop::plugin_path() . '/classes/jigoshop_checkout.class.php';

	jigoshop_checkout::instance()->process_checkout();

	die(0);
}


/**
 * Cancel a pending order - hook into init function
 **/
add_action('init', 'jigoshop_cancel_order');

function jigoshop_cancel_order() {

	if ( isset($_GET['cancel_order']) && isset($_GET['order']) && isset($_GET['order_id']) ) :

		$order_key = urldecode( $_GET['order'] );
		$order_id = (int) $_GET['order_id'];

		$order = new jigoshop_order( $order_id );

		if ($order->id == $order_id && $order->order_key == $order_key && $order->status=='pending' && jigoshop::verify_nonce('cancel_order', '_GET')) :

			// Cancel the order + restore stock
			$order->cancel_order( __('Order cancelled by customer.', 'jigoshop') );

			// Message
			jigoshop::add_message( __('Your order was cancelled.', 'jigoshop') );

		elseif ($order->status!='pending') :

			jigoshop::add_error( __('Your order is no longer pending and could not be cancelled. Please contact us if you need assistance.', 'jigoshop') );

		else :

			jigoshop::add_error( __('Invalid order.', 'jigoshop') );

		endif;

		wp_safe_redirect(jigoshop_cart::get_cart_url());
		exit;

	endif;
}


/**
 * Download a file - hook into init function
 **/
add_action('init', 'jigoshop_download_product');

function jigoshop_download_product() {

	if ( isset($_GET['download_file']) && isset($_GET['order']) && isset($_GET['email']) ) :

		global $wpdb;

		$download_file = (int) urldecode($_GET['download_file']);
		$order = urldecode( $_GET['order'] );
		$email = urldecode( $_GET['email'] );

		if (!is_email($email)) :
			wp_die( __('Invalid email address.', 'jigoshop') . ' <a href="'.home_url().'">' . __('Go to homepage &rarr;', 'jigoshop') . '</a>' );
		endif;

		$download_result = $wpdb->get_row( $wpdb->prepare("
			SELECT downloads_remaining
			FROM ".$wpdb->prefix."jigoshop_downloadable_product_permissions
			WHERE user_email = %s
			AND order_key = %s
			AND product_id = %s
		;", $email, $order, $download_file ) );

		if (!$download_result) :
			wp_die( __('Invalid download.', 'jigoshop') . ' <a href="'.home_url().'">' . __('Go to homepage &rarr;', 'jigoshop') . '</a>' );
			exit;
		endif;

		$order_id = $download_result->order_id;
		$downloads_remaining = $download_result->downloads_remaining;

		if ($order_id) :
			$order = new jigoshop_order( $order_id );
			if ($order->status!='completed' && $order->status!='processing' && $order->status!='publish') :
				wp_die( __('Invalid order.', 'jigoshop') . ' <a href="'.home_url().'">' . __('Go to homepage &rarr;', 'jigoshop') . '</a>' );
				exit;
			endif;
		endif;

		if ($downloads_remaining == '0') :
            wp_die( sprintf(__('Sorry, you have reached your download limit for this file. <a href="%s">Go to homepage &rarr;</a>', 'jigoshop'), home_url()) );
		else :
			if ($downloads_remaining>0) :
				$wpdb->update( $wpdb->prefix . "jigoshop_downloadable_product_permissions", array(
					'downloads_remaining' => $downloads_remaining - 1,
				), array(
					'user_email' => $email,
					'order_key' => $order,
					'product_id' => $download_file
				), array( '%d' ), array( '%s', '%s', '%d' ) );
			endif;

			$file_path = get_post_meta($download_file, 'file_path', true);

			if (!$file_path) wp_die( sprintf(__('File not found. <a href="%s">Go to homepage &rarr;</a>', 'jigoshop'), home_url()) );

			// Get URLS with https
			$site_url = site_url();
			$network_url = network_admin_url();
			if (is_ssl()) :
				$site_url = str_replace('https:', 'http:', $site_url);
				$network_url = str_replace('https:', 'http:', $network_url);
			endif;

			if (!is_multisite()) :
				$file_path = str_replace(trailingslashit($site_url), ABSPATH, $file_path);
			else :
				$upload_dir = wp_upload_dir();

				// Try to replace network url
				$file_path = str_replace(trailingslashit($network_url), ABSPATH, $file_path);

				// Now try to replace upload URL
				$file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_path);
			endif;

			// See if its local or remote
			if (strstr($file_path, 'http:') || strstr($file_path, 'https:') || strstr($file_path, 'ftp:')) :
				$remote_file = true;
			else :
				$remote_file = false;
				$file_path = realpath($file_path);
			endif;

			// Download the file
			$file_extension = strtolower(substr(strrchr($file_path,"."),1));

			switch ($file_extension) :
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpe": case "jpeg": case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
			endswitch;

			@ini_set('zlib.output_compression', 'Off');
			@set_time_limit(0);
			@session_start();
			@session_cache_limiter('none');
			@set_magic_quotes_runtime(0);
			@ob_end_clean();
			if (ob_get_level()) @ob_end_clean();
			@session_write_close();

			header("Pragma: no-cache");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Robots: none");
			header("Content-Type: ".$ctype."");
			header("Content-Description: File Transfer");
			header("Content-Transfer-Encoding: binary");

			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
				// workaround for IE filename bug with multiple periods / multiple dots in filename
				$iefilename = preg_replace('/\./', '%2e', basename($file_path), substr_count(basename($file_path), '.') - 1);
				header("Content-Disposition: attachment; filename=\"".$iefilename."\";");
			} else {
				header("Content-Disposition: attachment; filename=\"".basename($file_path)."\";");
			}

			header("Content-Length: ".@filesize($file_path));


			if ( $remote_file ) {
				 header('Location: '.$file_path);
			} else {
				@readfile("$file_path") or wp_die( sprintf(__('File not found. <a href="%s">Go to homepage &rarr;</a>', 'jigoshop'), home_url()) );
			}
			exit;
		endif;

	endif;
}


/**
 * Order Status completed - GIVE DOWNLOADABLE PRODUCT ACCESS TO CUSTOMER
 **/
add_action('order_status_completed', 'jigoshop_downloadable_product_permissions');

function jigoshop_downloadable_product_permissions( $order_id ) {

	global $wpdb;

	$order = new jigoshop_order( $order_id );

	if (sizeof($order->items)>0) foreach ($order->items as $item) :

		// if ($item['id']>0) :

			// @todo: Bit of a hack could be improved as id is null/0
			if ( (bool) $item['variation_id'] ) {
				$_product = new jigoshop_product_variation( $item['variation_id'] );
				$product_id = $_product->variation_id;
			} else {
				$_product = new jigoshop_product( $item['id'] );
				$product_id = $_product->ID;
			}

			if ( $_product->exists && $_product->is_type('downloadable') ) :

				$user_email = $order->billing_email;

				if ($order->user_id>0) :
					$user_info = get_userdata($order->user_id);
					if ($user_info->user_email) :
						$user_email = $user_info->user_email;
					endif;
				else :
					$order->user_id = 0;
				endif;

				$limit = trim(get_post_meta($_product->id, 'download_limit', true));

				if (!empty($limit)) :
					$limit = (int) $limit;
				else :
					$limit = '';
				endif;

				// Downloadable product - give access to the customer
				$wpdb->insert( $wpdb->prefix . 'jigoshop_downloadable_product_permissions', array(
					'product_id' => $product_id,
					'user_id' => $order->user_id,
					'user_email' => $user_email,
					'order_key' => $order->order_key,
					'downloads_remaining' => $limit
				), array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s'
				) );

			endif;

		// endif;

	endforeach;
}

/**
 * Displays Google Analytics tracking code in the footer
 *
 * @return  void
 */
add_action( 'wp_footer', 'jigoshop_ga_tracking' );
function jigoshop_ga_tracking() {

	// If admin don't track..shouldn't require this
	if ( is_admin() )
		return false;

	// Don't track the shop owners roaming
	if ( current_user_can('manage_options') )
		return false;

	$tracking_id = get_option('jigoshop_ga_id');

	if ( ! $tracking_id )
		return false;

	$loggedin = (is_user_logged_in()) ? 'yes' : 'no';

	if ( is_user_logged_in() ) {
		$user_id 		= get_current_user_id();
		$current_user 	= get_user_by('id', $user_id);
		$username 		= $current_user->user_login;
	}
	else {
		$user_id 		= null;
		$username 		= __('Guest', 'jigoshop');
	}
	?>
	<script>
	    var _gaq=[['_setAccount','<?php echo $tracking_id; ?>'],['_setCustomVar',1,'logged-in','<?php echo $loggedin; ?>',1],['_setCustomVar',2,'user-id','<?php echo $user_id; ?>',1],['_setCustomVar',3, 'username','<?php echo $username; ?>',1],['_trackPageview']];
	    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g,s)}(document,'script'));
  	</script>
	<?php
}

/**
 * Displays Google Analytics eCommerce tracking code on thank you page
 *
 * @return  void
 */
add_action( 'jigoshop_thankyou', 'jigoshop_ga_ecommerce_tracking' );
function jigoshop_ga_ecommerce_tracking( $order_id ) {

	// Skip if disabled
	if ( get_option('jigoshop_ga_ecommerce_tracking_enabled') != 'yes' )
		return false;

	// Don't track the shop owners roaming
	if ( current_user_can('manage_options') )
		return false;

	$tracking_id = get_option('jigoshop_ga_id');

	if ( ! $tracking_id )
		return false;

	// Unhook standard tracking so we don't count a view twice
	remove_action('wp_footer', 'jigoshop_ga_tracking');

	// Get the order and output tracking code
	$order = new jigoshop_order($order_id);

	$loggedin = (is_user_logged_in()) ? 'yes' : 'no';

	if ( is_user_logged_in() ) {
		$user_id 		= get_current_user_id();
		$current_user 	= get_user_by('id', $user_id);
		$username 		= $current_user->user_login;
	}
	else {
		$user_id 		= '';
		$username 		= __('Guest', 'jigoshop');
	}

	?>
	<script>
		var _gaq = [
			['_setAccount', '<?php echo $tracking_id; ?>'],
			['_setCustomVar', 1, 'logged-in', '<?php echo $loggedin; ?>', 1],
			['_setCustomVar', 2, 'user-id', '<?php echo $user_id; ?>', 1],
			['_setCustomVar', 3, 'username', '<?php echo $username; ?>', 1],
			['_trackPageview'],

			['_addTrans',
			'<?php echo $order_id; ?>',                // Order ID
			'<?php bloginfo('name'); ?>',              // Store Title
			'<?php echo $order->order_total; ?>',      // Order Total Amount
			'<?php echo $order->get_total_tax(); ?>',  // Order Tax Amount
			'<?php echo $order->order_shipping; ?>',   // Order Shipping Amount
			'<?php echo $order->billing_city; ?>',     // Billing City
			'<?php echo $order->billing_state; ?>',    // Billing State
			'<?php echo $order->billing_country; ?>'   // Billing Country
			],

			<?php if ($order->items) foreach($order->items as $item) : $_product = $order->get_product_from_item( $item ); ?>
				['_addItem',
				'<?php echo $order_id; ?>',             // Order ID
				'<?php echo $_product->sku; ?>',        // SKU
				'<?php echo $item['name']; ?>',         // Product Title
				'<?php if (isset($_product->variation_data))
					echo jigoshop_get_formatted_variation( $_product->variation_data, true ); ?>',   // category or variation
				'<?php echo ($item['cost']/$item['qty']); ?>', // Unit Price
				'<?php echo $item['qty']; ?>'           // Quantity
				],
			<?php endforeach; ?>

			['_trackTrans'] // Submits the transaction to the Analytics servers
		];

		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	<?php
}

/**
 * Jigoshop Dropdown categories
 *
 * @see     http://core.trac.wordpress.org/ticket/13258
 * @param   integer   Show Product Count?
 * @param   integer   Show Hierarchy?
 * @return  void
 */
function jigoshop_product_dropdown_categories( $show_counts = true, $hierarchal = true ) {
	global $wp_query;

	$r = array();
	$r['pad_counts'] = 1;
	$r['hierarchal'] = $hierarchal;
	$r['hide_empty'] = 1;
	$r['show_count'] = 1;
	$r['selected']   = (isset($wp_query->query['product_cat'])) ? $wp_query->query['product_cat'] : '';

	$terms = get_terms( 'product_cat', $r );
	if (!$terms) return;

	$output  = "<select name='product_cat' id='dropdown_product_cat'>";
	$output .= '<option value="">'.esc_html__('Select a category', 'jigoshop').'</option>';
	$output .= jigoshop_walk_category_dropdown_tree( $terms, 0, $r );
	$output .="</select>";

	echo $output;
}

/**
 * Walk the Product Categories.
 */
function jigoshop_walk_category_dropdown_tree() {
	$args = func_get_args();
	// the user's options are the third parameter
	if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
		$walker = new Jigoshop_Walker_CategoryDropdown;
	else
		$walker = $args[2]['walker'];

	return call_user_func_array(array( &$walker, 'walk' ), $args );
}

/**
 * Create HTML dropdown list of Product Categories.
 */
class Jigoshop_Walker_CategoryDropdown extends Walker {

	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

	function start_el(&$output, $object, $depth, $args, $current_object_id = 0) {
		$pad = str_repeat('&nbsp;', $depth * 3);

		$cat_name = apply_filters('list_product_cats', $object->name, $object);
		$output .= "\t<option class=\"level-$depth\" value=\"".esc_attr( $object->slug )."\"";
		if ( $object->slug == $args['selected'] )
			$output .= ' selected="selected"';
		$output .= '>';
		$output .= $pad.$cat_name;
		if ( $args['show_count'] )
			$output .= '&nbsp;('. $object->count .')';
		$output .= "</option>\n";
	}
}
