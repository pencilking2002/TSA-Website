<?php
/**
 * Functions used for custom post types in admin
 *
 * These functions control columns in admin, and other admin interface bits
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

// Add filter to ensure the text is context relevant when updated
// @todo: not sure if this is the best place to put this
add_filter( 'post_updated_messages', 'jigoshop_product_updated_messages' );
function jigoshop_product_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['product'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Product updated. <a href="%s">View Product</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Product updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Product restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Product published. <a href="%s">View Product</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Product saved.'),
    8 => sprintf( __('Product submitted. <a target="_blank" href="%s">Preview Product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Product draft updated. <a target="_blank" href="%s">Preview Product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

/**
 * Custom columns
 **/
 function jigoshop_edit_product_columns($columns) {

	$columns = array();

	$columns["cb"]    = "<input type=\"checkbox\" />";

	$columns["thumb"] = null;
	$columns["title"] = __("Title", 'jigoshop');

	$columns["featured"] = __("Featured", 'jigoshop');

	$columns["product-type"] = __("Type", 'jigoshop');
	if( get_option('jigoshop_enable_sku', true) == 'yes' ) {
		$columns["product-type"] .= ' &amp; ' . __("SKU", 'jigoshop');
	}

	if ( get_option('jigoshop_manage_stock')=='yes' ) {
	 	$columns["stock"] = __("Stock", 'jigoshop');
	}

	$columns["price"] = __("Price", 'jigoshop');

	$columns["product-visibility"] = __("Visibility", 'jigoshop');

	$columns["product-date"] = __("Date", 'jigoshop');

	return $columns;
}

add_filter('manage_edit-product_columns', 'jigoshop_edit_product_columns');

function jigoshop_custom_product_columns($column) {
	global $post;
	$product = new jigoshop_product($post->ID);

	switch ($column) {
		case "thumb" :
			if( 'trash' != $post->post_status ) {
				echo '<a class="row-title" href="'.get_edit_post_link( $post->ID ).'">';
					echo jigoshop_get_product_thumbnail( 'admin_product_list' );
				echo '</a>';
			}
			else {
				echo jigoshop_get_product_thumbnail( 'admin_product_list' );
			}

		break;
		case "price":
			echo $product->get_price_html();
		break;
		case "featured" :
			$url = wp_nonce_url( admin_url('admin-ajax.php?action=jigoshop-feature-product&product_id=' . $post->ID) );
			echo '<a href="'.esc_url($url).'" title="'.__('Change','jigoshop') .'">';
			if ($product->is_featured()) echo '<a href="'.esc_url($url).'"><img src="'.jigoshop::assets_url().'/assets/images/success.gif" alt="yes" />';
			else echo '<img src="'.jigoshop::assets_url().'/assets/images/success-off.gif" alt="no" />';
			echo '</a>';
		break;
		case "stock" :
			if ( ! $product->is_type( 'grouped' ) && $product->is_in_stock() ) {
				if ( $product->managing_stock() ) {
					echo $product->stock.' '.__('In Stock', 'jigoshop');
				} else {
					echo __('In Stock', 'jigoshop');
				}
			} elseif ( $product->is_type( 'grouped' ) ) {
				echo __('Parent (no stock)', 'jigoshop');
			} else {
				echo '<strong class="attention">' . __('Out of Stock', 'jigoshop') . '</strong>';
			}
		break;
		case "product-type" :
			echo ucwords($product->product_type);
			echo '<br/>';
			if ( get_option('jigoshop_enable_sku', true) == 'yes' && $sku = get_post_meta( $post->ID, 'sku', true )) {
				echo $sku;
			}
			else {
				echo $post->ID;
			}
		break;
		case "product-date" :
			if ( '0000-00-00 00:00:00' == $post->post_date ) :
				$t_time = $h_time = __( 'Unpublished', 'jigoshop' );
				$time_diff = 0;
			else :
				$t_time = get_the_time( __( 'Y/m/d g:i:s A', 'jigoshop' ) );
				$m_time = $post->post_date;
				$time = get_post_time( 'G', true, $post );

				$time_diff = time() - $time;

				if ( $time_diff > 0 && $time_diff < 24*60*60 )
					$h_time = sprintf( __( '%s ago', 'jigoshop' ), human_time_diff( $time ) );
				else
					$h_time = mysql2date( __( 'Y/m/d', 'jigoshop' ), $m_time );
			endif;

			echo '<abbr title="' . esc_attr( $t_time ) . '">' . apply_filters( 'post_date_column_time', $h_time, $post ) . '</abbr><br />';

            if ( 'publish' == $post->post_status ) :
                _e( 'Published', 'jigoshop' );
            elseif ( 'future' == $post->post_status ) :
                if ( $time_diff > 0 ) :
                    echo '<strong class="attention">' . __( 'Missed schedule', 'jigoshop' ) . '</strong>';
                else :
                    _e( 'Scheduled', 'jigoshop' );
                endif;
            else :
                _e( 'Draft', 'jigoshop' );
            endif;
		break;

		case "product-visibility" :
			if ( $product->visibility ) :
				echo ($product->visibility == 'Hidden')
					? '<strong class="attention">'.ucfirst($product->visibility).'</strong>'
					: ucfirst($product->visibility);
			endif;
		break;

	}
}

