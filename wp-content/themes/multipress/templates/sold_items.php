<?php
	$args = array(
		//'posts_per_page' => $nr_hot_posts,
		'post_status' => 'publish' ,
		//'meta_key' => 'hot_date' ,
		'post_type' => 'any',
		//'orderby' => 'meta_value_num' ,
		'meta_query' => array(
				array(
					'key' => 'nr_items_sold' ,
					'value' => 1 ,
					'compare' => '>=' ,
					'type' => 'numeric',
				) ),
		'order' => 'DESC'
	);
	
	$wp_query = new WP_Query( $args );
	
	/*ADD pagination*/
	
//var_dump($wp_query);
	
	if( $wp_query -> have_posts() ){
		$currency = _core::method('_settings','get','settings','payment','paypal','currency' );
?>
		<table class="table t_subscript" id="checkout">
			<thead>
				<tr>
					<th class=""><?php _e('Product',_DEV_); ?></th>
					<th class="" style="width: 10%;"><?php _e('Price',_DEV_); ?></th>
					<th class="" style="width: 10%;"><?php _e('Quantity',_DEV_); ?></th>
					<th class="last" style="width: 10%;"><?php _e('Action',_DEV_); ?></th>
				</tr>
			</thead>
			<tbody>
<?php		
		foreach( $wp_query -> posts as $post ){
			$wp_query -> the_post();
			//echo the_title();
?>
			<tr class="elements">
				<td>
					<span class="license"><?php echo the_title(); ?> </span>
				</td>
				<td >
					<?php 
						$price=_core::method('_meta','get',$post->ID,'register','value');
						echo _cart::get_currency_symbol($currency).' '.$price; 
					?>
				</td>
				<td>
					<span><?php echo _core::method('_meta','get',$post->ID,'nr_items_sold'); ?></span>
				</td>
				<td class="last">
					<?php
						/*tr_d - means transactions details*/
						$invoices_link = '<a href="'.add_query_arg( "tr_d", $post->ID ).'" > '.__("Check details",_DEV_).' </a>';
						echo $invoices_link;
					?>
				</td>
			</tr>
<?php			
		}
?>
			</tbody>
		</table>
<?php		
	}else{
?>
		<h2><?php _e('No result found',_DEV_); ?></h2>
<?php		
	}
?>