// Enable sorting for custom columns

add_filter("manage_edit-product_sortable_columns", 'jigoshop_custom_product_sort');
function jigoshop_custom_product_sort( $columns ) {
	$custom = array(
		'featured'				=> 'featured',
		'price'					=> 'price',
		'product-visibility'	=> 'visibility',
		'product-date'			=> 'date'
	);
	return wp_parse_args($custom, $columns);
}

// Product column orderby

add_filter( 'request', 'jigoshop_custom_product_orderby' );

function jigoshop_custom_product_orderby( $vars ) {
	if (isset( $vars['orderby'] )) :
		if ( 'featured' == $vars['orderby'] ) :
			$vars = array_merge( $vars, array(
				'meta_key' 	=> 'featured',
				'orderby' 	=> 'meta_value'
			) );
		endif;
		if ( 'price' == $vars['orderby'] ) :
			$vars = array_merge( $vars, array(
				'meta_key' 	=> 'regular_price',
				'orderby' 	=> 'meta_value_num'
			) );
		endif;
		if ( 'visibility' == $vars['orderby'] ) :
			$vars = array_merge( $vars, array(
				'meta_key' 	=> 'visibility',
				'orderby' 	=> 'meta_value'
			) );
		endif;
	endif;

	return $vars;
}

/**
 * Filter products by category, uses slugs for option values.
 * Props to: Andrew Benbow - chromeorange.co.uk
 **/
add_action('restrict_manage_posts','jigoshop_products_by_category');
function jigoshop_products_by_category() {
	global $typenow, $wp_query;

    if ( $typenow=='product' )
		jigoshop_product_dropdown_categories();
}

/**
 * Filter products by type
 **/
add_action('restrict_manage_posts', 'jigoshop_filter_products_type');

function jigoshop_filter_products_type() {
    global $typenow, $wp_query;

    if ( $typenow != 'product' )
    	return false;

	// Get all active terms
	$terms = get_terms('product_type');

	echo "<select name='product_type' id='dropdown_product_type'>";
	echo "<option value='0'>" . __('Show all types', 'jigoshop') . "</option>";

	foreach($terms as $term) {
		echo "<option value='" . esc_attr( $term->slug ) . "' ".selected($term->slug, isset($wp_query->query['product_type']) ? $wp_query->query['product_type'] : '', false).">".esc_html( ucfirst($term->name) )." (".absint( $term->count ).")</option>";
	}

	echo "</select>";
}
// NOTE: This causes a large spike in queries, however they are cached so the performance hit is minimal ~20ms -Rob
add_action('manage_product_posts_custom_column', 'jigoshop_custom_product_columns', 2);

function jigoshop_edit_order_columns($columns) {

    $columns = array();

    //$columns["cb"] = "<input type=\"checkbox\" />";

    $columns["order_status"] = __("Status", 'jigoshop');

    $columns["order_title"] = __("Order", 'jigoshop');

    $columns["customer"] = __("Customer", 'jigoshop');
    $columns["billing_address"] = __("Billing Address", 'jigoshop');
    $columns["shipping_address"] = __("Shipping Address", 'jigoshop');

    $columns["billing_and_shipping"] = __("Billing & Shipping", 'jigoshop');

    $columns["total_cost"] = __("Order Cost", 'jigoshop');

    return $columns;
}

add_filter('manage_edit-shop_order_columns', 'jigoshop_edit_order_columns');

add_action('manage_shop_order_posts_custom_column', 'jigoshop_custom_order_columns', 2);
function jigoshop_custom_order_columns($column) {

    global $post;
    $order = new jigoshop_order($post->ID);
    switch ($column) {
        case "order_status" :

            echo sprintf( '<mark class="%s">%s</mark>', sanitize_title($order->status), __($order->status, 'jigoshop') );

            break;
        case "order_title" :

            echo '<a href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '">' . sprintf(__('Order #%s', 'jigoshop'), $post->ID) . '</a>';

            echo '<time title="' . date_i18n('c', strtotime($post->post_date)) . '">' . date_i18n('F j, Y, g:i a', strtotime($post->post_date)) . '</time>';

            break;
        case "customer" :

            if ($order->user_id)
                $user_info = get_userdata($order->user_id);
            ?>
            <dl>
                <dt><?php _e('User:', 'jigoshop'); ?></dt>
                <dd><?php
            if (isset($user_info) && $user_info) :

                echo '<a href="user-edit.php?user_id=' . $user_info->ID . '">#' . $user_info->ID . ' &ndash; <strong>';

                if ($user_info->first_name || $user_info->last_name)
                    echo $user_info->first_name . ' ' . $user_info->last_name;
                else
                    echo $user_info->display_name;

                echo '</strong></a>';

            else :
                _e('Guest', 'jigoshop');
            endif;
            ?></dd>
                <?php if ($order->billing_email) : ?><dt><?php _e('Billing Email:', 'jigoshop'); ?></dt>
                    <dd><a href="mailto:<?php echo $order->billing_email; ?>"><?php echo $order->billing_email; ?></a></dd><?php endif; ?>
                <?php if ($order->billing_phone) : ?><dt><?php _e('Billing Tel:', 'jigoshop'); ?></dt>
                    <dd><?php echo $order->billing_phone; ?></dd><?php endif; ?>
            </dl>
            <?php
            break;
        case "billing_address" :
            echo '<strong>' . $order->billing_first_name . ' ' . $order->billing_last_name;
            if ($order->billing_company)
                echo ', ' . $order->billing_company;
            echo '</strong><br/>';
            echo '<a target="_blank" href="http://maps.google.co.uk/maps?&q=' . urlencode($order->formatted_billing_address) . '&z=16">' . $order->formatted_billing_address . '</a>';
            break;
        case "shipping_address" :
            if ($order->formatted_shipping_address) :
                echo '<strong>' . $order->shipping_first_name . ' ' . $order->shipping_last_name;
                if ($order->shipping_company) : echo ', ' . $order->shipping_company;
                endif;
                echo '</strong><br/>';
                echo '<a target="_blank" href="http://maps.google.co.uk/maps?&q=' . urlencode($order->formatted_shipping_address) . '&z=16">' . $order->formatted_shipping_address . '</a>';
            else :
                echo '&ndash;';
            endif;
            break;
        case "billing_and_shipping" :
            ?>
            <dl>
                <dt><?php _e('Payment:', 'jigoshop'); ?></dt>
                <dd><?php echo $order->payment_method_title; ?></dd>
                <dt><?php _e('Shipping:', 'jigoshop'); ?></dt>
                <?php
                if ($order->shipping_service) :
                    ?>
                    <dd><?php echo sprintf( __('%s via %s', 'jigoshop'), $order->shipping_service, $order->shipping_method_title); ?></dd>
                    <?php
                else :
                    ?>
                    <dd><?php echo $order->shipping_method_title; ?></dd>
                <?php
                endif;
                ?>
            </dl>
            <?php
            break;
        case "total_cost" :
            ?>
            <table cellpadding="0" cellspacing="0" class="cost">
                <tr>
                    <?php if (get_option('jigoshop_calc_taxes') == 'yes' && $order->order_subtotal_inc_tax) : ?>
                        <th><?php _e('Retail Price', 'jigoshop'); ?></th>
                    <?php else : ?>
                        <th><?php _e('Subtotal', 'jigoshop'); ?></th>
                    <?php endif; ?>
                    <td><?php echo jigoshop_price($order->order_subtotal); ?></td>
                </tr>
                <?php
                if (get_option('jigoshop_calc_taxes') == 'yes' && $order->order_subtotal_inc_tax) :
                    if ($order->order_shipping > 0) :
                        ?><tr>
                            <th><?php _e('Shipping', 'jigoshop'); ?></th>
                            <td><?php echo jigoshop_price($order->order_shipping); ?></td>
                        </tr>
                        <?php
                    endif;
                    foreach ($order->get_tax_classes() as $tax_class) :
                        if ($order->tax_class_is_not_compound($tax_class)) :
                            ?>
                            <tr>
                                <th><?php echo $order->get_tax_class_for_display($tax_class) . ' (' . (float) $order->get_tax_rate($tax_class) . '%):'; ?></th>
                                <td><?php echo $order->get_tax_amount($tax_class) ?></td>
                            </tr>
                            <?php
                        endif;
                    endforeach;
                    ?><tr>
                        <th><?php _e('Subtotal', 'jigoshop'); ?></th>
                        <td><?php echo jigoshop_price($order->order_subtotal_inc_tax); ?></td>
                    </tr>
                    <?php
                else :
                    if ($order->order_shipping > 0) :
                        ?><tr>
                            <th><?php _e('Shipping', 'jigoshop'); ?></th>
                            <td><?php echo jigoshop_price($order->order_shipping); ?></td>
                        </tr>
                        <?php
                    endif;
                endif;
                if (get_option('jigoshop_calc_taxes') == 'yes') :
                    if ($order->order_subtotal_inc_tax) :
                        foreach ($order->get_tax_classes() as $tax_class) :
                            if (!$order->tax_class_is_not_compound($tax_class)) :
                                ?>

                                <tr>
                                    <th><?php echo $order->get_tax_class_for_display($tax_class) . ' (' . (float) $order->get_tax_rate($tax_class) . '%):'; ?></th>
                                    <td><?php echo $order->get_tax_amount($tax_class) ?></td>
                                </tr>
                                <?php
                            endif;
                        endforeach;
                    else :
                        foreach ($order->get_tax_classes() as $tax_class) :
                            ?>
                            <tr>
                                <th><?php echo $order->get_tax_class_for_display($tax_class) . ' (' . (float) $order->get_tax_rate($tax_class) . '%):'; ?></th>
                                <td><?php echo $order->get_tax_amount($tax_class) ?></td>
                            </tr>
                        <?php endforeach;
                    endif;
                endif;

                if ($order->order_discount > 0) : ?><tr>
                        <th><?php _e('Discount', 'jigoshop'); ?></th>
                        <td><?php echo jigoshop_price($order->order_discount); ?></td>
                    </tr><?php endif; ?>
                <tr>
                    <th><?php _e('Total', 'jigoshop'); ?></th>
                    <td><?php echo jigoshop_price($order->order_total); ?></td>
                </tr>
            </table>
            <?php
            break;
    }
}

/**
 * Search by SKU or ID for products.
 * Adapted from code by BenIrvin (Admin Search by ID)
 * Special Thanks to Esbjörn Eriksson (https://github.com/esbite) for this adaption
 */
add_action( 'parse_request', 'jigoshop_admin_product_search' );
add_filter( 'get_search_query', 'jigoshop_admin_product_search_label' );


function jigoshop_admin_product_search( $wp ) {
    global $pagenow, $wpdb;

    if( 'edit.php' != $pagenow )
        return false;

    if( ! isset( $wp->query_vars['s'] ) )
        return false;

    if ( ($wp->query_vars['post_type'] == 'product' || $wp->query_vars['post_type'] == 'shop_order') ) {

        if ( 'ID:' == substr( $wp->query_vars['s'], 0, 3 )) {

            $id = absint( substr( $wp->query_vars['s'], 3 ) );

            if( ! $id )
                return false;

            unset( $wp->query_vars['s'] );
            $wp->query_vars['p'] = $id;
        }
        elseif( $wp->query_vars['post_type'] == 'product' && 'SKU:' == substr( $wp->query_vars['s'], 0, 4 ) ) {

            $sku = trim( substr( $wp->query_vars['s'], 4 ) );

            if( ! $sku )
                return false;

            $id = $wpdb->get_var('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key="sku" AND meta_value LIKE "%'.$sku.'%";');

            if( ! $id )
                return false;

            unset( $wp->query_vars['s'] );
            $wp->query_vars['p'] = $id;
            $wp->query_vars['sku'] = $sku;

        }
        elseif( $wp->query_vars['post_type'] == 'shop_order' && 'PID:' == substr( $wp->query_vars['s'], 0, 4 ) ) {

            $id = absint( substr( $wp->query_vars['s'], 4 ) );

            $id_length = strlen($id);

            // Get candidate orders
            $query = "'%s:2:\"id\";s:$id_length:\"$id\"%'";

            $results = $wpdb->get_results(
                    "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'order_items' AND meta_value LIKE $query"
            );

            // Verify orders contain this product id
            $ids = array();
            foreach ( $results as $result ) {
                $items = unserialize($result->meta_value);
                foreach ( $items as $item ) {
                    if ( $item['id'] == $id ) {
                        $ids[] = $result->post_id;
                        break;
                    }
                }
            }

            // Set search parameters
            unset( $wp->query_vars['s'] );
            $wp->query_vars['post__in'] = $ids;
            $wp->query_vars['order_product_id'] = $id;

        }
    }

}

function jigoshop_admin_product_search_label($query) {
    global $pagenow, $typenow, $wp;

    if ( 'edit.php' != $pagenow )
        return $query;

    if ( $typenow == 'product' || $typenow == 'shop_order' ) {

        $s = get_query_var( 's' );
        if ( $s )
            return $query;

        $sku = get_query_var( 'sku' );
        if($sku) {
            $post_type = get_post_type_object($wp->query_vars['post_type']);
            return sprintf(__("[%s with SKU of %s]", 'jigoshop'), $post_type->labels->singular_name, $sku);
        }

        $p = get_query_var( 'p' );
        if ($p) {
            $post_type = get_post_type_object($wp->query_vars['post_type']);
            return sprintf(__("[%s with ID of %d]", 'jigoshop'), $post_type->labels->singular_name, $p);
        }

        $order_id = get_query_var( 'order_product_id' );
        if (get_query_var( 'post__in' )) {
            $post_type = get_post_type_object($wp->query_vars['post_type']);
            return sprintf(__("[%s with product ID of %d]", 'jigoshop'), $post_type->labels->singular_name, $order_id);
        }
    }

    return $query;
}


/**
 * Order page filters
 * */
function jigoshop_custom_order_views($views) {

    $jigoshop_orders = new jigoshop_orders();

    $pending = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'pending') ? 'current' : '';
    $onhold = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'on-hold') ? 'current' : '';
    $processing = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'processing') ? 'current' : '';
    $completed = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'completed') ? 'current' : '';
    $cancelled = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'cancelled') ? 'current' : '';
    $refunded = (isset($_GET['shop_order_status']) && $_GET['shop_order_status'] == 'refunded') ? 'current' : '';

    $views['pending'] = '<a class="' . esc_attr( $pending ) . '" href="?post_type=shop_order&amp;shop_order_status=pending">' . __('Pending', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->pending_count . ')</span></a>';
    $views['onhold'] = '<a class="' . esc_attr( $onhold ) . '" href="?post_type=shop_order&amp;shop_order_status=on-hold">' . __('On-Hold', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->on_hold_count . ')</span></a>';
    $views['processing'] = '<a class="' . esc_attr( $processing ) . '" href="?post_type=shop_order&amp;shop_order_status=processing">' . __('Processing', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->processing_count . ')</span></a>';
    $views['completed'] = '<a class="' . esc_attr( $completed ) . '" href="?post_type=shop_order&amp;shop_order_status=completed">' . __('Completed', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->completed_count . ')</span></a>';
    $views['cancelled'] = '<a class="' . esc_attr( $cancelled ) . '" href="?post_type=shop_order&amp;shop_order_status=cancelled">' . __('Cancelled', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->cancelled_count . ')</span></a>';
    $views['refunded'] = '<a class="' . esc_attr( $refunded ) . '" href="?post_type=shop_order&amp;shop_order_status=refunded">' . __('Refunded', 'jigoshop') . ' <span class="count">(' . $jigoshop_orders->refunded_count . ')</span></a>';

    if ($pending || $onhold || $processing || $completed || $cancelled || $refunded) :

        $views['all'] = str_replace('current', '', $views['all']);

    endif;

    unset($views['publish']);

    if (isset($views['trash'])) :
        $trash = $views['trash'];
        unset($views['draft']);
        unset($views['trash']);
        $views['trash'] = $trash;
    endif;

    return $views;
}

add_filter('views_edit-shop_order', 'jigoshop_custom_order_views');

/**
 * Order page actions
 * */
function jigoshop_remove_row_actions($actions) {
    if (get_post_type() === 'shop_order') :
        unset($actions['view']);
        unset($actions['inline hide-if-no-js']);
    endif;
    return $actions;
}

add_filter('post_row_actions', 'jigoshop_remove_row_actions', 10, 1);

/**
 * Order page views
 * */
function jigoshop_bulk_actions($actions) {
    return array();
}

add_filter('bulk_actions-edit-shop_order', 'jigoshop_bulk_actions');

/**
 * Adds downloadable product support for thickbox
 * @todo: not sure if this is the best place for this?
 */
function jigoshop_media_upload_downloadable_product() {
	do_action('media_upload_file');
}

add_action('media_upload_downloadable_product', 'jigoshop_media_upload_downloadable_product');

/**
 * Order messages
 * */
function jigoshop_post_updated_messages($messages) {
    if (get_post_type() === 'shop_order') :

        $messages['post'][1] = sprintf(__('Order updated.', 'jigoshop'));
        $messages['post'][4] = sprintf(__('Order updated.', 'jigoshop'));
        $messages['post'][6] = sprintf(__('Order published.', 'jigoshop'));

        $messages['post'][8] = sprintf(__('Order submitted.', 'jigoshop'));
        $messages['post'][10] = sprintf(__('Order draft updated.', 'jigoshop'));

    endif;
    return $messages;
}
add_filter( 'post_updated_messages', 'jigoshop_post_updated_messages' );
